<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
$h_exam_e = explode("@XX@",$_SESSION["h_exam_A"]);
    //暫存上一次的各項度能力估計值
    for($i=0;$i<$MIRT_d;$i++)
    {
      $tmp_n = ($select_num-$MIRT_d-1)*$MIRT_d+$i;
      $h_exam_tmp_b[$i] = $h_exam_e[$tmp_n];
      $h_exam_tmp[$i] = 0;
      
      //echo $h_exam_tmp[$i]."<br>";
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
    for ($i=0;$i<6;$i++) //決定每個quadrature points 的能力值
    {
      $x[$i]=-3+1.2*$i;
      $q_d++;
    }
    // $sig_inv 為變異數-共變異數矩陣的反矩陣
    //  4向度
    /*$sig_inv=array(array(3.8235,-1.1765,-1.1765,-1.1765),
                  array(-1.1765,3.8235,-1.1765,-1.1765),
                  array(-1.1765,-1.1765,3.8235,-1.1765),
                  array(-1.1765,-1.1765,-1.1765,3.8235));
    $det=0.0272;*/
    //  3向度
    $sig_inv=array(array(1,0,0),
                   array(0,1,0),
                   array(0,0,1));
    $det=1;
    /*$sig_inv=array(array(13.5977,-7.4024,-5.0038),
                   array(-7.4024,12.7246,-2.8561),
                   array(-5.0038,-2.8561,5.8603));
    $det=0.0193;*/
    //$k=1;
    $tmp_f=0;
    $f_m_d = (1/(pow(2*pi(),$MIRT_d/2)*pow($det,1/2)));//計算發生機率中 固定的變數   
    
    for($i=0;$i<($select_num-1);$i++)
            {                     
              $b_theta_tmp = $b_theta_e[$i]-1;               
            }
    //...................................計算各向度個quadrature points的發生機率        
    //echo  $MIRT_d.'<br>';   
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
    //include_once("MIRT_EAP_q_p.php"); 
    
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
