<?php
//...................................................................使用MLE和newton法迭代
	//...................................................................MLE的函數
  //...................................................................function_U    1
  function U($D = 1.702,$A_item,$B_item,$h_exam)   
  {   
    $U = pow((1+exp(-$D*$A_item*($h_exam-$B_item))),(-1));
    return $U;
  }
  //...................................................................function_P    2
  function P($C_item,$U) 
  {   
    $P = $C_item+(1-$C_item)*$U;
    return $P;
  }
  //...................................................................function_f_1_sum_tmp    3
  function f_1_sum_tmp($A_item,$C_item,$X_item,$P)
  {   
    $f_1_sum_tmp = ($X_item-$P)*$A_item*($P-$C_item)/($P*(1-$C_item));
    return $f_1_sum_tmp;
  }
  //...................................................................function_f_2_sum_tmp    4
  function f_2_sum_tmp($A_item,$C_item,$X_item,$P)
  {   
    $f_2_sum_tmp = $A_item*$A_item*($P-$C_item)*($P-$C_item)*(1-$P)/($P*(1-$C_item)*(1-$C_item));
    return $f_2_sum_tmp;
  }
  //...................................................................使用MLE的函數進行迭代
  $D = 1.702;  
  $h_exam_tmp = $h_exam;  

  $h=0.01;
  $s=0;
  while (abs($h)>= 0.01)
  {
    $h_exam_tmp_1 = $h_exam; 
    $f_1_sum = 0;
    $f_2_sum = 0; 
  
     for($i=0;$i<=($select_num-1);$i++)
    {    
      $U = U($D,$A_item[$i],$B_item[$i],$h_exam);  
      $P = P($C_item[$i],$U);  
      $f_1_sum_tmp = f_1_sum_tmp($A_item[$i],$C_item[$i],$X_item[$i],$P);
      $f_1_sum = $f_1_sum+$f_1_sum_tmp; 
      $f_2_sum_tmp = f_2_sum_tmp($A_item[$i],$C_item[$i],$X_item[$i],$P);
      $f_2_sum = $f_2_sum+$f_2_sum_tmp; 
    }
    $f_1 = $D*$f_1_sum;
    $f_2 = (-1)*pow($D,2)*$f_2_sum;
    if ($f_2 !=0)
    {
      $h = $f_1/$f_2;  
      $h_exam = $h_exam - $h;
    }
    elseif (($f_2 ==0) && ($X_item[($select_num-1)]==1))
    {
      $h_exam = 3.2;
    }
    elseif (($f_2 ==0) && ($X_item[($select_num-1)]==0))
    {
      $h_exam = -3.2;
    }
     
    if ($h_exam >= 3.2)
    {
      $h_exam = 3.2;
    }
    elseif ($h_exam <= -3.2)
    {
      $h_exam = -3.2;
    }
    $s=$s+1;
    
    if ($s > 30)
    //if ($s > 30 || abs($h_exam)==3.2 )
    {
      break;
    }  
  }
  $h_exam = round($h_exam,4);
  $_SESSION["h_exam_A"].=$h_exam._SPLIT_SYMBOL;
  //echo $_SESSION["h_exam_A"];
  $error_d = abs($h_exam-$h_exam_tmp);
  //...................................................................end
?>
