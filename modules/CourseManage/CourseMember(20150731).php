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
		listCOURSE();
		//debug_msg("第".__LINE__."行 _REQUEST ", $_REQUEST);
echo '</td></tr></table>';
echo '<br>
	<table width="95%" border="1" cellpadding="0" cellspacing="0">
		<tr>
		<td align="center" valign="top" bordercolor="#FFCC33">';

if($_REQUEST['opt']=='EP2course'){
	EP2course($_REQUEST['course'], $_REQUEST['organization']);

}elseif($_REQUEST['opt']=='TeacherAdd2class' && $_REQUEST['Teachers']!=''){
	EPadd2course($_REQUEST['Teachers'], $_REQUEST[course], $_REQUEST['organization']);
}elseif($_REQUEST['opt']=='delTeacherClass' && $_REQUEST['AuthTeachers']!=''){
	course_delEP($_REQUEST['AuthTeachers'], $_REQUEST[course], $_REQUEST['organization']);
}elseif(isset($_REQUEST['opt'])){
	//預設值（刪除或新增的功能選錯）
	$RedirectTo="Location: modules.php?op=modload&name=".$module_name."&file=".$SubmitFile;
	Header($RedirectTo);
}

echo '</td></tr></table>';


function listCOURSE(){
	global $dbh, $module_name, $SubmitFile;

	$form = new HTML_QuickForm('frmadduser','post',$_SERVER['PHP_SELF']);
	//-- 尋找目前已建立之課程，並初始化"關聯選單"
	$se1[0]='年度';
	//$se2[0][0]='學期';
	//$se3[0][0][0]='課程';
	$se2[0][0]='場次';

	$sql = "select * from course GROUP BY year,seme,name ";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$ye=$row['year'];
		$se=$row['seme'];
		$name=$row['name'];
		$cid=$row['course_id'];

		$se1[$ye]=$ye."年度";
		//$se2[$ye][$se]="第".$se."學期";
		//$se3[$ye][$se][$cid]=$name;
		$se2[$ye][$cid]=$name;
	}

	$select1[0]='所有單位(學校)';
	$select2[0][0]='所有科系';
	$select3[0][0][0]='所有';
	$select4[0][0][0][0]='所有';

	$sql = "select distinct city_code, organization_id, grade, class from user_info ORDER BY city_code";
	//debug_msg("第".__LINE__."行 sql ", $sql );
	//$sql = "select city_code, organization_id, grade, class from user_info GROUP BY city_code, organization_id, grade, class";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$cc=$row['city_code'];
		$oi=$row['organization_id'];
		$gr=$row['grade'];
		$cl=$row['class'];
		$select1[$cc]=id2city($cc);
		$select2[$cc][$oi]=id2org($oi);
		$select3[$cc][$oi][$gr]="$gr ";
		$select4[$cc][$oi][$gr][$cl]="$cl ";
	}


	//-- 顯示選單
	//echo "☆★☆ 課程列表 ☆★☆<br>";
	$form->addElement('header', 'myheader', '<center> 場次與考生對應 </canter>'); 
	// Create the Element
	$sel =& $form->addElement('hierselect', 'course', '請選擇場次：');
	// And add the selection options
	//$sel->setOptions(array($se1, $se2, $se3));
	$sel->setOptions(array($se1, $se2));
	$sel =& $form->addElement('hierselect', 'organization', '單位：');  //關聯式選單
	$sel->setOptions(array($select1, $select2, $select3, $select4));
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file',$SubmitFile);
	$form->addElement('hidden','opt','EP2course');
	$form->addRule('course', '「場次」不可空白！', 'nonzero',null, 'client', null, null);
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->display();
}


