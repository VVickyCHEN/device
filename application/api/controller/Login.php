<?php

// +----------------------------------------------------------------------
// | 登录接口
// | creator ：lichengyi
// | time：2019/7/13
// +----------------------------------------------------------------------
namespace app\api\controller;
use think\Controller;
use think\Db;
use think\Validate;


//没登录无token时候使用的接口;
class Login extends controller{

  //会员注册
    public function register(){
       $data = input();
       if(empty($data['phone']) || empty($data['password'])){Result(1,'参数传递不正确');}
       $this->checkdates($data);//校检数据  
       $data['password'] = md5($data['password']);     
       $newvalidate = Validate::make(['phone'  => 'unique:member']);//验证唯一手机号
       $resultss = $newvalidate->check($data);
       if(!$resultss){
          Result(1,'手机号已存在');
       }
       $validate = Validate::make(['invite_code'  => 'unique:member','member_number' => 'unique:member']);//验证唯一      
       $shoji = $newvalidate->check($data['phone']);
       $data['invite_code'] = genRandomString(10);//需要多少位就输入多少数值
       $data['member_number'] = 'HT'.rand(100,999).'_'.genRandomString(8);//会员编号
       $result = $validate->check($data);
       if(!$result){
          Result(1,'目前注册过多，请再次点击注册');
       }
       $data['addtime'] = time();
       //有邀请码的时候处理
       if(strlen($data['code'])==10){
          $getone = db('member')->where('invite_code',$data['code'])->find();
          $newcode = $getone['invite_code'];
       }
       unset($data['code']);
       if(isset($newcode) && $newcode!=''){
           echo '有正确邀请码';exit;
           //三级分销式加积分
            $twoget = db('member')->where('id',$getone['pid'])->find();
            if($twoget){
              $threeget = db('member')->where('id',$twoget['cid'])->find();
            }
           


           //三级end


            //开启事务绑定账号关联
            Db::startTrans();
            try{
              $getid = db('member')->insertGetId($data);//会员注册
              //查码上
              if($getid){
                $das['invite_code'] = $newcode;
                $das['mid'] = $getid;
                $das['integral'] = 30;//邀请人加的积分
                $das['addtime'] = time();

                $invita = db('invitation')->insert($das);
                if($invita){
                  $res = db('member')->where('invite_code',$newcode)->setInc('integral',$das['integral']);
                }
              }            

              // model('Member')->saveAll([[],['id'=>1,PONEY=>2]]);
               // 提交事务
                Db::commit();    
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            !empty($res) ? Result(0,'注册成功') : Result(1,'注册失败');
       }else{
         // 无邀请码注册
         $res = db('member')->insert($data);
         if($res){
           Result(0,'注册成功');
         }else{
           Result(1,'注册失败');
         }
       }

      
    }

    //短信验证码接口
    public function getsmscode(){
        $data = input();
        if(empty($data['phone'] || !check_phone($data['phone']))){
          Result(1,'手机号格式不对');
        }
        //给手机号发送验证码
        $code = rand(100000,999999);
        // smsSend($data['phone'],$code);
        if($code){
          Result(0,'验证码发送成功');
        }else{
          Result(1,'发送失败');
        }

    }
    //会员登录
    public function login(){
        $data = input();
        if(empty($data['phone'] || !check_phone($data['phone']))){
          Result(1,'账号格式不对');
        }
        $res = Db::name('member')->field('phone,password')->where('phone',$data['phone'])->find();
        if($res){
          if(md5($data['password']) == $res['password']){
            Result(0,'登录成功');
          }else{
            Result(1,'密码错误');
          }
        }else{
          Result(1,'账号不存在');
        }
    }

   





    //注册信息验证器
    private function checkdates($data){
       if(!check_phone($data['phone'])){
         Result(1,'手机号格式不对');
       }
       if((strlen($data['password']) < 3) || (strlen($data['password']) >15)){
         Result(1,'密码长度应为3-15位字符');
       }
    }










}
