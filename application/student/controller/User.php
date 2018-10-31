<?php

namespace app\student\controller;

/**
 * 会员管理
 */
class User extends Base
{
    //无需登录的方法
    protected $noLogin = ['login', 'logout'];

    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\common\model\User;
    }

    /**
     * 个人中心
     */
    public function index()
    {
        //获取当前所在班级
        $classInfo = $this->currentClass();
        $user = [
            'avatar' => ROOT_URL . $this->user['avatar'],
            'username' => $this->user['username'],
            'class' => $classInfo['name'],
            'gender' => $this->user['gender'],
            'mobile' => $this->user['mobile'],
            'location' => $this->user['location'],
            'account' => $this->user['account'],
            'joinTime' => $this->user['join_time'],
        ];

        returnJson(0, __('Successful'), $user);
    }

    /**
     * 会员资料修改
     */
    public function edit()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post();
            if (empty($params)) {
                returnJson(1, __('Parameter can not be empty'));
            }
            try {
                $result = $this->model->allowField(['avatar','password','salt'])->save($params, ['user_id' => $this->user_id]);
                if ($result !== false) {
                    returnJson(0, __('Successful'));
                } else {
                    returnJson(1, __('Fail'));
                }
            } catch (\think\exception\PDOException $e) {
                returnJson(1, $e->getMessage());
            } catch (\think\Exception $e) {
                returnJson(1, $e->getMessage());
            }
        }
    }

    /**
     * 会员登录
     */
    public function login()
    {
        $account = $this->request->request('account');
        $password = $this->request->request('password');

        if (empty($account) || empty($password)) {
            returnJson(1, __('Please enter account password'));
        }

        //登录验证
        $res = $this->model->login($account, $password);

        if ($res['status'] == 0) {
            returnJson(1, $res['msg']);
        }

        $user = $res['user'];

        //角色判断
        if ($user['group_id'] != self::groupId) {
            returnJson(1, __('User no existent'));
        }

        //保存登录态
        session('user_id' , $user['user_id']);
        $this->user_id = $user['user_id'];

        //获取当前所在班级
        $classInfo = $this->currentClass();
        $data = [
            'avatar' => ROOT_URL . $user['avatar'],
            'username' => $user['username'],
            'class' => $classInfo['name'],
        ];
        returnJson(0, __('Login successful'), $data);
    }

    /**
     * 会员注销
     */
    public function logout()
    {
        session('user_id' , null);
        returnJson(0, __('Logout successful'));
    }
}