<?php

namespace app\admin\controller;

use app\admin\model\AdminUser;
use app\common\controller\Adminbase;
use think\Db;
use think\db\Where;

/**
 * 管理员管理
 */
class Manager extends Adminbase
{
    // protected function initialize()
    // {
    //     parent::initialize();
    //     $this->AdminUser = new AdminUser;
    // }
    public $show_page = 20;
    /**
     * 管理员管理列表
     */
    public function index()
    {
         $where = new Where();
         $query = [];
        //搜索
        if(!empty(input('search'))){
            $where['username|phone'] = [ 'like',"%".input('search')."%" ];
            $query['search'] = input('search');
        }
        $User = Db::name("admin")->order(array('userid' => 'ASC'))->where($where)->paginate($this->show_page,false,['query'=>$query]);
        $this->assign("Userlist", $User);

        return $this->fetch();
          
    }

 
//列表修改状态
    public function status_edit(){     
       if ($this->request->isPost()){
            $data = input();
            if($data){
              $das['ispower'] = $data['ispower'];
              ($data['ispower']==1)?$newmsg = '打开':$newmsg = '关闭';
              $res = Db::name('admin')->where('userid', $data['userid'])->update($das);
              $res?Result(0,$newmsg.'成功'):Result(1,$newmsg.'失败'); 
           }
        }

    }
    /**
     * 添加管理员
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post('');
            // dump($data);exit;
            $result = $this->validate($data, 'AdminUser.insert');

            if ($result !== true) {
                return $this->error($result);
            }
            if(!check_phone($data['phone'])){
              return $this->error('手机号格式不正确');
            }
            
            unset($data['password_confirm']);
            $data['roleid'] = $data['roleid'];
            $data['password'] = md5($data['password']);
            
            // dump($data);exit;
            if ($res = Db::name('admin')->insert($data)) {
                $this->success("添加用户成功！", url('admin/manager/index'));
            } else {
                // $error = Db::name('admin')->getError();
                $this->error('添加失败！');
            }

        } else {
            $this->assign("roles", model('admin/AuthGroup')->getGroups());
            return $this->fetch();
        }
    }


    /**
     * 管理员编辑
     */
    public function edit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post('');
            // dump($data);exit;
            $result = $this->validate($data, 'AdminUser.update');
            if ($result !== true) {
                return $this->error($result);
            }
            if($data['password']){
                $data['password'] = md5($data['password']);
            }else{
                 unset($data['password']);
            }
            if(!check_phone($data['phone'])){
              return $this->error('手机号格式不正确');
            }
            unset($data['password_confirm']);
            // dump($data);exit;
            $getupdate = Db::name('admin')->update($data);
            if ($getupdate !== false) {
                $this->success("修改成功！", url('admin/manager/index'));
            } else {
                $this->error('修改失败！');
            }
        }else{
            $id = $this->request->param('id/d');
            $data = Db::name('admin')->where(array("userid" => $id))->find();
            if (empty($data)) {
                $this->error('该信息不存在！');
            }

            $this->assign("data", $data);            
            $this->assign("roles", model('admin/AuthGroup')->getGroups());
            return $this->fetch();
        }
    }

    /**
     * 个人账号编辑
     */
    // public function myedit(){
    //     $Onuserinfo = $this->_userinfo;
    //     if ($this->request->isPost()) {
    //         $data = $this->request->post('');
    //         $result = $this->validate($data, 'AdminUser.update');
    //         if ($result !== true) {
    //             return $this->error($result);
    //         }
    //         if($data['password']){
    //             $data['password'] = md5($data['password']);
    //         }else{
    //              unset($data['password']);
    //         }
            
    //         unset($data['password_confirm']);
    //         unset($data['nickname']);
    //         // dump($data);exit;
    //         if (Db::name('admin')->update($data)) {
    //             $this->success("修改成功！", url('admin/main/index'));
    //         } else {
    //             $this->error('修改失败！');
    //         }
    //         // dump(input());
    //         // exit;

    //      }else{
    //         $id = $this->request->param('id/d');
    //          //判断是否是当前个人信息
    //         // echo $id;
    //         // dump($Onuserinfo);exit;
    //         if($Onuserinfo['ispower'] != 1) {
    //            if ($Onuserinfo['userid'] != $id) {
    //             $this->error('修改信息与当前登录信息不匹配');
    //           }
    //         }

    //         $data = Db::name('admin')->where(array("userid" => $id))->find();
    //         if (empty($data)) {
    //             $this->error('该信息不存在！');
    //         }

    //         $frame = Db::name('frame')->field('id,name,pid')->select();
    //         $data['frame'] = !empty($data['companyid'])?substr($this->getPid($frame,$data['companyid']),0,-2):null;//查找组织架构信息
    //         // dump($data);

    //         $this->assign("data", $data);            
    //         $this->assign("roles", model('admin/AuthGroup')->getGroups());
    //         return $this->fetch();
    //      }    
    // }

     /**
     * 管理员禁用启用
     */
    public function bankai()
    {
        $allurl = input('param.');
        // dump($allurl);
        if($allurl['status']==1){
          $data['status'] = 0;
            if (Db::name('admin')->where('userid',$allurl['id'])->update($data)){
                $this->success("禁用成功！");
            } else {
                $this->error('操作失败！');
            }           
          
        }else{
            // $data['status'] = 1;
            $this->error('已经禁用！');
        }
        
    }

    /**
     * 管理员删除
     */
    public function del()
    {
        $id = $this->request->param('id/d');
        if (Db::name('admin')->where('userid',$id)->delete()){
            $this->success("删除成功！");
        } else {
            $this->error(Db::name('admin')->getError() ?: '删除失败！');
        }
    }

}
