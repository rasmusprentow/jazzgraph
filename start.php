<?
//include "db_conn.php";
//include "ActivityLog_class.php";
include "log/db_conn.php";
connect_db();
include "log/ActivityLog_class.php";
$log = new ActivityLog();
$log->logActivity();
//echo "hej";

?>