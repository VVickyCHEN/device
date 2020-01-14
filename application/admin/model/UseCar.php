<?php

namespace app\admin\model;

use think\Model;
use think\Db;

// 要使用软删除功能，需要引入SoftDelete trait
// use traits\model\SoftDelete;

class UseCar extends Model
{   
    // use SoftDelete;
    // set 开头的是修改器
    // public function setStartTimeAttr($value)
    // {
    //     return strtotime($value);
    // }

    // 系统支持自动写入创建和更新的时间戳字段 
    protected $autoWriteTimestamp = true;

    // get 开头的是获取器
 
    public function getUpdateTimeAttr($value){
        return date('Y-m-d H:i:s',$value);
    }

    public function getCreateTimeAttr($value){
        return date('Y-m-d H:i:s',$value);
    }

  
    public function getapprolstaffAttr($value){
        $approlstaff = Db::name('admin')->where('staffid',$value)->value('name');
        return $approlstaff;
    }

    public function getapprolstaffidAttr($data,$value){
        return $value['approlstaff'];
    }

    public function getCarHotAttr($value){
        $hot_status = '';
        switch ($value) {
            case '1':
                $hot_status = '紧急';
                break;
            
            default:
                $hot_status = '正常';
                break;
        }
       
        return $hot_status;
    }

    public function getCarnameAttr($data,$value){
        $carname = Db::name('car')->where(['id'=>$value['carid']])->value('name');
        return $carname;
    }

  
}
