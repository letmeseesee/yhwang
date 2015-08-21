<?php
/**
 * Created by PhpStorm.
 * User: yhwang
 * Date: 2015/8/11
 * Time: 15:22
 * 为文件上传设置的一个简单的，单独的文件上传日志文件
 * 因为会将文件resource保存在实例中，为了处理并发的情况，会生成另外一个日志文件，通过这个文件来记录数据，当关闭连接时
 * 锁住log文件，将生成的文件的内容倒入日志文件中，放开文件锁，删除生成的日子文件，
 *这样就可以尽量避免文件冲突的问题（I hope）
 */

class supportDocumentLog {
    private static $Log = null;
    //文件句柄
    public $file = null;
    public $filename   = null;
    public $middlefilename = null;//用来暂时保存日志信息的中间文件
    public $middlefile = null;
    public $config = array(
        'path'=>'../../../log/',//基本目录
        'secondPath'=>'/uploadfilelog/',//次级目录
        'epath'=>'',//扩展目录
        'WAIT_TIME'=>3//尝试打开文件的次数
    );

    //隐藏构造函数
    private function __construct(){
    }

    //实例创建。只会生成一个实例
    public static function create($epath = ''){//expecting the application id,otherwise all the infoematonwill be log in app.txt
        if(!$epath){
            $epath = 'app';//如不指定将放入这个目录下
        }
        if(is_null(self::$Log)){
          self::$Log = new supportDocumentLog();
            //生成实例时会创建文件
            self::$Log->mkfile($epath);
        }
        return self::$Log;
    }

    public function mkfile($epath){
        $path =  self::$Log->config['path'];
        $secondPath = self::$Log->config['secondPath'];
        if($epath){
            self::$Log->config['epath'] = $epath;
        }
        $dirName = date('Ym',time());//日期。和日志文件共用的文件夹
        $fileName = "supportdocument".date('d',time()).".txt";
        if(!file_exists($path.$dirName.$secondPath)){
            if (!mkdir($path .$dirName.$secondPath, 0775, true)) {
                echo "can not create base dir,please check it";
                exit;
            }
        }
        if(!file_exists($path.$dirName.$secondPath.$epath)){
            if (!mkdir($path.$dirName.$secondPath.$epath, 0775, true)) {
                echo "can not create extend dir,please check it";
                exit;
            }
        }
        self::$Log->filename  = $path.$dirName.$secondPath.$epath.'/'.$fileName;

        //生成中间文件名
        $middlename = substr(md5(rand(1,100).time()),0,16).".txt";
        $middlename = $path.$dirName.$secondPath.$epath.'/'.$middlename;

        self::$Log->middlefilename = $middlename;
        self::$Log->middlefile = fopen($middlename,'a+');

        if(!self::$Log->middlefile){
            echo "can not create log file";
            exit;
        }
    }

    public function add($value){
        $date = date("Y-m-d-H-i",time());
        $value = $date.":".$value."\r\n";
        fwrite($this->middlefile,$value);
        return $this;
    }

    public function addparam(array $array){//需要记录参数时
        ob_start();
        var_dump($array);
        $string = ob_get_contents();
        ob_clean();
        $date = date("Y-m-d-H-i",time());
        $value = "param:".$string."\r\n";
        $value = strip_tags($value);
        fwrite($this->middlefile,$value);
        return $this;
    }

    public function show(){
        $content = file_get_contents($this->middlefilename);
        return $content;
    }

    public function close(){
        if(self::$Log == null){
            return false;
        }
        $content  = file_get_contents($this->middlefilename);
        $this->file = fopen($this->filename,'a+');
        for($i=0;$i<$this->config['WAIT_TIME'];$i++) {
            if (flock($this->file, LOCK_EX)) {
                fwrite($this->file, $content,strlen($content));
                flock($this->file, LOCK_UN);
                break;
            }
            sleep(1);
        }
        fclose($this->middlefile);
        unlink($this->middlefilename);
        fclose(self::$Log->file);
        self::$Log->filename = null;
        self::$Log = null;
        if($i==$this->config['WAIT_TIME']){
            echo 'fail to log the upload file!';
            return false;
        }
    }

    public function __toString(){
       return "This is a log class for uploadfile";
    }

    public function __call($name,$value){
        if($name == 'debug' && count($value)==1) {
            $this->add($value[0]);
        }else{
            return false;
        }
    }
}