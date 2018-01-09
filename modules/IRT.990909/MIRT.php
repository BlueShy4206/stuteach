<?php
require_once "include/adp_API.php";
require_once 'Date.php';
require_once 'db.php';

 
  $CsID=$_POST['cs_id'];
  $PaperVol=$_POST['paper_vol'];  
  $_SESSION["exam_type"]=$_POST['exam_type'];

//...................................................................判斷是否第一次作答
if((isset($_POST['user_answer']))&&(isset($_SESSION["selected_item"])))
{
  $type_id_e = $_SESSION["type_id_e"];
  $type_id_s = $_SESSION["type_id_s"];
  $type_id_t = $_SESSION["type_id_t"];
  $num_e = $_SESSION["num_e"];
  $item_length = $_SESSION["item_length"];  
  //debug_msg("第".__LINE__."行 _POST ", $_POST);
  $_POST['select_num']++;
  $select_num=$_POST['select_num']; //已做過題數
  
  $CsID=$_POST['CsID'];  //考卷的ID
  $selected_item=$_POST['selected_item'];  // 該卷編號
  $PaperVol=$_POST['PaperVol'];  //卷  
  $item_sn=$_POST['item_sn'];
  
  $a = $_POST['P_a'];
	$b = $_POST['P_b'];
	$c = $_POST['P_c'];
	$MIRT_d = $_SESSION["MIRT_d"];
	
  $next_one = 0; //條件終止前設為0
  // _SPLIT_SYMBOL-->@XX@
	$_SESSION["rec_user_answer"].=$_POST["user_answer"]._SPLIT_SYMBOL; 
	
	$_SESSION["select_P_a"].= $a._SPLIT_SYMBOL;	//字串(前一次所有做過的試題參數 a 組成)
	$_SESSION["select_P_b"].= $b._SPLIT_SYMBOL;	//字串(前一次所有做過的試題參數 b 組成)
	$_SESSION["select_P_c"].= $c._SPLIT_SYMBOL;	//字串(前一次所有做過的試題參數 c 組成)
	
  $_SESSION["PaperVol"]=$PaperVol;
	$h_exam=$_POST['h_exam']; //上一次能力值	
  $select_item_id_S = $_SESSION["select_item_id_S"];
  $select_item_id_A = $_SESSION["select_item_id_A"];
  
  //$question=new Item_Structure4IRT($CsID,$selected_item,$PaperVol);
  //...................................................................從資料撈出item_sn試題流水號
  $sql=mysql_query("SELECT `op_ans` FROM `concept_item` 
                    WHERE `item_sn`=$item_sn");
  while($row=mysql_fetch_array($sql))
  {
    $question = $row[0];    
  }
  mysql_free_result($sql);
  //...................................................................end
  //
	//...................................................................記錄上一筆作答情形
  //紀錄作答情形
  if($_POST['user_answer']==$question)//答對時
  {	
    $_SESSION["select_ans"].= "1@XX@";//字串(前一次所有做過的試題ans,再加上本次答對1) 
	}else//答錯時
  { 
		$_SESSION["select_ans"].= "0@XX@";//字串(前一次所有做過的試題ans,再加上本次答對0)
	}
  //...................................................................end
  //前一次全部作答情況(對錯,a,b,c參數)
  $X_item = explode("@XX@",$_SESSION["select_ans"]);
  $A_item = explode("@XX@",$_SESSION["select_P_a"]);
  $B_item = explode("@XX@",$_SESSION["select_P_b"]);
  $C_item = explode("@XX@",$_SESSION["select_P_c"]);
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
  $_SESSION["theta_count"] = $theta_count;
  //...................................................................end
	//
	if($select_num<=$MIRT_d)
	{
    //...................................................................進行其他項度的初始測驗	
    $item_sn_s= explode("','",$_SESSION["select_item_id_A"]);
    $item_sn = $item_sn_s[$select_num-1];
    $P_b_theta = $b_theta_e[$select_num-1];
    
    $sql=mysql_query("SELECT concept_item.item_num,concept_item.cs_id,concept_item.paper_vol,
                      concept_item.op_ans,concept_item_parameter.a,concept_item_parameter.b
                      ,concept_item_parameter.c FROM concept_item,concept_item_parameter 
                      WHERE concept_item.item_sn = concept_item_parameter.item_sn and concept_item_parameter.item_sn = $item_sn");	
    while($row=mysql_fetch_array($sql))
    {  
      $selected_item = $row[0];
      $CsID = $row[1];
      $PaperVol = $row[2]; 
      $op_ans = $row[3];
      $P_a = $row[4]; 
      $P_b = $row[5]; 
      $P_c = $row[6];   ; 
    }
    mysql_free_result($sql);    
    $question=new Item_Structure4IRT($CsID, $selected_item, $PaperVol);  //呼叫試題圖檔
	  $tmp = $select_num;
	}
	else //開始能力估計與選題
	{
    //...................................................................由資料庫檢查已做過幾題
    $sql=mysql_query("SELECT `a` FROM `concept_item_parameter` WHERE `item_sn` 
                      in ('".$select_item_id_A."')");
    $n =0;
    while($row=mysql_fetch_array($sql))
    {    
      $n++;  
    }
    mysql_free_result($sql);
    $tmp = $n+1; //暫存要做第幾題
    //...................................................................end
    
    //............................................................................. 判斷用 何種 能力估計的程式
    //...................................................................function_U    1
    if($type_id_t == 1)
    {
      include("select_rule/MIRT/MLE_m.php"); //...................................使用多向度MLE進行能力估計
    }
    elseif($type_id_t == 2)
    {
    include("select_rule/MIRT/EAP_m.php"); //...................................使用多向度EAP進行能力估計
    }
    //............................................................................. 判斷用 何種 能力估計的程式...end
  
    //............................................................................. 判斷用 何種 選題法的程式
    if(($type_id_e == 1) and ($type_id_s ==1))
    {
      include("select_rule/MIRT/FI_m.php"); //...................................使用無曝光控制 與 FI進行選題
    }
    elseif(($type_id_e > 1) and ($type_id_s ==1))
    {
      include("select_rule/MIRT/FI_EP_m.php"); //................................使用曝光率控制 與 FI進行選題
    }
    //............................................................................. 判斷用 何種 選題法的程式...end  
    $sql=mysql_query("SELECT concept_item.item_num,concept_item.cs_id,concept_item.paper_vol,
                      concept_item.op_ans,concept_item_parameter.b FROM concept_item,concept_item_parameter 
                      WHERE concept_item.item_sn = $item_sn and concept_item_parameter.item_sn = $item_sn");	
	  while($row=mysql_fetch_array($sql))
    {  
      $selected_item = $row[0];
      $CsID = $row[1];
      $PaperVol = $row[2]; 
      $op_ans = $row[3];
      $P_b = $row[4];  
    }
    mysql_free_result($sql);
    //...................................................................  選出下一試題編號與試題參數
    $question=new Item_Structure4IRT($CsID, $selected_item, $PaperVol);  //呼叫試題圖檔
    //...................................................................end
  } 	
}
else
{  
  if($_SESSION["selected_item"]) //沒有輸入任何值，直接送出答案  
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
    $h_exam_S=$_SESSION["h_exam_S"];
    $type_id_e = $_SESSION["type_id_e"];
    $type_id_s = $_SESSION["type_id_s"];
    $type_id_t = $_SESSION["type_id_t"];
    $num_e = $_SESSION["num_e"];
    $item_length = $_SESSION["item_length"];
    $MIRT_d = $_SESSION["MIRT_d"];
    $error_m_d = $_SESSION["error_m_d"];
    $theta_count = $_SESSION["theta_count"];
    $P_b_theta = $_SESSION["P_b_theta"];
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
      $item_sn_s= explode("','",$_SESSION["select_item_id_A"]);
      $item_sn = $item_sn_s[$select_num-1];
      $tmp = $select_num;
    }
    //...................................................................end      
  }
  else //初始測驗時
  {
    //...................................................................找出選擇IRT施測類型    
    $sql=mysql_query("SELECT `type_id_e`,`type_id_s`,`type_id_t`,`num_e`,`item_length`,`test_time`
                      FROM `exam_paper_access_irt` WHERE (`cs_id`=$CsID) and (`paper_vol`=$PaperVol)");                                        
    while($row=mysql_fetch_array($sql))
    {
      $type_id_e = $row[0]; //  曝光率控制法  1.FI 2.ASHCOF_FI
      $type_id_s = $row[1]; //  選題法
      $type_id_t = $row[2]; //  能力估計法 1.MLE 2.EAP
      $num_e = $row[3]; //  更新曝光的人數
      $item_length = $row[4]; //  測驗最大長度
      $test_time = $row[5]; //  測驗最大長度
    }
    mysql_free_result($sql);  
    $_SESSION["type_id_e"] = $type_id_e;
    $_SESSION["type_id_s"] = $type_id_s;
    $_SESSION["type_id_t"] = $type_id_t;
    $_SESSION["num_e"] = $num_e;
    $_SESSION["item_length"] = $item_length;
    $_SESSION["test_time"] = $test_time;
    
    unset($_SESSION["select_ans"]); //將select_ans的session歸零
    unset($_SESSION["select_item_id"]);//將select_item_id的session歸零
    unset($_SESSION["select_P_a"]);
    unset($_SESSION["select_P_b"]);
    unset($_SESSION["select_P_c"]);
    
    $_SESSION["start_time"]=date("U");       //初始化時間
    $_SESSION["date"]= date("Y-m-d, H:i:s");  //開始測驗時間
    //...................................................................end
    $h_exam = 0;   //起始能力值
    $_SESSION["h_exam_S"] = "";
    $_SESSION["h_exam_A"] = "";
    $_SESSION["select_item_id_S"] = ""; 
    $_SESSION["select_item_id_A"] = "";
    $_SESSION["select_item_b_theta"] = "";
    $error_d =3;
    $tmp = 1; 
    //...................................................................決定向度
    $MIRT_d = 0;
    $sql=mysql_query("SELECT distinct `dim` FROM `concept_item_parameter` WHERE (`cs_id`=$CsID)");
    while($row=mysql_fetch_array($sql))
    {
      $MIRT_d++;
    }
    mysql_free_result($sql); 
    $_SESSION["MIRT_d"] = $MIRT_d; 
    //...................................................................決定向度end  
    //...................................................................設定並存儲各項度初始能力
    for($d=0;$d<$MIRT_d;$d++)
    {
      $_SESSION["h_exam_A"] .= $h_exam._SPLIT_SYMBOL;
    }
    //...................................................................設定各項度初始能力 end      
    //............................................................................. 判斷用 何種 選題法的程式
    if(($type_id_e == 1) and ($type_id_s ==1))
    {
      include("select_rule/MIRT/FI_1st.php"); //...................................使用FI進行選題
    }
    elseif(($type_id_e > 1) and ($type_id_s ==1))
    {
      include("select_rule/MIRT/FI_EP_1st.php"); //............................使用曝光率控制進行選題
    }
    //............................................................................. 判斷用 何種 選題法的程式...end
    $item_sn_s= explode("','",$_SESSION["select_item_id_A"]);
    $item_sn = $item_sn_s[0];
    $b_theta_e = explode("@XX@",$_SESSION["select_item_b_theta"]); 
    $P_b_theta = $b_theta_e[0];
    $sql=mysql_query("SELECT `item_num`,`cs_id`,`paper_vol`
                      FROM concept_item WHERE `item_sn` = $item_sn ");
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
  
  $sql=mysql_query("SELECT concept_item.op_ans,concept_item_parameter.a
                    ,concept_item_parameter.b,concept_item_parameter.c
                    FROM concept_item,concept_item_parameter 
                    WHERE concept_item.item_sn = $item_sn and concept_item_parameter.item_sn = $item_sn");	
  while($row=mysql_fetch_array($sql))
  {
    $op_ans = $row[0];
    $P_a = $row[1]; 
    $P_b = $row[2];
    $P_c = $row[3];    
  }
  mysql_free_result($sql);
  $question=new Item_Structure4IRT($CsID, $selected_item, $PaperVol); //呼叫試題圖檔
}
  
if($select_num == $tmp)
{  
  /*echo $_SESSION["select_item_b_theta"].'<br>';
  echo $_SESSION["select_item_id_A"].'<br>';
  echo $_SESSION["select_item_id_S"].'<br>';*/
  //echo $item_sn.'<br>';
  $error_d_all = explode("@XX@",$_SESSION["error_d_all"]);
  $error_d_time = 0;
  for($i=0;$i<count($error_d_all)-1;$i++)
  {
    if($error_d_all[$i]==0)
    {
      $error_d_time++;
    }
  }
  //...................................................................判斷測驗是否中止  
  if($select_num <=$item_length)
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
      echo "<input type=\"hidden\" name=\"cs_id\" value=".$CsID.">";
      echo "<input type=\"hidden\" name=\"CsID\" value=".$CsID.">";
      echo "<input type=\"hidden\" name=\"paper_vol\" value=".$PaperVol.">";
      echo "<input type=\"hidden\" name=\"PaperVol\" value=".$PaperVol.">";  
      echo "<input type=\"hidden\" name=\"select_num\" value=".$select_num.">";
      echo "<input type=\"hidden\" name=\"selected_item\" value=".$selected_item.">";      
      echo "<input type=\"hidden\" name=\"item_sn\" value=".$item_sn.">";
      echo "<input type=\"hidden\" name=\"P_a\" value=".$P_a.">";
      echo "<input type=\"hidden\" name=\"P_b\" value=".$P_b.">";
      echo "<input type=\"hidden\" name=\"P_c\" value=".$P_c.">";
      echo "<input type=\"hidden\" name=\"exam_type\" value=".$_SESSION["exam_type"].">";
      echo "<input name=\"op\" type=\"hidden\" value=\"modload\">";
      echo "<input name=\"name\" type=\"hidden\" value=\"IRT\">";    
      echo '<input name="file" type="hidden" value="'.$_REQUEST['file'].'">';
      echo '<input name="screen" type="hidden" value="all">';
      
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
      $_SESSION["P_b_theta"]=$P_b_theta;  
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
    include_once("record/result.php"); //...結果呈現 與 作答紀錄
  }
}
else
{
  echo "<br>測驗中，請勿重新整理視窗或回到上一頁，請重新參加測驗<P>";  
  //session_destroy(); //清除全部session
  $RedirectTo="modules.php?op=main";
	echo '<a href="'.$RedirectTo.'">[ 按此返回 ]</a>';
}

?>
