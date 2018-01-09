<?php
require_once "include/adp_API.php";
require_once 'Date.php';
require_once "IRTConceptStructure.php";
require_once 'db.php';

if(Auth::staticCheckAuth($options)){  //检查登入状况
   ;}else{
	Header("Location: index.php");
	die();
}

$module_name= basename(dirname(__FILE__));
$file = basename(__FILE__);
list($SubmitFile, $FileType)=explode(".", $file);

//判斷是否繼續施測(以時間計算)
$NowtimeStamp=time();
//考試時間已到，而且確實開始考試
if(isset($_SESSION['time']['StopTimestamp']) and $NowtimeStamp>=$_SESSION['time']['StopTimestamp'] ){
  if($_SESSION['time']["test_time_sec"]>0 and $_REQUEST['TestTimeIsUP']=='1'){   //確實有在考試
    
    //讀出session data
    $CsID=$_REQUEST['cs_id'];
    $PaperVol=intval($_REQUEST['paper_vol']);
    $course_id=$_REQUEST['course_id'];
    $sql="SELECT * FROM session_data WHERE user_id='".$_SESSION['user_data']->user_id."' AND course_id='".$course_id."' AND cs_id='".$CsID."' AND paper_vol='".$PaperVol."' ";
    $result = $dbh->query($sql);
    while ($data = $result->fetchRow()){
      $tmp=json_decode(gzuncompress(base64_decode($data['session_data'])),true);    
      $_SESSION['IRT']=$tmp[0];
      $_SESSION['time']=$tmp[1];
      $_SESSION['code']=$tmp[2];  
      $_POST=json_decode(gzuncompress(base64_decode($data['post_data'])),true);  
    }
    
    $IRT=unserialize($_SESSION['IRT']);
    //計算每題作答時間
    $_SESSION['time']["item_end_time"]=time();
    $item_idle_time=$_SESSION['time']["item_end_time"]-$_SESSION['time']["item_start_time"];
    $_SESSION['time']["items_idle_time"].=$item_idle_time._SPLIT_SYMBOL;
    //記錄client單題作答時間(毫秒)
    $client_item_idle_time=$_POST['time']["client_end_ms"]-$_POST['time']["client_start_ms"];
    $_SESSION['time']["client_items_idle_time"].=$client_item_idle_time._SPLIT_SYMBOL;
  
  	$_SESSION['code']=$_POST['code'];
    $CsID=$IRT->get_csid();  //考卷的ID
  	$PaperVol=$IRT->get_paper_vol();  //卷
    $selected_item=$IRT->select_item_num; //試題編號  
  	$item_sn=$IRT->select_item_sn;
    
    //答題記錄
    if($IRT->step==1){  
      $IRT->pretest_rec_user_answer.=$_POST["user_answer"]._SPLIT_SYMBOL;
      $IRT->pretest_select_item_id.=$IRT->select_item_sn._SPLIT_SYMBOL;
      if($IRT->get_item_op_ans($IRT->select_item_num)==$_POST["user_answer"]){
        $IRT->pretest_response.="1"._SPLIT_SYMBOL;
      }else{
        $IRT->pretest_response.="0"._SPLIT_SYMBOL;
      }
    }else{
      $IRT->rec_user_answer.=$_POST["user_answer"]._SPLIT_SYMBOL;
      $IRT->h_exam_A.=$IRT->h_exam._SPLIT_SYMBOL;
      $IRT->select_item_id_S.=$IRT->select_item_sn._SPLIT_SYMBOL;
      $IRT->select_item_id_A.=$IRT->select_item_sn._SPLIT_SYMBOL;
      $IRT->select_P_a.=$IRT->get_item_a($IRT->select_item_num)._SPLIT_SYMBOL;
      $IRT->select_P_b.=$IRT->get_item_b($IRT->select_item_num)._SPLIT_SYMBOL;
      $IRT->select_P_c.=$IRT->get_item_c($IRT->select_item_num)._SPLIT_SYMBOL;
      $IRT->select_sub.=$IRT->get_item_sub($IRT->select_item_num)._SPLIT_SYMBOL;
      if($IRT->get_item_op_ans($IRT->select_item_num)==$_POST["user_answer"]){
        $IRT->response.="1"._SPLIT_SYMBOL;
      }else{
        $IRT->response.="0"._SPLIT_SYMBOL;
      }
    }
    $select_sub=explode(_SPLIT_SYMBOL,$IRT->select_item_sub_A);
    $select_sub[$IRT->get_item_sub($IRT->select_item_num)-1]++;
    $IRT->select_item_sub_A=implode(_SPLIT_SYMBOL,$select_sub);
    $IRT->del_item_left($IRT->select_item_num);
    //答題記錄 end
    
    //能力估計
    if($IRT->step==2){
      $IRT->h_exam_b=$IRT->h_exam;
      $IRT->h_exam=$IRT->get_MLE();
      $IRT->h_exam_A.=$h_exam._SPLIT_SYMBOL;
      $IRT->error_d = abs($h_exam-$IRT->h_exam_b);
    }
    //能力估計 end
    
    $_SESSION['IRT']=serialize($IRT);
        
    include_once("record/result.php"); //結果呈現 與 作答紀錄
    die(__LINE__);  
  }  
}

