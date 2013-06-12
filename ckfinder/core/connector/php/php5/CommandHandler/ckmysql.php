<?php
if (!defined('IN_CKFINDER')) {exit;}

class ckmysql {
	private static $config;
	private static $dbconn;

	private static function connect() {
		if (!self::$config) {
			self::$config= parse_ini_file('../../../../config.ini');
		}
		self::$dbconn=mysql_connect(self::$config['db.host'],self::$config['db.username'],self::$config['db.password']);
		mysql_select_db(self::$config['db.dbname']);
	}

	private static function sql($q) {
		mysql_query($q,self::$dbconn);
	}

	private static function disconnect() {
		mysql_close(self::$dbconn);
	}

	public static function DeleteFiles($filepath) {
		self::connect();
		$filepath=str_replace('//', '/', self::$config['path.ckfinder'].$filepath);
		self::sql('DELETE FROM termekkep WHERE url="'.$filepath.'"');
		self::sql('UPDATE termek SET kepurl="" WHERE kepurl="'.$filepath.'"');
		self::sql('UPDATE termekfa SET kepurl="" WHERE kepurl="'.$filepath.'"');
		self::sql('UPDATE cimketorzs SET kepurl="" WHERE kepurl="'.$filepath.'"');
		self::disconnect();
	}

	public static function RenameFile($from,$to) {
		self::connect();
		$to=self::$config['path.ckfinder'].$to;
		$from=self::$config['path.ckfinder'].$from;
		self::sql('UPDATE termekkep SET url="'.$to.'" WHERE url="'.$from.'"');
		self::sql('UPDATE termek SET kepurl="'.$to.'" WHERE kepurl="'.$from.'"');
		self::sql('UPDATE termekfa SET kepurl="'.$to.'" WHERE kepurl="'.$from.'"');
		self::sql('UPDATE cimketorzs SET kepurl="'.$to.'" WHERE kepurl="'.$from.'"');
		self::disconnect();
	}

	public static function DeleteFolder($path) {
		self::connect();
		self::sql('DELETE FROM termekkep WHERE url LIKE "'.$path.'%"');
		self::sql('UPDATE termek SET kepurl="" WHERE kepurl LIKE "'.$path.'%"');
		self::sql('UPDATE termekfa SET kepurl="" WHERE kepurl LIKE "'.$path.'%"');
		self::sql('UPDATE cimketorzs SET kepurl="" WHERE kepurl LIKE "'.$path.'%"');
		self::disconnect();
	}

	public static function RenameFolder($from,$to) {
		self::connect();
		$len=strlen($from)+1;
		self::sql('UPDATE termekkep SET url=CONCAT("'.$to.'",SUBSTRING(url,'.$len.')) WHERE url LIKE "'.$from.'%"');
		self::sql('UPDATE termek SET kepurl=CONCAT("'.$to.'",SUBSTRING(kepurl,'.$len.')) WHERE kepurl LIKE "'.$from.'%"');
		self::sql('UPDATE termekfa SET kepurl=CONCAT("'.$to.'",SUBSTRING(kepurl,'.$len.')) WHERE kepurl LIKE "'.$from.'%"');
		self::sql('UPDATE cimketorzs SET kepurl=CONCAT("'.$to.'",SUBSTRING(kepurl,'.$len.')) WHERE kepurl LIKE "'.$from.'%"');
		self::disconnect();
	}
}