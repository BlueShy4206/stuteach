<?php
include_once "include/config.php";
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
	//$AuthRes=3;
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
    $auth =  new Auth("DB", $params,null);
    $auth->setIdle(_IDLETIME);
    $auth->setExpire($expire);
    $auth->start();
}
//debug_msg("第".__LINE__."行 auth ", $auth);
//debug_msg("第".__LINE__."行 _SESSION ", $_SESSION);
//die();
require_once "logout.php";
//die();
if ((Auth::staticCheckAuth($options))) {
    $user_id=$_SESSION[_authsession][username];
	//取得ip
    if(getenv(HTTP_X_FORWARDED_FOR))
    {
        $ip=getenv(HTTP_X_FORWARDED_FOR);
    }else{
        $ip=getenv(REMOTE_ADDR);
    }

    if(!isset($_SESSION[user_data])){
        $user_data =  new UserData($user_id);   //產生使用者基本資料物件
        $_SESSION[user_data]=$user_data;
    }else{
        $user_data = $_SESSION[user_data];
    }
	if(!isset($_SESSION[block_content])){
        //檢查登入錯誤次數
	    /*
		$sql = "select count(user_id) from login_error_list where user_id = '$user_id'";
    	$data =& $dbh->getOne($sql);
    	if($data>=_ERROR_LOGIN){
        	$dbh->disconnect();   //資料庫離線
        	session_destroy();
        	Header("Location: index.php");
        }
        */
       	$sql = "select block_content from user_access where access_level = '{$user_data->access_level}'";
       	$_SESSION[block_content] =& $dbh->getOne($sql);

       	   //紀錄登入IP與時間
        $logintime=date("Y-m-d, H:i:s");
       	$sql="UPDATE user_status SET login_freq=login_freq+1, starttimestamp='{$logintime}', lastip='{$ip}' WHERE user_id ='{$user_id}'";
       	$result = $dbh->query($sql);
       	$query = "INSERT INTO login_list ( user_id, login_ip ) VALUES ( '{$user_id}', '{$ip}' )";
       	//debug_msg("第".__LINE__."行 query ", $query);
       	$result = $dbh->query($query);
       	//debug_msg("第".__LINE__."行 result ", $result);
       	$query = "DELETE from login_error_list WHERE user_id ='{$user_id}'";
   	    $result = $dbh->query($query);
	}

}

