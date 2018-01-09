<?php

// $Id: adp_core_function.php
//require_once( "adp_core_auth.php" );

//-- 編號->出版商
function id2publisher($id) {   //編號->出版商
	global $dbh;
	$sql = "select publisher from publisher where publisher_id = '$id'";
	$data =& $dbh->getOne($sql);
	return $data;
}

//-- 編號->科目名稱
function id2subject($id) {  
	global $dbh;
	$sql = "select name from subject where subject_id = '$id'";
	$data =& $dbh->getOne($sql);
	return $data;
}

//-- 編號->城市名稱
function id2city($id) {  
	global $dbh;
	$sql = "select city_name from city where city_code = '$id'";
	$data =& $dbh->getOne($sql);
	return $data;
}

//-- 編號->機關(就讀學校)名稱
function id2org($org_id) {  
	global $dbh;
	$sql = "select name from organization where organization_id = '$org_id'";
	$data =& $dbh->getOne($sql);
	return $data;
}

//-- 編號->補習班(施測地點)名稱
function id2firm($firm_id) {  
	global $dbh;
	$sql = "select name from firm where firm_id = '$firm_id'";
	$data =& $dbh->getOne($sql);
	return $data;
}

//-- 編號->都市名+學校名稱
function id2CityOrg($org_id) {  
	global $dbh;
	$sql = "select name, city_code from organization where organization_id = '$org_id'";
	$data = $dbh->query($sql);
	$row = $data->fetchRow();
	$city=id2city($row['city_code']);
	return $city.'-'.$row['name'];
}

//-- 編號->都市名+補習班名稱
function id2CityFirm($firm_id) {  
	global $dbh;
	$sql = "select name, city_code from firm where firm_id = '$firm_id'";
	$data = $dbh->query($sql);
	$row = $data->fetchRow();
	$city=id2city($row['city_code']);
	return $city.'-'.$row['name'];
}

//-- 帳號->存取等級
function id2level($id) { 
	global $dbh;
	$sql = "select access_title from user_access WHERE access_level='$id'";
	$data =& $dbh->getOne($sql);
	return $data;
}

//-- 帳號->姓名
function id2uname($id) { 
	global $dbh;
	$sql = "select uname from user_info WHERE user_id='$id'";
	$data =& $dbh->getOne($sql);
	return $data;
}

//-- 帳號->單元概念名稱
function id2csname($id) { 
	global $dbh;
	$sql = "select concept from concept_info WHERE cs_id='$id'";
	$data =& $dbh->getOne($sql);
	return $data;
}

//-- 帳號->施測類型
function id2ExamType($id) { 
	global $dbh;
	$sql = "select type from exam_type WHERE type_id='$id'";
	$data =& $dbh->getOne($sql);
	return $data;
}

function id2ItemEduPara($id) {
	global $dbh;
	$sql = "select edu_parameter from exam_edu_parameter WHERE sn='$id'";
	$data =& $dbh->getOne($sql);
	return $data;
}

//-- 組合cs_id
function get_csid($pid, $sid, $vid, $uid) {  
	$cs_id=sprintf("%03d%02d%02d%02d",$pid, $sid, $vid, $uid);
	return $cs_id;
}

//-- 組合ep_id
function get_epid($pid, $sid, $vid, $uid, $paper_vol) {  
	$cs_id=sprintf("%03d%02d%02d%02d%02d",$pid, $sid, $vid, $uid, $paper_vol);
	return $cs_id;
}

function explode_cs_id($cs_id) {  
	$data[0]=intval(substr($cs_id, 0, 3));   //123碼為publisher_id(版本)
	$data[1]=intval(substr($cs_id, 3, 2));   //45碼為subject_id(科目)
	$data[2]=intval(substr($cs_id, 5, 2));   //67碼為vol(冊別)
	$data[3]=intval(substr($cs_id, 7, 2));   //89碼為unit(單元)
	return $data;
}

