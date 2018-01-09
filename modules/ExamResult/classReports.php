<?php
require_once "HTML/QuickForm.php";
require_once "include/adp_API.php";
require_once "read_excel.inc.php";
require_once "Diag_Report_IRT_STUTEACH.php"; //劃表格

$module_name= basename(dirname(__FILE__));
$col_num=1;
$bg = array('#FFFFCC', '#FFCCCC', '#CCFFCC', '#99FFCC', '#CCFF99');
//$bg = array('#f1f6d2', '#f1f6d2', '#f1f6d2', '#f1f6d2');


if($user_data->access_level>=20 && $_GET['q_user_id']!=$user_data->user_id){
	EXAM_RESULT_table_header();
	//-- 顯示主畫面

	if(!is_null($_REQUEST['organization'])){
		$_SESSION['org']=$_REQUEST['organization'];
	}
	echo '<br><table width="98%" border="1" cellpadding="0" cellspacing="0"><tr><td align="center" valign="top" bordercolor="#FFCC33">';

	chooseCLASS($_SESSION['org']); 
	echo '</td>';
	//if($_REQUEST['chooseCLASS']){
	//	listUSER($_REQUEST['organization']);
	//}
	if(!is_null($_SESSION['org'])){
		echo '<td align="center">';
		listAllExams_row($_SESSION['org']['4']);
		echo '</td>';
	}
	echo '</tr></table>';
}elseif($_GET['RedirectTo']==1){  //剛測驗完，直接轉址而來
	$pass=0;  //不給予查詢歷來測驗功能
	/*
   if($user_data->user_id!="test" || $user_data->user_id!="s001"){
      echo "接下來要印出診斷報告<br><br>但正在維護中，故本次不列出。請由使用上方的「成果查詢」<br><br>抱歉！！";
      die();
   }
   */
}else{  //學生使用
	$q_user_id=$user_data->user_id;
	$pass=1;
}

if($pass==1){  //具有查詢的權限，顯示歷來所有測驗單元
	listAllExams_col($q_user_id);
}

if($_GET['report']==1){  //顯示個人測驗結果
	if($_GET['RedirectTo']==1){  //剛測驗完，直接轉址而來，提供測驗結果列印
		$url_p='<a href="modules.php?op=modload&name='.$module_name.'&file=classReports&report=2&q_user_id='.$_GET['q_user_id'].'&q_cs_id='.$_GET['q_cs_id'].'" target="new">';
		$img_p='<img src="'._ADP_URL.'images/print.gif" width="24" height="24" border="0" align="absmiddle" alt="友善列印">';
		//echo $url_p.$img_p."&nbsp; 列印本測驗之學習診斷報告書</a><br>";
	}
	if($pass==1){
		echo '<hr>';
	}
	personExamResults($_GET['q_user_id'], $_GET['q_cs_id'], $_GET['report']);
}elseif($_GET['report']==2){
	PrintOutpersonExamResults($_GET['q_user_id'], $_GET['q_cs_id'], $_GET['report']);
}



function chooseCLASS($org){
	global $dbh,$module_name;

	$form = new HTML_QuickForm('frmTest','post','');
	//-- 尋找目前已建立之學校、單位，並初始化"關聯選單"
	$select1[0]='縣市';
	$select2[0][0]='學校名稱';
	$select3[0][0][0]='年級';
	$select4[0][0][0][0]='班級';
	$select5[0][0][0][0][0]='學生';

	$sql = "select distinct city_code, organization_id, grade, class from user_info ORDER BY city_code";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$cc=$row['city_code'];
		$oi=$row['organization_id'];
		$gr=$row['grade'];
		$cl=$row['class'];
		$select1[$cc]=id2city($cc);
		$select2[$cc][$oi]=id2org($oi);
		$select3[$cc][$oi][$gr]="$gr 年";
		$select4[$cc][$oi][$gr][$cl]="$cl 班";
		$sql2 = "select * from user_info WHERE organization_id='$oi' AND grade='$gr' AND  class='$cl' ORDER BY user_id";
		$result2 =$dbh->query($sql2);
		while ($row2=$result2->fetchRow()){
			$uid=$row2['user_id'];
			$un=$row2['uname'];
			$select5[$cc][$oi][$gr][$cl][$uid]=$uid.'-'.$un;
		}
	}

	//-- 顯示選單
	echo '<font class="title"><b>☆★☆  學生診斷報告 ☆★☆</b></font><br>';	
	$form->addElement('header','newheader','請選取班級及學生');
	$sel =& $form->addElement('hierselect', 'organization', '');  //關聯式選單
	$sel->setOptions(array($select1, $select2, $select3, $select4,$select5));
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file','classReports');
	$form->addElement('submit','chooseCLASS','選取完畢，送出');
	$form->addRule('organization', '「服務單位」不可空白！', 'nonzero', null, 'client', null, null);
	$form->setRequiredNote('前有<font color=red>*</font>的欄位不可空白');
	$selected = array("organization"=>$org); 
	$form->setDefaults($selected);
	$form->display();
}



