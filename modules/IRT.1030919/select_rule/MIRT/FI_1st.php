<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
//...................................................................利用最接近難度法 決定 初始試題
    //$item_s = array(1,2,3,4); //設定初始的維度順序 最好做成亂數
    for($d=0;$d<$MIRT_d;$d++)
    {
      //$ran_num = (rand(0,10000)/10000);
      $tmp_d = $d+1;
      $cs_id_7=substr($CsID, 0, 7);
      $sql=mysql_query("SELECT `item_sn`,`b`,`dim` FROM `concept_item_parameter`
                        WHERE `item_sn` not in ('".$select_item_id_S."') 
                        and `cs_id` like '".$cs_id_7."%' and `dim` like '".$tmp_d."'");                        
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
      $_SESSION["select_item_id_A"].= $item_sn."','";	//字串(所有被選擇的試題id組成)
      $_SESSION["select_item_b_theta"].= $b_theta._SPLIT_SYMBOL;
      $_SESSION["select_item_id_S"].= $item_sn."','";	//字串(所有被選擇的試題id組成) 
      $_SESSION["h_exam_S"] .= $h_exam._SPLIT_SYMBOL;
    } 
    
    $select_item_id_S = $_SESSION["select_item_id_S"];
    $_SESSION["select_item_id_A"]= $select_item_id_S;	//字串(所有被施測的試題id組成)
    $select_item_id_A = $_SESSION["select_item_id_A"]; 
    //...................................................................end
?>
