<?php
require_once "HTML/QuickForm.php";
require_once 'HTTP/Upload.php';
require_once 'Date.php';
require_once "include/adp_API.php";

$module_name = basename(dirname(__FILE__));
$SubmitFile=basename(__FILE__);
$SubmitFile=str_replace(".php", "", $SubmitFile);
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
        $RedirectTo="Location: modules.php?op=modload&name=".$module_name."&file=ctrlEP_subitem";
	      //Header($RedirectTo);
        }
        /*echo '1'.$_REQUEST['opt'].'<br>';
        echo '2'.$_REQUEST['class'].'<br>';
        echo '3'.$_REQUEST['EPids'].'<br>'; 
        echo '4'.$_REQUEST['OpenEPids'].'<br>';*/
echo   '</td>
      </tr>
     </table><br>';

if ($_POST['import_user']=='yes') {

	$ep_id = $_POST['ep_id'];
	$sub_item='';
	for($i=0;$i<$_POST['sub_num'];$i++){
		 $sub_temp[$i]="sub-$i";
		 //echo $_POST["$sub_temp[$i]"]."<BR>";
		if($i<($_POST['sub_num']-1)){
			$sub_item=$sub_item.$_POST["$sub_temp[$i]"]._SPLIT_SYMBOL;
		}else{
		 	$sub_item=$sub_item.$_POST["$sub_temp[$i]"];
		}
	}

	$sql = "UPDATE exam_paper_subscale SET sub_test_num = '$sub_item' where exam_paper_id= '".$ep_id."' ";
	$result = $dbh->query($sql);

}

if ($_POST['import_user']=='yess') {

	$ep_id = $_POST['ep_id'];
	$sub_item='';
	for($i=0;$i<$_POST['sub_num'];$i++)
		{
		 $sub_temp[$i]="sub-$i";
		 //echo $_POST["$sub_temp[$i]"]."<BR>";
		 if($i<($_POST['sub_num']-1))
		 	$sub_item=$sub_item.$_POST["$sub_temp[$i]"]._SPLIT_SYMBOL;
		 else
		 	$sub_item=$sub_item.$_POST["$sub_temp[$i]"];
		}

	$query = 'INSERT INTO exam_paper_subscale (sub_test_num, exam_paper_id) VALUES (?,?)';
	$data = array($sub_item, $ep_id);
	$result =$dbh->query($query, $data);


}



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
	$form->addElement('header', 'myheader', '<center> 試卷列表 【能力指標出題數】</canter>');  //標頭文字
	// Create the Element
	$sel =& $form->addElement('hierselect', 'class', '請選擇試卷：');
	// And add the selection options
	$sel->setOptions(array($select1, $select2, $select3, $select4, $select5));
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file',$SubmitFile);
	$form->addElement('hidden','opt','EP2class');
	$form->addRule('class', '「班級」不可空白！', 'nonzero',  null, 'client', null, null);
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->display();
}



