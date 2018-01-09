<?php
require_once "HTML/QuickForm.php";
require_once 'HTTP/Upload.php';
require_once 'Date.php';
require_once "include/adp_API.php";


$module_name = basename(dirname(__FILE__));

if($user_data->access_level<=70){
	Header("Location: index.php");
}
$width="100%";
echo "<br>";

if($_POST['opt']=="listCS"){
	echo "<br>";
	listCS($_POST['opti']);
}

//-- 顯示主畫面

echo '<br>
<table width="98%" border="1" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="center" bordercolor="#FFCC33">';
moveCS($_REQUEST['opt']); 
echo '</td></tr></table>';


function moveCS($opt){
	global $dbh;
	$form = new HTML_QuickForm('frmTest','post','');

	//-- 尋找目前已建立之單元結構，並初始化"關聯選單"
	/* $select1[0]='版本';
	$select2[0][0]='科目';
	$select3[0][0][0]='冊別';
	$select4[0][0][0][0]='單元【單元名稱】'; */
	//$count=0;
	$sql = "select * from concept_info ORDER BY cs_id";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$myary[]=$row['cs_id'];
		$select1[$row['publisher_id']]=id2publisher($row['publisher_id']); //出版商
		$select2[$row['publisher_id']][$row['subject_id']]=id2subject($row['subject_id']);  //科目名稱
		$select3[$row['publisher_id']][$row['subject_id']][$row['vol']]='20'.$row['vol'].'年度';  //冊別
		$select4[$row['publisher_id']][$row['subject_id']][$row['vol']][$row['unit']]='單元(第'.$row['unit'].'次)【'.$row['concept'].'】'; //單元
		//$select5[$row['publisher_id']][$row['subject_id']][$row['vol']][$row['unit']][$row['concept']]=$row['concept'];
		//$count++;
	}

	//-- 顯示選單
	$form->addElement('header', 'myheader', '<center>☆★☆ 搬移/複製單元結構 ☆★☆</center>');  //標頭文字
	// Create the Element
	$sel =& $form->addElement('hierselect', 'unit', '原單元');
	// And add the selection options
	$sel->setOptions(array($select1, $select2, $select3, $select4));

	//--- 找出現有出版商
	$sql = "select * from publisher ORDER BY publisher_id";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$publish[]=$row[publisher_id];
	}

	//--- 找出所有領域(科目)
	//$sql = "select * from subject WHERE subject_id IN (1,2) ORDER BY subject_id";
	$sql = "select * from subject ORDER BY subject_id";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$subject[]=$row[subject_id];
	}

	/* $ch1[0]='版本';
	$ch2[0][0]='科目';
	$ch3[0][0][0]='冊別';
	$ch4[0][0][0][0]='單元'; */

    $aco=sizeof($publish);
    $bb=sizeof($subject);
	for($a=0;$a<$aco;$a++){
		for($b=0;$b<$bb;$b++){
			for ($i=15;$i<=25;$i++){
				for ($j=1;$j<=15;$j++){
					$all_cs_id=sprintf("%03d%02d%02d%02d",$publish[$a],$subject[$b],$i,$j);  //建立全體單元數
					if (!(in_array($all_cs_id, $myary))) {
						$differ[]=$all_cs_id;  //可以搬移的cs_id
						$cs_ary=explode_cs_id($all_cs_id);
						$ch1[$cs_ary[0]]=id2publisher($cs_ary[0]); //對應之出版商
						$ch2[$cs_ary[0]][$cs_ary[1]]=id2subject($cs_ary[1]);  //對應之科目名稱
						$ch3[$cs_ary[0]][$cs_ary[1]][$cs_ary[2]]="20".$cs_ary[2]."年度";  
						$ch4[$cs_ary[0]][$cs_ary[1]][$cs_ary[2]][$cs_ary[3]]="第".$cs_ary[3]."單元";  
					}
				}
			}
		}
	}
	
	$aa['copy']="複製";
	//$aa['move']="搬移";

	$form->addElement('header', 'myheader2', '');  //標頭文字
	$form->addElement('select', 'opti', '功能', $aa);
	$form->addElement('header', 'myheader1', '');  //標頭文字
	$sel2 =& $form->addElement('hierselect', 'new_cs', '新單元');
	$sel2->setOptions(array($ch1, $ch2, $ch3, $ch4));
	$form->addElement('text', 'newunitname', '新單元名稱');
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name','ConceptStructure');
	$form->addElement('hidden','file','moveCS_BN');
	$form->addElement('hidden','opt','listCS');
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->addRule('unit', '請選擇原單元', 'nonzero',null, 'client', null, null);
	$form->addRule('new_cs', '請選擇正確的新單元', 'nonzero',null, 'client', null, null);
	$form->addRule('newunitname', '新的單元名稱不可空白', 'required',null, 'client', null, null);
	$form->display();
	
}

