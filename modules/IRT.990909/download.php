<?php

require_once "include/adp_API.php";

if(!Auth::staticCheckAuth($options)){  //檢查登入狀況
	Header("Location: index.php");
	die();
}

$dlf = _ADP_TMP_UPLOAD_PATH.$_SESSION['dfn'];
$filename=$_SESSION['dfn'];
$dfn=$_SESSION['dfn'];

//取得$dlf的內容
$data=file_get_contents($dlf);

//強制下載 $_SESSION['dfn']
force_download($data,$filename);

unset($_SESSION['dfn']);
unlink($dlf);

?>