<?php
require_once "HTML/QuickForm.php";
require_once 'HTTP/Upload.php';
require_once 'Date.php';
require_once "include/adp_API.php";

$module_name = basename(dirname(__FILE__));


//匯入試題結構

IMPORT_ITEM_table_header();

//-- 顯示主畫面
 echo '<br>
	   <table width="95%" border="1" cellpadding="0" cellspacing="0">
		  <tr>
		    <td align="center" valign="top" bordercolor="#FFCC33">';

	listCLASS();  //一進來的選單畫面
		    //debug_msg("第".__LINE__."行 _REQUEST ", $_REQUEST);
 echo '  </td>
      </tr>
     </table>';

echo '<br>
	   <table width="95%" border="1" cellpadding="0" cellspacing="0">
		  <tr>
		    <td align="center" valign="top" bordercolor="#FFCC33">';
        if($_REQUEST['opt']=='EP2class'){
	       EP2class($_REQUEST['class']);
        }elseif(isset($_REQUEST['opt'])){
        //預設值（刪除或新增的功能選錯）
        $RedirectTo="Location: modules.php?op=modload&name=".$module_name."&file=ctrlEP_abc";
	      Header($RedirectTo);
        }
        /*echo '1'.$_REQUEST['opt'].'<br>';
        echo '2'.$_REQUEST['class'].'<br>';
        echo '3'.$_REQUEST['EPids'].'<br>'; 
        echo '4'.$_REQUEST['OpenEPids'].'<br>';*/
echo   '</td>
      </tr>
     </table><br>';

