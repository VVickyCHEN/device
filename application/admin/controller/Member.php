<?php

namespace app\admin\controller;

use app\admin\model\Member as MMember;
use app\common\controller\Adminbase;
use think\Db;
use think\db\Where;
use think\image;

/**
 * 会员管理 
 */
class Member extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->Member = new MMember;
    }

    public function isexist(){
      $email = input('email');

      if(input('id')){

        $isexist = Db::name('user')->where(['email'=>$email])->where('id','not in',input('id'))->find();
      }else{
        $isexist = Db::name('user')->where(['email'=>$email])->find();
      }
      
      empty($isexist)?$res=0:$res=1;
      return $res;
    }

    public $show_page = 20;
    /**
     * 会员列表
     */
    public function index()
    {
         $where = new Where();
         $query = [];
        //搜索
        if(!empty(input('search'))){
            $where['email'] = [ 'like',"%".input('search')."%" ];
            $query['search'] = input('search');
        }
        $res = Db::name("user")->order(array('id' => 'desc'))->where($where)->paginate($this->show_page,false,['query'=>$query]);
        // $res = Db::name("user")->order(array('id' => 'ASC'))->where($where)->select();

        $page = $res->render();
        $result = $res->all();

        $this->assign(["Userlist"=>$result,'page'=>$page]);

        return $this->fetch();
          
    }

    /**
     * 会员编辑
     */
    public function edit()
    {
        if ($this->request->isPost()) {
          $data = input();

          $img = request()->file();

          $res = ['msg'=>lang('edit failure'),'code'=>0];
          $id = $data['id'];
          $origin_img = MMember::where('id',$id)->value('head_img');

          if($origin_img !== $data['origin_img']){

            if(!empty($img['head_img'])){  
              $head_img = $this->uploads($img['head_img'],$id,['head_img','head_img_thumb']); 
              $data['head_img'] = $head_img;
              $point_start = strpos($head_img, '.');
              $head_img_thumb = substr($head_img, 0,$point_start).'min'.substr($head_img,$point_start);

              // 缩略图
              $image = \think\Image::open(ROOT_PATH.'public/uploads/'.$head_img);
              $image->thumb(500,500)
              ->save(ROOT_PATH.'public/uploads/'.$head_img_thumb);

              $data['head_img_thumb'] = $head_img_thumb;

            }else{     
                $res['msg'] = ":lang('picture must')";
                return $res;
            }

          }else{
            $data['head_img'] = $data['origin_img'];
            unset($data['origin_img']);
          }

          $result = $this->Member->save($data,['id'=>$id]);
          if($result){
            $res['code'] = 1;
            $res['msg'] = lang('edit success');
            $res['url'] = url('Member/index');
            return $res;
          }else{
            return $res;
          }
        }else{
            $id = $this->request->param('id');
            $data = Db::name('user')->where(array("id" => $id))->find();
            if (empty($data)) {
                $this->error('该会员信息有误！');
            }

            $this->assign("data", $data);            
            return $this->fetch();
        }
    }

    public function add(){

        if( $this->request->isPost() ){
            $data = input();

            $img = request()->file();

            $res = ['msg'=>lang('add failure'),'code'=>0];

            if(!empty($img['head_img'])){  
              $head_img = $this->uploads($img['head_img']); 
              $data['head_img'] = $head_img;
              $point_start = strpos($head_img, '.');
              $head_img_thumb = substr($head_img, 0,$point_start).'min'.substr($head_img,$point_start);

              // 缩略图
              $image = \think\Image::open(ROOT_PATH.'public/uploads/'.$head_img);
              $image->thumb(500,500)
              ->save(ROOT_PATH.'public/uploads/'.$head_img_thumb);

              $data['head_img_thumb'] = $head_img_thumb;

            }else{     
                $res['msg'] = lang('picture must');
                return $res;
            }
            
            $result = $this->Member->create($data);
            if($result){
              $res['code'] = 1;
              $res['msg'] = lang('add success');
              $res['url'] = url('Member/index');
              return $res;
            }else{
              return $res;
            }
        }else{
            return $this->fetch();
        }
    }

    // 上传
    private  function uploads($file,$id='',$fields=''){

      if(!empty($id)){
        if(!is_array($fields)){
          $find = Db::name('user')->where(array('id'=>$id))->value($fields);
          if($find) {          
               $lujing = ROOT_PATH.'public/uploads/'.$find;
               if(file_exists($lujing)){
                  unlink($lujing);
               }
          }
        }else{
          foreach ($fields as $key) {
            $find = Db::name('user')->where(array('id'=>$id))->value($key);
            if($find) {          
                 $lujing = ROOT_PATH.'public/uploads/'.$find;
                 if(file_exists($lujing)){
                    unlink($lujing);
                 }
            }
          }
        }
        
      }

      if($file){

          $info = $file->move(ROOT_PATH . 'public/'. 'uploads');

          if($info){
              return $info->getSaveName();
          }else{
              // 上传失败获取错误信息
              return 1;
          }
      }
    }


    /**
     * 会员删除
     */
    public function del()
    {
        $res = ['msg'=>lang('delete failure'),'code'=>0];
        $id = $this->request->param('id');

        $res = $this->uploads('',$id,['head_img','head_img_thumb']);
        if (Db::name('user')->where('id',$id)->delete()){
            $res['msg'] = lang('delete success');
            $res['code'] = 1;
            return $res;
        } else {
            return $res;
            
        }
    }

}
