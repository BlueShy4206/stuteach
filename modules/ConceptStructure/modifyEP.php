<?php
require_once "HTML/QuickForm.php";
require_once 'HTTP/Upload.php';
require_once "include/adp_API.php";

IMPORT_CREATITEM_table_header();
$OpenedCS=getCSlicense($user_data->user_id);

//-- 顯示主畫面上方子選單
//CS_ITEM_table_header();
//debug_msg("第".__LINE__."行 _REQUEST ", $_REQUEST);
//debug_msg("第".__LINE__."行 _REQUEST ", $_SESSION);
if(isset($_REQUEST['opt2'])){
   unset($_SESSION['opt2']);
   $_SESSION['opt2']=$_REQUEST['opt2'];
}


echo '<br>
<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="center" bordercolor="#FFCC33">';

if(isset($_GET['del_ep_id'])){
	echo "<br><br>".EPid2FullName($_GET['del_ep_id'])."--刪除成功！！<br><br>";
}

modifyEP($_REQUEST['opt']); 

echo '</td></tr></table>';

//debug_msg("第".__LINE__."行 _REQUEST ", $_REQUEST);
if($_REQUEST['opt']=="listEP" || $_REQUEST['opt']=="checkEP"){
	echo "<br>";
	listEP($_REQUEST['opt2']);
}elseif($_REQUEST['opt']=="deleteEP"){
	deleteEP($_REQUEST['opt']);
}elseif($_REQUEST['opt']=="AddDetailSol4EP"){
   //debug_msg("第".__LINE__."行 _REQUEST ", $_REQUEST);
   AddDetailSol4EP();
}
//debug_msg("第".__LINE__."行 _REQUEST ", $_REQUEST);

function modifyEP($opt){
	global $dbh, $OpenedCS, $user_data;
	//debug_msg("第".__LINE__."行 opt ", $opt);

	$form = new HTML_QuickForm('frmTest','post','');
	$cs_id=sprintf("%03d%02d%02d%02d",$_REQUEST['unit_item'][0],$_REQUEST['unit_item'][1],$_REQUEST['unit_item'][2],$_REQUEST['unit_item'][3]); 
	$paper_vol=$_REQUEST['unit_item'][4];
	$exam_paper_id=$cs_id.sprintf("%02d",$paper_vol);
	$item_num=$_REQUEST['unit_item'][5];


	//-- "上傳試題"選項由此開始
	//-- 尋找目前已建立之單元結構，並初始化"關聯選單"
	/* $select1[0]='版本';
	$select2[0][0]='科目';
	$select3[0][0][0]='冊別';
	$select4[0][0][0][0]='單元【單元名稱】';
	$select5[0][0][0][0][0]='卷別'; */

	//$sql = "select * from concept_info ORDER BY cs_id";
	if($user_data->access_level==91){
        $sql = "select * from concept_info, concept_info_plus WHERE concept_info.cs_id=concept_info_plus.cs_id ORDER BY concept_info.cs_id";
	}else{
        $sql = "select * from concept_info, concept_info_plus WHERE concept_info.cs_id=concept_info_plus.cs_id AND concept_info_plus.ready='0' ORDER BY concept_info.cs_id";
	}
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
    
             $sql2="select distinct paper_vol from concept_item where cs_id='".$row['cs_id']."' ORDER BY paper_vol";
             $result2 =$dbh->query($sql2);
             while ($row2=$result2->fetchRow()){
                $select5[$row['publisher_id']][$row['subject_id']][$row['vol']][$row['unit']][$row2['paper_vol']]="卷".$row2['paper_vol'];
             }
        }
    }
/*    
    if($_SESSION['opt2']=="modifyEP"){
        $chosen_opt="listEP";
        $head_title="編修試卷";
    }elseif($_SESSION['opt2']=="AddDetailSol"){
        $chosen_opt="AddDetailSol4EP";
        $head_title="新增試題詳解";
    }elseif($_SESSION['opt2']=="checkEP"){
        $chosen_opt="checkEP";
        $head_title="觀看試卷";
    }
*/
		$chosen_opt="listEP";
        $head_title="編修試卷";   
   
	//-- 顯示選單
	echo " ".$head_title." <br>";
	//$form->addElement('header', 'myheader', '&nbsp;&nbsp;');  //標頭文字
	// Create the Element
	$sel =& $form->addElement('hierselect', 'unit_item', '請選擇試卷：');
	// And add the selection options
	$sel->setOptions(array($select1, $select2, $select3, $select4, $select5));
	//$form->addElement("checkbox","ShowSol","功能1：","顯示答案詳解","1");
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name','ConceptStructure');
	$form->addElement('hidden','file','modifyEP');
	$form->addElement('hidden','opt',$chosen_opt);
	$form->addElement('hidden','opt2','modifyEP');
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->display();
}


