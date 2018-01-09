<?php
require_once "HTML/QuickForm.php";
require_once 'HTTP/Upload.php';
require_once 'Date.php';
require_once "include/adp_API.php";

$module_name = basename(dirname(__FILE__));

IMPORT_ITEM_table_header();


if ($_POST['btnSubmit1'])
	{
	 $upload = new HTTP_Upload();
	 if (PEAR::isError($upload)) die ($upload->getMessage());  //顯示錯誤訊息
	 $files = $upload->getFiles();
	 if (PEAR::isError($files)) die ($files->getMessage());  //顯示錯誤訊息
	 //debug_msg("第".__LINE__."行 _REQUEST ", $files);

	 foreach ($files as $file) 
	 	{
		 if ($file->isValid()) 
		 	{
		 	 $cs_id=explode_cs_id($_REQUEST['cs_id']);
			 $mydir=_ADP_CS_UPLOAD_PATH.$cs_id[0]."/".$cs_id[1]."/".$cs_id[2]."/".$cs_id[3]."/";  //預設上傳試題之目錄
			 //$realfilename=$exam_paper_id."_".$item_num."_".$key.".gif";
			 $file->setName('uniq');
			 $file_name = strtolower($file->moveTo($mydir));
			 if (PEAR::isError($file_name)) 
			 	{
				 die ('發生錯誤：'.$file_name->getMessage());  //顯示錯誤訊息
				}
			}

 		 if(isset($prop))  unset($prop);
		 	 $prop = $file->getProp();   //取得上傳檔案之最後資訊
			 $this_upload_file[]=$prop['name'];
			 if($i==0)
			 	{
				 $remedy_name.=strtolower($prop['name'])._SPLIT_SYMBOL;    //取得題目之題幹檔名
				}
			//debug_msg("第".__LINE__."行 prop ", $prop);
			//debug_msg("第".__LINE__."行 op_filename ", $op_filename);
		 }
	  $ii=count($this_upload_file);
	  for($i=0;$i<$ii;$i++)
	  	 {
	  	  $org_file = $mydir.$this_upload_file[$i];
	      $low_filename = strtolower($this_upload_file[$i]);  //強制轉小寫
	  	  $low_file = $mydir.$low_filename;
	  	  $RenameRsu=rename($org_file, $low_file);
	  	  if(!$RenameRsu)
	  	 	 {
		  	  die(__LINE__."改檔名錯誤！");
		 	 }
		 }
	  $ccid=$_POST['cs_id'];
	  $sql = "select remedy_instruction from concept_info WHERE cs_id = '$ccid' ";
	  $result =$dbh->query($sql);
	  while ($row=$result->fetchRow())
		 {
	 	  $sub_file_name=$row["remedy_instruction"];
		 }
	  $ssub_file_name=explode("@XX@",$sub_file_name);
	  if($sub_file_name!=null)
	  	{
	  	 $remedy_file_name=explode("@XX@",$remedy_name);
	  	 $new_ssub_file_name='';
	  	 for($i=0;$i<$ii;$i++)
	  		{
		 	 if($remedy_file_name[$i]==null)
		 	 	{
				 $remedy_file_name[$i] = $ssub_file_name[$i];
				}
			 $new_ssub_file_name.=$remedy_file_name[$i]._SPLIT_SYMBOL;
			}
		 $sql = "UPDATE concept_info SET remedy_instruction = '$new_ssub_file_name' where cs_id = '$ccid' ";
		 $result = $dbh->query($sql);
		 
		}
	  else
	  	{
		 $sql = "UPDATE concept_info SET remedy_instruction = '$remedy_name' where cs_id = '$ccid' ";
		 $result = $dbh->query($sql);
		 
		}
	  
	 }



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
           if (isset($_REQUEST['cs_id']))
		   	   {
				$cs_id=explode_cs_id($_REQUEST['cs_id']);
				EP2class($cs_id);
			   }
		   else	
		   	   {
	    	    EP2class($_REQUEST['class']);
	    	   }
        }
		elseif($_REQUEST['opt']=='EP2class2'){
			$cs_id=explode_cs_id($_REQUEST['cs_id']);
			remedy();
		}
		elseif(isset($_REQUEST['opt'])){
        //預設值（刪除或新增的功能選錯）
        $RedirectTo="Location: modules.php?op=modload&name=".$module_name."&file=creatREMEDY";
	      Header($RedirectTo);
        }
echo   '</td>
      </tr>
     </table><br>';



