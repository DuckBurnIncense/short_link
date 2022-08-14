<?php 

require_once __DIR__ . '/Tools.php';
require_once __DIR__ . '/Model.php';

class Controller {
	private static $SUFFIX_MAX_LETTERS = 50;
	private static $LINK_MAX_LETTERS = 500;

	/*
	 * @param string $m 可选, 得到指定传参, 不填则返回所有
	 * 得到指定或全部前端传参
	*/
	private static function get_data($m = null) {
		if (in_array($_SERVER['REQUEST_METHOD'], ['PUT', 'POST', 'DELETE'])) {
			$data = file_get_contents('php://input');
			$data = Tools::decode_json_data($data);
			return $m ? $data($data)[$m] : $data;
		} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			return $m ? $_GET[$m] : $_GET;
		} else {
			return $_SERVER['REQUEST_METHOD'];
		}
	}

	/* 
	 * @param mixed $data 返回给前端的内容
	 * 正常返回内容
	*/
	private static function resolve($data) {
		echo json_encode([
			'code'=> 200,
			'data'=> $data
		], JSON_UNESCAPED_UNICODE);
		exit();
	}

	/* 
	 * @param string $m 返回的错误提示
	 * @param int $c 返回的错误码 (http状态码)
	 * 返回一个错误
	*/
	private static function reject($m, $c) {
		Tools::die($m, $c);
	}

	/* 
	 * @param string $location 要重定向到的url
	 * 返回http302重定向
	*/
	private static function set_302_header($location) {
		header('HTTP/1.1 302 Move Temporarily');
		header("Location: {$location}");
	}

	/* 
	 * @param string $suffix 后缀
	 * 验证后缀是否合法
	*/
	private static function verify_suffix($suffix) :bool {
		// 是否存在
		if ($suffix == false) self::reject('缺少必要参数: suffix', 400);
		// 特殊字符
		if (
			stristr($suffix, '#') || 
			stristr($suffix, '/') || 
			stristr($suffix, ' ') || 
			stristr($suffix, '\\') || 
			stristr($suffix, '%')) self::reject('源链接不合法', 400);
		// 长度
		$len = mb_strlen($suffix);
		if ($len < 1 or $len > self::$SUFFIX_MAX_LETTERS) self::reject('后缀过长, 缩短一点吧~', 400);
		return true;
	}

	/* 
	 * @param string $link 链接
	 * 验证链接是否合法
	*/
	private static function verify_link($link) :bool {
		$url_regexr = "/^((https?:)?\/\/)?(?:www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b(?:[-a-zA-Z0-9()!@:%_\+.~#?&\/\/=]*)$/";
		// 是否存在
		if (!$link) self::reject('缺少必要参数: link', 400);
		// 长度
		$len = mb_strlen($link);
		if ($len < 1 or $len > self::$LINK_MAX_LETTERS) self::reject('源链接过长', 400);
		if (!preg_match($url_regexr, $link)) self::reject('源链接不合法', 400);
		return true;
	}

	public static function get() {
		// 得到参数
		$suffix = self::get_data('s');
		
		// 验证
		self::verify_suffix($suffix);
		// 去查数据库
		$r = Model::get_link_and_id_by_suffix($suffix);
		// 是否存在
		if (!$r) self::reject('链接不存在或已被封禁', 404);
		// id&link
		$id = $r['id'];
		$link = $r['link'];
		// 增加浏览量
		Model::add_views_by_id($id);
		// 成功, 返回
		self::set_302_header($link);
		exit();
	}

	public static function set() {
		$data = self::get_data();
		$suffix = $data['suffix'];
		$link = $data['link'];
		// 验证
		self::verify_suffix($suffix);
		self::verify_link($link);
		// 是否已存在同名后缀
		if (Model::get_link_and_id_by_suffix($suffix)) self::reject('已存在同名后缀, 换一个吧~', 409);
		Model::insert($suffix, $link, time(), IP);
		return self::resolve($suffix);
	}
}