<?php
require_once "HTML/QuickForm.php";
require_once 'HTTP/Upload.php';
require_once 'Date.php';
require_once "include/adp_API.php";

$module_name = basename(dirname(__FILE__));
$file = basename(__FILE__);
list($SubmitFile, $FileType)=explode(".", $file);

//匯入試題結構
//require_once "modules/".$module_name."/html_title.php";
//IRT_result_table_header($module_name);
//require_once "modules/".$module_name."/html_title.php";
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
	       EP2class($_REQUEST['class'], $_REQUEST['course']);
        }elseif(isset($_REQUEST['opt'])){
        //預設值（刪除或新增的功能選錯）
        $RedirectTo="Location: modules.php?op=modload&name=".$module_name."&file=ctrlEP_test_result_downclass";
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
	global $dbh, $module_name, $SubmitFile;
	//echo $dbh;
	//echo $module_name;
	$form = new HTML_QuickForm('frmadduser','post',$_SERVER['PHP_SELF']);
	//-- 尋找目前已建立之班級，並初始化"關聯選單"
	$select1[0]='版本';
	$select2[0][0]='科目';
	$select3[0][0][0]='冊';
	$select4[0][0][0][0]='單元';
	$select5[0][0][0][0][0]='卷';

	$sql = "SELECT distinct cs_id FROM exam_record_irt WHERE (cs_id != -1) ORDER BY cs_id ASC";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$paper_info=explode_ep_id($row['cs_id']);
	    $cc=intval($paper_info[0]);
	    $oi=intval($paper_info[1]);
	    $gr=intval($paper_info[2]);
	    $cl=intval($paper_info[3]);
	    $sql1 = "SELECT paper_vol FROM exam_record_irt WHERE (cs_id = '".$row["cs_id"]."') ORDER BY paper_vol ASC";
		$result1 =$dbh->query($sql1);
		//echo $sql1;
		while ($row1=$result1->fetchRow())
			{
			 $rl=intval($row1['paper_vol']);
			 $select1[$cc]=id2publisher($cc);
			 $select2[$cc][$oi]=id2subject($oi);
			 $select3[$cc][$oi][$gr]="第".$gr."冊";
		 	 $select4[$cc][$oi][$gr][$cl]="第".$cl."單元";
			 $select5[$cc][$oi][$gr][$cl][$rl]="卷".$rl;
			}
	}

	$se1[0]='所有學年度';
	$se2[0][0]='所有學期';
	$se3[0][0][0]='所有課程';

	$sql = "select * from course GROUP BY year,seme,name ";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$ye=$row['year'];
		$se=$row['seme'];
		$name=$row['name'];
		$cid=$row['course_id'];

		$se1[$ye]=$ye."學年度";
		$se2[$ye][$se]="第".$se."學期";
		$se3[$ye][$se][$cid]=$name;
	}

	//-- 顯示選單
	//echo "☆★☆ 班級列表 ☆★☆<br>";
	$form->addElement('header', 'myheader', '<center> 試卷列表 【CSV下載】</canter>');  //標頭文字
	// Create the Element
	$sel =& $form->addElement('hierselect', 'class', '請選擇試卷：');
	// And add the selection options
	$sel->setOptions(array($select1, $select2, $select3, $select4, $select5));
	$sel =& $form->addElement('hierselect', 'course', '請選擇課程：');
	$sel->setOptions(array($se1, $se2, $se3));
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file',$SubmitFile);
	$form->addElement('hidden','opt','EP2class');
	$form->addRule('class', '「班級」不可空白！', 'nonzero',  null, 'client', null, null);
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->display();
}



