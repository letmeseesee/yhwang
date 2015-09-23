<?php
/**
 * Created by PhpStorm.
 * User: wanghao
 * Date: 15/8/20
 * Time: 上午6:54
 * 连接数据库的函数
 */
class db{//连接数据库的类
    public $db;//数据库实例
    public $dbConfig = array(
        'host'=>'localhost',
        'port'=>3307,
        'username'=>'root',
        'password'=>'root',
        'dbName'=>'yhwang'
    );
    public $result = array(//保存上次查询的结果
        'result'=>'',
        'data'=>'',
        'number'=>'',
    );
    //获取数据库连接实例
    public function __construct(){
        $this->db = new mysqli($this->dbConfig['host'],$this->dbConfig['username'],$this->dbConfig['password'],
            $this->dbConfig['dbName']);//创建数据库实例
    }
    //判断是否连接成功
    public function ifConnectSuccess(){
        if($this->db){
            return true;
        }else{
            return false;
        }
    }
    /*
     * 进行sql查询
     * @param $sql :sql语句,只进行selectchax
     * **/
    public function query($sql){
        $result = $this->db->query($sql);
        $num_result = mysqli_num_rows($result);//取得结果的行数
        //echo $num_result;
        if($num_result>0){
            $this->result['number'] = $num_result;
            $this->result['result'] = true;
            for($i = 0;$i<$num_result;$i++){
                $row[] = $result->fetch_assoc();//fetch_row,fetch_object取得数字索引结果和取得对象结果
            }
            $this->result['data'] = $row;
        }else{
            $this->result['result'] = false;
        }
    }
    /*
     * 进行update等操作
     * @param $sql :sql语句
     * **/
    public function exec($sql){
        $result = $this->db->query($sql);
        $affect_num = mysqli_affected_rows($result);
        if($affect_num>0){
            $this->result['result'] = true;
            $this->result['number'] = $affect_num;
            $this->result['data'] = '';//清空上次的操作数据
        }else{
            $this->result['result'] = false;
            $this->result['number'] = 0;
            $this->result['data'] = '';//清空上次的操作数据
        }
    }
    public function __destruct(){
        $this->db->close();
    }
}
