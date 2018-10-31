<?php

namespace app\common\model;

use think\Model;

/**
 * 班级分类通用模型
 * @package app\common\model
 */
class ClassesCategory extends Model
{
    public function getList()
    {
        return self::column('ccid,name');
    }
}