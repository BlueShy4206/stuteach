<?php
//mb_internal_encoding('utf-8');
require_once "include/config.php";
require_once "Benchmark/Timer.php";
$timer= new Benchmark_Timer();
$timer->start();
require_once "Auth/Auth.php";
//require_once "DB.php";
require_once "classes/adp_core_class.php";


if(!(Auth::staticCheckAuth($options))){  //檢查登入狀況
/*
	//配合東海的認證
	$syear=getNowSemeYear();
	$getThuAuth='http://fsis.thu.edu.tw/wwwfunc/LDAP/auth_nds.php?func_id=AuthId&net_id='.$_POST[username].'&net_pw='.$_POST[password].'&syear='.$syear;
	//debug_msg("第".__LINE__."行 getThuAuth ", $getThuAuth);
	//$AuthRes=2;
	$AuthRes=file_get_contents($getThuAuth);
	$AuthRes=intval($AuthRes);

	$_SESSION[UserAuthRes]=$AuthRes;

	if($AuthRes>0){  //通過東海的認證
		$sql = "select viewpass from user_info where user_id = '".$_POST[username]."'";
		$viewpass =$dbh->getOne($sql);
		$_POST[password] =pass2compiler($viewpass);
	}else{  //採取本機認證
		;
	}
*/
    $params = array(
		"dsn" =>$DSN,
		"table" => $auth_table,
        "usernamecol" => "user_id",
        "passwordcol" => "pass",
    );
    //debug_msg("第".__LINE__."行 params ", __FILE__.$params);
    $auth =  new Auth("DB", $params,null);
    $auth->setIdle(_IDLETIME);
    $auth->setExpire($expire);
    $auth->start();
}

$StaticAuth=Auth::staticCheckAuth($options);
//debug_msg("第".__LINE__."行 AuthRes ", $AuthRes);
//debug_msg("第".__LINE__."行 auth ", $auth);
//debug_msg("第".__LINE__."行 _POST ", $_POST);
//debug_msg("第".__LINE__."行 _SESSION ", $_SESSION);
//debug_msg("第".__LINE__."行 StaticAuth ", $StaticAuth);
//var_dump($StaticAuth);
//die();


//if ($auth->checkAuth()) {

if($_SESSION[UserAuthRes]>0 || $StaticAuth){
	$rid=time().rand(1 , 9999);
	Header("Location: modules.php?op=main");
	exit;
}
