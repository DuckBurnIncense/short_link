<?php 

require_once __DIR__ . '/DAO.php';

class Model {
	public static function get($suffix) {
		$pp = DAO::new()->pp("SELECT `link` FROM `TN` WHERE `suffix` = ?;");
		$pp->bv(1, $suffix);
		$pp->exec();
		return $pp->gd()[0]['link'];
	}

	public static function set($suffix, $link, $ip) :bool {
		$d = DAO::new();
		$pp = $d->pp("INSERT INTO `TN` 
			(`suffix`, `link`, `ip`) 
			VALUES 
			(?, ?, INET_ATON(?));");
		$pp->bv(1, $suffix);
		$pp->bv(2, $link);
		$pp->bv(3, $ip);
		return $pp->exec();
	}
}