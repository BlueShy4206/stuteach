<?php
//require_once "HTML/QuickForm.php";
require_once "include/adp_API.php";
//require_once "read_excel.inc.php";

$module_name = basename(dirname(__FILE__));
$bg = array('#FFFFCC', '#FFCCFF', '#CCFFCC', '#99FFCC', '#CCFF99');

if(!$auth->checkAuth() || $user_data->access_level<80){
	FEETER();
	die();
}

TableTitle('760','加盟店資料查詢');

querySTORE();

if($_REQUEST['opt']=='list'){
	listMEMBER();
}

if(isset($_REQUEST['q_firm_id'])&& isset($_REQUEST['chief_id'])){
	InquirySTORE($_REQUEST['q_firm_id'], $_REQUEST['chief_id']);
}
	echo '</td></tr></table>';
	echo '</td></tr></table>';

function querySTORE(){
	global $user_data, $dbh, $module_name;
	
	OpenTable2();
	echo '<form name="form1" method="post" action="modules.php">';
	echo '<table width="100%" border="0" cellpadding="0" cellspacing="0">
					  <tr>
						<td align="center">請選擇加盟店所在地：<select name="city">
    <option value="0" selected>任何城市</option>';
	$sql="select distinct city_code from firm order by city_code";
	
	$result = $dbh->query($sql);
	while ($data = $result->fetchRow()) {
		echo '<option value="'.$data['city_code'].'">'.id2city($data['city_code']).'</option>';
	}
	echo '</select>
  
	<input name="op" type="hidden" value="modload" />
	<input name="name" type="hidden" value="'.$module_name.'" />
	<input name="file" type="hidden" value="InquirySTORE" />
	<input name="opt" type="hidden" value="list" />
	<input type="submit" name="Submit" class="butn04" value="查詢">';
	echo '<br><font color="#FF0000">說明：如果選擇「任何城市」，則會顯示出所有加盟店。</font>';
	echo '</td>
              </tr>
            </table>';
	echo '</form>';
	CloseTable2();

}

function listMEMBER(){
	global $user_data, $dbh, $bg;

	$sql="SELECT * FROM firm ";
	if($_REQUEST['city']!='0'){
		$sql.=" WHERE city_code='{$_REQUEST['city']}' ";
	}
	$sql.=" ORDER BY city_code";
	echo '<table width="100%" border="0" cellpadding="4" cellspacing="0">
			<tr>
                <td width="10%" class="record" scope="col">編號</td>
                <td width="30%" class="record" scope="col">名稱</td>
                <td width="15%" class="record" scope="col">店長姓名</td>
                <td width="15%" class="record" scope="col">電話</td>
                <td width="10%" class="record" scope="col" align="center">授權數</td>
                <td width="20%" class="record" scope="col" align="center">進階功能</td>
              </tr>';
	
	$result = $dbh->query($sql);
	$count=1;
	$img_q='<img src="'._ADP_URL.'images/inquiry.gif" width="25" height="25" border="0" align="absmiddle" alt="查詢詳細資料">';
	$img_e='<img src="'._ADP_URL.'images/edit_profile.gif" width="25" height="25" border="0" align="absmiddle" alt="編輯基本資料">';
	while ($data = $result->fetchRow()) {
		unset($chief);
		$sql2 = "select user_info.uname, user_info.user_id from user_info, user_status where user_info.firm_id='".$data['firm_id']."' AND user_info.user_id=user_status.user_id AND user_status.access_level='31'";
		$result2 = $dbh->query($sql2);
		while ($data2 = $result2->fetchRow()) {
			$chief['user_id']=$data2['user_id'];
			$chief['uname']=$data2['uname'];
		}
		$q_base='<a href="modules.php?op=modload&name=UserManage&file=InquirySTORE&q_firm_id='.$data['firm_id'].'&chief_id='.$chief['user_id'].'&city='.$_REQUEST['city'].'">'.$img_q.'</a>';
		//$e_base='<a href="modules.php?op=modload&name=UserManage&file=index&opt=edit&q_user_id='.$data['user_id'].'&school='.$_REQUEST['school'].'&grade='.$_REQUEST['grade'].'&class='.$_REQUEST['class'].'">'.$img_e.'</a>';
		echo '
  <tr bgcolor="'.$bg[$count%2].'"> 
    <td valign="bottom" class="line03">'.$count.'</td>
    <td valign="bottom" class="line03">'.id2CityFirm($data['firm_id']).'</td>
    <td valign="bottom" class="line03">'.$chief['uname'].'</td>
    <td valign="bottom" class="line03">'.$data['telno'].'</td>
    <td valign="bottom" class="line03" align="center">'.$data['auth_nums'].'</td>
    <td valign="bottom" class="line03" align="center">'.$q_base."&nbsp;".$img_e.'</td>
  </tr>';

		$count++;
	}
	if($count==0){
		echo '<tr bgcolor="'.$bg[$count%2].'"><td colspan="6">&nbsp; 沒有符合資格的加盟店資料</td></tr>';
	}
	echo '</table>';
}

