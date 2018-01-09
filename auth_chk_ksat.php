<?php
mb_internal_encoding('utf-8');
include_once "include/config.php";
require_once "Benchmark/Timer.php";
$timer= new Benchmark_Timer();
$timer->start();

require_once "Auth/Auth.php";
//require_once "DB.php";
require_once "classes/adp_core_class.php";
$params = array(
//            "dsn" => $dbtype."://".$dbuser.":".$dbpass."@".$dbhost."/".$database,
			"dsn" =>$DSN,
			"table" => $auth_table,
            "usernamecol" => "user_id",
            "passwordcol" => "pass",
//			'advancedsecurity' => 'true',
			//預設用md5
			//'cryptType' => "none",
//			"db_fields" => array('id','uname','realname','appointment')
            );
$auth = new Auth("DB", $params,null);
$auth->setIdle(_IDLETIME);
$auth->setExpire($expire);
//$auth->setAdvancedSecurity();
$auth->start();
require_once "logout.php";

if ($auth->checkAuth()) {
	$user_id=$auth->getUsername();
	
	$user_data = new UserData($user_id);   //產生使用者基本資料物件
	//echo $user_id."  $user_data->login_freq  <br>";
	$now_time=time();

	if($user_data->auth_stop_time<$now_time || $user_data->access_level<1 || $user_data->organization_id=='000000' || $user_data->uname==""){
		Header("Location: index.php?act=logout");  //超過使用期限帳號、被停權帳號、站長未開通之帳號   強制登出
		die();
	}

	$sql = "select block_content from user_access where access_level = '{$user_data->access_level}'";
	$data =& $dbh->getOne($sql);
	$_SESSION['block_content']=$data;
	//include_once "functionBar_hidden.php";
	if(!isset($_SESSION['first_login_chk'])){
		$_SESSION['first_login_chk']=1;
	}else{
		$_SESSION['first_login_chk']=0;
	}
	if($_SESSION['first_login_chk']==1){
		if(getenv(HTTP_X_FORWARDED_FOR)) 
        {
			$ip=getenv(HTTP_X_FORWARDED_FOR);
	    }else{
		    $ip=getenv(REMOTE_ADDR);
	    }
		if($user_data->access_level=="2" && $user_data->login_freq>0){
			Header("Location: index.php?act=logout");  //一次性帳號   強制登出
			die();
		}
		$logintime=date("Y-m-d, H:i:s");
		$sql="UPDATE user_status SET login_freq=login_freq+1, starttimestamp='{$logintime}', lastip='{$ip}' WHERE user_id ='{$user_id}'";
		$result = $dbh->query($sql);
		$query = "INSERT INTO login_list ( user_id ) VALUES ( '{$user_id}' )";
		$result = $dbh->query($query);
	}
}else{
	unset($_SESSION['first_login_chk']);
	Header("Location: index.php");
	die();
}
?>