function explode_ep_id($ep_id) {  
	$data[0]=substr($ep_id, 0, 3);   //1,2,3碼為publisher_id(版本)
	$data[1]=substr($ep_id, 3, 2);   //4,5碼為subject_id(科目)
	$data[2]=substr($ep_id, 5, 2);   //6,7碼為vol(冊別)
	$data[3]=substr($ep_id, 7, 2);   //8,9碼為unit(單元)
	$data[4]=substr($ep_id, 9, 2);   //10,11碼為paper_vol(卷別)
	return $data;
}

function CSid2FullName($cs_id) {  
	global $dbh;
	if(strpos($cs_id, _SPLIT_SYMBOL) == TRUE){  //cs_id中有 _SPLIT_SYMBOL
		$special_cs_id=$cs_id;
		list($q_cs_ida, $q_cs_unit) = split(_SPLIT_SYMBOL, $special_cs_id);
		$cs_id=$q_cs_ida.sprintf("%02d", $q_cs_unit);
		$my_csname="全";
	}
	$sql = "select publisher_id,subject_id,vol,unit,grade,concept,indicator_relation,indicator_item,indicator_threshold,indicator_item_nums, indicator_item_relation from concept_info WHERE cs_id='$cs_id' limit 0,1";
	$result = $dbh->query($sql);
	//echo $sql;
	$data = $result->fetchRow();
	if (!(in_array("", $data))) {
		$is_poly=1;
	}
	$cs_info=explode_cs_id($cs_id);
	$my_p=id2publisher($data[publisher_id]).' ';
	$my_s=id2subject($data[subject_id]);
	if($is_poly==1){
		if(intval($data[vol])%2==1){
			$tmp_vol="Ａ";
		}else{
			$tmp_vol="Ｂ";
		}
		$my_v=num2chinese(ceil(intval($data[vol])/2))."年級".$tmp_vol;
		$my_u="";
	}else{
		$my_v="第".intval($cs_info[2])."冊";
		$my_u="第".intval($cs_info[3])."單元";
	}
	if(!isset($my_csname)){
		$my_csname=$data[concept];
	}
	$cs_title=$my_p.$my_s.$my_v.$my_u.'【'.$my_csname.'】';
	
	return $cs_title;
}

function EPid2FullName($ep_id) {
	$cs_id=EPid2CSid($ep_id);
	$cs_title=CSid2FullName($cs_id);
	
	$ep_info=explode_ep_id($ep_id);
	/*
	$my_p=id2publisher(intval($ep_info[0])).' ';
	$my_s=id2subject(intval($ep_info[1]));
	$my_v="第".intval($ep_info[2])."冊";
	$my_u="第".intval($ep_info[3])."單元";
	$my_csname=id2csname(EPid2CSid($ep_id));
	*/
	$cs_title=$cs_title.'－卷'.$ep_info[4];
	return $cs_title;
}

function getChtExamTitle($cs_id, $paper_vol){

	$my_csid=explode_cs_id($cs_id);
	$my_p=id2publisher(intval($my_csid[0])).' ';
	$my_s=id2subject(intval($my_csid[1]));
	$my_v="第".intval($my_csid[2])."冊";
	$my_u="第".intval($my_csid[3])."單元";
	$my_csname=id2csname($cs_id);
	if($my_csid[0]=="006"){  //多點記分，能力指標
      $vid=intval($my_csid[2]);
   	$tmp_vol="Ｂ";
		if($vid%2==1){
			$tmp_vol="Ａ";
		}
		$my_v=num2chinese(ceil($vid/2))."年級".$tmp_vol;
   }
   if(strpos($cs_id, _SPLIT_SYMBOL) == TRUE){
      $cs_title=$my_p.$my_s.$my_v.'【全】';
   }else{
      $cs_title=$my_p.$my_s.$my_v.$my_u.'【'.$my_csname.'】';
   }
   if($paper_vol!=0){
      $cs_title.='-卷'.$paper_vol;
   }

   return $cs_title;
}



