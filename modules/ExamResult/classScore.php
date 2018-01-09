<?php
require_once "include/adp_API.php";
//require_once 'Date.php';
require_once "HTML/QuickForm.php";
$module_name = basename(dirname(__FILE__));

echo '<br>
<table width="95%" border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="top" bordercolor="#FFCC33">';
//echo __FILE__.__LINE__;
//die();
if($user_data->access_level>='81'){
	listCLASS();
	if(isset($_POST['class'])){
		echo "<hr>";
		listCLASSep($_POST['class']); 
		if(isset($_POST['myclass'])){
			echo "<hr>";
			listCLASSscore($_POST['myclass']); 
		}
	}
	//else{
	//	listCLASS();  
	//}
}elseif($user_data->access_level=='21'){
	listCLASSandCS4teacher(); 
}
//debug_msg(__LINE__."行  _POST", $_POST);
echo '</td></tr></table>';
/*
if($_REQUEST['opt']=='classErrorStatistics'){
	classErrorStatistics($_REQUEST['class_ep']);
}
*/

function listCLASS(){
	global $dbh, $module_name;
	
	$form = new HTML_QuickForm('frmadduser','post',$_SERVER['PHP_SELF']);
	//-- 尋找目前已建立之班級，並初始化"關聯選單"
	$select1[0]='縣市';
	$select2[0][0]='學校名稱';
	$select3[0][0][0]='年級';
	$select4[0][0][0][0]='班級';
	//$select5[0][0][0][0][0]='試卷';
	
	$sql2 = "select city_code, organization_id, grade, class from user_info WHERE city_code>0 AND organization_id >0 AND grade>0 and class>0 GROUP BY city_code, organization_id, grade, class";
	$result2 =$dbh->query($sql2);
	while ($row2=$result2->fetchRow()){
		
		$cc=org2citycode($row2['organization_id']);
		$oi=$row2['organization_id'];
		$gr=$row2['grade'];
		$cl=$row2['class'];

		$select1[$cc]=id2city($cc);
		$select2[$cc][$oi]=id2org($oi);
		$select3[$cc][$oi][$gr]=$gr."年";
		$select4[$cc][$oi][$gr][$cl]=$cl."班";
		//debug_msg(__LINE__."行  row2", $row2);
	}
	
	//-- 顯示選單
	echo "  班級學生成績查詢 <br>";
	$form->addElement('header', 'myheader', '請選擇班級：');  //標頭文字
	// Create the Element
	$sel =& $form->addElement('hierselect', 'class', '');
	// And add the selection options
	$sel->setOptions(array($select1, $select2, $select3, $select4));
	
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file','classScore');
	$form->addElement('hidden','opt','listedClass');
	$form->addRule('class', '「班級」不可空白！', 'nonzero',null, 'client', null, null);
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->display();
}

