<?php 

class LimitModel {
	public static function get_id_counts_by_ip_limit_a_day($ip) :int {
		$ltime = time() - 86400;
		$count = DAO::new()->query_once("SELECT 
			COUNT(`id`) AS 'count' 
			FROM `TN` 
			WHERE `ip` = '{$ip}'
			AND `time` > {$ltime};")['count'];
		return intval($count);
	}
}