function listEP($opt2){
	global $dbh;
    //debug_msg("第".__LINE__."行 opt2 ", $opt2);
    
	OpenTable();
	$cs_id=sprintf("%03d%02d%02d%02d",$_REQUEST['unit_item'][0],$_REQUEST['unit_item'][1],$_REQUEST['unit_item'][2],$_REQUEST['unit_item'][3]);
	$paper_vol=$_REQUEST['unit_item'][4];
	$ep_id=$cs_id.sprintf("%02d", $paper_vol);
	$EP_title=EPid2FullName($ep_id);
	if($opt2=="modifyEP"){
	   $delEP_url="modules.php?op=modload&name=ConceptStructure&file=modifyEP&opt=deleteEP&cs_id=".$cs_id.'&paper_vol='.$paper_vol;
        $delEP = "<a href=\"javascript:if (confirm('你確定刪除這張試卷？\n   這樣會刪除這張試卷下的所有試題！')==true) self.location = '".$delEP_url."';\"><img src=\""._ADP_URL."images/delete.png\" alt=\"刪除試卷\" border=\"0\">&nbsp;刪除試卷</a> ";
    }else{
        $delEP_url=$delEP="";
    }
	echo '<table width="100%" border="1" cellpadding="1" cellspacing="1" bordercolor="#0000FF" >';
	echo "<tr><td align=\"center\" colspan=\"2\"><font class=\"title\"><b>試卷名稱：".$EP_title.$ep_id."</b></font>&nbsp;&nbsp;&nbsp;&nbsp;【".$delEP."】</td>";
	echo '</tr><tr>';
	echo '<td align="center"><font class="title"><b>試題內容</b></font></td>';
	echo '<td align="center"><font class="title"><b>功能</b></font></td></tr>';

	$sql = "select * from concept_item where cs_id = '$cs_id' and paper_vol='$paper_vol' order by item_num";
	$result = $dbh->query($sql); 
  //debug_msg("第".__LINE__."行 sql ", $sql); 
	while ($data = $result->fetchRow()) {
		$item_snn=$data['item_sn'];
		$item_num=$data['item_num'];
		$item_data=new ItemData($cs_id, $paper_vol, $item_num);
		$item=$item_data->getItemData();
		$SolItem=$item_data->getSolItemData();
		$ItemEduPara=$data['edu_parameter'];      //建構題檔名
		$sol_pieces=$item_data->sol_pieces;  //詳解的原始檔名
			//debug_msg("第".__LINE__."行 data ", $data);  
		//debug_msg("第".__LINE__."行 item ", $item);
      //debug_msg("第".__LINE__."行 SolItem ", $SolItem);
   //die();
    $showCR='建構題檔名：'.$ItemEduPara;
		echo "<tr>";
    
		echo '<td align="left"><b>&nbsp;&nbsp;【'.$item_num.'】&nbsp;';
    if($data['edu_parameter']==''){
      echo '<img src="'.$item['0'].'" align="top"><br><br>';
  		for($i=1;$i<=4;$i++){
  			for($j=0;$j<10;$j++){   //控制縮排
  				echo '&nbsp;';
  			}
  			echo '<b>('.$i.')</b></font>&nbsp;&nbsp;<img src="'.$item[$i].'" align="top"><br><br>';
      }
    }else{
        echo '本題已設定為建構題，'.$showCR;
    }
		if($_REQUEST['ShowSol']==1){
            for($i=1;$i<=sizeof($SolItem);$i++){
                for($j=0;$j<10;$j++){   //控制縮排
                    echo '&nbsp;';
                }
                echo '<b>選項('.$i.')說明</b></font>'."&nbsp;&nbsp;";
                if($sol_pieces[$i-1]==""){
                    echo "：無<br><br>";
                }else{
                    echo '<img border="0" src="'.$SolItem[$i].'" align="top">'."<br><br>";
                }
            }
        }
        echo '</td>';
        $j=0;


        if($_REQUEST['ShowSol']==1){  //顯示詳解
            $ShowSol=1;
        }
        if($opt2=="modifyEP"){
    		$base='modules.php?op=modload&name=ConceptStructure&file=modifyITEM&pid='.$_REQUEST['unit_item'][0].'&sid='.$_REQUEST['unit_item'][1].'&vid='.$_REQUEST['unit_item'][2].'&uid='.$_REQUEST['unit_item'][3].'&paper='.$paper_vol.'&ShowSol='.$ShowSol;
    		$del_url=$base."&opt=deleteITEM&item_sn=".$item_data->item_sn;
    		$del = "<a href=\"javascript:if (confirm('你確定刪除這個試題【第".$item_num."題】？\n   這可能會影響到這張試卷的試題結構！建議使用「修改」的功能！')==true) self.location = '".$del_url."';\"><img src=\""._ADP_URL."images/delete.png\" alt=\"刪除試題\" border=\"0\" align=\"texttop\">&nbsp;刪除&nbsp;</a> ";
    		$modify_url=$base."&opt=editITEM&opti=editITEM&item_num=".$item_data->item_num."&ShowSol".$ShowSol;
    		$modify = '<a href="'.$modify_url.'"><img src="'._ADP_URL.'images/edit.png" alt="修改試題內容" border="0" align=\"texttop\">&nbsp;修改&nbsp;</a>';
        }else{
            $modify=$del="";
        }
    	$sql= "select a, b, c, sub  from concept_item_parameter where item_sn ='".$item_snn."' ";
		$result1 = $dbh->query($sql);
		while ($data1 = $result1->fetchRow()) {
			$P_a=$data1['a'];
			$P_b=$data1['b'];
			$P_c=$data1['c'];
			$P_sub=$data1['sub'];
		}
		echo '<td valign=top>試題SN<br>'.$data[item_sn].'<br><br><br><font class="title">答案：'.$item['ans'].'<br>配分：'.$item['points'].'<br>鑑別度︰'.$P_a.'<br>難度︰'.$P_b.'<br>猜測度︰'.$P_c.'<br>能力指標︰'.$P_sub.'</b></font><br /><br />';
		echo '&nbsp;'.$modify.'<br />&nbsp;'.$del.'</td></tr>';
	}

	echo "</table>";
	CloseTable();
}

