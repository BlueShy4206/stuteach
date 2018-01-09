<?php
require_once "HTML/QuickForm.php";
require_once "HTTP/Upload.php";
require_once "include/adp_API.php";
require_once "CourseData.php";
$module_name = basename(dirname(__FILE__));


exam_clean_all (); //清除所有測驗相關session

if($user_data->access_level>=8 and $user_data->access_level<=9){ //8:展示帳號，9:內部檢測用帳號
	$sql="DELETE FROM exam_record_irt WHERE user_id='".$user_data->user_id."'";
  $result = $dbh->query($sql);
  $sql="DELETE FROM pretest_record_irt WHERE user_id='".$user_data->user_id."'";
	$result = $dbh->query($sql);
}

TableTitle('90%','注意事項');
OpenTable2('90%');
echo "本測驗以嚴謹的測驗理論進行編製，所以進行本測驗請勿隨意亂猜，認真作答才能達到最佳的診斷效果！";
CloseTable2();

//if($user_data->access_level==8)
//echo $user_data->access_level;
    
TableTitle('90%','使用說明');
echo '<table width="90%" border="0" cellspacing="2" cellpadding="2">
                <tr>
                  <td scope="col"></td>
                </tr>
                <tr>
                  <td>1.選擇所要測試的測驗後，請按
                    <input name="Submit" type="submit" value="參加測驗" /></td>
                </tr>
                <tr>
                  <td>2.本測驗有作答的時間限制，請注意右上角的時間 。 </td>
                </tr>
                <tr>
                  <td>3.每題皆為單選題，點選答案後請按
                    <input name="Submit2" type="submit" class="butn01" value="選擇完畢，請進入下一題" /></td>
                </tr>
              </table>';
echo '<table width="90%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td scope="col"><img src="'._THEME_IMG.'main_lt.gif" width="12" height="12" /></td>
                <td width="98%" background="'._THEME_IMG.'main_ct.gif" scope="col"></td>
                <td scope="col"><img src="'._THEME_IMG.'main_rt.gif" width="12" height="12" /></td>
              </tr>
              <tr>
                <td background="'._THEME_IMG.'main_lc.gif"></td>
                        <td align="center" valign="top">';


if(_SYS_VER=='ladder'){
	
	if($user_data->access_level=='2' || $user_data->access_level=='3'){
		$sql="select count(*) from exam_record_irt where user_id='{$user_data->user_id}'";
		$_SESSION['test_times'] =& $dbh->getOne($sql);
	}

/*	if($_SESSION['test_times']>0 && $user_data->access_level=='2'){  //一次性帳號已經考過一張試卷
		echo "<br>您的身份為【".$user_data->user_level."】，<br><br>目前您無權限參加測驗！<br><br>";
	}elseif($_SESSION['test_times']>2 && $user_data->access_level=='3'){
		echo "<br>您的身份為【".$user_data->user_level."】，<br><br>目前您無權限參加測驗！<br><br>";;
	}else{
		viewEXAM_ladder($_REQUEST['opt']);
	}
*/
	viewEXAM_ladder($_REQUEST['opt']);
}else{
	viewEXAM($_REQUEST['opt']);
}



echo '</td>
                <td background="'._THEME_IMG.'main_rc.gif"></td>
              </tr>
              <tr>
                <td><img src="'._THEME_IMG.'main_ld.gif" width="12" height="12" /></td>
                <td background="'._THEME_IMG.'main_cd.gif"></td>
                <td><img src="'._THEME_IMG.'main_rd.gif" width="12" height="12" /></td>
              </tr>
            </table>';


