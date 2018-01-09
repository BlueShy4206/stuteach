<?php
require_once "HTML/QuickForm.php";
require_once 'HTTP/Upload.php';
require_once "include/adp_API.php";

if(!Auth::staticCheckAuth($options)){  //检查登入状况
	Header("Location: index.php");
	die();
}

if($user_data->access_level<=70){
	Header("Location: index.php");
}
IMPORT_CREATITEM_table_header();
$OpenedCS=getCSlicense($user_data->user_id);

//-- 顯示主畫面上方子選單
//CS_ITEM_table_header();

?>
<br>
<table width="95%" border="1" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="center" bordercolor="#FFCC33"><?php creatITEM($_REQUEST['opt'],$_REQUEST['p'],$_REQUEST['s']); ?></td>
  </tr>
</table>
<?php
function creatITEM($opt, $pid=0,$sid=0){
	global $dbh, $OpenedCS;
	$form = new HTML_QuickForm('frmTest','post','');
	$cs_id=sprintf("%03d%02d%02d%02d",$_REQUEST['unit_item'][0],$_REQUEST['unit_item'][1],$_REQUEST['unit_item'][2],$_REQUEST['unit_item'][3]); 
	$paper_vol=$_REQUEST['unit_item'][4];
	$exam_paper_id=$cs_id.sprintf("%02d",$paper_vol);
	$item_num=$_REQUEST['unit_item'][5];
	$TemplateID=$_REQUEST[template_id];

   //--  檢查是否為檔案上傳狀態，並回報
	if ($form->validate() && $_REQUEST['opt']=='creat_item') {
      //debug_msg("第".__LINE__."行 _REQUEST ", $_REQUEST);

		$upload = new HTTP_Upload();
		if (PEAR::isError($upload)) die ($upload->getMessage());  //顯示錯誤訊息
		$files = $upload->getFiles();
		if (PEAR::isError($files)) die ($files->getMessage());  //顯示錯誤訊息
		for($i=1;$i<=count($_SESSION['EduParaSN']);$i++){
         $sn="";
         if($_POST['EduPara'.$i]==1){
            $sn=$_SESSION['EduParaSN'][$i];
         }
         $EduParaSN.=$sn._SPLIT_SYMBOL;
      }

		
		//檔案上傳--GO    
    $i=0;
    foreach ($files as $file) {
			if ($file->isValid()) {
				$mydir=_ADP_CS_UPLOAD_PATH.$_REQUEST['unit_item'][0]."/".$_REQUEST['unit_item'][1]."/".$_REQUEST['unit_item'][2]."/".$_REQUEST['unit_item'][3]."/";  //預設上傳試題之目錄
				//$realfilename=$exam_paper_id."_".$item_num."_".$key.".gif";
                
				$file->setName('uniq');
				
                $file_name = strtolower($file->moveTo($mydir));
				
                if (PEAR::isError($file_name)) {
					die ('發生錯誤：'.$file_name->getMessage());  //顯示錯誤訊息
				}
			}
         /*
         elseif ($file->isMissing()) {
				die ('You must upload one file');
			}
         */
			if(isset($prop))  unset($prop);
				$prop = $file->getProp();   //取得上傳檔案之最後資訊
				$this_upload_file[]=$prop['name'];
                
				//題組一
                if($i==0){
					$item_filename1=strtolower($prop['name']);    //取得題目之題幹檔名
				}elseif($i>=1 AND $i<=10){
					$op_filename1.=strtolower($prop['name'])._SPLIT_SYMBOL;   //取得題目之選項檔名
				}
				//題組二
				if($i==11){
					$item_filename2=strtolower($prop['name']);    //取得題目之題幹檔名
				}elseif($i>=12 AND $i<=21){
					$op_filename2.=strtolower($prop['name'])._SPLIT_SYMBOL;   //取得題目之選項檔名
				}
				//題組三
				if($i==22){
					$item_filename3=strtolower($prop['name']);    //取得題目之題幹檔名
				}elseif($i>=23 AND $i<=32){
					$op_filename3.=strtolower($prop['name'])._SPLIT_SYMBOL;   //取得題目之選項檔名
				}				
				//題組四
				if($i==33){
					$item_filename4=strtolower($prop['name']);    //取得題目之題幹檔名
				}elseif($i>=34 AND $i<=43){
					$op_filename4.=strtolower($prop['name'])._SPLIT_SYMBOL;   //取得題目之選項檔名
				}					
				//題組五
				if($i==44){
					$item_filename5=strtolower($prop['name']);    //取得題目之題幹檔名
				}elseif($i>=45 AND $i<=64){
					$op_filename5.=strtolower($prop['name'])._SPLIT_SYMBOL;   //取得題目之選項檔名
				}					
			//debug_msg("第".__LINE__."行 prop ", $prop);
			//debug_msg("第".__LINE__."行 op_filename ", $op_filename);
			//debug_msg("第".__LINE__."行 op_content ", $op_content);
				$i++;
			}
			
      //檔案上傳--END
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

			$temp = "0@XX@0@XX@0@XX@0@XX@0@XX@0@XX@0@XX@0@XX@0@XX@0@XX@0@XX@0@XX@0@XX@0@XX@0@XX@0@XX@0@XX@";
			$temp1= "1@XX@1@XX@1@XX@1@XX@1@XX@1@XX@1@XX@1@XX@1@XX@1@XX@1@XX@1@XX@1@XX@1@XX@1@XX@1@XX@1@XX@";
			
			//--寫入資料庫的迴圈--GO
			$looptimes = 0;
      foreach( $_REQUEST['myans'] as $myans_value ){
        if( $_REQUEST['parameter_a'][$looptimes]!=null ){
            
  			//die();
        $data2['item_num_array'][] = ($item_num+$looptimes);
        	
        //die();
        //-- 寫入資料庫
  			if($op_content==null){$op_content=0;}
  			if( $looptimes == 0 ){$item_filename = $item_filename1; $op_filename=$op_filename1;}
  			if( $looptimes == 1 ){$item_filename = $item_filename2; $op_filename=$op_filename2;}
  			if( $looptimes == 2 ){$item_filename = $item_filename3; $op_filename=$op_filename3;}
  			if( $looptimes == 3 ){$item_filename = $item_filename4; $op_filename=$op_filename4;}
  			if( $looptimes == 4 ){$item_filename = $item_filename5; $op_filename=$op_filename5;}
  			if( $looptimes == 5 ){$item_filename = $item_filename6; $op_filename=$op_filename6;}
            $sql = 'INSERT INTO concept_item (exam_paper_id, item_num, cs_id, item_filename, op_filename, op_ans, points, paper_vol, op_content) VALUES (?,?,?,?,?,?,?,?,?)';
  			$data = array($exam_paper_id, ($item_num+$looptimes), $cs_id, $item_filename, $op_filename, $_REQUEST['myans'][$looptimes], $_REQUEST['mypoints'][$looptimes], $paper_vol, $op_content);
  			
  			$result =$dbh->query($sql, $data);
    		if (PEAR::isError($result)) {
    			echo __LINE__.'<br>';
                echo $result->getDebugInfo();
                die($result->getMessage());
    		}  
        	//debug_msg("第".__LINE__."行 _REQUEST ", $data['item_num_array']);		
  			$sql= 'select item_sn from concept_item where cs_id = "'.$cs_id.'" and item_num = "'.($item_num+$looptimes).'" ';
  			$result = $dbh->query($sql);
  			while ($data = $result->fetchRow()) {
  				$item_snn=$data['item_sn'];
  			}
  			$_REQUEST['parameter_dim']=1;
  			$query = 'INSERT INTO concept_item_parameter (item_sn, cs_id, a, b, c, S_N, A_N, EP, dim, sub) VALUES (?,?,?,?,?,?,?,?,?,?)';
  			$data = array($item_snn, $cs_id, $_REQUEST['parameter_a'][$looptimes], $_REQUEST['parameter_b'][$looptimes], $_REQUEST['parameter_c'][$looptimes], $temp, $temp ,$temp1, $_REQUEST['parameter_dim'], $_REQUEST['parameter_sub'][$looptimes]);
            //$data = array($item_snn, $cs_id, $_REQUEST['parameter_a'][$looptimes], $_REQUEST['parameter_b'][$looptimes], $_REQUEST['parameter_c'][$looptimes], $temp, $temp ,$temp1, $_REQUEST['parameter_dim'][$looptimes], $_REQUEST['parameter_sub'][$looptimes]);
  			//debug_msg("第".__LINE__."行 data ", $data);
  			//die();
  			$result =$dbh->query($query, $data);
			  //debug_msg("第".__LINE__."行 result ", $result);
    		if (PEAR::isError($result)) {
    			echo __LINE__.'<br>';
                echo $result->getDebugInfo();
                die($result->getMessage());
    		} 
    		
  			$query = 'INSERT INTO concept_item_testlet (exam_paper_id, testlet_num, testlet_sub_num, item_sn ) VALUES (?,?,?,?) ';
  			$data = array($exam_paper_id, $_REQUEST['unit_item'][6], ($looptimes+1), $item_snn );
  			$result =$dbh->query($query, $data);
  			//debug_msg("第".__LINE__."行 result ", $result);
        	$looptimes++;
        //debug_msg("第".__LINE__."行 _REQUEST ", $data['item_num_array']);
        }   
      }
      //--寫入資料庫的迴圈--END
		
			
		if (PEAR::isError($result)) {
			echo __LINE__.'<br>';
            echo $result->getDebugInfo();
            die($result->getMessage());
		}else{
		      
			foreach( $data2['item_num_array'] as $value ){
                echo "<font class=\"title\"><b>第".$value."題新增成功！</b></font><br>";
            }
      
			
      /*
      //-- 顯示試題
			$sql = "select * from concept_info where cs_id = '$cs_id'";   
			$result = $dbh->query($sql);
			while ($data = $result->fetchRow()) {
				$my_publisher_id=$data['publisher_id'];
				$my_subject_id=$data['subject_id'];
				$my_vol=$data['vol'];
				$my_unit=$data['unit'];
			}
			$pic_path=_ADP_EXAM_DB_PATH.$my_publisher_id."/".$my_subject_id."/".$my_vol."/".$my_unit."/";
			$item_pic=$pic_path.$item_filename;
			$op_pieces = explode(_SPLIT_SYMBOL, $op_filename);   //題目選項圖片檔名的陣列
			$sol_pieces = explode(_SPLIT_SYMBOL, $op_content);   //答案詳解選項圖片檔名的陣列
			for($i=0;$i<count($op_pieces)-1;$i++){
				$item_op_pic[$i]=$pic_path.$op_pieces[$i];
			}
			for($i=0;$i<count($sol_pieces)-1;$i++){
				$sol_op_pic[$i]=$pic_path.$sol_pieces[$i];
			}
			echo "<table width=\"680\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
			echo "<tr><td colspan=\"2\"><img border=\"0\" src=\"".$item_pic."\"</td></tr>";
			for($i=0;$i<count($item_op_pic);$i++){
				echo "<tr><td width=\"100\" align=\"right\">(".($i+1).")</td><td width=\"580\" align=\"left\"><img border=\"0\" src=\"".$item_op_pic[$i]."\"></td></tr>\n";
			}
			for($i=0;$i<count($sol_op_pic);$i++){
			   if($i==0){
               echo '<tr><td width="100"></td><td align="center"><hr width=400></td></tr>';
            }
            echo "<tr><td width=\"100\" align=\"right\">選項(".($i+1).")說明</td><td width=\"580\" align=\"left\">";
            if($sol_pieces[$i]==""){
               echo "：無";
            }else{
               echo "<img border=\"0\" src=\"".$sol_op_pic[$i]."\">";
            }
            echo "</td></tr>\n";
         }
         
			echo "</table><hr>";
		  */
    }
	}
	
  //-- "上傳試題"選項由此開始
	if(isset($_SESSION['pass_new_item'])){   //新增試卷後，直接新增試題
		//debug_msg("第".__LINE__."行 pass_new_item ", $_SESSION['pass_new_item']);
		for($i=0;$i<sizeof($_SESSION['pass_new_item']);$i++){
			$a[($i+1)]=$_SESSION['pass_new_item'][$i];
		}
		//debug_msg("第".__LINE__."行 a[] ", $a);
		$csid=get_csid($a[1], $a[2], $a[3], $a[4]);
		$cs_name=id2csname($csid);
		$select1[$a[1]]=id2publisher($a[1]);
		$select2[$a[1]][$a[2]]=id2subject($a[2]);
		$select3[$a[1]][$a[2]][$a[3]]='第'.$a[3].'冊';
		$select4[$a[1]][$a[2]][$a[3]][$a[4]]='第'.$a[4].'單元【'.$cs_name.'】';
		$select5[$a[1]][$a[2]][$a[3]][$a[4]][$a[5]]="卷".$a[5];
		$select6[$a[1]][$a[2]][$a[3]][$a[4]][$a[5]][1]='第1題';
		unset($_SESSION['pass_new_item']);
	}else{
		//-- 尋找目前已建立之單元結構，並初始化"關聯選單"
		$select1[0]='版本';
		$select2[0][0]='科目';
		$select3[0][0][0]='冊別';
		$select4[0][0][0][0]='單元【單元名稱】';
		$select5[0][0][0][0][0]='卷別';
		$select6[0][0][0][0][0][0]='題號';
		$select7[0][0][0][0][0][0][0]='題組';

		//$sql = "select * from concept_info ORDER BY cs_id";
		$sql = "select * from concept_info, concept_info_plus WHERE concept_info.cs_id=concept_info_plus.cs_id AND concept_info_plus.ready='0' ORDER BY concept_info.cs_id";
		$result =$dbh->query($sql);
		while ($row=$result->fetchRow()){
         $q_cs_ida=substr($row['cs_id'], 0, 5);
         //檢查是否具有該單元的操作權限
         if(in_array( $q_cs_ida, $OpenedCS)==TRUE){
			   $myary[]=$row['cs_id'];
			   $select1[$row['publisher_id']]=id2publisher($row['publisher_id']);
			   $select2[$row['publisher_id']][$row['subject_id']]=id2subject($row['subject_id']);
			   $select3[$row['publisher_id']][$row['subject_id']][$row['vol']]='第'.$row['vol'].'冊';
			   $select4[$row['publisher_id']][$row['subject_id']][$row['vol']][$row['unit']]='第'.$row['unit'].'單元【'.$row['concept'].'】';
			//$select5[$row['publisher_id']][$row['subject_id']][$row['vol']][$row['unit']][$row['concept']]=$row['concept'];
			   
         $sql2="select paper_vol from exam_paper where cs_id='".$row['cs_id']."' order by paper_vol";
			   $result2 =$dbh->query($sql2);
			   while ($row2=$result2->fetchRow()){
				  $select5[$row['publisher_id']][$row['subject_id']][$row['vol']][$row['unit']][$row2['paper_vol']]="卷".$row2['paper_vol'];

				  $sql3="select item_num from concept_item where cs_id='".$row['cs_id']."' and paper_vol='".$row2['paper_vol']."'";
				  $result3 =$dbh->query($sql3);
				  $i=1;
				  while ($row3=$result3->fetchRow()){
					 $tmp_item[$i]=$row3['item_num'];
					 $i++;
				  }

		          $sql4='SELECT max(testlet_num) FROM concept_item_testlet WHERE exam_paper_id="'.$row['cs_id'].sprintf("%02d",$row2['paper_vol']).'" ';
		          //debug_msg("第".__LINE__."行 sql4 ", $sql4);
		          $result4 = $dbh->getOne($sql4);
		          //debug_msg("第".__LINE__."行 result4 ", $result4);
				  
				  if (sizeof($tmp_item)==0){   //試卷中沒有任何題目，所以新題號為1
					 $select6[$row['publisher_id']][$row['subject_id']][$row['vol']][$row['unit']][$row2['paper_vol']][1]="第1題";
					 $select7[$row['publisher_id']][$row['subject_id']][$row['vol']][$row['unit']][$row2['paper_vol']][1][1]='題組1';
				  }elseif(sizeof($tmp_item)!=0 && sizeof($tmp_item)==max($tmp_item)){   //連續題號，新題號為"最大題號+1"
           $select6[$row['publisher_id']][$row['subject_id']][$row['vol']][$row['unit']][$row2['paper_vol']][max($tmp_item)+1]="第".(max($tmp_item)+1)."題";
					 $select7[$row['publisher_id']][$row['subject_id']][$row['vol']][$row['unit']][$row2['paper_vol']][max($tmp_item)+1][($result4+1)]='題組'.($result4+1);
				  }else{
					 for($i=1;$i<=max($tmp_item)+1;$i++){
						   $key=array_search($i, $tmp_item);  //尋找被刪除之題號，如果$key=''，表示已被刪除
						   if($key==''){
							  $select6[$row['publisher_id']][$row['subject_id']][$row['vol']][$row['unit']][$row2['paper_vol']][$i]="第".$i."題";
                $select7[$row['publisher_id']][$row['subject_id']][$row['vol']][$row['unit']][$row2['paper_vol']][max($tmp_item)+1][($result4+1)]='題組'.($result4+1);
						   }
					 }
				  }
				  unset($tmp_item);
			   }
			}
		}
	}

  /*
  //新增能力指標部分
  $sql_edu1 = 'SELECT * FROM concept_item_edu_Level1 ORDER BY edu_1_id ';
  $result_edu1 = $dbh->getALL($sql_edu1);
  foreach( $result_edu1 as $edu1_value ){
    $data['edu1'][$edu1_value['edu_1_id'] ]=$edu1_value['edu_1_name'];
  } 
  $sql_edu2 = 'SELECT * FROM concept_item_edu_Level2 ORDER BY edu_2_id ';
  $result_edu2 = $dbh->getALL($sql_edu2);
  foreach( $result_edu2 as $edu2_value ){
    $data['edu2'][$edu2_value['edu_2_id'] ]=$edu2_value['edu_2_name'];
  }
  $sql_edu3 = 'SELECT * FROM concept_item_edu_Level3 ORDER BY edu_3_id ';
  $result_edu3 = $dbh->getAll($sql_edu3);
  foreach( $result_edu3 as $edu3_value ){
    $edu_id_array = explode('_', $edu3_value['edu_3_id']);
    $select7[ $edu_id_array[0] ] = $data['edu1'][ $edu_id_array[0] ];
    $select8[ $edu_id_array[0] ][ $edu_id_array[1] ] = $data['edu2'][ $edu_id_array[0].'_'.$edu_id_array[1] ];
    $select9[ $edu_id_array[0] ][ $edu_id_array[1] ][ $edu_id_array[2] ] = $edu3_value['edu_3_name'];
  }
  */
  //debug_msg( __LINE__.' ', $data );
  //die();
	//$sql="SELECT * FROM exam_template ORDER BY template_id";
	//$result = $dbh->query($sql);
	//while ($data = $result->fetchRow()) {
		//$TpType[$data[template_id]]=$data[template_id].'.'.$data[name];
	//}

	//-- 顯示選單
	echo "☆★☆ 新增試題 ☆★☆<br>";
	//$form->addElement('header', 'myheader', '&nbsp;&nbsp;');  //標頭文字
	// Create the Element
	$sel =& $form->addElement('hierselect', 'unit_item', '請選擇試題題號：');
	//$sel2=& $form->addElement('hierselect', 'edu_name', '請選擇試題指標：');
	// And add the selection options
	$sel->setOptions(array($select1, $select2, $select3, $select4, $select5, $select6, $select7));
	//$sel2->setOptions(array($select7, $select8, $select9));
	for ($i=1;$i<=10;$i++)  $op_ans[$i]=$i;  //建立選項數
	for ($i=1;$i<=20;$i++)  $op_points[$i]=$i;  //建立配分
  
  //題組題--最多五題
  for($i=1;$i<=5;$i++){
  	$form->addElement('header', 'myheader', '<br><center>====題組題第'.$i.'題====</center>');
    //$form->addElement('hidden', 'testlet[]', $i);
    $form->addElement('file','userfile[]','題目：');
  	for( $ii= 1 ; $ii<=10 ; $ii++ ){
        $form->addElement('file','userfile[]','選項'.$ii.'：');  
    }    
  	$form->addElement('select', 'myans[]', '正確答案：', $op_ans);
	$form->addElement("select", "mypoints[]", "配分：", $op_points);
  	//$form->addElement('select','template_id','試題版型：', $TpType);
  	$form->addElement('text', 'parameter_a[]', '鑑別度a[必填]');
  	$form->addElement('text', 'parameter_b[]', '難度b[必填]');
  	$form->addElement('text', 'parameter_c[]', '猜測度c[必填]');
    //$form->addRule('parameter_a[]', '鑑別度a不可空白', 'required',null, 'client', null, null);
    //$form->addRule('parameter_b[]', '難度b不可空白', 'required',null, 'client', null, null);
    //$form->addRule('parameter_c[]', '猜測度c不可空白', 'required',null, 'client', null, null);
  //	$form->addElement('text', 'parameter_dim', '向度');
  	$form->addElement('text', 'parameter_sub[]', '能力指標');
  	/*$form->addElement('header', 'myheader', '====以下參數，無則免填====');
  	$form->addElement('file','userfile[]','選項1說明：');
  	$form->addElement('file','userfile[]','選項2說明：');
  	$form->addElement('file','userfile[]','選項3說明：');
  	$form->addElement('file','userfile[]','選項4說明：');*/  
  }

//	$sql = "SELECT * FROM `exam_edu_parameter` WHERE `show` =1 AND `publisher_id` ='".$pid."' AND `subject_id` ='".$sid."' ";
    //debug_msg("第".__LINE__."行 sql ", $sql);
//    die();
//	$result = $dbh->query($sql);
//	$i=1;
//	while ($data = $result->fetchRow()) {
//		$_SESSION['EduParaSN'][$i]=$data['sn'];
//		$form->addElement("checkbox", "EduPara".$i, "教育目標參數".$i."：" ,$data['edu_parameter']);
//		$i++;
//	}


    $form->addElement('hidden','op','modload');
	$form->addElement('hidden','name','ConceptStructure');
	$form->addElement('hidden','file','creatITEM_testlet');
	$form->addElement('hidden','opt','creat_item');
	$form->addRule('userfile', '不可上傳空檔', 'uploadedfile',null,'client');
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->display();
}



