<?php 

require_once __DIR__ . '/Tools.php';
require_once __DIR__ . '/Model.php';

class Controller {
	private static $SUFFIX_MAX_LETTERS = 50;
	private static $LINK_MAX_LETTERS = 500;
	private static $PASSWORD_MAX_LETTERS = 200;
	private static $SPEC_CHARS = ['#', '/', ' ', '\\', '%'];

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
	private static function is_str_has_spec_char($str) :bool {
		$s = self::$SPEC_CHARS;
		foreach ($s as $a) {
			if (stristr($str, $a)) return true;
		}
		return false;
	}

	/* 
	 * @param string $suffix 后缀
	 * 验证后缀是否合法
	*/
	private static function verify_suffix($suffix) :bool {
		// 是否存在
		if (!$suffix) self::reject('缺少必要参数: suffix', 400);
		// 特殊字符
		if (self::is_str_has_spec_char($suffix)) self::reject('后缀不合法', 400);
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

	/* 
	 * @param string $password 密码
	 * 验证访问密码是否合法
	*/
	private static function verify_password($password) :bool {
		// 特殊字符
		if (self::is_str_has_spec_char($password)) self::reject('访问密码不合法', 400);
		// 长度
		if (mb_strlen($password) > self::$PASSWORD_MAX_LETTERS) self::reject('访问密码过长', 400);
		return true;
	}

	public static function get() {
		// 得到要访问的后缀
		$suffix = trim(URI, '/');
		$input_password = $_GET['password'];
		// 验证
		self::verify_suffix($suffix);
		// 去查数据库
		$r = Model::get_base_info_by_suffix($suffix);
		// 是否存在
		if (!$r) self::reject('链接不存在或已被封禁', 404);
		// id&link&password
		$id = $r['id'];
		$link = $r['link'];
		$real_password = $r['password'];
		// 判断密码是否正确
		if ($real_password) {
			// 如果存在密码
			if (!$input_password) {
				// 如果未输入密码
				// 重定向到输入密码页
				self::set_302_header('/?suffix=' . $suffix);
				exit();
			} else if ($real_password != $input_password) {
				// 密码不正确
				self::reject('访问密码不正确', 401);
			} else {
				// 密码正确, 允许继续
			}
		}
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
		$password = $data['password'] ?? null;
		// 验证
		self::verify_suffix($suffix);
		self::verify_link($link);
		self::verify_password($password);
		// 是否已存在同名后缀
		if (Model::get_id_by_suffix($suffix)) self::reject('已存在同名后缀, 换一个吧~', 409);
		// 插入数据
		Model::insert($suffix, $link, $password, time(), IP);
		// 返回结果
		return self::resolve($suffix);
	}
}