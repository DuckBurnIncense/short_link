<?php 
// 请正确修改此文件后把此文件重命名为 configuration.php
return [
	'database' => [
		'type' => 'mysql', // 数据库类型
		'host' => 'localhost', // 数据库主机
		'port' => 3306, // 数据库端口
		'user' => 'root', // 用户名
		'password' => 'root', // 密码
		'db' => 'database', // 数据库名
		'table_name' => 'short_link', // 表名
		'ssl' => false // 是否使用ssl连接数据库
	],
];