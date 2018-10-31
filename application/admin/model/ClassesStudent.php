<?php

namespace app\admin\model;

use think\Model;

class ClassesStudent extends Model
{
    // 表名
    protected $name = 'classes_student';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'semester_text'
    ];
    

    
    public function getSemesterList()
    {
        return ['1' => __('Semester 1'),'2' => __('Semester 2')];
    }     


    public function getSemesterTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['semester']) ? $data['semester'] : '');
        $list = $this->getSemesterList();
        return isset($list[$value]) ? $list[$value] : '';
    }




    public function category()
    {
        return $this->belongsTo('ClassesCategory', 'ccid', 'ccid', [], 'LEFT')->setEagerlyType(0);
    }


    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'user_id', [], 'LEFT')->setEagerlyType(0);
    }
}
