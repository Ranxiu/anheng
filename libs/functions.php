<?php

    //显示视图并传参
    function view($file,$data=[]){
    
        extract($data);
        
        include ROOT.'view/'.$file.'.html';
    }


    //重定向
    function redirect($url)
    {
        header('Location:'.$url);
        exit;
    }