//判斷 F5
if(isset($_POST['user_answer'])&&isset($_SESSION['IRT'])&&$_SESSION['code']==$_POST['code']){
  //f5 跳出
  /*
  $IRT=unserialize($_SESSION['IRT']);
  //清除資料庫中暫存的考試記錄
  $sql="DELETE FROM session_data WHERE user_id='".$_SESSION['user_data']->user_id."' AND course_id='".$IRT->AuthCourseID."' AND cs_id='".$IRT->get_csid()."' AND paper_vol='".$IRT->get_paper_vol()."' ";
  $result = $dbh->query($sql);
  unset($_SESSION['IRT']);
  unset($IRT);
  echo "<br>測驗中，請勿重新整理視窗或回到上一頁，請重新參加測驗<P>";
  $RedirectTo="modules.php?op=main";
  echo '<a href="'.$RedirectTo.'">[ 按此返回 ]</a>';
  die();
  */
  
  //F5 重讀資料  
  $sql="SELECT * FROM session_data WHERE user_id='".$_SESSION['user_data']->user_id."' AND course_id='".$_SESSION['AuthCourseID']."' AND cs_id='".$_POST['cs_id']."' AND paper_vol='".$_POST['paper_vol']."' ";
  $result = $dbh->query($sql);
  while ($data = $result->fetchRow()){
    $tmp=json_decode(gzuncompress(base64_decode($data['session_data'])),true);    
    $_SESSION['IRT']=$tmp[0];
    $_SESSION['time']=$tmp[1];
    $_SESSION['code']=$tmp[2];  
    $_POST=json_decode(gzuncompress(base64_decode($data['post_data'])),true);  
  }  
    
}

//...................................................................判斷是否第一次作答

//確認是否有中斷預存資料
if((!isset($_POST['user_answer']))&&(!isset($_SESSION['IRT']))){  //如果是第一次作答

  $sql="SELECT * FROM session_data WHERE user_id='".$_SESSION['user_data']->user_id."' AND course_id='".$_SESSION['AuthCourseID']."' AND cs_id='".$_POST['cs_id']."' AND paper_vol='".$_POST['paper_vol']."' ";
  $result = $dbh->query($sql);
  while ($data = $result->fetchRow()){
    $tmp=json_decode(gzuncompress(base64_decode($data['session_data'])),true);    
    $_SESSION['IRT']=$tmp[0];
    $_SESSION['time']=$tmp[1];
    $_SESSION['code']=$tmp[2];  
    $_POST=json_decode(gzuncompress(base64_decode($data['post_data'])),true);  
  }  
}