function listUSER($org){
	global $dbh, $user_data, $module_name, $col_num, $bg;

	
	$_SESSION['org']=$org;
	$class_name=id2city($org[0])."&nbsp;".id2org($org[1])."&nbsp;".$org[2]."年&nbsp;".$org[3]."班";
	echo '<font class="title"><b>'.$class_name.'</b></font><br>';
	echo '<table width="95%" border="1" cellpadding="1" cellspacing="1" bordercolor="#0000FF" ';
	echo '<tr>';
	
	for($i=1;$i<=$col_num;$i++){
		echo '<td align="center"><font class="title"><b>帳號</b></font></td>';
		echo '<td align="center"><font class="title"><b>姓名</b></font></td>';
	}
	echo '</tr>';
	$class_data=new ClassData($org);   //產生班級基本資料物件
	//print_r($class_data);
	for($i=0;$i<sizeof($class_data->member);$i++){
		$bg_count=($i+1)%$col_num;
		if($bg_count==1){
			echo '<tr>';
		}
		$class_member = explode(_SPLIT_SYMBOL, $class_data->member[$i]);
		$url='<a href="modules.php?op=modload&name='.$module_name.'&file=classReports&q_user_id='.$class_member[1].'">';
		echo '<td align="left" bgcolor="'.$bg[$bg_count].'">'.$class_member[1].'</td>';  //帳號
		echo '<td align="left" bgcolor="'.$bg[$bg_count].'">'.$url.$class_member[2].'</a></td>';  //姓名
		if($bg_count==0){
			echo '</tr>';
		}
	}
	if($bg_count!=0){
			echo '</tr>';
	}
	echo "</table>";
}

function listAllExams_row($user_id){
	global $dbh, $module_name, $bg, $user_data;

	$col_num=5;
	if($user_id!=$user_data->user_id && $user_data->access_level<20){
		echo "<br><br>非本人不可查詢。<br><br>";
		//include_once "feet.php";
		die();
	}

	$q_uname=id2uname($user_id);
	echo '<font class="title"><b>'.$user_id.'【'.$q_uname.'】測驗紀錄</b></font><br>';
	//echo $_REQUEST['q_user_id'];
	echo '<table width="95%" border="1" cellpadding="1" cellspacing="1" bordercolor="#0000FF">';
	//echo '<tr><td align="center"><font class="title"><b>測驗名稱</b></font></td></tr>';
	//$sql="select * from exam_record WHERE user_id='$user_id' group by cs_id";
	$sql="select * from exam_record_irt WHERE user_id='$user_id' group by cs_id";
	$result =$dbh->query($sql);
	$i=0;
	while ($row=$result->fetchRow()){
		$bg_count=($i+1)%$col_num;
		$my_csid=explode_cs_id($row['cs_id']);
		$my_p=id2publisher(intval($my_csid[0])).' ';
		$my_s=id2subject(intval($my_csid[1]));
		$my_v="第".intval($my_csid[2])."冊";
		$my_u="第".intval($my_csid[3])."單元";
		$my_csname=id2csname($row['cs_id']);
		$cs_title=$my_p.$my_s.$my_v.$my_u.'【'.$my_csname.'】';
		$url='<a href="modules.php?op=modload&name='.$module_name.'&file=classReports&report=1&q_user_id='.$user_id.'&q_cs_id='.$row['cs_id'].'">';
		echo '<tr><td align="left" bgcolor="'.$bg[$bg_count].'">'.$url.$cs_title.'</a></td></tr>';  
		$i++;
	}


	if($bg_count!=0){
		echo '</tr>';
	}
	echo "</table>";
}



