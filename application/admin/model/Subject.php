<?php

namespace app\admin\model;

use think\Model;

class Subject extends Model
{
    // 表名
    protected $name = 'subject';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'type_text'
    ];
    protected static function init()
    {
        self::beforeDelete(function ($row) {
            /***************有关联数据的不能删除***********************/
            //成绩管理
            if (checkTableExistValue('Score', 'sid', $row->sid)) {
                return false;
            };
            //教师任课管理
            if (checkTableExistValue('TeacherSubject', 'sid', $row->sid)) {
                return false;
            };
            //拓展课程
            if (checkTableExistValue('Expand', 'sid', $row->sid)) {
                return false;
            };
        });
    }
    

    
    public function getTypeList()
    {
        return ['1' => __('Type 1'),'2' => __('Type 2')];
    }     


    public function getTypeTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
