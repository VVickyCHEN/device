<?php
// +----------------------------------------------------------------------
// | 后台产品管理
// +----------------------------------------------------------------------
namespace app\admin\model;
use think\Model;
class Staff extends Model
{


    // 设置json类型字段，使用模型查询json数据
    protected $json = ['content'];
    // 设置JSON数据返回数组

    protected $jsonAssoc = true;
    //获取产品接口
    // public function trade_list($where = '')
    // {
    //     $data = $this->where($where)->select();
    //     if ($data) {
    //         return $data;
    //     }
    //     $this->error = '入库失败！';
    //     return false;
    // }
}
