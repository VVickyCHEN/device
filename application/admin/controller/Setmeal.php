<?php

// +----------------------------------------------------------------------
// | 后台订单管理
// +----------------------------------------------------------------------
namespace app\admin\controller;


use app\common\controller\Adminbase;
use think\Db;
use think\db\Where;
class Setmeal extends Adminbase
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
            $where['sname'] = [ 'like',"%".input('search')."%" ];
            $query['search'] = input('search');
        }
		$setmeal = Db::name("set_meal")->where($where)->select();
    	// if(Request()->isPost()){
           
    	// }
    	// dump($setmeal);
        $this->assign("data", $setmeal);
        return $this->fetch();
    }

 
    /**
     * 套餐添加
     */
   public function add(){

        if ($this->request->isPost()) {
          $data = input();
          if(empty($data['sname'])) {$this->error('套餐名不能为空');} 
          $file = request()->file('file');
          // $file ? $data['img_url'] = $this->uploads($file):$this->error('请上传套餐封面');
          if($file){
              $data['img_url'] = $this->uploads($file);
          }else{
             $data['img_url'] = null;
             unset($data['file']);
          }
          // $data['addtime'] = time();
          // dump($data);exit;
          $res = db('set_meal')->insert($data);
          if($res){
              $this->success('添加套餐成功',url('Setmeal/index'));
          }else{
              $this->error('添加套餐失败');
          }
        }else{
          // $set_meal = Db::name("set_meal")->select();
          // // dump($goods_class);
          // $this->assign("set_meal", $set_meal);
          return $this->fetch();
        }

   }
    
    /**
     * 套餐添加
     */
   public function edit(){
      if ($this->request->isPost()){
           $data = input();
           // dump($data);
           if(empty($data['sname'])) {$this->error('套餐名不能为空');} 
           if(!isset($data['file'])){          
                $data['img_url'] = $this->uploads(request()->file('file'),$data['id'],'img_url');            
            }else{
              if ($data['img_url']){
                 $data['img_url'] = $data['img_url'];
                 unset($data['file']);
              }else{
                $this->error('请选择套餐封面');
              }
           }
           // dump($data);exit;
           $res = Db::name('set_meal')->where('id',$data['id'])->update($data); 
           if($res!==false){
              $this->success('修改成功',url('setmeal/index'));
           }else{
            $this->error('修改失败');
           } 


      }else{
        $id = input('id');
        if(!$id){$this->error('该数据信息有误');}
        $getone = Db::name("set_meal")->where('id',$id)->find();
        $this->assign("getone", $getone);
        return $this->fetch();
     }
  }

   // 上传图片
      private  function uploads($file,$id='',$fields=''){
        // dump($_FILES);
           //优化操作删除服务器冗余图片
            if($id){
                $datas = Db::name('set_meal')->field($fields)->where(["id" => $id])->find();              
                if($datas[$fields]) {
                 $lujing = ROOT_PATH.'public/uploads/'.$datas[$fields];
                 if(file_exists($lujing)){
                    unlink($lujing);
                 }
                }
            }


            // 上传图片开始，成功返回图片信息
            if($file){
                $info = $file->move(ROOT_PATH . 'public/uploads');
                if($info){
                    return $info->getSaveName();
                }else{
                    // 上传失败获取错误信息
                    return 1;
                }
            }
         
          
        }







}