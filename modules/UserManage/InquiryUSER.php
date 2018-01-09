<?php
//require_once "HTML/QuickForm.php";
require_once "include/adp_API.php";
//require_once "read_excel.inc.php";

$module_name = basename(dirname(__FILE__));
$bg = array('#FFFFCC', '#FFCCFF', '#CCFFCC', '#99FFCC', '#CCFF99');

if(!$auth->checkAuth()){
	FEETER();
	die();
}
//OpenTable();
//echo "<td><center><font class=\"title\"><b>學生資料查詢</b></font><br>";
//CloseTable();

TableTitle('760','學生資料查詢');

queryCLASS();

if($_REQUEST['opt']=='list'){
	listMEMBER();
}

if(isset($_REQUEST['q_user_id'])){
	$sql = "select firm_id from user_info where user_id = '{$_REQUEST['q_user_id']}'";
	$q_firm_id =& $dbh->getOne($sql);
	if($q_firm_id==$user_data->firm_id){
		InquiryUSER($_GET['q_user_id']);
	}else{
		echo "<br>權限不符合！<br>";
	}
}
	echo '</td></tr></table>';
	echo '</td></tr></table>';

function queryCLASS(){
	global $user_data, $dbh, $module_name;
	
	OpenTable2();
	echo '<form name="form1" method="post" action="modules.php">';
	echo '<table width="100%" border="0" cellpadding="0" cellspacing="0">
					  <tr>
						<td align="center">請選擇查詢條件：<select name="school">
    <option value="0" selected>任何學校</option>';
	$sql="select distinct organization_id from user_info where firm_id='{$user_data->firm_id}' AND organization_id!='' AND organization_id!='000000' AND uname!='' AND user_id!='{$user_data->user_id}' order by organization_id";
	
	$result = $dbh->query($sql);
	while ($data = $result->fetchRow()) {
		echo '<option value="'.$data['organization_id'].'">'.id2org($data['organization_id']).'</option>';
	}
	echo '</select>
  <select name="grade">
	<option value="0" selected>任何年級</option>
    <option value="1">一年</option>
    <option value="2">二年</option>
    <option value="3">三年</option>
    <option value="4">四年</option>
    <option value="5">五年</option>
    <option value="6">六年</option>
  </select>
  <select name="class">
	  <option value="0" selected>任何班級</option>';
  for($i=1;$i<=20;$i++){
	  echo '<option value="'.$i.'">'.$i.'班</option>';
  }
	echo '</select>
	<input name="op" type="hidden" value="modload" />
	<input name="name" type="hidden" value="'.$module_name.'" />
	<input name="file" type="hidden" value="InquiryUSER" />
	<input name="opt" type="hidden" value="list" />
	<input type="submit" name="Submit" class="butn04" value="查詢">';
	echo '<br><font color="#FF0000">說明：如果選擇「任何學校」、「任何年級」、「任何班級」，則會顯示出補習班中所有的學生。</font>';
	echo '</td>
              </tr>
            </table>';
	echo '</form>';
	CloseTable2();

}

