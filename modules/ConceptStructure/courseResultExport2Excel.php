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
  listCourseAndCS($database_name);  
}elseif($_REQUEST['opt2']=='courseAndEpidSelected'){
  $course_id=$_REQUEST['courseAndEpid'][0];
  $ep_id = get_epid($_REQUEST['courseAndEpid'][1],$_REQUEST['courseAndEpid'][2],$_REQUEST['courseAndEpid'][3],$_REQUEST['courseAndEpid'][4],$_REQUEST['courseAndEpid'][5]);
  courseResultExportExcel($_REQUEST['databaseName'],$ep_id,$_REQUEST['epid'][6],$course_id);
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
	echo "  場地學生施測資料匯出 <br>";
	$form->addElement('header', 'myheader', '請選擇資料庫：');  //標頭文字
  $sel =& $form->addElement('hierselect', 'database_name', '');
  $sel->setOptions(array($select1));
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file','courseResultExport2Excel');
	$form->addElement('hidden','opt1','databseSelected'); 
	//$form->addRule('database_name', '「資料庫」不可空白！', 'nonzero',null, 'client', null, null);
  $form->addRule('database_name', '「資料庫」不可空白！', null,null, 'client', null, null);
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->display();  
}

function listCourseAndCS($database_name){
  global $dbh, $module_name;
  
  $form = new HTML_QuickForm('frmadduser','post',$_SERVER['PHP_SELF']);
	$select1[0]='場地';
	$select2[0][0]='版本';
	$select3[0][0][0]='領域';
	$select4[0][0][0][0]='年度';
	$select5[0][0][0][0][0]='單元';
  $select6[0][0][0][0][0][0]='卷';
  
  $sql='SELECT distinct exam_course_access_irt.course_id,course.name,concept_item.exam_paper_id,concept_info.concept FROM '.$database_name.'.exam_course_access_irt , '.$database_name.'.course , '.$database_name.'.concept_info , '.$database_name.'.concept_item WHERE course.course_id=exam_course_access_irt.course_id and exam_course_access_irt.cs_id=concept_info.cs_id and concept_item.exam_paper_id!=-1 and concept_item.cs_id=concept_info.cs_id ORDER BY exam_course_access_irt.course_id ASC';
  //debug_msg("第".__LINE__."行 sql ", $sql);
  $result =$dbh->query($sql);
  while ($row=$result->fetchRow()){
    $paper_info=explode_ep_id($row['exam_paper_id']);
	  $cc=intval($paper_info[0]);
	  $oi=intval($paper_info[1]);
	  $gr=intval($paper_info[2]);
	  $cl=intval($paper_info[3]);
	  $rl=intval($paper_info[4]);
    $select1[$row['course_id']]=$row['name'];
		$select2[$row['course_id']][$cc]=id2publisher($cc);
		$select3[$row['course_id']][$cc][$oi]=id2subject($oi);
		$select4[$row['course_id']][$cc][$oi][$gr]="20".$gr;
		$select5[$row['course_id']][$cc][$oi][$gr][$cl]="第".$cl."單元";
		$select6[$row['course_id']][$cc][$oi][$gr][$cl][$rl]="卷".$rl;
    $select7[$row['course_id']][$cc][$oi][$gr][$cl][$rl][$row['concept']]=$row['concept'];    
  }
  $form->addElement('header', 'myheader', '<center> '.$database_name.'場地試卷列表</canter>');  //標頭文字
	// Create the Element
	$sel =& $form->addElement('hierselect', 'courseAndEpid', '請選擇場地與試卷：');
	// And add the selection options
	$sel->setOptions(array($select1, $select2, $select3, $select4, $select5,$select6));
	
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file','courseResultExport2Excel');
	$form->addElement('hidden','opt2','courseAndEpidSelected');
  $form->addElement('hidden','databaseName',$database_name);
	$form->addRule('class', '「班級」不可空白！', 'nonzero',  null, 'client', null, null);
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->display();  
}

