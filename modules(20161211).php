<?php

require_once "logout.php";
if($_REQUEST[screen]=="all"){
   require_once "head_full_src.php";
}else{
   require_once "head.php";
}
require_once "auth_chk.php";

require_once "HTML/QuickForm.php";
require_once "HTTP/Upload.php";
require_once "include/adp_API.php";
require_once "CourseData.php";
require_once 'HTML/QuickForm/checkbox.php';

echo '</div>';
echo '</TD></TR></TABLE>';


if(!Auth::staticCheckAuth($options)){  //檢查登入狀況
	Header("Location: index.php");
	die();
}

if ($_GET["file"]) {
        if (eregi("http:\/\/", $_GET["file"])||eregi("ftp:\/\/", $_GET["file"])||eregi("[[:alnum:]]+\.[[:alnum:]]+\.", $_GET["file"])||eregi("[[:alnum:]]+\.[[:alnum:]]+/", $_GET["file"])) { echo "Hi, How do u do ?"; exit; }
}


echo '<TABLE ID="TOP_TABLE" NAME="TOP_TABLE" width="960" CELLPADDING="0" CELLSPACING="0" BORDER="0" align="center">
	<TR>
		<TD COLSPAN="3" align="right"> <IMG NAME="TOP_IMG" WIDTH="960" HEIGHT="58" SRC="images/stu_inner_2.jpg">';

if($_REQUEST['file']=='IRT' || $_REQUEST['screen']=="all"){
	echo"";
}else{
   echo '<div align="center">'.$_SESSION['block_content'].'</div>';
}

echo '</TD>
	</TR>
	<TR>
		<TD WIDTH="13" background="images/stu_inner_3.jpg" align="center"></TD>';