if(isset($_POST['user_answer'])&&isset($_SESSION['IRT'])){

  $IRT=unserialize($_SESSION['IRT']);
  //產生session_data
  make_session_to_sql($_SESSION['user_data']->user_id , $_SESSION['AuthCourseID'] ,$IRT->get_csid() , $IRT->get_paper_vol() ,$_POST);  
  
  //計算每題作答時間
  $_SESSION['time']["item_end_time"]=time();
  $item_idle_time=$_SESSION['time']["item_end_time"]-$_SESSION['time']["item_start_time"];
  $_SESSION['time']["items_idle_time"].=$item_idle_time._SPLIT_SYMBOL;
  //記錄client單題作答時間(毫秒)
  $client_item_idle_time=$_POST['time']["client_end_ms"]-$_POST['time']["client_start_ms"];
  $_SESSION['time']["client_items_idle_time"].=$client_item_idle_time._SPLIT_SYMBOL;

	$_SESSION['code']=$_POST['code'];
  $CsID=$IRT->get_csid();  //考卷的ID
	$PaperVol=$IRT->get_paper_vol();  //卷
  $selected_item=$IRT->select_item_num; //試題編號  
	$item_sn=$IRT->select_item_sn;
  
  //答題記錄
  if($IRT->step==1){  
    $IRT->pretest_rec_user_answer.=$_POST["user_answer"]._SPLIT_SYMBOL;
    $IRT->pretest_select_item_id.=$IRT->select_item_sn._SPLIT_SYMBOL;
    if($IRT->get_item_op_ans($IRT->select_item_num)==$_POST["user_answer"]){
      $IRT->pretest_response.="1"._SPLIT_SYMBOL;
    }else{
      $IRT->pretest_response.="0"._SPLIT_SYMBOL;
    }
  }else{
    $IRT->rec_user_answer.=$_POST["user_answer"]._SPLIT_SYMBOL;
    $IRT->h_exam_A.=$IRT->h_exam._SPLIT_SYMBOL;
    $IRT->select_item_id_S.=$IRT->select_item_sn._SPLIT_SYMBOL;
    $IRT->select_item_id_A.=$IRT->select_item_sn._SPLIT_SYMBOL;
    $IRT->select_P_a.=$IRT->get_item_a($IRT->select_item_num)._SPLIT_SYMBOL;
    $IRT->select_P_b.=$IRT->get_item_b($IRT->select_item_num)._SPLIT_SYMBOL;
    $IRT->select_P_c.=$IRT->get_item_c($IRT->select_item_num)._SPLIT_SYMBOL;
    $IRT->select_sub.=$IRT->get_item_sub($IRT->select_item_num)._SPLIT_SYMBOL;
    if($IRT->get_item_op_ans($IRT->select_item_num)==$_POST["user_answer"]){
      $IRT->response.="1"._SPLIT_SYMBOL;
    }else{
      $IRT->response.="0"._SPLIT_SYMBOL;
    }
  }
  $select_sub=explode(_SPLIT_SYMBOL,$IRT->select_item_sub_A);
  $select_sub[$IRT->get_item_sub($IRT->select_item_num)-1]++;
  $IRT->select_item_sub_A=implode(_SPLIT_SYMBOL,$select_sub);
  $IRT->del_item_left($IRT->select_item_num);
  //答題記錄 end
  
  //能力估計
  if($IRT->step==2){
    $IRT->h_exam_b=$IRT->h_exam;
    $IRT->h_exam=$IRT->get_MLE();
    $IRT->h_exam_A.=$h_exam._SPLIT_SYMBOL;
    $IRT->error_d = abs($h_exam-$IRT->h_exam_b);
  }
  //能力估計 end

  //階段判斷
  if($IRT->step==1){
    if($IRT->max_sub_itemlength[$IRT->get_pretest_sub()-1]==$select_sub[$IRT->get_pretest_sub()-1]){
      $IRT->step=2;  //如果預試最大題數到達了，進入 step=2
      
      //預試能力預估
      $tmp=explode(_SPLIT_SYMBOL,$IRT->pretest_response);
      array_pop($tmp);
      $pretest_percent=array_sum($tmp)/sizeof($tmp);
      if($pretest_percent>0.5){
        $IRT->h_exam=1;
      }elseif($pretest_percent=0.5){
        $IRT->h_exam=0;  
      }else{
        $IRT->h_exam=-1;
      }
    }
  }elseif($IRT->step==2){
    //確認是否還有子測驗要做
    $sub_n=$IRT->get_sub_n();
    $tmp=0;
    for($i=0;$i<$sub_n;$i++){
      if($select_sub[$i]<$IRT->max_sub_itemlength[$i]){
        $tmp++;
      }
    }
    if($tmp==0){
      $_SESSION['IRT']=serialize($IRT);
      include_once("record/result.php"); //結果呈現 與 作答紀錄
      die(__LINE__);
    }    
  }
  //階段判斷 end
 
  //print_r($IRT->max_sub_itemlength);
  //print_r($IRT->select_item_sub_A);
  //選題
  if($IRT->step==1){
    $IRT->select_item_num=$IRT->pretest_item_num_order[$IRT->pretest_select_num];
    $IRT->select_item_sn=$IRT->get_item_sn_by_num($IRT->select_item_num);
    $IRT->pretest_select_num++;
    $IRT->select_num++;
  }elseif($IRT->step==2){
    
    //決定要做哪一個 $sub
    $sub=$IRT->next_sub();
    
    $IRT->select_item_num=$IRT->FI_by_sub($sub);
    $IRT->select_item_sn=$IRT->get_item_sn_by_num($IRT->select_item_num);    
    $IRT->select_num++;
  }
  
  $_SESSION['IRT']=serialize($IRT);
  
}else{
	//初始測驗時    
  $CsID=$_POST['cs_id'];
  $PaperVol=$_POST['paper_vol'];  
  $ep_id=$_POST['cs_id'].sprintf("%02d",$_POST['paper_vol']);    
  
  //..........................找出選擇IRT施測類型
  $tmp=0;
  $AuthCourseID=$_SESSION['AuthCourseID'];
  
  $sql="SELECT * FROM `exam_course_access_irt` WHERE (`cs_id`=$CsID) and (`course_id`=$AuthCourseID) and (`paper_vol`=$PaperVol)";
  $result = $dbh->query($sql);
	while ($row = $result->fetchRow()) {
		$test_time = $row["test_time"]; //  測驗時間
    }
  $_SESSION['time']["test_time"] = $test_time;
  $_SESSION['time']["test_time_sec"]=$_SESSION['time']["test_time"]*60;
  
  $_SESSION['time']["start_time"]=date("U");       //初始化時間
  $_SESSION['time']["date"]= date("Y-m-d, H:i:s");  //開始測驗時間
  //......................................end
  
  $IRT=new IRTConceptStructure($CsID,$PaperVol);
	
  $IRT->exam_type=$_POST['exam_type'];
  //print_r($IRT->exam_type);
  //因應預試試題，所以一開始先測預試的題目
  $IRT->step=1; //測驗階段=1
  $IRT->select_num=0;
  $IRT->pretest_select_num=0;
  //$IRT->select_item_num=$IRT->pretest_random_item();
  
  //排出預試試題順序
  $IRT->pretest_item_num_order=$IRT->get_pretest_item_num_order();
  $IRT->select_item_num=$IRT->pretest_item_num_order[$IRT->pretest_select_num];
  $IRT->select_item_sn=$IRT->get_item_sn_by_num($IRT->select_item_num);      
  $IRT->select_num ++;
  $IRT->pretest_select_num++;   
	$_SESSION['time']['StartTimestamp']=time();
	$_SESSION['time']['StopTimestamp']=$_SESSION['time']['StartTimestamp']+$_SESSION['time']["test_time_sec"];
  $_SESSION['IRT']=serialize($IRT);   
}

