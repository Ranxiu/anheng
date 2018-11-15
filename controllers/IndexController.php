<?php
namespace controllers;

class IndexController 
{   

    //默认方法  
    public function index()
    {
        return view('user/login');
    }
}

?>