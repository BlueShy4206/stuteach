<?php
require_once "HTML/QuickForm.php";
require_once 'HTTP/Upload.php';
require_once 'Date.php';
require_once "include/adp_API.php";
$module_name = basename(dirname(__FILE__));

if(!$auth->checkAuth()||$user_data->access_level<=80){
	FEETER();
	die();
}

//-- 顯示主畫面
/*
echo '<br>
	<table width="95%" border="1" cellpadding="0" cellspacing="0">
		<tr>
		<td align="center" bordercolor="#FFCC33">';
*/
echo '<table width="80%" border="0" cellspacing="4" cellpadding="0">
          <tr>
            <td scope="col">';

$form = new HTML_QuickForm('frmadduser','post',$_SERVER['PHP_SELF']);

if ($_POST['addnewuser'] && $form->validate()) {
	$reg_user_id=$_POST['user_id'];
	$pass=$_POST['pass1'];
	$mydate = new Date();
	$user_regdate=$mydate->format('%Y-%m-%d');
	$birthday=sprintf("%04d",$_POST['birthday']['Y']).'-'.sprintf("%02d",$_POST['birthday']['m']).'-'.sprintf("%02d",$_POST['birthday']['d']);
	//print_r($_POST);
	$auth_start[0]=$_POST[start_date][Y].'-'.sprintf("%02d", $_POST[start_date][m]).'-'.sprintf("%02d",$_POST[start_date][d]);
	$auth_start[1]=$auth_start[0]." 00:00:00";
	$mydate = new Date($auth_start[1]);
	$auth_start[2]=$mydate->getDate(DATE_FORMAT_UNIXTIME) ;

	$auth_stop[0]=$_POST[stop_date][Y].'-'.sprintf("%02d", $_POST[stop_date][m]).'-'.sprintf("%02d",$_POST[stop_date][d]);
	$auth_stop[1]=$auth_stop[0]." 23:59:59";
	$mydate = new Date($auth_stop[1]);
	$auth_stop[2]=$mydate->getDate(DATE_FORMAT_UNIXTIME) ;
	//print_r($mydate);
	//print_r($auth_start);
	//print_r($auth_stop);
	
	$sql="select count(*) from firm where city_code='{$_POST['city']}'";
	$firm_num =& $dbh->getOne($sql);
	$reg_firm_id=sprintf("%02d",$_POST['city']).sprintf("%04d",$firm_num+1);

	//-- 寫入資料庫
	if($_POST['file']=='addSTORE'){
		$query = 'INSERT INTO firm (firm_id, type, city_code, name, address, telno, auth_nums, auth_ip, auth_starttime, auth_stoptime, start_date, stop_date) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)';
		$data = array($reg_firm_id, 'a', $_POST['city'], $_POST['store_name'], $_POST['addr'], $_POST['tel'], $_POST['auth_num'], $_POST['auth_ip'], $auth_start[2], $auth_stop[2], $auth_start[0], $auth_stop[0]);
		$result =$dbh->query($query, $data);
		if($result==1){
			$msg='<br>新增加盟店　'.$reg_firm_id.'【'.$_POST['store_name'].'】完成';
		}
	}
	$pass_compiler=pass2compiler($pass);
	$query = 'INSERT INTO user_info (user_id, uname, email, sex, user_regdate, birthday, organization_id, pass, viewpass, city_code, grade, class, identity, tel, mobil, address, class_group, firm_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
	$data = array($reg_user_id, $_POST['uname'], $_POST['email'], $_POST['sex'], $user_regdate, $birthday, $reg_firm_id, md5($pass), $pass_compiler, $_POST['city'], '0', '0', $_POST['identity'], $_POST['phone1'], $_POST['phone2'], $_POST['address'], $_POST['class_group'], $reg_firm_id);
	$result =$dbh->query($query, $data);
	if (PEAR::isError($result)) {
		echo "錯誤訊息：".$result->getMessage()."<br>";
		echo "錯誤碼：".$result->getCode()."<br>";
		//echo "使用者訊息：".$result->getUserInfo()."<br>";
		echo "除錯訊息：".$result->getDebugInfo()."<br>";
	}else{
		$query = 'INSERT INTO user_status (user_id, access_level, auth_start_time, auth_stop_time, auth_start_date, auth_stop_date) VALUES (?,?,?,?,?,?)';
		$data = array($reg_user_id, $_POST['access_level'], $auth_start[2], $auth_stop[2], $auth_start[0], $auth_stop[0]);
		$result =$dbh->query($query, $data);
		if (PEAR::isError($result)) {
			echo "錯誤訊息：".$result->getMessage()."<br>";
			echo "錯誤碼：".$result->getCode()."<br>";
			echo "除錯訊息：".$result->getDebugInfo()."<br>";
		}else{
			$query2 = 'INSERT INTO user_parents (user_id, phone1) VALUES (?,?)';
			$data2 = array($reg_user_id, $_POST['phone1']);
			$result2 =$dbh->query($query2, $data2);
			if($result=='1' && $result2=='1'){
				$msg.='<br>新增店長帳號　'.$reg_user_id.'【'.$_POST['uname'].'】完成';
			}
		}
	}


}