//f5
$code=mt_rand(0,1000000); 

//取得試題資料
$op_ans=$IRT->get_item_op_ans($IRT->select_item_num);
$P_a=$IRT->get_item_a($IRT->select_item_num);
$P_b=$IRT->get_item_b($IRT->select_item_num);
$P_c=$IRT->get_item_c($IRT->select_item_num);
$P_sub=$IRT->get_item_sub($IRT->select_item_num);
$cs_id=$IRT->get_csid();
$paper_vol=$IRT->get_paper_vol();
$course_id=$_SESSION['AuthCourseID'];

  
$question=new Item_Structure4IRT($cs_id, $IRT->select_item_num, $paper_vol); //呼叫試題圖檔

//出題
$tableH=0;
$question_select_pic=$question->get_item_select_pic();
//print_r($_SESSION['user_data']);
//<img src="'._THEME_IMG.'li.gif" width="11" height="28" />
echo '<br><table width="700" border="0" cellspacing="6" cellpadding="0">
     <tr><td><p align="left"><font size="6"  style="font-family:標楷體;" >准考證號碼：'.$_SESSION['user_data']->user_id.' 姓名：'.$_SESSION['user_data']->uname.'</p></td></tr>
     <tr>
      <td scope="col"><table width="100%" border="0" cellpadding="2" cellspacing="0" class="title">
        
        <tr>
          
          		<td width="50%" scope="col" align="left"><font size="5" style="font-family:標楷體;">第'.$IRT->select_num.'題</font></td>';
				if(isset($_SESSION['time']['test_time']) and intval($_SESSION['time']['test_time'])>0){
					echo '<td width="50%" scope="col" align="right"><font size="5" style="font-family:標楷體;">剩餘時間：<font color="#a52a2a"><strong><span id="span_timer"></span></strong></font></td>';
				}
				echo '<tr><td><input type="image" value="變大" src="images/Enlarge.png" onclick="AutoResizeImage(2,0)">
				<input type="image" value="還原" src="images/Recovery.png" onclick="AutoResizeImage(0,0)">
				<input type="image" value="變小" src="images/Narrow.png" onclick="AutoResizeImage(1,0)">
				</td>';
				
		echo '</tr>
      </table></td>
    </tr>';
