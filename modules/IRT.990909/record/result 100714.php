<?php
  
  $_SESSION["stop_time"] = date("U");
  $cost_time = $_SESSION["stop_time"]-$_SESSION["start_time"];
  $total_items = $_SESSION["select_num"]-1;
  
  $exam_type_all = $_SESSION['exam_type'].'@XX@'.$type_id_t.'@XX@'.$type_id_s.'@XX@'.$type_id_e.'@XX@'.$num_e.'@XX@'.$item_length;
  //echo $MIRT_d.'<br>';
  //echo $CsID.'<br>';  
  echo "<br>恭喜你測驗完畢!<P>";
  if ($MIRT_d>1)
  {
    $h_exam_tmp= explode("@XX@",$_SESSION["h_exam_A"]);//暫存能力估計值陣列
    echo '<table border="1">
            <tr>
              <td>向度</td>                    
              <td>能力估計值</td>
              <td>已作題目</td>
            </tr>';           
      
    $h_exam_fal = '';
    $percent_score = '';
    for($i=1;$i<=$MIRT_d;$i++)
    {                
      $h_n = ($select_num-$MIRT_d)*$MIRT_d+($i-1);//這一次D個向度能力估計值的順序
      echo'<tr>
              <td><div align="center">'.$i.'</div></td>                    
              <td>'.$h_exam_tmp[$h_n].'</td>
              <td>'.$theta_count[$i-1].'</td>
              </tr>';   
      $h_exam_fal .= $h_exam_tmp[$h_n]._SPLIT_SYMBOL;
      //$_SESSION["select_item_b_theta"].= $P_b_theta."','";	
      //...................................................................計算各能力向度的百比分數   
      $score = $h_exam_tmp[$h_n]*15.625+50;
      $score = round($score);    
      $percent_score .= $score._SPLIT_SYMBOL;     
      //...................................................................計算各能力向度的百比分數 end
    }
    echo '</table>';
    $sql=mysql_query("SELECT DISTINCT `dim_name` FROM `concept_info_dim`
                        WHERE `cs_id` like '".$CsID."'");
    while($row=mysql_fetch_array($sql))
    {  
      $dim_name = $row[0];      
    }
    mysql_free_result($sql);
    $sub_score_name = $dim_name; //紀錄向度名稱 變數設為$sub_score_name是為了與下面統一 方便寫入資料庫
    $sub_score_res = $percent_score;
  }
  else
  {
    $h_exam_fal = $h_exam;
    //echo $user_data->access_level;  // access_level =8
    
    //...................................................................次級量尺分數   
      //  計算有幾種次級量尺
      $sql=mysql_query("SELECT DISTINCT `sub_score_name` FROM `concept_info_dim`
                        WHERE `cs_id` like '".$CsID."'");
      while($row=mysql_fetch_array($sql))
      {  
        $sub_score_name = $row[0];      
      }
      mysql_free_result($sql);
      $concept = explode("@XX@",$sub_score_name);
      $unit = count($concept);
      //  end
      //  計算每一個次級量尺有幾題 
      //...................................................................function_P    1
      function U_u($D = 1.702,$A_item,$B_item,$h_exam)   
      {   
        $U = pow((1+exp(-$D*$A_item*($h_exam-$B_item))),(-1));
        return $U;
      }
      //...................................................................function_P    2
      function P_u($C_item,$U) 
      {   
        $P = $C_item+(1-$C_item)*$U;
        return $P;
      } 
      
      $Siia = explode("@XX@",$_SESSION["select_item_id_A"]);
      for($i=0;$i<$total_items;$i++)
      	{
		 $Siia_i .= $Siia[$i]."','";
		}
	  $sql=mysql_query("SELECT `a`,`b`,`c`,`sub` FROM `concept_item_parameter`
                        WHERE `cs_id` like '".$CsID."' and `item_sn` in ('".$Siia_i."') ");
      while($row=mysql_fetch_array($sql))
      {
	   $S_a[]= $row[0];
	   $S_b[]= $row[1];
	   $S_c[]= $row[2];
       $Siis[]=$row[3];
      }
      mysql_free_result($sql);
      
      for($i=0;$i<$unit;$i++)
      {
        $n = 0;
        $tmp = 0;
        for($j=0;$j<$total_items;$j++)
        	{
			 if($Siis[$j]==$i+1)
			 	{
				 $U = U_u($D,$S_a[$j],$S_b[$j],$h_exam);  
         		 $P = P_u($S_c[$j],$U);
         		 $tmp = $tmp+$P;   
         		 $n++; 
				}
			}
		$sub_score[$i] = $tmp/$n;
        $sub_score[$i] = round($sub_score[$i]*100,2);
/*        $sql=mysql_query("SELECT `a`,`b`,`c` FROM `concept_item_parameter` 
                          WHERE `cs_id` like '".$CsID."' and sub ='".($i+1)."'");
        while($row=mysql_fetch_array($sql))
        {  
          $a = $row[0];
          $b = $row[1];
          $c = $row[2];      
          $U = U_u($D,$a,$b,$h_exam);  
          $P = P_u($c,$U);
          $tmp = $tmp+$P;   
          $n++;
        }
        $sub_score[$i] = $tmp/$n;
        $sub_score[$i] = round($sub_score[$i]*100,2);
        mysql_free_result($sql);
*/        
      }        
      echo '<table border="1">
            <tr>
              <td><div align="center">各項子測驗</div></td>
              <td><div align="center">平均通過率</div></td>
            </tr>';
      for($i=0;$i<$unit;$i++)
      {
        echo '<tr>
                <td>'.$concept[$i].'</td>
                <td><div align="center">'.$sub_score[$i].'%</div></td>
              </tr>';  
              $sub_score_res .= $sub_score[$i]._SPLIT_SYMBOL;
      }  
      echo '</table>';       
    //...................................................................次級量尺分數 end
  } 
    //===================   
    //===== 一些找出的相關資料,暫時保留
      $sql=mysql_query("SELECT `firm_id` FROM `user_info` WHERE `user_id` like '".$user_id."'");    
      while($row=mysql_fetch_array($sql))
      {        
        $firm_id = $row[0];
      }
      mysql_free_result($sql);
    //  end
    
    //-- 寫入資料庫
      //...................................................................紀錄此單元及試題有多少人做過...1      
      include("record_people.php");
      //...................................................................紀錄此單元及試題有多少人做過...end  
      //...................................................................符合條件時 更新曝光參數...2      
      include("renewEP.php");
      //...................................................................符合條件時 更新曝光參數...end
      //將 ',' 換成 @XX@      
      $select_item_id_S_new = explode("','",$_SESSION["select_item_id_S"]);
      $select_item_id_S_new1 = '';
      for($i=0;$i<count($select_item_id_S_new)-1;$i++)
      {
        $select_item_id_S_new1 .= $select_item_id_S_new[$i]."@XX@";
      }
      $_SESSION["select_item_id_S"] = $select_item_id_S_new1;
      
      $select_item_id_A_new = explode("','",$_SESSION["select_item_id_A"]);
      $select_item_id_A_new1 = '';
      for($i=0;$i<count($select_item_id_A_new)-1;$i++)
      {
        $select_item_id_A_new1 .= $select_item_id_A_new[$i]."@XX@";
      }
      $_SESSION["select_item_id_A"] = $select_item_id_A_new1;      
      //將 ',' 換成 @XX@ ....end
    //...................................................................清除重複學習紀錄
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
      
    //...................................................................紀錄學習紀錄   
    $sql = 'INSERT exam_record_irt (user_id, cs_id, date, start_time, stop_time, during_time, firm_id,            
                                    theta, total_items, select_item_id_S, select_item_id_A, org_res, binary_res,
                                    exam_res, sub_score_name, sub_score_res, paper_vol, type_id, theta_res, dim)
                            VALUES ("'.$user_id.'","'.$CsID.'","'.$_SESSION['date'].'","'.$_SESSION['start_time'].
                            '","'.$_SESSION["stop_time"].'","'.$cost_time.'","'.$firm_id.'","'.$h_exam_fal.'","'.
                            $total_items.'","'.$_SESSION["select_item_id_S"].'","'.$_SESSION["select_item_id_A"].
                            '","'.$_SESSION["rec_user_answer"].'","'.$_SESSION["select_ans"].'","'.$_SESSION["h_exam_A"].
                            '","'.$sub_score_name.'","'.$sub_score_res.'","'.$_SESSION["PaperVol"].
                            '","'.$exam_type_all.'","'.$_SESSION["select_item_b_theta"].'","'.$MIRT_d.'")';
    mysql_query($sql);  
    $result=1;
    if($result)
    {
      $RedirectTo="Location: modules.php?op=modload&name=ExamResult&file=classReports_right_now&report=1&q_user_id=".$user_id."&q_cs_id=".$CsID;
      exam_clean_all (); //-- 清除session中相關記憶
      Header($RedirectTo);
    }    
    //$RedirectTo="modules.php?op=main";
    //echo '<a href="'.$RedirectTo.'">[ 按此返回 ]</a>';
?>
