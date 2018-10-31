<?php

namespace app\admin\controller\classes;

use app\common\controller\Backend;

/**
 * 班级分类
 *
 * @icon fa fa-circle-o
 */
class Category extends Backend
{
    protected $searchFields = 'ccid,name';

    /**
     * ClassesCategory模型对象
     * @var \app\admin\model\ClassesCategory
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\ClassesCategory;

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
        $this->relationSearch = false;
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
                    
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $k => $row) {
                $row->visible(['ccid','name']);
                //区分年级/班级
                if ($row['pid'] == 0) {
                    $list[$k]['name'] = '[年级] ' . $list[$k]['name'];
                }
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    public function add()
    {
        //顶级分类
        $cats = $this->model->where(['pid' => 0])->column('ccid,name');
        $cats[0] = '年级分类';
        $this->view->assign('cats', build_select('row[pid]', $cats, 0, ['class' => 'form-control selectpicker']));
        return parent::add();
    }

    public function edit($ids = NULL)
    {
        $row = $this->model->get($ids);
        if (!$row)
            $this->error(__('No Results were found'));

        //上级分类
        $cats = $this->model->where(['pid' => 0])->column('ccid,name');
        $cats[0] = '年级分类';
        $this->view->assign('cats', build_select('row[pid]', $cats, $row['pid'], ['class' => 'form-control selectpicker']));
        return parent::edit($ids);
    }
}