function deleteEP($opt){
	global $dbh;

   
	if($opt=='deleteEP' && isset($_REQUEST['cs_id']) && isset($_REQUEST['paper_vol'])){
   	$sql = "select ready from concept_info_plus where cs_id='".$_REQUEST['cs_id']."'";
    $result = $dbh->query($sql);
    while ($data = $result->fetchRow()) {
			$ready=$data['ready'];
		}
      echo $ready;
     // debug_msg("第".__LINE__."行 data ", $data);
      if($ready==1){
         echo "<br>該單元已經上鎖，不能被修改！<br>";
         die();
      }
		$n=0;
		$sql= "select item_sn from concept_item where cs_id ='".$_REQUEST['cs_id']."' AND paper_vol='".$_REQUEST['paper_vol']."'";
		$result = $dbh->query($sql);
		while ($data = $result->fetchRow()) {
			$item_snn[$n]=$data['item_sn'];
			$n++;
		}
		for($i=0;$i<$n;$i++)
			{
			$sql="DELETE FROM concept_item WHERE cs_id='".$_REQUEST['cs_id']."' AND item_sn='".$item_snn[$i]."'";
			$result = $dbh->query($sql);
			$sql="DELETE FROM concept_item_parameter WHERE cs_id='".$_REQUEST['cs_id']."' AND item_sn='".$item_snn[$i]."'";
			$result = $dbh->query($sql);
			$sql="DELETE FROM concept_item_testlet WHERE cs_id like '".$_REQUEST['cs_id']."' AND item_sn='".$item_snn[$i]."'";
			$result = $dbh->query($sql);
			}
		$sql="DELETE FROM exam_paper WHERE cs_id='".$_REQUEST['cs_id']."' AND paper_vol='".$_REQUEST['paper_vol']."'";
		$result = $dbh->query($sql);
	}
	if($result==1){
		$ep_id=$_REQUEST['cs_id'].sprintf("%02d", $_REQUEST['paper_vol']);
		$RedirectTo="Location: modules.php?op=modload&name=ConceptStructure&file=modifyEP&del_ep_id=".$ep_id;
		
		Header($RedirectTo);
	}else{
		echo "刪除失敗！ 請洽系統管理員！";
	}

}