if ($_POST['import_user']) {

	$ep_id = $_POST['ep_id'];
	$upload = new HTTP_Upload();
	$mydir=_ADP_TMP_UPLOAD_PATH;  //預設上傳結構概念矩陣檔之目錄
	if (!is_dir($mydir)){
		mkdir($mydir, 0777);
	}
	if (PEAR::isError($upload)) die (__LINE__.$upload->getMessage());  //顯示錯誤訊息
	$file = $upload->getFiles('userfile');
	$file1 = $upload->getFiles('userfile1');
	if (PEAR::isError($file)) die (__LINE__.$file->getMessage());  //顯示錯誤訊息
	if (PEAR::isError($file1)) die (__LINE__.$file1->getMessage());  //顯示錯誤訊息

//題組結構    
	/*
    if ($file->isValid()) {
		$file->setName('uniq');
		$file_name = $file->moveTo($mydir); 
		if (PEAR::isError($file_name)){
			die ('發生錯誤：'.$file_name->getMessage());  //顯示錯誤訊息
		}elseif ($file->isMissing()) {
			die ('<br><br>錯誤！上傳檔案不正確！');
		}
		$prop = $file->getProp();   //取得上傳檔案之最後資訊

		$import_data_path=$mydir.$prop['name'];   //上傳檔案的路徑
		chk_file_exist($import_data_path, __LINE__);
		$InputArray=read_excel_2j($import_data_path, __LINE__);
      //debug_msg("第".__LINE__."行 InputArray ", $InputArray);
      	
		$sql = "delete from concept_item_testlet WHERE exam_paper_id = '$ep_id'";
		$result =$dbh->query($sql);		  
		  
		
		$x=0;
		$sql = "select item_sn from concept_item WHERE exam_paper_id = '$ep_id' ORDER BY item_num";
		$result =$dbh->query($sql);
		while ($row=$result->fetchRow())
			{
			 $x++;
			 $item[$x]=$row['item_sn'];
			}
//  debug_msg("第".__LINE__."行 data ", $item);
//	echo $import_data_path."<br>";
//	echo sizeof($InputArray)."<br>";
		for($j=0;$j<=sizeof($InputArray);$j++){
			if ( strlen($InputArray[$j][0]) && $InputArray[$j][0]!='') { //第一筆為抬頭，不檢查，ROW[0]：姓名不為空值
			 //  debug_msg("第".__LINE__."行 InputArray ", $InputArray);
			 //  $MaxArrayKey=max(array_keys($InputArray[$j]));
				$z=1;
	            for($i=1;$i<=$x;$i++){
						//$ROW[$i]=iconv("big5", "UTF-8", $InputArray[$j][$i]);   //轉成utf-8
						$ROW[$i]=trim($InputArray[$j][$i]);   //去除空格
				//		echo $ROW[$i];
						if ($ROW[$i]==1)
							{
							 $query = 'INSERT INTO concept_item_testlet (exam_paper_id, testlet_num, testlet_sub_num, item_sn ) VALUES (?,?,?,?)';
							 $data = array($ep_id, $j+1, $z, $item[$i]);
	      			//  	 debug_msg("第".__LINE__."行 data ", $data);
	        				  //die();
	
	        				  //-- 寫入資料庫
	     				 	$result=$dbh->query($query, $data);
	     				      $z++;
							}
					}

		//		 echo $testlet[$j]."<br>";
					//debug_msg("第".__LINE__."行 ROW ", $ROW);
					
		//		echo "<br>";	
	
					//echo "<br>seating= $seating    user_id= $user_id<br>";
			//	$query = 'INSERT INTO concept_item_testlet (exam_paper_id, testlet_num, item_sn ) VALUES (?,?,?)';
			//	$data = array($ep_id, $j+1, $testlet[$j]);
	        //	debug_msg("第".__LINE__."行 data ", $data);
	        	//die();
	
	        	//-- 寫入資料庫
	        //	$result=$dbh->query($query, $data);
				if (PEAR::isError($result)) {
					echo "錯誤訊息：".$result->getMessage()."<br>";
					echo "錯誤碼：".$result->getCode()."<br>";
					echo "除錯訊息：".$result->getDebugInfo()."<br>";
				}	
			}
		}
		unlink($import_data_path);
	}
	*/
	
///能力值結構
	if ($file1->isValid()) {
		$file1->setName('uniq');
		$file_name = $file1->moveTo($mydir); 
		if (PEAR::isError($file_name)){
			die ('發生錯誤：'.$file_name->getMessage());  //顯示錯誤訊息
		}elseif ($file1->isMissing()) {
			die ('<br><br>錯誤！上傳檔案不正確！');
		}
		$prop = $file1->getProp();   //取得上傳檔案之最後資訊

		$import_data_path=$mydir.$prop['name'];   //上傳檔案的路徑
		chk_file_exist($import_data_path, __LINE__);
		$InputArray=read_excel_2j($import_data_path, __LINE__);
      //debug_msg("第".__LINE__."行 InputArray ", $InputArray);
      	
      
      
      
      	$x=0;
		$sql = "select item_sn from concept_item WHERE exam_paper_id = '$ep_id' ORDER BY item_num";
		$result =$dbh->query($sql);
		while ($row=$result->fetchRow())
			{
			 $item[$x]=$row['item_sn'];
			 $x++;
			}
//  debug_msg("第".__LINE__."行 data ", $item);			
//	echo $import_data_path."<br>";
//	echo sizeof($InputArray)."<br>";
		for($j=0;$j<=sizeof($InputArray);$j++){
			if ( strlen($InputArray[$j][0]) && $InputArray[$j][0]!='') { //第一筆為抬頭，不檢查，ROW[0]：姓名不為空值
			//   debug_msg("第".__LINE__."行 InputArray ", $InputArray);
			 //  $MaxArrayKey=max(array_keys($InputArray[$j]));
						//$ROW[$i]=iconv("big5", "UTF-8", $InputArray[$j][$i]);   //轉成utf-8
//						$ROW[]=trim($InputArray[$j][]);   //去除空格
//						debug_msg("第".__LINE__."行 InputArray ", $ROW);
				$InputArray[$j][1]=$InputArray[$j][1]*1;
				$InputArray[$j][2]=$InputArray[$j][2]*1;
				$InputArray[$j][3]=$InputArray[$j][3]*1;
						if ($j<$x)
							{
					 		$sql = "UPDATE concept_item_parameter SET a = '".$InputArray[$j][1]."', b = '".$InputArray[$j][2]."', c = '".$InputArray[$j][3]."' where item_sn= '$item[$j]' ";
							
	   			         	//debug_msg("第".__LINE__."行 data ", $sql);
	        	  
			        	//-- 寫入資料庫
	    			    	$result = $dbh->query($sql);
							}
						 else
						 	{
						 	 break;
						 	}
					

		//		 echo $testlet[$j]."<br>";
					//debug_msg("第".__LINE__."行 ROW ", $ROW);
					
		//		echo "<br>";	
	
					//echo "<br>seating= $seating    user_id= $user_id<br>";
		//		$query = 'INSERT INTO concept_item_testlet (exam_paper_id, testlet_num, testlet_item_sn ) VALUES (?,?,?)';
		//		$data = array($ep_id, $j+1, $testlet1[$j]);
	    //   	debug_msg("第".__LINE__."行 data ", $data);
	        	//die();
	
	        	//-- 寫入資料庫
	        //	$result=$dbh->query($query, $data);
				if (PEAR::isError($result)) {
					echo "錯誤訊息：".$result->getMessage()."<br>";
					echo "錯誤碼：".$result->getCode()."<br>";
					echo "除錯訊息：".$result->getDebugInfo()."<br>";
				}	
			}
		}
		unlink($import_data_path);
	}

}




