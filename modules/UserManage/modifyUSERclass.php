<?php
require_once "HTML/QuickForm.php";
require_once "include/adp_API.php";

if(!$auth->checkAuth()){
	require_once "feet.php"; 
	die();
}

//-- 顯示主畫面上方子選單
//USER_MANAGE_table_header();

?>
<br>
<table width="98%" border="1" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="center" bordercolor="#FFCC33">
<?php 
	chooseUSER(); 

echo '</td></tr></table>';

if($_POST['listUSER']){
	listUSER($_REQUEST['organization']);
}

function chooseUSER(){
	global $dbh;

	$form = new HTML_QuickForm('frmTest','post','');
	//-- 尋找目前已建立之學校、單位，並初始化"關聯選單"
	$select1[0]='縣市';
	$select2[0][0]='學校名稱';
	$select3[0][0][0]='年級';
	$select4[0][0][0][0]='班級';

	$sql = "select distinct city_code, organization_id, grade, class from user_info WHERE grade>0 AND class>0 ORDER BY city_code";
	//debug_msg("第".__LINE__."行 sql ", $sql);
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
		//debug_msg("第".__LINE__."行 row ", $row);
	}

	//-- 顯示選單
	$form->addElement('header','newheader','<center>&nbsp;&nbsp;選取班級&nbsp;&nbsp;</center>');
	$sel =& $form->addElement('hierselect', 'organization', '班級：');  //關聯式選單
	$sel->setOptions(array($select1, $select2, $select3, $select4));
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name','UserManage');
	$form->addElement('hidden','file','modifyUSERclass');
	$form->addElement('submit','listUSER','選取完畢，送出');
	$form->addRule('organization', '「服務單位」不可空白！', 'nonzero', null, 'client', null, null);
	$form->setRequiredNote('前有<font color=red>*</font>的欄位不可空白');
	$form->display();
}

function listUSER($org){
	global $dbh, $user_data;

	OpenTable();

	//print_r($org);

	$class_name=id2city($org[0])."&nbsp;".id2org($org[1])."&nbsp;".$org[2]."年&nbsp;".$org[3]."班";
	//$delCLASS_url="modules.php?op=modload&name=ConceptStructure&file=modifyEP&opt=deleteEP&cs_id=".$cs_id.'&paper_vol='.$paper_vol;
	//$delCLASS = "<a href=\"javascript:if (confirm('你確定刪除這個班級？\n   這樣會刪除這個班級下的所有成員！')==true) self.location = '".$delCLASS_url."';\"><img src=\""._ADP_URL."images/delete.png\" alt=\"刪除整班學生\" border=\"0\">&nbsp;刪除整班學生</a> ";
	echo '<table width="100%" border="1" cellpadding="1" cellspacing="1" bordercolor="#0000FF" ';
	echo "<tr><td align=\"center\" colspan=\"5\"><font class=\"title\"><b>".$class_name."</b></font>&nbsp;&nbsp;&nbsp;&nbsp;【".$delCLASS."】</td>";
	echo '</tr><tr>';
	echo '<td align="center"><font class="title"><b>帳號</b></font></td>';
	echo '<td align="center"><font class="title"><b>姓名</b></font></td>';
	echo '<td align="center"><font class="title"><b>密碼</b></font></td>';
	echo '<td align="center"><font class="title"><b>身份</b></font></td>';
	echo '<td align="center"><font class="title"><b>編修功能</b></font></td>';
	echo '</tr>';

	$sql = "select * from user_info, user_status where user_info.organization_id = '$org[1]' and user_info.grade='$org[2]' and user_info.class='$org[3]' and user_info.user_id=user_status.user_id and user_status.access_level<'$user_data->access_level' order by user_status.access_level, user_info.user_id";
	//echo $sql."<br>";
	
	$result = $dbh->query($sql);    
	while ($data = $result->fetchRow()) {
		echo "<tr>";
		echo '<td align="left">'.$data['user_id'].'</td>';
		echo '<td align="left">'.$data['uname'].'</td>';
		echo '<td align="left">'.pass2compiler($data['viewpass']).'</td>';
		echo '<td align="left">'.id2level($data['access_level']).'</td>';
		
		/*
		$base='modules.php?op=modload&name=ConceptStructure&file=modifyITEM&pid='.$_REQUEST['unit_item'][0].'&sid='.$_REQUEST['unit_item'][1].'&vid='.$_REQUEST['unit_item'][2].'&uid='.$_REQUEST['unit_item'][3].'&paper='.$paper_vol;
		//$del_url=$base."&opt=deleteITEM&item_sn=".$item_data->item_sn;
		$del = "<a href=\"javascript:if (confirm('你確定刪除這個帳號？".$data['user_id']."【".$data['uname']."】？')==true) self.location = '".$del_url."';\"><img src=\""._ADP_URL."images/delete.png\" alt=\"刪除使用者帳號\" border=\"0\" align=\"texttop\">&nbsp;刪除&nbsp;</a> ";
		//$modify_url=$base."&opt=editITEM&opti=editITEM&item_num=".$item_data->item_num;
		$modify = '<a href="'.$modify_url.'"><img src="'._ADP_URL.'images/edit.png" alt="修改使用者資料" border="0" align=\"texttop\">&nbsp;修改&nbsp;</a>';
		echo '<td>&nbsp;'.$modify.'&nbsp;&nbsp;&nbsp;&nbsp;'.$del.'</td></tr>'; 
		*/
		echo '<td>&nbsp;</td></tr>'; 
	}

	//print_r($_REQUEST['unit_item']);
	echo "</table>";
	
	CloseTable();
}