/*
function CSid2IndicatorFullName($cs_id) {  
	$cs_info=explode_cs_id($cs_id);
	$my_p=id2publisher(intval($cs_info[0])).' ';
	$my_s=id2subject(intval($cs_info[1]));
	$my_v="第".intval($cs_info[2])."冊";
	$my_u="第".intval($cs_info[3])."單元";
	$my_csname=id2csname($cs_id);
	$my_csname="全";
	$cs_title=$my_p.$my_s.$my_v.$my_u.'【'.$my_csname.'】';
	return $cs_title;
}

function EPid2IndicatorFullName($ep_id) {  
	$ep_info=explode_ep_id($ep_id);
	$my_p=id2publisher(intval($ep_info[0])).' ';
	$my_s=id2subject(intval($ep_info[1]));
	$my_v="第".intval($ep_info[2])."冊";
	$my_u="第".intval($ep_info[3])."單元";
	
	$my_v=num2chinese(ceil(intval($my_csid[2])/2))."年級";
	$my_u="";
	if(strpos($row[cs_id], _SPLIT_SYMBOL) == TRUE){  //cs_id中有 _SPLIT_SYMBOL
		$my_csname="全";
	}
	$cs_title=$my_p.$my_s.$my_v.$my_u.'【'.$my_csname.'】-卷'.$ep_info[4];
	$cs_title=$my_p.$my_s.$my_v.$my_u.'【'.$my_csname.'】-卷'.$row['paper_vol'];
	return $cs_title;
}
*/

function EPid2CSid($ep_id) {  
	$data=substr($ep_id, 0, 9);   //前9碼為cs_id
	return $data;
}

//-- 學校機關->城市編號
function org2citycode($id) {  
	$data=intval(substr($id, 0, 2));   //前2碼為city_code
	return $data;
}

//-- 阿拉伯數字->國字數字
function num2chinese($id) {  
	$ary=array('O','一','二','三','四','五','六','七','八','九','十','十一','十二','十三','十四','十五','十六','十七','十八','十九','二十','二十一','二十二','二十三','二十四','二十五',);
	$data=$ary[$id];   
	return $data;
}

//-- 冊別->年級學期
function vol2grade($subject,$id) {  
	$sch=ceil(intval($id)/2);
	$sem=intval($id)%2;
	if($sem==1) $sem="上學期";
	else  $sem="下學期";
	if($subject=='數學' || $subject=='國語')
		$sch=num2chinese($sch).'年級';  
	
	return $sch.$sem;
}

//-- 逐字轉換utf8字串為big5
function  utf8_2_big5($utf8_str)  {
	$i=0;
	$len  =  strlen($utf8_str);
	$big5_str="";
	for  ($i=0;$i<$len;$i++)  {
		$sbit  =  ord(substr($utf8_str,$i,1));
		if  ($sbit  <  128)  {
			$big5_str.=substr($utf8_str,$i,1);
		}  else  if($sbit  >  191  &&  $sbit  <  224)  {
			$new_word=iconv("UTF-8","Big5",substr($utf8_str,$i,2));
			$big5_str.=($new_word=="")?"■":$new_word;
			$i++;
		}  else  if($sbit  >  223  &&  $sbit  <  240)  {
			$new_word=iconv("UTF-8","Big5",substr($utf8_str,$i,3));
			$big5_str.=($new_word=="")?"■":$new_word;
			$i+=2;
		}  else  if($sbit  >  239  &&  $sbit  <  248)  {
			$new_word=iconv("UTF-8","Big5",substr($utf8_str,$i,4));
			$big5_str.=($new_word=="")?"■":$new_word;
			$i+=3;
		}
	}
	return  $big5_str;
}