function EP2course($course, $organ){
	global $dbh, $module_name, $SubmitFile;
	$CO=new CourseData($course[1]);

	//debug_msg("第".__LINE__."行 course ", $course );
	//debug_msg("第".__LINE__."行 organ ", $organ );

	//$listCOURSE='<a href="modules.php?op=modload&name='.$module_name.'&file='.$SubmitFile.'">選擇其他課程</a>';
	$sql="SELECT a.user_id, a.uname FROM user_info a, user_status b WHERE a.user_id=b.user_id AND b.access_level<30";
	echo '<br>現在的場次是：<font color=red><b>'.$CO->FullName.'</b></font><hr>';
	if($organ[1]!=0){
		//$class_name=id2city($organ[0])."&nbsp;".id2org($organ[1])."&nbsp;".$organ[2]."年&nbsp;".$organ[3]."班";
		$class_name=id2city($organ[0])."&nbsp;".id2org($organ[1]);
		echo '<br>現在的單位是：<font color=red><b>'.$class_name.'</b></font>';
		$sql.=" AND a.organization_id ='$organ[1]' AND grade='$organ[2]' AND class='$organ[3]'";
	}
	$sql.=" order by user_id";
	//debug_msg(__LINE__."行  sql", $sql);
	$result = $dbh->query($sql);

	$IDcount=0;
	while ($data = $result->fetchRow()) {
		$AllUserID[$IDcount]=$data['user_id'];
		$AllUserName[$data['user_id']]=$data['uname'];
		$IDcount++;
	}
	$sql="SELECT user_id FROM user_course WHERE course_id ='".$CO->CourseID."' ORDER BY user_id";
	//debug_msg(__LINE__."行  sql", $sql);
	$result = $dbh->query($sql);
	$start=$IDcount;
	$IN=0;
	while ($data = $result->fetchRow()) {
		$InUserID[$IN]=$data['user_id'];
		$IN++;
	}
	if($IN==0){
		$OutUserID=$AllUserID;
	}elseif($IDcount==0){
		die('目前沒有符合資格的使用者！請先新增使用者。');
	}else{
		$OutUserID=array_diff($AllUserID, $InUserID);
	}

//debug_msg(__LINE__."行  AllUserID", $AllUserID);
//debug_msg(__LINE__."行  InUserID", $InUserID);
//debug_msg(__LINE__."行  OutUserID", $OutUserID);

	echo '<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<FORM ACTION="modules.php?op=modload&name='.$module_name.'&file='.$SubmitFile.'" METHOD="POST">
<tr>
<td bgcolor="#FFFFCC" align="center" width="45%">
<font size="3"><b>尚未指派之考生</b></font>
</TD>
<td bgcolor="#FFFFCC" align="center" width="10%">
<font size="3"><b>功能</b></font>
</TD>
<TD bgcolor="#FFFFCC" align="center" width="45%">
<font size="3"><b>已指派之考生</b></font>
</TD>
</TR>

<TR>
<TD bgcolor="#FFFFCC" VALIGN="TOP" align="center" width="45%">
<font size="3" face="arial">
<SELECT NAME="Teachers[]" SIZE="20" MULTIPLE>';
	if(count($OutUserID)>0){
		foreach($OutUserID as $UserID){
			$currentTeacher='【'.$UserID.'】'.$AllUserName[$UserID];  
			echo '<OPTION VALUE="'.$UserID.'">'.$currentTeacher.'</OPTION>'."\n";
		}
	}
	echo '</SELECT><br></TD><TD bgcolor="#FFFFCC" VALIGN="TOP" align="center" width="10%">';

	echo '</select><font size="3" face="arial">'."\n";
	echo '<INPUT TYPE="HIDDEN" NAME="course[0]" VALUE="'.$course[0].'">'."\n";
	echo '<INPUT TYPE="HIDDEN" NAME="course[1]" VALUE="'.$course[1].'">'."\n";
	//echo '<INPUT TYPE="HIDDEN" NAME="course[2]" VALUE="'.$course[2].'">'."\n";
	echo '<INPUT TYPE="HIDDEN" NAME="organization[0]" VALUE="'.$organ[0].'">'."\n";
	echo '<INPUT TYPE="HIDDEN" NAME="organization[1]" VALUE="'.$organ[1].'">'."\n";
	echo '<INPUT TYPE="HIDDEN" NAME="organization[2]" VALUE="'.$organ[2].'">'."\n";
	echo '<INPUT TYPE="HIDDEN" NAME="organization[3]" VALUE="'.$organ[3].'">'."\n";
	echo '<INPUT TYPE="HIDDEN" NAME="opt2" VALUE="teacher">'."\n";
	echo '<br><br>';
	echo '<select name="opt">
         <option value="TeacherAdd2class">新增 →</option>
         <option value="delTeacherClass">← 刪除</option></select>';
	echo '<br><br>';
	echo '<INPUT TYPE="SUBMIT" NAME="SUBMIT" VALUE="送出">';
	echo '</TD><TD bgcolor="#FFFFCC" VALIGN="TOP" align="center"><TABLE BORDER="0" CELLPADDING="5" CELLSPACING="0"><TR><TD>';
	echo '<SELECT NAME="AuthTeachers[]" SIZE="20" MULTIPLE>';
   
	if(count($InUserID)>0){
		foreach($InUserID as $UserID){
			if($AllUserName[$UserID]!=""){
				$name='【'.$UserID.'】'.$AllUserName[$UserID]; 
	   			echo '<OPTION VALUE="'.$UserID.'">'.$name.'</OPTION>'."\n";
	   		}
   		}
   	}
	echo '</SELECT><br>';
	echo "</TD><TR></TABLE></TD></TR></TABLE></FORM>";

}