//單元複製與搬移
function listCS($opti){
	global $dbh;
	OpenTable();
	$cs_id=sprintf("%03d%02d%02d%02d",$_POST['unit'][0],$_POST['unit'][1],$_POST['unit'][2],$_POST['unit'][3]);
	$new_cs_id=sprintf("%03d%02d%02d%02d",$_POST['new_cs'][0],$_POST['new_cs'][1],$_POST['new_cs'][2],$_POST['new_cs'][3]);
	//echo "<pre>";
	//print_r($_POST);

//檢查新單元是否已經存在
    $sql = "select * from concept_info where cs_id = '$new_cs_id'";
	$result = $dbh->query($sql);
	while ($data = $result->fetchRow()) {
        echo "<br>錯誤！該單元已經存在！請洽系統管理者！<br>";
        $org_name=getChtExamTitle($cs_id, 0);
        $new_name=getChtExamTitle($new_cs_id, 0);
        echo "舊單元：$org_name<br>";
        echo "新單元：$new_name<br>";
        die();
	}

	 
	$sql = "select * from concept_info where cs_id = '$cs_id'";
	$result = $dbh->query($sql);    
	while ($data = $result->fetchRow()) {
		$org_pid=$data['publisher_id'];
		$org_sid=$data['subject_id'];
		$org_vid=$data['vol'];
		$org_uid=$data['unit'];
		$org_grade=$data['grade'];
		$cs_name=$data['concept'];

  	 
		//--變動資料庫
		if($opti=="copy"){   //複製
			//echo "<pre>";
			//print_r($data);
			$table_name = 'concept_info';
			$data['cs_sn'] = '';
			$data['publisher_id'] = $_POST['new_cs'][0];
			$data['subject_id'] = $_POST['new_cs'][1];
			$data['vol'] = $_POST['new_cs'][2];
			$data['unit'] = $_POST['new_cs'][3];
			$data['concept'] = $_POST['newunitname'];
			$data['cs_id'] = $new_cs_id;
			$query = 'INSERT INTO '.$table_name.' (cs_sn, cs_id, publisher_id, subject_id, vol, unit, grade, concept, matrix_map, remedy_file, item_remedy_file, percent_map,  percent_gif, structure_gif, indicator_relation, indicator_item, indicator_threshold, indicator_item_nums, indicator_item_relation, remedy_instruction, book_ref ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';

			$sth = $dbh->prepare($query);
			$result1 = $dbh->execute($sth, $data);
			//echo "<pre>";
			//print_r($data);
		}elseif($opti=="move"){   //-- 更新資料庫
			$table_name   = 'concept_info';
			$table_values = array(
				'publisher_id' => $_POST['new_cs'][0],
				'subject_id' => $_POST['new_cs'][1],
				'vol' => $_POST['new_cs'][2],
				'unit' => $_POST['new_cs'][3],
				'concept' => $_POST['newunitname'],
				'cs_id' => $new_cs_id
			);
			$table_field='cs_id ='.$cs_id;
			$result1 = $dbh->autoExecute($table_name, $table_values,
                        DB_AUTOQUERY_UPDATE, $table_field);
		}
		if($result1=="1"){
			echo "更新 ".$table_name." 成功！<br>";
		}
	}
	
	unset($data);
	unset($table_values);
	
	//007 複製題本 concept_info_dim 
	$sql = "select * from concept_info_dim where cs_id = '$cs_id'";
	$result = $dbh->query($sql);    
	while ($data = $result->fetchRow()) {

		$table_name   = 'concept_info_dim';
		if($opti=="copy"){   //複製
			//echo "<pre>";
			//print_r($data);
			//$data['item_sn'] = ''; 
			$data['cs_id'] = $new_cs_id;
			//$data['exam_paper_id'] = $new_cs_id.sprintf("%02d",$data['paper_vol']);
			$query = 'INSERT INTO '.$table_name.' ( cs_id, dim, dim_name, sub, sub_score_name, dim_detail, sigma_det ) VALUES (?,?,?,?,?,?,?)';
			
			$sth = $dbh->prepare($query);
			$result6 = $dbh->execute($sth, $data);
		}elseif($opti=="move"){   //-- 更新資料庫
			/* $table_values = array(
				'cs_id' => $new_cs_id
			);
			$table_field='cs_id ='.$cs_id;
			$result6 = $dbh->autoExecute($table_name, $table_values,
                        DB_AUTOQUERY_UPDATE, $table_field); */
		}
	}
	
	if($result6=="1"){
		echo "更新 ".$table_name." 成功！<br>";
	}
	unset($data);
	unset($table_values);
	
	//007 複製題本 concept_info_dim end

	
	//concept_info_plus 再下方 007
	
	$sql = "SELECT max(item_sn) FROM concept_item where cs_id = '$cs_id'";
	$result = $dbh->query($sql);
	$item_sn = $result->fetchRow();
	print_r($item_sn);
	$sql = "select * from concept_item where cs_id = '$cs_id'";
	$result = $dbh->query($sql);    
	while ($data = $result->fetchRow()) {

		$table_name   = 'concept_item';
		if($opti=="copy"){   //複製
			//echo "<pre>";
			//print_r($data);
			$data['item_sn'] = ''; 
			$data['cs_id'] = $new_cs_id;
			$data['exam_paper_id'] = $new_cs_id.sprintf("%02d",$data['paper_vol']);
			
			$query = 'INSERT INTO '.$table_name.' ( item_sn, exam_paper_id, item_num, cs_id, item_content, item_filename, op_content, op_filename, op_ans, points, paper_vol, edu_parameter, edu_Level1, edu_Level2, edu_Level3 ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
			
			$sth = $dbh->prepare($query);
			$result2 = $dbh->execute($sth, $data);
		}elseif($opti=="move"){   //-- 更新資料庫
			/* $table_values = array(
				'exam_paper_id' => $new_cs_id.sprintf("%02d",$data['paper_vol']),
				'cs_id' => $new_cs_id
			);
			$table_field='cs_id ='.$cs_id;
			$result2 = $dbh->autoExecute($table_name, $table_values,
                        DB_AUTOQUERY_UPDATE, $table_field); */
		}
	}
	
	if($result2=="1"){
		echo "更新 ".$table_name." 成功！<br>";
	}
	unset($data);
	unset($table_values);
	
	//007 複製題本 concept_item_parameter 
		
	$sql = "select * from concept_item_parameter where cs_id = '$cs_id'";
	$result = $dbh->query($sql);    
	while ($data = $result->fetchRow()) {

		$table_name   = 'concept_item_parameter';
		if($opti=="copy"){   //複製
			//echo "<pre>";
			//print_r($data);
			$item = $item_sn['max(item_sn)']++;
			$data['item_sn'] = $item; 
			$data['cs_id'] = $new_cs_id;
			$data['tmp_N']= 0;
			$data['S_N'] = ''; 
			$data['A_N'] = ''; 
			$data['EP'] = ''; 
			$query = 'INSERT INTO '.$table_name.' ( item_sn, cs_id, a, b, c, tmp_N, S_N, A_N, EP, dim, sub ) VALUES (?,?,?,?,?,?,?,?,?,?,?)';
			$sth = $dbh->prepare($query);
			//echo print_r($data)."<br/>";
			$result7 = $dbh->execute($sth, $data);
		}elseif($opti=="move"){   //-- 更新資料庫
			/* $table_values = array(
				'cs_id' => $new_cs_id
			);
			$table_field='cs_id ='.$cs_id;
			$result7 = $dbh->autoExecute($table_name, $table_values,
                        DB_AUTOQUERY_UPDATE, $table_field); */
		}
	}
	
	if($result7=="1"){
		echo "更新 ".$table_name." 成功！<br>";
	}
	unset($data);
	unset($table_values);
	//007 複製題本 concept_item_parameter end
	
	
	//007 複製題本 concept_item_testlet
	
	$sql = "select * from concept_item_testlet where exam_paper_id LIKE '%$cs_id%'";  //ing
	$result = $dbh->query($sql);    
	while ($data = $result->fetchRow()) {

		$table_name   = 'concept_item_testlet';
		if($opti=="copy"){   //複製
			//echo "<pre>";
			//print_r($data);
			$data['testlet_sn'] = ''; 
			$exam_paper_id = str_replace ($cs_id,$new_cs_id,$data['exam_paper_id']);
			//echo $data['exam_paper_id']."<br/>".$exam_paper_id."<br/>";
			$data['exam_paper_id'] = $exam_paper_id;
			$query = 'INSERT INTO '.$table_name.' ( testlet_sn, exam_paper_id, testlet_num, testlet_sub_num, testlet_name, item_sn ) VALUES (?,?,?,?,?,?)';
			
			$sth = $dbh->prepare($query);
			$result7 = $dbh->execute($sth, $data);
		}elseif($opti=="move"){   //-- 更新資料庫
			/* echo "暫時沒有搬移功能";
			$table_values = array(
				'exam_paper_id' => str_replace ($cs_id,$new_cs_id,$data['exam_paper_id'])
			);
			$table_field='exam_paper_id ='.$cs_id.sprintf("%02d",$data['paper_vol']);
			$result7 = $dbh->autoExecute($table_name, $table_values,
                        DB_AUTOQUERY_UPDATE, $table_field); */
		}
	}
	
	if($result7=="1"){
		echo "更新 ".$table_name." 成功！<br>";
	}
	unset($data);
	unset($table_values);
	
	//007 複製題本 concept_item_testlet end 
	
	
	$table_name   = 'exam_paper';
	$sql = "select * from exam_paper where cs_id = '$cs_id'";
	$result = $dbh->query($sql);    
	while ($data = $result->fetchRow()) {
		if($opti=="copy"){   //複製
			//echo "<pre>";
			//print_r($data);
			$data['sn']='';
			$data['cs_id'] = $new_cs_id;
			$query = 'INSERT INTO '.$table_name.' (sn, cs_id, paper_vol, item_nums, ready) VALUES (?,?,?,?,?)';
			$sth = $dbh->prepare($query);
			$result3 = $dbh->execute($sth, $data);
		}elseif($opti=="move"){   //-- 更新資料庫
			/* $table_values = array(
				'cs_id' => $new_cs_id
			);
			$table_field='cs_id ='.$cs_id;
			$result3 = $dbh->autoExecute($table_name, $table_values,
                        DB_AUTOQUERY_UPDATE, $table_field); */
		}
	}
	if($result3=="1"){
		echo "更新 ".$table_name." 成功！<br>";
	}
	unset($data);
	unset($table_values);

	//$table_name   = 'exam_paper_access';
	$table_name   = 'exam_paper_access_irt';
	$sql = "select * from exam_paper_access_irt where cs_id = '$cs_id'";
	$result = $dbh->query($sql);    
	while ($data = $result->fetchRow()) {
		if($opti=="copy"){   //複製
			$show_me= "試卷存取不用複製資料庫！";
		}elseif($opti=="move"){   //-- 更新資料庫
			/* $table_values = array(
				'exam_paper_id' => $new_cs_id.sprintf("%02d",$data['paper_vol']),
				'cs_id' => $new_cs_id
			);
			$table_field='cs_id ='.$cs_id;
			$result4 = $dbh->autoExecute($table_name, $table_values,
                        DB_AUTOQUERY_UPDATE, $table_field); */
		}
	}
	if($result4=="1"){
		echo "更新 ".$table_name." 成功！<br>";
	}
	unset($data);
	unset($table_values);
	
	//007 複製題本 exam_paper_subscale 
	
	$sql = "select * from exam_paper_subscale where exam_paper_id LIKE '%$cs_id%'";  //ing
	$result = $dbh->query($sql);    
	while ($data = $result->fetchRow()) {

		$table_name   = 'exam_paper_subscale';
		if($opti=="copy"){   //複製
			//echo "<pre>";
			//print_r($data);
			$data['subscale_sn'] = ''; 
			$exam_paper_id = str_replace ($cs_id,$new_cs_id,$data['exam_paper_id']);
			//echo $data['exam_paper_id']."<br/>".$exam_paper_id."<br/>";
			$data['exam_paper_id'] = $exam_paper_id;
			$query = 'INSERT INTO '.$table_name.' ( subscale_sn, exam_paper_id, sub_test_num ) VALUES (?,?,?)';
			$sth = $dbh->prepare($query);
			$result8 = $dbh->execute($sth, $data);
		}elseif($opti=="move"){   //-- 更新資料庫
			/* echo "暫時沒有搬移功能";
			$table_values = array(
				'exam_paper_id' => str_replace ($cs_id,$new_cs_id,$data['exam_paper_id'])
			);
			$table_field='exam_paper_id ='.$cs_id.str_replace ($cs_id,$new_cs_id,$data['exam_paper_id']);
			$result8 = $dbh->autoExecute($table_name, $table_values,
                        DB_AUTOQUERY_UPDATE, $table_field); */
		}
	}
	
	if($result8=="1"){
		echo "更新 ".$table_name." 成功！<br>";
	}
	unset($data);
	unset($table_values);

	//007 複製題本 exam_paper_subscale end 
	
	//007 複製題本 exam_people 
	
	/* $sql = "select * from exam_people where cs_id = '$cs_id'";  
	$result = $dbh->query($sql);    
	while ($data = $result->fetchRow()) {

		$table_name   = 'exam_people';
		if($opti=="copy"){   //複製
			//echo "<pre>";
			//print_r($data);
			$data['cs_id'] = $new_cs_id; 
			
			$query = 'INSERT INTO '.$table_name.' ( cs_id, paper_vol, N, level_num ) VALUES (?,?,?,?)';
			
			$sth = $dbh->prepare($query);
			$result9 = $dbh->execute($sth, $data);
		}elseif($opti=="move"){   //-- 更新資料庫
			 echo "暫時沒有搬移功能";
			$table_values = array(
				'exam_paper_id' => str_replace ($cs_id,$new_cs_id,$data['exam_paper_id'])
			);
			$table_field='exam_paper_id ='.$cs_id.sprintf("%02d",$data['paper_vol']);
			$result9 = $dbh->autoExecute($table_name, $table_values,
                        DB_AUTOQUERY_UPDATE, $table_field); 
		}
	}
	
	if($result9=="1"){
		echo "更新 ".$table_name." 成功！<br>";
	}
	unset($data);
	unset($table_values); */
	
	//007 複製題本 exam_people end 

	
	$table_name   = 'concept_info_plus';
	$sql = "select * from $table_name where cs_id = '$cs_id'";
	$result = $dbh->query($sql);
	//debug_msg("第".__LINE__."行 sql ", $sql);
	while ($data = $result->fetchRow()) {
		if($opti=="copy"){   //複製
			$data['cs_id'] = $new_cs_id;
			//debug_msg("第".__LINE__."行 data ", $data);
			$query = 'INSERT INTO '.$table_name.' ( cs_id, matrix_map_csv, expert_map_csv, item_sequence_2, item_sequence_1, item_sequence_3, ready, stu_stru_ready, expert_stru_ready, min_test_nums_stu, min_test_nums_expert, max_item_nums) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)';
			$sth = $dbh->prepare($query);
			$result5 = $dbh->execute($sth, $data);
			//debug_msg("第".__LINE__."行 result5 ", $result5);
			//die();
		}elseif($opti=="move"){   //-- 更新資料庫
			/* $table_values = array(
				'cs_id' => $new_cs_id
			);
			$table_field='cs_id ='.$cs_id;
			$result5 = $dbh->autoExecute($table_name, $table_values,
                        DB_AUTOQUERY_UPDATE, $table_field); */
		}
	}
	
	if($result5=="1"){
		echo "更新 ".$table_name." 成功！<br>";
	}
	unset($data);
	unset($table_values);


	//檔案搬移
	//--  遞迴建立上傳檔案目錄
	$mydir=_ADP_CS_UPLOAD_PATH;  //預設上傳結構概念矩陣檔之目錄
	for($i=0;$i<count($_POST['new_cs']);$i++){
		$mydir.=$_POST['new_cs'][$i]."/";
		//echo "$mydir <br>";
		if (!is_dir($mydir))
			mkdir($mydir, 0755);
	}
	$org_dir=_ADP_CS_UPLOAD_PATH;
	for($i=0;$i<count($_POST['unit']);$i++){
		$org_dir.=$_POST['unit'][$i]."/";
	}

	//找出所有目錄下的檔案
   $i=0;
   $list_ignore = array ('.','..');
   $handle=opendir($org_dir);
   
   while ($file = readdir($handle)) {
   	if (is_file($org_dir.$file) && !in_array($file, $list_ignore) ) {
         $file_info[]=$file;
   		$i++;
   	}
   }
   closedir($handle);
   //debug_msg("第".__LINE__."行 file_info ", $file_info);
   //die();
   
    $ii=count($file_info);
    $j=0;
	for($i=0;$i<$ii;$i++){
		if($file_info[$i]!=""){
			$file = $org_dir.$file_info[$i];
			$newfile = $mydir.$file_info[$i];

			if (!copy($file, $newfile)) {
				echo "第".__LINE__."行， failed to copy $file...<br>\n";
			}else{
                $j++;
				if($opti=="move"){
					unlink($file);
				}
			}
		}
	}

	echo "============成功複製 $j 個檔案============<br>";
    unset($file_info);

	CloseTable();
}

		/*
		$file_info[]=$data['matrix_map'];
		$file_info[]=$data['remedy_file'];
		$file_info[]=$data['item_remedy_file'];
		$file_info[]=$data['percent_map'];
		$file_info[]=$data['percent_gif'];
		$file_info[]=$data['structure_gif'];
		$file_info[]=$data['indicator_relation'];
		$file_info[]=$data['indicator_item'];
		$file_info[]=$data['indicator_threshold'];
		$file_info[]=$data['indicator_item_nums'];
		$file_info[]=$data['indicator_item_relation'];

		$op_filename1=$data['remedy_instruction'];
		$op_pieces1 = explode(_SPLIT_SYMBOL, $op_filename1);   //題目選項圖片檔名的陣列
		for($i=1;$i<=count($op_pieces1)-1;$i++){
			$file_info[]=$op_pieces1[($i-1)];
		}
		$file_info[]=$data['book_ref'];
  	  */
	/*
		$file_info[]=$data['item_filename'];
		$op_filename=$data['op_filename'];
		$op_pieces = explode(_SPLIT_SYMBOL, $op_filename);   //題目選項圖片檔名的陣列
		for($i=1;$i<=count($op_pieces)-1;$i++){
			$file_info[]=$op_pieces[($i-1)];
		}
   */


?>

