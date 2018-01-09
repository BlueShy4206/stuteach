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
  
  $select_item_sub_S = array(2,1,4,3,6,5,9,2,3);
  //...................................................................曝光率控制
  //...................................................................找出本次能力估計在哪一個區間    
  $select_item_id_S = $_SESSION["select_item_id_S"];
  $select_item_sub_A = $_SESSION["select_item_sub_A"];
  $Sub_items = explode("@XX@",$select_item_sub_A);       //紀錄各指標做過題數
  //$cs_id_7=substr($CsID, 0, 7);
  
  if($select_num < 24)
  	{  
	  $sql=mysql_query("SELECT concept_item_parameter.item_sn,concept_item_parameter.a,concept_item_parameter.b,concept_item_parameter.c,
	  					concept_item_parameter.sub FROM concept_item_parameter,concept_item
	                    WHERE concept_item.item_sn not in ('".$select_item_id_S."') 
	                    and concept_item.exam_paper_id like '".$ep_id."' and concept_item.item_sn=concept_item_parameter.item_sn 
						and ( concept_item_parameter.sub!=8 and concept_item_parameter.sub !=7 )");
    }
  elseif( $select_num>=24 && $select_num < 28 )
  	{
	  $sqll=mysql_query("SELECT item_sn FROM concept_item_testlet WHERE exam_paper_id = '".$ep_id."' ");
	  while($roww=mysql_fetch_array($sqll))
		    {  
		      $select_testletitem_id_S .= $roww['item_sn']."','";; 
		    }
	  $sql=mysql_query("SELECT item_sn,a,b,c,sub FROM concept_item_parameter
	                    WHERE item_sn in ('".$select_testletitem_id_S."') 
	                    and cs_id like '".$CsID."'");

	}
  else
  	{
	  $sqll=mysql_query("SELECT item_sn FROM concept_item_testlet WHERE exam_paper_id = '".$ep_id."' ");
	  while($roww=mysql_fetch_array($sqll))
		    {  
		      $select_testletitem_id_S .= $roww['item_sn']."','";; 
		    }
	  $sql=mysql_query("SELECT concept_item_parameter.item_sn,concept_item_parameter.a,concept_item_parameter.b,concept_item_parameter.c,
	  					concept_item_parameter.sub FROM concept_item_parameter,concept_item
	                    WHERE concept_item.item_sn not in ('".$select_item_id_S."')
	                    and concept_item.item_sn not in ('".$select_testletitem_id_S."')
	                    and concept_item.exam_paper_id like '".$ep_id."' and concept_item.item_sn=concept_item_parameter.item_sn
	                    and ( concept_item_parameter.sub =8 or concept_item_parameter.sub =7 )");				  
	}
  $max_information = 0;      
  while($row=mysql_fetch_array($sql))
  {  
    $A_item = $row[1];
    $B_item = $row[2];
    $C_item = $row[3];  
    $item_sub = $row[4];
	    
    if ($A_item!=0 && $Sub_items[$item_sub-1] < $select_item_sub_S[$item_sub-1] )
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
		 $sql=mysql_query("SELECT item_sn FROM concept_item_testlet WHERE testlet_num = '$testlet' and exam_paper_id = '".$ep_id."' order by testlet_sub_num ");
    	 while($row=mysql_fetch_array($sql))
  			{ 
			 $n++; 
      	 	 $testlet_item_sn[$n] = $row['item_sn'];
      	 	 $_SESSION["select_item_id_S"].= $row['item_sn']."','";
      	 	 $_SESSION["select_item_id_A"].= $row['item_sn']."','";
      	 	  //debug_msg("第".__LINE__."行 data ", $testlet_item_sn);
    		}
    	 mysql_free_result($sql);
    	 for($i=1;$i<=$n;$i++)
    	 	{
			 $sql=mysql_query("SELECT sub FROM concept_item_parameter WHERE item_sn = '$testlet_item_sn[$i]' ");
	    	 while($row=mysql_fetch_array($sql))
	  			{ 
				 $Sub_items[$row['sub']-1]++;
	      	 	  //debug_msg("第".__LINE__."行 data ", $testlet_item_sn);
	    		}			  
			}
		 for($i=0;$i<9;$i++)
		 	{
			 $aaa.= $Sub_items[$i]._SPLIT_SYMBOL;
			}		 	
//   		debug_msg("第".__LINE__."行 data ", $testlet_item_sn);
			 $_SESSION["h_exam_S"] .= $h_exam._SPLIT_SYMBOL;
			 $_SESSION["select_item_sub_A"]=$aaa;
			 $select_item_sub_A =$_SESSION["select_item_sub_A"];
  			 $select_item_id_S = $_SESSION["select_item_id_S"];    
  //...................................................................曝光率控制end
    		 $select_item_id_A = $_SESSION["select_item_id_A"]; 
  //...................................................................最大訊息法end
		}
	else
		{
		 $n++;

		 $testlet_item_sn[$n]= $item_sn_t;
    	 for($i=1;$i<=$n;$i++)
    	 	{
			 $sql=mysql_query("SELECT sub FROM concept_item_parameter WHERE item_sn = '$testlet_item_sn[$i]' ");
	    	 while($row=mysql_fetch_array($sql))
	  			{ 
				 $Sub_items[$row['sub']-1]++;
	      	 	  //debug_msg("第".__LINE__."行 data ", $testlet_item_sn);
	    		}			  
			}
		 for($i=0;$i<9;$i++)
		 	{
			 $aaa.= $Sub_items[$i]._SPLIT_SYMBOL;
			}
					
		 $_SESSION["h_exam_S"] .= $h_exam._SPLIT_SYMBOL;
		 $_SESSION["select_item_sub_A"]=$aaa;
		 $select_item_sub_A =$_SESSION["select_item_sub_A"];
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