if(isset($showfig)){		unset($showfig);	}
$PImgProp['item_filename']=GetImageSize($_SESSION['cs_path'].$question->item_filename);
$showfig=explode(".", $question->item_filename);
$showfig[0]=str2compiler($showfig[0]);
echo '<tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td scope="col"><img src="'._THEME_IMG.'main_lt.gif" width="12" height="12" /></td>
          <td width="96%" background="'._THEME_IMG.'main_ct.gif" scope="col"></td>
          <td scope="col"><img src="'._THEME_IMG.'main_rt.gif" width="12" height="12" /></td>
        </tr>
        <tr>
          <td width="12" background="'._THEME_IMG.'main_lc.gif">&nbsp;</td>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" height="0">
            <tr>
              <td align="left" scope="col" height="0"><img name="Exam_Q" src="viewfig.php?list='.$showfig[0].'&tpp='.$showfig[1].'" border="0">';	

			  //<td align="left" scope="col" height="'.$PImgProp['item_filename'][1].'"><img name="Exam_Q" src="viewfig.php?list='.$showfig[0].'&tpp='.$showfig[1].'" border="0">';
echo '</td>
            </tr>
          </table></td>
          <td background="'._THEME_IMG.'main_rc.gif">&nbsp;</td>
        </tr>
        <tr>
          <td><img src="'._THEME_IMG.'main_ld.gif" width="12" height="12" /></td>
          <td background="'._THEME_IMG.'main_cd.gif"></td>
          <td><img src="'._THEME_IMG.'main_rd.gif" width="12" height="12" /></td>
        </tr>
      </table>
        </td>
    </tr>';
echo '<tr><td><form method="POST" name=keypad action="modules.php">'."\n";
for($i=0;$i<$question->get_item_select_num();$i++)
{
  $PImgProp['op_pieces'.$i]=GetImageSize($_SESSION['cs_path'].$question->op_pieces[$i]);
  $tableH+=$PImgProp['op_pieces'.$i][1];
}
$tableH+=56;
$tableH =0;
echo '<table width="100%" height="'.$tableH.'" border="0" cellpadding="2" cellspacing="2" class="line01">';
$the_user_answer = "user_answer".$x;
for($i=0;$i<$question->get_item_select_num();$i++)
{
if(isset($showfig)){		unset($showfig);	}
  $showfig=explode(".", $question->op_pieces[$i]);
  $showfig[0]=str2compiler($showfig[0]);
  //echo "<tr><td width=\"50\" align=\"center\" scope=\"col\"><input type=\"radio\" name=\"$the_user_answer\" value=\"".($i+1)."\" id=\"sq".$i."\"></td><td width=\"650\" align=\"left\"  class=\"s_title\" height=\"".$PImgProp['op_pieces'.$i][1]."\"><label for=\"sq".$i."\"><img name=\"Exam_t".$i."\" border=\"0\" src=\"viewfig.php?list=".$showfig[0]."&tpp=".$showfig[1]."\"></label></td></tr>\n";
  echo "<tr><td width=\"50\" align=\"center\" scope=\"col\"><input type=\"radio\" name=\"$the_user_answer\" style=\"width:25px;height:25px;\" value=\"".($i+1)."\" id=\"sq".$i."\" required=\"required\"></td><td width=\"650\" align=\"left\"  class=\"s_title\" height=\"0\"><label for=\"sq".$i."\"><img name=\"Exam_t".$i."\" border=\"0\" src=\"viewfig.php?list=".$showfig[0]."&tpp=".$showfig[1]."\"></label></td></tr>\n";
}
	echo "</table>";
