<?php
require_once "HTML/QuickForm.php";
require_once 'HTTP/Upload.php';
require_once 'Date.php';
require_once "include/adp_API.php";

$module_name = basename(dirname(__FILE__));


require_once "modules/".$module_name."/html_title.php";
IRT_result_table_header($module_name);

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
        $RedirectTo="Location: modules.php?op=modload&name=".$module_name."&file=ctrlEP_test_result";
	      Header($RedirectTo);
        }
        /*echo '1'.$_REQUEST['opt'].'<br>';
        echo '2'.$_REQUEST['class'].'<br>';
        echo '3'.$_REQUEST['EPids'].'<br>'; 
        echo '4'.$_REQUEST['OpenEPids'].'<br>';*/
echo   '</td>
      </tr>
     </table><br>';

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
	
	$sql = "SELECT distinct cs_id, paper_vol FROM exam_paper_access_irt WHERE (cs_id != -1) ORDER BY cs_id, paper_vol ASC";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$paper_info=explode_cs_id($row['cs_id']);
		$paper_vol = $row['paper_vol'];
	  $cc=intval($paper_info[0]);
	  $oi=intval($paper_info[1]);
	  $gr=intval($paper_info[2]);
	  $cl=intval($paper_info[3]);
	  $rl=intval($paper_vol);
		$select1[$cc]=id2publisher($cc);
		$select2[$cc][$oi]=id2subject($oi);
		$select3[$cc][$oi][$gr]="第".$gr."冊";
		$select4[$cc][$oi][$gr][$cl]="第".$cl."單元";
		$select5[$cc][$oi][$gr][$cl][$rl]="卷".$rl;
		
	}

	//-- 顯示選單
	//echo "☆★☆ 班級列表 ☆★☆<br>";
	$form->addElement('header', 'myheader', '<center> 試卷列表 【各題作答狀況】</canter>');  //標頭文字
	// Create the Element
	$sel =& $form->addElement('hierselect', 'class', '請選擇試卷：');
	// And add the selection options
	$sel->setOptions(array($select1, $select2, $select3, $select4, $select5));
	
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file','ctrlEP_test_result');
	$form->addElement('hidden','opt','EP2class');
	$form->addRule('class', '「班級」不可空白！', 'nonzero',  null, 'client', null, null);
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->display();
}



function EP2class($class){
	global $dbh , $module_name;

	$listCLASS='<a href="modules.php?op=modload&name='.$module_name.'&file=ctrlEP_test_result">選擇其他班級</a>';
	echo '<br>現在的試卷是：'.id2publisher($class[0]).'&nbsp;'.id2subject($class[1]).'&nbsp;&nbsp;'.'第'.$class[2].'冊'.'第'.$class[3].'單元&nbsp;'.'卷'.$class[4].'&nbsp;&nbsp;&nbsp;&nbsp;<hr>';
	
	$ep_id = get_epid($class[0],$class[1],$class[2],$class[3],$class[4]);
	$cs_id = get_csid($class[0],$class[1],$class[2],$class[3]);
	$testnum=0;
	$sql = "select item_num, op_ans from concept_item WHERE exam_paper_id = '$ep_id' ORDER BY item_num ASC ";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow())
		{
		 $testnum++;
		 $item_num[$testnum]=$row['item_num'];
		 $op_ans[$testnum]=$row['op_ans'];
		}
	if(isset($item_num[$testnum]))
		{
	  	 $sql = "select select_item_id_A, org_res, total_items from exam_record_irt WHERE cs_id = '$cs_id' and paper_vol = '$class[4]' ";
		 $result =$dbh->query($sql);
		 $s=0;
		 while ($row=$result->fetchRow())
			{
			 //debug_msg("第".__LINE__."行 data ", $row['select_item_id_A']);
			 $s++;
			 $total_items =$row['total_items'];
		 	 $select_item_id_A = explode("@XX@",$row['select_item_id_A']);
		 	 $org_res = explode("@XX@",$row['org_res']);
		 	 for($i=0;$i<$total_items;$i++)
		 	 	{
				 $sqll = "select item_num from concept_item WHERE item_sn = '".$select_item_id_A[$i]."'  ";
			   	 $resultt =$dbh->query($sqll);
				 while ($roww=$resultt->fetchRow())
					{
					  $the_item_num=$roww['item_num'];
					  $ans[$the_item_num][$org_res[$i]]++;
					} 
				}
			}

//	debug_msg("第".__LINE__."行 InputArray ", $num);
		echo '<table align="center" border="1" cellpadding="0" cellspacing="0">';
		echo '<tr align="center" valign="top" bordercolor="#FFCC33" ><td></td><td>　選項 1　</td><td>　選項 2　</td><td>　選項 3　</td><td>　選項 4　</td><td>　正確答案　</td><td>　答對率　</td></tr>';
		for($i=1;$i<=$testnum;$i++)
			{
			 echo '<tr align="center" valign="top" bordercolor="#FFCC33"><td>';

			 echo "　第 ".$item_num[$i]." 題　";
			 echo '</td>';
			 echo '<td>';
			 if(empty($ans[$i][1]))
			 	{
			 	 echo "0"." 人";
			 	}
			 else
			 	{
				 echo $ans[$i][1]." 人"; 
				}
			 echo '</td>';
			 echo '<td>';
			 if(empty($ans[$i][2]))
			 	{
			 	 echo "0"." 人";
			 	}
			 else
			 	{
				 echo $ans[$i][2]." 人"; 
				}
			 echo '</td>';
			 echo '<td>';
			 if(empty($ans[$i][3]))
			 	{
			 	 echo "0"." 人";
			 	}
			 else
			 	{
				 echo $ans[$i][3]." 人"; 
				}
			 echo '</td>';
			 echo '<td>';
			 if(empty($ans[$i][4]))
			 	{
			 	 echo "0"." 人";
			 	}
			 else
			 	{
				 echo $ans[$i][4]." 人"; 
				}
			 echo '</td>';
			 echo '<td>';
			 echo "　".$op_ans[$i]."　";
			 echo '</td>';
			 echo '<td>';
			 $ans_sum = $ans[$i][1]+$ans[$i][2]+$ans[$i][3]+$ans[$i][4];
			 if (empty($ans_sum))
			 	{
			 	 echo "0"."%";
			 	}
			 else
			 	{
			 	 echo round($ans[$i][$op_ans[$i]]/($ans_sum)*100,2)."%";
			 	}
			 echo '</td></tr>';
			}
		echo '</table><br><br>';	
	
		}
	else
		{
	 	echo "尚無題組結構";
		}

}





?>
