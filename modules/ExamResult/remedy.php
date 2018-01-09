<?php
require_once "HTML/QuickForm.php";
require_once 'HTTP/Upload.php';
require_once 'Date.php';
require_once "include/adp_API.php";


$q_cs_id= $_GET["cs_id"];
$sql = "select remedy_instruction from concept_info WHERE cs_id = '$q_cs_id' ";
$result =$dbh->query($sql);
while ($row=$result->fetchRow())
	{
	 $sub_num=$row["remedy_instruction"];
	}
	
$sql = "select sub_score_name from concept_info_dim WHERE cs_id = '$q_cs_id' ";
$result =$dbh->query($sql);
while ($row=$result->fetchRow())
	{
	 $sub_name=$row["sub_score_name"];
	}
	
$ssub=explode("@XX@",$sub_num);
$ssub_name=explode("@XX@",$sub_name);
$cs_id=explode_cs_id($q_cs_id);
$subb=$_GET["sub"];

$import_data_path = "data/CS_db/".$cs_id[0]."/".$cs_id[1]."/".$cs_id[2]."/".$cs_id[3]."/".$ssub[$subb];

//debug_msg("第".__LINE__."行 import_data_path ", $import_data_path);
//debug_msg("第".__LINE__."行 InputArray ", $InputArray);
//debug_msg("第".__LINE__."行 ssub_name ", $ssub_name);
if(is_file($import_data_path) and file_exist($import_data_path)){
	$InputArray=read_excel_2j($import_data_path, __LINE__);
}else{
	echo '<br><br><br><p><font color="red">目前尚無補救教學資料</font></p>';
}

//sizeof($InputArray[0])
echo '<table width="75%" border="0" cellspacing="0" cellpadding="0">';
echo '<tr><td scope="col" align="center"><img src="'._THEME_IMG.'diag_t1.jpg" /></td></tr>';
echo '<tr><td align="left" background="'._THEME_IMG.'tit_bg04.gif" class="d_title" colspan="2">'.$ssub_name[$subb].'</td></tr></table>';
echo '<table width="78%" align="center" border="0" cellpadding="4" cellspacing="0" class="d_tableline">';
echo '<tr><td align="center" background="'._THEME_IMG.'tit_bg04.gif" class="d_s_title41">學習材料</td>
		  <td align="center" background="'._THEME_IMG.'tit_bg04.gif" class="d_s_title41">作者</td><tr>';
for($i=0;$i<sizeof($InputArray);$i++)
	{
	 echo "<tr>";
	 if (ereg('http://',$InputArray[$i][0]))
	 	echo '<td align="left" background="'._THEME_IMG.'tit_bg04.gif" class="d_s_title41"><a href='.$InputArray[$i][0].' target=_blank>'.$InputArray[$i][1].'</a></td>';
	 else
	 	echo '<td align="left" background="'._THEME_IMG.'tit_bg04.gif" class="d_s_title41">'.$InputArray[$i][1].'︰'.$InputArray[$i][0].'</td>';
	 if (isset($InputArray[$i][2]))
	 	echo '<td width="35%" align="left" background="'._THEME_IMG.'tit_bg04.gif" class="d_s_title41">'.$InputArray[$i][2].'</td>';
	 else
	 	echo '<td align="left" background="'._THEME_IMG.'tit_bg04.gif" class="d_s_title41">　</td>';
	 echo "</tr>";
	}

echo '<tr><td colspan=2 align="left" background="'._THEME_IMG.'tit_bg04.gif" class="d_s_title41"><br>本站內容均透過網路搜尋整理而來，供大家閱讀學習，所有作品之版權均歸作者或版權持有人所有，任何單位和個人不得將之用於商業用途，否則後果自負！ 如有不妥，請來信『fiona@ms3.ntcu.edu.tw』告知，我們將在24小時之內進行處理。</td></tr>';
echo '</table>';
echo "<br>";
echo '<a href=modules.php?op=modload&name=ExamResult&file=classReports&report=1&q_user_id='.$_GET["q_user_id"].'&q_cs_id='.$_GET["cs_id"].'>返回</a>';
echo "<br><br>";
?>