function listCLASS(){
	global $dbh, $module_name;
	
	//echo $module_name;
	$form = new HTML_QuickForm('frmadduser','post',$_SERVER['PHP_SELF']);
	//-- 尋找目前已建立之班級，並初始化"關聯選單"
	$select1[0]='版本';
	$select2[0][0]='科目';
	$select3[0][0][0]='冊';
	$select4[0][0][0][0]='單元';
	
	$sql = "SELECT distinct cs_id FROM concept_info WHERE (cs_id != -1) ORDER BY cs_id ASC";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$paper_info=explode_cs_id($row['cs_id']);
	  $cc=intval($paper_info[0]);
	  $oi=intval($paper_info[1]);
	  $gr=intval($paper_info[2]);
	  $cl=intval($paper_info[3]);	  
		$select1[$cc]=id2publisher($cc);
		$select2[$cc][$oi]=id2subject($oi);
		$select3[$cc][$oi][$gr]="第".$gr."冊";
		$select4[$cc][$oi][$gr][$cl]="第".$cl."單元";		
		
	}

	//-- 顯示選單
	//echo "☆★☆ 班級列表 ☆★☆<br>";
	$form->addElement('header', 'myheader', '<center> 單元列表 【補救教學】</canter>');  //標頭文字
	// Create the Element
	$sel =& $form->addElement('hierselect', 'class', '請選擇單元：');
	// And add the selection options
	$sel->setOptions(array($select1, $select2, $select3, $select4));
	
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file','creatREMEDY');
	$form->addElement('hidden','opt','EP2class');
	$form->addRule('class', '「班級」不可空白！', 'nonzero',  null, 'client', null, null);
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->display();
}



function EP2class($class){
	global $dbh , $module_name;

	echo '<br>現在的單元是：'.id2publisher($class[0]).'&nbsp;'.id2subject($class[1]).'&nbsp;&nbsp;'.'第'.$class[2].'冊'.'第'.$class[3].'單元&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<hr>';
	
	$cs_id = get_csid($class[0],$class[1],$class[2],$class[3]);
	
	$sql = "select remedy_instruction from concept_info WHERE cs_id = '$cs_id' ";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow())
		{
	 	$sub_num=$row["remedy_instruction"];
		}
	
	if($sub_num!=null)
		{
		 $sql = "select sub_score_name from concept_info_dim WHERE cs_id = '$cs_id' ";
		 $result =$dbh->query($sql);
		 while ($row=$result->fetchRow())
		 	{
		 	 $sub_name=$row["sub_score_name"];
			}
		
		 $ssub=explode("@XX@",$sub_num);
		 $ssub_name=explode("@XX@",$sub_name);
    
		 echo '<table width="95%" align="center" border="0" cellpadding="4" cellspacing="0" class="d_tableline">';
		 echo '<tr><td align="center" background="'._THEME_IMG.'tit_bg04.gif" class="d_s_title41">能力指標</td>
			   <td align="center" background="'._THEME_IMG.'tit_bg04.gif" class="d_s_title41">補救教學</td><tr>';
		 for($i=0;$i<sizeof($ssub_name);$i++)
			 {
		 	 echo '<tr><td width="35%" align="center" background="'._THEME_IMG.'tit_bg04.gif" class="d_s_title41">'.$ssub_name[$i].'</td>
		 	   	   <td align="center" background="'._THEME_IMG.'tit_bg04.gif" class="d_s_title41"><a href=modules.php?op=modload&name='.$module_name.'&file=creatREMEDY&opt=EP2class2&cs_id='.$cs_id.'&sub='.$i.'>查詢</a></td></tr>';
			 }
		 echo '</table><br><hr>';
		 echo " 修改補救教學檔案 <br>";
		 $form = new HTML_QuickForm('frmTest','post','');
		 for($i=0;$i<sizeof($ssub_name);$i++)
		 	{
			 $form->addElement('file','userfile[]',$ssub_name[$i]);
			}
		 $form->addElement('hidden','op','modload');
		 $form->addElement('hidden','name','ConceptStructure');
		 $form->addElement('hidden','file','creatREMEDY');
		 $form->addElement('hidden','opt','EP2class');
		 $form->addElement('hidden','cs_id',$cs_id);
		 $form->addRule('userfile', '不可上傳空檔', 'uploadedfile',null,'client');
		 $form->addElement('submit','btnSubmit1','選擇完畢，送出！');
		 $form->display();
		}
	else
		{
	 	 echo "尚無補救教學<br><hr>";
	 	 $sql = "select remedy_instruction from concept_info WHERE cs_id = '$cs_id' ";
		 $result =$dbh->query($sql);
		 while ($row=$result->fetchRow())
			 {
	 		  $sub_num=$row["remedy_instruction"];
			 }
		 $sql = "select sub_score_name from concept_info_dim WHERE cs_id = '$cs_id' ";
		 $result =$dbh->query($sql);
		 while ($row=$result->fetchRow())
		 	{
		 	 $sub_name=$row["sub_score_name"];
			}
		
		 $ssub=explode("@XX@",$sub_num);
		 $ssub_name=explode("@XX@",$sub_name);
		 
	 	 echo " 新增補救教學檔案 <br>";
	 	 $form = new HTML_QuickForm('frmTest','post','');
	 	 for($i=0;$i<sizeof($ssub_name);$i++)
		 	{
			 $form->addElement('file','userfile[]',''.$ssub_name[$i].'');
			}
	 	 $form->addElement('hidden','op','modload');
		 $form->addElement('hidden','name','ConceptStructure');
		 $form->addElement('hidden','file','creatREMEDY');
		 $form->addElement('hidden','opt','EP2class');
		 $form->addElement('hidden','cs_id',$cs_id);
		 $form->addRule('userfile', '不可上傳空檔', 'uploadedfile',null,'client');
		 $form->addElement('submit','btnSubmit1','選擇完畢，送出！');
		 $form->display();
		}
	echo '補救教學檔案說明：<br>';
	echo '1.在Windows環境下，利用 excel 鍵入能力指標結構，存成 excel 檔，如 <a href="examples/import_remedy.xls">範例檔</a>。<br>';
	echo '2.利用本功能可自行匯入補救教學內容。<br><br>';
}


