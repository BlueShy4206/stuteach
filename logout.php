<?php
//echo __FILE__.__LINE__.var_dump($_REQUEST).var_dump($_GET);
if ($_GET['act'] ==  'logout') {
	$logouttime=date("Y-m-d, H:i:s");
	$sql="UPDATE user_status SET stoptimestamp='{$logouttime}' WHERE user_id ='{$_SESSION['_authsession']['username']}'";
	$result = $dbh->query($sql);

	$auth->logout();
	$dbh->disconnect();   //資料庫離線
	session_destroy();
	//$msg='您已經登出！';
	Header("Location: index.php");
    die();
}
?>

