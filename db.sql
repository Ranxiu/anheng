create table users
(
    uid int unsigned not null auto_increment comment '用户ID',
    uname varchar(255) not null comment '用户名',
    password varchar(64) not null  comment '密码',
    tel_num varchar(11) not null commnet '电话号码',
    reg_time datetime not null default current_timestamp comment '注册时间',
    primary key (`uid`)
)engine=InnoDB comment='用户表';