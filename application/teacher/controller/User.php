<?php

namespace app\teacher\controller;

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
        $user = [
            'username' => $this->user['username'],
            'account' => $this->user['account'],
            'gender' => $this->user['gender'],
            'avatar' => ROOT_URL . $this->user['avatar'],
        ];
        $classes = model('TeacherSubject')->getList($this->maxYear, $this->semester, $this->user_id);
        $data = [
            'user' => $user,
            'classes' => $classes
        ];

        returnJson(0, __('Successful'), $data);
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
        $data = [
            'avatar' => ROOT_URL . $user['avatar'],
            'username' => $user['username'],
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