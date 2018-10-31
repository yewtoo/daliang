<?php

namespace app\common\model;

use think\Model;
use \app\common\library\Auth;

/**
 * 会员通用模型
 */
class User Extends Model
{
    protected static function init()
    {
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

    /**
     * 登录验证
     * @param $account
     * @param $password
     * @return array
     */
    public function login($account, $password)
    {
        $user = self::get(['account' => $account]);

        if (!$user) {
            return ['status' => 0, 'msg' => __('User no existent')];
        }

        //验证密码
        if (Auth::instance()->getEncryptPassword($password, $user->salt) != $user->password) {
            return ['status' => 0, 'msg' => __('Password error')];
        }
        //验证状态
        if ($user->status != 1) {
            return ['status' => 0, 'msg' => __('User is locked')];
        }

        return ['status' => 1, 'user' => $user->data];
    }
}
