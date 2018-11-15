<?php



require_once './workerman/Autoloader.php';
require('../vendor/autoload.php');

use Firebase\JWT\JWT;
use Workerman\Worker;


// 创建一个Worker监听2346端口，使用websocket协议通讯
$worker = new Worker("websocket://0.0.0.0:2346");

// 启动1个进程对外提供服务
$worker->count = 1;

//定义数组保存用户id和这个客户端的关系
/*
一维数组，里面的数据格式：
[
    1 => $connection 对象,
    2 => $connection 对象,
    ....
]
*/
$userConn = [];


//定义数组（二维数组）保存所有聊天记录 0下标表示群发 其他下标对应私聊对象

$allmessages = [];


//当客户端连接的时候的处理逻辑
$worker->onConnect = function ($connection) {

	$connection->onWebSocketConnect = function($connection,$http_header) {
		 // 保存当前用户信息
		 global $users, $worker, $userConn;
		 // 解析 JWT 令牌
		 try
		 {
			 $type = 'jwt';
			 $info = JWT::decode($_GET['token'], $type, array('HS256'));
			 // 把ID和name保存到这个连接上， 以区分这个连接是谁
			 $connection->uid = $info->uid;
			 $connection->uname = $info->uname;
			 // 保存当前连接到所有用户的数组中
			 $users[$info->uid] = $info->uname;
			 // 把当前这个客户端的对象保存到数组中，用户ID是下标
			 $userConn[$info->uid] = $connection;
		 }
		 catch(  \Firebase\JWT\ExpiredException $e)
		 {
			 $connection->close();
		 }
		 catch( \Exception $e)
		 {
			 $connection->close();
		 }
	};

	
};
// 接收消息
$worker->onMessage = function($connection, $data) {
    global $worker;
    global $allmessages;
    /* 从消息中解析出第一个:前面的内容，以判断是群发还是单发 */
    // 根据 : 将字符串转成数组
    $ret = explode(':', $data);
    // 取出第一个元素（第一个:前面的内容）
    $code = $ret[0];
    // 去掉第一个:前面的内容
    unset($ret[0]);
    // 再把数组拼回字符串
    $rawData = implode(':', $ret);
    // 判断第一个元素
    if($code == 'all')
    {
        // 群发
        // 循环所有的客户端，给它们发消息
        $allmessages['all'][]=$connection->uname.'对所有人说:'.$rawData;
        foreach($worker->connections as $c)
        {
            $c->send(json_encode([
                'type'=>'message',
                'message'=>$connection->uname.'对所有人说:'.$rawData,
                'to'=>'0',
                'allmessages'=>$allmessages,
            ]));
        }
       
        // var_dump($allmessages);
        try{
            //通过pdo创建数据库连接
            $db = new PDO("mysql:host=localhost;dbname=user_system",'root','573511');
            //设置编码
            $db->exec("set names utf8");
            //设置出现异常的解决办法
            $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        }catch(PDOException $e){
            echo "mysql connection fail. ".$e->getMessage();
        }
        //var_dump("INSERT INTO chat(`uid`,`uname`,`to_id`,`to_name`,`message`) VALUES($connection->uid,'{$connection->uname}','0','所有人','{$rawData}')");
        $count = $db->exec("INSERT INTO chat(`uid`,`uname`,`to_id`,`to_name`,`message`) VALUES($connection->uid,'{$connection->uname}','0','所有人','{$rawData}')");
        //清空数据库对象资源
        $db = null;
    }
    else
    {

        // 引入保存所有客户端与用户ID对应关系的数组
        global $userConn;
        // 根据用户ID找到对应的客户端对象，
        // 然后调用这个对象的 send 方法给这个客户端发消息
        if(!isset($userConn[$code])){
            $connection->send(json_encode([
                'type'=>'waring',
                'message'=>'当前用户不在线'
            ]));
         
        }else{
            //把XXX和当前客户端的聊天记录插入以当前客户端uid为下标的二维数组中
            $allmessages[$connection->uid][]= $connection->uname.'对你说:'.$rawData;

            $userConn[$code]->send(json_encode([
                'type'=>'message',
                'message'=>$connection->uname.'对你说:'.$rawData,
                'to'=>$code,
                'allmessages'=>$allmessages,
            ]));
          
          
            try{
                //通过pdo创建数据库连接
                $db = new PDO("mysql:host=localhost;dbname=user_system",'root','573511');
                //设置编码
                $db->exec("set names utf8");
                //设置出现异常的解决办法
                $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    
            }catch(PDOException $e){
                echo "mysql connection fail. ".$e->getMessage();
            }

            //把当前客户端和对方的聊天记录插入以对方uid（$code）为下标的二维数组中
            $allmessages[$code][]= '你对'.$userConn[$code]->uname.'说:'.$rawData;
            //单向插入聊天记录 
            // $count = $db->exec("INSERT INTO chat(`uid`,`uname`,`to_id`,`to_name`,`message`) VALUES({$userConn[$code]->uid},'{$userConn[$code]->uname}',$connection->uid,'{$connection->uname}',$rawData)");
            $connection->send(json_encode([
                'type'=>'message',
                'message'=>'你对'.$userConn[$code]->uname.'说:'.$rawData,
                'to'=>$connection->uid,
                'allmessages'=>$allmessages,
            ]));
            // var_dump($rawData);
            $count = $db->exec("INSERT INTO chat(`uid`,`uname`,`to_id`,`to_name`,`message`) VALUES($connection->uid,'{$connection->uname}',{$userConn[$code]->uid},'{$userConn[$code]->uname}','{$rawData}')");
            $db = null;
        }
    }    
};

// $worker->onClose = function($connection)
// {
   
// };


// 运行
Worker::runAll();