<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Goods extends Model
{
    //设置使用软删除 trait
    use SoftDelete;
    // 配合软删除使用，如果数据库的字段是这个就不需要修改
//    protected $deleteTime = 'delete_time';
}
