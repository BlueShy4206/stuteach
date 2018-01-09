<?php
//...................................................................最大訊息法
	function information($h_exam,$A_item,$B_item,$C_item)
  {
    $D = 1.702;
    $H = $h_exam;
    $B = $B_item;
    $A = $A_item;
    $C = $C_item;
    
    $L = pow($A,2);
    $I = pow((1+exp(-$A*($H-$B))),2);
    $information = ($L*(1-$C))/(($C+exp($A*($H-$B)))*$I);
    return $information;
  }
  //...................................................................曝光率控制
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
    $EP = 0;    
    $ran_num = (rand(0,10000)/10000); //( rand(0,10000)/10000) 是含有4位小數的隨機亂數
    //...................................................................end
    while($EP <= $ran_num)
    {
      $ran_num = (rand(0,10000)/10000);
      //$cs_id_7=substr($CsID, 0, 7);
      $sql=mysql_query("SELECT `item_sn`,`a`,`b`,`c` FROM `concept_item_parameter`
                        WHERE `item_sn` not in ('".$select_item_id_S."') 
                        and `cs_id` like '".$CsID."'");
      $max_information = 0;      
      while($row=mysql_fetch_array($sql))
      {  
        $A_item = $row[1];
        $B_item = $row[2];
        $C_item = $row[3];  
        
        if ($A_item!=0)
        {
          $information = information($h_exam,$A_item,$B_item,$C_item);
          $information = round($information,4);     
          if ($information>$max_information)
          { //............................................................第一題的流水號
            $max_information = $information;
            $item_sn = $row[0];
            $P_a = $A_item; 
            $P_b = $B_item;
            $P_c = $C_item;
            
          }                                      
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
      
      $_SESSION["select_item_id_S"].= $item_sn."','";	//字串(所有被選擇的試題id組成)
      $select_item_id_S = $_SESSION["select_item_id_S"];
      $_SESSION["h_exam_S"] .= $h_exam._SPLIT_SYMBOL;
    }
  //...................................................................曝光率控制end
  $_SESSION["select_item_id_A"].= $item_sn."','";	//字串(所有被施測的試題id組成)
  $select_item_id_A = $_SESSION["select_item_id_A"]; 
  //...................................................................最大訊息法end
?>
