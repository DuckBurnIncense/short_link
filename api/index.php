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

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/Tools.php';

$config = require __DIR__ . '/configuration.php';
define('CONFIG', $config);

define('IP', $_SERVER["HTTP_CF_CONNECTING_IP"] ?? $_SERVER["REMOTE_ADDR"]);

header('Content-type: application/json; charset=utf-8');
header('X-Powered-By: DBI/1.0.0');

$p = $_GET['p'];
$m = $_SERVER['REQUEST_METHOD'];

if ($p == 'get' and $m == 'GET') {
	Controller::get();
} else if ($p == 'set' and $m == 'POST') {
	Controller::set();
} else {
	Tools::die('请求错误', 400);
}