//-- 強制檔案下載
function force_download ($data, $name, $mimetype='', $filesize=false) { 
    // File size not set? 
    if ($filesize == false OR !is_numeric($filesize)) { 
        $filesize = strlen($data); 
    } 
  
    // Mimetype not set? 
    if (empty($mimetype)) { 
        $mimetype = 'application/octet-stream'; 
    } 
  
    // Make sure there's not anything else left 
    ob_clean_all(); 
  
    // Start sending headers 
    header("Pragma: public"); // required 
    header("Expires: 0"); 
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
    header("Cache-Control: private",false); // required for certain browsers 
    header("Content-Transfer-Encoding: binary"); 
    header("Content-Type: " . $mimetype); 
    header("Content-Length: " . $filesize); 
    header("Content-Disposition: attachment; filename=\"" . $name . "\";" ); 
  
    // Send data 
    echo $data; 
    die(); 
} 
  
function ob_clean_all () { 
    $ob_active = ob_get_length () !== false; 
    while($ob_active) { 
        ob_end_clean(); 
        $ob_active = ob_get_length () !== false; 
    } 
  
    return true; 
} 

function exam_clean_all () {
	unset($_SESSION['cs_id']);
	unset($_SESSION['Student_Data']);
	unset($_SESSION['Concept_Data']);
	unset($_SESSION['paper_vol']);
	unset($_SESSION['start_time']);
	unset($_SESSION['date']);
	unset($_SESSION["selected_item"]);
	unset($_SESSION["rec_user_answer"]);
	unset($_SESSION["selected_item_rec"]);
	unset($_SESSION['exam_type']);
	unset($_SESSION['done_nums']);
	unset($_SESSION["Test_Model"]);
	unset($_SESSION['auth_ep']);
	unset($_SESSION['chk_guess']);
	unset($_SESSION['correct_item_num']);
	unset($_SESSION['guess_item']);
}

//-- 錯誤回報
function error_report2db($user_id) {
	global $dbh;

	$msg_time = date("Y-m-d H:i");
	$cs_info=explode_cs_id($_SESSION['cs_id']);
	$my_p=id2publisher($cs_info[0]);
	$my_s=id2subject($cs_info[1]);
	$my_v=$cs_info[2];
	$my_u=$cs_info[3];
	$item_title=$my_p.$my_s.'-第'.$my_v.'冊第'.$my_u.'單元-卷'.$_SESSION['paper_vol'].'-第'.$_SESSION["selected_item"].'題';
	$subject="問題回報-".$user_id."-".$item_title;
	$msg_text=$item_title.'有問題，請檢查，謝謝！';

	$sql = "select user_id from user_status WHERE access_level>80";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$query = 'INSERT INTO priv_msgs (msg_type_id, subject, from_userid, to_userid, msg_time, msg_text, read_msg) VALUES (?,?,?,?,?,?,?)';
		$data = array(1, $subject, $user_id, $row[user_id], $msg_time, $msg_text, 0);
		
		$result1 =$dbh->query($query, $data);
		if (PEAR::isError($result1)) {
			echo "錯誤訊息：".$result1->getMessage()."<br>";
			echo "錯誤碼：".$result1->getCode()."<br>";
			echo "除錯訊息：".$result1->getDebugInfo()."<br>";
			die();
		}else{
			$status="問題回報成功！";
		}
	}

	return $status;
}

function chk_file_exist($exec_file, $exec_line){
	$wait_sec=3;
	if(!file_exists($exec_file) || !is_file($exec_file)){
		echo "檔案不存在，請重新執行！<br><br>";
		debug_msg("exec_file", $exec_file);
		echo "<br>line: $exec_line <br>";
		//sleep($wait_sec);
		echo '<a href="modules.php?op=main">按此返回</a>';
		FEETER(); 
		die();
	}
}

function get_image_mime($filename)
{
    global $img_define;
    
    if(preg_match('/\.([^\.]+)$/', $filename, $matches)) {
        if($mime = $img_define["image_mime"][strtolower($matches[1])]) {
            return $mime;
        }
    }
    return;
}