if(_SYS_VER=="ladder"){
	addSTORE($msg, $user_data->access_level);
}
echo "</td></tr></table>";

function addSTORE($msg,$my_level){
	global $dbh, $form, $module_name;

	if($msg!=""){
		echo "<p> $msg </p>";
	}
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

	$home_city[0]="縣市";
	$sql = "select * from city";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$home_city[$row['city_code']]=$row['city_name'];		
	}
	// And add the selection options
	$min_year = date("Y");
	$options = array(
              'language'  => 'tw',
              'format'    => 'Y-m-d',
              'minYear'   => $min_year,
              'maxYear'   => $min_year+2
               );
	$defaultValue['start_date']['Y']=$min_year;
	$defaultValue['start_date']['m']=date("m");
	$defaultValue['start_date']['d']=date("d");
	$defaultValue['stop_date']['Y']=$min_year+1;
	$defaultValue['stop_date']['m']=date("m");
	$defaultValue['stop_date']['d']=date("d");
	//--加盟店資料
	$form->addElement('header','header1','加盟店資料');
	$form->addElement('text','store_name','加盟店名：');
	$form->addElement('select','city','所在縣市：',$home_city);
	$form->addElement('text','addr','店址：');
	$form->addElement('text','tel','電話：');
	$form->addElement('text','auth_num','授權人數：');
	$form->addElement('text','auth_ip','授權IP：');
	$form->addElement('date', 'start_date', '開始授權日：', $options);
	$form->addElement('date', 'stop_date', '終止授權日：', $options);
	
	//--店長資料
	$form->addElement('header','header2','店長資料');
	$form->addElement('text','user_id','店長帳號(身份證字號)：');
	$form->addElement('password','pass1','密碼：');
	$form->addElement('password','pass2','密碼確認：');
	$form->addElement('text','uname','姓名：');
	$form->addElement("radio","sex","性別：","男","男"); 
	$form->addElement("radio","sex",null,"女","女");
	$form->addElement('text','phone1','聯絡電話(1)：');
	$form->addElement('text','phone2','聯絡電話(2)：');
	$form->addElement('text','email','電子郵件信箱：');

	$form->addElement('header','header',"　");
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file','addSTORE');
	$form->addElement('hidden','access_level','31');
	$form->addElement('submit','addnewuser','新增帳號');
    $form->addRule('store_name', '「加盟店名」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('city', '「所在縣市」不可空白！', 'nonzero', null, 'client', null, null);
	$form->addRule('tel', '「加盟店電話」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('auth_num', '「授權人數」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('user_id', '「店長帳號」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('pass1', '「密碼」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('text', '「電話」不可空白！', 'required', null, 'client', null, null);
	$form->addRule(array('pass1','pass2'),'兩個密碼不相同，請重新輸入！','compare', null, 'client', null, null);
	$form->addRule('uname', '「姓名」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('sex', '「性別」不可空白！', 'required', null, 'client', null, null);
	$form->addRule('auth_num', '「授權人數」必須是數字！', 'numeric', null, 'client', null, null);
	
	$form->addRule('phone1', '「店長聯絡電話(1)」不可空白！', 'required', null, 'client', null, null);
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

?>

