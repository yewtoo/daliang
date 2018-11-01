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
            /***************有关联数据的不能删除***********************/
            //下级分类
            if (checkTableExistValue('ClassesCategory', 'pid', $row->ccid)) {
                return false;
            };
            //班级学生
            if (checkTableExistValue('ClassesStudent', 'ccid', $row->ccid)) {
                return false;
            };
            //班级管理
            if (checkTableExistValue('Classes', 'ccid', $row->ccid)) {
                return false;
            };
            //成绩管理
            if (checkTableExistValue('Score', 'ccid', $row->ccid)) {
                return false;
            };
            //教师任课管理
            if (checkTableExistValue('TeacherSubject', 'ccid', $row->ccid)) {
                return false;
            };
            //拓展课程
            if (checkTableExistValue('Expand', 'ccid', $row->ccid)) {
                return false;
            };
        });
    }






}
