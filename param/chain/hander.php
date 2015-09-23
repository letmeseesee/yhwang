<?php
namespace yhwang\param\chain;
/**
 * Created by PhpStorm.
 * User: yhwang
 * Date: 2015/8/24
 * Time: 16:36
 */
abstract class hander {
    /*相当于链表中的下一个节点的指针
     * 还需要一个append方法来是设置下一个节点的地址
     * 以及一个处理函数来运行当前节点的处理
     * 最后需要一个处理函数，里面是具体的处理程序
     * 整个过程相当于递归调用
     */
    private $chain;

    final public function append(hander $node){
        if(is_null($this->chain)){
            $this->chain = $node;
        }else{
            $this->chain->append($node);
        }
    }

    final public function handle(Request $req){
        $req->finalnode = get_called_class();
        $result = $this->porccess($req);
        if(!$result){
            return $result;
        }else {
            if (!is_null($this->chain)) {
                $result =  $this->chain->porccess($req);
            }
        }
        return $result;
    }

    abstract protected function porccess(Request $req);
}