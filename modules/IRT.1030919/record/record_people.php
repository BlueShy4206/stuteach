<?php
    //-- 寫入資料庫
      //...................................................................紀錄此單元有多少人做過...1      
      //echo $CsID;
      //die();
      $sql=mysql_query("SELECT `N` FROM `exam_people` 
                        WHERE `cs_id` like '".$CsID."' and `paper_vol`=".$PaperVol."");
      $n_p = 0;      
      $total_N = 0;
      while($row=mysql_fetch_array($sql)) 
      {  
        $exam_people = $row[0];
        $n_p = 1; //此考卷有人考過
      }
      mysql_free_result($sql);
      if ($n_p == 0)
      {      
        $level_num = "";
        for($i=0;$i<17;$i++)
        {        
          $level_num .= "0@XX@";
        }      
        $sql = "INSERT `exam_people` (`cs_id` ,`paper_vol` ,`N` ,`level_num` )
                VALUES ('".$CsID."',".$PaperVol.", '1', '".$level_num."')";          
        mysql_query($sql); 
      }
      else
      {
        $exam_people ++;
        $sql = "UPDATE `exam_people` SET `N` = ".$exam_people." 
                WHERE `cs_id` like '".$CsID."' and `paper_vol`=".$PaperVol."";
        mysql_query($sql);
        //$total_N = $exam_people;
      }    
      //...................................................................紀錄此單元有多少人做過...end
            
      //...................................................................紀錄此單元在能力區間的人數與施測該題人數..2
      $h_exam_A_a = explode("@XX@",$_SESSION["h_exam_A"]);
      $select_item_id_A_a = explode("','",$_SESSION["select_item_id_A"]);
      $item_A = count($h_exam_A_a)-2;  //施測試題總數 減2是一個是最後估計值 一個空格   
         
      $sql=mysql_query("SELECT `level_num` FROM `exam_people` 
                        WHERE `cs_id` like '".$CsID."' and `paper_vol`=".$PaperVol."");
      while($row=mysql_fetch_array($sql)) //叫出上一次所有能力區間的作答人數
      {  
        $level_num = $row[0];
      }
      mysql_free_result($sql);    
      $level_num= explode("@XX@",$level_num);      
        //...................................................................更新能力區間的人數與施測該題人數..2-1
        for($i=0;$i<$item_A;$i++)
        {
          $item_A_a = $select_item_id_A_a[$i];
          if (($h_exam_A_a[$i] >= -3.2) && ($h_exam_A_a[$i] < -3.0))
          {
            $pk_row=0;
            $level_num[$pk_row] = $level_num[$pk_row]+(1/$item_A);
            //叫出上一次試題所有能力區間的作答人數
            $sql=mysql_query("SELECT `A_N` FROM `concept_item_parameter` WHERE `item_sn` = ".$item_A_a."");
            $level_A = "";
            while($row=mysql_fetch_array($sql))
            {  
              $level_A = $row[0];
            }
            mysql_free_result($sql);    
            $level_A_d = explode("@XX@",$level_A);
            $level_A_d[$pk_row] = $level_A_d[$pk_row]+1;
            $level_A_n = "";
            for ($k=0;$k<17;$k++)
            {
              $level_A_n .= $level_A_d[$k]."@XX@";        
            }
            $sql = "UPDATE `concept_item_parameter` SET `A_N` = '".$level_A_n."' WHERE `item_sn` = ".$item_A_a."";
            mysql_query($sql);
            //end            
          }
          elseif (($h_exam_A_a[$i] >= 3.0) && ($h_exam_A_a[$i] <= 3.2))
          {
            $pk_row=16;
            $level_num[$pk_row] = $level_num[$pk_row]+(1/$item_A);
            //叫出上一次試題所有能力區間的作答人數
            $sql=mysql_query("SELECT `A_N` FROM `concept_item_parameter` WHERE `item_sn` = ".$item_A_a."");
            $level_A = "";
            while($row=mysql_fetch_array($sql))
            {  
              $level_A = $row[0];
            }
            mysql_free_result($sql);    
            $level_A_d = explode("@XX@",$level_A);
            $level_A_d[$pk_row] = $level_A_d[$pk_row]+1;
            $level_A_n = "";
            for ($k=0;$k<17;$k++)
            {
              $level_A_n .= $level_A_d[$k]."@XX@";        
            }
            $sql = "UPDATE `concept_item_parameter` SET `A_N` = '".$level_A_n."' WHERE `item_sn` = ".$item_A_a."";
            mysql_query($sql);
            //end
          }
          else
          {
            for ($j=1;$j<16;$j++)
            {
              if( ($h_exam_A_a[$i] >= (($j-1)*0.4-3.0)) && ($h_exam_A_a[$i] < ($j*0.4-3.0)) )   
              {
                $pk_row = $j;
                $level_num[$j] = $level_num[$j]+(1/$item_A);                
                //叫出上一次試題所有能力區間的作答人數
                $sql=mysql_query("SELECT `A_N` FROM `concept_item_parameter` WHERE `item_sn` = ".$item_A_a."");
                $level_A = "";
                while($row=mysql_fetch_array($sql))
                {  
                  $level_A = $row[0];
                }
                mysql_free_result($sql);    
                $level_A_d = explode("@XX@",$level_A);
                $level_A_d[$pk_row] = $level_A_d[$pk_row]+1;
                $level_A_n = "";
                for ($k=0;$k<17;$k++)
                {
                  $level_A_n .= $level_A_d[$k]."@XX@";        
                }
                $sql = "UPDATE `concept_item_parameter` SET `A_N` = '".$level_A_n."' WHERE `item_sn` = ".$item_A_a."";
                mysql_query($sql);
                //end
              }
            }                                      
          }
        }
        //...................................................................紀錄此單元在能力區間的人數與施測該題人數..end
        //...................................................................將更新人數寫入資料庫..3
        for ($pk_row=0;$pk_row<17;$pk_row++)
        {
          $level_num_n .= $level_num[$pk_row]."@XX@";        
        }
        $sql = "UPDATE `exam_people` SET `level_num` = '".$level_num_n."' 
                WHERE `cs_id` like '".$CsID."' and `paper_vol`=".$PaperVol."";
        mysql_query($sql); 
        //...................................................................將更新人數寫入資料庫..end
        //...................................................................紀錄選擇該題在能力區間的人數..4
        $h_exam_S_a = explode("@XX@",$_SESSION["h_exam_S"]);
        $select_item_id_S_a = explode("','",$_SESSION["select_item_id_S"]);
        $item_S = count($h_exam_S_a)-2;  //選擇試題總數 減2是一個是初始值 一個空格
        for($i=0;$i<$item_S;$i++)
        {
          $item_S_a = $select_item_id_S_a[$i];
          if (($h_exam_S_a[$i] >= -3.2) && ($h_exam_S_a[$i] < -3.0))
          {
            $pk_row=0;
            //叫出上一次試題所有能力區間的選擇人數
            $sql=mysql_query("SELECT `S_N` FROM `concept_item_parameter` WHERE `item_sn` = ".$item_S_a."");
            $level_S = "";
            while($row=mysql_fetch_array($sql))
            {  
              $level_S = $row[0];
            }
            mysql_free_result($sql);    
            $level_S_d = explode("@XX@",$level_S);
            $level_S_d[$pk_row] = $level_S_d[$pk_row]+1;
            $level_S_n = "";
            for ($k=0;$k<17;$k++)
            {
              $level_S_n .= $level_S_d[$k]."@XX@";        
            }
            $sql = "UPDATE `concept_item_parameter` SET `S_N` = '".$level_S_n."' WHERE `item_sn` = ".$item_S_a."";
            mysql_query($sql);
            //end            
          }
          elseif (($h_exam_S_a[$i] >= 3.0) && ($h_exam_S_a[$i] <= 3.2))
          {
            $pk_row=16;
            //叫出上一次試題所有能力區間的選擇人數
            $sql=mysql_query("SELECT `S_N` FROM `concept_item_parameter` WHERE `item_sn` = ".$item_S_a."");
            $level_S = "";
            while($row=mysql_fetch_array($sql))
            {  
              $level_S = $row[0];
            }
            mysql_free_result($sql);    
            $level_S_d = explode("@XX@",$level_S);
            $level_S_d[$pk_row] = $level_S_d[$pk_row]+1;
            $level_S_n = "";
            for ($k=0;$k<17;$k++)
            {
              $level_S_n .= $level_S_d[$k]."@XX@";        
            }
            $sql = "UPDATE `concept_item_parameter` SET `S_N` = '".$level_S_n."' WHERE `item_sn` = ".$item_S_a."";
            mysql_query($sql);
            //end 
          }
          else
          {
            for ($j=1;$j<16;$j++)
            {
              if( ($h_exam_S_a[$i] >= (($j-1)*0.4-3.0)) && ($h_exam_S_a[$i] < ($j*0.4-3.0)) )   
              {
                $level_num[$j] = $level_num[$j]+(1/$item_S);
                $pk_row = $j;
                //叫出上一次試題所有能力區間的選擇人數
                $sql=mysql_query("SELECT `S_N` FROM `concept_item_parameter` WHERE `item_sn` = ".$item_S_a."");
                $level_S = "";
                while($row=mysql_fetch_array($sql))
                {  
                  $level_S = $row[0];
                }
                mysql_free_result($sql);    
                $level_S_d = explode("@XX@",$level_S);
                $level_S_d[$pk_row] = $level_S_d[$pk_row]+1;
                $level_S_n = "";
                for ($k=0;$k<17;$k++)
                {
                  $level_S_n .= $level_S_d[$k]."@XX@";        
                }
                $sql = "UPDATE `concept_item_parameter` SET `S_N` = '".$level_S_n."' WHERE `item_sn` = ".$item_S_a."";
                mysql_query($sql);
                //end 
              }
            }                                      
          }
        }
      //...................................................................紀錄選擇該題在能力區間的人數..end      
?>
