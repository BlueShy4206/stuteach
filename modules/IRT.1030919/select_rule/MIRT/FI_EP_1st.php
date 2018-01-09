<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
  //...................................................................曝光率控制
  for($d=0;$d<$MIRT_d;$d++)
  {
    //...................................................................找出本次能力估計在哪一個區間
    if (($h_exam >= -3.2) && ($h_exam < -3.0))
    {
      $level_EP=0;
    }
    elseif (($h_exam >= 3.0) && ($h_exam <= 3.2))
    {
      $level_EP=16;
    }
    else
    {
      for ($j=1;$j<16;$j++)
      {
      if( ($h_exam >= (($j-1)*0.4-3.0)) && ($h_exam < ($j*0.4-3.0)) )   
        {
          $level_EP = $j;
        }
      }                                      
    }
    //echo $level_EP.'<br>';
    $EP = 0;    
    $ran_num = (rand(0,10000)/10000); //( rand(0,10000)/10000) 是含有4位小數的隨機亂數
    //...................................................................end
    while($EP <= $ran_num)
    {
      $ran_num = (rand(0,10000)/10000);
      $tmp_d = $d+1;
      $cs_id_7=substr($CsID, 0, 7);
      $sql=mysql_query("SELECT `item_sn`,`b`,`dim` FROM `concept_item_parameter`
                        WHERE `item_sn` not in ('".$select_item_id_S."') 
                        and `cs_id` like '".$CsID."' and `dim` like '".$tmp_d."'");                        
      $min_b = 99;      
      while($row=mysql_fetch_array($sql))
      {  
        $B_item = $row[1];        
        if ( ($row[2]!=0) && (abs($B_item)<$min_b) )
        {
          $min_b = abs($B_item);
          $item_sn = $row[0];
          $b_theta = $row[2];
          $P_b = $B_item;                                               
        }  
      }
      mysql_free_result($sql);                 
      
      $sql=mysql_query("SELECT `EP` FROM `concept_item_parameter` WHERE `item_sn` = ".$item_sn."");
      while($row=mysql_fetch_array($sql))
      {
        $EP_o = $row[0];
        
      }      
      mysql_free_result($sql);
      $EP_d = explode("@XX@",$EP_o);
      $EP = $EP_d[$level_EP];
      //echo $ran_num.'_';
      //echo $EP.'_';
      //echo $tmp_d.'_'.$EP.'_';
      $_SESSION["h_exam_S"] .= $h_exam._SPLIT_SYMBOL;
      $_SESSION["select_item_id_S"].= $item_sn."','";	//字串(所有被選擇的試題id組成) 
      $select_item_id_S = $_SESSION["select_item_id_S"];         
    }
    //echo '<br>';   
    //...................................................................曝光率控制end
    $_SESSION["select_item_id_A"].= $item_sn."','";	//字串(所有被施測的試題id組成)  
    $_SESSION["select_item_b_theta"].= $b_theta._SPLIT_SYMBOL; 
    //...................................................................最大訊息法end
  }
  
  
  $select_item_id_A = $_SESSION["select_item_id_A"]; 
?>
