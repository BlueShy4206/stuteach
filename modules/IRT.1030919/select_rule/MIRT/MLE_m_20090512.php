<?php
/*require_once "include/adp_API.php";
require_once 'Date.php';
require_once 'db.php';

if(!$auth->checkAuth())
{
	FEETER();
	die();
}
  $item_end = 30;
  $CsID=$_POST['cs_id'];
  $PaperVol=$_POST['paper_vol'];  
  $_SESSION["exam_type"]=$_POST['exam_type'];
  
//...................................................................判斷是否第一次作答
if((isset($_POST['user_answer']))&&(isset($_SESSION["selected_item"])))
{
  //debug_msg("第".__LINE__."行 _POST ", $_POST);
  $_POST['select_num']++;
  $select_num=$_POST['select_num'];
  $item_sn=$_POST['item_sn'];
  $error_d_tmp=$_POST['error_d'];
  //$a = $_POST['P_a'];
	$b = $_POST['P_b'];
	//$c = $_POST['P_c'];
	$MIRT_d = $_POST['MIRT_d'];
  $next_one = 0; //條件終止前設為0
  // _SPLIT_SYMBOL-->@XX@
	$_SESSION["rec_user_answer"].=$_POST["user_answer"]._SPLIT_SYMBOL; 
	$CsID=$_POST['CsID'];  //考卷的ID
	//...$_SESSION["select_item_id"].= $item_sn."','";	//字串(前一次所有做過的試題id組成)
	//$_SESSION["select_P_a"].= $a._SPLIT_SYMBOL;	//字串(前一次所有做過的試題參數 a 組成)
	$_SESSION["select_P_b"].= $b._SPLIT_SYMBOL;	//字串(前一次所有做過的試題參數 b 組成)
	//$_SESSION["select_P_c"].= $c._SPLIT_SYMBOL;	//字串(前一次所有做過的試題參數 c 組成)
	$PaperVol=$_POST['PaperVol'];  //卷
  $_SESSION["PaperVol"]=$PaperVol;
	$h_exam=$_POST['h_exam']; //上一次能力值	
  $selected_item=$_SESSION["selected_item"];
  $select_item_id_S = $_SESSION["select_item_id_S"];
  $select_item_id_A = $_SESSION["select_item_id_A"];
  $question=new Item_Structure4IRT($CsID, $selected_item, $PaperVol);
  
  //...................................................................從資料撈出item_sn試題流水號
  $sql=mysql_query("SELECT `item_sn` FROM `concept_item` WHERE (`cs_id`=$CsID)
                    and (`paper_vol`=$PaperVol) and (`item_num`=$selected_item)");
  while($row=mysql_fetch_array($sql))
  {
    $item_sn = $row[0];
  }
  mysql_free_result($sql);
  //...................................................................end
  //
	//...................................................................記錄上一筆作答情形
  //紀錄作答情形
	if($_POST['user_answer']==$question->get_item_correct_answer())//答對時
  {	
    $_SESSION["select_ans"].= "1@XX@";//字串(前一次所有做過的試題ans,再加上本次答對1) 
	}else//答錯時
  { 
		$_SESSION["select_ans"].= "0@XX@";//字串(前一次所有做過的試題ans,再加上本次答對0)
	}
	
  //前一次全部作答情況(對錯,a,b,c參數)
  $X_item = explode("@XX@",$_SESSION["select_ans"]);
  //$A_item = explode("@XX@",$_SESSION["select_P_a"]);
  $B_item = explode("@XX@",$_SESSION["select_P_b"]);
  //$C_item = explode("@XX@",$_SESSION["select_P_c"]);
  $b_theta_e = explode("@XX@",$_SESSION["select_item_b_theta"]);    
  //...................................................................  計算各向度做過的題數  
  for($i=0;$i<$MIRT_d;$i++)
  {
    $theta_count[$i] = 0;
    
  }
  $lim_c3 = 0; //測驗中止條件3 各項度試題至少做滿3題
  for($i=0;$i<count($b_theta_e);$i++)
  {
    for($j=0;$j<$MIRT_d;$j++)
    {    
      $tmp_theta = $b_theta_e[$i]-1;
      if($j==$tmp_theta)
      {
        $theta_count[$j]++;
      }      
    }
  }
  for($i=0;$i<$MIRT_d;$i++)
  {
    if($theta_count[$i]>=3)
    {
      $lim_c3++;
    }    
  }
  //echo $lim_c3;
  //...................................................................end
	//
	if($select_num<=$MIRT_d)
	{
    //...................................................................進行其他項度的初始測驗	
    $item_sn_s= explode("','",$_SESSION["select_item_id_A"]);
    $item_sn_tmp = $item_sn_s[$select_num-1];
  
    $sql=mysql_query("SELECT concept_item.item_num,concept_item.cs_id,concept_item.paper_vol,
                      concept_item.op_ans,concept_item_parameter.b,concept_item_parameter.dim 
                      FROM concept_item,concept_item_parameter 
                      WHERE concept_item.item_sn = concept_item_parameter.item_sn and concept_item_parameter.item_sn = $item_sn_tmp");	
    while($row=mysql_fetch_array($sql))
    {  
      $selected_item = $row[0];
      $CsID = $row[1];
      $PaperVol = $row[2]; 
      $op_ans = $row[3];
      $P_b = $row[4];    
      $P_b_theta = $row[5]; 
    }
    mysql_free_result($sql);    
  
    $question=new Item_Structure4IRT($CsID, $selected_item, $PaperVol);  //呼叫試題圖檔
	  $tmp = $select_num;
	}
	else //開始能力估計與選題*/
	{
    //...................................................................由資料庫檢查已做過幾題
    $sql=mysql_query("SELECT `b` FROM `concept_item_parameter` WHERE `item_sn` 
                      in ('".$select_item_id_A."')");
    $n =0;
    while($row=mysql_fetch_array($sql))
    {    
      $n++;  
    }
    mysql_free_result($sql);
    $tmp = $n+1; //暫存要做第幾題
    $h_exam_e = explode("@XX@",$_SESSION["h_exam_A"]);
    //暫存上一次的各項度能力估計值
    for($i=0;$i<$MIRT_d;$i++)
    {
      $tmp_n = ($select_num-$MIRT_d-1)*$MIRT_d+$i;
      $h_exam_tmp[$i] = $h_exam_e[$tmp_n];
      $h_exam_tmp_b[$i] = $h_exam_tmp[$i];
      //echo $h_exam_tmp[$i].'_'.$h_exam_tmp_b[$i].'<br>';
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
    for($i=0;$i<$MIRT_d;$i++)
    {      
      //echo $h_exam_tmp[$i].'_'.$h_exam_tmp_b[$i].'<br>';
    }
    $error_d = 0;    
    //將各項度的能力紀錄
    //echo $error_d_tmp.'<br>';
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
    //...................................................................MLE的能力估計...end
        
    //...................................................................MIRT的選題法
    
    //  紀錄各項度二階微分的和
    for($i=0;$i<$MIRT_d;$i++)
    {            
      $inf_sum[$i] = 0; 
      $max_information[$i] = 0;      
      $det_inf[$i] = 1; //行列式的值
    }
    for($i=0;$i<($select_num-1);$i++)
    {                     
      $b_theta_tmp = $b_theta_e[$i]-1;
      $ans_sign[$b_theta_tmp] = $X_item[$i];//紀錄每個項度最後一次的答題狀況
      $P = P($D,$B_item[$i],$h_exam_tmp[$b_theta_tmp]);      
      $inf_sum_tmp[$b_theta_tmp] = $P*(1-$P);
      $inf_sum[$b_theta_tmp] = $inf_sum[$b_theta_tmp]+$inf_sum_tmp[$b_theta_tmp]; 
    }     
    //  紀錄各項度二階微分的和...end          
    
    $cs_id_7=substr($CsID, 0, 7);
    $sql=mysql_query("SELECT `item_sn`,`b`,`dim` FROM `concept_item_parameter`
                      WHERE `item_sn` not in ('".$select_item_id_A."') 
                      and `cs_id` like '".$cs_id_7."%'");     
    while($row=mysql_fetch_array($sql))
    {          
      $B_item_tmp = $row[1];
      $b_theta_tmp = $row[2]-1;        
      if ($b_theta_tmp>=0)
      {
        $P = P($D,$B_item_tmp,$h_exam_tmp[$b_theta_tmp]);
        $information[$b_theta_tmp] = $inf_sum[$b_theta_tmp]+($P*(1-$P));
        $information[$b_theta_tmp] = round($information[$b_theta_tmp],4);  
        if ($information[$b_theta_tmp]>$max_information[$b_theta_tmp])
        { 
          $max_information[$b_theta_tmp] = $information[$b_theta_tmp];
          $item_sn_tmp[$b_theta_tmp] = $row[0];                 
        }                                        
      }
    }
    mysql_free_result($sql);
    //  計算行列式的值    
    //$test1 = 1;
    $det_max=0;      
    for($i=0;$i<$MIRT_d;$i++)
    {      
      for($j=0;$j<$MIRT_d;$j++)
      {      
        $inf_sum_tmp1[$j]=$inf_sum[$j];
        if($j == $i)
        {
          $inf_sum_tmp1[$j]=$max_information[$j];
        }           
        $det_inf[$i] = $det_inf[$i]*$inf_sum_tmp1[$j];
      }
      if($det_inf[$i]>$det_max)//找出使行列式值最大的題目
      {
        $det_max = $det_inf[$i];
        $det_max_d = $i; 
      }  
    }
    $item_sn = $item_sn_tmp[$det_max_d];    
    
    $_SESSION["select_item_id_A"].= $item_sn."','";	//字串(所有被施測的試題id組成)
    $select_item_id_A = $_SESSION["select_item_id_A"]; 
    //...................................................................最大訊息法end
    //
	 //...................................................................  選出下一試題編號與試題參數
	 $sql=mysql_query("SELECT concept_item.item_num,concept_item.cs_id,concept_item.paper_vol,
                     concept_item.op_ans,concept_item_parameter.b,concept_item_parameter.dim
                     FROM concept_item,concept_item_parameter 
                     WHERE concept_item.item_sn = concept_item_parameter.item_sn and concept_item_parameter.item_sn = $item_sn");	
	 while($row=mysql_fetch_array($sql))
   {  
    $selected_item = $row[0];
    $CsID = $row[1];
    $PaperVol = $row[2]; 
    $op_ans = $row[3];
    $P_b = $row[4];    
    $P_b_theta = $row[5];    
   }
   mysql_free_result($sql);  
   $_SESSION["select_item_b_theta"].= $P_b_theta._SPLIT_SYMBOL;	
   
   $question=new Item_Structure4IRT($CsID, $selected_item, $PaperVol);  //呼叫試題圖檔
  //...................................................................end   
  }
/*
else
{
  if(isset($CsID)) //初始測驗時
  {
    unset($_SESSION["select_ans"]); //將select_ans的session歸零
    unset($_SESSION["select_item_id"]);//將select_item_id的session歸零
    //unset($_SESSION["select_P_a"]);
    unset($_SESSION["select_P_b"]);
    //unset($_SESSION["select_P_c"]);
    unset($_SESSION["h_exam_S"]);
    unset($_SESSION["select_item_id_S"]);
    unset($_SESSION["select_item_id_A"]);
    unset($_SESSION["select_item_b_theta"]);
    
    $_SESSION["start_time"]=date("U");       //初始化時間
    $_SESSION["date"]= date("Y-m-d, H:i:s");  //開始測驗時間
    //...................................................................end
    $h_exam = 0;   //起始能力值
    //$_SESSION["h_exam_S"] = "";
    $_SESSION["h_exam_A"] = "";
    //$_SESSION["select_item_id_S"] = ""; 
    $_SESSION["select_item_id_A"] = "";
    $_SESSION["select_item_b_theta"] = "";
    $_SESSION["error_d_all"] = "";  //紀錄所有能力估計變動量
    $error_d =3;
    $tmp = 1;    
    
    // 判斷 MIRT的維度       
    $MIRT_d = 0;
    $sql=mysql_query("SELECT distinct `dim` FROM `concept_item_parameter` WHERE (`cs_id`=$CsID)");
    while($row=mysql_fetch_array($sql))
    {
      $MIRT_d++;
    }
    mysql_free_result($sql);
    //echo  $MIRT_d;    
    // 判斷 MIRT的維度  end
    //...................................................................設定並存儲各項度初始能力
    for($d=0;$d<$MIRT_d;$d++)
    {
      $_SESSION["h_exam_A"] .= $h_exam._SPLIT_SYMBOL;
    }
    //...................................................................設定各項度初始能力 end
    
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
    }
    $select_item_id_A = $_SESSION["select_item_id_A"];
    $item_sn_s= explode("','",$_SESSION["select_item_id_A"]);
    $item_sn = $item_sn_s[0];
    //echo count($item_sn_s);
    //$_SESSION["select_item_id_A"].= $item_sn."','";	//字串(所有被施測的試題id組成)
    $sql=mysql_query("SELECT `item_num`,`cs_id`,`paper_vol`
                      FROM concept_item WHERE `item_sn` = $item_sn");
    while($row=mysql_fetch_array($sql))
    {  
      $selected_item = $row[0];
      $CsID = $row[1];
      $PaperVol = $row[2]; 
    }
    mysql_free_result($sql);
  //...................................................................end
    $select_num=1;  //記錄已作幾題        
  }
  else //沒有輸入任何值，直接送出答案
  {
    $item_sn=$_SESSION["item_sn"];
    $CsID=$_SESSION["CsID"];
    $PaperVol=$_SESSION["PaperVol"];
    $selected_item=$_SESSION["selected_item"];
    $select_num=$_SESSION["select_num"];
    $h_exam_b=$_SESSION["h_exam_b"];
    $h_exam=$_SESSION["h_exam"];
    $error_d=$_SESSION["error_d"];
    $h_exam_A=$_SESSION["h_exam_A"];
    $select_item_id_A =$_SESSION["select_item_id_A"];
    $select_item_id_S =$_SESSION["select_item_id_S"];
    $h_exam_S = $_SESSION["h_exam_S"];
    $MIRT_d = $_POST['MIRT_d'];
    $error_m_d = $_SESSION["error_m_d"];
    $theta_count = $_SESSION["theta_count"];
    //...................................................................由資料庫檢查已做過幾題
    $sql=mysql_query("SELECT `a` FROM `concept_item_parameter` WHERE `item_sn` 
                      in ('".$select_item_id_A."')");
    $n =0;
    while($row=mysql_fetch_array($sql))
    {    
      $n++;  
    }
    mysql_free_result($sql);
    $tmp = $n; //暫存要做第幾題
    if($select_num<=$MIRT_d)
	  {
      $select_num=$_POST['select_num'];
      $item_sn_s= explode("','",$_SESSION["select_item_id_A"]);
      $item_sn = $item_sn_s[$select_num-1];
      $tmp = $select_num;
    }
    //...................................................................end      
  }
  $sql=mysql_query("SELECT concept_item.op_ans,concept_item_parameter.b
                    ,concept_item_parameter.dim 
                    FROM concept_item,concept_item_parameter 
                    WHERE concept_item.item_sn = concept_item_parameter.item_sn 
                    and concept_item_parameter.item_sn = $item_sn");	
  while($row=mysql_fetch_array($sql))
  {
    $op_ans = $row[0];
    $P_b = $row[1]; 
    $P_b_theta = $row[2];
  }
  mysql_free_result($sql);
  $question=new Item_Structure4IRT($CsID, $selected_item, $PaperVol); //呼叫試題圖檔
}
  
if($select_num == $tmp)
{
  //echo $_SESSION["select_item_b_theta"]."<br>";
  //echo $_SESSION["select_P_b"]."<br>";
  //echo $_SESSION["select_ans"]."<br>";
  //...................................................................判斷測驗是否中止
  //echo $error_d;
  //echo '<br>'.$_SESSION["error_d_all"].'<br>';
  $error_d_all = explode("@XX@",$_SESSION["error_d_all"]);
  $error_d_time = 0;
  for($i=0;$i<count($error_d_all)-1;$i++)
  {
    if($error_d_all[$i]==0)
    {
      $error_d_time++;
    }
  }
  //echo  $error_d_time;
  if($select_num <=$item_end)
  {
    if(($error_d == 0) && ($lim_c3 ==$MIRT_d) && ($error_d_time >= ($MIRT_d*2)))
    {
      $next_one = 1; //結束測驗
      $_SESSION["select_num"]=$select_num;
    }
    elseif (($error_d<0.01) && ($lim_c3 == $MIRT_d) && ($error_d_time >= ($MIRT_d*2)))
    {
      $next_one = 1; //結束測驗
      $_SESSION["select_num"]=$select_num;
    }
    else
    {
      $question_select_pic=$question->get_item_select_pic();
      echo '<br><table width="700" border="0" cellspacing="6" cellpadding="0">
           <tr>
           <td align="right" scope="col"><table width="75%" border="0" cellpadding="0" cellspacing="0">
            <tr>
            <td>  </td>
            <td align="right" scope="col" class="s_title"></td>
            </tr>
            </table></td>
           </tr>
           <tr>
            <td scope="col"><table width="100%" border="0" cellpadding="2" cellspacing="0" class="title">
              <tr>
                <td scope="col"><img src="'._THEME_IMG.'li.gif" width="11" height="28" /></td>
                <td width="99%" scope="col">第'.$select_num.'題【'.$selected_item.'】</td>
              </tr>
            </table></td>
          </tr>';
      if(isset($showfig)){		unset($showfig);	}
      $PImgProp['item_filename']=GetImageSize($_SESSION['cs_path'].$question->item_filename);
      $showfig=explode(".", $question->item_filename);
      $showfig[0]=str2compiler($showfig[0]);
      echo '<tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td scope="col"><img src="'._THEME_IMG.'main_lt.gif" width="12" height="12" /></td>
                <td width="96%" background="'._THEME_IMG.'main_ct.gif" scope="col"></td>
                <td scope="col"><img src="'._THEME_IMG.'main_rt.gif" width="12" height="12" /></td>
              </tr>
              <tr>
                <td width="12" background="'._THEME_IMG.'main_lc.gif">&nbsp;</td>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="left" scope="col" height="'.$PImgProp['item_filename'][1].'"><img src="viewfig.php?list='.$showfig[0].'&tpp='.$showfig[1].'" border="0">';	
      echo '</td>
                  </tr>
                </table></td>
                <td background="'._THEME_IMG.'main_rc.gif">&nbsp;</td>
              </tr>
              <tr>
                <td><img src="'._THEME_IMG.'main_ld.gif" width="12" height="12" /></td>
                <td background="'._THEME_IMG.'main_cd.gif"></td>
                <td><img src="'._THEME_IMG.'main_rd.gif" width="12" height="12" /></td>
              </tr>
            </table>
              </td>
          </tr>';
      echo '<tr><td><form method="POST" action="modules.php">'."\n";
      echo "<input type=\"hidden\" name=\"h_exam\" value=".$h_exam.">";
      echo "<input type=\"hidden\" name=\"CsID\" value=".$CsID.">";
      echo "<input type=\"hidden\" name=\"PaperVol\" value=".$PaperVol.">";  
      echo "<input type=\"hidden\" name=\"select_num\" value=".$select_num.">";
      echo "<input type=\"hidden\" name=\"item_sn\" value=".$item_sn.">";
      //echo "<input type=\"hidden\" name=\"P_a\" value=".$P_a.">";
      echo "<input type=\"hidden\" name=\"P_b\" value=".$P_b.">";
      //echo "<input type=\"hidden\" name=\"P_c\" value=".$P_c.">";
      echo "<input type=\"hidden\" name=\"exam_type\" value=".$_SESSION["exam_type"].">";
      echo "<input name=\"op\" type=\"hidden\" value=\"modload\">";
      echo "<input name=\"name\" type=\"hidden\" value=\"IRT\">";    
      echo '<input name="file" type="hidden" value="'.$_REQUEST['file'].'">';
      echo '<input name="screen" type="hidden" value="all">';
      echo "<input type=\"hidden\" name=\"MIRT_d\" value=".$MIRT_d.">";  
      echo "<input type=\"hidden\" name=\"error_d\" value=".$error_d.">";
          
      
      for($i=0;$i<$question->get_item_select_num();$i++)
      {
        $PImgProp['op_pieces'.$i]=GetImageSize($_SESSION['cs_path'].$question->op_pieces[$i]);
        $tableH+=$PImgProp['op_pieces'.$i][1];
      }
      $tableH+=56;
      echo '<table width="100%" height="'.$tableH.'" border="0" cellpadding="2" cellspacing="2" class="line01">';
      for($i=0;$i<$question->get_item_select_num();$i++)
      {
        if(isset($showfig)){		unset($showfig);	}
        $showfig=explode(".", $question->op_pieces[$i]);
        $showfig[0]=str2compiler($showfig[0]);
        echo "<tr><td width=\"50\" align=\"center\" scope=\"col\"><input type=\"radio\" name=\"user_answer\" value=\"".($i+1)."\"></td><td width=\"650\" align=\"left\"  class=\"s_title\" height=\"".$PImgProp['op_pieces'.$i][1]."\"><img border=\"0\" src=\"viewfig.php?list=".$showfig[0]."&tpp=".$showfig[1]."\"></td></tr>\n";
      }
      echo "</table><center><input type=\"submit\" value=\"選擇完畢，進入下一題\" name=\"submit\"  class=\"butn01\">\n";
      echo '</form>';
      echo '</td></tr></table>';
      echo "<table>
            <center>
            <tr>
            <td><div align=\"center\">";
      if($user_data->access_level==9)
      {
        //echo $item_sn.'<br>';
        echo "第".$P_b_theta."向度"."　難度".$P_b."　ans:".$op_ans."<P>";
        if (($select_num)>$MIRT_d)//$MIRT_d
        {
          echo '<table border="1">
                  <tr>
                    <td>向度</td>
                    <td>前一次能力估計值</td>
                    <td>能力估計值</td>
                    <td>前後能力估計的差距</td>
                    <td>已作題目</td>
                  </tr>';
           $h_exam_tmp= explode("@XX@",$_SESSION["h_exam_A"]);//暫存能力估計值陣列
           for($i=1;$i<=$MIRT_d;$i++)
           {
            $h_b = ($select_num-$MIRT_d-1)*$MIRT_d+($i-1);//前一次D個向度能力估計值的順序
            $h_n = ($select_num-$MIRT_d)*$MIRT_d+($i-1);//這一次D個向度能力估計值的順序
            echo'<tr>
                    <td><div align="center">'.$i.'</div></td>
                    <td>'.$h_exam_tmp[$h_b].'</td>
                    <td>'.$h_exam_tmp[$h_n].'</td>
                    <td>'.$error_m_d[$i-1].'</td>
                    <td>'.$theta_count[$i-1].'</td>
                 </tr>';            
           }
          echo '</table>';          
        }        
      }
      echo "</div>
          </td>
          </tr>
          </center>
          </table>";          
  //...................................................................暫存資料，當沒有送出答案時，所以給的資料
      $_SESSION["CsID"]=$CsID;
      $_SESSION["PaperVol"]=$PaperVol;
      $_SESSION["selected_item"]=$selected_item;
      $_SESSION["item_sn"]=$item_sn;
      $_SESSION["select_num"]=$select_num;
      $_SESSION["h_exam_b"]=$h_exam_b;
      $_SESSION["h_exam"]=$h_exam;
      $_SESSION["error_d"]=$error_d;   
      $_SESSION["error_m_d"]=$error_m_d;
      $_SESSION["theta_count"]=$theta_count;      
  //...................................................................end  
    }  
  }
  else
  { 
    $next_one = 1; //結束測驗
    $_SESSION["select_num"]=$select_num;
  }
  if($next_one)
  {    
    include_once("IRT_record.php"); //...結果呈現 與 作答紀錄
  }
}
else
{
  echo "<br>測驗中，請勿重新整理視窗或回到上一頁，請重新參加測驗<P>";  
  //session_destroy(); //清除全部session
  $RedirectTo="modules.php?op=main";
	echo '<a href="'.$RedirectTo.'">[ 按此返回 ]</a>';
}*/

?>
