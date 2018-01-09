<?php
require_once "include/adp_API.php";
$module_name = basename(dirname(__FILE__));

if(!Auth::staticCheckAuth($options)){  //檢查登入狀況
	Header("Location: index.php");
	die();
}

TableTitle('760','最新公告');
//echo '<table width="95%" border="0" cellspacing="4" cellpadding="0"><tr><td>';

OpenTable2();
$ann[]="目前無最新公告！";
/*
$ann[]="1.目前補救教學的重點已盡量對應階梯現有的教材，有些重點還是無法對應，請大家多多包涵！";
$ann[]="2.重點對應課本、習作頁碼的部份只對應當冊的教材，若是向下延伸的重點目前未做對應。";
*/
echo "<table>";
for($i=0;$i<count($ann);$i++){
	echo '<tr><td align="left">';
	echo $ann[$i]."<br>";
	echo '</td></tr>';
}
echo "</table>";
CloseTable2();


?>