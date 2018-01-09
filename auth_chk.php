<?php
include_once "include/config.php";
require_once "Benchmark/Timer.php";
$timer= new Benchmark_Timer();
$timer->start();

require_once "Auth/Auth.php";
//require_once "DB.php";
require_once "classes/adp_core_class.php";
if(!(Auth::staticCheckAuth($options))){  //�ˬd�n�J���p
/*
	//�t�X�F�����{��
	$syear=getNowSemeYear();
	$getThuAuth='http://fsis.thu.edu.tw/wwwfunc/LDAP/auth_nds.php?func_id=AuthId&net_id='.$_POST[username].'&net_pw='.$_POST[password].'&syear='.$syear;
	//$AuthRes=3;
	$AuthRes=file_get_contents($getThuAuth);
	$AuthRes=intval($AuthRes);
	$_SESSION[UserAuthRes]=$AuthRes;
	if($AuthRes>0){  //�q�L�F�����{��
		$sql = "select viewpass from user_info where user_id = '".$_POST[username]."'";
		$viewpass =$dbh->getOne($sql);
		$_POST[password] =pass2compiler($viewpass);
	}else{  //�Ĩ������{��
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
//debug_msg("��".__LINE__."�� auth ", $auth);
//debug_msg("��".__LINE__."�� _SESSION ", $_SESSION);
//die();
require_once "logout.php";
//die();
if ((Auth::staticCheckAuth($options))) {
    $user_id=$_SESSION[_authsession][username];
	//���oip
    if(getenv(HTTP_X_FORWARDED_FOR))
    {
        $ip=getenv(HTTP_X_FORWARDED_FOR);
    }else{
        $ip=getenv(REMOTE_ADDR);
    }

    if(!isset($_SESSION[user_data])){
        $user_data =  new UserData($user_id);   //���ͨϥΪ̰򥻸�ƪ���
        $_SESSION[user_data]=$user_data;
    }else{
        $user_data = $_SESSION[user_data];
    }
	if(!isset($_SESSION[block_content])){
        //�ˬd�n�J���~����
	    /*
		$sql = "select count(user_id) from login_error_list where user_id = '$user_id'";
    	$data =& $dbh->getOne($sql);
    	if($data>=_ERROR_LOGIN){
        	$dbh->disconnect();   //��Ʈw���u
        	session_destroy();
        	Header("Location: index.php");
        }
        */
       	$sql = "select block_content from user_access where access_level = '{$user_data->access_level}'";
       	$_SESSION[block_content] =& $dbh->getOne($sql);

       	   //�����n�JIP�P�ɶ�
        $logintime=date("Y-m-d, H:i:s");
       	$sql="UPDATE user_status SET login_freq=login_freq+1, starttimestamp='{$logintime}', lastip='{$ip}' WHERE user_id ='{$user_id}'";
       	$result = $dbh->query($sql);
       	$query = "INSERT INTO login_list ( user_id, login_ip ) VALUES ( '{$user_id}', '{$ip}' )";
       	//debug_msg("��".__LINE__."�� query ", $query);
       	$result = $dbh->query($query);
       	//debug_msg("��".__LINE__."�� result ", $result);
       	$query = "DELETE from login_error_list WHERE user_id ='{$user_id}'";
   	    $result = $dbh->query($query);
	}

}