function listAllExams_col($user_id){  //學生用
	global $dbh, $module_name, $col_num, $bg;

	$q_uname=id2uname($user_id);
	//$sql="select count(*) from exam_record WHERE user_id='$user_id'";
	$sql="select count(*) from exam_record_irt WHERE user_id='$user_id'";
	$data =& $dbh->getOne($sql);
	if($data==0){
		echo "<center><br><br><br>目前沒有任何測驗記錄可供查詢！<br><br><br><br></center>";
		require_once("die_feet.php");
		die();
	}
	
	echo '<br>';
	echo '<table width="799" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td>';
	echo '<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" >
			<tr><td align="center">';
   //echo '<IMG src="images/table01.jpg">';
   echo '</td></tr>';
	echo '<tr><td bgcolor="#46AFE4"><b><font size="4">　'.$user_id.'【'.$q_uname.'】　測驗記錄</font></b></td></tr></table>';
	//echo '<table align="center" width="90%" border="1" cellpadding="1" cellspacing="1" bordercolor="#0000FF" ';
	echo '<tr><td>';
	echo '<table width="100%" border="1" cellspacing="2" cellpadding="1">
              <tr>
                <td bgcolor="#D3E3EF" >科目</td>
                <td bgcolor="#D3E3EF" align="center" scope="col">日期</td>
                <td bgcolor="#D3E3EF" align="center" scope="col">量尺分數</td>
                <!-- <td bgcolor="#D3E3EF" align="center" scope="col">成績</td> -->
                <!-- <td bgcolor="#D3E3EF" align="center" scope="col">百分等級</td> -->
                <!-- <td bgcolor="#D3E3EF" align="center" scope="col">列印</td> -->
                </tr>';

	//$sql="select * from exam_record WHERE user_id='$user_id' group by exam_title";
	//echo $user_id;
  //$sql="select * from exam_record_irt WHERE user_id='".$user_id."' group by exam_title";
  //$sql="select * from exam_record_irt WHERE user_id='.$user_id.'";
	//$result =$dbh->query($sql);
	$i=1;
	$sql=mysql_query("select * from exam_record_irt WHERE user_id='".$user_id."' ORDER BY exam_title");
	/*while($row=mysql_fetch_array($sql))
      {  
        $a = $row[0];
        //$b = $row[1];        
      }
      echo $a."<br>";
  mysql_free_result($sql);*/
    
	
	//while ($row=$result->fetchRow()){
	while($row=mysql_fetch_array($sql))
	{  
		$my_csid=explode_cs_id($row['cs_id']);
		$my_p=id2publisher(intval($my_csid[0])).' ';
		$my_s=id2subject(intval($my_csid[1]));
		$my_v="第".intval($my_csid[2])."冊";
		$my_u="第".intval($my_csid[3])."單元";
		$my_csname=id2csname($row['cs_id']);
		//$cs_title=$my_p.$my_s.$my_v.$my_u.'【'.$my_csname.'】-卷'.$row['paper_vol'];
		//$cs_title='【'.$my_csname.'】-卷'.$row['paper_vol'];
		$cs_title='【'.$my_csname.'】';
		$ExamDate=explode( ',', $row['date'] );
		/*echo $row['cs_id'];
    echo $my_csid[0];
    echo $my_v;
    echo $cs_title;*/
    	$my_epid=get_epid($my_csid[0],$my_csid[1],$my_csid[2],$my_csid[3],$row['paper_vol']);
    	//echo $my_epid."<br>";
		$cs_path=_ADP_CS_UPLOAD_PATH.$my_csid[0]."/".$my_csid[1]."/".$my_csid[2]."/".$my_csid[3]."/";
		$url='<a href="modules.php?op=modload&name='.$module_name.'&file=classReports&report=1&q_user_id='.$user_id.'&q_cs_id='.$row['cs_id'].'">';
//		$url='<a href="modules.php?op=modload&name='.$module_name.'&file=classReports&report=1&q_user_id='.$user_id.'&q_cs_id='.$my_epid.'">';
		$url_p='<a href="modules.php?op=modload&name='.$module_name.'&file=classReports&report=2&q_user_id='.$user_id.'&q_cs_id='.$row['cs_id'].'" target="new">';
		$img_p='<img src="'._ADP_URL.'images/print.gif" width="24" height="24" border="0" align="absmiddle" alt="下載列印">';
		//echo '<tr bgcolor="'.$bg[($i%(sizeof($bg)))].'">';
		//$sql2 = "select book_ref from concept_info where cs_id = '{$row['cs_id']}'";
		//$book_ref =& $dbh->getOne($sql2);
		//$url_v='<a href="modules.php?op=modload&name=ExamResult&file=download&pid='.intval($my_csid[0]).'&sid='.intval($my_csid[1]).'&vid='.intval($my_csid[2]).'&uid='.intval($my_csid[3]).'&dfn='.$book_ref.'" target="blank">';
		$showfig=explode(".", $book_ref);
		$showfig[0]=str2compiler($showfig[0]);
		$url_v='<a href="viewfig3.php?list='.$showfig[0].'&tpp='.$showfig[1].'&cs_id='.$row['cs_id'].'" target="blank">';
		$img_v='<img src="'._THEME_IMG.'print.gif" width="23" height="23" border="0" align="absmiddle" alt="觀看">';
		echo '<tr>';
		echo '<td bgcolor="#ecf8d2" align="left">'.$url.$cs_title.'</a></td>';
		echo '<td bgcolor="#ecf8d2" align="center">'.$ExamDate[0].'</td>';
		if($row[dim]==1)
		{
      echo '<td bgcolor="#ecf8d2" align="center">'.theta_turn_percentage($row[theta]).'</td>';
    }
    else
    {
      $theta_new = explode("@XX@",$row[theta]);
      $theta_name = explode("@XX@",$row[sub_score_name]);
      $theta_renew = ''; 
      for ($i=0;$i<$row[dim];$i++){
        if ($i!=($row[dim]-1)){
          $theta_renew .= $theta_new[$i].'('.$theta_name[$i].')、';
        }
        else{
          $theta_renew .= $theta_new[$i].'('.$theta_name[$i].')';
        }        
      }
      echo '<td bgcolor="#ecf8d2" align="center">'.$theta_renew.'</td>';
    }
    
		//echo '<td bgcolor="#ecf8d2" align="center">'.$row[score].'</td>';
		//echo '<td bgcolor="#ecf8d2" align="center">'.$row[degree].'</td>';
		//echo '<td bgcolor="#ecf8d2" align="center">'.$url_p.$img_p.'</td>';
		//echo '<td bgcolor="#ecf8d2" align="center">'.$url_v.'</td>';
      echo '</tr>';
		$i++;
	}
	echo '</table><br>';
	echo '</td></tr></table>';
	
	
}
function personExamResults($q_user_id, $q_cs_id, $report_for_pc){
	global $dbh, $module_name;

	//-- 輸出PC版  report_for_pc==1
	if(strpos($q_cs_id, _SPLIT_SYMBOL) == TRUE){
		$poly_total_virtual=1;  //點多
    /*  點多 IRT暫時用不到
		$a=new Print_Poly_Item_Data();
		$prt=$a->print_header();  //標頭
    $prt.=$a->print_forword();  //標頭
    $prt.=$a->print_student_basic_data();  //學生基本資料
		include_once("modules/ExamResult/radarex8.php");
		$prt.=$a->print_indicator_data();  //能力指標學習記錄
		$prt.=$a->print_radar_gif("plotradar2.php", $report_for_pc);  //學習狀態雷達圖
		$prt.=$a->print_remedy_data($report_for_pc);   //概念診斷報告
		$prt.=$a->print_remedy_data($q_user_id, $q_cs_id, $report_for_pc);  
		$prt.=$a->print_feet(); //標尾*/
		//die();
	}else{
		//echo $q_user_id;
		//echo $q_cs_id;
		$a=new Diag_Report_IRT($q_user_id, $q_cs_id, $report_for_pc);

		$is_poly=$a->Concept_Object->check_indicator();
		$prt=$a->print_header();  //標頭
		$prt.=$a->print_foreword();
		$prt.=$a->print_student_basic_data();  //學生基本資料
		if($_GET['RedirectTo']==1){
			//$prt.=$a->print_least_data();   //最近一次測驗結果
		}


		$prt.=$a->print_concept_history_data();   //本單元歷來學習記錄
		
		if($is_poly==1){
			;
		}else{
		//	$prt.=$a->print_graphic_data($q_cs_id, $report_for_pc);   //百分等級圖
		}
/*
		if($a->score[$a->test_times-1]!=100){  //考100則不必顯示
         //$print_data="";
         $prt.=$a->print_error_concept($report_for_pc);
      }
*/	
      $prt.=$a->print_radar_gif("plotradar2.php", $report_for_pc);
      $prt.=$a->print_remedy_data($q_user_id, $q_cs_id, $report_for_pc);   //概念診斷報告
      //echo $q_cs_id;
      //$prt.='</td></tr></table></td></tr></table></td></tr>';
	  //$prt.=$a->print_error_items($report_for_pc);
      $prt.=$a->print_feet(); //標尾
	}
	echo $prt;
}