function AddDetailSol4EP(){
	global $dbh;
	if(isset($_REQUEST['cs_id']) and isset($_REQUEST['paper_vol'])){
      $cs_id=$_REQUEST['cs_id'];
      $paper_vol=$_REQUEST['paper_vol'];
   }else{
      $cs_id=sprintf("%03d%02d%02d%02d",$_REQUEST['unit_item'][0],$_REQUEST['unit_item'][1],$_REQUEST['unit_item'][2],$_REQUEST['unit_item'][3]);
      $paper_vol=$_REQUEST['unit_item'][4];
	}
	$ep_id=$cs_id.sprintf("%02d", $paper_vol);
	$EP_title=EPid2FullName($ep_id);

	$form = new HTML_QuickForm('frmTest','post','');

//新增答案詳解的畫面由此開始
	OpenTable();
	echo '<table width="100%" border="1" cellpadding="1" cellspacing="1" bordercolor="#0000FF" ';
	echo "<tr><td align=\"center\" colspan=\"2\"><font class=\"title\"><b>試卷名稱：".$EP_title."</b></font></td>";
	echo '</tr><tr>';
	if($_REQUEST['ShowSol']==1){
      $ShowSolHtml='<td align="left"><font class="title"><b>各題詳解內容</b></font><hr><br>';
   }
   
   
	//-- 顯示選單
   $form->addElement('header', 'myheader', '<center>&nbsp;&nbsp; 新增試題詳解 &nbsp;&nbsp;</center>');  //標頭文字
   $sql = "select * from concept_item where cs_id = '$cs_id' and paper_vol= '$paper_vol' order by item_num";
   $result = $dbh->query($sql);
   while ($data = $result->fetchRow()) {
      $item_num=$data['item_num'];
      $ItemOpContent[$data['item_num']]=explode(_SPLIT_SYMBOL, $data['op_content']);
      $ItemSN[$data['item_num']]=$data['item_sn'];
      $item_data=new ItemData($cs_id, $paper_vol, $item_num);
      $SolItem=$item_data->getSolItemData();
      $sol_pieces=$item_data->sol_pieces;  //詳解的原始檔名
      $form->addElement('header', 'myheader', '&nbsp;&nbsp;--第'.$item_num.'題--');  //分隔文字
      $ShowSolHtml.='<font class="title"><b>【'.$item_num.'】</b></font><br>';
      for($i=1;$i<=sizeof($SolItem);$i++){
         $form->addElement('file','userfile[]','選項'.$i.'說明：');  //左方新增答案詳解的欄位，
         $ShowSolHtml.='<font class="title"><b>選項('.$i.')說明</b></font>'."&nbsp;&nbsp;";
         if($sol_pieces[$i-1]==""){
            $ShowSolHtml.="：無<br><br>";
         }else{
            $ShowSolHtml.='<img border="0" src="'.$SolItem[$i].'" align="top">'."<br><br>";
         }
      }
      $TotalItemNum.=$item_num._SPLIT_SYMBOL.($i-1)._SPLIT_SYMBOL;
	}
	
	if($_REQUEST['ShowSol']==1){
	   echo $ShowSolHtml;
	   echo '</td>';
	}

	//--  檢查是否為檔案上傳狀態，並回報
	if ($form->validate() and $_REQUEST['TotalItemNum']!="") {
   	AddDetailSol4Items($ItemSN, $ItemOpContent, $cs_id, $paper_vol);
   }

   //-- 顯示檔案上傳表單
   echo '<td align="center" valign="top">';
   echo '<font color="#FF0000">★★不更改之檔案欄位請留空白！★★</font><br>';
	//$form->addElement('header', 'myheader', '&nbsp;&nbsp;☆★☆ 新增試題詳解 ☆★☆&nbsp;&nbsp;');  //標頭文字
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name','ConceptStructure');
	$form->addElement('hidden','file','creatITEM');
	$form->addElement('hidden','opt','AddDetailSol4EP');
	$form->addElement('hidden','TotalItemNum',$TotalItemNum);
   $form->addElement('hidden','opt2',$_SESSION['opt2']);
   $form->addElement('hidden','cs_id',$cs_id);
   $form->addElement('hidden','paper_vol',$paper_vol);
   //$form->addRule('userfile', '不可上傳空檔', 'uploadedfile',null,'client');
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->display();
   echo '</td></tr>';
	echo "</table>";
	CloseTable();
}


