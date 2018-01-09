<?php
//require_once "HTML/QuickForm.php";
//require_once 'HTTP/Upload.php';
require_once 'Date.php';
require_once "include/adp_API.php";
$module_name = basename(dirname(__FILE__));

if(!$auth->checkAuth()||$user_data->access_level<=90){
	FEETER();
	die();
}
//重新設定授權終止時間
$auth_start[0] = date("Y-m-d"); 
//$auth_start[0]=$_POST[start_date][Y].'-'.sprintf("%02d", $_POST[start_date][m]).'-'.sprintf("%02d",$_POST[start_date][d]);
	$auth_start[1]=$auth_start[0]." 00:00:00";
	$mydate = new Date($auth_start[1]);
	$auth_start[2]=$mydate->getDate(DATE_FORMAT_UNIXTIME) ;

	$auth_stop[0]='2008-10-10';
	$auth_stop[1]=$auth_stop[0]." 23:59:59";
	$mydate = new Date($auth_stop[1]);
	$auth_stop[2]=$mydate->getDate(DATE_FORMAT_UNIXTIME) ;
	echo "<pre>";
	print_r($auth_start);
	print_r($auth_stop);

	
		$table_name   = 'user_status';
		$table_values = array(
			'auth_start_time' => $auth_start[2],
			'auth_stop_time' => $auth_stop[2],
			'auth_start_date' => $auth_start[0],
			'auth_stop_date' => $auth_stop[0]
		);
		$table_field='auth_stop_date=0000-00-00';
		$result = $dbh->autoExecute($table_name, $table_values,
                        DB_AUTOQUERY_UPDATE, $table_field);

?>