function listCLASS(){
	global $dbh, $module_name;
	//echo $dbh;
	//echo $module_name;
	$form = new HTML_QuickForm('frmadduser','post',$_SERVER['PHP_SELF']);
	//-- 尋找目前已建立之班級，並初始化"關聯選單"
	$select1[0]='版本';
	$select2[0][0]='科目';
	$select3[0][0][0]='冊';
	$select4[0][0][0][0]='單元';
	$select5[0][0][0][0][0]='卷';
	
	$sql = "SELECT distinct exam_paper_id FROM concept_item WHERE (exam_paper_id != -1) ORDER BY exam_paper_id ASC";
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
		$select3[$cc][$oi][$gr]="第".$gr."冊";
		$select4[$cc][$oi][$gr][$cl]="第".$cl."單元";
		$select5[$cc][$oi][$gr][$cl][$rl]="卷".$rl;
		
	}

	//-- 顯示選單
	//echo "☆★☆ 班級列表 ☆★☆<br>";
	$form->addElement('header', 'myheader', '<center> 試卷列表 【試題參數】</canter>');  //標頭文字
	// Create the Element
	$sel =& $form->addElement('hierselect', 'class', '請選擇試卷：');
	// And add the selection options
	$sel->setOptions(array($select1, $select2, $select3, $select4, $select5));
	
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file','ctrlEP_abc');
	$form->addElement('hidden','opt','EP2class');
	$form->addRule('class', '「班級」不可空白！', 'nonzero',  null, 'client', null, null);
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->display();
}



function EP2class($class){
	global $dbh , $module_name;

	$listCLASS='<a href="modules.php?op=modload&name='.$module_name.'&file=ctrlEP_talent">選擇其他班級</a>';
	echo '<br>現在的試卷是：'.id2publisher($class[0]).'&nbsp;'.id2subject($class[1]).'&nbsp;&nbsp;'.'第'.$class[2].'冊'.'第'.$class[3].'單元&nbsp;'.'卷'.$class[4].'&nbsp;&nbsp;&nbsp;&nbsp;<hr>';
	
	$ep_id = get_epid($class[0],$class[1],$class[2],$class[3],$class[4]);
	$s=0;
//	echo $ep_id;
	$sql = "select item_sn from concept_item WHERE exam_paper_id = '$ep_id' order by item_num ";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow())
		{
		 $s++;
		 $num[$s]= $row['item_sn'];
		}
//	echo $itemm;
//	die();
	
