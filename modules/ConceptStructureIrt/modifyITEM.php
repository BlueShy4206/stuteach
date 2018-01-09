<?php
require_once "HTML/QuickForm.php";
require_once 'HTTP/Upload.php';
require_once "include/adp_API.php";

//-- 顯示主畫面上方子選單
//CS_ITEM_table_header();
IMPORT_CREATITEM_table_header();
$module_name = basename(dirname(__FILE__));

echo '<br>
<table width="98%" border="1" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="center" bordercolor="#FFCC33">';

if($_REQUEST['opt']=="deleteITEM"){
	deleteITEM($_REQUEST['opt']);
}elseif($_REQUEST['opt']=="updateITEM" && $_REQUEST['item_sn']!=''){
	updateITEM($_REQUEST['opt']);
}else{
	editITEM($_REQUEST['opt']); 
}

echo '</td>
  </tr>
</table>';

if($_REQUEST['opt']=="deleteITEM"){
	deleteITEM($_REQUEST['opt']);
}

function editITEM($opt){
	global $dbh, $module_name;


	//-- 顯示試題
	$my_publisher_id=$_REQUEST['pid'];
	$my_subject_id=$_REQUEST['sid'];
	$my_vol=$_REQUEST['vid'];
	$my_unit=$_REQUEST['uid'];
	$item_num=$_REQUEST['item_num'];
	$paper_vol=$_REQUEST['paper'];

	//檢查試卷是否上鎖

	$cs_id=get_csid($my_publisher_id, $my_subject_id, $my_vol, $my_unit);
	$item_data=new IrtItemData($cs_id, $paper_vol, $item_num);
	$item=$item_data->getItemData();
	$sol_item=$item_data->getSolItemData();
	$ItemEduPara=$item_data->getEduParameter();
	$sol_pieces=$item_data->sol_pieces;  //詳解的原始檔名
	$item_title=id2publisher($my_publisher_id)."-".id2subject($my_subject_id)."第 $my_vol 冊 第 $my_unit 單元【".$item_data->concept_name."】-第 $item_num 題";
	echo "<b>".$item_title."</b></font><br>";
	echo "<table width=\"600\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
	echo "<tr><td colspan=\"2\"><img border=\"0\" src=\"".$item[0]."\"</td></tr>";
	for($i=1;$i<=4;$i++){
		echo "<tr><td width=\"100\" align=\"right\">(".$i.")</td><td width=\"580\" align=\"left\"><img border=\"0\" src=\"".$item[$i]."\"></td></tr>\n";
	}
	//debug_msg("第".__LINE__."行 item_data->sol_item_data ", $item_data->sol_item_data);
	//debug_msg("第".__LINE__."行 item_data ", $item_data);
	/*
	for($i=0;$i<count($sol_item);$i++){
		if($i==0){
			echo '<tr><td width="100"></td><td align="center"><hr width=400></td></tr>';
		}
		echo "<tr><td width=\"150\" align=\"right\">選項(".($i+1).")說明</td><td width=\"580\" align=\"left\">";
		if($sol_pieces[$i]==""){
			echo "：無";
		}else{
			echo "<img border=\"0\" src=\"".$sol_item[$i+1]."\">";
		}
		echo "</td></tr>\n";
	}
	*/

	$j=0;
	$ShowItemEduPara="";
	for($i=0;$i<count($ItemEduPara);$i++){
		if($ItemEduPara[$i]!=""){
			$j++;
			if($j==1){
				$ShowItemEduPara.="<hr width=400>教育目標參數：<br>";
			}
			$ShowItemEduPara.="$j .".id2ItemEduPara($ItemEduPara[$i])."<br>";
		}
	}
	echo '<tr><td width="100"></td><td>'.$ShowItemEduPara.'</td></tr>';
	echo "</table><hr>";

	$form = new HTML_QuickForm('frmTest','post','');

	//-- 顯示選單
	echo " 修改試題 <br>";
	$form->addElement('header', 'myheader', $item_title);  //標頭文字
	// Create the Element
	for ($i=1;$i<=4;$i++)  $op_ans[$i]=$i;  //建立選項數
	for ($i=1;$i<=20;$i++)  $op_points[$i]=$i;  //建立配分
	for ($i=1;$i<=8;$i++)  $dims[$i]=$i;  //屬於第幾向度
	$form->addElement('file','userfile[]','題目：');
	$form->addElement('file','userfile[]','選項1：');
	$form->addElement('file','userfile[]','選項2：');
	$form->addElement('file','userfile[]','選項3：');
	$form->addElement('file','userfile[]','選項4：');
	$form->addElement('select', 'myans', '正確答案：', $op_ans);
	$form->addElement("select", "mypoints", "配分：", $op_points);
	$form->addElement('text', 'parameter_a', '鑑別度a');
	$form->addElement('text', 'parameter_b', '難度b');
	$form->addElement('text', 'parameter_c', '猜測度c');
	$form->addElement('select', 'parameter_dim', '第幾向度？', $dims);
//	$form->addElement('text', 'parameter_sub', '能力指標');
//  $form->addElement('file','userfile[]','選項1說明：');
//	$form->addElement('file','userfile[]','選項2說明：');
//	$form->addElement('file','userfile[]','選項3說明：');
//	$form->addElement('file','userfile[]','選項4說明：');
/*	$sql = "SELECT * FROM `exam_edu_parameter` WHERE `show` =1";
	$result = $dbh->query($sql);
	$i=1;
	while ($data = $result->fetchRow()) {
      $_SESSION['EduParaSN'][$i]=$data['sn'];
      if($ItemEduPara[$i-1]!=""){
         $chk="checked";
      }else{
         $chk="";
      }
      $form->addElement("checkbox", "EduPara".$i, "教育目標參數".$i."：" ,$data['edu_parameter'], $chk);
      $i++;
   }  
*/   
    $form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file','modifyITEM');
	$form->addElement('hidden','opt','updateITEM');
	$form->addElement('hidden','item_sn',$item_data->item_sn);
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->addRule('parameter_a', '「鑑別度a」只可輸入數字', 'numeric',null, 'client', null, null);
	$form->addRule('parameter_b', '「難度b」只可輸入數字', 'numeric',null, 'client', null, null);
	$form->addRule('parameter_c', '「猜測度c」只可輸入數字', 'numeric',null, 'client', null, null);
	$selected = array(
		"myans"=>$item['ans'],
		"mypoints"=>$item['points'],
		"parameter_a"=>$item_data->ParaA,
		"parameter_b"=>$item_data->ParaB,
		"parameter_c"=>$item_data->ParaC,
		"parameter_dim"=>$item_data->ParaDim
	); 

	$form->setDefaults($selected);
	$form->display();
	echo '<font color="#FF0000">★★「題目」及「選項」檔案欄位若不更改，請留空白！★★</font><br>';
	echo "<br><br>";
	$_SESSION['item_data']=serialize($item_data);  
}

