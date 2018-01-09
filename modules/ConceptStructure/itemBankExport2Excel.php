<?php
require_once "HTML/QuickForm.php";
require_once "include/adp_API.php";
require_once "classes/PHPExcel.php";
require_once "classes/PHPExcel/IOFactory.php";

$module_name="ConceptStructure";

//要加上 access_level 控制

if($_REQUEST['opt1']=='databseSelected'){
  $database_name=$_REQUEST['database_name'][0];
  IMPORT_ITEM_table_header();
  //-- 顯示主畫面
  listCS($database_name);  
}elseif($_REQUEST['opt2']=='epidSelected'){
  $ep_id = get_epid($_REQUEST['epid'][0],$_REQUEST['epid'][1],$_REQUEST['epid'][2],$_REQUEST['epid'][3],$_REQUEST['epid'][4]);
  itemBankExportExcel($_REQUEST['databaseName'],$ep_id,$_REQUEST['epid'][5]);
}else{
  IMPORT_ITEM_table_header();
  //-- 顯示主畫面
  listDatabase();
}

function listDatabase(){
  global $dbh, $module_name;
  
  $form = new HTML_QuickForm('frmadduser','post',$_SERVER['PHP_SELF']);
  $select1[0]='選擇資料庫';

  $sql="show databases";
  $result=$dbh->query($sql);
  while ($row=$result->fetchRow()){
    if(false!==(strpos($row['Database'],irtstuteach))){
      $database_name[$row['Database']]=$row['Database'];
      $select1[$row['Database']]=$row['Database'];
    }
  }
  //-- 顯示選單
	echo "  題庫資料匯出 <br>";
	$form->addElement('header', 'myheader', '請選擇資料庫：');  //標頭文字
  $sel =& $form->addElement('hierselect', 'database_name', '');
  $sel->setOptions(array($select1));
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file','itemBankExport2Excel');
	$form->addElement('hidden','opt1','databseSelected'); 
	//$form->addRule('database_name', '「資料庫」不可空白！', 'nonzero',null, 'client', null, null);
  $form->addRule('database_name', '「資料庫」不可空白！', null,null, 'client', null, null);
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->display();  
}

function listCS($database_name){
  global $dbh, $module_name;
  
  $form = new HTML_QuickForm('frmadduser','post',$_SERVER['PHP_SELF']);
	$select1[0]='版本';
	$select2[0][0]='領域';
	$select3[0][0][0]='年度';
	$select4[0][0][0][0]='單元';
	$select5[0][0][0][0][0]='卷';
  
	//$sql = 'SELECT distinct exam_paper_id FROM '.$database_name.'.concept_item WHERE (exam_paper_id != -1) ORDER BY exam_paper_id ASC';
  $sql = 'SELECT distinct concept_item.exam_paper_id,concept_info.concept FROM '.$database_name.'.concept_item , '.$database_name.'.concept_info WHERE concept_item.exam_paper_id!=-1 and concept_item.cs_id=concept_info.cs_id ORDER BY concept_item.exam_paper_id ASC';
  $result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$paper_info=explode_ep_id($row['exam_paper_id']);
	  $cc=intval($paper_info[0]);
	  $oi=intval($paper_info[1]);
	  $gr=intval($paper_info[2]);
	  $cl=intval($paper_info[3]);
	  $rl=intval($paper_info[4]);
		$select1[$cc]=id2publisher($cc);
		$select2[$cc][$oi]=id2subject($oi);
		$select3[$cc][$oi][$gr]="20".$gr;
		$select4[$cc][$oi][$gr][$cl]="第".$cl."單元";
		$select5[$cc][$oi][$gr][$cl][$rl]="卷".$rl;
    $select6[$cc][$oi][$gr][$cl][$rl][$row['concept']]=$row['concept'];
		
	}
	//-- 顯示選單
	//echo "☆★☆ 班級列表 ☆★☆<br>";
	$form->addElement('header', 'myheader', '<center> '.$database_name.'試卷列表</canter>');  //標頭文字
	// Create the Element
	$sel =& $form->addElement('hierselect', 'epid', '請選擇試卷：');
	// And add the selection options
	$sel->setOptions(array($select1, $select2, $select3, $select4, $select5,$select6));
	
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file','itemBankExport2Excel');
	$form->addElement('hidden','opt2','epidSelected');
  $form->addElement('hidden','databaseName',$database_name);
	$form->addRule('class', '「班級」不可空白！', 'nonzero',  null, 'client', null, null);
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->display();
}

function itemBankExportExcel($database_name,$ep_id,$concept){
  global $dbh;
  $sql = "select concept_item.item_sn , concept_item.item_num , concept_item_parameter.a , concept_item_parameter.b , concept_item_parameter.c from ".$database_name.".concept_item , ".$database_name.".concept_item_parameter WHERE concept_item.exam_paper_id = '$ep_id' and concept_item.item_sn=concept_item_parameter.item_sn order by concept_item.item_num ";
  $result =$dbh->query($sql);
  $Mcount=0;
  while ($row=$result->fetchRow()){
    $item_sn=$row['item_sn'];
    $item_num=$row['item_num'];
    $irt_a=$row['a'];
    $irt_b=$row['b'];
    $irt_c=$row['c'];
    
    //準備 excel 資料
    if($Mcount==0){
      $excel_content[0]=[$database_name."-".$concept."ep_id:".$ep_id,"a","b","c"];
    }
    $excel_content[$Mcount+1]=[$item_num."[".$item_sn."]",$irt_a,$irt_b,$irt_c];
    $Mcount++;    
  }
  $objPHPExcel = new PHPExcel();
  $objPHPExcel->setActiveSheetIndex(0);
  $objPHPExcel->getActiveSheet()->fromArray($excel_content, null, 'A1');
  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  $filename=$ep_id.'.xlsx';
  ob_clean();
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="'.$filename.'"');
  header('Cache-Control: max-age=0');
  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  $objWriter->save('php://output');
  exit;    
}


?>