function editUSER($opt){
	global $dbh;

	
	//-- 顯示試題
	$_SESSION['item'][0]=$my_publisher_id=$_REQUEST['pid'];
	$_SESSION['item'][1]=$my_subject_id=$_REQUEST['sid'];
	$_SESSION['item'][2]=$my_vol=$_REQUEST['vid'];
	$_SESSION['item'][3]=$my_unit=$_REQUEST['uid'];
	$_SESSION['item']['item_num']=$item_num=$_REQUEST['item_num'];
	$_SESSION['item']['paper']=$paper_vol=$_REQUEST['paper'];
	$cs_id=get_csid($my_publisher_id, $my_subject_id, $my_vol, $my_unit);
	$item_data=new ItemData($cs_id, $paper_vol, $item_num);
	$item=$item_data->getItemData();
	$item_title=id2publisher($my_publisher_id)."-".id2subject($my_subject_id)."第 $my_vol 冊 第 $my_unit 單元【".$item_data->concept_name."】-第 $item_num 題";
	echo "<font class=\"title\"><b>".$item_title."</b></font><br>";
	echo "<table width=\"600\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
	echo "<tr><td colspan=\"2\"><img border=\"0\" src=\"".$item[0]."\"</td></tr>";
	for($i=1;$i<=4;$i++){
		echo "<tr><td width=\"20\" align=\"right\">(".$i.")</td><td width=\"580\" align=\"left\"><img border=\"0\" src=\"".$item[$i]."\"></td></tr>\n";
	}
	echo "</table><hr>";
		
	$form = new HTML_QuickForm('frmTest','post','');

	//-- 顯示選單
	echo " 修改試題 <br>";
	$form->addElement('header', 'myheader', $item_title);  //標頭文字
	// Create the Element
	for ($i=1;$i<=4;$i++)  $op_ans[$i]=$i;  //建立選項數
	for ($i=1;$i<=10;$i++)  $op_points[$i]=$i;  //建立選項數
	$form->addElement('file','userfile[]','題目：');
	$form->addElement('file','userfile[]','選項1：');
	$form->addElement('file','userfile[]','選項2：');
	$form->addElement('file','userfile[]','選項3：');
	$form->addElement('file','userfile[]','選項4：');
	$form->addElement('select', 'myans', '正確答案：', $op_ans);
	$form->addElement("select", "mypoints", "配分：", $op_points);
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name','ConceptStructure');
	$form->addElement('hidden','file','modifyITEM');
	$form->addElement('hidden','opt','updateITEM');
	$form->addElement('hidden','item_sn',$item_data->item_sn);
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$selected = array("myans"=>$item['ans'],
		"mypoints"=>$item['points']
	); 
	
	$form->setDefaults($selected);
	$form->display();
	$_SESSION['item_data']=serialize($item_data);  
}

