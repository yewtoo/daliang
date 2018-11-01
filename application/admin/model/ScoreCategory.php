<?php

namespace app\admin\model;

use think\Model;

class ScoreCategory extends Model
{
    // 表名
    protected $name = 'score_category';
    
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
            /***************有关联数据的不能删除***********************/
            //成绩管理
            if (checkTableExistValue('Score', 'scid', $row->scid)) {
                return false;
            };
        });
    }
    

    







}
