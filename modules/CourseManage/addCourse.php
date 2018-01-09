<?php
require_once "HTML/QuickForm.php";
require_once "include/adp_API.php";

if($user_data->access_level<=70){
	Header("Location: index.php");
}
$module_name = basename(dirname(__FILE__));
$SubmitFile = basename(__FILE__);
$SUBTMP=explode(".", $SubmitFile);
$SubmitFile=$SUBTMP[0];

//$table_fill_color=array("#CCFFFF", "#FFFFCC", "#FFFACD", "#FFFFFF");
$table_fill_color=array("#FFFFFF", "#FFFACD", "#FFFFE0", "#F4F4F4", "#FFF0F5");
//學期
$seme['1']="第一場次";
$seme['2']="第二場次";
//$seme['3']="暑期";
//-- 顯示主畫面
CM_table_header($module_name);

//echo'<table><tr><td>';

echo '<br>
	<table width="98%" border="1" cellpadding="0" cellspacing="0">
	<tr>
		<td width="40%" align="center" valign="top" bordercolor="#FFCC33">';

if($_REQUEST['opt']=='edit'){
	modifyCourse($_REQUEST['mopt'], $_REQUEST['si']);
}else{
	creatCourse($Msg, $_REQUEST['opt']); 
}

echo '</td><td width="60%" align="center" valign="top" bordercolor="#FFCC33">';

viewCourse($_REQUEST['opt']);

echo '</td></tr></table>';


//echo'</td></tr></table>';

function creatCourse($Msg,$opt){
	global $dbh, $OpenedCS, $user_data, $module_name, $SubmitFile;
	
	$seme['1']="第一場次";
	$seme['2']="第二場次";
	//debug_msg("第".__LINE__."行 _REQUEST ", $_REQUEST);
	$form = new HTML_QuickForm('frmTest','post','');

	//--  建立新課程訊息
	if ($form->validate() && $opt=='creat_course') {
		$sql="select count(name) from course where year='".$_REQUEST['year']."' AND seme='".$_REQUEST['seme']."' AND name='".$_REQUEST['mycourse']."'";
		$data =& $dbh->getOne($sql);
		if($data>0){
			die("<br><br>錯誤！課程名稱重複！<br><br>");
		}
		$sql="select max(sn) from course ";
		$data =& $dbh->getOne($sql);
		if($data===null){
			$CourseID=sprintf("%03d%d%06d",$_REQUEST['year'], $_REQUEST['seme'], "1");
		}else{
			$CourseID=sprintf("%03d%d%06d",$_REQUEST['year'], $_REQUEST['seme'], $data+1);
		}

		//-- 寫入資料庫
		$query = 'INSERT INTO course (course_id, name, year, seme ) VALUES (?,?,?,?)';
		$data = array($CourseID, $_REQUEST['mycourse'], $_REQUEST['year'], $_REQUEST['seme']);
		$result =$dbh->query($query, $data);
		//echo "<pre>";
		//print_r($result);
		$Msg="「".$_REQUEST['mycourse']."」建立成功！";
		echo "<br>$Msg<br><br>";
	}

	//建立新課程的表單
	$form->addElement('header', 'myheader', '建立新場次');  //標頭文字
	$year['0']=$seme['0']="==請選擇==";
	$today_y = date("Y")-1911; 
	for($i=$today_y-3;$i<=$today_y+3;$i++){
		$year[$i]=$i;
	}
	ksort($seme);
	$form->addElement('select','year','年度：',$year);
	//$form->addElement('select','seme','學期：',$seme);
	$form->addElement('hidden','seme','學期：','第一學期');
	$form->addElement('text', 'mycourse', '場次名稱');
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file',$SubmitFile);
	$form->addElement('hidden','opt','creat_course');
	$form->addElement('submit','btnSubmit','輸入完畢，建立結構');
	$form->addRule('mycourse', '場次名稱不可空白', 'required', null, 'client', null, null);
	$form->display();

	echo '<br></div>';

}


