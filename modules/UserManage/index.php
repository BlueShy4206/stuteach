<?php
require_once "HTML/QuickForm.php";
require_once 'HTTP/Upload.php';
require_once 'Date.php';
require_once "include/adp_API.php";
$module_name = basename(dirname(__FILE__));

if(!$auth->checkAuth()||$user_data->access_level<=30){
	FEETER();
	die();
}

if(_SYS_VER=="adp"){
	//-- 顯示主畫面上方子選單
	USER_MANAGE_table_header();
}
echo '<table width="80%" border="0" cellspacing="4" cellpadding="0">
          <tr>
            <td scope="col">';

$form = new HTML_QuickForm('frmadduser','post',$_SERVER['PHP_SELF']);
//$reg_user_id=$_POST['reg_user_id'];
if ($_POST['addnewuser'] && $form->validate()) {
	$reg_user_id=strtolower($_POST['reg_user_id']);
	$pass=$_POST['pass1'];
	$mydate = new Date();
	$user_regdate=$mydate->format('%Y-%m-%d');

	//--取得授權日期
	$auth_start[0]=$_POST[start_date][Y].'-'.sprintf("%02d", $_POST[start_date][m]).'-'.sprintf("%02d",$_POST[start_date][d]);
	$auth_start[1]=$auth_start[0]." 00:00:00";
	$mydate = new Date($auth_start[1]);
	$auth_start[2]=$mydate->getDate(DATE_FORMAT_UNIXTIME) ;

	$auth_stop[0]=$_POST[stop_date][Y].'-'.sprintf("%02d", $_POST[stop_date][m]).'-'.sprintf("%02d",$_POST[stop_date][d]);
	$auth_stop[1]=$auth_stop[0]." 23:59:59";
	$mydate = new Date($auth_stop[1]);
	$auth_stop[2]=$mydate->getDate(DATE_FORMAT_UNIXTIME) ;
	//--end取得授權日期
	$birthday=sprintf("%04d",$_POST['birthday']['Y']).'-'.sprintf("%02d",$_POST['birthday']['m']).'-'.sprintf("%02d",$_POST['birthday']['d']);
	
	if($user_data->access_level==31){  //店長
		//-- 更新資料庫 user_info
		$table_name   = 'user_info';
		$table_values = array(
			'uname' => $_POST['uname'],
			'sex' => $_POST['sex'],
			'organization_id' => $_POST['organization'][1],
			'birthday' => $birthday,
			'grade' => $_POST['class'][0],
			'class' => $_POST['class'][1]
		);
		if(isset($_POST['pass1']) && $_POST['pass1']!=""){   //若密碼有值，則更新密碼
			$table_values['viewpass']=pass2compiler($_POST['pass1']);
		}
		$table_field='user_id=\''.$reg_user_id.'\' and firm_id=\''.$user_data->firm_id.'\'';
		$result = $dbh->autoExecute($table_name, $table_values,
                        DB_AUTOQUERY_UPDATE, $table_field);
		if($result==1){
			$msg='<br>帳號 '.$reg_user_id.'【'.$_REQUEST['uname'].'】已經開通完成';
		}
	}elseif($user_data->access_level>80){ 
		$reg_firm_id=$_REQUEST['firm'][1];
		if($user_data->access_level==81){ //階梯管理者
			$_REQUEST['uname']="";
			$_REQUEST['organization'][0]="";
			$_REQUEST['organization'][1]="000000";
			$_REQUEST['class'][0]="";
			$_REQUEST['class'][1]="";
		}
		$pass_compiler=pass2compiler($pass);
		//-- 寫入資料庫
		$query = 'INSERT INTO user_info (user_id, uname, email, sex, user_regdate, birthday, organization_id, pass, viewpass, city_code, grade, class, identity, tel, mobil, address, class_group, firm_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
		$data = array($reg_user_id, $_REQUEST['uname'], $_REQUEST['email'], $_REQUEST['sex'], $user_regdate, $birthday, $_REQUEST['organization'][1], md5($pass), $pass_compiler, $_REQUEST['firm'][0], $_REQUEST['class'][0], $_REQUEST['class'][1], $_REQUEST['identity'], $_REQUEST['tel'], $_REQUEST['mobil'], $_REQUEST['address'], $_REQUEST['class_group'], $reg_firm_id);
		$result =$dbh->query($query, $data);
		if (PEAR::isError($result)) {
			echo "錯誤訊息：該帳號已經存在！<br>";
		}else{  //新增user_status資料
			$query = 'INSERT INTO user_status (user_id, access_level, auth_start_time, auth_stop_time, auth_start_date, auth_stop_date) VALUES (?,?,?,?,?,?)';
			$data = array($reg_user_id, $_POST['access_level'], $auth_start[2], $auth_stop[2], $auth_start[0], $auth_stop[0]);
			$result =$dbh->query($query, $data);
			if (PEAR::isError($result)) {
				echo "錯誤訊息：".$result->getMessage()."<br>";
				echo "錯誤碼：".$result->getCode()."<br>";
				echo "除錯訊息：".$result->getDebugInfo()."<br>";
			}else{
				$msg='<br>新增帳號 '.$reg_user_id.'【'.id2CityFirm($reg_firm_id).'】完成';
			}
		}
	}

	if(_SYS_VER=="ladder" && ($user_data->access_level==31 || $user_data->access_level==91)){
		$sql = "select count(*) from user_parents where user_id = '{$reg_user_id}'";
		$num =& $dbh->getOne($sql);
		if($num==0){  //新增資料
			$query = 'INSERT INTO user_parents (user_id, father_name, fmobile, mother_name, mmobile, phone1, phone2, caretaker, care_age_id, address, city_code, email) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)';
			$data = array($reg_user_id, $_POST['father_name'], $_POST['fmobil'], $_POST['mother_name'], $_POST['mmobile'], $_POST['phone1'], $_POST['phone2'], $_POST['caretaker'], $_POST['care_age_id'], $_POST['address'], $_POST['parent_ccd'], $_POST['email']);
			$result =$dbh->query($query, $data);
			if($result==1){
				$msg.='<br>新增帳號 '.$reg_user_id.'【'.$_REQUEST['uname'].'】的家長資料完成';
			}
		}else{  //修改狀態
			$table_name   = 'user_parents';
			$table_fields = array('father_name', 'fmobile', 'mother_name', 'mmobile', 'phone1', 'phone2', 'caretaker', 'care_age_id', 'address', 'city_code', 'email');
			$table_values = array($_POST['father_name'], $_POST['fmobil'], $_POST['mother_name'], $_POST['mmobile'], $_POST['phone1'], $_POST['phone2'], $_POST['caretaker'], $_POST['care_age_id'], $_POST['address'], $_POST['parent_ccd'], $_POST['email']);
			$sth = $dbh->autoPrepare($table_name, $table_fields,
                        DB_AUTOQUERY_UPDATE, "user_id ='".$reg_user_id."'");
			if($dbh->execute($sth, $table_values)){
				$msg.='<br>修改帳號 '.$reg_user_id.'【'.$_REQUEST['uname'].'】的家長資料完成';
			}
		}
		//修改兄弟姊妹資料(刪除舊資料再新增之)
		$sql="DELETE FROM user_family WHERE user_id='".$reg_user_id."'";
		$result = $dbh->query($sql);
		for($i=1;$i<=4;$i++){
			if($_POST['familyname'.$i]!=""){
				$query = 'INSERT INTO user_family (user_id, sex, organization_id, grade, class, family_name) VALUES (?,?,?,?,?,?)';
				$data = array($reg_user_id, $_POST['sex'.$i], $_POST['organization'.$i][1], $_POST['class'.$i][0], $_POST['class'.$i][1], $_POST['familyname'.$i]);
				$result =$dbh->query($query, $data);
			}
		}
		if($result==1){
			$msg.='<br>編修帳號 '.$reg_user_id.'【'.$_REQUEST['uname'].'】的兄弟姊妹資料完成';
		}
	}
}