echo '<TD WIDTH="935" height="450" valign=top align=center>';
echo '<div id="main">';
$op=$_REQUEST['op'];
if($op=="modload"){
//		if (!isset($mainfile)) { include("mainfile.php"); }
	if (ereg("\.\.",$name) || ereg("\.\.",$file)) {
		echo "You are so cool";
		die();
	} else {
		$exec_file="modules/".$_REQUEST['name']."/".$_REQUEST['file'].".php";
		if(file_exists($exec_file)){
			include($exec_file);
		}else{
			echo "請不要任意輸入網址！！";
		}
	}
}elseif($op=="main"){
	$sems = date("Y") - 1911;
	$log_name = $user_data->user_id;
	echo "<font class=\"title1\"><center><br><br>";
	//print_r($user_data);
echo "<br><font size = \"5\" style=\"font-family:標楷體;\" >本人為&nbsp;&nbsp;</font><font size = \"5\" color=\"blue\" style=\"font-family:標楷體;\"><u>".$user_data->uname."</u></font><font size = \"5\" style=\"font-family:標楷體;\" >&nbsp;&nbsp;&nbsp;准考證號碼：<font size = \"5\" color=\"blue\" style=\"font-family:Times New Roman;\"><u>".$sems.substr($log_name, 0, $log_name.length -2)."</u></font>，我確認上述個人資料正確，";
echo "<p align= \"left\">並同意遵守下述及報名簡章所列示試場規則：<br><br>";
	//echo $user_data->city_name.$user_data->organization_name."".$user_data->cht_class."</b></font><br><br>";
echo "<font size = \"4\" style=\"font-family:標楷體;\" ><p align= \"left\">•	應考人應攜帶自行列印之「准考證」及「國民身分證」正本（或以駕照、具照片之健保卡或護照代替國民身分證）到場應試。未能提供身分證明文件者，即取消應試資格，不得參加本次評量。
	  <p align= \"left\">•	本次評量將由承辦單位提供計算紙及原子筆供試題計算用，應考人試後計算紙不得攜出考場，違者該科不予計分。
	  <p align= \"left\">•	作答時間之計算，以系統登入時間為主。但若以不合規定之手段干擾計時者視同作弊，並得視其情節加重扣分或該科不予計分。
	  <p align= \"left\">•	評量開始後逾15分鐘即不得入場應試，且評量未達25分鐘不得離開考場。
	  <p align= \"left\">•	其他未盡事宜，除依本中心訂頒之試場規則辦理外，由各該考區負責人處理之。
		<br><br>";
		
		//---

				
		
		if($user_data->access_level>=8 and $user_data->access_level<=9){ //8:展示帳號，9:內部檢測用帳號
			$sql="DELETE FROM exam_record_irt WHERE user_id='".$user_data->user_id."'";
			$result = $dbh->query($sql);
			$sql="DELETE FROM pretest_record_irt WHERE user_id='".$user_data->user_id."'";
			$result = $dbh->query($sql);
		}
		

/*
		if($user_data->access_level=='2' || $user_data->access_level=='3'){
			$sql="select count(*) from exam_record_irt where user_id='{$user_data->user_id}'";
			$_SESSION['test_times'] =& $dbh->getOne($sql);
		}
*/

			global $dbh, $auth, $user_data, $module_name;

			$myC=$user_data->getCourse();
			//debug_msg("第".__LINE__."行 user_data ", $user_data);
			//debug_msg("第".__LINE__."行 myC ", $myC);

			//-- 初始化"關聯選單"
			//$select0[0]='場次';
			//$select1[0][0]='測驗科目';
			//$select2[0][0][0]='卷別';
			//$select3[0][0][0][0]='冊別';
			//$select4[0][0][0][0][0]='單元名稱';
			//$select5[0][0][0][0][0][0]='卷別';

			$sql="select exam_title, type_id, course_id from exam_record_irt where user_id='".$user_data->user_id."' order by course_id, exam_title, type_id";  //已經考過的試題
			$result = $dbh->query($sql);
			$i=0;
			//$used_EP[0]="000000000";
			while ($data = $result->fetchRow()) {
				$used_EP[$i]=$data['course_id']._SPLIT_SYMBOL.$data['exam_title'].sprintf("%02d",$data['type_id']);    //已考過的試卷
				$i++;
			}
			$date = date("Y-m-d H:i:s",time());//目前時間 --teresa 20160408 add 
			echo $date;
			//debug_msg("第".__LINE__."行 myC ", $myC);
			if(count($myC)>0){
				foreach($myC as $key => $MyCourseID){
					$sql = "select * from exam_course_access_irt 
							where course_id ='".$MyCourseID."' AND start_time <= '$date' AND end_time >= '$date' 
							ORDER BY course_id, exam_paper_id, type_id_irt, type_id_e, type_id_s, type_id_t, num_e, item_length, test_time";   //找出已開放之試卷 && teresa add time range
              //debug_msg("第".__LINE__."行 sql ", $sql);
					//$sql = "select * from exam_course_access_irt where course_id ='".$MyCourseID."' ORDER BY course_id, exam_paper_id, type_id_irt, type_id_e, type_id_s, type_id_t, num_e, item_length, test_time";   //找出已開放之試卷
					$result = $dbh->query($sql);
					while ($data = $result->fetchRow()) {
						$my_type_id=$data['type_id_irt']._SPLIT_SYMBOL.$data['type_id_e']._SPLIT_SYMBOL.$data['type_id_s']._SPLIT_SYMBOL.$data['type_id_t']._SPLIT_SYMBOL.$data['num_e']._SPLIT_SYMBOL.$data['item_length']._SPLIT_SYMBOL.$data['test_time'];
						$tk=$data['course_id']._SPLIT_SYMBOL.$data['exam_paper_id'].sprintf("%02d",$data['type_id_irt']);
            //debug_msg("第".__LINE__."行 tk ", $tk);
						$open_EP[]=$tk;
						$open_EP1[$tk]=$data['course_id']._SPLIT_SYMBOL.$data['exam_paper_id'].$my_type_id;   //已開放之試卷
					}
				}
			}
			$_SESSION['open_EP_val']=$open_EP1;
		//debug_msg("第".__LINE__."行 used_EP ", $used_EP);
		//debug_msg("第".__LINE__."行 open_EP ", $open_EP);
			if(count($open_EP)==0){  //目前沒有開放的試卷
				echo "<center><br>".__LINE__."目前沒有可以施測的試卷！<br></center>";
				echo '<center><a href="index.php?act=logout">登出</a></center>';
				//die();
			}else{
				$form = new HTML_QuickForm('frmTest','post','');
				if(count($used_EP)==0){
					$differ=$open_EP;
				}else{
					$differ = array_diff($open_EP, $used_EP);   //元素少者在前，找兩陣列相異之值
				}
				sort($differ);   // $differ為目前已開放且尚未施測之試卷
         //debug_msg("第".__LINE__."行 differ ", $differ);
         
				//$_SESSION['auth_ep']=$differ;
				$jj=sizeof($differ);
				for($j=0;$j<$jj;$j++){
					list($nowCourseID,$differEP)=explode(_SPLIT_SYMBOL, $differ[$j]);
					$_SESSION['auth_ep'][$j]=$differEP;
					$cid=$nowCourseID;
					$CO=new CourseData($cid);
					$EPid=substr($differEP,0,11);
					$exam_type=intval(substr($differEP,11,2));

					$cs_id=EPid2CSid($EPid);
					$EP_info=explode_ep_id($EPid);
					$paper_vol=intval($EP_info[4]);

					$pid=intval($EP_info[0]);
					$sid=intval($EP_info[1]);
					$vid=intval($EP_info[2]);
					$uid=intval($EP_info[3]);
					$subject=id2subject($sid);
					$paper_vol=intval($EP_info[4]);
					$select0[$cid]=$CO->FullName;
					$select1[$cid][$EPid]=id2csname($cs_id);

					
					$form->addElement('hidden',$EPid,$exam_type);  //不同的試卷，可能有不同的出題演算法
				}

				$sel =& $form->addElement('hierselect', 'ep_item', '', 'style=\'display:none\'');
				$sel->setOptions(array($select0, $select1,));

				
				//$advCheckbox = new HTML_QuickForm_advcheckbox('chk1', '', '本人瞭解上述試場規則，並願意遵守。', 'onclick="check_YN()" ', 1 );
				//$form->addElement($advCheckbox);
				$form->addElement('hidden','op','modload');
				$form->addElement('hidden','name','IRT');
				$form->addElement('hidden','file',"AdaptiveTestInit");
				$form->addElement('hidden','system_version',_SYS_VER);
				$form->addElement('hidden','screen',"all");
				//echo count($used_EP)."<br>";
				//print_r($used_EP);
				//echo count($open_EP)."<br>";
				//print_r($open_EP);
				if(count($differ) == 0 || count($open_EP)==0){
					echo '<center><br>'.__LINE__.'目前沒有可以施測的試卷！<br></center>';
					echo '<center><a href="index.php?act=logout">登出</a></center>';
				}else{
					$form->addElement('checkbox','chk', '', '本人瞭解上述試場規則，並願意遵守。 ','onclick="check_YN()" value = 1 id = chk');
					$btnsu = $form->createElement('submit','btnSubmit',' 參 加 測 驗 ', 'disabled=disabled id =btnSubmit style="width:150px;height:40px;font-size:20px;font-family:DFKai-sb;"');
					//$textnbsp = $form->createElement('hidden', 'userName', 'hidden');
					$linkhref = $form->createElement('link', 'Link', '', 'index.php?act=logout', '登出');
					$group[] = $btnsu;
					//$group[] = $textnbsp;
					$group[] = $linkhref;
					$form->addGroup($group);
					
					//echo '<br><a href="index.php?act=logout">登出</a>';
				}
				
				
				
				//echo "<br>";
				//print_r($form);
				$form->display();
			}
			
			
			echo "<script type=\"text/javascript\">
		
						
						function check_YN(){ 
						
							var chkYN = document.getElementById(\"chk\");
							if(chkYN.checked ==true) {
								document.getElementById(\"btnSubmit\").disabled = false;; 
							}else{
								document.getElementById(\"btnSubmit\").disabled = true;
							}
							
						}

						</script>
				";
		
		
		
		
		//---

		
		

	echo "<br><br>";
}elseif($op=="logout"){
	$logouttime=date("Y-m-d, H:i:s");
	$sql="UPDATE user_status SET stoptimestamp='{$logouttime}' WHERE user_id ='{$_SESSION['_authsession']['username']}'";
	$result = $dbh->query($sql);

	$auth->logout();
	$dbh->disconnect();   //資料庫離線
	session_destroy();
	//$msg='您已經登出！';
	Header("Location: index.php");
}
else{
    die ("抱歉！您的權限不符");
}
echo '</div>';
		echo '</TD>
		<TD WIDTH="12" background="images/stu_inner_5.jpg" align="center"></TD>
	</TR>
	<TR>
	<TD COLSPAN="3" align="center" ></TD>
</TR>
</TABLE>';





/*
echo '<TABLE width="959" CELLPADDING="0" CELLSPACING="0" BORDER="0" align="center">
	<TR><TD><IMG WIDTH="47" HEIGHT="46" SRC="images/tcc02_960_2.jpg" BORDER="0"></TD>';
	
echo '<TD WIDTH="864" HEIGHT="46" align="center" background="images/tcc_bk1.jpg">';
//if($_REQUEST['file']=='AdaptiveTestBox' || $_REQUEST['screen']=="all"){
if($_REQUEST['file']=='IRT' || $_REQUEST['screen']=="all"){
	echo"";
}else{
   echo $_SESSION['block_content'];
}
echo "</TD>";
echo '<TD><IMG WIDTH="48" HEIGHT="46" SRC="images/tcc02_960_4.jpg" BORDER="0"></TD>
	</TR>
	<TR>
		<TD><IMG WIDTH="47" HEIGHT="4" SRC="images/tcc02_960_5.jpg" BORDER="0"></TD>
		<TD><IMG WIDTH="864" HEIGHT="4" SRC="images/tcc02_960_6.jpg" BORDER="0"></TD>
		<TD><IMG WIDTH="48" HEIGHT="4" SRC="images/tcc02_960_7.jpg" BORDER="0"></TD>
	</TR>
	</TABLE>';

echo '<div id="main">';
echo '<TABLE width="959" CELLPADDING="0" CELLSPACING="0" BORDER="0" align="center">
	<TR>
		<TD WIDTH="47" HEIGHT="484" background="images/tcc02_960_11.jpg" BORDER="0"></TD>';
echo '<TD WIDTH="864" HEIGHT="484" background="images/tcc02_960_9.jpg" BORDER="0" align="center" valign="top">';

$op=$_REQUEST['op'];
if($op=="modload"){
//		if (!isset($mainfile)) { include("mainfile.php"); }
	if (ereg("\.\.",$name) || ereg("\.\.",$file)) {
		echo "You are so cool";
		die();
	} else {
		$exec_file="modules/".$_REQUEST['name']."/".$_REQUEST['file'].".php";
		if(file_exists($exec_file)){
			include($exec_file);
		}else{
			echo "請不要任意輸入網址！！";
		}
	}
}elseif($op=="main"){

	echo "<font class=\"title1\"><center><br><b>歡迎光臨！<br>";
echo "<br>您是　".$user_data->user_id."【".$user_data->uname."】，身份：".$user_data->user_level."<br><br>";
	echo $user_data->city_name.$user_data->organization_name."".$user_data->cht_class."</b></font><br><br>";

	echo "<br><br>";
}else{
    die ("抱歉！您的權限不符");
}
echo '</TD>
		<TD WIDTH="48" HEIGHT="484" background="images/tcc02_960_13.jpg" BORDER="0"></TD>
	</TR></TABLE>';
echo '</div>';
echo '<div id="Layer1" style="position:absolute; left:1px; top:1px; width:1px; height:1px; z-index:1">';
require_once "feet.php"; 

*/

