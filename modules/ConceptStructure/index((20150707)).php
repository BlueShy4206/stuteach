<?php
require_once "HTML/QuickForm.php";
require_once "HTTP/Upload.php";
require_once "include/adp_API.php";

$OpenedCS=getCSlicense($user_data->user_id);

//debug_msg("第".__LINE__."行 OpenedCS ", $OpenedCS);
//die();


$module_name = basename(dirname(__FILE__));
//$table_fill_color=array("#CCFFFF", "#FFFFCC", "#FFFACD", "#FFFFFF");
$table_fill_color=array("#FFFFFF", "#FFFACD", "#FFFFE0", "#F4F4F4", "#FFF0F5");
//CS_table_header($module_name);
//-- 顯示主畫面
IMPORT_CREATITEM_table_header();
echo '<br>
	<table width="98%" border="1" cellpadding="0" cellspacing="0">
	<tr>
		<td width="45%" align="center" valign="top" bordercolor="#FFCC33">';

if($_REQUEST['opt']=='edit'){
	modifyCS($_REQUEST['mopt'], $_REQUEST['cs_sn']);
}else{
	creatCS($Msg, $_REQUEST['opt']); 
}

echo '</td><td width="55%" align="center" valign="top" bordercolor="#FFCC33">';

viewCS($_REQUEST['opt']);

echo '</td></tr></table>';



