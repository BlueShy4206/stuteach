<?php
require_once "HTML/QuickForm.php";
require_once "include/adp_API.php";
$width="90%";
if($user_data->access_level<=70){
	Header("Location: index.php");
}
$SubmitFile=basename(__FILE__);
$SubmitFile=str_replace(".php", "", $SubmitFile);
$module_name = basename(dirname(__FILE__));

echo "<br>";
IMPORT_USER_table_header();
//debug_msg("第".__LINE__."行 _REQUEST ", $_REQUEST);

echo '
<table width="'.$width.'" border="1" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" bordercolor="#FFCC33">';

if($_POST['listUSER']){
	listUSER($_REQUEST['organization']);
}elseif($_REQUEST[opt]=='deleteUSER' && isset($_REQUEST['uid']) && isset($_REQUEST['org'])){
	deleteUSER($_REQUEST[opt],$_REQUEST['uid'], $_REQUEST['org']);
}elseif($_REQUEST[opt]=='deleteCLASS' && isset($_REQUEST['org'])){
	deleteCLASS($_REQUEST['org']);
}elseif($_REQUEST[opt]=='editUSER' && isset($_REQUEST['quser'])){
	editUSER($_REQUEST['quser'], $_REQUEST['org']);
}elseif($_REQUEST[opt]=='update'){
	updateUSER($_REQUEST['pass1'], $_REQUEST['quser'], $_REQUEST['org'], $_REQUEST['uname'], $_REQUEST['sex']);
}else{
	TableTitle($width,'查詢使用者資料');
	chooseUSER();
}
echo '</td></tr></table>';

function chooseUSER(){
	global $dbh, $SubmitFile, $module_name;

	$form = new HTML_QuickForm('frmTest','post','');
	//-- 尋找目前已建立之學校、單位，並初始化"關聯選單"
	/* $select1[0]='單位(學校)';
	$select2[0][0]='科系';
	$select3[0][0][0]=' ';
	$select4[0][0][0][0]=' '; */

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
	$form->addElement('header','newheader','<center>&nbsp;&nbsp;選取單位&nbsp;&nbsp;</center>');
	$sel =& $form->addElement('hierselect', 'organization', '單位：');  //關聯式選單
	$sel->setOptions(array($select1, $select2, $select3, $select4));
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file',$SubmitFile);
	$form->addElement('submit','listUSER','選取完畢，送出');
	$form->addRule('organization', '「單位」不可空白！', 'nonzero', null, 'client', null, null);
	$form->setRequiredNote('前有<font color=red>*</font>的欄位不可空白');
	$form->display();
}

