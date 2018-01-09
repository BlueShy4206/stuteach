<?php
require_once "HTML/QuickForm.php";
require_once 'HTTP/Upload.php';
require_once "include/adp_API.php";
//require_once 'Object/Print_Student_Data.inc.php';

$module_name="AdaptiveTest";


//OpenTable();
//echo "<td><center><font class=\"title\"><b>適性測驗結果</b></font></center></td>";
//CloseTable();

//-- 顯示主畫面

/*
$a=new Print_Student_Data($user_data->user_id, $_REQUEST['cs_id']);
echo $a->print_student_basic_data();  //學生基本資料
echo $a->print_least_data();   //最近一次測驗結果
echo $a->print_graphic_data();   //百分等級圖
echo $a->print_concept_history_data();   //本單元歷來學習記錄
echo $a->print_remedy_data();   //概念診斷報告
//$a->print_least_user_ans();   //學生最近一次作答反應
*/
$url_p='<a href="modules.php?op=modload&name='.$module_name.'&file=download">';
$img_p='<img src="'._ADP_URL.'images/print.gif" width="24" height="24" border="0" align="absmiddle" alt="友善列印">';
echo $url_p.$img_p."&nbsp; 列印本單元之學習診斷報告書</a><br>";

$report_for_pc=1;
$a=new Print_Student_Data($user_data->user_id, $_REQUEST['cs_id'], $_REQUEST['report_for_pc']);
$prt[1]=$a->print_student_basic_data();  //學生基本資料
echo $prt[1].'<hr>';
$prt[2]=$a->print_least_data();   //最近一次測驗結果
echo $prt[2].'<hr>';
$prt[3]=$a->print_graphic_data();   //百分等級圖
echo $prt[3].'<hr>';
$prt[4]=$a->print_concept_history_data();   //本單元歷來學習記錄
echo $prt[4].'<hr>';
$prt[5]=$a->print_remedy_data($report_for_pc);   //概念診斷報告
echo $prt[5];

//-- 輸出報表
$report_for_pc=0;
$print_file=$user_data->user_id.'_'.$_REQUEST['cs_id'].'.htm';
$print_file_loc=_ADP_TMP_UPLOAD_PATH.$print_file;
$_SESSION['dfn']=$print_file;
$prt[0]='<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>report</title>
</head>
<body style="font-family:標楷體" bgcolor="white" text="black" link="blue" vlink="purple" alink="red" onload="self.print();">';
flush();
if ($fp = fopen($print_file_loc, "w+")) {
	
	$prt[4].="<P STYLE='page-break-before: always;'>";

	for($i=0;$i<sizeof($prt);$i++){
		$astr=utf8_2_big5($prt[$i]);
		fwrite($fp, $astr);
	}
	$html_feet='</body></html>';
	fwrite($fp, $html_feet);
	fclose($fp);  //關閉檔案

	//$RedirectTo="Location: modules.php?op=modload&name=".$module_name."&file=download";
	//Header($RedirectTo);
}




?>