if($msg!=""){	echo "$msg <br>";	}

if(_SYS_VER=="ladder"){
	if($user_data->access_level==81 && $_GET[q_user_id]==""){   //階梯管理者
		addUSER_auth($user_data->access_level);
	}elseif($user_data->access_level==31 && !isset($_GET[q_user_id])){   //店長
		$title[0]='帳號開通';   $title[1]='資料查詢';
		add_header($title);
		OpenTable2();

		$sql="select user_id from user_info where firm_id='".$user_data->firm_id."' and ( organization_id='000000' or uname='') order by user_id";
		$result =$dbh->query($sql);
		while ($row=$result->fetchRow()){
			$account[$row['user_id']]=$row['user_id'];
		}
		if(count($account)>0){  // 有未開通的帳號
			$form1 = new HTML_QuickForm('frmadduser','post',$_SERVER['PHP_SELF']);
			$form1->addElement('select','update_user_id','目前有以下帳號等待開通：',$account);
			$form1->addElement('hidden','op','modload');
			$form1->addElement('hidden','name',$module_name);
			$form1->addElement('hidden','file','index');
			$form1->addElement('hidden','opt','update');
			$form1->addElement('submit','updatenewuser','設定帳號');
			$form1->display();
		}else{
			echo "目前沒有「待開通帳號」！<br>";
		}
		if($_POST['opt']=='update' && !isset($_GET[q_user_id])){ 
			UpdateUser($user_data->access_level, $_POST['update_user_id'], $change_pass=0);
			$form->display();
		}
		CloseTable2();
	}elseif($user_data->access_level==91 && $_GET[q_user_id]==""){   //管理者
		addUSER_ladder($user_data->access_level);
	}
	if($_GET['opt']=="edit" && $user_data->access_level>30){
		$sql="select * from user_info where user_id='".$_GET['q_user_id']."'";
		$result = $dbh->query($sql);
		while ($data = $result->fetchRow()) {
			//echo "<pre>";
			//print_r($data);
			$defaultValue['uname']=$data['uname'];
			$defaultValue['email']=$data['email'];
			$defaultValue['sex']=$data['sex'];
			$defaultValue['reg_user_id'][0]=$_GET['q_user_id'];
			$pieces = explode("-", $data['birthday']);
			$defaultValue['birthday']['Y']=$pieces[0];
			$defaultValue['birthday']['m']=$pieces[1];
			$defaultValue['birthday']['d']=$pieces[2];
			$defaultValue['organization'][0]=$data['city_code'];
			$defaultValue['organization'][1]=$data['organization_id'];
			$defaultValue['class'][0]=$data['grade'];
			$defaultValue['class'][1]=$data['class'];
			$defaultValue['pass1']=pass2compiler($data['viewpass']);
			$defaultValue['pass2']=pass2compiler($data['viewpass']);
		}
		$sql="select * from user_parents where user_id='".$_GET['q_user_id']."'";
		$result = $dbh->query($sql);
		while ($data = $result->fetchRow()) {
			$defaultValue['father_name']=$data['father_name'];
			$defaultValue['mother_name']=$data['mother_name'];
			$defaultValue['fmobil']=$data['fmobile'];
			$defaultValue['mmobile']=$data['mmobile'];
			$defaultValue['phone1']=$data['phone1'];	$defaultValue['phone2']=$data['phone2'];
			$defaultValue['caretaker']=$data['caretaker'];
			$defaultValue['address']=$data['address'];
			$defaultValue['city_code']=$data['city_code'];
			$defaultValue['email']=$data['email'];
			$defaultValue['care_age_id']=$data['care_age_id'];
			$defaultValue['parent_ccd']=$data['city_code'];
		}
		$i=1;
		$sql="select * from user_family where user_id='".$_GET['q_user_id']."'";
		$result = $dbh->query($sql);
		while ($data = $result->fetchRow()) {
			$defaultValue['familyname'.$i]=$data['family_name'];
			$defaultValue['sex'.$i]=$data['sex'];
			$defaultValue['organization'.$i][0]=$defaultValue['organization'][0];
			$defaultValue['organization'.$i][1]=$data['organization_id'];
			$defaultValue['class'.$i][0]=$data['grade'];
			$defaultValue['class'.$i][1]=$data['class'];
			$i++;
		}
		//echo "<pre>";
		//print_r($defaultValue);
		$q_base='<a href="modules.php?op=modload&name=UserManage&file=InquiryUSER&opt=list&school='.$_REQUEST['school'].'&grade='.$_REQUEST['grade'].'&class='.$_REQUEST['class'].'">【按此返回】</a>';
		$title="編輯 ".$defaultValue['reg_user_id'][0]."【".$defaultValue['uname']."】的個人資料";
		$title.="&nbsp;&nbsp;&nbsp;".$q_base;
		TableTitle('760', $title);
		OpenTable2();
		UpdateUser($user_data->access_level, $_GET['q_user_id'], $change_pass=1);
		$form->setDefaults($defaultValue);
		$form->display();
		CloseTable2();
		echo "</td></tr></table>";
	}
}else{
	addUSER($msg, $user_data->access_level); 
}

