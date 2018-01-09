<?php
require_once "HTML/QuickForm.php";
require_once "include/adp_API.php";

$module_name = basename(dirname(__FILE__));
//$table_fill_color=array("#CCFFFF", "#FFFFCC", "#FFFACD", "#FFFFFF");
$table_fill_color=array("#FFFFFF", "#FFFACD", "#FFFFE0", "#F4F4F4", "#FFF0F5");
$SubmitFile = basename(__FILE__);
$SUBTMP=explode(".", $SubmitFile);
$SubmitFile=$SUBTMP[0];

if($user_data->access_level<=70){
	Header("Location: index.php");
}

//-- 顯示主畫面
$width="100%";
echo "<br>";
IMPORT_USER_table_header();
TableTitle($width,'新增科系');

//echo'<table><tr><td>';

echo '<br>
	<table width="95%" border="1" cellpadding="0" cellspacing="0">
	<tr>
		<td width="45%" align="center" valign="top" bordercolor="#FFCC33">';

if($_REQUEST['opt']=='edit'){
	modifySubject($_REQUEST['mopt'], $_REQUEST['si']);
}else{
	creatSubject($Msg, $_REQUEST['opt']); 
}

echo '</td><td width="55%" align="center" valign="top" bordercolor="#FFCC33">';

viewSubject($_REQUEST['opt']);

echo '</td></tr></table>';


//echo'</td></tr></table>';

function creatSubject($Msg,$opt){
	global $dbh, $OpenedCS, $user_data, $module_name;

	//debug_msg("第".__LINE__."行 _REQUEST ", $_REQUEST);
	$form = new HTML_QuickForm('frmTest','post','');

	//--  建立新科系訊息
	if ($form->validate() && $opt=='creat_subject' && $_REQUEST[myorganization]!="") {
		$sql="select count(organization_id) from organization where name='".$_REQUEST['myorganization']."'";
		$data =& $dbh->getOne($sql);
		if($data>0){
			die("<br><br>錯誤！科系名稱重複！<br><br>");
		}
		$sql="select max(organization_id) from organization ";
		$data =& $dbh->getOne($sql);
		if($data===null){
			$OrgID=1;
		}else{
			$OrgID=$data*1+1;
		}

		//-- 寫入資料庫
		$query = 'INSERT INTO organization (organization_id, name, type, city_code, used) VALUES (?,?,?,?,?)';
		$data = array($OrgID, $_REQUEST['myorganization'], 'u', '19', '1');
		$result =$dbh->query($query, $data);
		if($result=='1'){
			$Msg="「".$_REQUEST['myorganization']."」建立成功！";
			echo "<br>$Msg<br><br>";
		}else{
			debug_msg("第".__LINE__."行 result ", $result);
		}
	}


	//建立新科系的表單
	$form->addElement('header', 'myheader', '建立新科系');  //標頭文字
	$ci['0']="==請選擇==";

	$form->addElement('text', 'myorganization', '科系名稱');
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file','addDepartment');
	$form->addElement('hidden','opt','creat_subject');
	$form->addElement('submit','btnSubmit','輸入完畢，送出');
	$form->addRule('mysubject', '科系名稱不可空白', 'required', null, 'client', null, null);
	$form->display();

	echo '<br></div>';

}


function modifySubject($mopt, $OrgID){
	global $dbh, $module_name;

	//--  檢查是否為檔案上傳狀態，並回報
	if ($mopt=='modify' and $_REQUEST[myorganization]!="") {   //第一次輸入資料要修改
		$sql="select count(organization_id) from organization where name='".$_REQUEST['myorganization']."'";
		$data =& $dbh->getOne($sql);
		if($data>0){
			die("<br><br>錯誤！科系名稱重複！<br><br>");
		}
		$OrganizationName=$_REQUEST[myorganization];

		//-- 更新資料庫
		$table_name   = 'organization';
		$table_values = array(
			'name' => $OrganizationName
		);     
		$table_field='organization_id ='.$OrgID;
		$result = $dbh->autoExecute($table_name, $table_values,
                        DB_AUTOQUERY_UPDATE, $table_field);
		if($result=='1'){
			echo '<br>科系（'.$OrganizationName.'）修改成功！<br>';
		}else{
			debug_msg("第".__LINE__."行 result ", $result);
		}
	}

	$sql = "select * from organization where organization_id='".$OrgID."'";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$name=$row[name];
		$sid[$row[organization_id]]=$row[organization_id];
	}
	$form1 = new HTML_QuickForm('frmTest','post','');

	$form1->addElement('header', 'myheader', '修改科系資訊');  //標頭文字
	$form1->addElement('select', 'mysubjectid', '科系代號', $sid);
	$form1->addElement('text', 'myorganization', '科系名稱');
	$form1->addElement('hidden','op','modload');
	$form1->addElement('hidden','name',$module_name);
	$form1->addElement('hidden','file','addDepartment');
	$form1->addElement('hidden','opt','edit');
	$form1->addElement('hidden','mopt','modify');
	$form1->addElement('hidden','si', $OrgID);
	$form1->addElement('submit','btnSubmit','輸入完畢，送出');
	$selected = array(
			"myorganization"=>$name
	); 
	$form1->addRule('myorganization', '科系名稱不可空白', 'required',null, 'client', null, null);
	$form1->setDefaults($selected);
	//$form1->freeze();  //固定欄位，不能更改
	$form1->display();
	//debug_msg("第".__LINE__."行 myCS ", $myCS);
	//echo '<font color="#FF0000">★★不更改之檔案欄位請留空白！★★</font><br>';

}


