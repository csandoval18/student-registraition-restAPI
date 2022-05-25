<?php
class Db {
	private static $db;
	public static function getDb() {
    if (!self::$db) {
			try {
				$dsn = 'mysql:host=localhost;dbname=uww';
				self::$db = new PDO($dsn, 'root', 'root');
				self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				self::$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
			} catch (PDOException $e) {
				die('Connection error: ' . $e->getMessage());
			}
		}
		return self::$db;
	}
}