//	debug_msg("第".__LINE__."行 ROW ", $num);
	if(isset($num[$s]))
		{
//	debug_msg("第".__LINE__."行 InputArray ", $num);
		echo '<table width="80%" align="center" border="1" cellpadding="0" cellspacing="0">';			
	//		debug_msg("第".__LINE__."行 ROW ", $result);
		echo '<tr><td align="center" valign="top" bordercolor="#FFCC33">';
		echo "　"."題　數"."　";
		echo '</td>';
		echo '<td align="center" valign="top" bordercolor="#FFCC33">';
		echo "　"."鑑別度"."　";
		echo '</td>';
		echo '<td align="center" valign="top" bordercolor="#FFCC33">';
		echo "　"."難度"."　";
		echo '</td>';
		echo '<td align="center" valign="top" bordercolor="#FFCC33">';
		echo "　"."猜測度"."　";
		echo '</td></tr>';		
			 for($j=1;$j<=$s;$j++)
			 	{
				 $sql = "select concept_item.item_num, concept_item_parameter.a, concept_item_parameter.b, concept_item_parameter.c 
				 		 from concept_item,concept_item_parameter WHERE concept_item_parameter.item_sn = '$num[$j]' and concept_item.item_sn = '$num[$j]' ";
			  	 $result1 =$dbh->query($sql);
			  	 while ($row1=$result1->fetchRow())
			   	 	{
			 	  	 //$num_s_i[$j]=$row1['item_num'];
			 	  	 echo '<tr><td align="center" valign="top" bordercolor="#FFCC33">';
			 	  	 echo "第　".$row1['item_num']."　題";
			 	  	 echo '</td>';
			 	  	 echo '<td align="center" valign="top" bordercolor="#FFCC33">';
			 	  	 echo "　".$row1['a']."　";
			 	  	 echo '</td>';
			 	  	 echo '<td align="center" valign="top" bordercolor="#FFCC33">';
			 	  	 echo "　".$row1['b']."　";
			 	  	 echo '</td>';
			 	  	 echo '<td align="center" valign="top" bordercolor="#FFCC33">';
			 	  	 echo "　".$row1['c']."　";
			 	  	 echo '</td></tr>';
				 	}
			 	}

			
		echo '</table><br><br>';	
		$form = new HTML_QuickForm('frmadduser','post',$_SERVER['PHP_SELF']);
//		$form->addElement('header','newheader','修改題組結構');
//		$form->addElement('file','userfile','XLS 檔案：');
		$form->addElement('header','newheader','修改試題參數結構');
		$form->addElement('file','userfile1','XLS 檔案：');
		$form->addElement('hidden','op','modload');
		$form->addElement('hidden','name',$module_name);
		$form->addElement('hidden','file','ctrlEP_abc');
		$form->addElement('hidden','ep_id',$ep_id);
		$form->addElement('submit','import_user','開始匯入');
//		$form->addRule('userfile', '不可上傳空檔', 'uploadedfile');
		$form->addRule('userfile1', '不可上傳空檔', 'uploadedfile');
		$form->setRequiredNote('前有<font color=red>*</font>的欄位不可空白');
		$form->setDefaults($defaultValue);	
		$form->display();	



		echo '試題參數結構說明：<br>';
		echo '1.在Windows環境下，利用 excel 鍵入試題參數結構，存成 excel 檔，如 <a href="examples/import_abc.xls">範例檔</a>。<br>';
		//echo '2.在Linux下，鍵入帳號資料，存成 csv 檔，並保留第一列標題檔，如 <a href="examples/import_userALL.csv">範例檔</a>。<br>';
		echo '2.利用本功能可自行設定試題參數結構狀態，匯入試題參數。<br>';
		echo '3.若使用此功能修改試題參數，必須輸入該卷<font color=red>所有試題</font>的試題參數。<br>';
		echo '4.<font color=red>本功能僅接受 Excel 2003檔案格式。</font><br><br>';
		
	
	
		}
	else
		{
	 	echo "尚無試題參數結構";
		}
/*
echo '<td width="40%" bordercolor="#FFCC33">';
//echo $msg;
echo '題組結構說明：<br>';
echo '1.在Windows環境下，利用 excel 鍵入題組結構，存成 excel 檔，如 <a href="examples/import_testlet.xls">範例檔</a>。<br>';
//echo '2.在Linux下，鍵入帳號資料，存成 csv 檔，並保留第一列標題檔，如 <a href="examples/import_userALL.csv">範例檔</a>。<br>';
echo '2.利用本功能可自行設定題組結構狀態，匯入題組。<br><br>';
echo '能力指標結構說明：<br>';
echo '1.在Windows環境下，利用 excel 鍵入能力指標結構，存成 excel 檔，如 <a href="examples/import_talent.xls">範例檔</a>。<br>';
//echo '2.在Linux下，鍵入帳號資料，存成 csv 檔，並保留第一列標題檔，如 <a href="examples/import_userALL.csv">範例檔</a>。<br>';
echo '2.利用本功能可自行設定能力指標結構狀態，匯入能力指標。<br>';
echo '</td>';
*/

}





?>