function PrintOutpersonExamResults($q_user_id, $q_cs_id, $report_for_pc){
	global $dbh, $module_name;

	//-- 輸出報表   $report_for_pc==2
	$print_file=$q_user_id.$q_cs_id.'.htm';
	$print_file_loc=_ADP_TMP_UPLOAD_PATH.$print_file;
	$_SESSION['dfn']=$print_file;
	$prt[0]='<html><head>
		<meta http-equiv="Content-Type" content="text/html; charset=big5">
		<title>MFT電腦適性測驗診斷系統-診斷報告</title>
		<link href="'._THEME_CSS.'" rel="stylesheet" type="text/css" />
		</head>
		<body onload="self.print();">';
	if ($report_for_pc==2 && $fp = fopen($print_file_loc, "w+")) {  //可下載列印
		if(strpos($q_cs_id, _SPLIT_SYMBOL) == TRUE){
			$poly_total_virtual=1;

			$a=new Print_Poly_Item_Data($q_user_id, $q_cs_id, $report_for_pc);
			$prt[0].=$a->print_header();  //標頭
			$prt[1]=$a->print_student_basic_data();  //學生基本資料
			//include_once("modules/ExamResult/radarex8.php");
			$prt[2]=$a->print_indicator_data();  //能力指標學習記錄
			//require_once "plotradar.php";
			//$prt[3]=$a->print_radar_gif("plotradar.php", $report_for_pc);  //學習狀態雷達圖
			$prt[3].="<tr><td><P STYLE='page-break-before: always;'></td></tr>";
			$prt[4]=$a->print_remedy_data($q_user_id, $q_cs_id, $report_for_pc);   //概念診斷報告
			$prt[5]=$a->print_feet(); //標尾
			//die();
		}else{
			$a=new Diag_Report_IRT($q_user_id, $q_cs_id);
			//echo "<pre>";
			//print_r($a);
			$prt[0].=$a->print_header();  //標頭
      $prt[0].=$a->print_foreword();  //標頭
      $prt[1]=$a->print_student_basic_data();  //學生基本資料
			if($_GET['RedirectTo']==1){
				//$prt[2]=$a->print_least_data();   //最近一次測驗結果
			}
			//$prt[3]=$a->print_graphic_data($q_cs_id, $report_for_pc);   //百分等級圖
			$prt[4]=$a->print_concept_history_data();   //本單元歷來學習記錄
			$prt[4].="<tr><td><P STYLE='page-break-before: always;'></td></tr>";
/*
         if($a->score[$a->test_times-1]!=100){  //考100則不必顯示
			   $prt[4].=$a->print_error_concept($report_for_pc);
			}
*/
			$prt[4].=$a->print_radar_gif("plotradar2.php", $report_for_pc);
			$prt[4].=$a->print_remedy_data($q_user_id, $q_cs_id, $report_for_pc);   //概念診斷報告
/*
         if($a->score[$a->test_times-1]!=100){  //考100則不必顯示
			   $prt[4].=$a->print_error_items($report_for_pc);
			}
*/

         $prt[5]="";
			$prt[6]=$a->print_feet();   //標尾
		}
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
