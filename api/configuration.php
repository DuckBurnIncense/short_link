<?php 
// configuration.php
// 1 为生产环境, 0 为开发环境
return 0 ? [
	'database' => [
		'type' => 'mysql',
		'host' => $_ENV['HOST'],
		'port' => 3306,
		'user' => $_ENV['USERNAME'],
		'password' => $_ENV['PASSWORD'],
		'db' => $_ENV['DATABASE'],
		'table_name' => 'short_link'
	],
] : [
	'database' => [
		'type' => 'mysql',
		'host' => 'localhost',
		'port' => 3306,
		'user' => 'duck',
		'password' => 'duck3329',
		'db' => 'duck',
		'table_name' => 'short_link'
	],
];