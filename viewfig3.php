<?php
//require_once "auth_chk.php"; 
require_once "include/config.php";
require_once "include/adp_core_function.php";
/*
if(!$auth->checkAuth()){
	FEETER();
	die();
}
*/
if(isset($_GET['cs_id'])){
	$data=explode_cs_id($_GET['cs_id']);
	$path="";
	for($i=0;$i<count($data);$i++){
		$path.=intval($data[$i])."/";
	}
	$_SESSION['cs_path']=_ADP_CS_UPLOAD_PATH.$path;
}
//echo "<pre>";
//print_r($_GET);
//print_r($_SESSION);
$filename=compiler2str($_GET['list']).'.'.$_GET['tpp'];
$obj_path=$_SESSION['cs_path'].$filename;
echo $obj_path."<br>";
echo $filename."<br>";
//die();
$data=file_get_contents($obj_path);

//±j¨î¤U¸ü $_SESSION['dfn']
$newfilename=time().'.'.$_GET['tpp'];
force_download($data, $newfilename);

unlink($obj_path);
//die();
/*
ob_clean(); 
header("Content-Transfer-Encoding: binary"); 
header("Content-Type: " . $mimetype); 
//header("Content-Length: " . filesize($obj_path)); 
if(strtolower($_GET['tpp'])=='doc'){
	header("Content-Length: " . filesize($obj_path)); 
	header("Content-Disposition: attachment; filename=".time().".".$_GET['tpp']);
}
readfile($obj_path);
exit();
*/
?>