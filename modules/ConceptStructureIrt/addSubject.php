<?php
require_once "HTML/QuickForm.php";
require_once "include/adp_API.php";

$module_name = basename(dirname(__FILE__));
//$table_fill_color=array("#CCFFFF", "#FFFFCC", "#FFFACD", "#FFFFFF");
$table_fill_color=array("#FFFFFF", "#FFFACD", "#FFFFE0", "#F4F4F4", "#FFF0F5");

//-- 顯示主畫面
IMPORT_CREATITEM_table_header();

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

	//--  建立新科目訊息
	if ($form->validate() && $opt=='creat_subject') {
		$sql="select count(subject_id) from subject where name='".$_REQUEST['mysubject']."'";
		$data =& $dbh->getOne($sql);
		if($data>0){
			die("<br><br>錯誤！科目名稱重複！<br><br>");
		}
		$sql="select max(subject_id) from subject ";
		$data =& $dbh->getOne($sql);
		if($data===null){
			$SubjectID=1;
		}else{
			$SubjectID=$data*1+1;
		}

		//-- 寫入資料庫
		$query = 'INSERT INTO subject (subject_id, name  ) VALUES (?,?)';
		$data = array($SubjectID, $_REQUEST['mysubject']);
		$result =$dbh->query($query, $data);
		//echo "<pre>";
		//print_r($result);
		$Msg="「".$_REQUEST['mysubject']."」建立成功！";
		echo "<br>$Msg<br><br>";
	}


	//建立新科目的表單
	$form->addElement('header', 'myheader', '建立新科目');  //標頭文字
	$ci['0']="==請選擇==";

	$form->addElement('text', 'mysubject', '科目名稱');
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file','addSubject');
	$form->addElement('hidden','opt','creat_subject');
	$form->addElement('submit','btnSubmit','輸入完畢，建立結構');
	$form->addRule('mysubject', '科目名稱不可空白', 'required', null, 'client', null, null);
	$form->display();

	echo '<br></div>';

}


function modifySubject($mopt, $SubjectID){
	global $dbh, $module_name;

	//--  檢查是否為檔案上傳狀態，並回報
	if ($mopt=='modify') {   //第一次輸入資料要修改
		$SubjectName=$_REQUEST[mysubject];

		//-- 更新資料庫
		$table_name   = 'subject';
		$table_values = array(
			'name' => $SubjectName
		);     
		$table_field='subject_id ='.$SubjectID;
		$result = $dbh->autoExecute($table_name, $table_values,
                        DB_AUTOQUERY_UPDATE, $table_field);
		if($result==1){
			echo '<br>科目（'.$SubjectName.'）修改成功！<br>';
		}
	}

	$sql = "select * from subject where subject_id='".$SubjectID."'";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$name=$row[name];
		$sid[$row[subject_id]]=$row[subject_id];
	}
	$form1 = new HTML_QuickForm('frmTest','post','');

	$form1->addElement('header', 'myheader', '修改科目資訊');  //標頭文字
	$form1->addElement('select', 'mysubjectid', '科目代號', $sid);
	$form1->addElement('text', 'mysubject', '科目名稱');
	$form1->addElement('hidden','op','modload');
	$form1->addElement('hidden','name',$module_name);
	$form1->addElement('hidden','file','addSubject');
	$form1->addElement('hidden','opt','edit');
	$form1->addElement('hidden','mopt','modify');
	$form1->addElement('hidden','si', $SubjectID);
	$form1->addElement('submit','btnSubmit','輸入完畢，送出');
	$selected = array(
			"mysubject"=>$name
	); 
	$form1->addRule('mysubject', '科目名稱不可空白', 'required',null, 'client', null, null);
	$form1->setDefaults($selected);
	//$form1->freeze();  //固定欄位，不能更改
	$form1->display();
	//debug_msg("第".__LINE__."行 myCS ", $myCS);
	//echo '<font color="#FF0000">★★不更改之檔案欄位請留空白！★★</font><br>';

}


function viewSubject($opt){
	global $dbh, $table_fill_color, $user_data, $module_name;


echo '
<table width="100%" border="0" align="center">
  <tr>
	<td align="left">科目列表：</td>
	<td align="right">【<a href="modules.php?op=modload&name='.$module_name.'&file=addSubject">新增科目</a>】</td>
  </tr>
</table>
<table width="98%" border="1" align="center" bordercolor="#FFFFFF">
  <tr>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">科目代號</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">科目名稱</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">功能</div></td>
  </tr>';

	$sql = "select * from subject order by subject_id";
	$result = $dbh->query($sql);
	$ii=1;
	while ($data = $result->fetchRow()) {
		$myary=array($data['subject_id'], $data['name']);
		echo "<tr>";
		$cs_title='';
		for($i=0;$i<count($myary);$i++){
			echo "<td bordercolor=\"#4D6185\" bgcolor=\"".$table_fill_color[intval($data['subject'])%count($table_fill_color)]."\"><div align=\"center\">".$myary[$i]."</div></td>";
		}
		$modify_url="modules.php?op=modload&name=".$module_name."&file=addSubject&opt=edit&si=".$data['subject_id'];
		$modify = '<a href="'.$modify_url.'"><img src="'._ADP_URL.'images/edit.png" alt="修改科目名稱" border="0"></a>';
		echo "<td bordercolor=\"#4D6185\" bgcolor=\"#FF99FF\" align=\"center\">".$modify."&nbsp;&nbsp;</td></tr>";
		$ii++;
	}
	echo "</table>";
}

echo "<br><br>";



//require_once "feet.php"; 

?>
