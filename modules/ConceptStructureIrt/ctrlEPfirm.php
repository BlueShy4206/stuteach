<?php
//階梯系統專用
require_once "HTML/QuickForm.php";
require_once "include/adp_API.php";

$module_name = basename(dirname(__FILE__));


OpenTable();
echo "<td><center><font class=\"title\"><b>試卷存取控制</b></font></center></td>";
CloseTable();
//-- 顯示主畫面
echo '<br>
	<table width="95%" border="1" cellpadding="0" cellspacing="0">
		<tr>
		<td align="center" valign="top" bordercolor="#FFCC33">';
		listCLASS();
echo '</td></tr></table>';
echo '<br>
	<table width="95%" border="1" cellpadding="0" cellspacing="0">
		<tr>
		<td align="center" valign="top" bordercolor="#FFCC33">';
if($_REQUEST['opt']=='EP2class'){
	EP2class($_REQUEST['class']);
}elseif($_REQUEST['opt']=='EPadd2class' && $_REQUEST['EPids']!=''){
	EPadd2class($_REQUEST['EPids']);
}elseif($_REQUEST['opt']=='delClassEP' && $_REQUEST['OpenEPids']!=''){
	class_delEP($_REQUEST['OpenEPids']);
}elseif(isset($_REQUEST['opt'])){
   //預設值（刪除或新增的功能選錯）
   $RedirectTo="Location: modules.php?op=modload&name=".$module_name."&file=ctrlEPfirm";
	Header($RedirectTo);
}

echo '</td></tr></table>';


function listCLASS(){
	global $dbh, $module_name;
	
	$form = new HTML_QuickForm('frmadduser','post',$_SERVER['PHP_SELF']);
	//-- 尋找目前已建立之班級，並初始化"關聯選單"
	$select1[0]='縣市';
	$select2[0][0]='補習班名稱';
	$select3[0][0][0]='年級';
	//$select4[0][0][0][0]='班級';
	
	$sql = "select city_code, firm_id from firm GROUP BY city_code, firm_id";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$cc=$row['city_code'];
		$fi=$row['firm_id'];
		$gr=$row['grade'];
		//$cl=$row['class'];
	
		$select1[$cc]=id2city($cc);
		$select2[$cc][$fi]=id2firm($fi);
		for($i=1;$i<=6;$i++){
			$select3[$cc][$fi][$i]=$i."年級";
		}
	}

	//-- 顯示選單
	//echo "☆★☆ 班級列表 ☆★☆<br>";
	$form->addElement('header', 'myheader', '<center> 班級列表 </canter>');  //標頭文字
	// Create the Element
	$sel =& $form->addElement('hierselect', 'class', '請選擇補習班&年級：');
	// And add the selection options
	$sel->setOptions(array($select1, $select2, $select3));
	
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file','ctrlEPfirm');
	$form->addElement('hidden','opt','EP2class');
	$form->addRule('class', '「補習班&年級」不可空白！', 'nonzero',null, 'client', null, null);
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->display();
}


