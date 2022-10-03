<?php 
/*
 *                        _oo0oo_
 *                       o8888888o
 *                       88" . "88
 *                       (| -_- |)
 *                       0\  =  /0
 *                     ___/`---'\___
 *                   .' \\|     |// '.
 *                  / \\|||  :  |||// \
 *                 / _||||| -:- |||||- \
 *                |   | \\\  - /// |   |
 *                | \_|  ''\---/''  |_/ |
 *                \  .-\__  '-'  ___/-. /
 *              ___'. .'  /--.--\  `. .'___
 *           ."" '<  `.___\_<|>_/___.' >' "".
 *          | | :  `- \`.;`\ _ /`;.`/ - ` : | |
 *          \  \ `_.   \_ __\ /__ _/   .-` /  /
 *      =====`-.____`.___ \_____/___.-`___.-'=====
 *                        `=---='
 * 
 * 
 *      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * 
 *            佛祖保佑     永不宕机     永无BUG
**/

// 自动加载
spl_autoload_register(function($class) {
	require_once __DIR__ . '/' . $class . '.php';
});

// 配置文件
$config = require __DIR__ . '/configuration.php';
define('CONFIG', $config);

// IP地址
define('IP', $_SERVER["HTTP_CF_CONNECTING_IP"] ?? $_SERVER["REMOTE_ADDR"]);

// 请求的页面
$uri = $_SERVER['REQUEST_URI'];
if (false !== $pos = strpos($uri, '?')) $uri = substr($uri, 0, $pos);
$uri = rawurldecode($uri);
define('URI', $uri);

// 请求方法
define('REQUEST_METHOD', $_SERVER['REQUEST_METHOD']);

// header头
header('Content-type: application/json; charset=utf-8');
header('X-Powered-By: DBI/1.0.0');

// router
if (REQUEST_METHOD == 'GET') {
	RecordController::get();
} else if (REQUEST_METHOD == 'POST' and URI == '/api/set') {
	RecordController::set();
} else {
	Tools::die('请求错误', 400);
}