function creatCS($Msg,$opt){
	global $dbh, $OpenedCS, $user_data, $module_name;
	

	$form = new HTML_QuickForm('frmTest','post','');

	//--  檢查是否為檔案上傳狀態，並回報
	if ($form->validate() && $opt=='creat_cs') {
      $q_cs_ida=sprintf("%03d%02d",$_REQUEST['mypublisher'],$_REQUEST['mysubject']);
      //檢查是否具有該單元的操作權限
      if(in_array( $q_cs_ida, $OpenedCS)!=TRUE){
         echo "您無權限，".__LINE__."<br>";
         echo '<p>|| <a href="javascript:history.go(-1);">返回上一頁</a> ||</p>';
         die();
      }
      
		$cs_id=sprintf("%03d%02d%02d%02d",$_REQUEST['mypublisher'],$_REQUEST['mysubject'],$_REQUEST['mybook'],$_REQUEST['myunit']);  //測驗結構編號
		$sql="select count(*) from concept_info where cs_id='$cs_id'";
		$data =& $dbh->getOne($sql);
		//debug_msg("第".__LINE__."行 data ", $data);
		if($data>0) {
			echo "錯誤！該單元已經存在！".__LINE__."<br>";
			echo '<p>|| <a href="javascript:history.go(-1);">返回上一頁</a> ||</p>';
			die ();
		}
		//--  遞迴建立上傳檔案目錄
		$array = array($_REQUEST['mypublisher'], $_REQUEST['mysubject'], $_REQUEST['mybook'], $_REQUEST['myunit']);
		$mydir=_ADP_CS_UPLOAD_PATH;  //預設上傳結構概念矩陣檔之目錄
		for($i=0;$i<count($array);$i++){
			$mydir.=$array[$i]."/";
			if (!is_dir($mydir))
				mkdir($mydir, 0777);
		}	//$mydir=_ADP_CS_UPLOAD_PATH.$_REQUEST['mypublisher']."/".$_REQUEST['mysubject']."/".$_REQUEST['mybook']."/".$_REQUEST['myunit']."/";  //預設上傳試題之目錄

		$upload = new HTTP_Upload();
		if (PEAR::isError($upload)) die (__LINE__.$upload->getMessage());  //顯示錯誤訊息
		$files = $upload->getFiles();
		if (PEAR::isError($files)) die (__LINE__.$files->getMessage());  //顯示錯誤訊息
		$i=0;
      foreach ($files as $file) {
			if ($file->isValid()) {
				$file->setName('uniq');
				$file_name = strtolower($file->moveTo($mydir));
				if (PEAR::isError($file_name)) die ('發生錯誤：'.$file_name->getMessage());  //顯示錯誤訊息
			}
			$prop = $file->getProp();   //取得上傳檔案之最後資訊
			$this_upload_file[$i]=strtolower($prop['name']);    //取得檔案上傳後之檔名
			$i++;
      }
      
      //檔名轉小寫
      $ii=count($this_upload_file);
      for($i=0;$i<$ii;$i++){
         $org_file = $mydir.$this_upload_file[$i];
         $low_filename = strtolower($this_upload_file[$i]);
         $low_file = $mydir.$low_filename;
         //$RenameRsu=rename($org_file, $low_file);
         //if(!$RenameRsu){
          //  die(__LINE__."改檔名錯誤！");
        // }
		}

      //-- 寫入資料庫
		$query1 = 'INSERT INTO concept_info (cs_id, publisher_id, subject_id, vol, grade, unit, concept) VALUES (?,?,?,?,?,?,?)';
		//$data = array($cs_id, $_REQUEST['mypublisher'], $_REQUEST['mysubject'], $_REQUEST['mybook'], $_REQUEST['mygrade'], $_REQUEST['myunit'], $_REQUEST['myunitname']);
		$data = array($cs_id, $_REQUEST['mypublisher'], $_REQUEST['mysubject'], $_REQUEST['mybook'], '1', $_REQUEST['myunit'], $_REQUEST['myunitname']);
		$result =$dbh->query($query1, $data);
		//debug_msg("第".__LINE__."行 result ", $result);
		$queryx = 'INSERT INTO concept_info_dim (cs_id, dim, dim_name, sub  ) VALUES (?,?,?,?)';
		$datax = array($cs_id, 1, id2subject($_REQUEST['mysubject']), $_REQUEST['mysubnumber']);
		$resultx =$dbh->query($queryx, $datax);
		
		//echo "<pre>";
		//print_r($result);
		if($result=="1"){
         $query = 'INSERT INTO concept_info_plus (cs_id, ready ) VALUES (?,?)';
         $data = array($cs_id, '0');
         $result =$dbh->query($query, $data);
         //debug_msg("第".__LINE__."行 result ", $result);

         if($result=="1"){
			   $Msg= "測驗結構(".$_REQUEST['myunitname'].")建立成功！" ;
			   //$require_file="modules/".$module_name."/modifyConceptRemedy.php";
			   //require_once "$require_file";
            //$structure_path=$mydir.$this_upload_file['1'];
            //$structure_temp=read_excel($structure_path, __LINE__);
            //debug_msg("第".__LINE__."行 structure_temp ", $structure_temp);
            //include_once "modules/$module_name/DbConceptRemedy.php";
			   //$result1=modifyConceptRemedy($cs_id, $structure_temp);
			   //debug_msg("第".__LINE__."行 result1 ", $result1);
            //die();
//			   if($result1=="1"){
 //              $Msg.= "<br>單元概念(".$cs_id.")建立成功！" ;
//            }else{
//              $Msg.= "<br>單元概念(".$cs_id.")建立失敗！" ;
//            }
			}else{
            $Msg.="concept_info_plus 新增錯誤！";
         }
			echo "$Msg<br>";
		}
	}
	
	
	//建立測驗結構的表單

	$accept_mime=array('application/vnd.ms-excel', 'image/pjpeg', 'image/gif');

	$form->addElement('header', 'myheader', '建立測驗結構');  //標頭文字
   $units['0']=$grades['0']=$books['0']=$subject['0']=$publish['0']="==請選擇==";
   
   if($user_data->user_id=="admin"){
      
      $sql = "select * from publisher";
      $result =$dbh->query($sql);
      while ($row=$result->fetchRow()){
         $publish[$row[publisher_id]]=$row[publisher];
      }

      //--- 找出所有領域(科目)
      $sql = "select * from subject";
      $result =$dbh->query($sql);
      while ($row=$result->fetchRow()){
         $subject[$row[subject_id]]=$row[name];
      }
   }else{
      while(list($null, $CsID) = each($OpenedCS)) {
         $pid=substr($CsID, 0, 3)*1;
         $sid=substr($CsID, 3, 2)*1;
         $publish[$pid]=id2publisher($pid);
         $subject[$sid]=id2subject($sid);
      }
   }
	for ($i=15;$i<=25;$i++)  $books[$i]=$i+2000;  //建立年度數
	for ($i=1;$i<=9;$i++)   $grades[$i]=$i;  //建立年級數
	//$units[40]="起點行為試題";
	for ($i=1;$i<=15;$i++)  $units[$i]=$i;  //建立單元數
	//$units[30]="第一次段考";
	//$units[31]="第二次段考";
	
    $form->addElement('select', 'mypublisher', '版本', $publish);
    $form->addElement('select', 'mysubject', '領域', $subject);
	$form->addElement('select', 'mybook', '年度', $books);
	//$form->addElement('select', 'mygrade', '適用年級', $grades);
	$form->addElement('select', 'myunit', '單元', $units);
	$form->addElement('text', 'myunitname', '名稱');
	$form->addElement('text', 'mysubnumber', '學科或主題數量(數字)');
	//$form->addElement('text', 'mythreshold', '閥值(threshold)');
/*	$form->addElement('file','userfile[]','1.單元Rtree xls');
	$form->addElement('file','userfile[]','2.試卷概念表 xls');
	$form->addElement('file','userfile[]','3.試題概念表 xls');
	$form->addElement('file','userfile[]','4.百分對照表 xls');
	$form->addElement('file','userfile[]','5.百分等級圖 gif');
	$form->addElement('file','userfile[]','6.知識結構圖 gif');
	$form->addElement('header', 'myheader', '   ');  //標頭文字
	$form->addElement('file','userfile[]','7.多點Rtree xls');
	$form->addElement('file','userfile[]','8.各點下題目 xls');
	$form->addElement('file','userfile[]','9.各點的閥值 xls');
	$form->addElement('file','userfile[]','10.各試卷題數 xls');
	$form->addElement('header', 'myheader', '   ');  //標頭文字
	$form->addElement('file','userfile[]','11.雷達圖試題數 xls');
	$form->addElement('file','userfile[]','12.教材對照表 doc');   */
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name','ConceptStructure');
	$form->addElement('hidden','file','index');
	$form->addElement('hidden','opt','creat_cs');
	$form->addElement('submit','btnSubmit','輸入完畢，建立結構');
	$form->addRule('mypublisher', '請選擇正確版本', 'nonzero',null, 'client', null, null);
	$form->addRule('mysubject', '請選擇正確領域', 'nonzero',null, 'client', null, null);
	$form->addRule('mybook', '請選擇正確冊別', 'nonzero',null, 'client', null, null);
	//$form->addRule('mygrade', '請選擇正確適用年級', 'nonzero',null, 'client', null, null);
	$form->addRule('myunit', '請選擇正確單元', 'nonzero',null, 'client', null, null);
	$form->addRule('myunitname', '名稱不可空白', 'required',null, 'client', null, null);
	//$form->addRule('userfile', '不可上傳空檔', 'uploadedfile');
	//if ($form->validate()) {
	//	$form->freeze();
	//}
	$form->display();
//	echo '<font color="#0000FF">上傳檔案1~3</font><font color="#FF0000">必上傳範例檔</font><br>';
//	echo '<font color="#0000FF">上傳檔案4~5</font><font color="#FF0000">若有則上傳，沒有則上傳範例檔</font><br>';
//	echo '<font color="#0000FF">上傳檔案6~12</font><font color="#FF0000">空白！</font><br><br>';
//	echo '<font color="#0000FF">上傳檔案11</font><font color="#FF0000">為多點記分且「能力分佈雷達圖」專用</font><hr>';
//	echo "範例<br>[<a href=\"examples/cs_map.xls\" target=\"_blank\">1.單元Rtree</a>][<a href=\"examples/remedy.xls\" target=\"_blank\">2.試卷概念</a>]";
//	echo "[<a href=\"examples/item_remedy.xls\" target=\"_blank\">3.試題概念</a>][<a href=\"examples/percentage.xls\" target=\"_blank\">4.百分對照</a>][<a href=\"examples/percentage_graphic.gif\" target=\"_blank\">5.百分等級</a>]";
//	echo "<br>[<a href=\"\" target=\"_blank\">6.知識結構圖</a>][<a href=\"examples/poly_rtree.xls\" target=\"_blank\">7.多點Rtree</a>]";
//	echo "[<a href=\"examples/poly_items.xls\" target=\"_blank\">8.各點下題目</a>][<a href=\"examples/poly_threshold.xls\" target=\"_blank\">9.各點的閥值</a>]";
//	echo "<br>[<a href=\"examples/paper_items.xls\" target=\"_blank\">10.各試卷題數</a>][<a href=\"examples/radar.xls\" target=\"_blank\">11.雷達圖試題數</a>][<a href=\"examples/1174010565.doc\" target=\"_blank\">12.教材對照表</a>]";
	echo "<br><br>";

}


