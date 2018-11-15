<?php

define('ROOT',__DIR__.'/../');
session_start();
//引入函数文件
require(ROOT.'libs/functions.php');
require(ROOT.'vendor/autoload.php');


//自动加载函数
function autoClass($class){

    $path = str_replace('\\', '/', $class);
    require_once (ROOT . $path . '.php');
}
//注册加载函数
spl_autoload_register('autoClass');



$controller = '\controllers\IndexController';
$action = 'index';

if(isset($_SERVER['PATH_INFO'])){
    $pathInfo = explode('/',$_SERVER['PATH_INFO']);

    $controller = '\controllers\\'.ucfirst($pathInfo[1]).'Controller';
    $action = $pathInfo[2];
}

$_C = new $controller;

$_C->$action();

