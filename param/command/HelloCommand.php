<?php
/**
 * Created by PhpStorm.
 * User: yhwang
 * Date: 2015/8/24
 * Time: 18:19
 */

namespace yhwang\command;


class HelloCommand implements commandInterface{
    public $output;
    public function __construct(Receive $receive){
        $this->output = $receive;
    }
    public function execute(){
        $this->output->writer("hello world");
    }
}