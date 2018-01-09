<?php
//  計算各向度個quadrature points的發生機率
    
  $Lh = 1;
            //echo 'd1:'.$d1.'d2:'.$d2.'d3:'.$d3.'d4:'.$d4.'_<br><br>';
  for($i=0;$i<($select_num-1);$i++)
  {                     
    $b_theta_tmp = $b_theta_e[$i]-1;   
    for ($j=0;$j<$MIRT_d;$j++)
    {
      
      if($b_theta_tmp==$j)
      {          
        $P = P($D,$B_item[$i],$tq[$j]);
        if($X_item[$i]==1)
        {
          $Lh = $Lh*$P;
        }
        else
        {
          $Lh = $Lh*(1-$P);
        }
      }
    }
  }                      
  //...x*sigma
  for($m1=0;$m1<$MIRT_d;$m1++)
  {
    $c1[0][$m1]=0;
    for($t1=0;$t1<$MIRT_d;$t1++)
    {
      $c1[0][$m1] = $tq[$t1]*$sig_inv[$m1][$t1]+$c1[0][$m1];
    }              
  }
  //...x*sigma..end
  //...接上*x'
  $c2=0;
  for($t1=0;$t1<$MIRT_d;$t1++)
  {
    $c2 = $c1[0][$t1]*$tq[$t1]+$c2;
  }                   
  $f_m = $Lh*$f_m_d*exp((-1/2)*$c2);
        
  for ($j=0;$j<$MIRT_d;$j++)
  {
      $h_exam_tmp[$j] = $h_exam_tmp[$j]+$tq[$j]*$f_m;            
  }      
  $tmp_f = $tmp_f+$f_m;  
?>
