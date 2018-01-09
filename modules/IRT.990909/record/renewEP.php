<?php
  if($num_e==0){}        
  else
  {      
    //...................................................................更新試題曝光參數...5   
    switch ($type_id_e)
    {
      case 2: // 2 ASHCO
        {
         if(($total_N%$num_e)==0) 
          {           
            for ($i=0;$i<17;$i++)
            {
              $level_N[$i] = 0; //先將各能力區間的人數歸零
              $level_EP_tmp[$i] = 0; //先暫存各能力區間的曝光參數歸零
            }      
            $total_N = 0; //先將總人數歸零
            $sql=mysql_query("SELECT `N`, `level_num` FROM `exam_people` 
                              WHERE `cs_id` like '".$CsID."%' and `paper_vol`=".$PaperVol."");           
            while($row=mysql_fetch_array($sql)) 
            {  
              $exam_people = $row[0];
              $total_N = $total_N+$exam_people; 
              $level_num = explode("@XX@",$row[1]);
              for ($i=0;$i<17;$i++)
              {
                $level_N[$i] = $level_N[$i]+$level_num[$i];
              }        
            }
            mysql_free_result($sql);     
      
            // num_e 更新曝光參數的人數
            $Rmax = 0.2;
            $sql=mysql_query("SELECT `item_sn` FROM `concept_item_parameter` WHERE `cs_id` like '".$CsID."%'");
            while($row=mysql_fetch_array($sql)) 
            {
              $item_sn_up[] = $row[0];
            }
            mysql_free_result($sql);
        
            for ($j=0;$j<count($item_sn_up);$j++)
            {
              $item_sn_n = $item_sn_up[$j];
              $sql=mysql_query("SELECT `item_sn`, `S_N`, `A_N` FROM `concept_item_parameter` 
                            WHERE `item_sn` = ".$item_sn_n."");
              while($row=mysql_fetch_array($sql)) 
              {            
                $level_S_N = explode("@XX@",$row[1]);
                $level_A_N = explode("@XX@",$row[2]);
              }
              mysql_free_result($sql);
                 
              $EP_n = "";
              for($i=0;$i<17;$i++)
              {
                //...................................................................計算各能力值在各試題被選取的機率
                if ( $level_N[$i]==0)
                {
                  $level_P_S[$i] = 0;
                }
                else
                {
                  $level_P_S[$i] = $level_S_N[$i]/$level_N[$i];
                  $level_P_S[$i] = round($level_P_S[$i],4);
                  if( $level_P_S[$i]>1 )
                  {
                    $level_P_S[$i] = 1;
                  }
                }
                //...................................................................計算各能力值在各試題被選取的機率..end
                //...................................................................計算各能力值在各被施測試題的機率
                if ( $level_N[$i]==0)
                {
                  $level_P_A[$i] = 0;
                }
                else
                {
                  $level_P_A[$i] = $level_A_N[$i]/$level_N[$i];
                  $level_P_A[$i] = round($level_P_A[$i],4);
                  if( $level_P_A[$i]>1 )
                  {
                    $level_P_A[$i] = 1;
                  }
                }
                //...................................................................計算各能力值在各被施測試題的機率..end                                              
                if( $level_P_S[$i]>$Rmax )
                {
                    $level_EP_tmp[$i] = $Rmax/$level_P_S[$i];
                }
                  else
                {
                    $level_EP_tmp[$i] = 1;
                }                           
                $EP_n .= $level_EP_tmp[$i]."@XX@";
              }
              $sql ="UPDATE `concept_item_parameter` SET `EP` = '".$EP_n."' WHERE `item_sn` = ".$item_sn_n."";
              mysql_query($sql);
            }        
          }
      }          
      break;
      case 3: // 3 SHCOF
        {
         if(($total_N%$num_e)==0) 
          {           
            for ($i=0;$i<17;$i++)
            {
              $level_N[$i] = 0; //先將各能力區間的人數歸零
              $level_EP_tmp[$i] = 0; //先暫存各能力區間的曝光參數歸零
            }      
            $total_N = 0; //先將總人數歸零
            $sql=mysql_query("SELECT `N`, `level_num` FROM `exam_people` 
                              WHERE `cs_id` like '".$CsID."%' and `paper_vol`=".$PaperVol."");           
            while($row=mysql_fetch_array($sql)) 
            {  
              $exam_people = $row[0];
              $total_N = $total_N+$exam_people; 
              $level_num = explode("@XX@",$row[1]);
              for ($i=0;$i<17;$i++)
              {
                $level_N[$i] = $level_N[$i]+$level_num[$i];
              }        
            }
            mysql_free_result($sql);     
      
            // num_e 更新曝光參數的人數
            $Rmax = 0.2;
            $sql=mysql_query("SELECT `item_sn` FROM `concept_item_parameter` WHERE `cs_id` like '".$CsID."%'");
            while($row=mysql_fetch_array($sql)) 
            {
              $item_sn_up[] = $row[0];
            }
            mysql_free_result($sql);
        
            for ($j=0;$j<count($item_sn_up);$j++)
            {
              $item_sn_n = $item_sn_up[$j];
              $sql=mysql_query("SELECT `item_sn`, `S_N`, `A_N` FROM `concept_item_parameter` 
                            WHERE `item_sn` = ".$item_sn_n."");
              while($row=mysql_fetch_array($sql)) 
              {            
                $level_S_N = explode("@XX@",$row[1]);
                $level_A_N = explode("@XX@",$row[2]);
              }
              mysql_free_result($sql);
                   
              $EP_n = "";
              for($i=0;$i<17;$i++)
              {
                //...................................................................計算各能力值在各試題被選取的機率
                if ( $level_N[$i]==0)
                {
                  $level_P_S[$i] = 0;
                }
                else
                {
                  $level_P_S[$i] = $level_S_N[$i]/$level_N[$i];
                  $level_P_S[$i] = round($level_P_S[$i],4);
                  if( $level_P_S[$i]>1 )
                  {
                    $level_P_S[$i] = 1;
                  }
                }
                //...................................................................計算各能力值在各試題被選取的機率..end
                //...................................................................計算各能力值在各被施測試題的機率
                if ( $level_N[$i]==0)
                {
                  $level_P_A[$i] = 0;
                }
                else
                {
                  $level_P_A[$i] = $level_A_N[$i]/$level_N[$i];
                  $level_P_A[$i] = round($level_P_A[$i],4);
                  if( $level_P_A[$i]>1 )
                  {
                    $level_P_A[$i] = 1;
                  }
                }
                //...................................................................計算各能力值在各被施測試題的機率..end                                              
                if( $level_P_A[$i]>$Rmax )
                {
                  $level_EP_tmp[$i] = 0;
                }
                else
                {
                  if( $level_P_S[$i]>$Rmax )
                  {
                    $level_EP_tmp[$i] = $Rmax/$level_P_S[$i];
                  }
                  else
                  {
                    $level_EP_tmp[$i] = 1;
                  }
                }              
                $EP_n .= $level_EP_tmp[$i]."@XX@";
              }
              $sql ="UPDATE `concept_item_parameter` SET `EP` = '".$EP_n."' WHERE `item_sn` = ".$item_sn_n."";
              mysql_query($sql);
            }        
          }
        }          
      break;
      case 4: // 4 ASHCO
        {
         if(($total_N%$num_e)==0) 
          {           
            for ($i=0;$i<17;$i++)
            {
              $level_N[$i] = 0; //先將各能力區間的人數歸零
              $level_EP_tmp[$i] = 0; //先暫存各能力區間的曝光參數歸零
            }      
            $total_N = 0; //先將總人數歸零
            $sql=mysql_query("SELECT `N`, `level_num` FROM `exam_people` 
                              WHERE `cs_id` like '".$CsID."%' and `paper_vol`=".$PaperVol."");           
            while($row=mysql_fetch_array($sql)) 
            {  
              $exam_people = $row[0];
              $total_N = $total_N+$exam_people; 
              $level_num = explode("@XX@",$row[1]);
              for ($i=0;$i<17;$i++)
              {
                $level_N[$i] = $level_N[$i]+$level_num[$i];
              }        
            }
            mysql_free_result($sql);    
            // num_e 更新曝光參數的人數
            //  $Rmax 隨著不同能力區間的人數 會有的控制
            for ($i=0;$i<17;$i++)
            {              
              
              $Rmax[$i] = 1-sqrt(0.9*$level_N[$i]/$total_N);
              $Rmax[$i] = round($Rmax[$i],4);
              if($Rmax[$i]<0.2)
              {
                $Rmax[$i]=0.2;
              }             
            } 
            $sql=mysql_query("SELECT `item_sn` FROM `concept_item_parameter` WHERE `cs_id` like '".$CsID."%'");
            while($row=mysql_fetch_array($sql)) 
            {
              $item_sn_up[] = $row[0];
            }
            mysql_free_result($sql);
        
            for ($j=0;$j<count($item_sn_up);$j++)
            {
              $item_sn_n = $item_sn_up[$j];
              $sql=mysql_query("SELECT `item_sn`, `S_N`, `A_N` FROM `concept_item_parameter` 
                            WHERE `item_sn` = ".$item_sn_n."");
              while($row=mysql_fetch_array($sql)) 
              {            
                $level_S_N = explode("@XX@",$row[1]);
                $level_A_N = explode("@XX@",$row[2]);
              }
              mysql_free_result($sql);
                   
              $EP_n = "";
              for($i=0;$i<17;$i++)
              {
                //...................................................................計算各能力值在各試題被選取的機率
                if ( $level_N[$i]==0)
                {
                  $level_P_S[$i] = 0;
                }
                else
                {
                  $level_P_S[$i] = $level_S_N[$i]/$level_N[$i];
                  $level_P_S[$i] = round($level_P_S[$i],4);
                  if( $level_P_S[$i]>1 )
                  {
                    $level_P_S[$i] = 1;
                  }
                }
                //...................................................................計算各能力值在各試題被選取的機率..end
                //...................................................................計算各能力值在各被施測試題的機率
                if ( $level_N[$i]==0)
                {
                  $level_P_A[$i] = 0;
                }
                else
                {
                  $level_P_A[$i] = $level_A_N[$i]/$level_N[$i];
                  $level_P_A[$i] = round($level_P_A[$i],4);
                  if( $level_P_A[$i]>1 )
                  {
                    $level_P_A[$i] = 1;
                  }
                }
                //...................................................................計算各能力值在各被施測試題的機率..end                                              
                
                  if( $level_P_S[$i]>$Rmax[$i] )
                  {
                    $level_EP_tmp[$i] = $Rmax[$i]/$level_P_S[$i];
                  }
                  else
                  {
                    $level_EP_tmp[$i] = 1;
                  }
                              
                $EP_n .= $level_EP_tmp[$i]."@XX@";
              }
              $sql ="UPDATE `concept_item_parameter` SET `EP` = '".$EP_n."' WHERE `item_sn` = ".$item_sn_n."";
              mysql_query($sql);
            }        
          }
        }          
      break;
      case 5: // 5 ASHCOF
        {
         if(($total_N%$num_e)==0) 
          {           
            for ($i=0;$i<17;$i++)
            {
              $level_N[$i] = 0; //先將各能力區間的人數歸零
              $level_EP_tmp[$i] = 0; //先暫存各能力區間的曝光參數歸零
            }      
            $total_N = 0; //先將總人數歸零
            $sql=mysql_query("SELECT `N`, `level_num` FROM `exam_people` 
                              WHERE `cs_id` like '".$CsID."%' and `paper_vol`=".$PaperVol."");           
            while($row=mysql_fetch_array($sql)) 
            {  
              $exam_people = $row[0];
              $total_N = $total_N+$exam_people; 
              $level_num = explode("@XX@",$row[1]);
              for ($i=0;$i<17;$i++)
              {
                $level_N[$i] = $level_N[$i]+$level_num[$i];
              }        
            }
            mysql_free_result($sql);    
            // num_e 更新曝光參數的人數
            //  $Rmax 隨著不同能力區間的人數 會有的控制
            for ($i=0;$i<17;$i++)
            {              
              
              $Rmax[$i] = 1-sqrt(0.9*$level_N[$i]/$total_N);
              $Rmax[$i] = round($Rmax[$i],4);
              if($Rmax[$i]<0.2)
              {
                $Rmax[$i]=0.2;
              }             
            } 
            $sql=mysql_query("SELECT `item_sn` FROM `concept_item_parameter` WHERE `cs_id` like '".$CsID."%'");
            while($row=mysql_fetch_array($sql)) 
            {
              $item_sn_up[] = $row[0];
            }
            mysql_free_result($sql);
        
            for ($j=0;$j<count($item_sn_up);$j++)
            {
              $item_sn_n = $item_sn_up[$j];
              $sql=mysql_query("SELECT `item_sn`, `S_N`, `A_N` FROM `concept_item_parameter` 
                            WHERE `item_sn` = ".$item_sn_n."");
              while($row=mysql_fetch_array($sql)) 
              {            
                $level_S_N = explode("@XX@",$row[1]);
                $level_A_N = explode("@XX@",$row[2]);
              }
              mysql_free_result($sql);
                   
              $EP_n = "";
              for($i=0;$i<17;$i++)
              {
                //...................................................................計算各能力值在各試題被選取的機率
                if ( $level_N[$i]==0)
                {
                  $level_P_S[$i] = 0;
                }
                else
                {
                  $level_P_S[$i] = $level_S_N[$i]/$level_N[$i];
                  $level_P_S[$i] = round($level_P_S[$i],4);
                  if( $level_P_S[$i]>1 )
                  {
                    $level_P_S[$i] = 1;
                  }
                }
                //...................................................................計算各能力值在各試題被選取的機率..end
                //...................................................................計算各能力值在各被施測試題的機率
                if ( $level_N[$i]==0)
                {
                  $level_P_A[$i] = 0;
                }
                else
                {
                  $level_P_A[$i] = $level_A_N[$i]/$level_N[$i];
                  $level_P_A[$i] = round($level_P_A[$i],4);
                  if( $level_P_A[$i]>1 )
                  {
                    $level_P_A[$i] = 1;
                  }
                }
                //...................................................................計算各能力值在各被施測試題的機率..end                                              
                if( $level_P_A[$i]>$Rmax[$i] )
                {
                  $level_EP_tmp[$i] = 0;
                }
                else
                {
                  if( $level_P_S[$i]>$Rmax[$i] )
                  {
                    $level_EP_tmp[$i] = $Rmax[$i]/$level_P_S[$i];
                  }
                  else
                  {
                    $level_EP_tmp[$i] = 1;
                  }
                }              
                $EP_n .= $level_EP_tmp[$i]."@XX@";
              }
              $sql ="UPDATE `concept_item_parameter` SET `EP` = '".$EP_n."' WHERE `item_sn` = ".$item_sn_n."";
              mysql_query($sql);
            }        
          }
        }          
      break;
      /*case 1: // 2 ASHCOF
        {
          
        }
        break;*/
    }
    //...................................................................更新試題曝光參數...end   
  }
                  
       

?>
