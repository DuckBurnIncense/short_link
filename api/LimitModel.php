<?php 

class LimitModel {
	public static function get_ip_request_id_and_time_limit_a_day($ip) :array {
		$ltime = time() - 86400;
		$info = DAO::new()->query("SELECT 
			`id`, 
			`time` 
			FROM `PRE_rate_limit` 
			WHERE `ip` = '{$ip}'
			AND `time` > {$ltime};");
		foreach ($info as &$i) {
			$i['id'] = intval($i['id']);
			$i['time'] = intval($i['time']);
		}
		return $info ?? [];
	}

	public static function add_access_record($ip, $time, $status, $uri) :bool {
		$pp = DAO::new()->pp("INSERT INTO `PRE_rate_limit` 
			(`ip`, `time`, `status`, `uri`)
			VALUES
			(?, ?, ?, ?)");
		$pp->bv(1, $ip);
		$pp->bv(2, $time);
		$pp->bv(3, $status);
		$pp->bv(4, $uri);
		return $pp->exec();
	}
}