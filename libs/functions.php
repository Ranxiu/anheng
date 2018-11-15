<?php


    function view($file,$data=[]){
    
        extract($data);
        
        include ROOT.'view/'.$file.'.html';
    }

    function redirect($url)
    {
        header('Location:'.$url);
        exit;
    }