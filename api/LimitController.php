<?php 

require_once __DIR__ . '/LimitModel.php';

class LimitController {
	private static function get_ip_set_times($ip) :int {
		return LimitModel::get_id_counts_by_ip_limit_a_day($ip);
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
		$ip = IP;
		$c = CONFIG['limit'];
		if (!$c['enable']) return true;
		$max_request_times_per_day = $c['max_request_times_per_day'];
		$cf_zone_id = $c['cf_zone_id'];
		$cf_authorization = $c['cf_authorization'];
		$count = self::get_ip_set_times($ip);
		if ($count > $max_request_times_per_day) {
			// ban
			self::ban_ip($ip, $cf_zone_id, $cf_authorization);
		}
		return true;
	}
}