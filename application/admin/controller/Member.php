<?php

namespace app\admin\controller;

use app\admin\model\AdminUser;
use app\common\controller\Adminbase;
use think\Db;
use think\db\Where;

/**
 * 会员管理 
 */
class Member extends Adminbase
{
    // protected function initialize()
    // {
    //     parent::initialize();
    //     $this->AdminUser = new AdminUser;
    // }
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
            $where['phone'] = [ 'like',"%".input('search')."%" ];
            $query['search'] = input('search');
        }
        // $User = Db::name("member")->order(array('id' => 'ASC'))->where($where)->paginate($this->show_page,false,['query'=>$query]);
        $User = Db::name("member")->order(array('id' => 'ASC'))->where($where)->select();
        $this->assign("Userlist", $User);

        return $this->fetch();
          
    }

 


    /**
     * 会员编辑
     */
    public function edit()
    {
        if ($this->request->isPost()) {
     


        }else{
            $id = $this->request->param('id/d');
            $data = Db::name('member')->where(array("id" => $id))->find();
            if (empty($data)) {
                $this->error('该会员信息有误！');
            }
            $codelist = db('invitation')->where('invite_code',$data['invite_code'])->select();
            // dump($codelist);
            $this->assign("codelist", $codelist);            
            $this->assign("data", $data);            
            return $this->fetch();
        }
    }

    

    /**
     * 会员删除
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
