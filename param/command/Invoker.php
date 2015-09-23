<?php
/**
 * Created by PhpStorm.
 * User: yhwang
 * Date: 2015/8/24
 * Time: 18:18
 * 负责调用具体的command
 */

namespace yhwang\command;
class Invoker {
    public $command;
    public function setCommand(commandInterface $command){
        $this->command = $command;
    }
    public function run(){
        $this->command->execute();
    }
}