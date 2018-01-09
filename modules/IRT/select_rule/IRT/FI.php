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

	$sqlstr="SELECT sub_test_num FROM exam_paper_subscale WHERE exam_paper_id='".$ep_id."' ";
	$sql= mysql_query($sqlstr);
	//debug_msg("第".__LINE__."行 sqlstr ", $sqlstr);
	while($row=mysql_fetch_array($sql))
	{
  		 $select_item_sub_S=$row[0];
	}
	$select_item_sub_S = explode("@XX@",$select_item_sub_S);  //各子測驗總出題數
	$ch_testlet_1='';
	$test_temp_testlet=0;
	$sqlstr="SELECT distinct concept_item_parameter.sub FROM concept_item_parameter,concept_item_testlet 
		 WHERE concept_item_parameter.item_sn=concept_item_testlet.item_sn and concept_item_testlet.exam_paper_id='".$ep_id."' ";
	//debug_msg("第".__LINE__."行 sqlstr ", $sqlstr);
	$sql= mysql_query($sqlstr);   //找出有題組試題之子測驗
	while($row=mysql_fetch_array($sql))
	{
		//debug_msg("第".__LINE__."行 ch_testlet_1 ", $ch_testlet_1);
		if ($ch_testlet_1==null)
		{
			$ch_testlet_1 = $ch_testlet_1.'concept_item_parameter.sub!='.$row[0];
			$ch_testlet_2 = $ch_testlet_2.'concept_item_parameter.sub='.$row[0];
			$test_temp_testlet=$test_temp_testlet+$select_item_sub_S[$row[0]-1]; 
		}else{
			$ch_testlet_1 = $ch_testlet_1.' and concept_item_parameter.sub!='.$row[0];
			$ch_testlet_2 = $ch_testlet_2.' or concept_item_parameter.sub='.$row[0];
			$test_temp_testlet=$test_temp_testlet+$select_item_sub_S[$row[0]-1];  
		}
		//debug_msg("第".__LINE__."行 ch_testlet_1 ", $ch_testlet_1);  
	}
	$test_temp_no_testlet=array_sum($select_item_sub_S)-$test_temp_testlet;	//$test_temp_testlet 有題組試題之子測驗總出題數

	//...................................................................曝光率控制
	//...................................................................找出本次能力估計在哪一個區間    
	$select_item_id_S = $_SESSION["select_item_id_S"];
	$select_item_sub_A = $_SESSION["select_item_sub_A"];
	$item_length_done = $_SESSION["item_length_done"];  //已做過題組數
	$Sub_items = explode("@XX@",$select_item_sub_A);       //紀錄各指標做過題數
	//debug_msg("第".__LINE__."行 select_item_id_S ", $select_item_id_S);
	//debug_msg("第".__LINE__."行 test_temp_no_testlet ", $test_temp_no_testlet);

	//debug_msg("第".__LINE__."行 select_num ", $select_num);
	if($select_num < $test_temp_no_testlet)
  	{  
		$sqlstr="SELECT concept_item_parameter.item_sn,concept_item_parameter.a,concept_item_parameter.b,concept_item_parameter.c,
	  					concept_item_parameter.sub FROM concept_item_parameter,concept_item
	                    WHERE concept_item.exam_paper_id like '".$ep_id."' and concept_item.item_sn=concept_item_parameter.item_sn 
						";
		if($ch_testlet_1!=''){
			$sqlstr.=" and ( ".$ch_testlet_1." )";
		}
		if($select_item_id_S!=''){
			$sqlstr.=" and concept_item.item_sn not in ('".$select_item_id_S."')                     ";
		}
		//debug_msg("第".__LINE__."行 sqlstr ", $sqlstr);
		$sql=mysql_query($sqlstr);
	}
	elseif( $select_num>=$test_temp_no_testlet && $item_length_done < $item_length )
  	{
  		if($select_item_id_S==''){
  			$sqlstr="SELECT item_sn FROM concept_item_testlet WHERE exam_paper_id = '".$ep_id."' ";
  		}else{
  			$sqlstr="SELECT item_sn FROM concept_item_testlet WHERE exam_paper_id = '".$ep_id."' and item_sn not in ('".$select_item_id_S."') ";
  		}
		//debug_msg("第".__LINE__."行 sqlstr ", $sqlstr);
		$sqll=mysql_query($sqlstr);
		while($roww=mysql_fetch_array($sqll))
		{
			//debug_msg("第".__LINE__."行 roww ", $roww);
			$testlet_item_sn=$roww['item_sn'];
			$select_testletitem_id_S .= $roww['item_sn']."','";; 
		}
		//$select_testletitem_id_S =implode("','", $testlet_item_sn);
		//debug_msg("第".__LINE__."行 select_testletitem_id_S ", $select_testletitem_id_S);
		$sqlstr="SELECT item_sn,a,b,c,sub FROM concept_item_parameter
	                    WHERE item_sn in ('".$select_testletitem_id_S."') 
	                    and cs_id LIKE '".$CsID."'";
	    //debug_msg("第".__LINE__."行 sqlstr ", $sqlstr);
		$sql=mysql_query($sqlstr);

	}else{
		$sqlstr="SELECT item_sn FROM concept_item_testlet WHERE exam_paper_id = '".$ep_id."' ";
		$sqll=mysql_query($sqlstr);
		while($roww=mysql_fetch_array($sqll))
		{  
			$select_testletitem_id_S .= $roww['item_sn']."','";
		}
		$sqlstr="SELECT concept_item_parameter.item_sn,concept_item_parameter.a,concept_item_parameter.b,concept_item_parameter.c,
	  					concept_item_parameter.sub FROM concept_item_parameter,concept_item
	                    WHERE concept_item.item_sn not in ('".$select_item_id_S."')
	                    and concept_item.item_sn not in ('".$select_testletitem_id_S."')
	                    and concept_item.exam_paper_id like '".$ep_id."' and concept_item.item_sn=concept_item_parameter.item_sn
	                    and ( ".$ch_testlet_2." )";
	    //debug_msg("第".__LINE__."行 sqlstr ", $sqlstr);
		$sql=mysql_query($sqlstr);				  
	}
	$max_information = 0; 

	//debug_msg("第".__LINE__."行 Sub_items ", $Sub_items);
	//debug_msg("第".__LINE__."行 select_item_sub_S ", $select_item_sub_S);     
	while($row=mysql_fetch_array($sql))
	{  
		$A_item = $row[1];
		$B_item = $row[2];
		$C_item = $row[3];  
		$item_sub = $row[4];
	    ////debug_msg("第".__LINE__."行 row ", $row);
		////debug_msg("第".__LINE__."行 A_item ", $A_item);
    	if ($A_item!=0 && ($Sub_items[$item_sub-1] < $select_item_sub_S[$item_sub-1]) ){
			$information = information($h_exam,$A_item,$B_item,$C_item);
			$information = round($information,5);  
			$info_test[$row[0]]=$information;
			if ($information>$max_information){ //第一題的流水號
        		$max_information = $information;
        		$item_sn_t = $row[0];       
			}                                      
		}  
	}
	//debug_msg("第".__LINE__."行 item_sn_t ", $item_sn_t);
	//debug_msg("第".__LINE__."行 info_test ", $info_test);

	mysql_free_result($sql);   
	$sqlstr="SELECT testlet_num FROM concept_item_testlet WHERE `item_sn` = $item_sn_t ";
    $sql=mysql_query($sqlstr);
    while($row=mysql_fetch_array($sql))
	{  
		$testlet = $row[0]; 
	}
    mysql_free_result($sql);
    $n=0;
	if(isset($testlet)) {
		 $sql=mysql_query("SELECT item_sn FROM concept_item_testlet WHERE testlet_num = '$testlet' and exam_paper_id = '".$ep_id."' order by testlet_sub_num ");
    	 while($row=mysql_fetch_array($sql))
  			{ 
			 $n++; 
      	 	 $testlet_item_sn[$n] = $row['item_sn'];
      	 	 $_SESSION["select_item_id_S"].= $row['item_sn']."','";
      	 	 $_SESSION["select_item_id_A"].= $row['item_sn']."','";
      	 	 //debug_msg("第".__LINE__."行 testlet_item_sn ", $testlet_item_sn);
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
		 for($i=0;$i<sizeof($select_item_sub_S);$i++)
		 	{
			 $aaa.= $Sub_items[$i]._SPLIT_SYMBOL;
			}
//   		//debug_msg("第".__LINE__."行 data ", $testlet_item_sn);
			 $_SESSION["item_length_done"] = $item_length_done+1;
			 $_SESSION["h_exam_S"] .= $h_exam._SPLIT_SYMBOL;
			 $_SESSION["select_item_sub_A"]=$aaa;
			 $select_item_sub_A =$_SESSION["select_item_sub_A"];
  			 $select_item_id_S = $_SESSION["select_item_id_S"];    
  //...................................................................曝光率控制end
    		 $select_item_id_A = $_SESSION["select_item_id_A"]; 
  //...................................................................最大訊息法end
	}else{
		 $n++;

		 $testlet_item_sn[$n]= $item_sn_t;
    	 for($i=1;$i<=$n;$i++)
    	 	{
			 $sql=mysql_query("SELECT sub FROM concept_item_parameter WHERE item_sn = '$testlet_item_sn[$i]' ");
	    	 while($row=mysql_fetch_array($sql))
	  			{ 
				 $Sub_items[$row['sub']-1]++;
	      	 	  //debug_msg("第".__LINE__."行 testlet_item_sn ", $testlet_item_sn);
	    		}			  
			}
		 for($i=0;$i<sizeof($select_item_sub_S);$i++)
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
	$_SESSION["select_num_temp"]=$n;    
  
                  
?>
