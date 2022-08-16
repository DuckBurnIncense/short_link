<?php 

require_once __DIR__ . '/DAO.php';

class RecordModel {
	public static function get_id_by_suffix($suffix) {
		$pp = DAO::new()->pp("SELECT `id` FROM `PRE_record` WHERE `suffix` = ? AND `ban` = 0;");
		$pp->bv(1, $suffix);
		$pp->exec();
		$id = $pp->gd()[0]['id'];
		if (!$id) return null;
		return intval($id);
	}

	public static function get_base_info_by_suffix($suffix) {
		$pp = DAO::new()->pp("SELECT `id`, `link`, `password` FROM `PRE_record` WHERE `suffix` = ? AND `ban` = 0;");
		$pp->bv(1, $suffix);
		$pp->exec();
		$r = $pp->gd()[0];
		if (!$r) return null;
		$r['id'] = intval($r['id']);
		return $r;
	}

	public static function add_views_by_id(int $id) :bool {
		return DAO::new()->exec("UPDATE `PRE_record` SET `views` = `views` + 1 WHERE `id` = {$id};");
	}

	public static function insert($suffix, $link, $password, $time, $ip) :bool {
		$d = DAO::new();
		$pp = $d->pp("INSERT INTO `PRE_record` 
			(`suffix`, `link`, `password`, `time`, `ip`) 
			VALUES 
			(?, ?, ?, ?, ?);");
		$pp->bv(1, $suffix);
		$pp->bv(2, $link);
		$pp->bv(3, $password);
		$pp->bv(4, $time);
		$pp->bv(5, $ip);
		return $pp->exec();
	}
}