function listMEMBER(){
	global $user_data, $dbh, $bg;

	//print_r($_REQUEST);
	//$op1=$_REQUEST['choose1'];
	//$op2=$_REQUEST['choose2'];
	//$cho[0]="OR";
	//$cho[1]="AND";
	$sql="SELECT user_info.user_id, uname, organization_id, grade, class, phone1 FROM user_info, user_status, user_parents ";
	$sql.="WHERE user_status.user_id=user_info.user_id AND user_parents.user_id=user_info.user_id AND user_info.firm_id='{$user_data->firm_id}' AND user_status.access_level='1'";
	if($_REQUEST['school']!='0'){
		$sql.=" AND organization_id='{$_REQUEST['school']}' ";
	}
	if($_REQUEST['grade']!='0'){
		$sql.=" AND grade='{$_REQUEST['grade']}' ";
	}
	if($_REQUEST['class']!='0'){
		$sql.=" AND class='{$_REQUEST['class']}' ";
	}
	echo '<table width="100%" border="0" cellpadding="4" cellspacing="0">
			<tr>
                <td width="14%" class="record" scope="col">帳號</td>
                <td width="10%" class="record" scope="col">姓名</td>
                <td width="22%" class="record" scope="col">學校</td>
                <td width="14%" class="record" scope="col">班級</td>
                <td width="16%" class="record" scope="col">電話</td>
                <td width="12%" class="record" scope="col">個人資料</td>
                <td width="12%" class="record" scope="col">歷來測驗</td>
              </tr>';

	$sql.=" ORDER BY organization_id, grade, class, user_info.user_id";
	
	//echo '<br>'.$sql."<br>";
	$result = $dbh->query($sql);
	$count=0;
	$img_q='<img src="'._ADP_URL.'images/inquiry.gif" width="25" height="25" border="0" align="absmiddle" alt="查詢">';
	$img_e='<img src="'._ADP_URL.'images/edit_profile.gif" width="25" height="25" border="0" align="absmiddle" alt="編輯">';
	while ($data = $result->fetchRow()) {
		$q_base='<a href="modules.php?op=modload&name=UserManage&file=InquiryUSER&q_user_id='.$data['user_id'].'&school='.$_REQUEST['school'].'&grade='.$_REQUEST['grade'].'&class='.$_REQUEST['class'].'">'.$img_q.'</a>';
		$t_base='<a href="modules.php?op=modload&name=ExamResult&file=firmReports&q_user_id='.$data['user_id'].'">'.$img_q.'</a>';
		$e_base='<a href="modules.php?op=modload&name=UserManage&file=index&opt=edit&q_user_id='.$data['user_id'].'&school='.$_REQUEST['school'].'&grade='.$_REQUEST['grade'].'&class='.$_REQUEST['class'].'">'.$img_e.'</a>';
		echo '
  <tr bgcolor="'.$bg[$count%2].'"> 
    <td valign="bottom" class="line03">'.$data['user_id'].'</td>
    <td valign="bottom" class="line03">'.$data['uname'].'</td>
    <td valign="bottom" class="line03">'.id2org($data['organization_id']).'</td>
    <td valign="bottom" class="line03">'.num2chinese($data['grade'])."年".num2chinese($data['class'])."班".'</td>
    <td valign="bottom" class="line03">'.$data['phone1'].'</td>
    <td valign="bottom" class="line03">'.$q_base."&nbsp;".$e_base.'</td>
    <td valign="bottom" align="center" class="line03">'.$t_base.'</td>
  </tr>';

		$count++;
	}
	if($count==0){
		echo '<tr bgcolor="'.$bg[$count%2].'"><td colspan="8">&nbsp; 沒有符合資格的學生資料</td></tr>';
	}
	echo '</table>';
}

