<?php
  $IRT=unserialize($_SESSION['IRT']);
 
    /*
    //...........................................................清除重複學習紀錄
    $re_record = 0;
    $sql=mysql_query("SELECT * FROM `exam_record_irt` WHERE `user_id` like '".$user_id."' 
                      and `cs_id` like '".$CsID."' and `paper_vol` = '".$_SESSION["PaperVol"]."'");    
    while($row=mysql_fetch_array($sql))
    {        
		$re_record = 1;
    }
    mysql_free_result($sql);
    if($re_record==1)
    {
		$sql = "DELETE FROM `exam_record_irt` WHERE `user_id` like '".$user_id."' 
              and `cs_id` like '".$CsID."' and `paper_vol` = '".$_SESSION["PaperVol"]."'";
		mysql_query($sql);
    }
    */  
    //...................................................................紀錄學習紀錄
    //預試資料準備
    
    //正式資料準備
    $user_id=$_SESSION['user_data']->user_id;
    $cs_id=$IRT->get_csid();
    $cost_time=$_SESSION["stop_time"]-$_SESSION["start_time"];
    //$firm_id
    $_SESSION["stop_time"]=date("U");
    $theta=$IRT->h_exam; 	
  	$total_items=$IRT->select_num-$IRT->pretest_select_num;
    $sub_score_name=$IRT->sub_score_name;
    $sub_score_res=$IRT->get_sub_MLE();
    $paper_vol=$IRT->get_paper_vol();
    $exam_type_all=$IRT->exam_type._SPLIT_SYMBOL.$IRT->type_id_t._SPLIT_SYMBOL.$IRT->type_id_s._SPLIT_SYMBOL.$IRT->type_id_e._SPLIT_SYMBOL.$num_e._SPLIT_SYMBOL.$IRT->item_length;
    //$_SESSION["select_item_b_theta"]
    $dim=$IRT->get_sub_n();
    $epid=$IRT->get_epid();
      
  	//預試
    $sql='INSERT pretest_record_irt (user_id,cs_id,total_items,select_item_id_S,select_item_id_A,org_res,binary_res,paper_vol,course_id,exam_title) VALUES ("'.$user_id.'","'.$cs_id.'","'.$IRT->pretest_select_num.'","'.$IRT->pretest_select_item_id.'","'.$IRT->pretest_select_item_id.'","'.$IRT->pretest_rec_user_answer.'","'.$IRT->pretest_response.'","'.$paper_vol.'","'.$_SESSION['AuthCourseID'].'","'.$epid.'")';
    $result = mysql_query($sql);
    
    
    //正式測驗      
    $sql = 'INSERT exam_record_irt (user_id, cs_id, date, start_time, stop_time, during_time, firm_id,theta, total_items, select_item_id_S, select_item_id_A, org_res, binary_res,exam_res, sub_score_name, sub_score_res, paper_vol, type_id, theta_res, dim, course_id, exam_title) VALUES ("'.$user_id.'","'.$cs_id.'","'.$_SESSION['date'].'","'.$_SESSION['start_time'].'","'.$_SESSION["stop_time"].'","'.$cost_time.'","'.$firm_id.'","'.$theta.'","'.$total_items.'","'.$IRT->select_item_id_S.'","'.$IRT->select_item_id_A.'","'.$IRT->rec_user_answer.'","'.$IRT->response.'","'.$IRT->h_exam_A.'","'.$sub_score_name.'","'.$sub_score_res.'","'.$paper_vol.'","'.$exam_type_all.'","'.$_SESSION["select_item_b_theta"].'","'.$dim.'","'.$_SESSION['AuthCourseID'].'","'.$epid.'")';
    $result = mysql_query($sql);
    //$result=1;
    if($result==TRUE){
      //清除資料庫中暫存的考試記錄
      $sql="DELETE FROM session_data WHERE user_id='".$_SESSION['user_data']->user_id."' AND course_id='".$IRT->AuthCourseID."' AND cs_id='".$IRT->get_csid()."' AND paper_vol='".$IRT->get_paper_vol()."' ";
      $result = $dbh->query($sql);
  		
      $RedirectTo="Location: modules.php?op=modload&name=ExamResult&file=classReports_right_now&report=1&q_user_id=".$user_id."&q_cs_id=".$cs_id;
  		exam_clean_all (); //-- 清除session中相關記憶
  		//Header($RedirectTo);
  		echo '<center><br><br><br><br>測驗完畢，謝謝您的參與！<br><br><br>【<a href="index.php">回首頁</a>】<br><br><br><br><br><br></center>';
  		echo "</td>";
  		echo '</div>';
  		echo '</TD>';
  		echo '<TD WIDTH="50" HEIGHT="380" background="images/stu_inner_5.jpg" ></TD>
  			</TR>';
  
  		echo '<TR>
  			<TD COLSPAN="3"><IMG WIDTH="960" HEIGHT="141" SRC="images/stu_inner_6.jpg" ></TD>
  		</TR>
  		</TABLE>
  		</BODY>
  		</HTML>';
    }else{
    	die (__LINE__." Invalid query 儲存失敗");
    }
    //$RedirectTo="modules.php?op=main";
    //echo '<a href="'.$RedirectTo.'">[ 按此返回 ]</a>';
?>