function listCLASSep($stuClass){
	global $dbh, $module_name;

	$myclass = implode(_SPLIT_SYMBOL, $stuClass);
	//echo var_dump($myclass);
	//die();
	$form2 = new HTML_QuickForm('frmadduser2','post',$_SERVER['PHP_SELF']);
	//-- 尋找目前已建立之班級，並初始化"關聯選單"
	$se1[0]='版本';
	$se2[0][0]='領域';
	$se3[0][0][0]='冊別';
	$se4[0][0][0][0]='單元名稱';
	$se5[0][0][0][0][0]='卷別';
	
	$sql2 = "select exam_paper_id, paper_vol from exam_paper_access WHERE grade='".$stuClass[2]."' ORDER BY exam_paper_id";
	$result2 =$dbh->query($sql2);
	while ($row2=$result2->fetchRow()){
		
		$EP_info=explode_ep_id($row2['exam_paper_id']);
		$cs_id=EPid2CSid($row2['exam_paper_id']);
		$paper_vol=$row2['paper_vol'];
		
		$pid=intval($EP_info[0]);
		$sid=intval($EP_info[1]);
		$vid=intval($EP_info[2]);
		$uid=intval($EP_info[3]);
		$subject=id2subject($sid);
		//$paper_vol=intval($EP_info[4]);
		$se1[$pid]=id2publisher($pid);
		$se2[$pid][$sid]=$subject;
		$se3[$pid][$sid][$vid]=vol2grade($subject,$vid).'〈第'.$vid.'冊〉';
		$se4[$pid][$sid][$vid][$uid]='第'.$uid.'單元-'.id2csname($cs_id);
		$se5[$pid][$sid][$vid][$uid][$paper_vol]='卷'.$paper_vol;

		//debug_msg(__LINE__."行  row2", $row2);
	}
	//debug_msg(__LINE__."行  se1", $se1);
	//debug_msg(__LINE__."行  se2", $se2);
	//debug_msg(__LINE__."行  se3", $se3);
	//debug_msg(__LINE__."行  se4", $se4);
	//debug_msg(__LINE__."行  se5", $se5);
	//die();
	//-- 顯示選單
	$classFullName=id2CityOrg($stuClass[1]).$stuClass[2].'年'.$stuClass[3].'班';
	echo "  $classFullName 學生成績查詢 <br>";
	//$form2->addElement('header', 'myheader', '請選擇試卷：');  //標頭文字
	// Create the Element
	$sel =& $form2->addElement('hierselect', 'ep', '');
	// And add the selection options
	$sel->setOptions(array($se1, $se2, $se3, $se4, $se5));
	
	$form2->addElement('hidden','op','modload');
	$form2->addElement('hidden','name',$module_name);
	$form2->addElement('hidden','file','classScore');
	$form2->addElement('hidden','opt','listedClass');
	$form2->addElement('hidden','myclass',$myclass);
	$form2->addRule('ep', '「試卷」不可空白！', 'nonzero',null, 'client', null, null);
	$form2->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form2->display();
}


