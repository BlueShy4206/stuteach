<script type="text/javascript" src="./include/nSel2.js"></script>
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
}

if(_SYS_VER=='ladder'){
	
	if($user_data->access_level=='2' || $user_data->access_level=='3'){
		$sql="select count(*) from exam_record_irt where user_id='{$user_data->user_id}'";
		$_SESSION['test_times'] =& $dbh->getOne($sql);
	}
	$tabel_viewEXAM = viewEXAM_ladder($_REQUEST['opt']);
}

//--底下是table
TableTitle('90%','注意事項');
OpenTable2('90%');
echo "本測驗以嚴謹的測驗理論進行編製，所以進行本測驗請勿隨意亂猜，認真作答才能達到最佳的診斷效果！";
CloseTable2();
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
              </table>
      <table width="90%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td scope="col"><img src="'._THEME_IMG.'main_lt.gif" width="12" height="12" /></td>
                <td width="98%" background="'._THEME_IMG.'main_ct.gif" scope="col"></td>
                <td scope="col"><img src="'._THEME_IMG.'main_rt.gif" width="12" height="12" /></td>
              </tr>
              <tr>
                <td background="'._THEME_IMG.'main_lc.gif"></td>
                <td align="center" valign="top">
                
                <form method="post" action="./modules.php" >
                '.$tabel_viewEXAM.'
                </form>
                
                </td>
                <td background="'._THEME_IMG.'main_rc.gif"></td>
              </tr>
              <tr>
                <td><img src="'._THEME_IMG.'main_ld.gif" width="12" height="12" /></td>
                <td background="'._THEME_IMG.'main_cd.gif"></td>
                <td><img src="'._THEME_IMG.'main_rd.gif" width="12" height="12" /></td>
              </tr>
      </table>';
//--上面是table

function viewExam_ladder($opt){
	global $dbh, $auth, $user_data, $module_name;

  //course_id 是課程代碼

	$myC=$user_data->getCourse();
  print_r($myC); die();
	//-- 初始化"關聯選單"
  // --firstSelAry 要改
  $firstSelAry=array( '場次', '測驗科目');
  foreach( $firstSelAry as $key=>$val ){
    $sel[($key+1)][0][0]=urlencode($val);
    $selHtml .= '<select id="LV'.($key+1).'" name="SelTest[]"></select>';
  }
  
  //撈出該生做過的場次，試卷
  $sql_do = '
    SELECT course_id, exam_paper_id 
    FROM exam_record_irt
    WHERE user_id="'.$user_data->user_id.'"
    ORDER BY course_id 
  ';
  $re_do = $dbh->getAll($sql_do);
  foreach( $re_do as $v ){
    $do_course = $v[course_id];
    $do_epid = $v[exam_paper_id];
    $do_sql[] = '( course_id != "'.$do_course.'" AND exam_paper_id != "'.$do_epid.'" )';
  }
  
  //撈出分配給該生班級的場次，試卷。不挑做過的
  $sql_paper = '
    SELECT course_id, cs_id
    FROM exam_course_access_irt
    WHERE user_id=""
  ';
  

  foreach( $re_stu as $key_stu=>$val_stu ){
          //-- $sel 是三維陣列，要符合格式 
          // $sel[1][a] = ...
          // $sel[2][a][b] =...
          // $sel[3][ab][c] = ...
          // $sel[4][abc][d] = ...
  
    //县市
    //$Sel[1][0] = urlencode('县市');
    $sel[1][ $val_stu[city_code] ] = urlencode( id2city( $val_stu[city_code] ) );
    //学校
    //$Sel[2][0][0] = urlencode('学校');
    $sel[2][ $val_stu[city_code] ][ $val_stu[organization_id] ] = urlencode( id2org( $val_stu[organization_id] ) );
    //年级
    //$Sel[3][0][0][0] = urlencode('年级');        
    $sel[3][ $val_stu[city_code].$val_stu[organization_id] ][ $val_stu[grade] ] = urlencode( $val_stu[grade].'年' );
    //班级
    //$Sel[4][0][0][0][0] = urlencode('班级');
    $sel[4][ $val_stu[city_code].$val_stu[organization_id].$val_stu[grade] ][ $val_stu['class'] ] = urlencode( $val_stu['class'].'班' );
    //学生姓名
    //$Sel[5][0][0][0][0][0] = urlencode('学生姓名');
    $sel[5][ $val_stu[city_code].$val_stu[organization_id].$val_stu[grade].$val_stu['class'] ][ $val_stu[user_id] ] = urlencode( $val_stu[uname] );
    //纪录学生作过的CS_ID
    //$Sel[6][0][0][0][0][0][0] = urlencode('CS_ID');
    $sel[6][ $val_stu[city_code].$val_stu[organization_id].$val_stu[grade].$val_stu['class'].$val_stu[user_id] ][ $val_stu[cs_id] ] = urlencode( CSid2FullName($val_stu[cs_id]) );
  
  }

}
?>