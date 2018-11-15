# anheng-chat-dome
安恒php面试题



### 项目介绍

php在线聊天系统

1. web 使用自己完善的mvc框架实现

2. 登录采用了jwt令牌认证的方式

3. socker服务 使用workerman

4. 聊天室采用了嵌套vue.js文件，主要用来数据双向绑定


### 实现功能

​    1.用户注册登录

​    2.后台管理（修改和删除）

​    3.群聊和私聊



### 使用说明

一共只有2个composer包,所以直接上传了，方便直接运行



public目录下一共有2个php文件 一个是index.php  web主入口文件，一个是start.php  websocket运行文件



步骤一： 运行web 服务器  //如使用vscode 可直接在根目录开启内置服务器  php -S 127.0.0.1:8000 -t public



步骤二： 运行websocket服务器   //public目录下 开启websocket服务 php start.php start