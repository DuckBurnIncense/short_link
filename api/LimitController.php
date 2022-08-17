<?php 

require_once __DIR__ . '/LimitModel.php';

class LimitController extends Controller {
	private static function get_ip_request_id_and_time_limit_a_day($ip) :array {
		return LimitModel::get_ip_request_id_and_time_limit_a_day($ip);
	}

	private static function ban_ip($ip, $cf_zone_id, $cf_authorization) {
		$url = "https://api.cloudflare.com/client/v4/zones/{$cf_zone_id}/firewall/access_rules/rules";
		$data = [
			'mode' => 'block',
			'configuration' => [
				'target' => 'ip',
				'value' => $ip
			],
			'notes' => 'auto_ban'
		];
		$header = [
			'Content-type: application/json',
			"Authorization: {$cf_authorization}"
		];
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
	}

	public static function check() {
		// 当前ip
		$ip = IP;
		// 配置
		$c = CONFIG['limit'];
		// 是否启用
		if (!$c['enable']) return true;
		// 每分钟最大请求数
		$max_request_times_per_minute = $c['max_request_times_per_minute'];
		// 每天最大请求数
		$max_request_times_per_day = $c['max_request_times_per_day'];
		// cloudflare的zone_id
		$cf_zone_id = $c['cf_zone_id'];
		// cf api header头的authorization字段
		$cf_authorization = $c['cf_authorization'];
		// 当前时间
		$now_time = time();
		// 一天内该ip的所有请求
		$all_request = self::get_ip_request_id_and_time_limit_a_day($ip);
		// 一分钟内该ip的所有请求数
		$minute_count = count(
			array_filter($all_request, 
				function ($i) use ($now_time) { 
					return $i['time'] > $now_time - 60; 
				}
			)
		);
		// 一天内该ip的所有请求数
		$day_count = count($all_request);
		// 是否大于限制
		if (
			$minute_count > $max_request_times_per_minute 
			or
			$day_count > $max_request_times_per_day
		) {
			// ban
			self::ban_ip($ip, $cf_zone_id, $cf_authorization);
			LimitModel::add_access_record($ip, $now_time, 403, URI);
			self::reject('访问过快, 疑似刷接口, IP喜提永封~', 403);
		}
		LimitModel::add_access_record($ip, $now_time, 200, URI);
		return true;
	}
}