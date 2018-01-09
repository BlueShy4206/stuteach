<?php
require_once "HTML/QuickForm.php";
require_once 'HTTP/Upload.php';
require_once 'Date.php';
require_once "include/adp_API.php";


$module_name = basename(dirname(__FILE__));

if($user_data->access_level<=70){
	Header("Location: index.php");
}
$width="100%";
echo "<br>";
//-- 顯示主畫面上方子選單
IMPORT_USER_table_header();
//TableTitle($width,'匯入所有帳號資料');
//-- 顯示主畫面
echo '<br>
<table width="100%" border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" bordercolor="#FFCC33">';

$form = new HTML_QuickForm('frmadduser','post',$_SERVER['PHP_SELF']);

if ($_POST['import_user'] && $form->validate()) {

	$upload = new HTTP_Upload();
	$mydir=_ADP_TMP_UPLOAD_PATH;  //預設上傳結構概念矩陣檔之目錄
	if (!is_dir($mydir)){
		mkdir($mydir, 0777);
	}
	if (PEAR::isError($upload)) die (__LINE__.$upload->getMessage());  //顯示錯誤訊息
	$file = $upload->getFiles('userfile');
	if (PEAR::isError($file)) die (__LINE__.$file->getMessage());  //顯示錯誤訊息
    if ($file->isValid()) {
		$file->setName('uniq');
		$file_name = $file->moveTo($mydir); 
		if (PEAR::isError($file_name)){
			die ('發生錯誤：'.$file_name->getMessage());  //顯示錯誤訊息
		}elseif ($file->isMissing()) {
			die ('<br><br>錯誤！上傳檔案不正確！');
		}
		$prop = $file->getProp();   //取得上傳檔案之最後資訊
		$user_school=$_POST['organization'][1];
		$mydate = new Date();
		$user_regdate=$mydate->format('%Y-%m-%d');
		$user_level=$_POST['access_level'];
		$auth_start[0]=date("Y-m-d");
		$auth_start[1]=$auth_start[0]." 00:00:00";
		$mydate = new Date($auth_start[1]);
		$auth_start[2]=$mydate->getDate(DATE_FORMAT_UNIXTIME) ;

		$auth_stop[0]=$_POST[stop_date][Y].'-'.sprintf("%02d", $_POST[stop_date][m]).'-'.sprintf("%02d",$_POST[stop_date][d]);
		$auth_stop[1]=$auth_stop[0]." 23:59:59";
		$mydate = new Date($auth_stop[1]);
		$auth_stop[2]=$mydate->getDate(DATE_FORMAT_UNIXTIME) ;
		$import_data_path=$mydir.$prop['name'];   //上傳檔案的路徑
		$sql="SELECT count(user_id) FROM user_info WHERE organization_id = '".$user_school."'";
		//echo $sql."<br>";
		$max_user_num =& $dbh->getOne($sql);
		chk_file_exist($import_data_path, __LINE__);
		$InputArray=read_excel_2j($import_data_path, __LINE__);
      //debug_msg("第".__LINE__."行 InputArray ", $InputArray);

		$jj=sizeof($InputArray);
		for($j=0;$j<$jj;$j++){
			if ( strlen($InputArray[$j][0]) && $InputArray[$j][0]!='') { //第一筆為抬頭，不檢查，ROW[0]：姓名不為空值
			   //debug_msg("第".__LINE__."行 InputArray ", $InputArray);
			   $MaxArrayKey=max(array_keys($InputArray[$j]));

            for($i=0;$i<=$MaxArrayKey;$i++){
					//$ROW[$i]=iconv("big5", "UTF-8", $InputArray[$j][$i]);   //轉成utf-8
					$ROW[$i]=trim($InputArray[$j][$i]);   //去除空格
				}
				//debug_msg("第".__LINE__."行 ROW ", $ROW);
				
				if($ROW[4]==""){
					$birthday='0000-00-00';
				}else{
					$birthday=$ROW[4];
				}
				if($ROW[9]==""){
					$_REQUEST['organization'][2]='6';
				}else{
					$_REQUEST['organization'][2]=$ROW[9];
				}
				if($ROW[10]==""){
					$_REQUEST['organization'][3]='1';
				}else{
					$_REQUEST['organization'][3]=$ROW[10];
				}

				//debug_msg("第".__LINE__."行 _POST ", $_POST);

				$seating=intval($max_user_num)+$j+2;  //決定流水號
				if($_POST[withID]=="1" and $_POST[withID]!=""){
					$user_id=$ROW[11];
				}else{
					$a=substr($user_school, 0, 1);
					if($a=="0"){
						$user_id=intval($user_school).sprintf("%04d",$seating);
					}else{
						$user_id=$user_school.sprintf("%04d",$seating);
					}
				}
				if($_POST[IDequalPASS]=="1"){
					$pass=$user_id;
				}elseif($ROW[1]==""){
					$pass=rand(100000,999999);
				}else{
					$pass=$ROW[1];
				}

				//檢查帳號是否重複
				$sql2="SELECT count(user_id) FROM user_info WHERE user_id = '".$user_id."'";
				//echo $sql2."<br>";
				$num =& $dbh->getOne($sql2);
				if($num>0){
					die("<br> $user_id 帳號已經存在，請重新匯入！");
				}

				$pass_compiler=pass2compiler($pass);
				$chkName=trim($ROW[0]);
				//echo "<br>seating= $seating    user_id= $user_id<br>";
				$query = 'INSERT INTO user_info (user_id, uname, email, sex, user_regdate, birthday, organization_id, pass, viewpass, city_code, grade, class, identity, tel, mobil, address, exarea) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
				$data = array($user_id, $chkName, $ROW[2], $ROW[3], $user_regdate, $birthday, $_REQUEST['organization'][1], md5($pass), $pass_compiler, $_REQUEST['organization'][0], $_REQUEST['organization'][2], $_REQUEST['organization'][3], $ROW[5], $ROW[6], $ROW[7], $ROW[8], $ROW[12]);
            	//debug_msg("第".__LINE__."行 data ", $data);
            	//die();

            	//-- 寫入資料庫
            	$result=$dbh->query($query, $data);
				if (PEAR::isError($result)) {
					echo "錯誤訊息：".$result->getMessage()."<br>";
					echo "錯誤碼：".$result->getCode()."<br>";
					echo "除錯訊息：".$result->getDebugInfo()."<br>";
				}else{
					//$query = 'INSERT INTO user_status (user_id, access_level, auth_start_time, auth_stop_time, auth_start_date, auth_stop_date) VALUES (?,?,?,?,?,?)';
					//$data = array($user_id, $user_level, $auth_start[2], $auth_stop[2], $auth_start[0], $auth_stop[0]);
					$query = 'INSERT INTO user_status (user_id, access_level, auth_start_time, auth_stop_time, auth_start_date, auth_stop_date) VALUES (?,?,?,?,?,?)';
					$data = array($user_id, $user_level, $auth_start[2], $auth_stop[2], $auth_start[0], $auth_stop[0]);
					$result =$dbh->query($query, $data);
					if (PEAR::isError($result)) {
						echo "錯誤訊息：".$result->getMessage()."<br>";
						echo "錯誤碼：".$result->getCode()."<br>";
						echo "除錯訊息：".$result->getDebugInfo()."<br>";
					}else{
						$msg.='<br>新增帳號'.$user_id.'【'.$ROW[0].'】'.$pass;
						$chk_zero1=substr($user_id, 0, 1);  //處理excel中，0自動消失
						$chk_zero2=substr($pass, 0, 1);  //處理excel中，0自動消失
						if($chk_zero1==0){
	                        $user_id='="'.$user_id.'"';
						}
						//if($chk_zero2==0){
	                        $show_pass='="'.$pass.'"';
						//}
						$csv_content[]=id2org($_POST['organization'][1]).",".$_REQUEST['organization'][2].",".$_REQUEST['organization'][3].",".id2level($user_level).",$user_id,$ROW[0],$show_pass,";
					}
				}
			}
		}
		//unlink($import_data_path);
		$msg.="<hr>";
		$csv_file=$_REQUEST['organization'][1]."_".date("His").'.csv';
		$csv_header="科系,年級,班級,身份,帳號,姓名,密碼,";
		creat_csv($csv_file, $csv_header, $csv_content);
		$base='<a href="'._ADP_URL.'data/tmp/'.$csv_file;
		$csv_url='【'.$base.'" target="blank">下載本次新增之帳號密碼檔</a>】';
		echo $csv_url;
	}
}

