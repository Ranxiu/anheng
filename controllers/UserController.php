<?php 
namespace controllers;

use models\User;
use Firebase\JWT\JWT;

class UserController 
{   


    //显示登录界面
    public function login()
    {   
        return view('user/login');
    }
    //显示注册界面
    public function register()
    {
        return view('user/register');
    }
    //账号登录处理
    public function doLogin()
    {   

        //接收原始数据
        $post = file_get_contents('php://input');
        //把JSON数据转成数据
        $_POST = json_decode($post, TRUE);
        
        // var_dump($_POST);die;

        $model = new User;
        $model->fill($_POST);


        //判断是否勾选记住登录状态 没有直接登录 有则cookie保存用户信息7天时间
        if(!isset($model->data['remember']))
        {
            $user = $model->findLoginUser($model->data['uname'],md5($model->data['password']));
            if($user){
                //当前用户id存入SESSION
                $_SESSION['uid'] = $user['uid'];

                $type = 'jwt';
                $info = [
                    'uid' => $user['uid'],
                    'uname'=> $user['uname'],
                ];             
                //生成令牌
                $jwt = JWT::encode( $info ,$type);
                //给前台ajax返回数据
                echo json_encode([
                    'code'=>'200',
                    'jwt'=>$jwt,
                ]);
            }else{
                // 返回 JSON 数据
                echo json_encode([
                    'code'=>'403',
                    'error'=>'用户名或者密码错误！',
                ]);
            }

        }else{
            
            $user = $model->findLoginUser($model->data['uname'],md5($model->data['password']));

            if($user){
                //设置setcookie
                setcookie('uname',$model->data['uname'],time()+604800);
                setcookie('password',$model->data['password'],time()+604800);
                setcookie('remember',$model->data['remember'],time()+604800);

                //当前用户id存入SESSION
                $_SESSION['uid'] = $user['uid'];

                $type = 'jwt';
                $info = [
                    'uid' => $user['uid'],
                    'uname'=> $user['uname'],
                ];             
                //生成令牌
                $jwt = JWT::encode( $info ,$type);
                //给前台ajax返回数据
                echo json_encode([
                    'code'=>'200',
                    'jwt'=>$jwt,
                ]);

            }else{
                // 返回 JSON 数据
                echo json_encode([
                    'code'=>'403',
                    'error'=>'用户名或者密码错误！',
                ]);
            }
        }
    }
    //账号注册处理
    public function doRegister()
    {   
        $model = new User;
        //处理表单数据
        $model->fill($_POST);

        //接收表单数据 后台对前台提交的数据进行判断是否存在
        if($model->data['uname']!=''&&$model->data['password']!=''&&$model->data['tel_num']!=''){

            $status = $model->findRegUser($_POST['uname']);
            
            if(!$status){

                $model->insert();
                
                redirect('/user/login');

            }else{
                echo "<script>alert('账号已存在');location.href='/user/register'</script>";
            }
        }
    }

    //显示用户列表
    public function user_list()
    {   
        $model = new User;
        $data = $model->findAll();
        // echo '<pre>';
        // var_dump($data);die;

        return view('user/user_list',$data);
    }

    //显示聊天室界面
    public function chat_room()
    {   
        $model = new User;
        $data = $model->findAll();
        return view('user/chat_room',$data);
    }

    //修改用户信息界面
    public function edit()
    {   
       $uid = $_GET['uid'];
       $model = new User;
       $data = $model->findOne($uid);
       return view('user/edit',$data);

    }

    //修改表单处理
    public function update()
    {   
        $uid = $_GET['uid'];
        $model = new User;
        $model->fill($_POST);
        $model->update($uid);
        redirect('/user/user_list');

    }

    //删除用户
    public function delete()
    {
        $uid = $_GET['uid'];
        $model = new User;
        $model->delete($uid);
        redirect('/user/login');
    }

}