<?php
require_once "HTML/QuickForm.php";
require_once "include/adp_API.php";
require_once "read_excel.inc.php";

$module_name = basename(dirname(__FILE__));
$bg = array('#FFFFCC', '#FFCCCC', '#CCFFCC', '#99FFCC', '#CCFF99');

//!isset($_REQUEST['report'])
if($user_data->access_level>=30 && $_GET['q_user_id']!=$user_data->user_id){  //班主任使用，只能查詢該補習班的學生
	$sql = "select firm_id from user_info where user_id = '{$_GET['q_user_id']}'";
	$q_firm_id =& $dbh->getOne($sql);
	if($q_firm_id==$user_data->firm_id){
		$q_user_id=$_GET['q_user_id'];
		$pass=1;
	}else{
		echo "<br>權限不符合！<br>";
	}
}elseif($_GET['RedirectTo']==1){  //剛測驗完，直接轉址而來
	$pass=0;  //不給予查詢歷來測驗功能
}else{  //學生使用
	$q_user_id=$user_data->user_id;
	$pass=1;
}

if($pass==1){  //具有查詢的權限，顯示歷來所有測驗單元
	listAllExams_col($q_user_id);
}

if($_GET['report']==1){  //顯示個人測驗結果
	if($_GET['RedirectTo']==1){  //剛測驗完，直接轉址而來，提供測驗結果列印
		$url_p='<a href="modules.php?op=modload&name='.$module_name.'&file=firmReports&report=2&q_user_id='.$_GET['q_user_id'].'&q_cs_id='.$_GET['q_cs_id'].'" target="new">';
		$img_p='<img src="'._ADP_URL.'images/print.gif" width="24" height="24" border="0" align="absmiddle" alt="友善列印">';
		//echo $url_p.$img_p."&nbsp; 列印本單元之學習診斷報告書</a><br>";
	}
	if($pass==1){
		echo '<hr>';
	}
	personExamResults($_GET['q_user_id'], $_GET['q_cs_id'], $_GET['report']);
}elseif($_GET['report']==2){
	PrintOutpersonExamResults($_GET['q_user_id'], $_GET['q_cs_id'], $_GET['report']);
}

function listAllExams_col($user_id){
	global $dbh, $module_name, $bg;
	
	//$col_num=2;
	$q_uname=id2uname($user_id);
	echo '<table width="98%" border="0" cellspacing="4" cellpadding="2" align="center">
          <tr>
            <td scope="col"><table width="100%" border="0" cellpadding="2" cellspacing="0" class="title">
              <tr>
                <td width="1%" scope="col"><img src="'._THEME_IMG.'li.gif" width="11" height="28" /></td>
                <td width="99%" scope="col">歷來測驗紀錄</td>
              </tr>
            </table>';
	$sql="select count(*) from exam_record WHERE user_id='$user_id'";
	$data =& $dbh->getOne($sql);
	if($data==0){
		echo "<br>目前沒有任何測驗記錄可供查詢！";
		echo '</td>
          </tr>
        </table>';
		FEETER();
		die();
	}
	echo '</td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td scope="col"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="record">
                  <tr>
                    <td width="8%" align="center" scope="col">編號</td>
                    <td width="50%" align="center" scope="col">測驗名稱（依版本排列）</td>
                    <td width="11%" align="center" scope="col">成績</td>
                    <td width="15%" align="center" scope="col">百分等級</td>
                    <td width="8%" align="center" scope="col">列印</td>
					<td width="8%" align="center" scope="col">教材對照</td>
                  </tr>
                </table></td>
              </tr><tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="2">';

	$sql="select * from exam_record WHERE user_id='$user_id' group by exam_title";
	$result =$dbh->query($sql);
	$i=1;
	while ($row=$result->fetchRow()){
		$my_csid=explode_cs_id($row['cs_id']);
		$my_p=id2publisher(intval($my_csid[0])).' ';
		$my_s=id2subject(intval($my_csid[1]));
		$my_v="第".intval($my_csid[2])."冊";
		$my_u="第".intval($my_csid[3])."單元";
		$my_csname=id2csname($row['cs_id']);
		$cs_title=$my_p.$my_s.$my_v.$my_u.'【'.$my_csname.'】-卷'.$row['paper_vol'];
		$cs_path=_ADP_CS_UPLOAD_PATH.$my_csid[0]."/".$my_csid[1]."/".$my_csid[2]."/".$my_csid[3]."/";
		$url='<a href="modules.php?op=modload&name='.$module_name.'&file=firmReports&report=1&q_user_id='.$user_id.'&q_cs_id='.$row['cs_id'].'">';
		$url_p='<a href="modules.php?op=modload&name='.$module_name.'&file=firmReports&report=2&q_user_id='.$user_id.'&q_cs_id='.$row['cs_id'].'" target="new">';
		$img_p='<img src="'._ADP_URL.'images/print.gif" width="24" height="24" border="0" align="absmiddle" alt="下載列印">';
		//echo '<tr bgcolor="'.$bg[($i%(sizeof($bg)))].'">';
		$sql2 = "select book_ref from concept_info where cs_id = '{$row['cs_id']}'";
		$book_ref =& $dbh->getOne($sql2);
		//$url_v='<a href="modules.php?op=modload&name=ExamResult&file=download&pid='.intval($my_csid[0]).'&sid='.intval($my_csid[1]).'&vid='.intval($my_csid[2]).'&uid='.intval($my_csid[3]).'&dfn='.$book_ref.'" target="blank">';
		$showfig=explode(".", $book_ref);
		$showfig[0]=str2compiler($showfig[0]);
		$url_v='<a href="viewfig3.php?list='.$showfig[0].'&tpp='.$showfig[1].'&cs_id='.$row['cs_id'].'" target="blank">';
		$img_v='<img src="'._THEME_IMG.'print.gif" width="23" height="23" border="0" align="absmiddle" alt="觀看">';
		echo '<tr>';
		echo '<td width="8%" align="center" class="line03" scope="col">'.$i.'</td>';
		echo '<td width="50%" align="left" class="line03" scope="col">'.$url.$cs_title.'</a></td>'; 	
		echo '<td width="11%" align="center" class="line03" scope="col">'.$row['score'].'</td>';
		echo '<td width="15%" align="center" class="line03" scope="col">'.$row['degree'].'</td>';
		echo '<td width="8%" align="center" class="line03" scope="col">'.$url_p.$img_p.'</td>';
		echo '<td width="8%" align="center" class="line03" scope="col">';
		$exec_file=$cs_path.$book_ref;
		$_SESSION['cs_path']=$cs_path;
		if(file_exists($exec_file) && is_file($exec_file)){
			echo $url_v.$img_v;

		}else{
			echo "　";
		}
		echo '</td></tr>';
		$i++;
	}
	echo '</table></td>
              </tr>
            </table></td>
          </tr>
        </table>';
}


