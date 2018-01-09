<?php
require_once "HTML/QuickForm.php";
require_once "include/adp_API.php";


$module_name = basename(dirname(__FILE__));
//$table_fill_color=array("#CCFFFF", "#FFFFCC", "#FFFACD", "#FFFFFF");
$table_fill_color=array("#FFFFFF", "#FFFACD", "#FFFFE0", "#F4F4F4", "#FFF0F5");
$SubmitFile = basename(__FILE__);
$SUBTMP=explode(".", $SubmitFile);
$SubmitFile=$SUBTMP[0];

//-- 顯示主畫面
IMPORT_CREATITEM_table_header();

//echo'<table><tr><td>';

echo '<br>
	<table width="95%" border="1" cellpadding="0" cellspacing="0">
	<tr>
		<td width="45%" align="center" valign="top" bordercolor="#FFCC33">';

if($_REQUEST['opt']=='edit'){
	modifyPublisher($_REQUEST['mopt'], $_REQUEST['pi']);
}else{
	creatPublisher($Msg, $_REQUEST['opt']); 
}

echo '</td><td width="55%" align="center" valign="top" bordercolor="#FFCC33">';

viewPublisher($_REQUEST['opt']);

echo '</td></tr></table>';


//echo'</td></tr></table>';

function creatPublisher($Msg,$opt){
	global $dbh, $user_data, $module_name;

	//debug_msg("第".__LINE__."行 _REQUEST ", $_REQUEST);
	$form = new HTML_QuickForm('frmTest','post','');

	//--  建立新版本訊息
	if ($form->validate() && $opt=='creat_publisher') {
		$sql="select count(publisher_id) from publisher where publisher='".$_REQUEST['mypublisher']."'";
		$data =& $dbh->getOne($sql);
		if($data>0){
			die("<br><br>錯誤！版本名稱重複！<br><br>");
		}
		$sql="select max(publisher_id) from publisher ";
		$data =& $dbh->getOne($sql);
		if($data===null){
			$PublisherID=1;
		}else{
			$PublisherID=$data*1+1;
		}

		//-- 寫入資料庫
		$query = 'INSERT INTO publisher (publisher_id, publisher  ) VALUES (?,?)';
		$data = array($PublisherID, $_REQUEST['mypublisher']);
		$result =$dbh->query($query, $data);
		//echo "<pre>";
		//print_r($result);
		$Msg="「".$_REQUEST['mypublisher']."」建立成功！";
		echo "<br>$Msg<br><br>";
	}


	//建立新版本的表單
	$form->addElement('header', 'myheader', '建立新版本');  //標頭文字
	$ci['0']="==請選擇==";

	$form->addElement('text', 'mypublisher', '版本名稱');
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file','addPublisher');
	$form->addElement('hidden','opt','creat_publisher');
	$form->addElement('submit','btnSubmit','輸入完畢，建立結構');
	$form->addRule('mypublisher', '版本名稱不可空白', 'required', null, 'client', null, null);
	$form->display();

	echo '<br></div>';

}


function modifyPublisher($mopt, $PublisherID){
	global $dbh, $module_name;

	//--  檢查是否為檔案上傳狀態，並回報
	if ($mopt=='modify') {   //第一次輸入資料要修改
		$PublisherName=$_REQUEST[mypublisher];

		//-- 更新資料庫
		$table_name   = 'publisher';
		$table_values = array(
			'publisher' => $PublisherName
		);     
		$table_field='publisher_id ='.$PublisherID;
		$result = $dbh->autoExecute($table_name, $table_values,
                        DB_AUTOQUERY_UPDATE, $table_field);
		if($result==1){
			echo '<br>版本（'.$PublisherName.'）修改成功！<br>';
		}
	}

	$sql = "select * from publisher where publisher_id='".$PublisherID."'";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$publisher=$row[publisher];
		$pid[$row[publisher_id]]=$row[publisher_id];
	}
	$form1 = new HTML_QuickForm('frmTest','post','');

	$form1->addElement('header', 'myheader', '修改版本資訊');  //標頭文字
	$form1->addElement('select', 'mypublisherid', '版本代號', $pid);
	$form1->addElement('text', 'mypublisher', '版本名稱');
	$form1->addElement('hidden','op','modload');
	$form1->addElement('hidden','name',$module_name);
	$form1->addElement('hidden','file','addPublisher');
	$form1->addElement('hidden','opt','edit');
	$form1->addElement('hidden','mopt','modify');
	$form1->addElement('hidden','pi', $PublisherID);
	$form1->addElement('submit','btnSubmit','輸入完畢，送出');
	$selected = array(
			"mypublisher"=>$publisher
	); 
	$form1->addRule('mypublisher', '版本名稱不可空白', 'required',null, 'client', null, null);
	$form1->setDefaults($selected);
	//$form1->freeze();  //固定欄位，不能更改
	$form1->display();
	//debug_msg("第".__LINE__."行 myCS ", $myCS);
	//echo '<font color="#FF0000">★★不更改之檔案欄位請留空白！★★</font><br>';

}