function viewExam_ladder($opt){
	global $dbh, $auth, $user_data, $module_name;

	$myC=$user_data->getCourse();
	//debug_msg("第".__LINE__."行 user_data ", $user_data);
	//debug_msg("第".__LINE__."行 myC ", $myC);

	//-- 初始化"關聯選單"
	$select0[0]='場次';
	$select1[0][0]='測驗科目';
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

	//debug_msg("第".__LINE__."行 myC ", $myC);
	if(count($myC)>0){
		foreach($myC as $key => $MyCourseID){
			$sql = "select * from exam_course_access_irt where course_id ='".$MyCourseID."' ORDER BY course_id, exam_paper_id, type_id_irt, type_id_e, type_id_s, type_id_t, num_e, item_length, test_time";   //找出已開放之試卷
			$result = $dbh->query($sql);
			while ($data = $result->fetchRow()) {
				$my_type_id=$data['type_id_irt']._SPLIT_SYMBOL.$data['type_id_e']._SPLIT_SYMBOL.$data['type_id_s']._SPLIT_SYMBOL.$data['type_id_t']._SPLIT_SYMBOL.$data['num_e']._SPLIT_SYMBOL.$data['item_length']._SPLIT_SYMBOL.$data['test_time'];
				$tk=$data['course_id']._SPLIT_SYMBOL.$data['exam_paper_id'].sprintf("%02d",$data['type_id_irt']);
				$open_EP[]=$tk;
				$open_EP1[$tk]=$data['course_id']._SPLIT_SYMBOL.$data['exam_paper_id'].$my_type_id;   //已開放之試卷
			}
		}
	}
	$_SESSION['open_EP_val']=$open_EP1;
//debug_msg("第".__LINE__."行 used_EP ", $used_EP);
//debug_msg("第".__LINE__."行 open_EP ", $open_EP);
	if(count($open_EP)==0){
		echo "<br>目前沒有可以施測的試卷！<br><br>";
	}else{
		$form = new HTML_QuickForm('frmTest','post','');
		if(count($used_EP)==0){
			$differ=$open_EP;
		}else{
			$differ = array_diff($open_EP, $used_EP);   //元素少者在前，找兩陣列相異之值
		}
		sort($differ);   // $differ為目前已開放且尚未施測之試卷

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
			//$select1[$cid][$EPid]=id2csname($cs_id).'-卷'.$paper_vol;
			//$select2[$cid][$pid][$sid]=$subject;
			//$select3[$cid][$pid][$sid][$vid]='第'.$vid.'冊';
			//$select4[$cid][$pid][$sid][$vid][$uid]='第'.$uid.'單元-'.id2csname($cs_id);
			//$select5[$cid][$pid][$sid][$vid][$uid][$EPid]='卷'.$paper_vol;

			$form->addElement('hidden',$EPid,$exam_type);  //不同的試卷，可能有不同的出題演算法
		}

		//-- 顯示選單
		//echo "☆★☆ 編修試卷 ☆★☆<br>";
		$form->addElement('header', 'myheader', '<center>測 驗 選 擇</center>');  //標頭文字
		// Create the Element
		$sel =& $form->addElement('hierselect', 'ep_item', '');
		// And add the selection options
		$sel->setOptions(array($select0, $select1,));
		$form->addElement('hidden','op','modload');
		$form->addElement('hidden','name',$module_name);
		$form->addElement('hidden','file',"AdaptiveTestInit");
		$form->addElement('hidden','system_version',_SYS_VER);
		$form->addElement('hidden','screen',"all");
		$form->addElement('submit','btnSubmit',' 參 加 測 驗 ');
		$form->display();
	}
}
// end

