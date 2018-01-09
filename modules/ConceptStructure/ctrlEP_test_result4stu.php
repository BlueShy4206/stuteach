<?php
require_once "HTML/QuickForm.php";
require_once 'HTTP/Upload.php';
require_once 'Date.php';
require_once "include/adp_API.php";

$module_name = basename(dirname(__FILE__));

//匯入試題結構
//require_once "modules/".$module_name."/html_title.php";
IRT_result_table_header($module_name);

//IMPORT_ITEM_table_header();

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
        $RedirectTo="Location: modules.php?op=modload&name=".$module_name."&file=ctrlEP_test_result4stu";
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
	global $dbh, $module_name, $user_data;
	//echo $dbh;
	//echo $module_name;
	$form = new HTML_QuickForm('frmadduser','post',$_SERVER['PHP_SELF']);
	//-- 尋找目前已建立之班級，並初始化"關聯選單"
	$select1[0]='學校';
	$select2[0][0]='科系';
	$select3[0][0][0]='年級';
	$select4[0][0][0][0]='班級';
	$select5[0][0][0][0][0]='學生';
	$select6[0][0][0][0][0][0]='試卷';

	if($user_data->access_level>70){
		$sql = "select user_info.city_code, user_info.organization_id, user_info.grade, user_info.class from user_info,exam_record_irt  
			WHERE exam_record_irt.user_id=user_info.user_id GROUP BY city_code, organization_id, grade, class";
	}elseif($user_data->access_level>20){
		$sql = "select user_info.city_code, user_info.organization_id, user_info.grade, user_info.class from user_info,exam_record_irt, user_status   
			WHERE exam_record_irt.user_id=user_info.user_id AND user_status.user_id=user_info.user_id AND user_status.access_level<".$user_data->access_level." GROUP BY city_code, organization_id, grade, class";
	}else{
		die('無使用權限');
	}
	//debug_msg("第".__LINE__."行 sql ", $sql);
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow())
		{
		 $cc=$row['city_code'];
	   	 $oi=$row['organization_id'];
	   	 $gr=$row['grade'];
	   	 $cl=$row['class'];
	   	 $select1[$cc]=id2city($cc);
	   	 $select2[$cc][$oi]=id2org($oi);
	   	 $select3[$cc][$oi][$gr]="$gr 年";
	   	 $select4[$cc][$oi][$gr][$cl]="$cl 班";
	   	 $sql2 = "select * from user_info WHERE organization_id='$oi' AND grade='$gr' AND class='$cl' ORDER BY user_id";
	   	 $result2 =$dbh->query($sql2);
	   	 while ($row2=$result2->fetchRow())
			  {
	   			$uid=$row2['user_id'];
	   			$un=$row2['uname'];
	   			$select5[$cc][$oi][$gr][$cl][$uid]=$uid.'-'.$un;
	   			$sql3 = "select distinct cs_id, paper_vol from exam_record_irt WHERE user_id='$uid' ORDER BY cs_id,paper_vol";
	   	 		$result3 =$dbh->query($sql3);
	   			 while ($row3=$result3->fetchRow())
			  		{
			  		  $cs_id=$row3['cs_id'];
	   				  $paper_info=explode_cs_id($row3['cs_id']);
	  				  $cc1=intval($paper_info[0]);
					  $oi1=intval($paper_info[1]);
					  $gr1=intval($paper_info[2]);
					  $cl1=intval($paper_info[3]);
					  $rl1=intval($row3['paper_vol']);
					  $ep_id=get_epid($cc1,$oi1,$gr1,$cl1,$rl1);
					  $paper_info_1=id2publisher($cc1).id2subject($oi1)."第".$gr1."冊"."第".$cl1."單元"."卷".$rl1;
	   				  $select6[$cc][$oi][$gr][$cl][$uid][$ep_id]=$paper_info_1;
	   		  		}
	   		  }
		}

	//-- 顯示選單
	//echo "☆★☆ 班級列表 ☆★☆<br>";
	$form->addElement('header', 'myheader', '<center> 學生列表 【各題作答反應】</canter>');  //標頭文字
	// Create the Element
	$sel =& $form->addElement('hierselect', 'class', '請選擇班級：');
	// And add the selection options
	$sel->setOptions(array($select1, $select2, $select3, $select4, $select5, $select6));
	
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','uname',$un);
	$form->addElement('hidden','file','ctrlEP_test_result4stu');
	$form->addElement('hidden','opt','EP2class');
	$form->addRule('class', '「班級」不可空白！', 'nonzero',  null, 'client', null, null);
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->display();
}



function EP2class($class){
	global $dbh , $module_name;
	
	$ep_id=$class[5];
	$paper_info=explode_ep_id($ep_id);
	$cc1=intval($paper_info[0]);
	$oi1=intval($paper_info[1]);
	$gr1=intval($paper_info[2]);
	$cl1=intval($paper_info[3]);
	$rl1=intval($paper_info[4]);
	$paper_info_1=id2publisher($cc1).id2subject($oi1)."第".$gr1."冊"."第".$cl1."單元"."卷".$rl1;
	
	$listCLASS='<a href="modules.php?op=modload&name='.$module_name.'&file=ctrlEP_test_result4stu">選擇其他班級</a>';
	echo '<br>現在的班級是：'.id2city($class[0]).'&nbsp;'.id2org($class[1]).'&nbsp;&nbsp;'.$class[2].'年'.'&nbsp;'.$class[3].'班&nbsp;&nbsp;&nbsp;&nbsp;'.$class[4].'-'.id2uname($class[4]).'&nbsp;&nbsp;&nbsp;'.$paper_info_1.'&nbsp;<hr>';

	$cs_id=get_csid($cc1,$oi1,$gr1,$cl1);
	$sql = "select select_item_id_A, org_res, binary_res, date, total_items from exam_record_irt WHERE user_id='".$class[4]."' and cs_id='".$cs_id."' and paper_vol='".$rl1."' ";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow())
		{
		 $select_item = $row['select_item_id_A'];
		 $org_res = $row['org_res'];
		 $binary_res = $row['binary_res'];
		 $date = $row['date'];
		 $total_items= $row['total_items'];
		}
//	echo $select_item."<br>".$org_res."<br>".$binary_res."<br>".$date."<br>".$total_items;
	
	$select_item=explode("@XX@",$select_item);
	$org_res=explode(_SPLIT_SYMBOL,$org_res);
	$binary_res=explode(_SPLIT_SYMBOL,$binary_res);
	for($i=0;$i<$total_items;$i++)
		{
		$sql= "select item_num from concept_item WHERE item_sn='".$select_item[$i]."' and cs_id='".$cs_id."' ";
		$result =$dbh->query($sql);
		while ($row=$result->fetchRow())
			{
			 $item_num[$i]=$row['item_num'];
			}
		}

	echo '<table align="center" border="1" cellpadding="0" cellspacing="0">';
	echo '<tr align="center" valign="top" ><td>　施測順序　</td><td>　題號　</td><td>　原始作答反應　</td><td>　二元作答反應　</td><td>　作答時間　</td></tr>';
	echo "<tr align=center valign=top ><td>1</td><td>".$item_num[0]."</td><td>".$org_res[0]."</td><td>".$binary_res[0]."</td><td rowspan=$total_items>".$date."</td></tr>";
	for($i=1;$i<$total_items;$i++)
		{
		 $j=$i+1;
		 echo "<tr align=center valign=top ><td>".$j."</td><td>".$item_num[$i]."</td><td>".$org_res[$i]."</td><td>".$binary_res[$i]."</td></tr>";
		}
	echo '</table><br>';

}





?>
