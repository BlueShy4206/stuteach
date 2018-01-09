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

//-- 顯示主畫面
IMPORT_CREATITEM_table_header();

//echo'<table><tr><td>';

echo '<br>
	<table width="95%" border="1" cellpadding="0" cellspacing="0">
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


//echo'</td></tr></table>';

function creatCS($Msg,$opt){
	global $dbh, $OpenedCS, $user_data, $module_name;
	

	$form = new HTML_QuickForm('frmTest','post','');

	//--  檢查是否為檔案上傳狀態，並回報
	if ($form->validate() && $opt=='creat_cs') {
		$q_cs_ida=sprintf("%03d%02d",$_REQUEST['mypublisher'],$_REQUEST['mysubject']);
		//檢查是否具有該測驗的操作權限
		if(in_array( $q_cs_ida, $OpenedCS)!=TRUE){
			echo "您無權限，".__LINE__."<br>";
			echo '<p>|| <a href="javascript:history.go(-1);">返回上一頁</a> ||</p>';
			die();
		}
		$dims=$_REQUEST[mydims]*1;  //有幾個向度

		$cs_id=sprintf("%03d%02d%02d%02d",$_REQUEST['mypublisher'],$_REQUEST['mysubject'],$_REQUEST['mybook'],$_REQUEST['myunit']);  //測驗結構編號
		$sql="select count(*) from concept_info where cs_id='$cs_id'";
		$data =& $dbh->getOne($sql);
	//debug_msg("第".__LINE__."行 data ", $data);
		if($data>0) {
			echo "錯誤！該測驗已經存在！".__LINE__."<br>";
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
		}
		//$mydir=_ADP_CS_UPLOAD_PATH.$_REQUEST['mypublisher']."/".$_REQUEST['mysubject']."/".$_REQUEST['mybook']."/".$_REQUEST['myunit']."/";  //預設上傳試題之目錄

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
	
			//讀檔
			$import_data_path=$mydir.$this_upload_file[$i];   //上傳檔案的路徑
			chk_file_exist($import_data_path, __LINE__);
			$input[$i]=read_excel_2j($import_data_path, __LINE__);
			$countii=count($input[$i]);
			if($i==0){   //取各向度名稱
				for($x=0;$x<$countii;$x++){
					$DimNames[]=$input[$i][$x][0];
				}
			}elseif($i==1){   //取反矩陣之值
				$countyy=count($input[$i][0]);
				if($countii!=$dims || $countyy!=$dims){  //輸入錯誤
					echo "<br><br>第".__LINE__."行錯誤，輸入的矩陣值與向度數不符合！<br><br>請重新輸入！<br><br>";
					die();
				}else{
					for($x=0;$x<$dims;$x++){  //讀出反矩陣之值
						for($y=0;$y<$dims;$y++){
							if(is_numeric($input[$i][$x][$y])){
								$sigma[]=$input[$i][$x][$y];
							}else{
								die("<br><br>輸入的矩陣值必須是數字！<br><br>請重新輸入！<br>");
							}
						}
					}
				}
			}
			$i++;
		}

		$myin[0]=implode(_SPLIT_SYMBOL, $DimNames);
		$myin[1]=implode(_SPLIT_SYMBOL, $sigma);
		if($_REQUEST[det]!="" and is_numeric($_REQUEST[det])){
			$myin[2]=$_REQUEST[det];
		}else{
			die("<br><br>輸入的行列式值必須是數字！<br><br>請重新輸入！<br>");
		}
		//debug_msg("第".__LINE__."行 input ", $input);
		//debug_msg("第".__LINE__."行 _REQUEST ", $_REQUEST);
		//debug_msg("第".__LINE__."行 myin ", $myin);
		//檔名轉小寫
		$ii=count($this_upload_file);
		for($i=0;$i<$ii;$i++){
			$org_file = $mydir.$this_upload_file[$i];
			$low_filename = strtolower($this_upload_file[$i]);
			$low_file = $mydir.$low_filename;
			$RenameRsu=rename($org_file, $low_file);
			if(!$RenameRsu){
				die(__LINE__."改檔名錯誤！");
			}
		}

		//-- 寫入資料庫
		$query = 'INSERT INTO concept_info (cs_id, publisher_id, subject_id, vol, grade, unit, concept) VALUES (?,?,?,?,?,?,?)';
		$data = array($cs_id, $_REQUEST['mypublisher'], $_REQUEST['mysubject'], $_REQUEST['mybook'], $_REQUEST['mygrade'], $_REQUEST['myunit'], $_REQUEST['myunitname']);
		$result =$dbh->query($query, $data);
		//echo "<pre>";
		//print_r($result);
		if($result=="1"){
			$query = 'INSERT INTO concept_info_plus (cs_id, ready ) VALUES (?,?)';
			$data = array($cs_id, '1');
			$result =$dbh->query($query, $data);
			//debug_msg("第".__LINE__."行 result ", $result);

			if($result=="1"){
				$Msg= "<br>測驗結構(".$_REQUEST['myunitname'].")建立成功！" ;
				//輸入 concept_info_dim
				$query = 'INSERT INTO concept_info_dim (cs_id, dim, dim_name, sub, sigma_det, dim_name_file, sigma_file) VALUES (?,?,?,?,?,?,?)';
				$data = array($cs_id, $dims, $myin[0], '1', $myin[1]._SPLIT_SYMBOL.$myin[2], $this_upload_file[0], $this_upload_file[1]);
				$result =$dbh->query($query, $data);
				if($result=="1"){
					$Msg.= "<br>MCAT測驗概念(".$cs_id.")建立成功！" ;
				}else{
					$Msg.= "<br>MCAT測驗概念(".$cs_id.")建立失敗！" ;
				}
			}else{
            	$Msg.="<br>concept_info_plus 新增錯誤！";
			}
			echo "$Msg<br>";
		}
	}


	//建立單元結構的表單

	$accept_mime=array('application/vnd.ms-excel', 'image/pjpeg', 'image/gif');

	$form->addElement('header', 'myheader', '建立MCAT測驗結構');  //標頭文字
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
	for ($i=1;$i<=18;$i++)  $books[$i]=$i;  //建立冊別數
	for ($i=1;$i<=9;$i++)   $grades[$i]=$i;  //建立年級數
	//$units[40]="起點行為試題";
	for ($i=1;$i<=15;$i++)  $units[$i]=$i;  //建立單元數
	//$units[30]="第一次段考";
	//$units[31]="第二次段考";
	for ($i=2;$i<=8;$i++)  $mydims[$i]=$i;  //建立向度數
	
    $form->addElement('select', 'mypublisher', '版本', $publish);
    $form->addElement('select', 'mysubject', '領域', $subject);
	$form->addElement('select', 'mybook', '冊別', $books);
	$form->addElement('select', 'mygrade', '適用年級', $grades);
	$form->addElement('select', 'myunit', '單元編號', $units);
	$form->addElement('text', 'myunitname', '測驗名稱');
	$form->addElement('select', 'mydims', '共有幾向度？', $mydims);
	//$form->addElement('text', 'mythreshold', '閥值(threshold)');
	$form->addElement('file','userfile[]','1.各向度名稱 xls');
	$form->addElement('file','userfile[]','2.EAP反矩陣 xls');
	$form->addElement('text', 'det', '行列式值(數字)');
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file','index');
	$form->addElement('hidden','opt','creat_cs');
	$form->addElement('submit','btnSubmit','輸入完畢，建立結構');
	$form->addRule('mypublisher', '請選擇正確版本', 'nonzero',null, 'client', null, null);
	$form->addRule('mysubject', '請選擇正確領域', 'nonzero',null, 'client', null, null);
	$form->addRule('mybook', '請選擇正確冊別', 'nonzero',null, 'client', null, null);
	$form->addRule('mygrade', '請選擇正確適用年級', 'nonzero',null, 'client', null, null);
	$form->addRule('myunit', '請選擇正確單元', 'nonzero',null, 'client', null, null);
	$form->addRule('mydims', '請選擇正確向度數', 'nonzero',null, 'client', null, null);
	$form->addRule('det', '「行列式值」只可輸入數字', 'numeric',null, 'client', null, null);
	$form->addRule('myunitname', '測驗名稱不可空白', 'required',null, 'client', null, null);
	//$form->addRule('userfile', '不可上傳空檔', 'uploadedfile');
	//if ($form->validate()) {
	//	$form->freeze();
	//}
	$form->display();
	echo '<hr>';
	echo "範例<br>[<a href=\"examples/dim_name.xls\" target=\"_blank\">1.各向度名稱</a>]<br>[<a href=\"examples/sigma.xls\" target=\"_blank\">2.(EAP)變異數-共變異數矩陣的反矩陣</a>]";
	echo '<hr>';
	echo '<div align="left">說明：
	<br>1.若本測驗打算使用EAP方法，則<font color=red>必須上傳 「EAP反矩陣 xls」</font>。
	<br>2.承上，三向度之MCAT，請輸入3*3之EAP反矩陣。
	<br>3.「行列式值」請輸入數字。
	<br>4.<font color=red>本功能僅接受 Excel 2003檔案格式。</font><br>
	<br></div>';

}