function personExamResults($q_user_id, $q_cs_id, $report_for_pc){
	global $dbh, $module_name;
	
	//-- 輸出PC版  report_for_pc==1
	$a=new Print_Student_Data($q_user_id, $q_cs_id, $report_for_pc);
	$prt[0]=$a->print_header();  //標頭
	echo $prt[0];
	$prt[1]=$a->print_student_basic_data();  //學生基本資料
	echo $prt[1];
	$prt[2]=$a->print_least_data();   //最近一次測驗結果
	echo $prt[2];
	$prt[3]=$a->print_graphic_data($q_cs_id, $report_for_pc);   //百分等級圖
	echo $prt[3];
	$prt[4]=$a->print_concept_history_data();   //本單元歷來學習記錄
	echo $prt[4];
	//$prt[5]=$a->print_sturcture_gif();   //知識結構圖
	//echo $prt[5];
	$prt[6]=$a->print_remedy_data($report_for_pc);   //概念診斷報告
	echo $prt[6];   
	$prt[7]=$a->print_feet();   //標尾
	echo $prt[7]; 
}

function PrintOutpersonExamResults($q_user_id, $q_cs_id, $report_for_pc){
	global $dbh, $module_name;
	
	//-- 輸出報表   $report_for_pc==2
	$print_file=$q_user_id.$q_cs_id.'.htm';
	$print_file_loc=_ADP_TMP_UPLOAD_PATH.$print_file;
	$_SESSION['dfn']=$print_file;
	$prt[0]='<html><head>
		<meta http-equiv="Content-Type" content="text/html; charset=big5">
		<title>KSAT電腦適性測驗診斷系統-診斷報告</title>
		<link href="'._THEME_CSS.'" rel="stylesheet" type="text/css" />
		</head>
		<body onload="self.print();">';
	if ($report_for_pc==2 && $fp = fopen($print_file_loc, "w+")) {  //可下載列印
		$a=new Print_Student_Data($q_user_id, $q_cs_id);
		//echo "<pre>";
		//print_r($a);
		$prt[0].=$a->print_header();  //標頭
		$prt[1]=$a->print_student_basic_data();  //學生基本資料
		$prt[2]=$a->print_least_data();   //最近一次測驗結果
		$prt[3]=$a->print_graphic_data($q_cs_id, $report_for_pc);   //百分等級圖
		$prt[4]=$a->print_concept_history_data();   //本單元歷來學習記錄
		$prt[4].="<tr><td><P STYLE='page-break-before: always;'></td></tr>";
		$prt[5]=$a->print_remedy_data($report_for_pc);   //概念診斷報告
		$prt[6]=$a->print_feet();   //標尾
		//echo "<pre>";
		//print_r($prt);
		for($i=0;$i<=sizeof($prt);$i++){
			$astr=utf8_2_big5($prt[$i]);
			fwrite($fp, $astr);
		}
		$html_feet='</body></html>';
		fwrite($fp, $html_feet);
		fclose($fp);  //關閉檔案
	
		$RedirectTo="Location: modules.php?op=modload&name=".$module_name."&file=download2";
		Header($RedirectTo);
	}
}


?>