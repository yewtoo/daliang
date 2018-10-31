<?php

namespace app\common\model;

use think\Model;

/**
 * 科目通用模型
 * @package app\common\model
 */
class Subject extends Model
{
    public function getList()
    {
        return self::column('sid,name');
    }
}