function viewSubject($opt){
	global $dbh, $table_fill_color, $user_data, $module_name, $SubmitFile;
	if($opt=='delete' && isset($_REQUEST['cs_sn'])){
		$sql = "select organization_id from organization where organization_id='".$_REQUEST['cs_sn']."'";
		$o_id =$dbh->getOne($sql);
		//先刪除該科系下的人員
		if($o_id>0){
			$sql="SELECT user_id FROM user_info WHERE organization_id='".$o_id."'";
			$result = $dbh->query($sql);
			while ($data = $result->fetchRow()) {
				$du[]=$data[user_id];
			}
			if(count($du)>0){
				$str=implode("','", $du);
				$sql="DELETE FROM user_status WHERE user_id IN ('".$str."')";
				$result = $dbh->query($sql);
				$sql="DELETE FROM user_info WHERE organization_id='".$o_id."'";
				$result = $dbh->query($sql);
				$sql="DELETE FROM organization WHERE organization_id='".$o_id."'";
				$result = $dbh->query($sql);
			}else{
				$sql="DELETE FROM organization WHERE organization_id='".$o_id."'";
				$result = $dbh->query($sql);
				echo __LINE__."該科系已被刪除！";
			}
		}else{
			die(__LINE__."該科系本來就不存在！");
		}
	}


echo '
<table width="100%" border="0" align="center">
  <tr>
	<td align="left">科系列表：</td>
	<td align="right">【<a href="modules.php?op=modload&name='.$module_name.'&file=addDepartment">新增科系</a>】</td>
  </tr>
</table>
<table width="98%" border="1" align="center" bordercolor="#FFFFFF">
  <tr>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">科系代號</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">科系名稱</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">功能</div></td>
  </tr>';

	$sql = "select * from organization order by name";
	$result = $dbh->query($sql);
	$ii=1;
	while ($data = $result->fetchRow()) {
		$myary=array($data['organization_id'], $data['name']);
		echo "<tr>";
		$cs_title='';
		$ii=count($myary);
		for($i=0;$i<$ii;$i++){
			echo "<td bordercolor=\"#4D6185\" bgcolor=\"".$table_fill_color[intval($data['subject'])%count($table_fill_color)]."\"><div align=\"center\">".$myary[$i]."</div></td>";
		}
		$del_url="modules.php?op=modload&name=".$module_name."&file=".$SubmitFile."&opt=delete&cs_sn=".$data['organization_id'];
        $del = "<a href=\"javascript:if (confirm('你確定刪除這個科系？\n' + '「".$data['name']."」的所有學生資料會被刪除！')==true) self.location = '".$del_url."';\"><img src=\""._ADP_URL."images/delete.png\" alt=\"刪除單元\" border=\"0\"></a> ";
		$modify_url="modules.php?op=modload&name=".$module_name."&file=addDepartment&opt=edit&si=".$data['organization_id'];
		$modify = '<a href="'.$modify_url.'"><img src="'._ADP_URL.'images/edit.png" alt="修改科系名稱" border="0"></a>';
		echo "<td bordercolor=\"#4D6185\" bgcolor=\"#FF99FF\" align=\"center\">".$modify."&nbsp;&nbsp;".$del."</td></tr>";
		$ii++;
	}
	echo "</table>";
}

echo "<br><br>";



//require_once "feet.php"; 

?>
