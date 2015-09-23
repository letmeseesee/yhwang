<?php
/**
 * Created by PhpStorm.
 * User: yhwang
 * Date: 2015/8/24
 * Time: 17:19
 */

namespace yhwang\param\chain\filter;


use yhwang\param\chain\hander;
use yhwang\param\chain;
class filter2 extends hander{
    public $data;
    public function __coonstruct($data){
        $this->date = $data;
    }
    protected function porccess(chain\Request $req){
    //根据request和自己的data进行比较就行
    }
}