function EP2class($class){
	global $dbh , $module_name, $SubmitFile;

	$listCLASS='<a href="modules.php?op=modload&name='.$module_name.'&file=ctrlEP_subitem">選擇其他班級</a>';
	echo '<br>現在的試卷是：'.id2publisher($class[0]).'&nbsp;'.id2subject($class[1]).'&nbsp;&nbsp;'.'第'.$class[2].'冊'.'第'.$class[3].'單元&nbsp;'.'卷'.$class[4].'&nbsp;&nbsp;&nbsp;&nbsp;<hr>';

	$ep_id = get_epid($class[0],$class[1],$class[2],$class[3],$class[4]);
	$cs_id=EPid2CSid($ep_id);
	$sql = "select sub_test_num from exam_paper_subscale WHERE exam_paper_id = '$ep_id' ";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow())
		{
		 $sub_test_num=$row['sub_test_num'];
		}
	if(isset($sub_test_num))
		{
		$sql = "select sub, sub_score_name from concept_info_dim WHERE cs_id = '$cs_id' ";
		$result =$dbh->query($sql);
		while ($row=$result->fetchRow()){
			$sub_score_name=$row['sub_score_name'];
			$sub=$row['sub'];
		}
		$sub_test_num=explode(_SPLIT_SYMBOL,$sub_test_num);
		$sub_score_name=explode(_SPLIT_SYMBOL,$sub_score_name);
		//debug_msg(__LINE__."行  sub_test_num ", $sub_test_num);

		if(sizeof($sub_score_name)!=$sub){
			echo "能力指標數量錯誤";
			die();
		}
		echo '<br>';
		for($i=1;$i<=$sub;$i++){
			$sub_2[$i-1]="sub-".($i-1);
			$sql2 = "select count(item_sn) from concept_item_parameter WHERE sub = '$i' AND cs_id = '$cs_id'";
			$ItemNumsInEachSub[$i] =& $dbh->getOne($sql2);
		}
		//debug_msg(__LINE__."行  ItemNumsInEachSub ", $ItemNumsInEachSub);
		$sub_1=$sub+1;
		echo '<table align="center" border="1" cellpadding="0" cellspacing="0">';
		echo '<tr height="30" bgcolor="#CCCCCC"><td colspan='.$sub_1.' align="center">目前各能力指標出題數</td></tr>';
		echo '<tr height="30"><td align="center" bgcolor="#CCCCCC">　能力指標　</td>';
//debug_msg(__LINE__."行  sub_test_num", $sub_test_num);
//debug_msg(__LINE__."行  sub_score_name", $sub_score_name);
//debug_msg(__LINE__."行  sub_2", $sub_2);
		for($i=0;$i<$sub;$i++)
			{
			 echo '<td align="center">'."\n";
			 echo " ".$sub_score_name[$i]." "."\n";
			 echo '</td>'."\n";
			}
		echo '</tr><tr height="30">'."\n";
		echo '<td align="center" bgcolor="#CCCCCC">出題數</td>'."\n";
		for($i=0;$i<$sub;$i++)
			{
			 echo '<td align="center">';
			 echo $sub_test_num[$i];
			 echo '</td>';
			}
		echo '</tr></table><br><br>';
		echo '<form name=sub_ck action='.$_SERVER['PHP_SELF'].' method=POST >';
		echo '<table align="center" border="1" cellpadding="0" cellspacing="0">';
		echo '<tr height="30"><td align="center" bordercolor="#FFCC33" bgcolor="#CCCCCC">　能力指標　</td><td align="center" bgcolor="#CCCCCC">　出題數　</td><td align="center" bgcolor="#CCCCCC">　<font color=red>允許出題數</font>　</td></tr>';
		for($i=0;$i<$sub;$i++)
			{
			 echo '<tr height="30"><td align="center">　'.$sub_score_name[$i].'　</td>
			 	   <td align="center" bordercolor="#FFCC33"><input type="text" name='.$sub_2[$i].' value="'.$sub_test_num[$i].'" size=5></td>
					<td align="center" bordercolor="#FFCC33"><font color=red>'.$ItemNumsInEachSub[$i+1].'</font></td></tr>'."\n";
			}
			//<input type="text" name='.$sub_2.'>
		echo '<input type="hidden" name="ep_id" value='.$ep_id.'>'."\n";
		echo '<input type="hidden" name="op" value=modload>'."\n";
		echo '<input type="hidden" name="name" value='.$module_name.'>'."\n";
		echo '<input type="hidden" name="sub_num" value='.$sub.'>'."\n";
		echo '<input type="hidden" name="file" value='.$SubmitFile.'>'."\n";
		echo '<input type="hidden" name="import_user" value=yes>'."\n";
		echo '<tr height="30"><td>　</td><td align="center"><input type="submit" name="Submit" value="送出"></td></tr>'."\n";
		echo '</table></form>'."\n";	 
			
		}
	else
		{
		echo "尚無各能力指標出題數量之結構<BR><BR>";
	 	$sql = "select sub, sub_score_name from concept_info_dim WHERE cs_id = '$cs_id' ";
		$result =$dbh->query($sql);
		while ($row=$result->fetchRow()){
			$sub_score_name=$row['sub_score_name'];
			$sub=$row['sub'];
		}
		$sub_score_name=explode(_SPLIT_SYMBOL,$sub_score_name);
		for($i=0;$i<$sub;$i++)
			{
			 $sub_2[$i]="sub-$i";
			 //echo $sub_2[$i];
			}
		if(sizeof($sub_score_name)!=$sub)
			{
			 echo "能力指標數量錯誤";
			 die();
			}
		echo '<form name=sub_ck action='.$_SERVER['PHP_SELF'].' method=POST >';
		echo '<table align="center" border="1" cellpadding="0" cellspacing="0">';
		echo '<tr height="30"><td align="center" bordercolor="#FFCC33">　能力指標　</td><td align="center">　出題數　</td></tr>'."\n";
		for($i=0;$i<$sub;$i++)
			{
			 echo '<tr height="30"><td align="center">　'.$sub_score_name[$i].'　</td>
			 	   <td align="center" bordercolor="#FFCC33"><input type="text" name='.$sub_2[$i].' size=5></td></tr>'."\n";
			}
			//<input type="text" name='.$sub_2.'>
		echo '<input type="hidden" name="ep_id" value='."$ep_id".'>'."\n";
		echo '<input type="hidden" name="op" value=modload>'."\n";
		echo '<input type="hidden" name="name" value='."$module_name".'>'."\n";
		echo '<input type="hidden" name="sub_num" value='."$sub".'>'."\n";
		echo '<input type="hidden" name="file" value='.$SubmitFile.'>'."\n";
		echo '<input type="hidden" name="import_user" value=yess>'."\n";
		echo '<tr height="30"><td>　</td><td align="center"><input type="submit" name="Submit" value="送出"></td></tr>'."\n";
		echo '</table></form>';	
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