function InquiryUSER($q_user_id){
	global $dbh, $module_name;

	//echo '<hr width="98%">';
	echo "<br>";
	$q_user_data= new UserData($q_user_id);   //取得被查詢者資料物件
	$q_base='<a href="modules.php?op=modload&name=UserManage&file=InquiryUSER&opt=list&school='.$_REQUEST['school'].'&grade='.$_REQUEST['grade'].'&class='.$_REQUEST['class'].'">【按此返回】</a>';
	$title=$q_user_data->user_id."【".$q_user_data->uname."】的個人資料";
	$title.="&nbsp;&nbsp;&nbsp;".$q_base;
	TableTitle('760', $title);
	
	echo '
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td scope="col"><img src="'._THEME_IMG.'main_lt.gif" width="12" height="12" /></td>
			<td width="98%" background="'._THEME_IMG.'main_ct.gif" scope="col"></td>
			<td scope="col"><img src="'._THEME_IMG.'main_rt.gif" width="12" height="12" /></td>
		</tr>
		<tr>
			<td background="'._THEME_IMG.'main_lc.gif"></td>
			<td><table width="100%" border="0" cellspacing="2" cellpadding="0">
		<tr>
			<td width="80" height="27" align="center" class="title">學生資料</td>
			<td colspan="5">&nbsp;</td>
		</tr>
		<tr>
			<td width="50" align="center" class="record">帳號</td>
			<td width="170" class="record01">&nbsp;'.$q_user_data->user_id.'</td>
			<td width="50" align="center" class="record">姓名</td>
			<td width="109" class="record01">&nbsp;'.$q_user_data->uname.'</td>
			<td width="50" align="center" class="record">性別</td>
			<td width="107" class="record01">&nbsp;'.$q_user_data->sex.'</td>
		</tr>
		<tr>
			<td width="50" align="center" class="record">學校</td>
			<td width="170" class="record01">&nbsp;'.$q_user_data->organization_name.'</td>
			<td width="50" align="center" class="record">密碼</td>
			<td width="109" class="record01">&nbsp;'.$q_user_data->viewpass.'</td>
			<td width="50" align="center" class="record">班級</td>
			<td width="107" class="record01">&nbsp;'.$q_user_data->cht_class.'</td>
			
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
	
	$parents=$q_user_data->get_parents($q_user_data->user_id);  //取得家長資料
	//print_r($parents);
	echo "<br>";
	echo '
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td scope="col"><img src="'._THEME_IMG.'main_lt.gif" width="12" height="12" /></td>
			<td width="98%" background="'._THEME_IMG.'main_ct.gif" scope="col"></td>
			<td scope="col"><img src="'._THEME_IMG.'main_rt.gif" width="12" height="12" /></td>
		</tr>
		<tr>
			<td background="'._THEME_IMG.'main_lc.gif"></td>
			<td><table width="100%" border="0" cellspacing="2" cellpadding="4">
		<tr>
			<td width="100" height="27" align="center" class="title">家長資料</td>
			<td colspan="5" align="right">&nbsp;</td>
		</tr>
		<tr>
			<td align="center" class="record">父 親</td>
			<td width="70" class="record01">'.$parents['father_name'].'</td>
			<td width="74" align="center" class="record">母 親</td>
			<td width="70" class="record01">'.$parents['mother_name'].'</td>
			<td width="100" align="center" class="record">主要照顧者</td>
			<td width="100" class="record01">'.$parents['caretaker'].'</td>
		</tr>
		<tr>
			<td align="center" class="record">電話(1)</td>
			<td class="record01">'.$parents['phone1'].'</td>
			<td align="center" class="record">電話(2)</td>
			<td colspan="3" class="record01">'.$parents['phone2'].'</td>
		</tr>
		<tr>
			<td align="center" class="record">縣 市</td>
			<td class="record01">'.$parents['city_name'].'</td>
			<td align="center" class="record">地 址</td>
			<td colspan="3" class="record01">'.$parents['address'].'</td>
		</tr>
		<tr>
			<td align="center" class="record">E-Mail</td>
			<td colspan="5" class="record01">'.$parents['email'].'</td>
		</tr>
		</table></td>
			<td background="'._THEME_IMG.'main_rc.gif"></td>
		</tr>
		<tr>
			<td><img src="'._THEME_IMG.'main_ld.gif" width="12" height="12" /></td>
			<td background="'._THEME_IMG.'main_cd.gif"></td>
			<td><img src="'._THEME_IMG.'main_rd.gif" width="12" height="12" /></td>
		</tr>
		</table>';
	

	$family=$q_user_data->get_family($q_user_data->user_id);  //取得兄弟姊妹資料
	echo "<br>";
	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td scope="col"><img src="'._THEME_IMG.'main_lt.gif" width="12" height="12" /></td>
			<td width="98%" background="'._THEME_IMG.'main_ct.gif" scope="col"></td>
			<td scope="col"><img src="'._THEME_IMG.'main_rt.gif" width="12" height="12" /></td>
		</tr>
		<tr>
			<td background="'._THEME_IMG.'main_lc.gif"></td>
			<td><table width="100%" border="0" cellspacing="0" cellpadding="2">
		<tr>
			<td width="100" height="27" align="center" class="title">兄弟姐妹</td>
			<td colspan="5" align="right">&nbsp;</td>
		</tr>';
	$count=sizeof($family);
	if($count=="0"){
		echo "<tr><td>沒有相關資料</td></tr>";
	}else{
		echo '<tr>
			<td width="143" colspan="2" class="record">姓　名</td>
			<td width="25%" class="record">性　別</td>
			<td width="30%" class="record">學　校</td>
			<td width="15%" class="record">班　別</td>
		</tr>';
		for($i=1;$i<=$count;$i++){
			 echo '<tr>
				<td colspan="2" class="line03">'.$family[$i]['family_name'].'</td>
				<td class="line03">'.$family[$i]['sex'].'</td>
				<td class="line03">'.$family[$i]['school'].'</td>
				<td class="line03">'.$family[$i]['grade_class'].'</td>
				</tr>';
		}
	}
	echo '</table></td>
                      <td background="'._THEME_IMG.'main_rc.gif"></td>
                    </tr>
                    <tr>
                      <td><img src="'._THEME_IMG.'main_ld.gif" width="12" height="12" /></td>
                      <td background="'._THEME_IMG.'main_cd.gif"></td>
                      <td><img src="'._THEME_IMG.'main_rd.gif" width="12" height="12" /></td>
                    </tr>
                  </table>';
}


?>