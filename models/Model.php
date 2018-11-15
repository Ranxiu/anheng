<?php
namespace models;
use \PDO;
class Model {

    protected $_db;
    //操作的表名，值由子类设置
    protected $table;
    //表单中的数据，值由控制器设置
    public $data;

    protected function _before_write(){}
    protected function _after_write(){}

    public function __construct(){
        $this->_db = \libs\Db::make();
    }


    //接收表单中的数据
    public function fill($data){
        //判断是否在白名单中
        foreach($data as $k =>$v){
            if(!in_array($k,$this->fillable)){
                unset($data[$k]);
            }
        }
       $this->data = $data;

    }
    //插入表单数据
    public function insert(){
        //插入个人信息之前给密码加密
        $this->_before_write();
        $keys = [];
        $values = [];
        $token = [];

        foreach($this->data as $k => $v){
            $keys[] = $k;
            $values[] = $v;
            $token[] = '?';
        }

        $keys = implode(',',$keys);
        $token = implode(',',$token);
        $sql = "INSERT INTO {$this->table}($keys) VALUES($token)";
        $stmt =  $this->_db->prepare($sql);
        $stmt->execute($values);

        $this->data['id'] = $this->_db->lastInsertId();  

        $this->_after_write();
    }
    //查询表单所有数据
    public function findAll(){
        $stmt = $this->_db->prepare("SELECT * FROM {$this->table}");   
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    //查询单条数据
    public function findOne($uid){

        $stmt = $this->_db->prepare("SELECT * FROM {$this->table} WHERE `uid`=?");   
        $stmt->execute([$uid]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    //注册时查询用户是否已经存在
    public function findRegUser($uname){
        $stmt = $this->_db->prepare("SELECT * FROM {$this->table} WHERE uname=?");   
        $stmt->execute([$uname]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    //登录时查询用户是否存在
    public function findLoginUser($uname,$password){
        $stmt = $this->_db->prepare("SELECT * FROM {$this->table} WHERE uname=? and `password`=?");   
        $stmt->execute([$uname,$password]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    //更新数据
    public function update($uid){

        //更新个人信息之前给密码加密
        $this->_before_write();

        $set = [];
        $token = [];
        
        foreach($this->data as $k=>$v){
            $set[] = "$k=?";
            $values[] = $v;
        }

        $set = implode(',',$set);

        $values[] = $uid;
        $sql = "UPDATE {$this->table} SET $set WHERE `uid`=? ";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute($values);
    }

    //删除数据
    public function delete($uid){
        $stmt = $this->_db->prepare("DELETE FROM {$this->table} WHERE  `uid`=?");
        $stmt->execute([$uid]);
    }

}

?>