<?php

require_once "logout.php";
if($_REQUEST[screen]=="all"){
   require_once "head_full_src.php";
}else{
   require_once "head.php";
}
require_once "auth_chk.php";

echo '</div>';
echo '</TD></TR></TABLE>';


if(!Auth::staticCheckAuth($options)){  //檢查登入狀況
	Header("Location: index.php");
	die();
}

if ($_GET["file"]) {
        if (eregi("http:\/\/", $_GET["file"])||eregi("ftp:\/\/", $_GET["file"])||eregi("[[:alnum:]]+\.[[:alnum:]]+\.", $_GET["file"])||eregi("[[:alnum:]]+\.[[:alnum:]]+/", $_GET["file"])) { echo "Hi, How do u do ?"; exit; }
}


echo '<TABLE width="960" CELLPADDING="0" CELLSPACING="0" BORDER="0" align="center">
	<TR>
		<TD COLSPAN="3" WIDTH="960" HEIGHT="58" background="images/stu_inner_2.jpg" align="right">';

if($_REQUEST['file']=='IRT' || $_REQUEST['screen']=="all"){
	echo"";
}else{
   echo '<div align="center">'.$_SESSION['block_content'].'</div>';
}

echo '</TD>
	</TR>
	<TR>
		<TD WIDTH="13" background="images/stu_inner_3.jpg" align="center"></TD>';
echo '<TD WIDTH="935" height="450" valign=top align=center>';
echo '<div id="main">';
$op=$_REQUEST['op'];
if($op=="modload"){
//		if (!isset($mainfile)) { include("mainfile.php"); }
	if (ereg("\.\.",$name) || ereg("\.\.",$file)) {
		echo "You are so cool";
		die();
	} else {
		$exec_file="modules/".$_REQUEST['name']."/".$_REQUEST['file'].".php";
		if(file_exists($exec_file)){
			include($exec_file);
		}else{
			echo "請不要任意輸入網址！！";
		}
	}
}elseif($op=="main"){

	echo "<font class=\"title1\"><center><br><b>歡迎光臨！<br>";
echo "<br>您是　".$user_data->user_id."【".$user_data->uname."】，身份：".$user_data->user_level."<br><br>";
	//echo $user_data->city_name.$user_data->organization_name."".$user_data->cht_class."</b></font><br><br>";

	echo "<br><br>";
}elseif($op=="logout"){
	$logouttime=date("Y-m-d, H:i:s");
	$sql="UPDATE user_status SET stoptimestamp='{$logouttime}' WHERE user_id ='{$_SESSION['_authsession']['username']}'";
	$result = $dbh->query($sql);

	$auth->logout();
	$dbh->disconnect();   //資料庫離線
	session_destroy();
	//$msg='您已經登出！';
	Header("Location: index.php");
}
else{
    die ("抱歉！您的權限不符");
}
echo '</div>';
		echo '</TD>
		<TD WIDTH="12" background="images/stu_inner_5.jpg" align="center"></TD>
	</TR>
	<TR>
	<TD COLSPAN="3"><IMG WIDTH="960" HEIGHT="31" SRC="images/stu_inner_6.jpg" BORDER="0"></TD>
</TR>
</TABLE>';









/*
echo '<TABLE width="959" CELLPADDING="0" CELLSPACING="0" BORDER="0" align="center">
	<TR><TD><IMG WIDTH="47" HEIGHT="46" SRC="images/tcc02_960_2.jpg" BORDER="0"></TD>';
	
echo '<TD WIDTH="864" HEIGHT="46" align="center" background="images/tcc_bk1.jpg">';
//if($_REQUEST['file']=='AdaptiveTestBox' || $_REQUEST['screen']=="all"){
if($_REQUEST['file']=='IRT' || $_REQUEST['screen']=="all"){
	echo"";
}else{
   echo $_SESSION['block_content'];
}
echo "</TD>";
echo '<TD><IMG WIDTH="48" HEIGHT="46" SRC="images/tcc02_960_4.jpg" BORDER="0"></TD>
	</TR>
	<TR>
		<TD><IMG WIDTH="47" HEIGHT="4" SRC="images/tcc02_960_5.jpg" BORDER="0"></TD>
		<TD><IMG WIDTH="864" HEIGHT="4" SRC="images/tcc02_960_6.jpg" BORDER="0"></TD>
		<TD><IMG WIDTH="48" HEIGHT="4" SRC="images/tcc02_960_7.jpg" BORDER="0"></TD>
	</TR>
	</TABLE>';

echo '<div id="main">';
echo '<TABLE width="959" CELLPADDING="0" CELLSPACING="0" BORDER="0" align="center">
	<TR>
		<TD WIDTH="47" HEIGHT="484" background="images/tcc02_960_11.jpg" BORDER="0"></TD>';
echo '<TD WIDTH="864" HEIGHT="484" background="images/tcc02_960_9.jpg" BORDER="0" align="center" valign="top">';

$op=$_REQUEST['op'];
if($op=="modload"){
//		if (!isset($mainfile)) { include("mainfile.php"); }
	if (ereg("\.\.",$name) || ereg("\.\.",$file)) {
		echo "You are so cool";
		die();
	} else {
		$exec_file="modules/".$_REQUEST['name']."/".$_REQUEST['file'].".php";
		if(file_exists($exec_file)){
			include($exec_file);
		}else{
			echo "請不要任意輸入網址！！";
		}
	}
}elseif($op=="main"){

	echo "<font class=\"title1\"><center><br><b>歡迎光臨！<br>";
echo "<br>您是　".$user_data->user_id."【".$user_data->uname."】，身份：".$user_data->user_level."<br><br>";
	echo $user_data->city_name.$user_data->organization_name."".$user_data->cht_class."</b></font><br><br>";

	echo "<br><br>";
}else{
    die ("抱歉！您的權限不符");
}
echo '</TD>
		<TD WIDTH="48" HEIGHT="484" background="images/tcc02_960_13.jpg" BORDER="0"></TD>
	</TR></TABLE>';
echo '</div>';
echo '<div id="Layer1" style="position:absolute; left:1px; top:1px; width:1px; height:1px; z-index:1">';
require_once "feet.php"; 

*/

