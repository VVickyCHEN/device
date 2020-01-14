<?php

// +----------------------------------------------------------------------
// | 后台商品管理
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\AdminUser;
use app\common\controller\Adminbase;
use think\Db;
use think\db\Where;

class Goods extends Adminbase
{

	/**
     * 商品列表
     */
    public function index(){
         $where = new Where();
         $query = [];
        //搜索
        if(!empty(input('search'))){
            $where['g.title'] = [ 'like',"%".input('search')."%" ];
            $query['search'] = input('search');
        }
        // $User = Db::name("member")->order(array('id' => 'ASC'))->where($where)->paginate($this->show_page,false,['query'=>$query]);
        $goods = Db::name("goods")->alias('g')->field('g.*,gc.name as gcname')->join('goods_class gc','g.cid = gc.id')->order('g.cid asc,g.addtime desc')->where($where)->select();
        
        // foreach ($goods as $key => $value) {
        //    if(strtotime($value['endtime']) > time()){
        //        $goods[$key]['status'] = '正常';
        //    }else{
        //         $goods[$key]['status'] = '已结束';
        //    }
        // }
        // dump($goods);
        $this->assign("goodlist", $goods);

        return $this->fetch();
          
    }

    /**
     * 商品分类列表
     */
    public function goodsclass(){
        $goodclass = Db::name("goods_class")->select();
        // dump($goodclass);
        $this->assign("goodclass", $goodclass);
        return $this->fetch();
    }
     // 添加商品分类
    public function addcate(){
        $datas = input();
         if($datas){
            $data['name'] = $datas['name'];
            if (!$data['name']){Result(1,'分类名不能为空'); }
            // dump($data);exit;
            $res = Db::name('goods_class')->insert($data);
            if ($res) {
               Result(0,'添加成功');
            }else{
               Result(1,'添加失败'); 
            }
         }else{
            Result(1,'获取信息失败');
         }
     }

      //列表单字段修改
    public function singlefield_edit()
    {
        if(!$this->request->isPost()){
            Result(1,'请求错误！'); 
        }
        $receive = $this->request->param();
        // dump($receive);exit;
        $data[$receive['field']] = $receive['value'];
        if(Db::name('goods_class')->where('id', $receive['id'])->update($data)){
             Result(0,'单字段编辑成功'); 
        }else{
            Result(1,'编辑失败了！'); 
        }

    }
   /**
     * 商品添加
     */
   public function add(){

        if ($this->request->isPost()) {
          $data = input();
          if(empty($data['title'])) {$this->error('商品名不能为空');} 
          $file = request()->file('file');
          // $file ? $data['img_url'] = $this->uploads($file):$this->error('请上传商品封面');
          if($file){
              $data['img_url'] = $this->uploads($file);
          }else{
             $data['img_url'] = null;
             unset($data['file']);
          }
          $data['addtime'] = time();
          // dump($data);exit;
          $res = db('goods')->insert($data);
          if($res){
              $this->success('添加商品成功',url('Goods/index'));
          }else{
              $this->error('添加商品失败');
          }
        }else{
          $goods_class = Db::name("goods_class")->select();
          // dump($goods_class);
          $this->assign("goods_class", $goods_class);
          return $this->fetch();
        }

   }

   /**
     * 商品添加
     */
   public function edit(){
      if ($this->request->isPost()){
           $data = input();
           if(empty($data['title'])) {$this->error('商品名不能为空');} 
           if(!isset($data['file'])){          
                $data['img_url'] = $this->uploads(request()->file('file'),$data['id'],'img_url');            
            }else{
              if ($data['img_url']){
                 $data['img_url'] = $data['img_url'];
                 unset($data['file']);
              }else{
                $this->error('请选择商品封面');
              }
           }
           // dump($data);exit;
           $res = Db::name('goods')->where('id',$data['id'])->update($data); 
           if($res!==false){
              $this->success('修改成功',url('Goods/index'));
           }else{
            $this->error('修改失败');
           } 


      }else{
        $id = input('id');
        if(!$id){$this->error('该数据信息有误');}
        $goods_class = Db::name("goods_class")->select();
        $getone = Db::name("goods")->where('id',$id)->find();
        $this->assign("getone", $getone);
        $this->assign("goods_class", $goods_class);
        return $this->fetch();
     }
  }


   // 上传图片
      private  function uploads($file,$id='',$fields=''){
        // dump($_FILES);
           //优化操作删除服务器冗余图片
            if($id){
                $datas = Db::name('goods')->field($fields)->where(["id" => $id])->find();              
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