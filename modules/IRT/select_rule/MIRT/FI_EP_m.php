<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
  //...................................................................曝光率控制
    //...................................................................找出本次能力估計在哪一個區間
    for($i=0;$i<$MIRT_d;$i++)
    {
      $level_EP[$i] = 0;
      if (($h_exam_tmp[$i] >= -3.2) && ($h_exam_tmp[$i] < -3.0))
      {
        $level_EP[$i]=0;
      }
      elseif (($h_exam_tmp[$i] >= 3.0) && ($h_exam_tmp[$i] <= 3.2))
      {
        $level_EP[$i]=16;
      }
      else
      {
        for ($j=1;$j<16;$j++)
        {
        if( ($h_exam_tmp[$i] >= (($j-1)*0.4-3.0)) && ($h_exam_tmp[$i] < ($j*0.4-3.0)) )   
          {
            $level_EP[$i] = $j;
          }
        }                                      
      }      
    }
    
    for($i=0;$i<$MIRT_d;$i++)
    {            
      $inf_sum[$i] = 0; 
    }
    for($i=0;$i<($select_num-1);$i++)
    {                     
        $b_theta_tmp = $b_theta_e[$i]-1;
        $ans_sign[$b_theta_tmp] = $X_item[$i];//紀錄每個項度最後一次的答題狀況
        $P = P($D,$B_item[$i],$h_exam_tmp[$b_theta_tmp]);      
        $inf_sum_tmp[$b_theta_tmp] = $P*(1-$P);
        $inf_sum[$b_theta_tmp] = $inf_sum[$b_theta_tmp]+$inf_sum_tmp[$b_theta_tmp]; 
    }  
    
    $EP = 0;    
    $ran_num = (rand(0,10000)/10000); //( rand(0,10000)/10000) 是含有4位小數的隨機亂數
    //...................................................................end
    while($EP <= $ran_num)
    {
      for($i=0;$i<$MIRT_d;$i++)
      {                 
        $max_information[$i] = 0;      
        $det_inf[$i] = 1; //行列式的值
      }
      $ran_num = (rand(0,10000)/10000);
      //...................................................................MLE的能力估計...end        
      //...................................................................MIRT的選題法    
      //  紀錄各項度二階微分的和
         
      //  紀錄各項度二階微分的和...end          
      $sql=mysql_query("SELECT `item_sn`,`b`,`dim` FROM `concept_item_parameter`
                        WHERE `item_sn` not in ('".$select_item_id_S."') 
                        and `cs_id` like '".$CsID."'");     
      while($row=mysql_fetch_array($sql))
      {          
        $B_item_tmp = $row[1];
        $b_theta_tmp = $row[2]-1;        
        if ($b_theta_tmp>=0)
        {
          $P = P($D,$B_item_tmp,$h_exam_tmp[$b_theta_tmp]);
          $information[$b_theta_tmp] = $inf_sum[$b_theta_tmp]+($P*(1-$P));
          $information[$b_theta_tmp] = round($information[$b_theta_tmp],4);  
          if ($information[$b_theta_tmp]>$max_information[$b_theta_tmp])
          { 
            $max_information[$b_theta_tmp] = $information[$b_theta_tmp];
            $item_sn_tmp[$b_theta_tmp] = $row[0];                 
          }                                        
        }
      }
      mysql_free_result($sql);
      //  計算行列式的值    
      $det_max=0;      
      for($i=0;$i<$MIRT_d;$i++)
      {              
        for($j=0;$j<$MIRT_d;$j++)
        {      
          $inf_sum_tmp1[$j]=$inf_sum[$j];
          if($j == $i)
          {
            $inf_sum_tmp1[$j]=$max_information[$j];
          }           
          $det_inf[$i] = $det_inf[$i]*$inf_sum_tmp1[$j];
        }
        if($det_inf[$i]>$det_max)//找出使行列式值最大的題目
        {
          $det_max = $det_inf[$i];
          $det_max_d = $i; 
        }        
      }
      
      $item_sn = $item_sn_tmp[$det_max_d];        
      $_SESSION["h_exam_S"].=$h_exam_tmp[$det_max_d]._SPLIT_SYMBOL;
      $_SESSION["select_item_id_S"] .= $item_sn."','";;
      $select_item_id_S = $_SESSION["select_item_id_S"];
      
      //...................................................................最大訊息法end
  //...................................................................end                 
      
      $sql=mysql_query("SELECT `EP` FROM `concept_item_parameter` WHERE `item_sn` = ".$item_sn."");
      while($row=mysql_fetch_array($sql))
      {
        $EP_o = $row[0];        
      }      
      mysql_free_result($sql);
      $EP_d = explode("@XX@",$EP_o);
      $EP = $EP_d[$level_EP[$det_max_d]];      
    }       
    $P_b_theta = $det_max_d+1;
    $_SESSION["select_item_b_theta"] .= $P_b_theta._SPLIT_SYMBOL;     
    $_SESSION["select_item_id_A"].= $item_sn."','";	//字串(所有被施測的試題id組成)
    $select_item_id_A = $_SESSION["select_item_id_A"];     
?>