function modifyCS($mopt, $cs_sn){
	global $dbh, $OpenedCS, $module_name;

	$myCS= new ConceptData($cs_sn);
	$sql = "select concept_info_plus.ready from concept_info, concept_info_plus where concept_info.cs_sn = '$cs_sn' AND concept_info.cs_id=concept_info_plus.cs_id";
	$data =& $dbh->getOne($sql);
	if($data==1){
      echo "<br>該單元已經上鎖，不能被修改！<br>";
      die();
   }
		//--  檢查是否為檔案上傳狀態，並回報
	//debug_msg("第".__LINE__."行 _REQUEST ", $_REQUEST);
	if ($mopt=='modify') {   //第一次輸入資料要修改
	   $q_cs_ida=sprintf("%03d%02d",$_REQUEST['mypublisher'],$_REQUEST['mysubject']);
      //檢查是否具有該單元的操作權限
      /*
	  if(in_array( $q_cs_ida, $OpenedCS)!=TRUE){
         echo "您無權限，".__LINE__."<br>";
         echo '<p>|| <a href="javascript:history.go(-1);">返回上一頁</a> ||</p>';
         die();
      }
      */
		$cs_id=sprintf("%03d%02d%02d%02d",$_REQUEST['mypublisher'],$_REQUEST['mysubject'],$_REQUEST['mybook'],$_REQUEST['myunit']);  //測驗結構編號
		
        
        /*
		//寫入 concept_remedy資料表
		$structure_path=$mydir.$this_upload_file['1'];
      $structure_temp=read_excel($structure_path, __LINE__);
      include_once "modules/$module_name/DbConceptRemedy.php";
		$result1=modifyConceptRemedy($cs_id, $structure_temp);
		if($result1){
			echo '<br>單元概念列表（'.$cs_id.'）修改成功！<br>';
		}
        */

		//-- 更新資料庫
/*		$table_name   = 'concept_info';
		$table_values = array(
			'matrix_map' => $this_upload_file['0'],
			'remedy_file' => $this_upload_file['1'],
			'item_remedy_file' => $this_upload_file['2'],
			'percent_map' => $this_upload_file['3'],
			'percent_gif' => $this_upload_file['4'],
			'structure_gif' => $this_upload_file['5'],
			'indicator_relation' => $this_upload_file['6'],
			'indicator_item' => $this_upload_file['7'],
			'indicator_threshold' => $this_upload_file['8'],
			'indicator_item_nums' => $this_upload_file['9'],
			'indicator_item_relation' => $this_upload_file['10'],
			'book_ref' => $this_upload_file['11']
		);     
		$table_field='cs_sn ='.$cs_sn;
		$result = $dbh->autoExecute($table_name, $table_values,
                        DB_AUTOQUERY_UPDATE, $table_field);
*/      
        $table_name   = 'concept_info_dim';
		$table_values = array(
			'sub' => $_REQUEST['mysubnumber']
		);     
		$table_field='cs_id ='.$cs_id;
		$result = $dbh->autoExecute($table_name, $table_values,
                        DB_AUTOQUERY_UPDATE, $table_field);
        //debug_msg("第".__LINE__."行 result ", $result);
        
        $table_name   = 'concept_info';
		$table_values = array(
			'concept' => $_REQUEST['myunitname']
		);     
		$table_field='cs_sn ='.$cs_sn;
		$result1 = $dbh->autoExecute($table_name, $table_values,
                        DB_AUTOQUERY_UPDATE, $table_field);
        
		if($result1){
			echo '<br>測驗結構（'.$_REQUEST['myunitname'].'-名稱）修改成功！<br>';
		}
		if($result){
			echo '<br>測驗結構（'.$_REQUEST['myunitname'].'-能力指標數量）修改成功！<br>';
		}
	}
	
	$form1 = new HTML_QuickForm('frmTest','post','');
	

	//--- 找出現有出版商
/*	$sql = "select * from publisher";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$publish[$row[publisher_id]]=$row[publisher];
	}

	//--- 找出所有領域(科目)
	$sql = "select * from subject";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$subject[$row[subject_id]]=$row[name];
	}

	for ($i=1;$i<=18;$i++)  $books[$i]=$i;  //建立冊別數
	for ($i=1;$i<=9;$i++)   $grades[$i]=$i;  //建立年級數
	for ($i=1;$i<=15;$i++)  $units[$i]=$i;  //建立單元數
	$books[$myCS->vol]=$myCS->vol;
	$units[$myCS->unit]=$myCS->unit;

	$base='<a href="modules.php?op=modload&name=ConceptStructure&file=download&pid='.$myCS->publisher_id.'&sid='.$myCS->subject_id.'&vid='.$myCS->vol.'&uid='.$myCS->unit.'&dfn=';
	$cdata=array("單元Rtree","試卷概念表","試題概念表","百分對照表","百分等級圖","知識結構圖","多點Rtree","各點下題目","各點的閥值","各試卷題數","雷達圖試題數","教材對照表");
	for($j=0;$j<count($cdata);$j++){
		if($myCS->cs_file[$j]==""){
			${a.$j}=($j+1).'.'."$cdata[$j]";
		}else{
			${a.$j}=($j+1).'.'.$base.$myCS->cs_file[$j].'" target="blank">'.$cdata[$j].'</a>';
		}
	}
*/

	$sql = "select sub from concept_info_dim where cs_id=$myCS->cs_id";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$sub=$row[sub];
	}

	$publish[$myCS->publisher_id]=$myCS->publisher_id;
	$subject[$myCS->subject_id]=$myCS->subject_id;
	$books[$myCS->vol]=$myCS->vol;
	$grades[$myCS->grade]=$myCS->grade;
	$units[$myCS->unit]=$myCS->unit;

	//debug_msg("第".__LINE__."行 myCS->cs_file ", $myCS->cs_file);

	$form1->addElement('header', 'myheader', '修改測驗結構');  //標頭文字
	//$form1->addElement('select', 'mypublisher', '版本', $publish);
	//$form1->addElement('select', 'mysubject', '領域', $subject);
	//$form1->addElement('select', 'mybook', '冊別', $books);
	//$form1->addElement('select', 'mygrade', '適用年級', $grades);
	//$form1->addElement('select', 'myunit', '單元', $units);
	$form1->addElement('text', 'myunitname', '名稱');
	$form1->addElement('text', 'mysubnumber', '能力指標數量(數字)');
	//$form1->addElement('file','userfile[]',$a0);
	//$form1->addElement('file','userfile[]',$a1);
	//$form1->addElement('file','userfile[]',$a2);
	//$form1->addElement('file','userfile[]',$a3);
	//$form1->addElement('file','userfile[]',$a4);
