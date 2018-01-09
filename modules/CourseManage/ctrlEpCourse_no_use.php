<?php
require_once "HTML/QuickForm.php";
require_once "include/adp_API.php";
require_once "CourseData.php";

if($user_data->access_level<=70){
	Header("Location: index.php");
}

$module_name = basename(dirname(__FILE__));
$SubmitFile=basename(__FILE__);
$SubmitFile=str_replace(".php", "", $SubmitFile);
//-- 顯示主畫面
CM_table_header($module_name);
//debug_msg(__LINE__."行  _REQUEST", $_REQUEST);
//-- 顯示主畫面
echo '<br>
	<table width="95%" border="1" cellpadding="0" cellspacing="0">
		<tr>
		<td align="center" valign="top" bordercolor="#FFCC33">';
		listEP();
		//debug_msg("第".__LINE__."行 _REQUEST ", $_REQUEST);
echo '</td></tr></table>';
echo '<br>
	<table width="95%" border="1" cellpadding="0" cellspacing="0">
		<tr>
		<td align="center" valign="top" bordercolor="#FFCC33">';

if($_REQUEST['opt']=='EP2course'){
	EP2course($_REQUEST['ep']);

}elseif($_REQUEST['opt']=='TeacherAdd2class' && $_REQUEST['Teachers']!=''){
	EPadd2course($_REQUEST['Teachers'], $_REQUEST[ep_id], $_REQUEST[ExamType]);
}elseif($_REQUEST['opt']=='delTeacherClass' && $_REQUEST['AuthTeachers']!=''){
	course_delEP($_REQUEST['AuthTeachers'], $_REQUEST[ep_id]);
}elseif(isset($_REQUEST['opt'])){
	//預設值（刪除或新增的功能選錯）
	$RedirectTo="Location: modules.php?op=modload&name=".$module_name."&file=".$SubmitFile;
	Header($RedirectTo);
}

echo '</td></tr></table>';


function listEP(){
	global $dbh, $module_name, $SubmitFile;

	$form = new HTML_QuickForm('frmadduser','post',$_SERVER['PHP_SELF']);
	//-- 初始化"關聯選單"
	$select1[0]='版本';
	$select2[0][0]='領域';
	$select3[0][0][0]='冊別';
	$select4[0][0][0][0]='單元名稱';
	$select5[0][0][0][0][0]='卷別';

	//$sql = "select a.exam_paper_id from concept_item a, concept_info_plus b WHERE  a.cs_id=b.cs_id GROUP BY exam_paper_id ";
	$sql = "select exam_paper_id from concept_item GROUP BY exam_paper_id ";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$EPid=$row[exam_paper_id];
		$cs_id=EPid2CSid($EPid);
		$EP_info=explode_ep_id($EPid);
		$paper_vol=intval($EP_info[4]);
		$pid=intval($EP_info[0]);
		$sid=intval($EP_info[1]);
		$vid=intval($EP_info[2]);
		$uid=intval($EP_info[3]);
		$subject=id2subject($sid);
		$paper_vol=intval($EP_info[4]);
		$select1[$pid]=id2publisher($pid);
		$select2[$pid][$sid]=$subject;
		$select3[$pid][$sid][$vid]='第'.$vid.'冊';
		$select4[$pid][$sid][$vid][$uid]='第'.$uid.'單元-'.id2csname($cs_id);
		$select5[$pid][$sid][$vid][$uid][$paper_vol]='卷'.$paper_vol;
	}

	//-- 顯示選單
	//echo "☆★☆ 課程列表 ☆★☆<br>";
	$form->addElement('header', 'myheader', '<center> 課程與試卷存取控制 </canter>'); 
	// Create the Element
	$sel =& $form->addElement('hierselect', 'ep', '請選擇試卷：');
	// And add the selection options
	$sel->setOptions(array($select1, $select2, $select3, $select4, $select5));
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file',$SubmitFile);
	$form->addElement('hidden','opt','EP2course');
	$form->addRule('course', '「課程」不可空白！', 'nonzero',null, 'client', null, null);
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->display();
}