function updateITEM($item_sn){
	global $dbh, $module_name;

	$item=unserialize($_SESSION['item_data']);
   //$ItemEduPara=$item->getEduParameter();
	$upload = new HTTP_Upload();
	if (PEAR::isError($upload)) die ($upload->getMessage());  //顯示錯誤訊息
	$files = $upload->getFiles();
	if (PEAR::isError($files)) die ($files->getMessage());  //顯示錯誤訊息
	$EduParaSN="";
	for($i=1;$i<=count($_SESSION['EduParaSN']);$i++){
		$sn="";
		if($_POST['EduPara'.$i]==1){
			$sn=$_SESSION['EduParaSN'][$i];
		}
		$EduParaSN.=$sn._SPLIT_SYMBOL;
	}


   $mydir=_ADP_CS_UPLOAD_PATH.$item->publisher_id."/".$item->subject_id."/".$item->vol."/".$item->unit."/";  //預設上傳試題之目錄
	$i=-1;
	foreach ($files as $file) {
		if ($file->isValid()) {
			
			$file->setName('uniq');
			$file_name = strtolower($file->moveTo($mydir));
			if (PEAR::isError($file_name)) die ('發生錯誤：'.$file_name->getMessage());  //顯示錯誤訊息
		}

		$prop = $file->getProp();   //取得上傳檔案之最後資訊
		if($i==-1){   //題幹
			if($prop['name']==''){
				$item_filename=$item->item_filename;
			}else{
				$item_filename=strtolower($prop['name']);    //取得題目之題幹檔名
			}
		}elseif($i>=0 and $i<=3){    //選項
			if($prop['name']==''){      //未上傳檔案，表示原檔案不更改
				$prop['name']=$item->op_pieces[$i];
			}
			$op_filename.=strtolower($prop['name'])._SPLIT_SYMBOL;   //取得題目之選項檔名
		}elseif($i>=4 and $i<=7){    //詳解
			if($prop['name']==''){      //未上傳檔案，表示原檔案不更改
				$prop['name']=$item->sol_pieces[$i-4];
			}
			$op_sol_content.=strtolower($prop['name'])._SPLIT_SYMBOL;   //取得題目之選項詳解檔名
		}
		$this_upload_file[]=$prop['name'];
		//debug_msg("第".__LINE__."行 $i prop ", $prop);
		$i++;
	}

	//檔名轉小寫
	$ii=count($this_upload_file);
	for($i=0;$i<$ii;$i++){
		$org_file = $mydir.$this_upload_file[$i];
		$low_filename = strtolower($this_upload_file[$i]);  //強制轉小寫
		$low_file = $mydir.$low_filename;
		$RenameRsu=rename($org_file, $low_file);
		if(!$RenameRsu){
			die(__LINE__."改檔名錯誤！");
		}
	}

   //debug_msg("第".__LINE__."行 op_filename ", $op_filename);
   //debug_msg("第".__LINE__."行 op_sol_content ", $op_sol_content);
//die();
	//-- 更新資料庫
	$table_name   = 'concept_item';
	$table_values = array(
		'item_filename' => $item_filename,
		'op_filename' => $op_filename,
		'op_content' => $op_sol_content,
		'op_ans' => $_REQUEST['myans'],
		'points' => $_REQUEST['mypoints'],
		'edu_parameter' => $EduParaSN
	);
	$table_field='item_sn ='.$item->item_sn;
	$result = $dbh->autoExecute($table_name, $table_values,
                        DB_AUTOQUERY_UPDATE, $table_field);
                        
    //取得預設值             
    $sql= "select a, b, c, dim, sub from concept_item_parameter where item_sn = '".$item->item_sn."' ";
	$result = $dbh->query($sql);
	while ($data = $result->fetchRow()) {
		$P_a=$data['a'];
		$P_b=$data['b'];
		$P_c=$data['c'];
		$P_dim=$data['dim'];
		$P_sub=$data['sub'];
	}


	if($_REQUEST['parameter_a']!=""){
		if(is_numeric($_REQUEST[parameter_a])){
			$P_a=$_REQUEST['parameter_a'];
		}else{
			die(__LINE__."「鑑別度a」應為數字");
		}
	}

	if($_REQUEST['parameter_b']!=""){
		if(is_numeric($_REQUEST[parameter_b])){
			$P_b=$_REQUEST['parameter_b'];
		}else{
			die(__LINE__."「難度b」應為數字");
		}
	}

	if($_REQUEST['parameter_c']!=""){
		if(is_numeric($_REQUEST[parameter_b])){
			$P_c=$_REQUEST['parameter_c'];
		}else{
			die(__LINE__."「猜測度c」應為數字");
		}
	}
	$P_dim=$_REQUEST[parameter_dim];

    $table_name1   = 'concept_item_parameter';
	$table_values1 = array(
		'a' => $P_a,
		'b' => $P_b,
		'c' => $P_c,
		'dim' => $P_dim,
	);
	$table_field1='item_sn ='.$item->item_sn;
	$result = $dbh->autoExecute($table_name1, $table_values1,
                        DB_AUTOQUERY_UPDATE, $table_field1);
                        
	if($result==1){
		if($_REQUEST['ShowSol']==1){  //顯示詳解
			$ShowSol=1;
		}
		$RedirectTo='Location: modules.php?op=modload&name='.$module_name.'&file=modifyEP&opt=modifyEP&unit_item[0]='.$item->publisher_id.'&unit_item[1]='.$item->subject_id.'&unit_item[2]='.$item->vol.'&unit_item[3]='.$item->unit.'&unit_item[4]='.$item->paper_vol.'&ShowSol'.$ShowSol.'&opt2=modifyEP';
		unset($item);
		unset($_SESSION['item_data']);
		//debug_msg("第".__LINE__."行 RedirectTo ", $RedirectTo);
		//die();
		Header($RedirectTo);
	}else{
		die (__LINE__.'行，發生錯誤');
	}
}


function deleteITEM($opt){
	global $dbh, $module_name;
	
	if($opt=='deleteITEM' && isset($_REQUEST['item_sn'])){
		$sql="DELETE FROM concept_item WHERE item_sn='".$_REQUEST['item_sn']."'";
		$result = $dbh->query($sql);
		$sql="DELETE FROM concept_item_parameter WHERE item_sn='".$_REQUEST['item_sn']."'";
		$result = $dbh->query($sql);
	}
	//echo "<pre>";
	//print_r($_REQUEST);
	$RedirectTo='Location: modules.php?op=modload&name='.$module_name.'&file=modifyEP&opt=modifyEP&unit_item[0]='.$_REQUEST['pid'].'&unit_item[1]='.$_REQUEST['sid'].'&unit_item[2]='.$_REQUEST['vid'].'&unit_item[3]='.$_REQUEST['uid'].'&unit_item[4]='.$_REQUEST['paper'].'&opt2=modifyEP';
	Header($RedirectTo);
}

//require_once "feet.php"; 
?>