echo '</td></tr></table>'; 
echo "<input type=\"hidden\" name=\"client_start_ms\" value=\"\">";
echo "<input type=\"hidden\" name=\"client_end_ms\" value=\"\">";
echo "<input type=\"hidden\" name=\"cs_id\" value=".$cs_id.">";
echo "<input type=\"hidden\" name=\"paper_vol\" value=".$paper_vol.">";
echo "<input type=\"hidden\" name=\"code\" value=".$code.">";
echo "<input type=\"hidden\" name=\"exam_type\" value=".$IRT->exam_type.">";
echo "<input name=\"op\" type=\"hidden\" value=\"modload\">";
echo "<input name=\"name\" type=\"hidden\" value=\"IRT\">";    
echo '<input name="file" type="hidden" value="'.$_REQUEST['file'].'">';
echo '<input name="screen" type="hidden" value="all">';
echo "<input id=\"layoutsize\" name=\"layoutsize\" type=\"hidden\" value=".$_POST['layoutsize'].">";
  //echo "<center><input type=\"submit\" value=\"選擇完畢\n進入下一題\" name=\"submit\"  class=\"butn01\" style=\"width:150px;height:75px;\" onClick=itemtimesent()>\n";
  echo "<center><input type=\"image\" value=\"選擇完畢\n進入下一題\" name=\"submit\"  alt=\"Submit\"  src=\"images/next_item.png\" onClick=itemtimesent()>\n";
  echo '</form>';
  echo "<table>
        <center>
        <tr>
        <td><div align=\"center\">";
  if($user_data->access_level==9)
  {
    if (($IRT->select_num)>1)
    {
      echo"前一次能力估計值".str_replace(_SPLIT_SYMBOL, "", $IRT->h_exam_b).
      "　能力估計值".str_replace(_SPLIT_SYMBOL, "", $IRT->h_exam).
      "　前後能力估計的差距".str_replace(_SPLIT_SYMBOL, "", abs($IRT->h_exam-$IRT->h_exam_b));
    }
    echo"<P>ans:".str_replace(_SPLIT_SYMBOL, "", $op_ans).
       "　鑑別度".str_replace(_SPLIT_SYMBOL, "", $P_a).
   "　難度".str_replace(_SPLIT_SYMBOL, "", $P_b).
   "　猜測度".str_replace(_SPLIT_SYMBOL, "", $P_c).
   "　指標".str_replace(_SPLIT_SYMBOL, "", $P_sub);
  }
  echo "</div>
      </td>
      </tr>
      </center>
      </table>";
	  
		echo "<script type=\"text/javascript\">
			var size_x = ".$_POST['layoutsize']."+0;
			window.onload=AutoResizeImage(size_x,0);
		
		
		
			//圖片大小
		function AutoResizeImage(maxWidth,maxHeight){ 
			var objImg = document.images[\"Exam_Q\"]; 
			var objImg_t1 = document.images[\"Exam_t0\"]; 
			var objImg_t2 = document.images[\"Exam_t1\"]; 
			var objImg_t3 = document.images[\"Exam_t2\"]; 
			var objImg_t4 = document.images[\"Exam_t3\"];
			
			var objImg_top = document.images[\"TOP_IMG\"];
			var objImg_under = document.images[\"UNDER_IMG\"];
			
			var w = objImg.width; 
			var w_t = objImg_t1.width;  
			if (maxWidth ==0 && maxHeight==0){  
				w = 960;
				w_t = 960;
				document.getElementById(\"layoutsize\").value = w;
			}else if (maxWidth==2){// 
				if(objImg.width < 1400){
					w = w + 200;
					w_t = w_t + 200;
					document.getElementById(\"layoutsize\").value = w;
				}else{
					alert(\"已放大至最大\");
				}
				
			}else if (maxWidth==1){ 
				if(objImg.width > 400){
					w = w - 200;
					w_t = w_t - 200;
					document.getElementById(\"layoutsize\").value = w;					
				}else{
					alert(\"已縮小至最小\");
				}
			}else if (maxWidth > 100){ 
				w = maxWidth;
				w_t = maxWidth;
			}
			
			
			objImg.width = w; 
			if(w <= 960){
				//objImg_under.width = 1060;
				objImg_top.width = 1060;
				
			}else{
				//objImg_under.width = w + 100;
				objImg_top.width = w + 100;
				
			}
			
			objImg_t1.width = w_t;
			objImg_t2.width = w_t;
			objImg_t3.width = w_t;
			objImg_t4.width = w_t;
			
			
		
		}
		
		
		
		</script>
		";
  
  //記錄本題考試時間
  $_SESSION["item_start_time"]=time();

//計算考試時間用
if(isset($_SESSION['time']['test_time']) and intval($_SESSION['time']['test_time'])>0){
	//$redirectURL=_ADP_URL."modules.php?op=modload&name=".$module_name."&file=".$SubmitFile."&screen=all&h_exam=".$h_exam."&cs_id=".$CsID."&CsID=".$CsID."&paper_vol=".$PaperVol."&select_num=".$select_num."&selected_item=".$selected_itemm."&item_sn=".$item_snn."&P_a=".$P_a."&P_b=".$P_b."&P_c=".$P_c."&test_num=".$n."&exam_type=".$_SESSION["exam_type"];
	$redirectURL=_ADP_URL."modules.php?op=modload&name=".$module_name."&file=".$SubmitFile."&screen=all&TestTimeIsUP=1&cs_id=".$_SESSION['user_data']->user_id."&cs_id=".$cs_id."&paper_vol=".$paper_vol."&course_id=".$course_id;

	//$redirectURL="Location: modules.php?op=modload&name=".$module_name."&file=".$SubmitFile."&opt=EP2course&course[0]=".$CourseID[0]."&course[1]=".$CourseID[1]."&course[2]=".$CourseID[2];
	//$redirectURL="";
	//debug_msg("第".__LINE__."行 _POST ", $_POST);

	$ItemTestTime=$_SESSION['time']['StopTimestamp']-time();
	echo '
	<script language="JavaScript1.3">
		var timer = 0;
		var mytime = '.$ItemTestTime.';
		var t1 = (new Date()).getTime()+0;
		var itemstart;
		itemstart=new Date();
		document.keypad.client_start_ms.value=itemstart.getTime();
		counter();
		//倒數計時
		function counter(){	
			if(mytime < 0){
				//alert("時間到！請按下「確定」");
				//var t3=t2-t1;
				window.location.href = "'.$redirectURL.'";
				
			}else{
				//var msec=mytime%100;
				//var sec=(mytime - msec)%60;
				var sec=mytime%60;
				var min=(mytime - sec)/60;
				document.getElementById(\'span_timer\').innerHTML = min+"分"+sec+"秒<br><br>";
				//暫停一秒
				setTimeout("counter()", 1000);
				//setTimeout("counter()", 10);
	
				//倒數
				mytime--;
				timer++;
			}
		}

		function itemtimesent(){	
			var itemend;
			itemend=new Date();
			//alert("時間到！請按下「確定」"+itemend.getTime());
			document.keypad.client_end_ms.value=itemend.getTime();
			
		}

	</script>';
	//echo $ptr;
}


//製作暫存資料
function make_session_to_sql($user_id , $course_id ,$cs_id , $paper_vol , $post){

  global $dbh;
  //確認sql方式
  $sql="SELECT * FROM session_data WHERE user_id='".$user_id."' AND course_id='".$course_id."' AND cs_id='".$cs_id."' AND paper_vol='".$paper_vol."' ";
  $result = $dbh->query($sql);
  $sql_mode="INSERT";
  while ($data = $result->fetchRow()) {
    if($user_id==$data['user_id']){
      $session_id=$data['session_id'];
      $sql_mode="UPDATE";
    }
  }
  //製作 session data  
  $session_tmp=array($_SESSION['IRT'],$_SESSION['time'],$_SESSION['code']);  
  $session_data=base64_encode(gzcompress(json_encode($session_tmp)));
  $post_data=base64_encode(gzcompress(json_encode($post)));
  $date=date("Y-m-d H:i:s");  

  //上傳sql
  if($sql_mode=="INSERT"){
    $sql='INSERT INTO session_data (user_id, course_id, cs_id, paper_vol, date, session_data, post_data) VALUES (?,?,?,?,?,?,?)';
    $data=array($user_id , $course_id , $cs_id , $paper_vol , $date , $session_data , $post_data);
    $result =$dbh->query($sql, $data);
  }elseif($sql_mode=="UPDATE"){
    $table_name   = 'session_data';
		$table_values = array(
			'user_id' => $user_id,
			'course_id' => $course_id,
			'cs_id' => $cs_id,
      'paper_vol' => $paper_vol,
      'date' => $date,
      'session_data' => $session_data,
      'post_data' => $post_data
		);
		//debug_msg("第".__LINE__."行 table_values ", $table_values);
		$table_field='session_id ='.$session_id;
		$result = $dbh->autoExecute($table_name, $table_values, DB_AUTOQUERY_UPDATE , $table_field);
  }
}


?>
