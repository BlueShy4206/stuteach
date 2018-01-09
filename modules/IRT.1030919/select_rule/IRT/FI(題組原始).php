<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
  $select_item_id_S = $_SESSION["select_item_id_S"];      
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
        $item_sn_t = $row[0];           
      }                                      
    }  
  }
  mysql_free_result($sql);   
  
    $sql=mysql_query("SELECT testlet_num FROM concept_item_testlet WHERE `item_sn` = $item_sn_t ");
    while($row=mysql_fetch_array($sql))
	    {  
	      $testlet = $row[0]; 
	    }
    mysql_free_result($sql);
    $n=0;
	if(isset($testlet)) 
		{
		 $sql=mysql_query("SELECT item_sn FROM concept_item_testlet WHERE testlet_num = '$testlet' order by testlet_sub_num ");
    	 while($row=mysql_fetch_array($sql))
  			{ 
			 $n++; 
      	 	 $testlet_item_sn[$n] = $row['item_sn'];
      	 	 $_SESSION["select_item_id_S"].= $row['item_sn']."','";
      	 	 $_SESSION["select_item_id_A"].= $row['item_sn']."','";
      	 	  //debug_msg("第".__LINE__."行 data ", $testlet_item_sn);
    		}
   		 	 mysql_free_result($sql);
				 	
//   		debug_msg("第".__LINE__."行 data ", $testlet_item_sn);
			 $_SESSION["h_exam_S"] .= $h_exam._SPLIT_SYMBOL;
  			 $select_item_id_S = $_SESSION["select_item_id_S"];    
  //...................................................................曝光率控制end
    		 $select_item_id_A = $_SESSION["select_item_id_A"]; 
  //...................................................................最大訊息法end
		}
	else
		{
		 $n++;

		 $testlet_item_sn[$n]= $item_sn_t;
		 $_SESSION["h_exam_S"] .= $h_exam._SPLIT_SYMBOL;
  		 $_SESSION["select_item_id_S"].= $testlet_item_sn[$n]."','";	//字串(所有被選擇的試題id組成)  
  		 $select_item_id_S = $_SESSION["select_item_id_S"];
    
  //...................................................................曝光率控制end
  		 $_SESSION["select_item_id_A"].= $testlet_item_sn[$n]."','";	//字串(所有被施測的試題id組成)
  	 	 $select_item_id_A = $_SESSION["select_item_id_A"]; 
  //...................................................................最大訊息法end
		}
  
  	$tmp=$tmp+$n;
  	$select_num=$select_num+$n;
	    
  
                  
?>
