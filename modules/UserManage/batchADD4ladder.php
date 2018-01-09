<?php
require_once "HTML/QuickForm.php";
require_once 'HTTP/Upload.php';
require_once 'Date.php';
require_once "include/adp_API.php";

if(!$auth->checkAuth()||$user_data->access_level<=80){
	FEETER();
	die();
}

//-- 顯示主畫面上方子選單
//USER_MANAGE_table_header();

//-- 顯示主畫面

echo '<br>
<table width="95%" border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" bordercolor="#FFCC33">';
 
$form = new HTML_QuickForm('frmadduser','post',$_SERVER['PHP_SELF']);

if ($_POST['addnewuser'] && $form->validate()) {
	$user_class=$_POST['organization'][1].$_POST['organization'][2].sprintf("%02d",$_POST['organization'][3]);
	$member_num=$_POST['member_num'];
	$pass=$_POST['pass1'];
	$mydate = new Date();
	$user_regdate=$mydate->format('%Y-%m-%d');
	$birthday='0000-00-00';
	
	$sql="select count(user_id) from user_info where firm_id='{$_POST['firm'][1]}' and grade='{$_POST['organization'][2]}' and user_id LIKE '".$_POST['firm'][1]."%'";
	$used_mem_num =& $dbh->getOne($sql);

	for($i=1;$i<=$member_num;$i++){	
		if($_POST['pass_radom']==1){
			$pass=mt_rand();
		}
		$sn=$i+$used_mem_num;
		if($_POST['access_level']>='2' && $_POST['access_level']<='4'){  //一次性帳號(每個補習班最多有9999個)
			$user_id=$_POST['firm'][1].sprintf("%04d",$sn);
		}else{
			$user_id=$user_class.sprintf("%04d",$sn);
		}
		$auth_start[0]=date("Y-m-d");
		$auth_start[1]=$auth_start[0]." 00:00:00";
		$mydate = new Date($auth_start[1]);
		$auth_start[2]=$mydate->getDate(DATE_FORMAT_UNIXTIME) ;

		$auth_stop[0]=$_POST[stop_date][Y].'-'.sprintf("%02d", $_POST[stop_date][m]).'-'.sprintf("%02d",$_POST[stop_date][d]);
		$auth_stop[1]=$auth_stop[0]." 23:59:59";
		$mydate = new Date($auth_stop[1]);
		$auth_stop[2]=$mydate->getDate(DATE_FORMAT_UNIXTIME) ;
		
		$uname="學生".$sn;
		$_POST['sex']=' ';
		
		$pass_compiler=pass2compiler($pass);
		//-- 寫入資料庫
		$query = 'INSERT INTO user_info (user_id, uname, email, sex, user_regdate, birthday, organization_id, pass, viewpass, city_code, grade, class, identity, tel, mobil, address, class_group, firm_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
		$data = array($user_id, $uname, $_POST['email'], $_POST['sex'], $user_regdate, $birthday, $_POST['organization'][1], md5($pass), $pass_compiler, $_POST['organization'][0], $_POST['organization'][2], $_POST['organization'][3], $_POST['identity'], $_POST['tel'], $_POST['mobil'], $_POST['address'], $_POST['class_group'], $_POST['firm'][1]);
		$result =$dbh->query($query, $data);
		if (PEAR::isError($result)) {
			echo "錯誤訊息：".$result->getMessage()."<br>";
			echo "錯誤碼：".$result->getCode()."<br>";
			//echo "使用者訊息：".$result->getUserInfo()."<br>";
			echo "除錯訊息：".$result->getDebugInfo()."<br>";
		}else{
			$query = 'INSERT INTO user_status (user_id, access_level, auth_start_time, auth_stop_time, auth_start_date, auth_stop_date) VALUES (?,?,?,?,?,?)';
			$data = array($user_id, $_POST['access_level'], $auth_start[2], $auth_stop[2], $auth_start[0], $auth_stop[0]);
			$result =$dbh->query($query, $data);
			$query = 'INSERT INTO user_parents (user_id, phone1) VALUES (?,?)';
			$data = array($user_id, $_POST['mobil']);
			$result =$dbh->query($query, $data);
			if (PEAR::isError($result)) {
				echo "錯誤訊息：".$result->getMessage()."<br>";
				echo "錯誤碼：".$result->getCode()."<br>";
				echo "除錯訊息：".$result->getDebugInfo()."<br>";
			}else{
				//$msg.='<br>新增帳號 '.$user_id.'【'.$uname.'】完成--密碼：'.$pass;
				$csv_content[]="$user_id,$uname,$pass";
			}
		}
	}

	$csv_file=date('md_His_').mt_rand().'.csv';
	//$csv_file_loc=_ADP_TMP_UPLOAD_PATH.$csv_file;
	//$_SESSION['dfn']=$csv_file;
	$csv_header="帳號,姓名,密碼";
	creat_csv($csv_file, $csv_header, $csv_content);
	$base='<a href="'._ADP_URL.'data/tmp/'.$csv_file;
	$csv_url='【'.$base.'" target="blank">下載本次新增之帳號密碼檔</a>】';
	echo $csv_url;
	

}
if($_REQUEST['opt']=='onlyone'){
	onlyoneBATCHaddUSER($msg, $user_data->access_level, $_REQUEST['opt']); 
}else{
	BATCHaddUSER($msg, $user_data->access_level); 
}
echo '</td></tr></table>';

