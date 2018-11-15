<?php
/* 
所有其它模型的父模型
*/
namespace libs;

use \PDO;

class Db
{
    // 保存 PDO 对象
    private static $_obj = null;
    private function __clone(){}
    private $_pdo;
    private function __construct()
    {
        if($this->_pdo === null)
        {
            
            // 连接数据库
            $this->_pdo = new \PDO('mysql:host=localhost;dbname=user_system', 'root', '573511');
            $this->_pdo->exec('SET NAMES utf8');
        }

    }

    //返回唯一对象
    public static function make(){
        if(self::$_obj === null){
            self::$_obj = new self;
        }
        return self::$_obj;
    }

    //预处理
    public function prepare($sql){
        return $this->_pdo->prepare($sql);
    }
    
    //非处理执行SQL
    public function exec($sql){
        return $this->_pdo->exec($sql);
    }
    // 获取最新添加的记录的ID
    public function lastInsertId()
    {
        return $this->_pdo->lastInsertId();
    }
}
