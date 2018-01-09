<?php
require_once "HTML/QuickForm.php";
require_once 'HTTP/Upload.php';
require_once "include/adp_API.php";

IMPORT_CREATITEM_table_header();
$OpenedCS=getCSlicense($user_data->user_id);

//-- 顯示主畫面上方子選單
//CS_ITEM_table_header();

?>
<br>
<table width="95%" border="1" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="center" bordercolor="#FFCC33"><?php showEP_msg($_REQUEST['opt']); ?><br>
	<?php creatEP(); ?></td>
  </tr>
</table>

<?php


function creatEP(){
	global $dbh, $OpenedCS;
	
	$form = new HTML_QuickForm('frmTest','post','');

	//-- 尋找目前已建立之單元結構，並初始化"關聯選單"
	/* $select1[0]='版本';
	$select2[0][0]='科目';
	$select3[0][0][0]='冊別';
	$select4[0][0][0][0]='單元【單元名稱】';
	$select5[0][0][0][0][0]='卷別'; */

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
         $sql2="select paper_vol from exam_paper where cs_id='".$row['cs_id']."' order by paper_vol DESC";
         $result2 =$dbh->limitQuery($sql2, 0, 1);
         if($row2=$result2->fetchRow()){
            $select5[$row['publisher_id']][$row['subject_id']][$row['vol']][$row['unit']][$row2['paper_vol']+1]='卷'.($row2['paper_vol']+1);
         }else{
            $select5[$row['publisher_id']][$row['subject_id']][$row['vol']][$row['unit']][1]='卷1';
         }
      }
	}


	//-- 顯示選單
	echo " 新增試卷<br>";
	$form->addElement('header', 'myheader', '※※若以下選單無法選擇，表示您尚未建立任何單元結構！');  //標頭文字
	// Create the Element
	$sel =& $form->addElement('hierselect', 'unit_ep', '請新增試卷：');

	// And add the selection options
	$sel->setOptions(array($select1, $select2, $select3, $select4, $select5));

	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name','ConceptStructure');
	$form->addElement('hidden','file','creatEP');
	$form->addElement('hidden','opt','creatEP');
	$form->addElement("checkbox","sp_add","特殊選項：","新增完，接著出題","1"); 
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->display();

}

function showEP_msg($opt){
	global $dbh;
	
	$cs_id=sprintf("%03d%02d%02d%02d",$_REQUEST['unit_ep'][0],$_REQUEST['unit_ep'][1],$_REQUEST['unit_ep'][2],$_REQUEST['unit_ep'][3]);   //不管如何，cs_id至少會是'000000000'
	$paper_vol=$_REQUEST['unit_ep'][count($_REQUEST['unit_ep'])-1];
	if($opt='creatEP' && $cs_id!='000000000'){	
		   //unit_ep陣列的最後一個元素
		$query = 'INSERT INTO exam_paper (cs_id, paper_vol) VALUES (?,?)';
		$data = array($cs_id, $paper_vol);
		$result =$dbh->query($query, $data);
		if($result){
			$paper_title=id2publisher($_REQUEST['unit_ep'][0])."-".id2subject($_REQUEST['unit_ep'][1])."-第".$_REQUEST['unit_ep'][2]."冊第".$_REQUEST['unit_ep'][3]."單元(".$_REQUEST['unit_ep'][4].")卷".$paper_vol;
			$Msg= $paper_title."，新增成功！" ;
			if($_REQUEST['sp_add']=="1"){  //直接新增試題
				for($i=0;$i<count($_REQUEST['unit_ep']);$i++){
					$_SESSION['pass_new_item'][$i]=$_REQUEST['unit_ep'][$i];
				}
				$RedirectTo="Location: modules.php?op=modload&name=ConceptStructure&file=creatITEM&opt=creatITEM";
				Header($RedirectTo);
			}
		}else{
			$Msg="新增失敗，請洽系統管理者！";
		}
		echo "$Msg<hr>";
	}

}




//require_once "feet.php"; 
?>