function EP2class($class, $course){
	global $dbh , $module_name, $SubmitFile;

	$paper_info_1=id2publisher($class[0]).' '.id2subject($class[1]).' '."第".$class[2]."冊 "."第".$class[3]."單元 "."卷".$class[4];
	echo '現在的試卷是：'.$paper_info_1;
	$cs_id=get_csid($class[0],$class[1],$class[2],$class[3]);
	$paper_vol=$class[4];
	$ep_id=get_epid($class[0],$class[1],$class[2],$class[3],$class[4]);
	
	$csv_file=$ep_id.'_'.sprintf("%02d",rand(0,99)).'res.csv';
	$csv_file_loc=_ADP_TMP_UPLOAD_PATH.$csv_file;
	$base='<a href="modules.php?op=modload&name='.$module_name.'&file=download2';
	echo "<br>";
	$csv_url='【'.$base.'" target="blank">下載試卷作答反應之csv檔</a>】';
	echo $csv_url."<br>";
	
	$sql = "select exam_sn, user_id, select_item_id_A, org_res, binary_res, date, total_items from exam_record_irt WHERE cs_id='".$cs_id."' and paper_vol='".$paper_vol."' ";
	if($course[2]!=0){
		$sql.=" AND course_id='$course[2]'";
	}
	$result =$dbh->query($sql);
	$Mcount=0;
	while ($row=$result->fetchRow())
		{
		 $sn = $row['exam_sn'];
		 $user_id = $row['user_id'];
		 $select_item = $row['select_item_id_A'];
		 $org_res = $row['org_res'];
		 $binary_res = $row['binary_res'];
		 $date = $row['date'];
		 $total_items= $row['total_items'];
		 if($Mcount==0)
		 	{
		 	 $sql2 = "select max(item_length) from exam_paper_access_irt WHERE cs_id='".$cs_id."' and paper_vol='".$paper_vol."' ";
		     $result2 =$dbh->query($sql2);
		     while ($row2=$result2->fetchRow())
		 	 	{
		 	 	 $item_length=$row2['max(item_length)'];
		 	 	}
			 if(isset($str)){	unset($str);	}
			 $str='流水號,試卷,學校,科系,班級,帳號,姓名,作答題數,作答日期,作答時間,';
			 for($i=1;$i<=$item_length;$i++){ $str.="題號$i,"; }
			 for($i=1;$i<=$item_length;$i++){ $str.="原始作答反應$i,"; }
			 for($i=1;$i<=$item_length;$i++){ $str.="二元作答反應$i,"; }
			}
		 $sql1 = "select uname, organization_id, city_code, grade, class from user_info WHERE user_id='".$user_id."' ";
		 $result1 =$dbh->query($sql1);
		 while ($row1=$result1->fetchRow())
		 	{
			 $uname = $row1['uname'];
			 $organization_id = id2org($row1['organization_id']);
			 $city_code = id2city($row1['city_code']);
			 $grade = $row1['grade'];
			 $class = $row1['class'];
			}
		 $org_res_1 = explode(_SPLIT_SYMBOL,$org_res);
		 $binary_res_1 = explode(_SPLIT_SYMBOL,$binary_res);
		 $select_item_1 = explode(_SPLIT_SYMBOL,$select_item);
		 for($i=0;$i<$item_length;$i++)
		 	{
		 	 if($i<$total_items)
		 	 	{
			 	 $org_res_2 .= ",".$org_res_1[$i];
			 	 $binary_res_2 .= ",".$binary_res_1[$i];
			 	 $select_item_2 .= ",".$select_item_1[$i];
			 	}
			 else
			 	{
			 	 $org_res_2 .= ",";
			 	 $binary_res_2 .= ",";
			 	 $select_item_2 .= ",";
			 	}
		 	}
		 $csv_content[$Mcount]=$sn.",".$paper_info_1.",".$city_code.",".$organization_id.",".$grade.'年'.$class.'班,'.$user_id.",".$uname.",".$total_items.",".$date.$select_item_2.$org_res_2.$binary_res_2;
		 unset($org_res_2);
		 unset($binary_res_2);
		 unset($select_item_2);
		 $Mcount++;
		}
	$_SESSION['dfn']=$csv_file;
	creat_csv($csv_file, $str, $csv_content);
		

}





?>
