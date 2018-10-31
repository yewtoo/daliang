<?php

namespace app\admin\controller\score;

use app\common\controller\Backend;

/**
 * 成绩管理
 *
 * @icon fa fa-circle-o
 */
class Score extends Backend
{
    
    /**
     * Score模型对象
     * @var \app\admin\model\Score
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Score;
        $this->view->assign("semesterList", $this->model->getSemesterList());
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    

    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->with(['ccategory','subject','scategory','user'])
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['ccategory','subject','scategory','user'])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                $row->visible(['id','year','semester','score']);
                $row->visible(['ccategory']);
				$row->getRelation('ccategory')->visible(['name']);
				$row->visible(['subject']);
				$row->getRelation('subject')->visible(['name']);
				$row->visible(['scategory']);
				$row->getRelation('scategory')->visible(['name']);
				$row->visible(['user']);
				$row->getRelation('user')->visible(['username']);
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }

        //年度
        $year[''] = '全部';
        for($i = $this->minYear; $this->maxYear >=  $i; $i++) {
            $year[$i] = $i;
        }
        $this->view->assign('year', build_select('year', $year, 0, ['class' => 'form-control']));

        //学期
        $semester = [
            '' => '全部',
            '1' => __('Semester 1'),
            '2' => __('Semester 2')
        ];
        $this->view->assign('semester', build_select('semester', $semester, 0, ['class' => 'form-control']));

        return $this->view->fetch();
    }
}
