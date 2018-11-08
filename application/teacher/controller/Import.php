<?php

namespace app\teacher\controller;
use think\Db;
/**
 * 拓展课程导入评价跟成绩
 */
class Import extends \think\Controller
{
    //无需登录的方法
    protected $noLogin = ['login', 'logout'];

    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('expand');
    }
    
    public function import()
    {
//        $file = $this->request->request('file');
        
        $file='uploads'.DS.'20181107'.DS.'123.xls';
        if (!$file) {
            returnJson(1,__('Parameter %s can not be empty', 'file'));
        }
        $filePath = ROOT_PATH . DS . 'public' . DS . $file;
        if (!is_file($filePath)) {
           returnJson(1,__('No results were found'));
        }
        $PHPReader = new \PHPExcel_Reader_Excel2007();
        if (!$PHPReader->canRead($filePath)) {
            $PHPReader = new \PHPExcel_Reader_Excel5();
            if (!$PHPReader->canRead($filePath)) {
                $PHPReader = new \PHPExcel_Reader_CSV();
                if (!$PHPReader->canRead($filePath)) {
                    returnJson(1,__('Unknown data format'));
                }
            }
        }

        //导入文件首行类型,默认是注释,如果需要使用字段名称请使用name
        $importHeadType = isset($this->importHeadType) ? $this->importHeadType : 'comment';

        $table = $this->model->getQuery()->getTable();
        $database = \think\Config::get('database.database');
        $fieldArr = [];
        $list = DB::query("SELECT COLUMN_NAME,COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME ='uweb_expand' AND TABLE_SCHEMA ='daliang'");
        
        foreach ($list as $k => $v) {
            if ($importHeadType == 'comment') {
                $fieldArr[$v['COLUMN_COMMENT']] = $v['COLUMN_NAME'];
            } else {
                $fieldArr[$v['COLUMN_NAME']] = $v['COLUMN_NAME'];
            }
        }
        
        $PHPExcel = $PHPReader->load($filePath); //加载文件
        
        $currentSheet = $PHPExcel->getSheet(0);  //读取文件中的第一个工作表
        $allColumn = $currentSheet->getHighestDataColumn(); //取得最大的列号
        $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
        $maxColumnNumber = \PHPExcel_Cell::columnIndexFromString($allColumn);
        for ($currentRow = 1; $currentRow <= 1; $currentRow++) {
            for ($currentColumn = 0; $currentColumn < $maxColumnNumber; $currentColumn++) {
                $val = $currentSheet->getCellByColumnAndRow($currentColumn, $currentRow)->getValue();
                $fields[] = $val;
            }
        }
        $insert = [];
        for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
            $values = [];
            for ($currentColumn = 0; $currentColumn < $maxColumnNumber; $currentColumn++) {
                $val = $currentSheet->getCellByColumnAndRow($currentColumn, $currentRow)->getValue();
                $values[] = is_null($val) ? '' : $val;
            }
            
            $row = [];
            $temp = array_combine($fields, $values);
            foreach ($temp as $k => $v) {
                if (isset($fieldArr[$k]) && $k !== '') {
                    $row[$fieldArr[$k]] = $v;
                    
                }
            }
            
            if ($row) {
                $insert[] = $row;
            }
        }
//        dump($insert);die;
        if (!$insert) {
            returnJson(1,__('No rows were updated'));
        }
        try {
            foreach($insert as $k=>$v){
                $zzz=db('expand')->where("year={$v['year']} and semester={$v['semester']} and ccid={$v['ccid']} and sid={$v['sid']} and teacher_id={$v['teacher_id']} and student_id={$v['student_id']}")->update(['score'=>$v['score'],'evaluate'=>$v['evaluate']]);
            }
            returnJson(0, __('Successful'));
        } catch (\think\exception\PDOException $exception) {
           returnJson(1,$exception->getMessage());
        } catch (\Exception $e) {
            returnJson(1,$e->getMessage());
        }

        
    }
    
}