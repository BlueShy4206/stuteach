<?php
    $h_exam_e = explode("@XX@",$_SESSION["h_exam_A"]);
    //暫存上一次的各項度能力估計值
    for($i=0;$i<$MIRT_d;$i++)
    {
      $tmp_n = ($select_num-$MIRT_d-1)*$MIRT_d+$i;
      $h_exam_tmp[$i] = $h_exam_e[$tmp_n];
      $h_exam_tmp_b[$i] = $h_exam_tmp[$i];
    }
    //...................................................................end
  
    //...................................................................使用MRCML的MLE和newton法迭代
	  //...................................................................function_P    1
    function P($D = 1.702,$B_item,$h_exam)   
    {   
      $P = pow((1+exp((-1)*($h_exam-$B_item))),(-1));    
      return $P;    
    }   
    //...................................................................使用MLE的能力估計進行迭代
    $D = 1.702;    
    $h_d=0.01;
    $s=0;     
    if (($select_num-1)==$MIRT_d)// 第一次能力估計需要估計所有的項度
    {
      while (abs($h_d)>= 0.01)
      {    
        for($i=0;$i<$MIRT_d;$i++)
        {      
          $f_1_sum[$i] = 0;
          $f_2_sum[$i] = 0;     
        }
        for($i=0;$i<($select_num-1);$i++)
        {                     
          $b_theta_tmp = $b_theta_e[$i]-1;
          $P = P($D,$B_item[$i],$h_exam_tmp[$b_theta_tmp]);
          $f_1_sum_tmp[$b_theta_tmp] = $X_item[$i]-$P;
          $f_1_sum[$b_theta_tmp] = $f_1_sum[$b_theta_tmp]+$f_1_sum_tmp[$b_theta_tmp]; 
          $f_2_sum_tmp[$b_theta_tmp] = (-1)*$P*(1-$P);
          $f_2_sum[$b_theta_tmp] = $f_2_sum[$b_theta_tmp]+$f_2_sum_tmp[$b_theta_tmp]; 
        }        
        $h_d = 99;
        for($i=0;$i<$MIRT_d;$i++)
        {                
          if ($f_2_sum[$i] !=0)
          {
            $h[$i] = $f_1_sum[$i]/$f_2_sum[$i];
            if (abs($h[$i])<$h_d)
            {
              $h_d = abs($h[$i]);
            }
            $h_exam_tmp[$i] = $h_exam_tmp[$i] - $h[$i];   
          }
          elseif (($f_2_sum[$i] ==0) && ($X_item[$i]==1))
          {
            $h_exam_tmp[$i] = 3.2;
          }
          elseif (($f_2_sum[$i] ==0) && ($X_item[$i]==0))
          {
            $h_exam_tmp[$i] = -3.2;
          }                        
          if ($h_exam_tmp[$i] > 3.2)
          {
            $h_exam_tmp[$i] = 3.2;
          }
          elseif ($h_exam_tmp[$i] < -3.2)
          {
            $h_exam_tmp[$i] = -3.2;
          }           
        }
      
        $s=$s+1;    
        if ($s > 30)      
        {
          break;
        }  
      }
    }
    else
    {
      $b_theta_tmp = $b_theta_e[$select_num-2]-1;//要能力估計的項度
      while (abs($h_d)>= 0.01)
      {                
        $f_1_sum[$b_theta_tmp] = 0;
        $f_2_sum[$b_theta_tmp] = 0;     
        $ans_sign[$b_theta_tmp] =0;  
        for($i=0;$i<($select_num-1);$i++)
        {                     
          $b_theta_tmp1 = $b_theta_e[$i]-1;
          if( $b_theta_tmp1 == $b_theta_tmp)
          {
            $ans_sign[$b_theta_tmp] = $X_item[$i];//紀錄每個項度最後一次的答題狀況
            $P = P($D,$B_item[$i],$h_exam_tmp[$b_theta_tmp]);
            $f_1_sum_tmp[$b_theta_tmp] = $X_item[$i]-$P;
            $f_1_sum[$b_theta_tmp] = $f_1_sum[$b_theta_tmp]+$f_1_sum_tmp[$b_theta_tmp]; 
            $f_2_sum_tmp[$b_theta_tmp] = (-1)*$P*(1-$P);
            $f_2_sum[$b_theta_tmp] = $f_2_sum[$b_theta_tmp]+$f_2_sum_tmp[$b_theta_tmp]; 
          }
        }    
      
        $h_d = 99;
        if ($f_2_sum[$b_theta_tmp] !=0)
        {
          $h[$b_theta_tmp] = $f_1_sum[$b_theta_tmp]/$f_2_sum[$b_theta_tmp];
          if (abs($h[$b_theta_tmp])<$h_d)
          {
            $h_d = abs($h[$b_theta_tmp]);
          }
          $h_exam_tmp[$b_theta_tmp] = $h_exam_tmp[$b_theta_tmp] - $h[$b_theta_tmp];   
        }
        elseif (($f_2_sum[$b_theta_tmp] ==0) && ($ans_sign[$b_theta_tmp]==1))
        {
          $h_exam_tmp[$b_theta_tmp] = 3.2;
        }
        elseif (($f_2_sum[$b_theta_tmp] ==0) && ($ans_sign[$b_theta_tmp]==0))
        {
          $h_exam_tmp[$b_theta_tmp] = -3.2;
        }                        
        if ($h_exam_tmp[$b_theta_tmp] >= 3.2)
        {
          $h_exam_tmp[$b_theta_tmp] = 3.2;
        }
        elseif ($h_exam_tmp[$b_theta_tmp] <= -3.2)
        {
          $h_exam_tmp[$b_theta_tmp] = -3.2;
        }           
      
        $s=$s+1;    
        if ($s > 30)      
        {
          break;
        }  
      }
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
