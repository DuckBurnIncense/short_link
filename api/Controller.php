<?php 

class Controller {
	protected static $SUFFIX_MAX_LETTERS = 50;
	protected static $LINK_MAX_LETTERS = 500;
	protected static $PASSWORD_MAX_LETTERS = 200;
	protected static $SPEC_CHARS = ['#', '/', ' ', '\\', '%', '?'];

	/**
	 * 得到指定或全部前端传参
	 * @param {String} $m 可选, 得到指定传参, 不填则返回所有
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

	/**
	 * 正常返回内容
	 * @param {mixed} $data 返回给前端的内容
	 */
	protected static function resolve($data) {
		echo json_encode([
			'code'=> 200,
			'data'=> $data
		], JSON_UNESCAPED_UNICODE);
		exit();
	}

	/**
	 * 返回一个错误
	 * @param {string} $m 返回的错误提示
	 * @param {int} $c 返回的错误码 (http状态码)
	 */
	protected static function reject($m, $c) {
		Tools::die($m, $c);
	}

	/** 
	 * 返回http302重定向
	 * @param {string} $location 要重定向到的url
	 */
	protected static function set_302_header($location) {
		header('HTTP/1.1 302 Move Temporarily');
		header("Location: {$location}");
	}

	/** 
	 * 字符串中是否含有特殊字符
	 * @param {string} $str 字符串
	 */
	protected static function is_str_has_spec_char($str) :bool {
		$s = self::$SPEC_CHARS;
		foreach ($s as $a) {
			if (stristr($str, $a)) return true;
		}
		return false;
	}

	/**
	 * 验证后缀是否合法
	 * @param {string} $suffix 后缀
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

	/**
	 * 验证链接是否合法
	 * @param {string} $link 链接
	 */
	protected static function verify_link($link) :bool {
		$url_regexr = "/^((https?:)?\/\/)?(?:www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b(?:[-a-zA-Z0-9()!@:%_\+.~#?&\/\/=]*)$/";
		// 是否存在
		if (!$link) self::reject('缺少必要参数: link', 400);
		// 长度
		$len = mb_strlen($link);
		if ($len < 1 or $len > self::$LINK_MAX_LETTERS) self::reject('源链接过长', 400);
		// "链接" 是否为链接
		if (!preg_match($url_regexr, $link)) self::reject('源链接不合法', 400);
		// 是否为禁止的域名
		$link_domain = parse_url($link, PHP_URL_HOST); // 用户输入的链接的域名
		$banned_domains = CONFIG['banned_domains']; // 禁止的域名的列表
		if (in_array($link_domain, $banned_domains)) self::reject('你无法为该链接生成短链, 因为该链接的域名已被禁止!', 400);
		return true;
	}

	/**
	 * 验证访问密码是否合法
	 * @param {string} $password 密码
	 */
	protected static function verify_password($password) :bool {
		if (!$password) return true;
		// 特殊字符
		if (self::is_str_has_spec_char($password)) self::reject('访问密码不合法', 400);
		// 长度
		if (mb_strlen($password) > self::$PASSWORD_MAX_LETTERS) self::reject('访问密码过长', 400);
		return true;
	}
	
	/** 
	 * 验证过期时间是否合法
	 * @param {string} $expire 时间戳
	 */
	protected static function verify_expire($expire) :bool {
		if (!$expire or $expire == 0) return true;
		if ((!is_numeric($expire)) or (count(str_split($expire . '')) != 10)) self::reject('时间戳错误', 400);
		return true;
	}

	/** 
	 * 把没有协议头的链接加上http协议头
	 * @param {string} $link 链接
	 */
	protected static function add_http_protocol_header_if_not_exist($link) :string {
		// 如果没有协议头则自动添加
		if (!preg_match("/^((https?:)?\/\/)/", $link)) $link = 'http://' . $link;
		return $link;
	}
}