function modifyCourse($mopt, $CourseID){
	global $dbh, $module_name, $SubmitFile;

	$seme['1']="第一場次";
	$seme['2']="第二場次";

	//--  檢查是否為檔案上傳狀態，並回報
	if ($mopt=='modify') {   //第一次輸入資料要修改
		$CourseName=$_REQUEST[mycourse];
		$Year=$_REQUEST[myyear];
		$Seme=$_REQUEST[myseme];
		//-- 更新資料庫
		$table_name   = 'course';
		$table_values = array(
			'name' => $CourseName,
			'year' => $Year,
			'seme' => $Seme
		);     
		$table_field='course_id ='.$CourseID;
		$result = $dbh->autoExecute($table_name, $table_values,
                        DB_AUTOQUERY_UPDATE, $table_field);
		if($result==1){
			echo '<br>課程（'.$CourseName.'）修改成功！<br>';
		}
	}

	$sql = "select * from course where course_id='".$CourseID."'";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$name=$row[name];
		$sid[$row[course_id]]=$row[course_id];
		$my_year=$row[year];
		$my_seme=$row[seme];
		//debug_msg("第".__LINE__."行 row ", $row);
	}
	$today_y = date("Y")-1911; 
	for($i=$today_y-3;$i<=$today_y+3;$i++){
		$myyear[$i]=$i;
	}
	$form1 = new HTML_QuickForm('frmTest','post','');

	$form1->addElement('header', 'myheader', '修改場次資訊');  //標頭文字
	$form1->addElement('select', 'mycourseid', '場次代號', $sid);
	$form1->addElement('select', 'myyear', '年度', $myyear);
	//$form1->addElement('select', 'myseme', '學期', $seme);
	$form1->addElement('hidden', 'myseme', '學期', '第一學期');
	$form1->addElement('text', 'mycourse', '場次名稱');
	$form1->addElement('hidden','op','modload');
	$form1->addElement('hidden','name',$module_name);
	$form1->addElement('hidden','file',$SubmitFile);
	$form1->addElement('hidden','opt','edit');
	$form1->addElement('hidden','mopt','modify');
	$form1->addElement('hidden','si', $CourseID);
	$form1->addElement('submit','btnSubmit','輸入完畢，送出');
	$selected = array(
			"mycourse"=>$name,
			"myyear"=>$my_year,
			"seme"=>$my_seme
	); 
	$form1->addRule('mycourse', '課程名稱不可空白', 'required',null, 'client', null, null);
	$form1->setDefaults($selected);
	//$form1->freeze();  //固定欄位，不能更改
	$form1->display();
	//debug_msg("第".__LINE__."行 myCS ", $myCS);
	//echo '<font color="#FF0000">★★不更改之檔案欄位請留空白！★★</font><br>';

}


function viewCourse($opt){
	global $dbh, $table_fill_color, $user_data, $module_name, $seme, $SubmitFile;

	if($opt=='delete' && isset($_REQUEST['cs_sn'])){
		$sql = "select course_id from course where course_id='".$_REQUEST['cs_sn']."'";
		$co_id =& $dbh->getOne($sql);
		//$sql="DELETE FROM concept_info WHERE cs_sn='".$_REQUEST['cs_sn']."'";
		//$result = $dbh->query($sql);
		if($co_id){
			$sql="DELETE FROM course WHERE course_id='".$co_id."'";
			$result = $dbh->query($sql);
			$sql="DELETE FROM exam_course_access WHERE course_id='".$co_id."'";
			$result = $dbh->query($sql);
			$sql="DELETE FROM user_course WHERE course_id='".$co_id."'";
			$result = $dbh->query($sql);
		}
	}

	echo '
<table width="100%" border="0" align="center">
  <tr>
	<td align="left">場次列表：</td>
	<td align="right">【<a href="modules.php?op=modload&name='.$module_name.'&file=addCourse">新增場次</a>】</td>
  </tr>
</table>
<table width="98%" border="1" align="center" bordercolor="#FFFFFF">
  <tr>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">代號</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">年度</div></td>
   <!-- <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">學期</div></td> -->
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">場次名稱</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">功能</div></td>
  </tr>';

	$sql = "select * from course order by year, seme";
	$result = $dbh->query($sql);
	$ii=1;
	while ($data = $result->fetchRow()) {
		//$myary=array($data['course_id'], $data['year'], $data['seme'], $data['name']);
		$myary=array($data['course_id'], $data['year'], $data['name']);
		echo "<tr>";
		$cs_title='';
		$myi=count($myary);
		for($i=0;$i<$myi;$i++){
			echo "<td bordercolor=\"#4D6185\" bgcolor=\"".$table_fill_color[intval($data['course'])%count($table_fill_color)]."\"><div align=\"center\">".$myary[$i]."</div></td>";
		}
		$del_url="modules.php?op=modload&name=".$module_name."&file=".$SubmitFile."&opt=delete&cs_sn=".$data['course_id'];
        $del = "<a href=\"javascript:if (confirm('你確定刪除這個課程？\n' + '「".$data['name']."」的所有課程選修學生資料會被刪除！')==true) self.location = '".$del_url."';\"><img src=\""._ADP_URL."images/delete.png\" alt=\"刪除課程\" border=\"0\"></a> ";
		$modify_url="modules.php?op=modload&name=".$module_name."&file=".$SubmitFile."&opt=edit&si=".$data['course_id'];
		$modify = '<a href="'.$modify_url.'"><img src="'._ADP_URL.'images/edit.png" alt="修改課程名稱" border="0"></a>';
		echo "<td bordercolor=\"#4D6185\" bgcolor=\"#FF99FF\" align=\"center\">".$modify."&nbsp;&nbsp;".$del."</td></tr>";
		$ii++;
	}
	echo "</table>";
}

echo "<br><br>";



//require_once "feet.php"; 

?>
