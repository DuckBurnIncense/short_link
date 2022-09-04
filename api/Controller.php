<?php 

class Controller {
	protected static $SUFFIX_MAX_LETTERS = 50;
	protected static $LINK_MAX_LETTERS = 500;
	protected static $PASSWORD_MAX_LETTERS = 200;
	protected static $SPEC_CHARS = ['#', '/', ' ', '\\', '%', '?'];

	/*
	 * @param string $m 可选, 得到指定传参, 不填则返回所有
	 * 得到指定或全部前端传参
	*/
	protected static function get_data($m = null) {
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
	protected static function resolve($data) {
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
	protected static function reject($m, $c) {
		Tools::die($m, $c);
	}

	/* 
	 * @param string $location 要重定向到的url
	 * 返回http302重定向
	*/
	protected static function set_302_header($location) {
		header('HTTP/1.1 302 Move Temporarily');
		header("Location: {$location}");
	}

	/* 
	 * @param string $str 字符串
	 * 字符串中是否含有特殊字符
	*/
	protected static function is_str_has_spec_char($str) :bool {
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
	protected static function verify_suffix($suffix) :bool {
		// 是否存在
		if (!$suffix) self::reject('缺少必要参数: suffix', 400);
		// 特殊字符
		if (self::is_str_has_spec_char($suffix)) self::reject('后缀不合法', 400);
		// 长度
		$len = mb_strlen($suffix);
		if ($len < 1 or $len > self::$SUFFIX_MAX_LETTERS) self::reject('后缀过长, 缩短一点吧~', 400);
		// 是否为已存在的文件
		if (file_exists(__DIR__ . "/..\/" . $suffix)) self::reject('这没有bug, 赶紧给爷爬doge', 400);
		return true;
	}

	/* 
	 * @param string $link 链接
	 * 验证链接是否合法
	*/
	protected static function verify_link($link) :bool {
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
	protected static function verify_password($password) :bool {
		// 特殊字符
		if (self::is_str_has_spec_char($password)) self::reject('访问密码不合法', 400);
		// 长度
		if (mb_strlen($password) > self::$PASSWORD_MAX_LETTERS) self::reject('访问密码过长', 400);
		return true;
	}

	/* 
	 * @param string $link 链接
	 * 把没有协议头的链接加上http协议头
	*/
	protected static function add_http_protocol_header_if_not_exist($link) :string {
		// 如果没有协议头则自动添加
		if (!preg_match("/^((https?:)?\/\/)/", $link)) $link = 'http://' . $link;
		return $link;
	}
}