/*
function viewExam($opt){  
	global $dbh, $auth, $user_data, $module_name;

	$form = new HTML_QuickForm('frmTest','post','');
	//-- 初始化"關聯選單"
	$select1[0]='版本';
	$select2[0][0]='領域';
	$select3[0][0][0]='冊別';
	$select4[0][0][0][0]='單元名稱';
	$select5[0][0][0][0][0]='卷別';

	$sql="select exam_title, type_id from exam_record_irt where user_id='".$user_data->user_id."'";  //已經考過的試題
	$result = $dbh->query($sql);
	$i=0;
	$used_EP[0]="000000000";
	while ($data = $result->fetchRow()) {
		$used_EP[$i]=$data['exam_title'].sprintf("%02d",$data['type_id']);    //已考過的試卷
		$i++;
	}
	//echo "<pre>";
	//echo "used_EP";
	//print_r($used_EP);
	
	$sql = "select exam_paper_id, type_id_irt  from exam_paper_access_irt  where firm_id='".$user_data->firm_id."' AND grade='".$user_data->grade."' AND class='".$user_data->class_name."'";   //找出已開放之試卷
	$result = $dbh->query($sql);
	while ($data = $result->fetchRow()) {
		$open_EP[]=$data['exam_paper_id'].sprintf("%02d",$data['type_id_irt']);   //已開放之試卷
	}
	//echo "open_EP";
	//print_r($open_EP);
	$differ = array_diff($open_EP, $used_EP);   //元素少者在前，找兩陣列相異之值
	sort($differ);   // $differ為目前已開放且尚未施測之試卷
	//echo "<pre>";
	//echo "differ_EP";
	//print_r($differ);
	for($j=0;$j<sizeof($differ);$j++){
		$EPid=substr($differ[$j],0,11);
		$exam_type=intval(substr($differ[$j],11,2));

		$cs_id=EPid2CSid($EPid);
		$EP_info=explode_ep_id($EPid);
		$paper_vol=intval($EP_info[4]);
		
		$pid=intval($EP_info[0]);
		$sid=intval($EP_info[1]);
		$vid=intval($EP_info[2]);
		$uid=intval($EP_info[3]);
		$subject=id2subject($sid);
		$paper_vol=intval($EP_info[4]);
		$select1[$pid]=id2publisher($pid);
		$select2[$pid][$sid]=$subject;
		$select3[$pid][$sid][$vid]=vol2grade($subject,$vid).'〈第'.$vid.'冊〉';
		$select4[$pid][$sid][$vid][$uid]='第'.$uid.'單元-'.id2csname($cs_id);
		$select5[$pid][$sid][$vid][$uid][$paper_vol]='卷'.$paper_vol;

		$form->addElement('hidden',$EPid,$exam_type);  //不同的試卷，可能有不同的出題演算法
	}

	//-- 顯示選單
	//echo "☆★☆ 編修試卷 ☆★☆<br>";
	$form->addElement('header', 'myheader', '<center>☆★☆ 請選擇試卷 ☆★☆</center>');  //標頭文字
	// Create the Element
	$sel =& $form->addElement('hierselect', 'ep_item', '');
	// And add the selection options
	$sel->setOptions(array($select1, $select2, $select3, $select4, $select5));
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file',"AdaptiveTestInit");
	$form->addElement('hidden','system_version',_SYS_VER);
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->display();

}

function viewExam_org($opt){
	global $dbh, $auth, $user_data, $module_name;
	
	echo '<br>測驗內容：<br>
<table width="98%" border="1" align="center" bordercolor="#FFFFFF">
  <tr>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">編號</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">版本</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">領域</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">冊</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">單元</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">名稱</div></td>
	<td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">卷別</div></td>
    <td bordercolor="#4D6185" bgcolor="#D9E8FD"><div align="center">&nbsp;</div></td>
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
	
	$sql="select exam_title, type_id from exam_record_irt where user_id='".$user_data->user_id."'";  //已經考過的試題
	$result = $dbh->query($sql);
	$i=0;
	$used_EP[0]="000000000";
	while ($data = $result->fetchRow()) {
		$used_EP[$i]=$data['exam_title'].sprintf("%02d",$data['type_id']);    //已考過的試卷
		$i++;
	}
	//echo "<pre>";
	//print_r($used_EP);
	$sql = "select exam_paper_id, type_id_irt from exam_paper_access_irt where school_id='".$user_data->organization_id."' AND grade='".$user_data->grade."' AND class='".$user_data->class_name."'";   //找出已開放之試卷
	$result = $dbh->query($sql);
	while ($data = $result->fetchRow()) {
		$open_EP[]=$data['exam_paper_id'].sprintf("%02d",$data['type_id_irt']);   //已開放之試卷
	}
	//print_r($open_EP);
	$differ = array_diff($open_EP, $used_EP);   //元素少者在前，找兩陣列相異之值
	
	sort($differ);   // $differ為目前已開放且尚未施測之試卷
	
	//print_r($differ);
  
	for($j=0;$j<sizeof($differ);$j++){
		//echo $differ[$j];
    $EPid=substr($differ[$j],0,11);
		$exam_type=intval(substr($differ[$j],11,2));
		$cs_id=EPid2CSid($EPid);
		$EP_info=explode_ep_id($EPid);
		$paper_vol=intval($EP_info[4]);  

		$sql = "select * from exam_paper, concept_info where exam_paper.cs_id=concept_info.cs_id and concept_info.cs_id='".$cs_id."' and exam_paper.paper_vol='".$paper_vol."'";   
		$result = $dbh->query($sql);
		while ($data = $result->fetchRow()) {
		
			$myary=array(($j+1),  $tran1[$data['publisher_id']], $tran2[$data['subject_id']], $data['vol'], $data['unit'], $data['concept'], $data['paper_vol']);
			echo "<tr>";
			for($i=0;$i<count($myary);$i++){
				echo "<td bordercolor=\"#4D6185\" bgcolor=\"#FFFFCC\"><div align=\"center\">".$myary[$i]."</div></td>";
			}
			if($exam_type==1){
				$file='IRT';
			}elseif($exam_type==2){
				$file='MIRT';
			}
			// 由AdaptiveTestInit.php 控制 選取考試後 對應到 哪一個程式
			//echo "<td bordercolor=\"#4D6185\" bgcolor=\"#FF99FF\"><div align=\"center\"><a href=\"modules.php?op=modload&name=AdaptiveTest&file=".$file."&cs_id=".$data['cs_id']."&paper_vol=".$data['paper_vol']."&exam_type=".$exam_type."\">參加測驗</a></div></td></tr>";
			echo "<td bordercolor=\"#4D6185\" bgcolor=\"#FF99FF\"><div align=\"center\"><a href=\"modules.php?op=modload&name=IRT&file=".$file."&cs_id=".$data['cs_id']."&paper_vol=".$data['paper_vol']."&exam_type=".$exam_type."&publisher_id=".$tran1[$data['publisher_id']]."\">參加測驗</a></div></td></tr>";
			//echo "<td bordercolor=\"#4D6185\" bgcolor=\"#FF99FF\"><div align=\"center\"><a href=\"http://www.yahoo.com.tw\">參加測驗1</a></div></td></tr>";
		}
	}
	echo "</table>";
}
*/


?>
