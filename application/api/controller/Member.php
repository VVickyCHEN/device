<?php

// +----------------------------------------------------------------------
// | 中铁接口
// | creator ：lichengyi
// | time：2019/7/13
// +----------------------------------------------------------------------
namespace app\api\controller;
use think\Controller;
use think\Db;
use think\Validate;
use phpqrcode;
use app\api\controller\Base;

//每个接口调用验证，token
class Member extends Base
{

    protected function initialize()
    {
        parent::initialize();
        
        // echo 666;
    }    

    public function index()
    {
      echo '会员接口';
    }

  








}
