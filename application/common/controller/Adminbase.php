<?php


// +----------------------------------------------------------------------
// | 后台控制模块
// +----------------------------------------------------------------------
namespace app\common\controller;
use think\Db;
use app\admin\model\AdminUser as AdminUser_model;

class Adminbase extends Base
{
    public $_userinfo; //当前登录账号信息
    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->AdminUser_model = new AdminUser_model;
        if (defined('UID')) {
            return;
        }
        define('UID', (int) $this->AdminUser_model->isLogin());
        //验证登录
        if (false == $this->competence()) {
            //跳转到登录界面
            $this->redirect('admin/login/index');
        } else {
            //是否超级管理员
            if (!$this->AdminUser_model->isAdministrator()) {
                //检测访问权限
                $rule = strtolower($this->request->module() . '/' . $this->request->controller() . '/' . $this->request->action());
                //新版
                // $authList_all = Db::name('auth_rule')->column('name');//所有权限
                //     $authList_all = $this->_test($authList_all);
                //    // dump($authList_all);
                //     if(!empty($authList_all) && !empty($rule)){
                //         if(in_array($rule,$authList_all)){
                //             //如果没有添加权限不做判断
                //             $authList = Db::name('auth_rule')->where('title','in',Session('admin_user_auth')['rules'])->column('name');
                //             $authList = $this->_test($authList);
                //    dump(Session('admin_user_auth'));exit;
                //             if (!in_array($rule,$authList) && !empty($authList)) {
                //                 $this->error('未授权访问!');
                //             }
                //             //dump(1);
                //         }else{
                //             //dump($authList_all);
                //         }
                //     }
                // 自定义权限
                if (Session('admin_user_auth')['is_rule'] == 1) {
                    $authList = Db::name('auth_rule')->where('title','in',Session('admin_user_auth')['rules'])->column('name');
                    if (!in_array($rule,$authList)) {
                        $this->error('未授权访问!');
                        // echo "<script>alert('未授权访问!')</script>";die;
                    }
                }else{
                    $rule = strtolower($this->request->module() . '/' . $this->request->controller() . '/' . $this->request->action());

                    $authList = Db::name('auth_rule')->where('title','in',Session('admin_user_auth')['rules'])->column('name');
            // dump($authList);
                // dump(Session('admin_user_auth'));exit;
                    
                    $is_exit = db('menu')->where([ 'app'=>$this->request->module(),'controller'=>$this->request->controller(),'action'=> $this->request->action()])->find();
                    // dump($rule);
                    // dump($authList);
                    if(!empty($is_exit) && !in_array($rule,$authList)){
                        if (!$this->checkRule($rule, [1, 2])) {
                            $this->error('未授权访问!');
                            // echo "<script>alert('未授权访问!')</script>";die;
                        }
                    }
                }
            }
        }
        $this->assign('ispower',empty($this->_userinfo['ispower']) ? 0 : 1);
    }


    public function _test($data){
        $result = [];
        if(!empty($data)){
            foreach($data as $key=>$value){
          $result[] = strtolower($value);
            }
        }
        return $result;
    }

    //验证登录
    private function competence()
    {
        //检查是否登录
        $uid = (int) $this->AdminUser_model->isLogin();
        if (empty($uid)) {
            return false;
        }
        //获取当前登录用户信息
        $userInfo = $this->AdminUser_model->getUserInfo($uid);
        if (empty($userInfo)) {
            $this->AdminUser_model->logout();
            return false;
        }
        $this->_userinfo = $userInfo;
        //是否锁定
        /*if (!$userInfo['status']) {
        User::getInstance()->logout();
        $this->error('您的帐号已经被锁定！', url('admin/index/login'));
        return false;
        }*/
        return $userInfo;

    }

    /**
     * 权限检测
     * @param string  $rule    检测的规则
     * @param string  $mode    check模式
     * @return boolean
     */
    final protected function checkRule($rule, $type = AuthRule::RULE_URL, $mode = 'url')
    {
        static $Auth = null;
        if (!$Auth) {
            $Auth = new \libs\Auth();
        }
        if (!$Auth->check($rule, UID, $type, $mode)) {
            return false;
        }
        return true;
    }

}
