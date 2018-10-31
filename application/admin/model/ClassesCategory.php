<?php

namespace app\admin\model;

use think\Model;

class ClassesCategory extends Model
{
    // 表名
    protected $name = 'classes_category';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [

    ];
    protected static function init()
    {
        self::beforeDelete(function ($row) {
            //有下级分类的不能删除
            if (checkTableExistValue('ClassesCategory', 'pid', $row->ccid)) {
                return false;
            };
        });
    }






}
