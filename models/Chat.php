<?php
namespace models;

class Chat extends Model 
{   
    // 设置这个模型对应的表
    protected $table = 'chat';
    // 设置允许接收的字段
    protected $fillable = ['uid','uname','to_id','to_name','message'];


}