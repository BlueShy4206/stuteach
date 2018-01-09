
<?php
require_once 'HTML/QuickForm.php';
if ($auth->checkAuth()) {
    $form1 = new HTML_QuickForm('frmchgpass','post','');
	$form1->addElement("header","chgheader","修改密碼");
	$form1->addElement("text","user","帳號：");
	$form1->addElement("password","newpasswd","新密碼：");
	$form1->addElement("password","renewpasswd","新密碼確認：");
	$form1->addElement('submit','chgSubmit','修改');
	$form1->addRule(array('newpasswd', 'renewpasswd'), '新密碼比對錯誤!', 'compare', null, 'client');
	if ($_POST['chgSubmit']) {
		if ($form1->validate()) {
			$result = $auth->changePassword($_POST['user'],$_POST['newpasswd']);
			$form1->freeze();
			if ($result) echo '已修改密碼！！';
			}
	}
	$form1->display();
}
?>

