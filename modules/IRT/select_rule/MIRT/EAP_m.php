<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
$h_exam_e = explode("@XX@",$_SESSION["h_exam_A"]);
    //暫存上一次的各項度能力估計值
    for($i=0;$i<$MIRT_d;$i++)
    {
      $tmp_n = ($select_num-$MIRT_d-1)*$MIRT_d+$i;
      $h_exam_tmp_b[$i] = $h_exam_e[$tmp_n];
      $h_exam_tmp[$i] = 0;      
    }
    //...................................................................end
  
    //...................................................................使用MRCML的EAP
	  //...................................................................function_P    1
    function P($D = 1.702,$B_item,$h_exam)   
    {   
      $P = pow((1+exp((-1)*($h_exam-$B_item))),(-1));    
      return $P;    
    }   
    //...................................................................使用EAP的能力估計
    $q_d = 0; //quadrature points的總數
    for ($i=0;$i<11;$i++) //決定每個quadrature points 的能力值
    {
      $x[$i]=-3+0.6*$i;
      $q_d++;
    }
    //echo $q_d;
    // 寫進資料庫 變異數-共變異數矩陣的反矩陣 與 行列式 的值
    // 向度間無相關 1@XX@0@XX@0@XX@0@XX@1@XX@0@XX@0@XX@0@XX@1@XX@1
    // 向度間有相關 按照真實資料 13.5977@XX@-7.4024@XX@-5.0038@XX@-7.4024@XX@12.7246@XX@-2.8561@XX@-5.0038@XX@-2.8561@XX@5.8603@XX@0.0193
    $sql=mysql_query("SELECT `sigma_det` FROM `concept_info_dim` WHERE `cs_id`=$CsID ");                                        
    while($row=mysql_fetch_array($sql))
    {
      $sigma_det = $row[0];
    }
    mysql_free_result($sql);
    $sigma_det = explode("@XX@",$sigma_det);
    
    $tmp_s = 0;
    for ($i=0;$i<$MIRT_d;$i++)
    {
      for ($j=0;$j<$MIRT_d;$j++)
      {
        $sig_inv[$i][$j] = $sigma_det[$tmp_s];
        $tmp_s = $tmp_s +1;
      }
    }
    $det = $sigma_det[$MIRT_d*$MIRT_d];
    
    $tmp_f=0;
    $f_m_d = (1/(pow(2*pi(),$MIRT_d/2)*pow($det,1/2)));//計算發生機率中 固定的變數   
    
    for($i=0;$i<($select_num-1);$i++)
            {                     
              $b_theta_tmp = $b_theta_e[$i]-1;               
            }
    //...................................計算各向度個quadrature points的發生機率        
    for ($d1=0;$d1<$q_d;$d1++)
    {
      $tq[0] = $x[$d1];
      if($MIRT_d>=2)
      {
        for ($d2=0;$d2<$q_d;$d2++)
        {    
          $tq[1] = $x[$d2];
          if($MIRT_d>=3)
          {
            for ($d3=0;$d3<$q_d;$d3++)
            {
              $tq[2] = $x[$d3];
              if($MIRT_d>=4)
              {
                for ($d4=0;$d4<$q_d;$d4++)
                {              
                  $tq[3] = $x[$d4];
                  if($MIRT_d>=5)
                  {                    
                    for ($d5=0;$d5<$q_d;$d5++)
                    {    
                      $tq[4] = $x[$d5];
                      if($MIRT_d>=6)
                      {
                        for ($d6=0;$d6<$q_d;$d6++)
                        {
                          $tq[5] = $x[$d6];
                          if($MIRT_d>=7)
                          {
                            for ($d7=0;$d7<$q_d;$d7++)
                            {              
                              $tq[6] = $x[$d7];
                              include("MIRT_EAP_q_p.php");
                            }
                          }else{
                          include("MIRT_EAP_q_p.php");        
                          }          
                        }
                      }else{
                      include("MIRT_EAP_q_p.php");       
                      }        
                    }
                  }else{
                    include("MIRT_EAP_q_p.php"); 
                  }
                }
              }else{
                include("MIRT_EAP_q_p.php");        
              }          
            }
          }else{
              include("MIRT_EAP_q_p.php");       
          }        
        }
        }else{
            include("MIRT_EAP_q_p.php"); 
        }
      }
    //...................................計算各向度個quadrature points的發生機率 ...end
    //...................................................................MLE的能力估計...end
    for ($j=0;$j<$MIRT_d;$j++)
    {
      $h_exam_tmp[$j] = $h_exam_tmp[$j]/$tmp_f;
    }
      $error_d = 0;    
    //將各項度的能力紀錄    
    for($i=0;$i<$MIRT_d;$i++)
    {
      $h_exam_tmp[$i] = round($h_exam_tmp[$i],4);
      $_SESSION["h_exam_A"].=$h_exam_tmp[$i]._SPLIT_SYMBOL;
      $error_m_d[$i] = abs($h_exam_tmp[$i]-$h_exam_tmp_b[$i]);
      if($error_m_d[$i]>$error_d)
      {        
        $error_d = $error_m_d[$i];
      }
    }
    $_SESSION["error_d_all"] .= $error_d._SPLIT_SYMBOL;
?>