function onlyoneBATCHaddUSER($msg,$my_level,$opt){
	global $dbh, $form;

	if($msg!=""){
		echo "<br>$msg<br>";
	}
	
	//-- 尋找目前已建立之學校、單位，並初始化"關聯選單"
	$select1[0]='縣市';
	$select2[0][0]='學校名稱';
	$select3[0][0][0]='年級';
	$select4[0][0][0][0]='班級';

	//$sql = "select * from city, organization WHERE city.city_code=organization.city_code ORDER BY organization_id";
	$sql = "select * from city, organization WHERE city.city_code='1' and organization.city_code='1' ORDER BY organization_id";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$select1[$row['city_code']]=id2city($row['city_code']);
		$select2[$row['city_code']][$row['organization_id']]=$row['name'];
		for($i=6;$i<=6;$i++){  //一次性帳號都開在六年一班
			$select3[$row['city_code']][$row['organization_id']][$i]=num2chinese($i)."年";
			for($j=1;$j<=1;$j++){
				$select4[$row['city_code']][$row['organization_id']][$i][$j]=num2chinese($j)."班";
			}
		}
	}

	//-- 尋找目前已建立之補習班，並初始化"關聯選單"
	$se1[0]='縣市';
	$se2[0][0]='補習班名稱';
	$se3[0][0][0]='年級';

	$sql = "select * from city, firm WHERE city.city_code=firm.city_code ORDER BY firm_id";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$sql2="select count(user_id) from user_info where firm_id='".$row['firm_id']."' and user_id LIKE '".$row['firm_id']."%'";
		$mem_count =& $dbh->getOne($sql2);
		$se1[$row['city_code']]=id2city($row['city_code']);
		$se2[$row['city_code']][$row['firm_id']]=$row['name']."(「一次性」、「展示用」、「測試用」帳號數： $mem_count )";
	}

	$sql = "select access_level, access_title from user_access WHERE access_level>='2' AND access_level<='4'";

	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$level[$row['access_level']]=$row['access_title'];
	}
	for ($i=50;$i<=1000;$i=$i+50)  $num[$i]=$i;  
	$startYear=date("Y");
	$options = array(
              'language'  => 'tw',
              'format'    => 'Y-m-d',
              'minYear'   => $startYear,
              'maxYear'   => $startYear+2
               );
	$defaultValue['stop_date']['Y']=$startYear+1;
	$defaultValue['stop_date']['m']=date("m");
	$defaultValue['stop_date']['d']=date("d");
	$form->addElement('header','newheader','新增測試帳號');
	$form->addElement('password','pass1','密碼：');
	$form->addElement('password','pass2','密碼確認：');
	$form->addElement("checkbox","pass_radom","特殊功能：","密碼隨機產生","1"); 
	$sel =& $form->addElement('hierselect', 'organization', '所屬學校：');  //關聯式選單
	$sel->setOptions(array($select1, $select2, $select3, $select4));
	$sel =& $form->addElement('hierselect', 'firm', '所屬補習班：');  //關聯式選單
	$sel->setOptions(array($se1, $se2));
	$form->addElement('select', 'member_num', '本次新增人數：', $num);
	$form->addElement('date', 'stop_date', '終止授權日：', $options);
	$form->addElement('select','access_level','身份：',$level);
	$form->addElement('text','mobil','手機號碼：');
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name','UserManage');
	$form->addElement('hidden','file','batchADD');
	$form->addElement('hidden','opt',$opt);
	$form->addElement('submit','addnewuser','新增帳號');
    $form->addRule(array('pass1','pass2'),'兩個密碼不相同，請重新輸入！','compare', null, 'client', null, null);
    $form->addRule('member_num', '「本次新增人數」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('pass1', '「密碼」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('member_num', '「班級人數」必須是數字！', 'numeric', null, 'client', null, null);
	$form->addRule('mobil', '「手機號碼」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('organization', '「所屬學校」不可空白！', 'nonzero',null, 'client', null, null);
	$form->addRule('firm', '「所屬補習班」不可空白！', 'nonzero',null, 'client', null, null);
	$form->addRule('organization', '「所屬學校」不可空白！', 'required',null, 'client', null, null);
	$form->addRule('firm', '「所屬補習班」不可空白！', 'required',null, 'client', null, null);
	$form->setRequiredNote('1.前有<font color=red>*</font>的欄位不可空白<br>2.若勾選「密碼隨機產生」，原密碼欄任意輸入即可');
	$form->setDefaults($defaultValue);

	$form->display();
}

function BATCHaddUSER($msg,$my_level){
	global $dbh, $form;

	echo "<br>$msg<br>";
	
	//-- 尋找目前已建立之學校、單位，並初始化"關聯選單"
	$select1[0]='縣市';
	$select2[0][0]='學校名稱';
	$select3[0][0][0]='年級';
	$select4[0][0][0][0]='班級';

	$sql = "select * from city, organization WHERE city.city_code=organization.city_code AND organization.used='1' ORDER BY organization_id";
	
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$select1[$row['city_code']]=id2city($row['city_code']);
		$select2[$row['city_code']][$row['organization_id']]=$row['name'];
		for($i=1;$i<=6;$i++){
			$select3[$row['city_code']][$row['organization_id']][$i]=num2chinese($i)."年";
			for($j=1;$j<=20;$j++){
				$select4[$row['city_code']][$row['organization_id']][$i][$j]=num2chinese($j)."班";
			}
		}
	}

	//-- 尋找目前已建立之補習班，並初始化"關聯選單"
	$se1[0]='縣市';
	$se2[0][0]='補習班名稱';
	$se3[0][0][0]='年級';

	$sql = "select * from city, firm WHERE city.city_code=firm.city_code ORDER BY firm_id";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$se1[$row['city_code']]=id2city($row['city_code']);
		$se2[$row['city_code']][$row['firm_id']]=$row['name'];
	}

	$sql = "select access_level, access_title from user_access WHERE access_level>'0' AND access_level<'".$my_level."' ORDER BY access_level";

	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$level[$row['access_level']]=$row['access_title'];
	}

	//-- 顯示選單
	//echo "☆★☆ 批次新增使用者 ☆★☆<br>";

	// And add the selection options
	$options = array(
              'language'  => 'tw',
              'format'    => 'Y-m-d',
              'minYear'   => 1950,
              'maxYear'   => 2006
               );

	$form->addElement('header','newheader','批次新增使用者帳號');
	//$form->addElement('text','user_id','帳號：');
	$form->addElement('password','pass1','密碼：');
	$form->addElement('password','pass2','密碼確認：');
	$sel =& $form->addElement('hierselect', 'organization', '所屬學校：');  //關聯式選單
	$sel->setOptions(array($select1, $select2, $select3, $select4));
	$sel =& $form->addElement('hierselect', 'firm', '所屬補習班：');  //關聯式選單
	$sel->setOptions(array($se1, $se2));
	$form->addElement('text','member_num','本次新增人數：');
	//$form->addElement("radio","sex","性別：","男","男"); 
	//$form->addElement("radio","sex",null,"女","女");
	$form->addElement('select','access_level','身份：',$level);
	//$form->addElement('date', 'birthday', '生日：', $options);
	//$form->addElement('text','identity','身份證字號：');
	$form->addElement('text','tel','住所電話：');
	$form->addElement('text','mobil','手機號碼：');
	$form->addElement('text','address','居住地址：');
	$form->addElement('text','email','電子郵件信箱：');
	//$form->addElement('text','class_group','班系：');
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name','UserManage');
	$form->addElement('hidden','file','batchADD');
	$form->addElement('submit','addnewuser','新增帳號');
    $form->addRule(array('pass1','pass2'),'兩個密碼不相同，請重新輸入！','compare', null, 'client', null, null);
    $form->addRule('member_num', '「班級人數」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('pass1', '「密碼」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('member_num', '「班級人數」必須是數字！', 'numeric', null, 'client', null, null);
	$form->addRule('mobil', '「手機號碼」不可空白！', 'required', null, 'client', null, null);
	//$form->addRule('mobil', '「班級人數」必須是數字！', 'numeric', null, 'client', null, null);
	//$form->addRule('sex', '「性別」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('organization', '「所屬學校」不可空白！', 'nonzero',null, 'client', null, null);
	$form->addRule('firm', '「所屬補習班」不可空白！', 'nonzero',null, 'client', null, null);
	$form->setRequiredNote('前有<font color=red>*</font>的欄位不可空白');
	
	$form->display();
}

?>