function EPadd2course($UserIDs, $course, $organ){
	global $dbh, $module_name, $SubmitFile;

	$CourseID=$course[1];
	while(list($null, $UserID) = each($UserIDs)) {
		$query = 'INSERT INTO user_course (course_id, user_id) VALUES (?,?)';
		$data = array($CourseID, $UserID );

		$result =$dbh->query($query, $data);
		if (PEAR::isError($result)) {
			echo "錯誤訊息：".$result->getMessage()."<br>";
			echo "錯誤碼：".$result->getCode()."<br>";
			echo "除錯訊息：".$result->getDebugInfo()."<br>";
		}else{
			$RedirectTo="Location: modules.php?op=modload&name=".$module_name."&file=".$SubmitFile."&opt=EP2course&course[0]=".$course[0]."&course[1]=".$course[1]."&course[2]=".$course[2]."&organization[0]=".$organ[0]."&organization[1]=".$organ[1]."&organization[2]=".$organ[2]."&organization[3]=".$organ[3];
			Header($RedirectTo);
		}
	}
}

function course_delEP($UserIDs, $course, $organ){
	global $dbh, $module_name, $SubmitFile;

	//debug_msg("第".__LINE__."行 UserIDs ", $UserIDs);
	//debug_msg("第".__LINE__."行 course ", $course);
	//die();

	$CourseID=$course[1];
	while(list($null, $UserID) = each($UserIDs)) {
		$sql="DELETE FROM user_course WHERE course_id='".$CourseID."' AND user_id='".$UserID."'";
		$result = $dbh->query($sql);
		if (PEAR::isError($result)) {
			echo "錯誤訊息：".$result->getMessage()."<br>";
			echo "錯誤碼：".$result->getCode()."<br>";
			echo "除錯訊息：".$result->getDebugInfo()."<br>";
			die();
		}
	}
	$RedirectTo="Location: modules.php?op=modload&name=".$module_name."&file=$SubmitFile&opt=EP2course&course[0]=".$course[0]."&course[1]=".$course[1]."&course[2]=".$course[2]."&organization[0]=".$organ[0]."&organization[1]=".$organ[1]."&organization[2]=".$organ[2]."&organization[3]=".$organ[3];
	Header($RedirectTo);
}



?>

