<?php

// +----------------------------------------------------------------------
// | 后台订单管理
// +----------------------------------------------------------------------
namespace app\admin\controller;


use app\common\controller\Adminbase;
use think\Db;
use think\db\Where;
class Order extends Adminbase
{

	// protected function initialize()
 //    {
 //        parent::initialize();
 //        $this->AdminOrder = new AdminOrder;
 //    }

    // 订单列表
	public function index(){
    	$where = new Where();
        $query = [];
        //搜索
        if(!empty(input('search'))){
            $where['o.order_number'] = [ 'like',"%".input('search')."%" ];
            $query['search'] = input('search');
        }
        $id = input('id');
        if(empty($id)){
          $status = 0;//默认第一个
        }else{
          $status = $id-1;
        }
		    $order = Db::name("order")->alias('o')->field('o.*,m.phone as mname')->join('member m','o.memberid = m.id')->where($where)->where('o.status',$status)->order('o.addtime desc')->select();


        // dump($status);
        // dump($order);
        $this->assign("data", $order);
        $this->assign("status", $status);
        return $this->fetch();
    }

 
   //生成订单号(唯一)
   private function generate_order_number($prefix=''){
    return $prefix.'_'.time().genRandomString(12);
   }



   public function order_number(){
     echo '唯一订单号生成1：'.$this->generate_order_number('HT');
   }
  


   //查看审核订单
   public function edit(){
     if($this->request->isPost()) {
         $data = input();

         // dump($data);
         if($data['status']=='0' || $data['status']=='1' || $data['status']=='2' || $data['status']=='3' || $data['status']=='4'){
           // dump(111);exit;

           $res = db('order')->where('id',$data['id'])->update(['status'=>$data['status']]);
           if($res){
             $this->success('更新成功',url('Order/index',['id'=> $data['status']+1]));
           }else{
             $this->error('更新失败');
           }
         }else{
           $this->error('参数信息有误');
         }     

        }else{
            $id = $this->request->param('id/d');
         
            $getone = db('order')->where('id',$id)->find();
            // dump($getone);
            $this->assign("data", $getone);                     
            return $this->fetch();
        }

   }







}