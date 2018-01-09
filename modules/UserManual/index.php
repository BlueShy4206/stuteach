<?php

if(!Auth::staticCheckAuth($options)){  //檢查登入狀況
	Header("Location: index.php");
	die();
}

$dlf = _ADP_URL."data/Manual/";
$module_name = basename(dirname(__FILE__));

echo '<h3>點右鍵，另存新檔<center>';

$manual[1]=array("manual"=>"學生操作手冊");
$manual[9]=array("manual"=>"學生操作手冊");
$manual[21]=array("manual"=>"學生操作手冊");
$manual[71]=array("manual"=>"學生操作手冊");
$manual[72]=array("manual"=>"學生操作手冊");
$manual[91]=array("manual"=>"學生操作手冊");
echo "<br>";
$i=1;
foreach($manual[$user_data->access_level] as $key=>$val){
	echo '<h3><center>';
	//$_SESSION[df][$user_data->access_level][$key]=$dlf.$val;
	$show=$i.".".$val;
	$download=$dlf.$key.".pdf";
	echo '【<a href="'.$download.'" target="_blank">'.$show."</a>】";
	echo '<br></center></h3>';
	$i++;
}
