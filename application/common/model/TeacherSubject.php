<?php

namespace app\common\model;

use think\Model;

/**
 * 教师任课通用模型
 * @package app\common\model
 */
class TeacherSubject extends Model
{
    /**
     * 获取老师所教的班级科目信息
     * @param $yeay
     * @param $semester
     * @param $user_id
     * @return array
     */
    public function getList($year, $semester, $user_id)
    {
        $where = [
            'year' => $year,
            'semester' => $semester,
            'user_id' => $user_id
        ];

        //获取老师当班主任的班级ID
        $director = model('Classes')->field('ccid')->where($where)->select();
        $ccid = [];
        foreach ($director as $v) {
            $ccid[] = $v->ccid;
        }

        //所有班级分类信息
        $classesCat = model('ClassesCategory')->getList();
        //所有科目信息
        $subject = model('subject')->getList();

        //老师所教班级
        $classes = self::field('ccid,sid')->where($where)->select();
        $data = [];
        foreach ($classes as $v) {
            //是否班主任
            $isDirector = in_array($v->ccid, $ccid) ? 1 : 0;
            $data[] = [
                'ccid' => $v->ccid,
                'class' => $classesCat[$v->ccid],
                'subject' => $subject[$v->sid],
                'isDirector' => $isDirector,
            ];
        }

        return $data;
    }
}