function EP2course($ep){
	global $dbh, $module_name, $SubmitFile;

	//debug_msg(__LINE__."行  ep", $ep);
	$EPid=get_epid($ep[0],$ep[1],$ep[2],$ep[3],$ep[4]);
	$cs_id=EPid2CSid($EPid);
	$EpFullName=EPid2FullName($EPid);

	//$listCOURSE='<a href="modules.php?op=modload&name='.$module_name.'&file='.$SubmitFile.'">選擇其他課程</a>';
	echo '<br>現在的試卷是：<font color=red><b>'.$EpFullName.'</b></font><hr>';

	$sql="SELECT * FROM course ORDER BY year,seme,name ";
	//debug_msg(__LINE__."行  sql", $sql);
	$result = $dbh->query($sql);
	$IDcount=0;
	while ($data = $result->fetchRow()) {
		$AllCourseID[$IDcount]=$data['course_id'];
		$AllCourseName[$data['course_id']]=$data[year]."學年度第".$data[seme]."學期-".$data[name];
		$IDcount++;
	}
	$sql="SELECT sn, course_id FROM exam_course_access WHERE exam_paper_id ='".$EPid."' ORDER BY year,seme,course_id";
	//debug_msg(__LINE__."行  sql", $sql);
	$result = $dbh->query($sql);
	$IN=0;
	while ($data = $result->fetchRow()) {
		$InCourseID[$IN]=$data['course_id'];
		$InCourseSN[$data['course_id']]=$data['sn'];
		$IN++;
	}
	if($IN==0){
		$OutCourseID=$AllCourseID;
	}elseif($IDcount==0){
		die('目前沒有任何課程！請先新增課程。');
	}else{
		$OutCourseID=array_diff($AllCourseID, $InCourseID);
	}

//debug_msg(__LINE__."行  AllCourseID", $AllCourseID);
//debug_msg(__LINE__."行  InCourseID", $InCourseID);
//debug_msg(__LINE__."行  OutCourseID", $OutCourseID);

	echo "<center>";
	echo '<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<FORM ACTION="modules.php?op=modload&name='.$module_name.'&file='.$SubmitFile.'" METHOD="POST">
<tr>
<td bgcolor="#FFFFCC" align="right" width="20%">
<font size="3"><b>尚未指派之課程</b></font>
</TD>';
	echo '<TD bgcolor="#FFFFCC" VALIGN="TOP" align="left" width="80%">
<font size="3" face="arial">
<SELECT NAME="Teachers[]" SIZE="10" MULTIPLE>';
	if(count($OutCourseID)>0){
		$ii=1;
		foreach($OutCourseID as $CourseID){
			$current='【'.$ii.'】'.$AllCourseName[$CourseID];  
			echo '<OPTION VALUE="'.$CourseID.'">'.$current.'</OPTION>'."\n";
			$ii++;
		}
	}
	echo '</SELECT><br></TD></TR>';

	echo '<tr>
<td bgcolor="#FFFFCC" align="right" width="20%">
<font size="3"><b>測驗類型</b></font>
</TD>';
	echo '<TD bgcolor="#FFFFCC" VALIGN="TOP" align="left" width="80%">
<font size="3" face="arial">
<select name="ExamType">';
	$sql="SELECT * FROM exam_type ORDER BY type_id";
	$result = $dbh->query($sql);
	while ($data = $result->fetchRow()) {
		echo '<option value="'.$data['type_id'].'">'.$data['type'].'</option>';
	}
	echo '</SELECT><br></TD></TR>';

	echo '<TR>
<td bgcolor="#FFFFCC" align="right" width="20%">
<font size="3"><b>功能</b></font>
</TD>';

	echo '<TD bgcolor="#FFFFCC" VALIGN="TOP" align="left" width="80%">';
	echo '</select><font size="3" face="arial">'."\n";
	echo '<INPUT TYPE="HIDDEN" NAME="ep_id" VALUE="'.$EPid.'">'."\n";
	//echo '<INPUT TYPE="HIDDEN" NAME="course[1]" VALUE="'.$course[1].'">'."\n";
	//echo '<INPUT TYPE="HIDDEN" NAME="course[2]" VALUE="'.$course[2].'">'."\n";
	echo '<INPUT TYPE="HIDDEN" NAME="opt2" VALUE="teacher">'."\n";
	echo '<select name="opt">
         <option value="TeacherAdd2class">新增 ↓</option>
         <option value="delTeacherClass">刪除 ↑</option></select>';
	echo '　　<INPUT TYPE="SUBMIT" NAME="SUBMIT" VALUE="送出"><br>';
	echo '</TD></TR>';


	echo '<TR>
<TD bgcolor="#FFFFCC" align="right" width="20%">
<font size="3"><b>已指派之課程</b></font>
</TD>';


	echo '<TD bgcolor="#FFFFCC" VALIGN="TOP" align="left">';
	echo '<SELECT NAME="AuthTeachers[]" SIZE="10" MULTIPLE>';
   
	if(count($InCourseID)>0){
		$ii=1;
		foreach($InCourseID as $CourseID){
			$name='【'.$ii.'】'.$AllCourseName[$CourseID]; 
	   		echo '<OPTION VALUE="'.$InCourseSN[$CourseID].'">'.$name.'</OPTION>'."\n";
	   		$ii++;
   		}
   	}
	echo '</SELECT><br>';
	echo "</TD><TR></TABLE></FORM>";
	echo "</center>";

}

