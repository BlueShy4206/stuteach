<?php
require_once "HTML/QuickForm.php";
require_once "include/adp_API.php";
require_once "read_excel.inc.php";

$module_name="ExamResult";

EXAM_RESULT_table_header();
//-- 顯示主畫面

echo '<br>
<table width="95%" border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="top" bordercolor="#FFCC33">';

if($user_data->access_level>='91'){
	listCLASSandCS();  
}elseif($user_data->access_level=='21'){
	listCLASSandCS4teacher(); 
}
echo '</td></tr></table>';
if($_REQUEST['opt']=='classErrorStatistics'){
	classErrorStatistics($_REQUEST['class_ep']);
}

function listCLASSandCS(){
	global $dbh, $module_name;
	
	$form = new HTML_QuickForm('frmadduser','post',$_SERVER['PHP_SELF']);
	//-- 尋找目前已建立之班級，並初始化"關聯選單"
	$select1[0]='縣市';
	$select2[0][0]='學校名稱';
	$select3[0][0][0]='年級';
	$select4[0][0][0][0]='班級';
	$select5[0][0][0][0][0]='試卷';
	
	$sql2 = "select * from exam_paper_access, exam_type WHERE exam_paper_access.type_id=exam_type.type_id ORDER BY exam_paper_access.school_id, exam_paper_access.grade, exam_paper_access.class, exam_paper_access.exam_paper_id, exam_paper_access.type_id";
	$result2 =$dbh->query($sql2);
	while ($row2=$result2->fetchRow()){
		$cc=org2citycode($row2['school_id']);
		$oi=$row2['school_id'];
		$gr=$row2['grade'];
		$cl=$row2['class'];
		$ep_id=$row2['exam_paper_id'];
		$paper_vol=$row2['paper_vol'];
		$cs_id=$row2['cs_id'];
		$sn=$row2['sn'];
		$my_csid=explode_cs_id($cs_id);
		$my_p=id2publisher(intval($my_csid[0])).' ';
		$my_s=id2subject(intval($my_csid[1]));
		$my_v="第".intval($my_csid[2])."冊";
		$my_u="第".intval($my_csid[3])."單元";
		$paper="-卷".$paper_vol;
		$type=$row2['type'];
		//print_r($row2);

		$select1[$cc]=id2city($cc);
		$select2[$cc][$oi]=id2org($oi);
		$select3[$cc][$oi][$gr]=$gr."年";
		$select4[$cc][$oi][$gr][$cl]=$cl."班";
		$select5[$cc][$oi][$gr][$cl][$ep_id]=$my_p.$my_s.$my_v.$my_u.$paper.'【'.$type.'】';
	}

	//-- 顯示選單
	echo "  班級學習狀態統計 <br>";
	$form->addElement('header', 'myheader', '請選擇班級&試卷：');  //標頭文字
	// Create the Element
	$sel =& $form->addElement('hierselect', 'class_ep', '');
	// And add the selection options
	$sel->setOptions(array($select1, $select2, $select3, $select4, $select5));
	
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file','classErrorStatistics');
	$form->addElement('hidden','opt','classErrorStatistics');
	$form->addElement("radio","show","功能1：","僅顯示本試卷學習狀態統計","epSINGLE"); 
	$form->addElement("radio","show",null,"顯示本單元所有學習狀態統計","epALL");
	$form->addElement("checkbox","output_csv","功能2：","提供CSV下載","1"); 
	$form->addRule('class_ep', '「班級&試卷」不可空白！', 'nonzero',null, 'client', null, null);
	$form->addRule('show', '「功能1」不可空白！', 'required', null, 'client', null, null);
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->display();
}


function listCLASSandCS4teacher(){
	global $dbh, $module_name, $user_data;

	$form = new HTML_QuickForm('frmadduser','post',$_SERVER['PHP_SELF']);
	//-- 尋找目前已建立之班級，並初始化"關聯選單"
	$select1[0]='單元名稱';
	$select2[0][0]='卷別';
	
	$sql2 = "select * from exam_paper_access, exam_type WHERE exam_paper_access.type_id=exam_type.type_id AND exam_paper_access.firm_id='{$user_data->firm_id}' AND exam_paper_access.grade='{$user_data->grade}' ORDER BY exam_paper_access.firm_id, exam_paper_access.grade, exam_paper_access.class, exam_paper_access.exam_paper_id, exam_paper_access.type_id";
	$result2 =$dbh->query($sql2);
	while ($row2=$result2->fetchRow()){
		$gr=$row2['grade'];
		$cl=$row2['class'];
		$ep_id=$row2['exam_paper_id'];
		$paper_vol=$row2['paper_vol'];
		$cs_id=$row2['cs_id'];
		$sn=$row2['sn'];
		$my_csid=explode_cs_id($cs_id);
		$my_p=id2publisher(intval($my_csid[0])).' ';
		$my_s=id2subject(intval($my_csid[1]));
		$my_v="第".intval($my_csid[2])."冊";
		$my_u="第".intval($my_csid[3])."單元";
		$cs_name=id2csname($cs_id);
		$paper="-卷".$paper_vol;
		$type=$row2['type'];
		$select1[$cs_id]=$my_p.$my_s.$my_v.$my_u.'【'.$cs_name.'】';
		//$select2[$cs_id][$ep_id]=$paper.'【'.$type.'】';
		$select2[$cs_id][$ep_id]=$paper;
	}
	//-- 顯示選單
	echo "  班級學習狀態統計 <br>";
	$form->addElement('header', 'myheader', '請選擇試卷：');  //標頭文字
	// Create the Element
	$sel =& $form->addElement('hierselect', 'class_ep', '');
	// And add the selection options
	$sel->setOptions(array($select1, $select2));
	
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file','classErrorStatistics');
	$form->addElement('hidden','opt','classErrorStatistics');
	$form->addElement("radio","show","功能1：","僅顯示本試卷學習狀態統計","epSINGLE"); 
	$form->addElement("radio","show",null,"顯示本單元所有學習狀態統計","epALL");
	$form->addElement("checkbox","output_csv","功能2：","提供CSV下載","1"); 
	$form->addRule('class_ep', '「班級&試卷」不可空白！', 'required',null, 'client', null, null);
	$form->addRule('show', '「功能1」不可空白！', 'required', null, 'client', null, null);
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->display();
}


function classErrorStatistics($class_ep){
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