ImportUSER(); 
echo '</td></tr><tr><td bordercolor="#FFCC33">';
echo $msg;
echo '說明：<br>';
echo '1.在Windows環境下，鍵入帳號資料，存成 excel 2003 檔<font color=red>(不支援Excel 2007格式)</font>，如 <a href="examples/import_userALLwithID.xls">範例檔</a>。<br>';
//echo '2.在Linux下，鍵入帳號資料，存成 csv 檔，並保留第一列標題檔，如 <a href="examples/import_userALL.csv">範例檔</a>。<br>';
echo '2.利用本功能匯入之帳號，可自行設定年級、班級、帳號。<br>';
echo '</tr></table>';


function ImportUSER(){
	global $dbh, $form, $module_name, $user_data;

	//-- 尋找目前已建立之學校、單位，並初始化"關聯選單"
	/* $select1[0]='年度'; //單位(學校)
	$select2[0][0]='考場';	//科系 */

	//$sql = "select * from city, organization WHERE city.city_code=organization.city_code AND organization.used=1 ORDER BY organization_id";
	$sql = "select * from city, organization WHERE city.city_code=organization.city_code AND organization.used=1 ORDER BY organization_id";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$select1[$row['city_code']]=id2city($row['city_code']);
		$select2[$row['city_code']][$row['organization_id']]=$row['name'];
	}
	/*
	$se1[0]='縣市';
	$se2[0][0]='補習班名稱';

	$sql = "select * from city, firm WHERE city.city_code=firm.city_code ORDER BY firm_id";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$sql2="select count(user_id) from user_info where firm_id='".$row['firm_id']."' and user_id LIKE '".$row['firm_id']."%'";
		$mem_count =& $dbh->getOne($sql2);
		$se1[$row['city_code']]=id2city($row['city_code']);
		$se2[$row['city_code']][$row['firm_id']]=$row['name'];
	}
	*/
	$sql = "select access_level, access_title from user_access WHERE access_level>'0' AND access_level<'90' ORDER BY access_level";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$level[$row['access_level']]=$row['access_title'];
	}
	$startYear=date("Y");
	$options = array(
              'language'  => 'tw',
              'format'    => 'Y-m-d',
              'minYear'   => $startYear,
              'maxYear'   => $startYear+6
               );
	$defaultValue['stop_date']['Y']=$startYear+1;
	$defaultValue['stop_date']['m']=date("m");
	$defaultValue['stop_date']['d']=date("d");

	$form->addElement('header','newheader','匯入所有帳號');
	$sel =& $form->addElement('hierselect', 'organization', '單位(學校)：');  //關聯式選單
	$sel->setOptions(array($select1, $select2));
	//$sel =& $form->addElement('hierselect', 'firm', '所屬補習班：');  //關聯式選單
	//$sel->setOptions(array($se1, $se2));
	$form->addElement('select','access_level','身份：',$level);
	$form->addElement('date', 'stop_date', '終止授權日：', $options);
	$form->addElement('file','userfile','Excel 檔案：');
	$form->addElement("checkbox","withID","功能1：","自訂帳號","1"); 
	$form->addElement("checkbox","IDequalPASS","功能2：","密碼與帳號相同","1"); 
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file','importAllUSERwithID');
	$form->addElement('submit','import_user','開始匯入');
	$form->addRule('organization', '「學校」不可空白！', 'nonzero',null, 'client', null, null);
	$form->addRule('userfile', '不可上傳空檔', 'uploadedfile');
	$form->setRequiredNote('前有<font color=red>*</font>的欄位不可空白');
	$form->setDefaults($defaultValue);

	$form->display();
}

?>