function listUSER($org){
	global $dbh, $user_data, $width, $SubmitFile, $module_name;

	OpenTable2($width);

	//$class_name=id2city($org[0])."&nbsp;".id2org($org[1])."&nbsp;".$org[2]."年&nbsp;".$org[3]."班";
	$class_name=id2city($org[0])."&nbsp;".id2org($org[1]);
	$delCLASS_url="modules.php?op=modload&name=".$module_name.'&file='.$SubmitFile.'&opt=deleteCLASS&org[0]='.$org[0].'&org[1]='.$org[1].'&org[2]='.$org[2].'&org[3]='.$org[3];
	//$delCLASS = "<a href=\"javascript:if (confirm('你確定刪除這個班級？\n   這樣會刪除這個班級下的所有成員！')==true) self.location = '".$delCLASS_url."';\"><img src=\""._ADP_URL."images/delete.png\" alt=\"刪除整班學生\" border=\"0\">&nbsp;刪除整班學生</a> ";
	$delCLASS = "<a href=\"javascript:if (confirm('你確定刪除這個班級？\n   這樣會刪除這個班級下的所有成員！')==true) self.location = '".$delCLASS_url."';\"><img src=\""._ADP_URL."images/delete.png\" alt=\"刪除\" border=\"0\">&nbsp;刪除</a> ";
	echo '<table width="'.$width.'" border="1" cellpadding="1" cellspacing="1" bordercolor="#0000FF" >';
	echo "<tr><td align=\"center\" colspan=\"5\"><font class=\"title\"><b>".$class_name."</b></font>&nbsp;&nbsp;&nbsp;&nbsp;【".$delCLASS."】</td>";
	echo '</tr><tr>';
	echo '<td align="center"><font class="title"><b>帳號</b></font></td>';
	echo '<td align="center"><font class="title"><b>姓名</b></font></td>';
	echo '<td align="center"><font class="title"><b>密碼</b></font></td>';
	echo '<td align="center"><font class="title"><b>身份</b></font></td>';
	echo '<td align="center"><font class="title"><b>編修功能</b></font></td>';
	echo '</tr>';

	$sql = "select * from user_info, user_status where user_info.organization_id = '$org[1]' and user_info.grade='$org[2]' and user_info.class='$org[3]' and user_info.user_id=user_status.user_id and user_status.access_level<'".$user_data->access_level."' order by user_status.access_level, user_info.user_id";

	$result = $dbh->query($sql);    
	while ($data = $result->fetchRow()) {
		echo "<tr>";
		echo '<td align="left">'.$data['user_id'].'</td>';
		echo '<td align="left">'.$data['uname'].'</td>';
		echo '<td align="left">'.pass2compiler($data['viewpass']).'</td>';
		echo '<td align="left">'.id2level($data['access_level']).'</td>';

		$base='modules.php?op=modload&name='.$module_name.'&file='.$SubmitFile.'&uid='.$data['user_id']."&org[0]=".$org[0].'&org[1]='.$org[1].'&org[2]='.$org[2].'&org[3]='.$org[3];
		$del_url=$base."&opt=deleteUSER";
		$del = "<a href=\"javascript:if (confirm('你確定刪除這個帳號？".$data['user_id']."【".$data['uname']."】？他的所有測驗資料將被刪除！')==true) self.location = '".$del_url."';\"><img src=\""._ADP_URL."images/delete.png\" alt=\"刪除使用者帳號\" border=\"0\" align=\"texttop\">&nbsp;刪除&nbsp;</a> ";
		$modify_url=$base."&opt=editUSER&quser=".$data['user_id'];
		$modify = '<a href="'.$modify_url.'"><img src="'._ADP_URL.'images/edit.png" alt="修改使用者資料" border="0" align="texttop">&nbsp;修改&nbsp;</a>';
		echo "<td>&nbsp;".$modify."&nbsp;&nbsp;".$del.'</td></tr>'; 

		//echo '<td>&nbsp;</td>';
		echo '</tr>'; 
	}

	//print_r($_REQUEST['unit_item']);
	echo "</table>";
	
	CloseTable2();
}

function deleteUSER($opt,$delUserID,$org){
	global $dbh, $SubmitFile, $module_name, $user_data;

	if($opt=='deleteUSER' && isset($_REQUEST['uid'])){
		$sqla[]="DELETE FROM user_info WHERE user_id='".$delUserID."'";
		$sqla[]="DELETE FROM user_status WHERE user_id='".$delUserID."'";
		$sqla[]="DELETE FROM user_parents WHERE user_id='".$delUserID."'";
		$sqla[]="DELETE FROM exam_record WHERE user_id='".$delUserID."'";
		$sqla[]="DELETE FROM user_course WHERE user_id='".$delUserID."'";
		foreach($sqla as $sql){
			$result = $dbh->query($sql);
			if (PEAR::isError($result)) {
				die($result->getMessage());
			}
		}
	}

	echo "<h2>刪除成功！</h2>";
	//print_r($_REQUEST);
	$RedirectTo='modules.php?op=modload&name='.$module_name.'&file='.$SubmitFile.'&listUSER=1&organization[0]='.$org[0].'&organization[1]='.$org[1].'&organization[2]='.$org[2].'&organization[3]='.$org[3];
	//echo '【<a href="'.$RedirectTo.'">下一步</a>】';
}

function deleteCLASS($org){
	global $dbh, $SubmitFile, $module_name, $user_data;

	$CD=new ClassData($org);
	$mem=$CD->getClassMember();
	if(count($mem)>0){
		foreach($mem as $val){
			$a=explode(_SPLIT_SYMBOL, $val);
			if($user_data->access_level > $a[3]){
				$user[]=$a[1];
			}
		}
	}else{
		echo "該班無任何成員！";
	}

	foreach($user as $delUserID){
		$sqla[]="DELETE FROM user_info WHERE user_id='".$delUserID."'";
		$sqla[]="DELETE FROM user_status WHERE user_id='".$delUserID."'";
		$sqla[]="DELETE FROM user_parents WHERE user_id='".$delUserID."'";
		$sqla[]="DELETE FROM exam_record WHERE user_id='".$delUserID."'";
		$sqla[]="DELETE FROM user_course WHERE user_id='".$delUserID."'";
		foreach($sqla as $sql){
			$result = $dbh->query($sql);
			if (PEAR::isError($result)) {
				die($result->getMessage());
			}
		}
	}

	echo "<h2>刪除成功！</h2>";
	//print_r($_REQUEST);
	$RedirectTo='modules.php?op=modload&name='.$module_name.'&file='.$SubmitFile.'&listUSER=1&organization[0]='.$org[0].'&organization[1]='.$org[1].'&organization[2]='.$org[2].'&organization[3]='.$org[3];
	//echo '【<a href="'.$RedirectTo.'">下一步</a>】';
}

