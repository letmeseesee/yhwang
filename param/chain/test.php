<?php
/**
 * Created by PhpStorm.
 * User: yhwang
 * Date: 2015/8/24
 * Time: 17:22
 */

namespace yhwang\param\chain\test;
use yhwang\param\chain\hander;
use yhwang\param\chain;
use yhwang\param\chain\filter;
class test {
    protected $chain;
    public function setup(){
        $this->chain = new filter\filter1(array('1' => 'test1'));//只要写相对路径就行
        $this->chain->append(new filter\filter2(array('2' => 'test2')));
    }
    public function makeRequest(){
        $req = new chain\Request();
        $req->data = array('1'=>'test');
        return $req;
    }
    public function hand($req){
        $result = $this->chain->handle($req);
        return $result;
    }
}