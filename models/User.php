<?php
namespace models;

class User extends Model 
{   
    // 设置这个模型对应的表
    protected $table = 'users';
    // 设置允许接收的字段
    protected $fillable = ['uname','password','tel_num','remember'];

    // 添加之前自动被执行
    public function _before_write()
    {
        $this->data['password'] = md5($this->data['password']);
    }



}