function viewPublisher($opt){
	global $dbh, $table_fill_color, $user_data, $module_name, $SubmitFile;

	if($opt=='delete' && isset($_REQUEST['cs_sn'])){
		$sql = "select publisher_id from publisher where publisher_id='".$_REQUEST['cs_sn']."'";
		//debug_msg("第".__LINE__."行 sql ", $sql);
		$p_id =$dbh->getOne($sql);
		//先刪除該版本下的所有檔案
		if($p_id>0){
			$sql="SELECT cs_id FROM concept_info WHERE publisher_id='".$p_id."'";
			$result = $dbh->query($sql);
			while ($data = $result->fetchRow()) {
				$du[]=$data[cs_id];
			}
			if(count($du)>0){
				$str=implode("','", $du);
				$DelTable=array("concept_info_plus", "concept_info", "concept_item", "concept_item_remedy","concept_matrix_exp","concept_matrix_stu","concept_pr_map","concept_remedy","exam_paper","exam_paper_access","exam_record");
				foreach($DelTable as $val){
					$sql="DELETE FROM ".$val." WHERE cs_id IN ('".$str."')";
					$result = $dbh->query($sql);
				}
			}
			$sql="DELETE FROM publisher WHERE publisher_id='".$p_id."'";
			$result = $dbh->query($sql);
		}else{
			die(__LINE__."該版本原先就不存在！");
		}
	}

echo '
<table width="100%" border="0" align="center">
  <tr>
	<td align="left">版本列表：</td>
	<td align="right">【<a href="modules.php?op=modload&name='.$module_name.'&file=addPublisher">新增版本</a>】</td>
  </tr>
</table>
<table width="98%" border="1" align="center" bordercolor="#FFFFFF">
  <tr>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">版本代號</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">版本名稱</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">功能</div></td>
  </tr>';

	$sql = "select * from publisher order by publisher_id";
	$result = $dbh->query($sql);
	$ii=1;
	while ($data = $result->fetchRow()) {
		$myary=array($data['publisher_id'], $data['publisher']);
		echo "<tr>";
		$cs_title='';
		for($i=0;$i<count($myary);$i++){
			echo "<td bordercolor=\"#4D6185\" bgcolor=\"".$table_fill_color[intval($data['publisher'])%count($table_fill_color)]."\"><div align=\"center\">".$myary[$i]."</div></td>";
		}

		//del功能，備而不用
		$del_url="modules.php?op=modload&name=".$module_name."&file=".$SubmitFile."&opt=delete&cs_sn=".$data['publisher_id'];
        $del = "<a href=\"javascript:if (confirm('你確定刪除這個版本？\n' + '「".$data['name']."」的所有單元資料及考試紀錄都會被刪除！')==true) self.location = '".$del_url."';\"><img src=\""._ADP_URL."images/delete.png\" alt=\"刪除單元\" border=\"0\"></a> ";

        
		$modify_url="modules.php?op=modload&name=".$module_name."&file=addPublisher&opt=edit&pi=".$data['publisher_id'];
		$modify = '<a href="'.$modify_url.'"><img src="'._ADP_URL.'images/edit.png" alt="修改版本名稱" border="0"></a>';
		echo "<td bordercolor=\"#4D6185\" bgcolor=\"#FF99FF\" align=\"center\">".$modify."&nbsp;&nbsp;".$del."</td></tr>";
		$ii++;
	}
	echo "</table>";
}

echo "<br><br>";



//require_once "feet.php"; 

?>