function str2compiler($str){
	$str=str_replace('1','~',$str);
	//$str=str_replace('2','@',$str);  //好像有問題
	$str=str_replace('3','-',$str);
	$str=str_replace('5','/',$str);
	$str=str_replace('6','_',$str);
	$str=str_replace('7','.',$str);
	$data=strrev($str);

	return $data;
}

function compiler2str($str){
	$str=str_replace('~','1',$str);
	//$str=str_replace('@','2',$str);
	$str=str_replace('-','3',$str);
	$str=str_replace('/','5',$str);
	$str=str_replace('_','6',$str);
	$str=str_replace('.','7',$str);
	$data=strrev($str);

	return $data;
}

function pass2compiler($pass){
	$newpass=str_split($pass, 3);
	$fin="";
	for($i=0;$i<count($newpass);$i++){
		$newpa[$i]=strrev($newpass[$i]);
		$fin.=$newpa[$i];
	}
	return $fin;
}

function creat_csv($filename, $header, $content){
	$csv_file_loc=_ADP_TMP_UPLOAD_PATH.$filename;
	if ($fp = fopen($csv_file_loc, "w+")) { //表示第一筆作答反應，先印出標頭
		$header.="\r\n";
		$astr=utf8_2_big5($header);
		fwrite($fp, $astr); 
		for($i=0;$i<count($content);$i++){
			$content[$i].="\r\n";
			$astr=utf8_2_big5($content[$i]);
			fwrite($fp, $astr); 
		}
		fclose($fp);  //關閉檔案
	}

}

function debug_msg($title, $showarry){
	echo "<pre>";
	echo "<br>$title<br>";
	print_r($showarry);
}

function modify_pic_pix($ini, $set_val){
		$PImgProp=GetImageSize($ini);
		$pic_pix[0]=intval($PImgProp[0]);  //圖片寬度
		$mini=1;
		while($pic_pix[0]>$set_val){
			$mini=$mini-0.01;
			$pic_pix[0]=ceil(intval($PImgProp[0])*$mini);
		}
		$pic_pix[1]=ceil(intval($PImgProp[1])*$mini);  //輸出圖片高度

		return $pic_pix;
}

function getCSlicense($user_id){
   global $dbh;
   
   if($user_id=="admin"){
      $sql = "select * from publisher";
      $result =$dbh->query($sql);
      while ($row=$result->fetchRow()){
         $publish[]=$row[publisher_id];
      }

      $sql = "select * from subject";
      $result =$dbh->query($sql);
      while ($row=$result->fetchRow()){
         $subject[]=$row[subject_id];
      }

      for($i=0;$i<count($publish);$i++){
         for($j=0;$j<count($subject);$j++){
            $OpenedCS[]=sprintf("%03d%02d", $publish[$i], $subject[$j]);
         }
      }

   }else{
      $sql = "select subject from subject_acnt WHERE acnt='".$user_id."' ORDER BY subject";
   }
   $result = $dbh->query($sql);
   while ($data = $result->fetchRow()) {
      $OpenedCS[]=$data[subject];
   }
   
   return $OpenedCS;
}

function EPid2PaperVol($ep_id) {
	$data=intval(substr($ep_id, 9, 2));   //10,11碼為卷別
	return $data;
}

function getXls2CsvName($name){
   list($main_name, $sub) = split(".", $name);
   $new_name=$main_name.".csv";

   return $new_name;
}

function Xls2Csv($FileWithLoc){
   //讀excel轉csv
   list($csv_file, $csv_file2) = explode('.', $FileWithLoc);
   $csv_file_name=$csv_file.".csv";
   $fp = fopen($csv_file, 'w');
   $s=read_excel($FileWithLoc, __LINE__);
   $ii=count($s);
   for($i=0;$i<$ii;$i++){
      $Scsv[$i]=implode(',', $s[$i]);
      fputcsv($fp, split(',', $Scsv[$i]));
   }
   fclose($fp);
}

?>
