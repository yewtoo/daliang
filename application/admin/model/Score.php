<?php

namespace app\admin\model;

use think\Model;

class Score extends Model
{
    // 表名
    protected $name = 'score';
    
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




    public function ccategory()
    {
        return $this->belongsTo('ClassesCategory', 'ccid', 'ccid', [], 'LEFT')->setEagerlyType(0);
    }


    public function subject()
    {
        return $this->belongsTo('Subject', 'sid', 'sid', [], 'LEFT')->setEagerlyType(0);
    }


    public function scategory()
    {
        return $this->belongsTo('ScoreCategory', 'scid', 'scid', [], 'LEFT')->setEagerlyType(0);
    }


    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'user_id', [], 'LEFT')->setEagerlyType(0);
    }
}