echo "</td></tr></table>";

function addUSER_ladder($my_level){
	global $dbh, $form, $module_name;

	$title[0]='新增帳號';   $title[1]='資料查詢';
	add_header($title);

	echo '<tr>
            <td>
              <table width="100%" border="0" cellspacing="4" cellpadding="0">
                <tr>
                  <td colspan="3" scope="col"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td scope="col"><img src="'._THEME_IMG.'main_lt.gif" width="12" height="12" /></td>
                      <td width="98%" background="'._THEME_IMG.'main_ct.gif" scope="col"></td>
                      <td scope="col"><img src="'._THEME_IMG.'main_rt.gif" width="12" height="12" /></td>
                    </tr>
                    <tr>
                      <td background="'._THEME_IMG.'main_lc.gif"></td>
                      <td align="center">';

		
	//-- 尋找目前已建立之學校、單位，並初始化"關聯選單"
	$select1[0]='==學校所在縣市==';
	$select2[0][0]='==學 校 名 稱==';
	
	$sql = "select * from city, organization WHERE city.city_code=organization.city_code ORDER BY organization_id";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$select1[$row['city_code']]=id2city($row['city_code']);
		$select2[$row['city_code']][$row['organization_id']]=$row['name'];
		
	}

	$select3[0]='年級';
	$select4[0][0]='班級';
	for($i=3;$i<=6;$i++){
		$select3[$i]="$i 年";
		for($j=1;$j<=20;$j++){
			$select4[$i][$j]="$j 班";
		}
	}

	$sql = "select access_level, access_title from user_access WHERE access_level>'0' AND access_level<'".$my_level."' ORDER BY access_level";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$level[$row['access_level']]=$row['access_title'];
	}

	//-- 顯示選單
	//echo "☆★☆ 新增使用者 ☆★☆<br>";

	// And add the selection options
	$max_year = date("Y")-6;
	$min_year = $max_year-6;
	$options = array(
              'language'  => 'tw',
              'format'    => 'Y-m-d',
              'minYear'   => $min_year,
              'maxYear'   => $max_year
               );

	$startYear=date("Y");
	$ops = array(
              'language'  => 'tw',
              'format'    => 'Y-m-d',
              'minYear'   => $startYear,
              'maxYear'   => $startYear+2
               );
	$defaultValue['start_date']['Y']=$startYear;
	$defaultValue['start_date']['m']=date("m");
	$defaultValue['start_date']['d']=date("d");
	$defaultValue['stop_date']['Y']=$startYear+1;
	$defaultValue['stop_date']['m']=date("m");
	$defaultValue['stop_date']['d']=date("d");

	//-- 尋找目前已建立之學校、單位，並初始化"關聯選單"
	$sel1[0]='==縣市==';
	$sel2[0][0]='==加盟店名稱==';
	
	$sql = "select * from city, firm WHERE city.city_code=firm.city_code ORDER BY firm_id";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$sel1[$row['city_code']]=id2city($row['city_code']);
		$sel2[$row['city_code']][$row['firm_id']]=$row['name'];
		
	}

	$form->addElement('header','header1',"帳號資料");
	$form->addElement('text','reg_user_id','帳號(身份證字號)：');
	$form->addElement('password','pass1','密碼：');
	$form->addElement('password','pass2','密碼確認：');
	$form->addElement('text','uname','姓名：');
	$form->addElement("radio","sex","性別：","男","男"); 
	$form->addElement("radio","sex",null,"女","女");
	$form->addElement('date', 'birthday', '生日：', $options);
	$sel =& $form->addElement('hierselect', 'organization', '就讀學校：');  //關聯式選單
	$sel->setOptions(array($select1, $select2));
	$sel =& $form->addElement('hierselect', 'class', '班級：');  //關聯式選單
	$sel->setOptions(array($select3, $select4));
	$sel =& $form->addElement('hierselect', 'firm', '隸屬之加盟店：');  //關聯式選單
	$sel->setOptions(array($sel1, $sel2));
	$form->addElement('select','access_level','身份：',$level);
	$form->addElement('date', 'start_date', '開始授權日：', $ops);
	$form->addElement('date', 'stop_date', '終止授權日：', $ops);

	//$form->addElement('text','identity','身份證字號：');
	//$form->addElement('text','tel','住所電話：');
	//$form->addElement('text','mobil','手機號碼：');
	//$form->addElement('text','address','居住地址：');
	//$form->addElement('text','email','電子郵件信箱：');
	//$form->addElement('text','class_group','班系：');
	
	//-- 家長資料
	$home_city[0]="縣市";
	$sql = "select * from city";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$home_city[$row['city_code']]=$row['city_name'];		
	}
	
	$care_relation[0]="關係";
	$sql = "select * from user_family_relation";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$care_relation[$row['care_age_id']]=$row['family_relation'];		
	}
	$form->addElement('header','header2','家長資料');
	$form->addElement('text','father_name','父親姓名：');
	$form->addElement('text','fmobil','　　　　　　　父親手機號碼：');
	$form->addElement('text','mother_name','母親姓名：');
	$form->addElement('text','mmobile','母親手機號碼：');
	$form->addElement('text','phone1','聯絡電話(1)：');
	$form->addElement('text','phone2','聯絡電話(2)：');
	$form->addElement('text','caretaker','主要照顧者：');
	$form->addElement('select','care_age_id','關係：',$care_relation);
	$form->addElement('text','address','地址：');
	$form->addElement('select','parent_ccd','縣市：',$home_city);
	$form->addElement('text','email','電子郵件信箱：');
	
	//-- 兄弟姊妹
	for($i=1;$i<=4;$i++){
		$form->addElement('header','header'.$i,'兄弟姊妹'.$i.'(無則免填)');
		$form->addElement('text','familyname'.$i,'姓名：');
		$form->addElement("radio","sex".$i,"性別：","男","男"); 
		$form->addElement("radio","sex".$i,null,"女","女");
		$sel =& $form->addElement('hierselect', 'organization'.$i, '就讀學校：');  //關聯式選單
		$sel->setOptions(array($select1, $select2));
		$sel =& $form->addElement('hierselect', 'class'.$i, '班級：');  //關聯式選單
		$sel->setOptions(array($select3, $select4));

	}
	$form->addElement('header','header',"　");
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file','index');
	$form->addElement('submit','addnewuser','填寫完畢，送出');
    $form->addRule(array('pass1','pass2'),'兩個密碼不相同，請重新輸入！','compare', null, 'client', null, null);
    $form->addRule('reg_user_id', '「帳號」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('pass1', '「密碼」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('uname', '「姓名」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('sex', '「性別」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('organization', '「就讀學校」不可空白！', 'nonzero',null, 'client', null, null);
	$form->addRule('class', '「班級」不可空白！', 'nonzero',null, 'client', null, null);
	$form->addRule('phone1', '「聯絡電話(1)」不可空白！', 'required', null, 'client', null, null);
	$form->setRequiredNote('前有<font color=red>*</font>的欄位不可空白');
	$form->setDefaults($defaultValue);
	$form->display();

	echo '</td>
                      <td background="'._THEME_IMG.'main_rc.gif"></td>
                    </tr>
                    <tr>
                      <td><img src="'._THEME_IMG.'main_ld.gif" width="12" height="12" /></td>
                      <td background="'._THEME_IMG.'main_cd.gif"></td>
                      <td><img src="'._THEME_IMG.'main_rd.gif" width="12" height="12" /></td>
                    </tr>
                  </table></td>
                </tr>';
	echo '</td></tr></table>';

}

function UpdateUser($my_level, $update_user_id, $change_pass){
	global $dbh, $form, $module_name, $user_data;


	//-- 尋找目前已建立之學校、單位，並初始化"關聯選單"
	$select1[0]='==學校所在縣市==';
	$select2[0][0]='==學 校 名 稱==';
	
	$sql = "select * from city, organization WHERE city.city_code='{$user_data->city_code}' and organization.city_code='{$user_data->city_code}' ORDER BY organization_id";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$select1[$row['city_code']]=id2city($row['city_code']);
		$select2[$row['city_code']][$row['organization_id']]=$row['name'];
		
	}

	$select3[0]='年級';
	$select4[0][0]='班級';
	for($i=3;$i<=6;$i++){
		$select3[$i]="$i 年";
		for($j=1;$j<=20;$j++){
			$select4[$i][$j]="$j 班";
		}
	}

	$sql = "select access_level, access_title from user_access WHERE access_level='1'";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$level[$row['access_level']]=$row['access_title'];
	}

	// And add the selection options
	$max_year = date("Y")-6;
	$min_year = $max_year-6;
	$options = array(
              'language'  => 'tw',
              'format'    => 'Y-m-d',
              'minYear'   => $min_year,
              'maxYear'   => $max_year
               );
	$account[$update_user_id]=$update_user_id;
	$form->addElement('header','header1',"學生資料（".$update_user_id."）");
	//$form->addElement('text','user_id','帳號(身份證字號)：');
	$form->addElement('select','reg_user_id','帳號：', $account);
	$form->addElement('text','uname','姓名：');
	if($change_pass==1){
		$form->addElement('password','pass1','密碼：');
		$form->addElement('password','pass2','密碼確認：');
	}
	$form->addElement("radio","sex","性別：","男","男"); 
	$form->addElement("radio","sex",null,"女","女");
	$sel =& $form->addElement('hierselect', 'organization', '就讀學校：');  //關聯式選單
	$sel->setOptions(array($select1, $select2));
	$sel =& $form->addElement('hierselect', 'class', '班級：');  //關聯式選單
	$sel->setOptions(array($select3, $select4));
	$form->addElement('select','access_level','身份：',$level);
	$form->addElement('date', 'birthday', '生日：', $options);
	//$form->addElement('text','identity','身份證字號：');
	//$form->addElement('text','tel','住所電話：');
	//$form->addElement('text','mobil','手機號碼：');
	//$form->addElement('text','address','居住地址：');
	//$form->addElement('text','email','電子郵件信箱：');
	//$form->addElement('text','class_group','班系：');
	
	//-- 家長資料
	$home_city[0]="縣市";
	$sql = "select * from city";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$home_city[$row['city_code']]=$row['city_name'];		
	}
	
	$care_relation[0]="關係";
	$sql = "select * from user_family_relation";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$care_relation[$row['care_age_id']]=$row['family_relation'];		
	}

	$form->addElement('header','header2','家長資料');
	$form->addElement('text','father_name','父親姓名：');
	$form->addElement('text','fmobil','　　　　　　　父親手機號碼：');
	$form->addElement('text','mother_name','母親姓名：');
	$form->addElement('text','mmobile','母親手機號碼：');
	$form->addElement('text','phone1','聯絡電話(1)：');
	$form->addElement('text','phone2','聯絡電話(2)：');
	$form->addElement('text','caretaker','主要照顧者：');
	$form->addElement('select','care_age_id','關係：',$care_relation);
	$form->addElement('text','address','地址：');
	$form->addElement('select','parent_ccd','縣市：',$home_city);
	$form->addElement('text','email','電子郵件信箱：');
	
	//-- 兄弟姊妹
	for($i=1;$i<=4;$i++){
		$form->addElement('header','header'.$i,'兄弟姊妹'.$i.'(無則免填)');
		$form->addElement('text','familyname'.$i,'姓名：');
		$form->addElement("radio","sex".$i,"性別：","男","男"); 
		$form->addElement("radio","sex".$i,null,"女","女");
		$sel =& $form->addElement('hierselect', 'organization'.$i, '就讀學校：');  //關聯式選單
		$sel->setOptions(array($select1, $select2));
		$sel =& $form->addElement('hierselect', 'class'.$i, '班級：');  //關聯式選單
		$sel->setOptions(array($select3, $select4));

	}
	$form->addElement('header','header',"　");
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file','index');
	$form->addElement('submit','addnewuser','填寫完畢，送出');
    $form->addRule(array('pass1','pass2'),'兩個密碼不相同，請重新輸入！','compare', null, 'client', null, null);
    $form->addRule('reg_user_id', '「帳號」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('pass1', '「密碼」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('pass2', '「密碼確認」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('uname', '「姓名」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('sex', '「性別」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('organization', '「就讀學校」不可空白！', 'required',null, 'client', null, null);
	$form->addRule('organization', '「就讀學校」不可空白！', 'nonzero',null, 'client', null, null);
	$form->addRule('class', '「班級」不可空白！', 'nonzero',null, 'client', null, null);
	$form->addRule('class', '「班級」不可空白！', 'required',null, 'client', null, null);
	$form->addRule('phone1', '「聯絡電話(1)」不可空白！', 'required', null, 'client', null, null);
	$form->setRequiredNote('前有<font color=red>*</font>的欄位不可空白');	

}



function addUSER_auth($my_level){
	global $dbh, $form, $module_name;
	
	OpenTable2();
	//-- 尋找目前已建立之學校、單位，並初始化"關聯選單"
	$select1[0]='==縣市==';
	$select2[0][0]='==加盟店名稱==';
	
	$sql = "select * from city, firm WHERE city.city_code=firm.city_code ORDER BY firm_id";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$select1[$row['city_code']]=id2city($row['city_code']);
		$select2[$row['city_code']][$row['firm_id']]=$row['name'];
		
	}

	$select3[0]='年級';
	$select4[0][0]='班級';
	for($i=3;$i<=6;$i++){
		$select3[$i]="$i 年";
		for($j=1;$j<=20;$j++){
			$select4[$i][$j]="$j 班";
		}
	}

	$sql = "select access_level, access_title from user_access WHERE access_level='1' ORDER BY access_level";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$level[$row['access_level']]=$row['access_title'];
	}

	//-- 顯示選單
	//echo "☆★☆ 新增使用者 ☆★☆<br>";

	// And add the selection options
	$startYear = date("Y");
	$options = array(
              'language'  => 'tw',
              'format'    => 'Y-m-d',
              'minYear'   => $startYear-1,
              'maxYear'   => $startYear+2
               );
	$defaultValue['start_date']['Y']=$startYear;
	$defaultValue['start_date']['m']=date("m");
	$defaultValue['start_date']['d']=date("d");
	$defaultValue['stop_date']['Y']=$startYear+1;
	$defaultValue['stop_date']['m']=date("m");
	$defaultValue['stop_date']['d']=date("d");
	$form->addElement('header','header1','帳號資料');
	$form->addElement('text','reg_user_id','帳號(身份證字號)：');
	$form->addElement('password','pass1','密碼：');
	$form->addElement('password','pass2','密碼確認：');
	//$form->addElement('text','uname','姓名：');
	//$form->addElement("radio","sex","性別：","男","男"); 
	//$form->addElement("radio","sex",null,"女","女");
	$sel =& $form->addElement('hierselect', 'firm', '隸屬之加盟店：');  //關聯式選單
	$sel->setOptions(array($select1, $select2));
	//$sel =& $form->addElement('hierselect', 'class', '班級：');  //關聯式選單
	//$sel->setOptions(array($select3, $select4));
	$form->addElement('select','access_level','身份：',$level);
	$form->addElement('date', 'start_date', '開始授權日：', $options);
	$form->addElement('date', 'stop_date', '終止授權日：', $options);
	
	$form->addElement('header','header',"　");
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file','index');
	$form->addElement('submit','addnewuser','新增帳號');
    $form->addRule(array('pass1','pass2'),'兩個密碼不相同，請重新輸入！','compare', null, 'client', null, null);
    $form->addRule('reg_user_id', '「帳號」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('pass1', '「密碼」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('firm', '「隸屬之加盟店」不可空白！', 'nonzero',null, 'client', null, null);
	//$form->addRule('class', '「班級」不可空白！', 'nonzero',null, 'client', null, null);
	//$form->addRule('phone1', '「聯絡電話(1)」不可空白！', 'required', null, 'client', null, null);
	$form->setRequiredNote('前有<font color=red>*</font>的欄位不可空白');
	$form->setDefaults($defaultValue);
	$form->display();

	CloseTable2();
}

function addUSER($my_level){
	global $dbh, $form, $module_name;

	//-- 尋找目前已建立之學校、單位，並初始化"關聯選單"
	$select1[0]='縣市';
	$select2[0][0]='學校名稱';
	
	$sql = "select * from city, organization WHERE city.city_code=organization.city_code ORDER BY organization_id";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$select1[$row['city_code']]=id2city($row['city_code']);
		$select2[$row['city_code']][$row['organization_id']]=$row['name'];
		
	}

	$select3[0]='年級';
	$select4[0][0]='班級';
	for($i=3;$i<=6;$i++){
		$select3[$i]="$i 年";
		for($j=1;$j<=20;$j++){
			$select4[$i][$j]="$j 班";
		}
	}

	$sql = "select access_level, access_title from user_access WHERE access_level>'0' AND access_level<='".$my_level."' ORDER BY access_level";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$level[$row['access_level']]=$row['access_title'];
	}

	//-- 顯示選單
	echo " 新增使用者 <br>";

	// And add the selection options
	$options = array(
              'language'  => 'tw',
              'format'    => 'Y-m-d',
              'minYear'   => 1950,
              'maxYear'   => $max_year
               );

	$form->addElement('header','newheader','新增使用者帳號');
	$form->addElement('text','reg_user_id','帳號：');
	$form->addElement('password','pass1','密碼：');
	$form->addElement('password','pass2','密碼確認：');
	$sel =& $form->addElement('hierselect', 'organization', '就讀學校：');  //關聯式選單
	$sel->setOptions(array($select1, $select2));
	$sel =& $form->addElement('hierselect', 'class', '班級：');  //關聯式選單
	$sel->setOptions(array($select3, $select4));
	$form->addElement('text','uname','姓名：');
	$form->addElement("radio","sex","性別：","男","男"); 
	$form->addElement("radio","sex",null,"女","女");
	$form->addElement('select','access_level','身份：',$level);
	$form->addElement('date', 'birthday', '生日：', $options);
	$form->addElement('text','identity','身份證字號：');
	$form->addElement('text','tel','住所電話：');
	$form->addElement('text','mobil','手機號碼：');
	$form->addElement('text','address','居住地址：');
	$form->addElement('text','email','電子郵件信箱：');
	$form->addElement('text','class_group','班系：');
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file','index');
	$form->addElement('submit','addnewuser','新增帳號');
    $form->addRule(array('pass1','pass2'),'兩個密碼不相同，請重新輸入！','compare', null, 'client', null, null);
    $form->addRule('reg_user_id', '「帳號」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('pass1', '「密碼」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('uname', '「姓名」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('sex', '「性別」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('organization', '「服務單位」不可空白！', 'nonzero',null, 'client', null, null);
	$form->setRequiredNote('前有<font color=red>*</font>的欄位不可空白');
	
	$form->display();

}

function add_header($title){
	echo '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="line01">
              <tr>
                <td width="15%" scope="col"><table width="95" height="28" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td align="center" valign="bottom" background="'._THEME_IMG.'before.gif" scope="col" onmouseover="this.style.background=\'url('._THEME_IMG.'after.gif)\';" onmouseout="this.style.background=\'url('._THEME_IMG.'before.gif)\';"><img src="'._THEME_IMG.'add.gif" width="10" height="11" /><a href="modules.php?op=modload&name=UserManage&file=index">'.$title[0].'</a></td>
                  </tr>
                </table></td>
                <td width="15%" scope="col"><table width="95" height="28" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td align="center" valign="bottom" background="'._THEME_IMG.'before.gif" scope="col" onmouseover="this.style.background=\'url('._THEME_IMG.'after.gif)\';" onmouseout="this.style.background=\'url('._THEME_IMG.'before.gif)\';"><img src="'._THEME_IMG.'b.gif" width="11" height="15" /><a href="modules.php?op=modload&name=UserManage&file=InquiryUSER">'.$title[1].'</a></td>
                  </tr>
                </table></td>
                <td width="70%" scope="col">&nbsp;</td>
              </tr>
            </table></td>
          </tr><tr>
            <td>';
}

?>

