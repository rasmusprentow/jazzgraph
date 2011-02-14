<?php
// Usage: log = new ActivityLog();
// 			log->logActivity();

class ActivityLog {
	private $filename = 'logs/activitylog';
	
	function __construct(){
		
	}


	function logActivity(){	
		$sql = "INSERT INTO `activity_log` (`time`, `url`, `ip`, `sys_info`) VALUES ('" . time() . "', '{$_SERVER["REQUEST_URI"]}', '{$_SERVER['REMOTE_ADDR']}', '{$_SERVER['HTTP_USER_AGENT']}');";
		mysql_query($sql) or die(mysql_error_trigger($sql));
		//$this->logToFile($user);
	}


	function logToFile()
	{
		// open file
		
		if(!$fd = fopen($this->filename, "a")){
			trigger_error("unable to open");
		}
		
		// append date/time to message
		$str = $_SERVER["REQUEST_URI"] . " | " . showtime(time()) . " | " . $_SERVER['REMOTE_ADDR'];
		// write string
		fwrite($fd, $str . "\n");
		// close file
		fclose($fd);
	}
}

?>