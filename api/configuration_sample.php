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
		'table_prefix' => 'eb5a', // 表前缀
		'ssl' => false // 是否使用ssl连接数据库
	],
	'limit' => [
		'enable' => true, // 是否启用
		'max_request_times_per_day' => 50, // 每24小时单ip最大访问数
		'max_request_times_per_minute' => 10, // 每分钟单ip最大访问数
		'cf_zone_id' => '', // cloudflare的zone id
		'cf_authorization' => '', // cloudflare的访问token
		'urgent_mode_htaccess_path' => __DIR__ . '/../.htaccess' // .htaccess 文件的路径, 用于紧急模式下屏蔽一切请求 (紧急模式: 每分钟单IP请求数大于允许值的1.2倍, 因cloudflare的ip阻止不及时, 需要网站手动屏蔽所有请求30秒)
	]
];