<?php
//�w�qwindows���APEAR�Ҧb
mb_internal_encoding('utf-8');
//@ini_set('include_path' , '.:/opt/lampp/htdocs/CH_IRT_t/classes/PEAR:/opt/lampp/htdocs/CH_IRT_t/classes');
//@ini_set('include_path' , '.:'.$_SERVER["DOCUMENT_ROOT"].'/stuteach_test/classes/PEAR183:'.$_SERVER["DOCUMENT_ROOT"].'/stuteach/classes');
//@ini_set('include_path' , '.;'.$_SERVER["DOCUMENT_ROOT"].'/stuteach/classes/PEAR183;'.$_SERVER["DOCUMENT_ROOT"].'/stuteach/classes');
@ini_set('include_path' , '.;C:\xampp\htdocs\stuteach\classes\PEAR183;C:\xampp\htdocs\stuteach\classes');
@ini_set('allow_call_time_pass_reference','On');
@ini_set('error_reporting','E_ALL & ~E_NOTICE');
//@ini_set('error_reporting','E_ALL');

//�w�q�t�Υؿ�
define('_ADP_PATH' ,dirname($_SERVER['SCRIPT_FILENAME'])."/");
//define('_ADP_PATH' ,"D:\TWAMPd\htdocs\stuteach");
define('_ADP_URL' , 'http://'.$_SERVER["SERVER_ADDR"].'/stuteach/');
define('_WEB_TITLE' , 'irtch');
define('_SPLIT_SYMBOL' , '@XX@');

//�D�w�u�����m
define('_REAL_CSDB_PATH' , _ADP_URL."data/CS_db/");

//�W���ɮץؿ�
define('_ADP_UPLOAD_PATH' , _ADP_PATH."data/");

//�w�]�W�ǵ��c�����x�}�ɤθ��D���ؿ�
define('_ADP_CS_UPLOAD_PATH' , _ADP_UPLOAD_PATH."CS_db/");

//�w�]�W���ɮ׼Ȧs�ؿ�
define('_ADP_TMP_UPLOAD_PATH' , _ADP_UPLOAD_PATH."tmp/");

//�w�]�D�w���}
define('_ADP_EXAM_DB_PATH' , _ADP_URL."data/CS_db/");

//�Ҳչw�] templates_dir
define("_TEMPLATE_DIR", dirname($_SERVER['SCRIPT_FILENAME'])."/templates/");

//�����D�D theme
define("_THEME", "themes/stuteach/");
define("_THEME_CSS", _ADP_URL._THEME."css/front.css");
define("_THEME_IMG", _ADP_URL._THEME."img/");

//�t�Ϊ���
define("_SYS_VER", "ladder");

//���Ʈw�]�w
$dbtype='mysql';
$hostspec='localhost';
$dbuser=$db_user='root';
$dbpass=$db_user_passwd='42064206';
$database=$db_dbn='irtstuteach';  //���Ʈw�W��
//grant all privileges on irtstuteach.* to irtadmin@localhost identified by '35regk22';

$dbhost = 'localhost';
//�����{�Ҫ����ƪ�
$auth_table='user_info';
//�������e���ƪ�
//$news_table='news';
//�n�J�᤹�\�����m�ɶ�(��)
define('_IDLETIME' , '36000');
$idletime = 36000;
//�n�J��cookie���s���ɶ�(��)
$expire = 36000;

//$link = mysql_connect($hostspec, $db_user, $db_user_passwd);
//mysql_select_db($db_dbn);
include_once ("DB.php");
mysql_connect("localhost","$dbuser","$dbpass") or die("fail");
mysql_select_db("$database");

$DSN=$dbtype."://".$db_user.":".$db_user_passwd."@".$hostspec."/".$db_dbn;
$options = array(
    'debug'       => 2,
//    'portability' => DB_PORTABILITY_ALL,
);
$dbh =& DB::connect($DSN, $options);
$dbh->query("SET NAMES utf8");
$dbh->setFetchMode(DB_FETCHMODE_ASSOC);  //�H���Ʈw�������@���^�ǰ}�C��key
//�s�Wlocalhost��mysql��adp���Ʈw

?>
