<?php

namespace app\student\controller;

use think\Controller;

/**
 * 学生端控制器基类
 */
class Base extends Controller
{
    const groupId = 2;//学生分组ID
    public $user = [];
    public $user_id = [];
    public $minYear = 0;//最小年度
    public $maxYear = 0;//最大年度
    public $semester = 0;//最大学期（即当期学期）

    /**
     * 无需登录的方法
     * @var array
     */
    protected $noLogin = [];

    public function _initialize()
    {
        $this->checkLogin();
        //最大最小年度
        $maxMinYear = getMaxMinYear();
        $this->minYear = $maxMinYear['min'];
        $this->maxYear = $maxMinYear['max'];
        $this->semester = getMaxSemester($maxMinYear['max']);
    }

    /**
     * 登录验证
     */
    private function checkLogin() {
        if (!in_array($this->request->action(), $this->noLogin)) {
            if (!session('?user_id')) {
                returnJson(2, __('Please login'));
            }

            $user_id = session('user_id');
            $user = Model('User')->get($user_id);

            //判断是否为学生
            if ($user->group_id != self::groupId) {
                returnJson(2, __('please login studenr'));
            }
            //覆盖session 中的 user
            session('user' , $user->getData());
            $this->user = $user->getData();
            $this->user_id = $user->user_id;
        }
    }

    /**
     * 获取当前学生所在班级
     * @return array|false|\PDOStatement|string|\think\Model
     */
    protected function currentClass()
    {
        $where = [
            'a.year' => $this->maxYear,
            'a.semester' => $this->semester,
            'a.user_id' => $this->user_id
        ];
        $result = model('ClassesStudent')
            ->field('b.*')->alias('a')
            ->join('classesCategory b', 'a.ccid = b.ccid', 'LEFT')
            ->where($where)
            ->find();

        //没有对应结果
        if (!$result) {
            $result = [
                'ccid' => 0,
                'name' => __('Not yet allocated classes'),
                'pid' => 0,
            ];
        }
        return $result;
    }
}