function EP2class($class){
	global $dbh, $module_name;
	
	echo '<br>現在的補習班是：'.id2city($class[0]).'&nbsp;'.id2firm($class[1]).'&nbsp;&nbsp;'.$class[2].'年級<hr>';

	echo '<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<FORM ACTION="modules.php?op=modload&name='.$module_name.'&file=ctrlEPfirm" METHOD="POST">
<tr>
<td bgcolor="#FFFFCC" align="center" width="45%">
<font size="3"><b>尚未開放之試卷</b></font>
</TD>
<td bgcolor="#FFFFCC" align="center" width="10%">
<font size="3"><b>施測類型</b></font>
</TD>
<TD bgcolor="#FFFFCC" align="center" width="45%">
<font size="3"><b>已開放試卷</b></font>
</TD>
</TR>

<TR>
<TD bgcolor="#FFFFCC" VALIGN="TOP" align="center" width="45%">
<font size="3" face="arial">
<SELECT NAME="EPids[]" SIZE="20" MULTIPLE>';


	$sql="SELECT sn, exam_paper_id, type_id FROM exam_paper_access WHERE firm_id='".$class[1]."' AND grade ='".$class[2]."' ORDER BY exam_paper_id";
	$result = $dbh->query($sql);
	while ($data = $result->fetchRow()) {
		$current_EP[]=$data['exam_paper_id'].sprintf("%02d",$data['type_id']);  //已開放試卷及施測類型
		$EP_sn[]=$data['sn'];
	}
	$sql = "SELECT distinct exam_paper_id FROM concept_item WHERE (exam_paper_id != -1) ";
	for($i=0;$i<sizeof($current_EP);$i++){
		$currEPid=substr($current_EP[$i],0,11);   //取出試卷id
		$sql .= "AND (exam_paper_id != $currEPid) ";
	}
	$sql .= "ORDER BY exam_paper_id ASC";
	$result = $dbh->query($sql);
	while ($data = $result->fetchRow())	{
		$paper_info=explode_ep_id($data['exam_paper_id']);
		$paper_title=id2publisher($paper_info[0]).id2subject($paper_info[1]).'第'.$paper_info[2].'冊第'.$paper_info[3].'單元-卷'.$paper_info[4];
		echo '<OPTION VALUE="'.$data['exam_paper_id'].'">'.$paper_title.'</OPTION>\n';
	}

	echo '</SELECT><br></TD><TD bgcolor="#FFFFCC" VALIGN="TOP" align="center" width="10%"><select name="ExamType">';


	$sql="SELECT * FROM exam_type ORDER BY type_id";
	$result = $dbh->query($sql);
	while ($data = $result->fetchRow()) {
		echo '<option value="'.$data['type_id'].'">'.$data['type'].'</option>';
	}

	echo '</select><font size="3" face="arial">';
	echo '<INPUT TYPE="HIDDEN" NAME="city_code" VALUE="'.$class[0].'">';
	echo '<INPUT TYPE="HIDDEN" NAME="firm_id" VALUE="'.$class[1].'">';
	echo '<INPUT TYPE="HIDDEN" NAME="grade" VALUE="'.$class[2].'">';
	echo '<INPUT TYPE="HIDDEN" NAME="class" VALUE="'.$class[3].'">';
   echo '<br><br>';
   echo '<select name="opt">
         <option value="EPadd2class">新增 →</option>
         <option value="delClassEP">← 刪除</option></select>';
	echo '<br><br>';
	echo '<INPUT TYPE="SUBMIT" NAME="SUBMIT" VALUE="送出">';
	echo '</TD><TD bgcolor="#FFFFCC" VALIGN="TOP" align="center"><TABLE BORDER="0" CELLPADDING="5" CELLSPACING="0"><TR><TD>';
   echo '<SELECT NAME="OpenEPids[]" SIZE="20" MULTIPLE>';
	for($i=0;$i<sizeof($current_EP);$i++){
		$currEPid=substr($current_EP[$i],0,11);   //取出試卷id
		$exam_type_id=substr($current_EP[$i],11,2);   //取出施測類型
		$paper_info=explode_ep_id($currEPid);
		$paper_title=id2publisher($paper_info[0]).id2subject($paper_info[1]).'第'.$paper_info[2].'冊第'.$paper_info[3].'單元-卷'.$paper_info[4].'【'.$exam_type=id2ExamType($exam_type_id).'】';
		//echo $del.'&nbsp;'.$paper_title."<br>";
		echo '<OPTION VALUE="'.$EP_sn[$i].'">'.$paper_title.'</OPTION>\n';
	}
	echo '</SELECT><br>';
	echo "</TD><TR></TABLE></TD></TR></TABLE></FORM>";

}


function EPadd2class($EPids){
	global $dbh, $module_name;

	$fid=$_REQUEST['firm_id'];
	$gid=$_REQUEST['grade'];
	$cid=$_REQUEST['class'];

	while(list($null, $curr_EPid) = each($EPids)) {
		$cs_id=EPid2CSid($curr_EPid);
		$EP_info=explode_ep_id($curr_EPid);
		$paper_vol=intval($EP_info[4]);
		$query = 'INSERT INTO exam_paper_access (cs_id, paper_vol, exam_paper_id, firm_id, grade, class, type_id) VALUES (?,?,?,?,?,?,?)';
		$data = array($cs_id, $paper_vol, $curr_EPid, $fid, $gid, $cid, $_REQUEST['ExamType']);
		
		$result =$dbh->query($query, $data);
		if (PEAR::isError($result)) {
			echo "錯誤訊息：".$result->getMessage()."<br>";
			echo "錯誤碼：".$result->getCode()."<br>";
			echo "除錯訊息：".$result->getDebugInfo()."<br>";
		}else{
			$RedirectTo="Location: modules.php?op=modload&name=".$module_name."&file=ctrlEPfirm&opt=EP2class&class[0]=".$_REQUEST['city_code']."&class[1]=".$fid."&class[2]=".$gid."&class[3]=".$cid;
			Header($RedirectTo);
		}
	}
}

function class_delEP($EP_sn){
	global $dbh, $module_name;

   $ccd=$_REQUEST['city_code'];
   $fid=$_REQUEST['firm_id'];
	$gid=$_REQUEST['grade'];

   for($i=0;$i<count($EP_sn);$i++){
      $sql="DELETE FROM exam_paper_access WHERE sn='".$EP_sn[$i]."' AND firm_id='".$fid."'";
      $result = $dbh->query($sql);
      if (PEAR::isError($result)) {
         echo "錯誤訊息：".$result->getMessage()."<br>";
         echo "錯誤碼：".$result->getCode()."<br>";
         echo "除錯訊息：".$result->getDebugInfo()."<br>";
         die();
      }
   }
   $RedirectTo="Location: modules.php?op=modload&name=".$module_name."&file=ctrlEPfirm&opt=EP2class&class[0]=".$ccd."&class[1]=".$fid."&class[2]=".$gid."&class[3]=".$cid;
	Header($RedirectTo);

}

?>