function courseResultExportExcel($database_name,$ep_id,$concept,$course_id){
  global $dbh;
  //試題參數
  $sql = "select concept_item.item_sn , concept_item.item_num , 
  concept_item_parameter.a , concept_item_parameter.b , concept_item_parameter.c 
  from ".$database_name.".concept_item , ".$database_name.".concept_item_parameter 
  WHERE concept_item.exam_paper_id = '$ep_id' and 
  concept_item.item_sn=concept_item_parameter.item_sn 
  order by concept_item.item_num ";
  $result =$dbh->query($sql);
  $Mcount=0;
  while ($row=$result->fetchRow()){
    $item_data[$row['item_num']]=$row['item_sn'];
    $item_sn=$row['item_sn'];
    $item_num=$row['item_num'];
    $irt_a=$row['a'];
    $irt_b=$row['b'];
    $irt_c=$row['c'];
    //試題參數表=>excel_content
    if($Mcount==0){
      $excel_content[0][]=$database_name."-".$concept."ep_id:".$ep_id;
      $excel_content[1][]="a";
      $excel_content[2][]="b";
      $excel_content[3][]="c";
    }
    $excel_content[0][]=$item_num."[".$item_sn."]";
    $excel_content[1][]=$irt_a;
    $excel_content[2][]=$irt_b;
    $excel_content[3][]=$irt_c;
    $Mcount++;    
  }
  //debug_msg("第".__LINE__."行 excel_content ", $excel_content);
  $objPHPExcel = new PHPExcel();
  $objPHPExcel->setActiveSheetIndex(0);
  $objPHPExcel->getActiveSheet()->fromArray($excel_content, null, 'A1');
  
  //作答資料 order by item_sn
  $sql="SELECT a.user_id , c.uname , 
  a.select_item_id_S as pre_select_item_id_S, 
  a.org_res as pre_org_res, 
  a.binary_res as pre_binary_res,
  b.client_items_idle_time , b.select_item_id_S , b.org_res , b.binary_res , 
  b.exam_res  
  FROM ".$database_name.".pretest_record_irt as a, 
  ".$database_name.".exam_record_irt as b, 
  ".$database_name.".user_info as c 
  WHERE a.user_id=c.user_id and
  a.user_id=b.user_id and 
  a.course_id='$course_id' and 
  a.exam_title='$ep_id' and
  b.course_id='$course_id' and 
  b.exam_title='$ep_id'";
  //debug_msg("第".__LINE__."行 sql ", $sql);
  $result =$dbh->query($sql);
  $Mcount=0;
  while ($row=$result->fetchRow()){
    //debug_msg("第".__LINE__."行 row ", $row);
    $user_id=$row['user_id'];
    $user_name=$row['uname'];
    $pre_select_item_sn=explode(_SPLIT_SYMBOL,$row['pre_select_item_id_S']);
    $pre_org_res=explode(_SPLIT_SYMBOL,$row['pre_org_res']);
    $pre_binary_res=explode(_SPLIT_SYMBOL,$row['pre_binary_res']);
    $select_item_sn=explode(_SPLIT_SYMBOL,$row['select_item_id_S']);
    $org_res=explode(_SPLIT_SYMBOL,$row['org_res']);
    $binary_res=explode(_SPLIT_SYMBOL,$row['binary_res']);
    $client_items_idle_time=explode(_SPLIT_SYMBOL,$row['client_items_idle_time']);
    $exam_res=explode(_SPLIT_SYMBOL,$row['exam_res']);
		if(end($pre_select_item_sn)==""){
			array_pop($pre_select_item_sn);
		}
		if(end($pre_org_res)==""){
			array_pop($pre_org_res);
		}
		if(end($pre_binary_res)==""){
			array_pop($pre_binary_res);
		}
		if(end($select_item_sn)==""){
			array_pop($select_item_sn);
		}
		if(end($org_res)==""){
			array_pop($org_res);
		}
		if(end($binary_res)==""){
			array_pop($binary_res);
		}
		if(end($client_items_idle_time)==""){
			array_pop($client_items_idle_time);
		}
		if(end($exam_res)==""){
			array_pop($exam_res);
		}
    $total_select_item_sn=array_merge($pre_select_item_sn,$select_item_sn);
    $total_org_res=array_merge($pre_org_res,$org_res);
    $total_binary_res=array_merge($pre_binary_res,$binary_res);    
    //將 binar_res 依據 item_num 排序 => excel_courseResultByItemSn_content
    if($Mcount==0){
      $excel_courseResultByItemSn_content[0]=['user_id','姓名'];
      foreach($item_data as $key=>$value){
        $excel_courseResultByItemSn_content[0][]=$key."[".$value."]";        
      }  
    }    
    $excel_courseResultByItemSn_content[$Mcount+1][0]=$user_id;
    $excel_courseResultByItemSn_content[$Mcount+1][1]=$user_name;    
    foreach($item_data as $key=>$value){
      $res_key=array_search($value,$total_select_item_sn);
      if(gettype($res_key)=="integer"){
        $excel_courseResultByItemSn_content[$Mcount+1][]=$total_binary_res[$res_key];
      }else{
        $excel_courseResultByItemSn_content[$Mcount+1][]='';      
      }
    }
    //作答時間表 order by 出題順序 =>excel_itemsTime_content
    if($Mcount==0){
      $excel_itemsTime_content[0]=['course_id','user_id'];
      $ii=count($client_items_idle_time);
      for($i=0;$i<$ii;$i++){
        $excel_itemsTime_content[0][]="item".$i+1;
      }
    }
    $excel_itemsTime_content[$Mcount+1][0]=
    $excel_itemsTime_content[$Mcount+1][1]=$user_id;
    $excel_itemsTime_content[$Mcount+1][]=$client_items_idle_time;
    //被施測試題的id => excel_itemsID_content
  }
}

?>