function updateITEM($item_sn){
	global $dbh;

	$item=unserialize($_SESSION['item_data']);

	$upload = new HTTP_Upload();
	if (PEAR::isError($upload)) die ($upload->getMessage());  //顯示錯誤訊息
	$files = $upload->getFiles();
	if (PEAR::isError($files)) die ($files->getMessage());  //顯示錯誤訊息
	$mydir=_ADP_CS_UPLOAD_PATH.$item->publisher_id."/".$item->subject_id."/".$item->vol."/".$item->unit."/";  //預設上傳試題之目錄
	$i=-1;
	foreach ($files as $file) {
		if ($file->isValid()) {
			
			$file->setName('uniq');
			$file_name = $file->moveTo($mydir); 
			if (PEAR::isError($file_name)) die ('發生錯誤：'.$file_name->getMessage());  //顯示錯誤訊息
		}

		$prop = $file->getProp();   //取得上傳檔案之最後資訊
		if($i==-1){
			if($prop['name']==''){
				$item_filename=$item->item_filename;
			}else{
				$item_filename=$prop['name'];    //取得題目之題幹檔名
			}
		}else{
			if($prop['name']==''){      //未上傳檔案，表示原檔案不更改
				$prop['name']=$item->op_pieces[$i];
			}
			$op_filename.=$prop['name']._SPLIT_SYMBOL;   //取得題目之選項檔名
		}
		$i++;
	}

	//-- 更新資料庫
	$table_name   = 'concept_item';
	$table_values = array(
		'item_filename' => $item_filename,
		'op_filename' => $op_filename,
		'op_ans' => $_REQUEST['myans'],
		'points' => $_REQUEST['mypoints']
	);
	$table_field='item_sn ='.$item->item_sn;
	$result = $dbh->autoExecute($table_name, $table_values,
                        DB_AUTOQUERY_UPDATE, $table_field);
	if($result){
		$RedirectTo='Location: modules.php?op=modload&name=ConceptStructure&file=modifyEP&opt=listEP&unit_item[0]='.$item->publisher_id.'&unit_item[1]='.$item->subject_id.'&unit_item[2]='.$item->vol.'&unit_item[3]='.$item->unit.'&unit_item[5]='.$item->paper_vol;
		unset($item);
		unset($_SESSION['item_data']);
		//echo "<br>TO= $RedirectTo <br>";
		Header($RedirectTo);
	}else{
		die ('138發生錯誤'); 
	}
}


function deleteITEM($opt){
	global $dbh;
	
	if($opt=='deleteITEM' && isset($_REQUEST['item_sn'])){
		$sql="DELETE FROM concept_item WHERE item_sn='".$_REQUEST['item_sn']."'";
		$result = $dbh->query($sql);
	}
	//echo "<pre>";
	//print_r($_REQUEST);
	$RedirectTo='Location: modules.php?op=modload&name=ConceptStructure&file=modifyEP&opt=listEP&unit_item[0]='.$_REQUEST['pid'].'&unit_item[1]='.$_REQUEST['sid'].'&unit_item[2]='.$_REQUEST['vid'].'&unit_item[3]='.$_REQUEST['uid'].'&unit_item[5]='.$_REQUEST['paper'];
	Header($RedirectTo);
}

require_once "feet.php"; 
?>

