<?php
require_once "HTML/QuickForm.php";
require_once "include/adp_API.php";

if($user_data->access_level<=90){
	Header("Location: index.php");
}
$module_name = basename(dirname(__FILE__));
$SubmitFile = basename(__FILE__);
$SUBTMP=explode(".", $SubmitFile);
$SubmitFile=$SUBTMP[0];

$sql="SELECT * FROM session_data ORDER BY date DESC";
$result = $dbh->query($sql);
$count=0;
$msg=array();
$msg[0]='<br>線上即時受測人數:';
while ($data = $result->fetchRow()){
	if($count==0){
		$msg[]=' 
<table width="100%" border="2" align="center" bordercolor="#FFFFFF">
  <tr>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">流水號</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">帳號(cs_id)</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">科目</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">最近更<br>新時間</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">答題數</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">累積<br>時間</div></td>
  </tr>';
	}
	$tmp=json_decode(gzuncompress(base64_decode($data['session_data'])),true); 
	//debug_msg("第".__LINE__."行 tmp ", $tmp[1]); 
	//debug_msg("第".__LINE__."行 tmp ", $tmp[2]); 
	//計算累積時間
	$client_time=explode(_SPLIT_SYMBOL, $tmp[1][client_items_idle_time]);
	$item_count=0;
	$ttime=0;
	foreach($client_time as $timeval){
		if($timeval!=''){
			$ttime+=intval($timeval);   //累積時間(毫秒)
			$item_count++; //已做試題數
		}
	}
	$mytime=round($ttime/1000); //改成秒
	$sec=$mytime%60;
	$min=($mytime - $sec)/60;
	$my_cht_time=$min."分".$sec."秒";
	$_SESSION['IRT']=$tmp[0];
	$_SESSION['time']=$tmp[1];
	$_SESSION['code']=$tmp[2]; 
	$IRT=unserialize($_SESSION['IRT']); 
	//debug_msg("第".__LINE__."行 client_time ", $client_time); 
	$user_post=json_decode(gzuncompress(base64_decode($data['post_data'])),true);  
	//debug_msg("第".__LINE__."行 user_post ", $user_post); 
	$count++;
	$msg[]="<tr><td>".$count.'</td><td>'.$data['user_id'].'</td><td>'.$data['cs_id'].'</td><td>'.$data['date'].'</td><td>'.$item_count.'</td><td>'.$my_cht_time."</td></tr>";
}
if($count>0){
	$msg[0].=$count.'人';
	$msg[]="</table>";
}else{
	echo __LINE__."無資料";
}

foreach($msg as $val){
	echo $val;
}