//	$form1->addElement('file','userfile[]',$a5);
	$form1->addElement('header', 'myheader', '   ');  //標頭文字
//	$form1->addElement('file','userfile[]',$a6);
//	$form1->addElement('file','userfile[]',$a7);
//	$form1->addElement('file','userfile[]',$a8);
//	$form1->addElement('file','userfile[]',$a9);
//	$form1->addElement('header', 'myheader', '   ');  //標頭文字
//	$form1->addElement('file','userfile[]',$a10);
//	$form1->addElement('file','userfile[]',$a11);
	$form1->addElement('hidden','op','modload');
	$form1->addElement('hidden','name','ConceptStructure');
	$form1->addElement('hidden','file','index');
	$form1->addElement('hidden','opt','edit');
	$form1->addElement('hidden','mopt','modify');
	$form1->addElement('hidden','cs_sn', $cs_sn);
	$form1->addElement('submit','btnSubmit','輸入完畢，修改結構');
	$selected = array("mypublisher"=>$myCS->publisher_id,
			"mysubject"=>$myCS->subject_id,
			"mybook"=>$myCS->vol,
			//"mygrade"=>$myCS->grade,
			"myunit"=>$myCS->unit,
			"myunitname"=>$myCS->concept_name,
			"mysubnumber"=>$sub
	); 
	
	$form1->setDefaults($selected);