function listCLASSscore($myclass){
	global $dbh, $module_name, $user_data;
	
	//做相容的動作
	if($user_data->access_level=='21'){
		//echo "<pre>";
		//print_r($class_ep);
		//print_r($user_data);
		$class_ep[4]=$class_ep[1];
		$class_ep[0]=$user_data->city_code;
		$class_ep[1]=$user_data->organization_id;
		$class_ep[2]=$user_data->grade;
		$class_ep[3]=$user_data->class_name;
		//die();
	}
	$ep_id=$class_ep[4];    //試卷編號
	//echo "<pre>";
	//print_r($class_ep);
	$class_data=new ClassData($class_ep);   //產生班級基本資料物件
	//debug_msg(__LINE__."行  class_data", $class_data);
	$cs_id=EPid2CSid($ep_id);
	//print_r($class_data->member);
	$remedy_data=new RemedyData($cs_id);
	$sch_name=id2city($class_ep[0]).'-'.id2org($class_ep[1]);
	//print_r($remedy_data);

	$csv_file=$ep_id.'_'.$class_ep[1].'_'.$class_ep[2].sprintf("%02d",$class_ep[3]).'_'.$_REQUEST['show'].'.csv';
	$csv_file_loc=_ADP_TMP_UPLOAD_PATH.$csv_file;
	$base='<a href="modules.php?op=modload&name='.$module_name.'&file=download&dfn='.$csv_file;
	if($_REQUEST['output_csv']==1){
		$csv_url='【'.$base.'" target="blank">下載csv檔</a>】';
	}
	echo "<p align=\"center\"><u><b><font size=\"4\">".$sch_name.$class_ep[2]."年".$class_ep[3]."班-學習診斷報告書".$csv_url."</font></b></u></p>\n";
	if($_REQUEST['show']=='epSINGLE'){
		$ep_data=explode_ep_id($ep_id);
		$this_paper_vol=intval($ep_data[4]);
		$test_times=$start_paper_vol=$this_paper_vol;   //僅取單一試卷施測結果
	}elseif($_REQUEST['show']=='epALL'){
		$start_paper_vol=1;
		$sql = "select * from exam_record WHERE cs_id='$cs_id' GROUP BY paper_vol";
		$result =$dbh->query($sql);
		$test_times=0;
		while ($row=$result->fetchRow()){  //計算本單元測驗次數
			$test_times++;
		}
	}
	//echo "$this_paper_vol <br>$test_times";

	//-- 初始化作答反應陣列
	for($i=0;$i<sizeof($remedy_data->structure);$i++){ 
		for($j=$start_paper_vol;$j<=$test_times;$j++){
			$class_pass_rate[$j][$i]=0;
			$class_no_pass_rate[$j][$i]=0;
		}
	}
	//echo "<pre>";
	for($k=0;$k<sizeof($class_data->member);$k++){
		$class_member = explode(_SPLIT_SYMBOL, $class_data->member[$k]);   
		//print_r($class_member);
		if($_REQUEST['show']=='epSINGLE'){
			$sql="select * from exam_record WHERE user_id='$class_member[1]' and exam_title='$ep_id'";;
		}elseif($_REQUEST['show']=='epALL'){
			$sql="select * from exam_record WHERE user_id='$class_member[1]' and cs_id='$cs_id' order by cs_id";
		}
		//echo $sql."<br>";
		$result =$dbh->query($sql);
		while ($row=$result->fetchRow()){
			$user_remedy = explode(_SPLIT_SYMBOL, $row['remedy_rate']);
			//print_r($user_remedy);
			//print_r($remedy_data->sturcture);
			for($i=0;$i<sizeof($user_remedy)-1;$i++){
				if($_REQUEST['show']=='epALL'){
					$j=$row['paper_vol'];   //為了畫表格需要而調整
				}elseif($_REQUEST['show']=='epSINGLE'){
					$j=$this_paper_vol;
				}
				if($user_remedy[$i]>=$remedy_data->threshold[$i]){
					$class_pass_rate[$j][$i]++;   //通過者
				}else{
					$class_no_pass_rate[$j][$i]++;  //未通過者
				}
			}
		}
	}
	//print_r($class_pass_rate);
	//print_r($class_no_pass_rate);

	//-- 被要求輸出csv，並且可開啟該csv檔案	
	if ( ($_REQUEST['output_csv']=='1') && ($fp = fopen($csv_file_loc, "w+")) ) { 
		//$astr=iconv("UTF-8", "big5", $add_str);   //轉成utf-8
		$str=$sch_name.$class_ep[2]."年".$class_ep[3]."班-概念列表,";
		for($j=$start_paper_vol;$j<=$test_times;$j++){
			$str.="卷".$j."通過,卷".$j."未通過,";
		}
		$str.="\r\n";
		$astr=iconv("UTF-8", "big5", $str);   //轉成utf-8
		fwrite($fp, $astr);  //寫入標頭檔
		for($i=0;$i<sizeof($remedy_data->structure);$i++){
			$str=$remedy_data->structure[$i].",";
			for($j=$start_paper_vol;$j<=$test_times;$j++){
				$str.=sprintf("%02d",$class_pass_rate[$j][$i]).','.sprintf("%02d",$class_no_pass_rate[$j][$i]).',';
			}
			$str.="\r\n";
			$astr=iconv("UTF-8", "big5", $str);   //轉成utf-8
			fwrite($fp, $astr);  //寫入標頭檔
		}
		fclose($fp);
	} 

	echo "<table border=\"1\" width=\"95%\" align=\"center\">";
	echo "<tr>\n";
	echo "<td bgcolor=\"#CCCCCC\" width=\"40%\" rowspan=\"2\">概念列表</td>\n";
//		echo "<td width=\"10%\" rowspan=\"2\">測驗結果</td>\n";
	echo "<td bgcolor=\"#CCCCCC\" colspan=\"".$test_times."\"width=\"60%\" colspan=\"10\">診斷結果</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	for($j=$start_paper_vol;$j<=$test_times;$j++){
		echo "<td bgcolor=\"#CCCCCC\" width=\"".(60/$test_times)."%\">卷".$j."<br>通過-未通過</td>\n";
	}
	echo "</tr>\n";

	for($i=0;$i<sizeof($remedy_data->structure);$i++){
		echo "<tr>\n";
		echo "<td width=\"40%\">".$remedy_data->structure[$i]."</td>\n";
//			echo "<td width=\"10%\">".$status[$i]."</td>\n";
		for($j=$start_paper_vol;$j<=$test_times;$j++){
			echo "<td width=\"".(60/$test_times)."%\">".sprintf("%02d",$class_pass_rate[$j][$i]).'-<font color="#FF0000"><strong>'.sprintf("%02d",$class_no_pass_rate[$j][$i])."</strong></font></td>\n";
		}
		echo "</tr>\n";
	}
	echo "</table>\n";
}

?>