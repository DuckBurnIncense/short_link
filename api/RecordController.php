<?php 

class RecordController extends Controller {
	public static function get() {
		// 得到要访问的后缀
		$suffix = trim(URI, '/');
		$input_password = $_GET['password'];
		// 验证
		self::verify_suffix($suffix);
		// 去查数据库
		$r = RecordModel::get_base_info_by_suffix($suffix);
		// 是否存在
		if (!$r) self::reject('链接不存在或已被封禁', 404);
		// id&link&password&expire
		$id = $r['id'];
		$link = self::add_http_protocol_header_if_not_exist($r['link']);
		$real_password = $r['password'];
		$expire = $r['expire'];
		// 是否过期
		if ($expire and time() > $expire) self::reject('链接已于 ' . $expire . ' 过期', 403);
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
				// 增加浏览量
				RecordModel::add_views_by_id($id);
				// 正常返回
				return self::resolve($link);
			}
		} else {
			// 无需密码
			// 增加浏览量
			RecordModel::add_views_by_id($id);
			// 成功, 返回
			self::set_302_header($link);
			exit();
		}
	}

	public static function set() {
		$data = self::get_data();
		$suffix = $data['suffix'];
		$link = $data['link'];
		$password = $data['password'] ?? null;
		$expire = $data['expire'] ?? null;
		// 验证
		self::verify_suffix($suffix);
		self::verify_link($link);
		self::verify_password($password);
		self::verify_expire($expire);
		// 是否已存在同名后缀
		if (RecordModel::get_id_by_suffix($suffix)) self::reject('已存在同名后缀, 换一个吧~', 409);
		// 插入数据
		RecordModel::insert($suffix, $link, $password, $expire, time(), IP);
		// 返回结果
		return self::resolve($suffix);
	}
}