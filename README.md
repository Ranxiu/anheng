# anheng
安恒php面试题


一共只有2个composer包,所以直接上传了，方便直接运行

public目录下一共有2个php文件 一个是index.php 主入口文件，一个是start.php websoket运行文件

步骤一： 运行php服务器  //如使用vscode 可直接在根目录开启内置服务器  php -S localhost:8000 -t public


步骤二： 运行websoket服务器   //public目录下 开启websoket服务 php start.php start


注意事项：

1.由于登录注册功能用到了jwt_token,所有在进入聊天室之前,会有token令牌检测,没有会跳转到登录页

2.进入聊天室之前 请确保public目录下的start.php 已经运行
