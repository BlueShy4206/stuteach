<?php
require_once "include/adp_API.php";
$module_name = basename(dirname(__FILE__));

if(!Auth::staticCheckAuth($options)){  //檢查登入狀況
	Header("Location: index.php");
	die();
}

TableTitle('90%','修改個人資料');
//echo '<table width="95%" border="0" cellspacing="4" cellpadding="0"><tr><td>';

echo "<hr><br>";
/*
$sql = "select access_level from user_info, user_status where user_info.user_id=user_status.user_id and user_info.user_id='kbc' and access_level>8";   
$result = $dbh->query($sql);
while ($data = $result->fetchRow()) {
    var_dump($data);
    echo "<hr>";
}


echo "<hr>";
*/

if($_REQUEST['opt']=="update"){
	updateUSER($_POST['pass1'], $user_data->user_id);
}
echo '<table><tr><td>';
if($user_data->access_level<=10){
	editUSER_stu($_REQUEST['opt']);
}elseif($user_data->access_level>=20){
	editUSER($_REQUEST['opt']);
}
echo '</td></tr></table>';



function editUSER_stu($opt){
	global $dbh, $module_name, $user_data;

	$school=$user_data->city_name.$user_data->organization_name;
	$class=$user_data->grade.'年'.$user_data->class_name.'班';
?>

<script language="JavaScript" src="include/chk_data.js"></script>

<?php
	echo '<script language="JavaScript" src="include/chk_data.js"></script>';
	echo '<form action="modules.php" method="post" name="frmadduser" id="frmadduser" onsubmit="return validate_frmadduser(this);">
	<input name="op" type="hidden" value="modload" />
	<input name="name" type="hidden" value="'.$module_name.'" />
	<input name="opt" type="hidden" value="update" />
	<input name="file" type="hidden" value="modifyData" />';
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
			<td width="100" height="27" align="center" class="title">學生資料</td>
			<td colspan="5">&nbsp;</td>
		</tr>
		<tr>
			<td align="center" class="record">帳號</td>
			<td width="115" class="record01"> '.$user_data->user_id.'</td>
			<td width="86" align="center" class="record">姓名</td>
			<td width="109" class="record01">　'.$user_data->uname.'</td>
			<td width="93" align="center" class="record">性別</td>
			<td width="107" class="record01">　'.$user_data->sex.'</td>
		</tr>
		<tr>
			<td align="center" class="record">密碼(新)</td>
			<td width="115" class="record01"><input name="pass1" type="password" size="20" /></td>
			<td width="86" align="center" class="record">密碼確認</td>
			<td class="record01" colspan="3"><input name="pass2" type="password" size="20" /></td>
			
		</tr>
		<tr>
			<td align="center" class="record">就讀學校                                      </td>
			<td colspan="3" class="record01">　'.$school.'</td>
			<td width="93" align="center" class="record">班級</td>
			<td width="107" class="record01">　'.$class.'</td>
			
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

function editUSER($opt){
	global $dbh, $module_name, $user_data;
	
	//$school=$user_data->city_name.$user_data->organization_name;
	//$class=$user_data->grade.'年'.$user_data->class_name.'班';
	echo '<script language="JavaScript" src="include/chk_data.js"></script>';
	echo '<form action="modules.php" method="post" name="frmadduser" id="frmadduser" onsubmit="return validate_frmadduser(this);">
	<input name="op" type="hidden" value="modload" />
	<input name="name" type="hidden" value="'.$module_name.'" />
	<input name="opt" type="hidden" value="update" />
	<input name="file" type="hidden" value="modifyData" />';
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
			<td width="115"  class="record01">'.$user_data->user_id.'</td>
			<td width="86" align="center" class="record">姓名</td>
			<td width="109"  class="record01">'.$user_data->uname.'</td>
			<td width="93" align="center" class="record">性別</td>
			<td width="107"  class="record01">'.$user_data->sex.'</td>
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


function updateUSER($pass,$user_id){
	global $dbh;
	
	if($pass==""){
		echo "<br>沒有輸入密碼！<br>";
	}else{
		$sql="UPDATE user_info SET pass = '".md5($pass)."', viewpass = '".pass2compiler($pass)."' WHERE user_id ='{$user_id}'";
		$result = $dbh->query($sql);
		if($result){
			echo "<br>資料更新成功！<br>";
		}
	}
}


?>