function InquirySTORE($firm_id, $chief_id){
	global $dbh, $module_name;

	//echo '<hr width="98%">';
	echo "<br>";
	$firm= new FirmData($firm_id);
	$chief_data= new UserData($chief_id);   //取得被查詢者資料物件
	$q_base='<a href="modules.php?op=modload&name=UserManage&file=InquirySTORE&opt=list&city='.$_REQUEST['city'].'">【按此返回】</a>';
	$title="查詢【".$firm->cht_name."】的資料";
	$title.="&nbsp;&nbsp;&nbsp;".$q_base;
	TableTitle('760', $title);
	//echo "<pre>";
	//print_r($firm);
	//print_r($chief_data);
	
	OpenTable2();
	echo '<table width="100%" border="0" cellspacing="2" cellpadding="0">
		<tr>
			<td width="80" height="27" align="center" class="title">基本資料</td>
			<td colspan="5">&nbsp;</td>
		</tr>
		<tr>
			<td width="50" align="center" class="record">店名</td>
			<td width="170" class="record01">&nbsp;'.$firm->name.'</td>
			<td width="60" align="center" class="record">所在地</td>
			<td width="100" class="record01">&nbsp;'.$firm->city.'</td>
			<td width="50" align="center" class="record">電話</td>
			<td width="120" class="record01">&nbsp;'.$firm->telno.'</td>
		</tr>
		<tr>
			<td width="50" align="center" class="record">地址</td>
			<td colspan="5" class="record01">&nbsp;'.$firm->address.'</td>
		</tr></table>';
	echo '<table width="100%" border="0" cellspacing="2" cellpadding="0">
		<tr>
			<td width="100" align="center" class="record">開始授權日</td>
			<td width="170" class="record01">&nbsp;'.$firm->start_date.'</td>
			<td width="100" align="center" class="record">終止授權日</td>
			<td width="170" class="record01">&nbsp;'.$firm->stop_date.'</td>
		</tr>';
	echo '<tr>
			<td width="100" align="center" class="record">授權IP</td>
			<td width="170" class="record01">&nbsp;'.$firm->auth_ip.'</td>
			<td width="100" align="center" class="record">授權人數</td>
			<td width="170" class="record01">&nbsp;'.$firm->auth_nums.'</td>
		</tr>';
	echo '<tr>
			<td width="100" align="center" class="record">一次性帳號</td>
			<td width="170" class="record01">&nbsp;'.$firm->access['2'].'</td>
			<td width="100" align="center" class="record">學生人數</td>
			<td width="170" class="record01">&nbsp;'.$firm->access['1'].'</td>
		</tr>';
	echo '</table>';
		
	
	CloseTable2();
	echo "<br>";
	OpenTable2();
	echo '<table width="100%" border="0" cellspacing="2" cellpadding="4">
		<tr>
			<td width="100" height="27" align="center" class="title">店長資料</td>
			<td colspan="5" align="right">&nbsp;</td>
		</tr>
		<tr>
			<td align="center" class="record">姓名</td>
			<td width="120" class="record01">&nbsp;'.$chief_data->uname.'</td>
			<td width="74" align="center" class="record">性別</td>
			<td width="70" class="record01">&nbsp;'.$chief_data->sex.'</td>
			<td width="70" align="center" class="record">密碼</td>
			<td width="100" class="record01">&nbsp;'.$chief_data->viewpass.'</td>
		</tr>
		<tr>
			<td align="center" class="record">電話(1)</td>
			<td class="record01">&nbsp;'.$chief_data->tel.'</td>
			<td align="center" class="record">電話(2)</td>
			<td colspan="3" class="record01">&nbsp;'.$chief_data->mobil.'</td>
		</tr>
		<tr>
			<td align="center" class="record">帳號</td>
			<td class="record01">&nbsp;'.$chief_data->user_id.'</td>
			<td align="center" class="record">縣市</td>
			<td colspan="3" class="record01">&nbsp;'.$chief_data->city_name.'</td>
		</tr>
		<tr>
			<td align="center" class="record">E-Mail</td>
			<td colspan="5" class="record01">&nbsp;'.$chief_data->email.'</td>
		</tr>
		<tr>
			<td align="center" class="record">登入次數</td>
			<td colspan="5" class="record01">&nbsp;'.$chief_data->login_freq.'</td>
		</tr>
		</table>';
	CloseTable2();
	
	echo "<br>";

}




?>