function EPadd2course($CourseIDs, $ep_id, $ExamType){
	global $dbh, $module_name, $SubmitFile;

	//$CourseID=$course[2];
	while(list($null, $CourseID) = each($CourseIDs)) {
		$CO=new CourseData($CourseID);
		$cs_id=EPid2CSid($ep_id);
		$ep=explode_ep_id($ep_id);
		$PaperVol=$ep[4];
		$query = 'INSERT INTO exam_course_access (cs_id, paper_vol, exam_paper_id, course_id, year, seme, type_id) VALUES (?,?,?,?,?,?,?)';
		$data = array($cs_id, $PaperVol, $ep_id, $CourseID, $CO->Year, $CO->Seme, $ExamType);

		$result =$dbh->query($query, $data);
		if (PEAR::isError($result)) {
			echo "錯誤訊息：".$result->getMessage()."<br>";
			echo "錯誤碼：".$result->getCode()."<br>";
			echo "除錯訊息：".$result->getDebugInfo()."<br>";
		}else{
			$RedirectTo="Location: modules.php?op=modload&name=".$module_name."&file=".$SubmitFile."&opt=EP2course&ep[0]=".$ep[0]."&ep[1]=".$ep[1]."&ep[2]=".$ep[2]."&ep[3]=".$ep[3]."&ep[4]=".$ep[4];
			Header($RedirectTo);
		}
	}
}

function course_delEP($SNs, $ep_id){
	global $dbh, $module_name, $SubmitFile;

	//debug_msg("第".__LINE__."行 SNs ", $SNs);
	//debug_msg("第".__LINE__."行 ep_id ", $ep_id);
	//die();

	$ep=explode_ep_id($ep_id);
	while(list($null, $SN) = each($SNs)) {
		$sql="DELETE FROM exam_course_access WHERE sn='".$SN."'";
		$result = $dbh->query($sql);
		if (PEAR::isError($result)) {
			echo "錯誤訊息：".$result->getMessage()."<br>";
			echo "錯誤碼：".$result->getCode()."<br>";
			echo "除錯訊息：".$result->getDebugInfo()."<br>";
			die();
		}
	}
	$RedirectTo="Location: modules.php?op=modload&name=".$module_name."&file=".$SubmitFile."&opt=EP2course&ep[0]=".$ep[0]."&ep[1]=".$ep[1]."&ep[2]=".$ep[2]."&ep[3]=".$ep[3]."&ep[4]=".$ep[4];
	Header($RedirectTo);
}



?>

