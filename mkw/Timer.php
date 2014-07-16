<?php
namespace mkw;


class Timer {

	private static $starttimes = array();
	private static $stoptimes = array();
	private static $runtimes = array();
        private static $paused = array();


	public static function start($timername) {
		$mtime = microtime();
		$mtime = explode(' ',$mtime);
                if (!array_key_exists($timername, self::$paused)) {
                    self::$runtimes[$timername] = 0;
                }
                unset(self::$paused[$timername]);
		self::$starttimes[$timername] = $mtime[1] + $mtime[0];
	}

	public static function stop($timername) {
		$mtime = microtime();
		$mtime = explode(' ',$mtime);
		self::$stoptimes[$timername] = $mtime[1] + $mtime[0];
		self::$runtimes[$timername] += self::$stoptimes[$timername] - self::$starttimes[$timername];
		return self::$runtimes[$timername];
	}

        public static function pause($timername) {
                $mtime = microtime();
		$mtime = explode(' ',$mtime);
                self::$stoptimes[$timername] = $mtime[1] + $mtime[0];
		self::$runtimes[$timername] += self::$stoptimes[$timername] - self::$starttimes[$timername];
                self::$paused[$timername] = true;
                return self::$runtimes[$timername];
        }

	public static function continueTimer($timername) {
		if (array_key_exists($timername, self::$starttimes)) {
			self::stop($timername);
			unset(self::$starttimes[$timername]);
			unset(self::$stoptimes[$timername]);
		}
		else {
			self::start($timername);
		}
	}

	public static function clearTimer($timername) {
		unset(self::$starttimes[$timername]);
		unset(self::$stoptimes[$timername]);
		unset(self::$runtimes[$timername]);
	}

	public static function getRuntime($timername, $clear = true) {
		$result = self::$runtimes[$timername];
		if ($clear) {
			unset(self::$runtimes[$timername]);
		}
		return $result;
	}
}