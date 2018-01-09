<?php
require_once "include/adp_API.php";
require_once 'Date.php';


$module_name = basename(dirname(__FILE__));
/*
if($_SESSION['test_times']>0 && $user_data->access_level=='2'){  //一次性帳號已經考過一張試卷
	echo "<br>您的身份為【".$user_data->user_level."】，<br><br>目前您無權限參加測驗！<br><br>";
	FEETER();
	die();
}
  */

if($_POST['system_version']==_SYS_VER){
	$ep_id=$_POST[ep_item][1];
	$ep=explode_ep_id($ep_id);
	$_POST['paper_vol']=$ep[4];
	$_POST['cs_id']=EPid2CSid($ep_id);
	$_POST['exam_type']=$_POST[$ep_id];  //玄機所在
}
//debug_msg("第".__LINE__."行 _POST ", $_POST);
//die();
if(isset($_POST['exam_type'])){
	$sql="select * from exam_type_irt where type_id_irt=".$_POST['exam_type']."";
	//echo "$sql <br>";
	
	$result = $dbh->query($sql);
	$data = $result->fetchRow();
	$_REQUEST['file']=$data['filename_irt'];
	$exec_file=_ADP_PATH."modules/".$module_name."/".$_REQUEST['file'].".php";
	//echo "<pre>";
//	echo "exec_file=  $exec_file <br>";
	//die();
	//echo "auth_ep  <br>";
	//print_r ($_SESSION['auth_ep']);
	//debug_msg("第".__LINE__."行 exec_file ", $exec_file);
	//debug_msg("第".__LINE__."行 _SESSION['auth_ep'] ", $_SESSION['auth_ep']);
	//debug_msg("第".__LINE__."行 _SESSION['open_EP_val'] ", $_SESSION['open_EP_val']);
	//die();
	$_SESSION['AuthCourseID']=$_POST[ep_item][0];
	$this_ep_id=$ep_id.sprintf("%02d",$_POST['exam_type']);
	$test111=in_array($this_ep_id, $_SESSION['auth_ep']);
	//debug_msg("第".__LINE__."行 _SESSION ", $_SESSION);
	//debug_msg("第".__LINE__."行 this_ep_id ", $this_ep_id);
	//debug_msg("第".__LINE__."行 _SESSION['auth_ep'] ", $_SESSION['auth_ep']);
	//die();
	if (!(in_array($this_ep_id, $_SESSION['auth_ep']))) {  //不在開放權限內的試卷，不能使用
		echo "錯誤！您不能參加本次測驗！<br><br>請通知管理者！";
		FEETER();
		header("location:modules.php?op=modload&name=".$module_name."&file=index");
		die();
	}
	//$test111=file_exists($exec_file);
	//echo " $test111 <br> _ADP_PATH="._ADP_PATH;

	if(file_exists($exec_file)){
		require_once($exec_file);
	}else{
		echo "請不要任意輸入網址！！";
	}
}else{
	header("location:modules.php?op=modload&name=".$module_name."&file=index");
}
?>
