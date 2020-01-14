<?php

// +----------------------------------------------------------------------
// | banner管理
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\common\controller\Adminbase;
use think\Db;
use think\Paginator;
use think\Request;

class Banner extends Adminbase
{
    
    //轮播图首页
    public function index(){
    
        $res = Db::name('banner')->order('sort desc')->select();
        // dump($res);
        $this->assign('data',$res);    

        return $this->fetch();
    }

     public function add(){
       
      if ($this->request->isPost()) {
        $data = input();
        if (!$data['img_url']) {
          $this->error('图片不能为空');
        }
        
        if (Db::name('banner')->insert($data)) {
            $this->success("添加成功！", url("banner/index"));
        } else {
            $this->error('添加失败！');
        }

      }
      
        return $this->fetch();
    }

    public function edit(){
       $data = $this->request->param(); 
       $id = $data['id'];

        if ($this->request->isPost()) {
          $data = input();
          if (!$data['img_url']) {
          $this->error('图片不能为空');
        }
            // dump($data);exit;
          if (Db::name('banner')->where('id',$data['id'])->update($data)) {
              $this->success("编辑成功！", url("banner/index"));
          } else {
              $this->error('编辑失败！');
          }

        }

       $res = Db::name('banner')->where('id',$id)->find();


       $this->assign('data',$res);
       return $this->fetch();

   }

    //简单图片上传
    public function upload(){
      $file = request()->file('file');
      // dump($file);exit;
      $info = $file->move(ROOT_PATH.'public/'.'banner');//图片上传的位置

      if($info){
        Result(0,'上传成功',$info->getSaveName());
      }else{
         Result(1,'上传失败',$file->getError());
      }
  }
 //列表修改状态
    public function status_edit(){     
       if ($this->request->isPost()){
        $data = input();
        if($data){
          $das['status'] = $data['status'];
          ($data['status']==0)?$newmsg = '显示':$newmsg = '隐藏';
          $res = Db::name('banner')->where('id', $data['id'])->update($das);
          $res?Result(0,$newmsg.'成功'):Result(1,$newmsg.'失败'); 
       }
     }

    }



//列表单字段修改
    public function singlefield_edit()
    {
        if(!$this->request->isPost()){
            Result(1,'请求错误！'); 
        }
        $receive = $this->request->param();
        $data[$receive['field']] = $receive['value'];
        if(Db::name('banner')->where('id', $receive['id'])->update($data)){
             Result(0,'排序成功'); 
        }else{
            Result(1,'排序失败了！'); 
        }

    }



    /**
     * 删除
     */
    public function delete()
    {
        $data = input();
        // dump($data);exit;
        if (empty($data['id'])) {
            Result(1,'ID错误');  
        }
        //顺带删除服务器多余图片
        $find = db('banner')->where(array('id'=>$data['id']))->value('img_url');
        if($find) {          
             $lujing = ROOT_PATH.'public/banner/'.$find;
             if(file_exists($lujing)){
                unlink($lujing);
             }
        }
        

       // exit;
        if (Db::name('banner')->where(["id" =>$data['id']])->delete()) {
            // $this->success("删除人员成功！");
            Result(0,'删除人员成功！');  
        } else {
            Result(1,'删除失败！');  
            // $this->error("删除失败！");
        }
    }














}