function modifyCS($mopt, $cs_sn){
	global $dbh, $OpenedCS, $module_name;

	$myCS= new IrtData($cs_sn);

	//--  檢查是否為檔案上傳狀態，並回報
	if ($mopt=='modify') {   //第一次輸入資料要修改
		//debug_msg("第".__LINE__."行 _REQUEST ", $_REQUEST);
		$q_cs_ida=sprintf("%03d%02d",$_REQUEST['mypublisher'],$_REQUEST['mysubject']);
		//檢查是否具有該單元的操作權限
		if(in_array( $q_cs_ida, $OpenedCS)!=TRUE){
			echo "您無權限，".__LINE__."<br>";
			echo '<p>|| <a href="javascript:history.go(-1);">返回上一頁</a> ||</p>';
			die();
		}
		$dims=$_REQUEST[mydims]*1;  //有幾個向度
		$cs_id=sprintf("%03d%02d%02d%02d",$_REQUEST['mypublisher'],$_REQUEST['mysubject'],$_REQUEST['mybook'],$_REQUEST['myunit']);  //單元結構編號
		//--  遞迴建立上傳檔案目錄
		$array = array($_REQUEST['mypublisher'], $_REQUEST['mysubject'], $_REQUEST['mybook'], $_REQUEST['myunit']);
		$mydir=_ADP_CS_UPLOAD_PATH;  //預設上傳結構概念矩陣檔之目錄
		for($i=0;$i<count($array);$i++){
			$mydir.=$array[$i]."/";
			if (!is_dir($mydir))
				mkdir($mydir, 0777);
		}
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
			if($this_upload_file[$i]==''){      //未上傳檔案，表示原檔案不更改
				$this_upload_file[$i]=$myCS->dim_file[$i];
			}else{
				$myCS->dim_file[$i]=strtolower($this_upload_file[$i]);
			}
			/*
			debug_msg("第".__LINE__."行 file ", $file);
			debug_msg("第".__LINE__."行 this_upload_file ", $this_upload_file);
			debug_msg("第".__LINE__."行 myCS->cs_file ", $myCS->cs_file);
			die();
			*/
			//讀檔
			$import_data_path=$mydir.$this_upload_file[$i];   //上傳檔案的路徑
			chk_file_exist($import_data_path, __LINE__);
			$input[$i]=read_excel_2j($import_data_path, __LINE__);
			$countii=count($input[$i]);
			if($i==0){   //取各向度名稱
				for($x=0;$x<$countii;$x++){
					$DimNames[]=$input[$i][$x][0];
				}
			}elseif($i==1){   //取反矩陣之值
				$countyy=count($input[$i][0]);
				if($countii!=$dims || $countyy!=$dims){  //輸入錯誤
					echo "<br><br>第".__LINE__."行錯誤，輸入的矩陣值與向度數不符合！<br><br>請重新輸入！<br><br>";
					die();
				}else{
					for($x=0;$x<$dims;$x++){  //讀出反矩陣之值
						for($y=0;$y<$dims;$y++){
							if(is_numeric($input[$i][$x][$y])){
								$sigma[]=$input[$i][$x][$y];
							}else{
								die("<br><br>輸入的矩陣值必須是數字！<br><br>請重新輸入！<br>");
							}
						}
					}
				}
			}
			$i++;
		}

		$myin[0]=implode(_SPLIT_SYMBOL, $DimNames);
		$myin[1]=implode(_SPLIT_SYMBOL, $sigma);
		$myin[2]=$_REQUEST[mydet]*1;

		//檔名轉小寫
		$ii=count($this_upload_file);
		for($i=0;$i<$ii;$i++){
			$org_file = $mydir.$this_upload_file[$i];
			$low_filename = strtolower($this_upload_file[$i]);
			$low_file = $mydir.$low_filename;
			$RenameRsu=rename($org_file, $low_file);
			if(!$RenameRsu){
				die(__LINE__."改檔名錯誤！");
			}
		}
        
		//-- 更新資料庫
		$table_name   = 'concept_info_dim';
		$table_values = array(
			'dim' => $dims,
			'dim_name' => $myin[0],
			'sigma_det' => $myin[1]._SPLIT_SYMBOL.$myin[2],
			'dim_name_file' => $this_upload_file['0'],
			'sigma_file' => $this_upload_file['1']
		);     
		$table_field='cs_id ='.$cs_id;
		$result = $dbh->autoExecute($table_name, $table_values,
                        DB_AUTOQUERY_UPDATE, $table_field);
		if($result){
			echo '<br>測驗結構（'.$_REQUEST['myunitname'].'）修改成功！<br>';
		}
	
		//-- 更新資料庫
		$table_name   = 'concept_info';
		$table_values = array(
			'concept' => $_REQUEST['myunitname']
		);
		$table_field='cs_sn ='.$cs_sn;
		$result = $dbh->autoExecute($table_name, $table_values,
                        DB_AUTOQUERY_UPDATE, $table_field);
        if($result){
			echo '<br>測驗結構（'.$_REQUEST['myunitname'].'）修改成功！<br>';
		}
	}

	$form1 = new HTML_QuickForm('frmTest','post','');


	$publish[$myCS->publisher_id]=id2publisher($myCS->publisher_id);
	$subject[$myCS->subject_id]=id2subject($myCS->subject_id);

	$books[$myCS->vol]="第".num2chinese($myCS->vol)."冊";
	$grades[$myCS->grade]=num2chinese($myCS->grade)."年級";
	$units[$myCS->unit]="第".$myCS->unit."單元";
	for ($i=2;$i<=8;$i++)  $mydims[$i]=$i;  //建立向度數

	$base='<a href="modules.php?op=modload&name='.$module_name.'&file=download&pid='.$myCS->publisher_id.'&sid='.$myCS->subject_id.'&vid='.$myCS->vol.'&uid='.$myCS->unit.'&dfn=';
	$cdata=array("各向度名稱", "EAP反矩陣");
	for($j=0;$j<count($cdata);$j++){
		if($myCS->dim_file[$j]==""){
			${a.$j}=($j+1).'.'."$cdata[$j]";
		}else{
			${a.$j}=($j+1).'.'.$base.$myCS->dim_file[$j].'" target="blank">'.$cdata[$j].'</a>';
		}
	}
	//debug_msg("第".__LINE__."行 myCS->cs_file ", $myCS->cs_file);

	$form1->addElement('header', 'myheader', '修改MCAT測驗結構');  //標頭文字
	$form1->addElement('select', 'mypublisher', '版本', $publish);
	$form1->addElement('select', 'mysubject', '領域', $subject);
	$form1->addElement('select', 'mybook', '冊別', $books);
	$form1->addElement('select', 'mygrade', '適用年級', $grades);
	$form1->addElement('select', 'myunit', '單元編號', $units);
	$form1->addElement('text', 'myunitname', '測驗名稱');
	$form1->addElement('select', 'mydims', '共有幾向度？', $mydims);
	$form1->addElement('file','userfile[]',$a0);
	$form1->addElement('file','userfile[]',$a1);
	$form1->addElement('text', 'mydet', '行列式值(數字)');
	$form1->addElement('hidden','op','modload');
	$form1->addElement('hidden','name',$module_name);
	$form1->addElement('hidden','file','index');
	$form1->addElement('hidden','opt','edit');
	$form1->addElement('hidden','mopt','modify');
	$form1->addElement('hidden','cs_sn', $cs_sn);
	$form1->addElement('submit','btnSubmit','輸入完畢，修改結構');
	$selected = array("mypublisher"=>$myCS->publisher_id,
			"mysubject"=>$myCS->subject_id,
			"mybook"=>$myCS->vol,
			"mygrade"=>$myCS->grade,
			"myunit"=>$myCS->unit,
			"myunitname"=>$myCS->concept_name,
			"mydims"=>$myCS->dims,
			"mydet"=>$myCS->det
	); 
	$form1->addRule('mydet', '「行列式值」只可輸入數字', 'numeric',null, 'client', null, null);
	$form1->addRule('myunitname', '測驗名稱不可空白', 'required',null, 'client', null, null);
	
	$form1->setDefaults($selected);
	//$form1->freeze();  //固定欄位，不能更改
	$form1->display();
	//debug_msg("第".__LINE__."行 myCS ", $myCS);
	echo '<font color="#FF0000">★★不更改之檔案欄位請留空白！★★</font><br>';
	//echo "範例[<a href=\"examples/cs_map.xls\" target=\"_blank\">1.單元結構</a>][<a href=\"examples/remedy.xls\" target=\"_blank\">2.試卷概念</a>]";
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
		$sql="DELETE FROM concept_info_dim WHERE cs_id='".$cs_id."'";
		$result = $dbh->query($sql);
		$sql="DELETE FROM exam_paper WHERE cs_id='".$cs_id."'";
		$result = $dbh->query($sql);
		$sql="DELETE FROM concept_info WHERE cs_id='".$cs_id."'";
		$result = $dbh->query($sql);
		$sql="DELETE FROM concept_info_plus WHERE cs_id='".$cs_id."'";
		$result = $dbh->query($sql);
		$sql="DELETE FROM exam_paper_access WHERE cs_id='".$cs_id."'";
		$result = $dbh->query($sql);
		$sql="DELETE FROM exam_record_item WHERE cs_id='".$cs_id."'";
		$result = $dbh->query($sql);
		$sql="DELETE FROM concept_item_parameter WHERE cs_id='".$cs_id."'";
		$result = $dbh->query($sql);
		$sql="DELETE FROM exam_paper_access_irt WHERE cs_id='".$cs_id."'";
		$result = $dbh->query($sql);
		$sql="DELETE FROM exam_people WHERE cs_id='".$cs_id."'";
		$result = $dbh->query($sql);
		$sql="DELETE FROM exam_record_irt WHERE cs_id='".$cs_id."'";
		$result = $dbh->query($sql);
	}
}

