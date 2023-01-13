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
		'table_prefix' => 'eb5a', // 表前缀, 一般不用改
		'ssl' => false // 是否使用ssl连接数据库
	],
	'banned_domains' => [ // 禁止的域名 (即: 长链接的域名不能为这些). 主要用于防止套娃. 注意: 一定要把你自己网站的域名也加上!
		'www.xn--eb5a.ml',
		'xn--eb5a.ml',
		'www.gg.gg',
		'gg.gg',
		'www.dlj.bz',
		'dlj.bz'
	]
];