function editUSER($quser, $opt){
	global $dbh, $module_name, $user_data, $SubmitFile;

	$UD=new UserData($quser);
	//$school=$user_data->city_name.$user_data->organization_name;
	//$class=$user_data->grade.'年'.$user_data->class_name.'班';
	echo '<script language="JavaScript" src="include/chk_data.js"></script>';
	echo '<form action="modules.php" method="post" name="frmadduser" id="frmadduser" onsubmit="return validate_frmadduser(this);">
	<input name="quser" type="hidden" value="'.$quser.'" />
	<input name="op" type="hidden" value="modload" />
	<input name="name" type="hidden" value="'.$module_name.'" />
	<input name="opt" type="hidden" value="update" />
	<input name="file" type="hidden" value="'.$SubmitFile.'" />';
	echo '
		<table width="760" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td scope="col"><img src="'._THEME_IMG.'main_lt.gif" width="12" height="12" /></td>
			<td width="98%" background="'._THEME_IMG.'main_ct.gif" scope="col"></td>
			<td scope="col"><img src="'._THEME_IMG.'main_rt.gif" width="12" height="12" /></td>
		</tr>
		<tr>
			<td background="'._THEME_IMG.'main_lc.gif"></td>
			<td><table width="100%" border="0" cellspacing="2" cellpadding="0">
		<tr>
			<td width="100" height="27" align="center" class="title">個人資料</td>
			<td colspan="5">&nbsp;</td>
		</tr>
		<tr>
			<td align="center" align="center" class="record">帳號</td>
			<td width="115"  class="record01">'.$UD->user_id.'</td>
			<td width="86" align="center" class="record">姓名</td>
			<td width="109"  class="record01"><input name="uname" type="text" size="20" value="'.$UD->uname.'" ></td>
			<td width="93" align="center" class="record">性別</td>
			<td width="107"  class="record01"><input name="sex" type="text" size="20" value="'.$UD->sex.'" ></td>
		</tr>
		<tr>
			<td align="center" class="record">密碼(新)</td>
			<td width="115"  class="record01"><input name="pass1" type="password" size="20" /></td>
			<td width="86" align="center" class="record">密碼確認</td>
			<td width="109"  class="record01" colspan="3"><input name="pass2" type="password" size="20" /></td>
		</tr>

		</table>
			</td>
			<td background="'._THEME_IMG.'main_rc.gif"></td>
		</tr>
		<tr>
			<td><img src="'._THEME_IMG.'main_ld.gif" width="12" height="12" /></td>
			<td background="'._THEME_IMG.'main_cd.gif"></td>
			<td><img src="'._THEME_IMG.'main_rd.gif" width="12" height="12" /></td>
		</tr>
	</table>';

	echo '<br><center>';
	echo '<input name="addnewuser" value="確定修改，送出" type="submit" class="butn01" />';
	echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="Submit" value="重新填寫" class="butn02" ></td>';
	echo '</form>';
}


function updateUSER($pass,$user_id,$org,$uname,$sex){
	global $dbh;

	$UD=new UserData($user_id);

	if($pass==""){
		$pass=pass2compiler($UD->viewpass);
	}
	if($uname==""){
		$uname=$UD->uname;
	}
	if($sex==""){
		$sex=$UD->sex;
	}
	$sql="UPDATE user_info SET pass = '".md5($pass)."', viewpass = '".pass2compiler($pass)."' , uname= '".$uname."', sex='".$sex."' WHERE user_id ='{$user_id}'";
	$result = $dbh->query($sql);
	if (PEAR::isError($result)) {
		die($result->getMessage());
	}else{
		echo "<br>資料更新成功！<br>";
	}
}




?>