function remedy()
	{
	 global $dbh , $module_name;
	 
	 $cs_id = explode_cs_id($_GET['cs_id']);
	 $ccs_id = $_GET['cs_id'];
	 $sub = $_GET['sub']+1;
	 $suub = $_GET['sub'];
	 $sql = "select sub_score_name from concept_info_dim WHERE cs_id = '$ccs_id' ";
	 $result =$dbh->query($sql);
	 while ($row=$result->fetchRow())
	 	{
	 	 $sub_name=$row["sub_score_name"];
		}
	 $sql = "select remedy_instruction from concept_info WHERE cs_id = '$ccs_id' ";
	 $result =$dbh->query($sql);
	 while ($row=$result->fetchRow())
		{
	 	 $sub_file_name=$row["remedy_instruction"];
		}
	 $ssub_name=explode("@XX@",$sub_name);
	 $sub_file_name = explode("@XX@",$sub_file_name);
	 $import_data_path = "data/CS_db/".$cs_id[0]."/".$cs_id[1]."/".$cs_id[2]."/".$cs_id[3]."/".$sub_file_name[$suub];
	 echo '<br>現在的單元是：'.id2publisher($cs_id[0]).'&nbsp;'.id2subject($cs_id[1]).'&nbsp;&nbsp;'.'第'.$cs_id[2].'冊'.'第'.$cs_id[3].'單元&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<hr>';
	
	 $InputArray=read_excel_2j($import_data_path, __LINE__);
	 //debug_msg("第".__LINE__."行 InputArray ", $InputArray);
	 //sizeof($InputArray[0])
	 echo '<br>';
	 echo '<table width="75%" border="0" cellspacing="0" cellpadding="0">';
	 echo '<tr><td scope="col" align="center"><img src="'._THEME_IMG.'diag_t1.jpg"></td></tr>';
	 echo '<tr><td align="left" background="'._THEME_IMG.'tit_bg04.gif" class="d_title" colspan="2">'.$ssub_name[$suub].'</td></tr></table>';
	 echo '<table width="82%" align="center" border="0" cellpadding="4" cellspacing="0" class="d_tableline">';
	 echo '<tr><td align="center" background="'._THEME_IMG.'tit_bg04.gif" class="d_s_title41">學習材料</td>
			  <td align="center" background="'._THEME_IMG.'tit_bg04.gif" class="d_s_title41">作者</td><tr>';
	 for($i=0;$i<sizeof($InputArray);$i++)
		 {
		  echo "<tr>";
		  if (ereg('http://',$InputArray[$i][0]))
		 	 echo '<td align="left" background="'._THEME_IMG.'tit_bg04.gif" class="d_s_title41"><a href='.$InputArray[$i][0].' target=_blank>'.$InputArray[$i][1].'</a></td>';
		  else
		 	 echo '<td align="left" background="'._THEME_IMG.'tit_bg04.gif" class="d_s_title41">'.$InputArray[$i][1].'︰'.$InputArray[$i][0].'</td>';
		  if (isset($InputArray[$i][2]))
		 	 echo '<td width="35%" align="left" background="'._THEME_IMG.'tit_bg04.gif" class="d_s_title41">'.$InputArray[$i][2].'</td>';
		  else
		 	 echo '<td align="left" background="'._THEME_IMG.'tit_bg04.gif" class="d_s_title41">　</td>';
		  echo "</tr>";
		 }
	 echo '<tr><td colspan=2 align="left" background="'._THEME_IMG.'tit_bg04.gif" class="d_s_title41"><br>本站內容均透過網路搜尋整理而來，供大家閱讀學習，所有作品之版權均歸作者或版權持有人所有，任何單位和個人不得將之用於商業用途，否則後果自負！ 如有不妥，請來信『fiona@ms3.ntcu.edu.tw』告知，我們將在24小時之內進行處理。</td></tr>';
	 echo '</table>';
	 echo "<br>";
	 echo '<a href=modules.php?op=modload&name='.$module_name.'&file=creatREMEDY&opt=EP2class&cs_id='.$ccs_id.'>返回</a>';
	 echo "<br><br>";
	}

?>