function AddDetailSol4Items($ItemSN, $ItemOpContent, $cs_id, $paper_vol){
   global $dbh;
//處理上傳之檔案
   $upload = new HTTP_Upload();
	if (PEAR::isError($upload)) die ($upload->getMessage());  //顯示錯誤訊息
	$files = $upload->getFiles();
	if (PEAR::isError($files)) die ($files->getMessage());  //顯示錯誤訊息
	$csID=explode_cs_id($cs_id);
	$mydir=_ADP_CS_UPLOAD_PATH.$csID[0]."/".$csID[1]."/".$csID[2]."/".$csID[3]."/";  //預設上傳試題之目錄
	//debug_msg("第".__LINE__."行 mydir ", $mydir);
   if(isset($_REQUEST['TotalItemNum'])){
      $TotalItemNum = explode(_SPLIT_SYMBOL, $_REQUEST['TotalItemNum']);
      array_pop($TotalItemNum);
      //debug_msg("第".__LINE__."行 TotalItemNum ", $TotalItemNum);
   }
   $ii=count($TotalItemNum);
   for($i=0;$i<$ii;$i++){
      if($i%2==0){
         $item_num[]=$TotalItemNum[$i];
      }else{
         $item_ops[]=$TotalItemNum[$i];
      }
   }

   //debug_msg("第".__LINE__."行 ItemOpContent ", $ItemOpContent);
   //debug_msg("第".__LINE__."行 ItemSN ", $ItemSN);
   //die();
	$i=0;
	$ItemOpsFlag=1;
	$op_content="";
   $ItemNumFlag=$item_num[$i];
   $ItemSnFlag=$ItemSN[$ItemNumFlag];
	foreach ($files as $file) {
		if ($file->isValid()) {
			$file->setName('uniq');
			$file_name = $file->moveTo($mydir);
			if (PEAR::isError($file_name)) die ('發生錯誤：'.$file_name->getMessage());  //顯示錯誤訊息
		}

		$prop = $file->getProp();   //取得上傳檔案之最後資訊
		$this_upload_file[]=$prop['name'];
		//debug_msg("第".__LINE__."行 prop ", $prop);
		if($prop['name']==''){      //未上傳檔案，表示原檔案不更改
         //debug_msg("第".__LINE__."行 ItemNumFlag ", $ItemNumFlag);
         //debug_msg("第".__LINE__."行 ItemOpsFlag ", $ItemOpsFlag);
         //debug_msg("第".__LINE__."行 ItemOps ", $ItemOpContent[$ItemNumFlag][$ItemOpsFlag-1]);
			$prop['name']=$ItemOpContent[$ItemNumFlag][$ItemOpsFlag-1];
		}
		$op_sol_content.=strtolower($prop['name'])._SPLIT_SYMBOL;   //取得題目之選項檔名
		
		$kk=count($this_upload_file);
      for($k=0;$k<$kk;$k++){
         $org_file = $mydir.$this_upload_file[$k];
         $low_filename = strtolower($this_upload_file[$k]);  //強制轉小寫
         $low_file = $mydir.$low_filename;
         $RenameRsu=rename($org_file, $low_file);
         if(!$RenameRsu){
            die(__LINE__."改檔名錯誤！");
         }
   	}
   	//debug_msg("第".__LINE__."行 ItemOpsFlag ", $ItemOpsFlag);
   	//debug_msg("第".__LINE__."行 item_ops ", $item_ops);
   	//die();
   	
      if($ItemOpsFlag==$item_ops[$i]){
         //debug_msg("第".__LINE__."行 op_sol_content ", $op_sol_content);
         //資料庫更新
         $table_name   = 'concept_item';
         $table_values = array(
            'op_content' => $op_sol_content
         );
         $table_field=' item_sn ='.$ItemSnFlag;
         $result = $dbh->autoExecute($table_name, $table_values,
                        DB_AUTOQUERY_UPDATE, $table_field);
         //debug_msg("第".__LINE__."行 table_field ", $table_field);
         //debug_msg("第".__LINE__."行 result ", $result);
         
         if($result!=1){
            die(__LINE__."行，錯誤發生");
         }

         //相關指標重新計數
         $ItemOpsFlag=1;
         $i++;
         $ItemNumFlag=$item_num[$i];
         $ItemSnFlag=$ItemSN[$ItemNumFlag];
         $op_sol_content="";
      }else{
			$ItemOpsFlag++;
      }
   }
}

//require_once "feet.php"; 
?>
