<?php
require_once "include/adp_API.php";
require_once 'Date.php';

if(!$auth->checkAuth()){
	FEETER();
	die();
}

$module_name = basename(dirname(__FILE__));
if($_SESSION['test_times']>0 && $user_data->access_level=='2'){  //一次性帳號已經考過一張試卷
	echo "<br>您的身份為【".$user_data->user_level."】，<br><br>目前您無權限參加測驗！<br><br>";
	FEETER();
	die();
}

if($_POST['system_version']==_SYS_VER){
	$ep_id=get_epid($_POST[ep_item][0],$_POST[ep_item][1],$_POST[ep_item][2],$_POST[ep_item][3],$_POST[ep_item][4]);
	$_POST['paper_vol']=$_POST[ep_item][4];
	$_POST['cs_id']=get_csid($_POST[ep_item][0],$_POST[ep_item][1],$_POST[ep_item][2],$_POST[ep_item][3]);
	$_POST['exam_type']=$_POST[$ep_id];  //玄機所在
}
//echo "<hr>";
//print_r($_POST);
//die();
if(isset($_POST['exam_type'])){
	$sql="select * from exam_type where type_id=".$_POST['exam_type']."";
	//echo "$sql <br>";
	
	$result = $dbh->query($sql);
	$data = $result->fetchRow();
	$_REQUEST['file']=$data['filename'];
	$exec_file=_ADP_PATH."modules/".$module_name."/".$_REQUEST['file'].".php";
	//echo "<pre>";
	//echo "exec_file=  $exec_file <br>";
	//die();
	//echo "auth_ep  <br>";
	//print_r ($_SESSION['auth_ep']);
	
	$this_ep_id=$ep_id.sprintf("%02d",$_POST['exam_type']);
	$test111=in_array($this_ep_id, $_SESSION['auth_ep']);
	//echo " $this_ep_id    <br>  $test111 <br>";
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