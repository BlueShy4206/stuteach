<?php
include_once "include/config.php";
require_once "Benchmark/Timer.php";
$timer= new Benchmark_Timer();
$timer->start();

require_once "Auth/Auth.php";
require_once "classes/adp_core_class.php";
$params = array(
			"dsn" =>$DSN,
			"table" => $auth_table,
            "usernamecol" => "user_id",
            "passwordcol" => "pass",
            );
$auth =  new Auth("DB", $params,null);
$auth->setIdle(_IDLETIME);
$auth->setExpire($expire);
$auth->start();
require_once "logout.php";

if ($auth->checkAuth()) {
	$user_id=$auth->getUsername();
	$user_data =  new UserData($user_id);   //產生使用者基本資料物件
	$sql = "select block_content from user_access where access_level = '{$user_data->access_level}'";
	$data =& $dbh->getOne($sql);
	$_SESSION[block_content]=$data;
	//include_once "functionBar_hidden.php";
}

