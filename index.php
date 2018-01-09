<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>師資生電腦適性測驗診斷系統</title>
</head>
<body onLoad="window.moveTo(0,0); window.resizeTo(screen.width,screen.height);" BGCOLOR="#FFFFFF" LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0">
	<TABLE CELLPADDING="0" CELLSPACING="0" BORDER="0" align="center" background="images/stuteach_login_960.jpg" BORDER="0" WIDTH="960" HEIGHT="719">
	<TR>
		<TD WIDTH="960" HEIGHT="316"></TD>
	</TR>
	<TR>
	  <TD WIDTH="960" HEIGHT="170" valign="top" align="right">
<?php
session_start();
/*
require_once 'login.php';
//require_once 'logout.php';
if ($_GET['act'] ==  'logout') {
	$logouttime=date("Y-m-d, H:i:s");
	$sql="UPDATE user_status SET stoptimestamp='{$logouttime}' WHERE user_id ='{$_SESSION['_authsession']['username']}'";
	$result = $dbh->query($sql);
	
	$auth->logout();
	$dbh->disconnect();   //資料庫離線
	session_destroy();
	$msg='您已經登出！';

	Header("Location: index.php");
    die();
}
*/
if ($_GET['act'] ==  'logout') {

	include_once "include/config.php";
	require_once "classes/adp_core_class.php";
	require_once "Auth/Auth.php";

	$logouttime=date("Y-m-d, H:i:s");
	$sql="UPDATE user_status SET stoptimestamp='{$logouttime}' WHERE user_id ='{$_SESSION['_authsession']['username']}'";
	$result = $dbh->query($sql);

	$dbh->disconnect();   //資料庫離線
	session_destroy();
	$msg='您已經登出！';

	Header("Location: index.php");
    die();
}

require_once 'login.php';



//echo '<div id="Layer2" style="position:absolute; left:1px; top:1px; width:1px; height:1px; z-index:1">';
echo '
</TD>
</TR>
</TABLE>
<br>
</BODY>
</HTML>';