//	$form1->freeze();  //固定欄位，不能更改
	$form1->display();
	//debug_msg("第".__LINE__."行 myCS ", $myCS);
	//echo '<font color="#FF0000">★★不更改之檔案欄位請留空白！★★</font><br>';
	//echo "範例[<a href=\"examples/cs_map.xls\" target=\"_blank\">1.測驗結構</a>][<a href=\"examples/remedy.xls\" target=\"_blank\">2.試卷概念</a>]";
	//echo "[<a href=\"examples/item_remedy.xls\" target=\"_blank\">3.試題概念</a>][<a href=\"examples/percentage.xls\" target=\"_blank\">4.百分對照</a>][<a href=\"examples/percentage_graphic.gif\" target=\"_blank\">5.百分等級</a>]";
	//unset($form1);

}


function viewCS($opt){
	global $dbh, $table_fill_color, $user_data, $OpenedCS, $module_name;

if($opt=='delete' && isset($_REQUEST['cs_sn'])){
	$sql = "select cs_id from concept_info where cs_sn='".$_REQUEST['cs_sn']."'";
	$cs_id =& $dbh->getOne($sql);
	//$sql="DELETE FROM concept_info WHERE cs_sn='".$_REQUEST['cs_sn']."'";
	//$result = $dbh->query($sql);
	
	if($cs_id){
		$sql="DELETE FROM concept_item WHERE cs_id='".$cs_id."'";
		$result = $dbh->query($sql);
		$sql="DELETE FROM concept_remedy WHERE cs_id='".$cs_id."'";
		$result = $dbh->query($sql);
		$sql="DELETE FROM exam_paper WHERE cs_id='".$cs_id."'";
		$result = $dbh->query($sql);
		$sql="DELETE FROM concept_info WHERE cs_id='".$cs_id."'";
		$result = $dbh->query($sql);
		$sql="DELETE FROM concept_info_dim WHERE cs_id='".$cs_id."'";
		$result = $dbh->query($sql);
		$sql="DELETE FROM concept_info_plus WHERE cs_id='".$cs_id."'";
		$result = $dbh->query($sql);
		$sql="DELETE FROM exam_paper_access WHERE cs_id='".$cs_id."'";
		$result = $dbh->query($sql);
		$sql="DELETE FROM exam_paper_access_irt WHERE cs_id='".$cs_id."'";
		$result = $dbh->query($sql);
		$sql="DELETE FROM exam_record_irt WHERE cs_id='".$cs_id."'";
		$result = $dbh->query($sql);
		$sql="DELETE FROM concept_item_testlet WHERE exam_paper_id like '".$cs_id."%' ";
		$result = $dbh->query($sql);
	}
}

//<td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">領域</div></td>
//    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">冊</div></td>
//    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">單元</div></td>


echo '
<table width="100%" border="0" align="center">
  <tr>
	<td align="left">測驗列表：</td>
	<td align="right"> </td>
  </tr>
</table>
<table width="98%" border="1" align="center" bordercolor="#FFFFFF">
  <tr>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">編號</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">版本</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">名稱</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">功能</div></td>
  </tr>';

	//-- 出版商及領域名稱之轉換
	$sql = "select * from publisher";   
	$result = $dbh->query($sql);
	while ($data = $result->fetchRow()) {
		$tran1[$data['publisher_id']]=$data['publisher'];
	}
	$sql = "select * from subject";   
	$result = $dbh->query($sql);
	while ($data = $result->fetchRow()) {
		$tran2[$data['subject_id']]=$data['name'];
	}

	$sql = "select * from concept_info, concept_info_plus WHERE concept_info.cs_id=concept_info_plus.cs_id order by publisher_id, subject_id, vol, unit";
	$result = $dbh->query($sql);
	$ii=1;
	while ($data = $result->fetchRow()) {
      $q_cs_id=sprintf("%03d", $data['publisher_id']).sprintf("%02d", $data['subject_id']);
      if(in_array( $q_cs_id, $OpenedCS)==TRUE){

         $myary=array($ii,  $tran1[$data['publisher_id']], $data['concept']);
         echo "<tr>";
         $cs_title='';
         for($i=0;$i<count($myary);$i++){
            echo "<td bordercolor=\"#4D6185\" bgcolor=\"".$table_fill_color[intval($data['vol'])%count($table_fill_color)]."\"><div align=\"center\">".$myary[$i]."</div></td>";
         }
         $cs_title="\n".$myary[1]."-".$myary[2]."-"."第".$myary[3]."冊-第".$myary[4]."單元【".$myary[5]."】";
         if($data['ready']==0){
            $del_url="modules.php?op=modload&name=ConceptStructure&file=index&opt=delete&cs_sn=".$data['cs_sn'];
            $del = "<a href=\"javascript:if (confirm('你確定刪除這個測驗？\n' + '  這個測驗的所有試題及學生測驗結果都會被刪除！')==true) self.location = '".$del_url."';\"><img src=\""._ADP_URL."images/delete.png\" alt=\"刪除測驗\" border=\"0\"></a> ";
            $modify_url="modules.php?op=modload&name=ConceptStructure&file=index&opt=edit&cs_sn=".$data['cs_sn'];
            $modify = '<a href="'.$modify_url.'"><img src="'._ADP_URL.'images/edit.png" alt="修改測驗結構" border="0"></a>';
            echo "<td bordercolor=\"#4D6185\" bgcolor=\"#FF99FF\" align=\"center\">".$modify."&nbsp;&nbsp;&nbsp;".$del."</td></tr>";
         }elseif($data['ready']==1){
            $lock_pic="<img src=\""._ADP_URL."images/locked.gif\" alt=\"已上鎖\" border=\"0\"> ";
            echo "<td bordercolor=\"#4D6185\" bgcolor=\"#FF99FF\" align=\"center\">".$lock_pic."</td></tr>";
         }
         $ii++;
      }
	}
	echo "</table>";
}




//require_once "feet.php"; 

?>