echo '
<table width="100%" border="0" align="center">
  <tr>
	<td align="left">單元列表：</td>
	<td align="right">【<a href="modules.php?op=modload&name='.$module_name.'&file=index">新增單元結構</a>】</td>
  </tr>
</table>
<table width="98%" border="1" align="center" bordercolor="#FFFFFF">
  <tr>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">編號</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">版本</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">領域</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">冊</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">單元</div></td>
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

         $myary=array($ii,  $tran1[$data['publisher_id']], $tran2[$data['subject_id']], $data['vol'], $data['unit'], $data['concept']);
         echo "<tr>";
         $cs_title='';
         for($i=0;$i<count($myary);$i++){
            echo "<td bordercolor=\"#4D6185\" bgcolor=\"".$table_fill_color[intval($data['vol'])%count($table_fill_color)]."\"><div align=\"center\">".$myary[$i]."</div></td>";
         }
         $cs_title="\n".$myary[1]."-".$myary[2]."-"."第".$myary[3]."冊-第".$myary[4]."單元【".$myary[5]."】";
         //if($data['ready']==0){
            $del_url="modules.php?op=modload&name=".$module_name."&file=index&opt=delete&cs_sn=".$data['cs_sn'];
            $del = "<a href=\"javascript:if (confirm('你確定刪除這個單元？\n' + '".$cs_title."  這個單元的所有試卷、試題及學生測驗結果都會被刪除！')==true) self.location = '".$del_url."';\"><img src=\""._ADP_URL."images/delete.png\" alt=\"刪除單元\" border=\"0\"></a> ";
            $modify_url="modules.php?op=modload&name=".$module_name."&file=index&opt=edit&cs_sn=".$data['cs_sn'];
            $modify = '<a href="'.$modify_url.'"><img src="'._ADP_URL.'images/edit.png" alt="修改單元結構" border="0"></a>';
            echo "<td bordercolor=\"#4D6185\" bgcolor=\"#FF99FF\" align=\"center\">".$modify."&nbsp;&nbsp;&nbsp;".$del."</td></tr>";
         //}elseif($data['ready']==1){
            //$lock_pic="<img src=\""._ADP_URL."images/locked.gif\" alt=\"已上鎖\" border=\"0\"> ";
            //echo "<td bordercolor=\"#4D6185\" bgcolor=\"#FF99FF\" align=\"center\">".$lock_pic."</td></tr>";
         //}
         $ii++;
      }
	}
	echo "</table>";
}

echo "<br><br>";



//require_once "feet.php"; 

?>
