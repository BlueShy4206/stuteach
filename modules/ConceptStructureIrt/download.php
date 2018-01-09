<?php
require_once "include/adp_API.php";

if(!Auth::staticCheckAuth($options)){  //檢查登入狀況
	Header("Location: index.php");
	die();
}

$dlf = _ADP_CS_UPLOAD_PATH.$_REQUEST['pid']."/".$_REQUEST['sid']."/".$_REQUEST['vid']."/".$_REQUEST['uid'].'/'.$_REQUEST['dfn'];
$download=_ADP_EXAM_DB_PATH.$_REQUEST['pid']."/".$_REQUEST['sid']."/".$_REQUEST['vid']."/".$_REQUEST['uid'].'/'.$_REQUEST['dfn'];

if (file_exists($dlf)){
/*
	header("Content-type:application");
	header("Content-Disposition: attachment; filename=".$_REQUEST['dfn']);
	readfile($dlf);

	flush();
*/
	header("Location:".$download);
}else{
  echo "No ".$_REQUEST['dfn']." file!";
}

?>
