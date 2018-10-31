<?php

namespace app\admin\model;

use think\Model;

class User extends Model
{
    // 表名
    protected $name = 'user';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [
        'gender_text',
        'status_text'
    ];

    protected static function init()
    {
        //添加前
        self::beforeInsert(function ($row) {
            $salt = \fast\Random::alnum();
            $row->password = \app\common\library\Auth::instance()->getEncryptPassword($row['password'], $salt);
            $row->salt = $salt;
        });
        //修改前
        self::beforeUpdate(function ($row) {
            $changed = $row->getChangedData();
            //如果有修改密码
            if (isset($changed['password'])) {
                if ($changed['password']) {
                    $salt = \fast\Random::alnum();
                    $row->password = \app\common\library\Auth::instance()->getEncryptPassword($changed['password'], $salt);
                    $row->salt = $salt;
                } else {
                    unset($row->password);
                }
            }
        });
    }

    
    public function getGenderList()
    {
        return ['1' => __('Gender 1'),'2' => __('Gender 2')];
    }     

    public function getStatusList()
    {
        return ['1' => __('Status 1'),'0' => __('Status 0')];
    }     


    public function getGenderTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['gender']) ? $data['gender'] : '');
        $list = $this->getGenderList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




    public function group()
    {
        return $this->belongsTo('UserGroup', 'group_id', 'group_id', [], 'LEFT')->setEagerlyType(0);
    }
}
