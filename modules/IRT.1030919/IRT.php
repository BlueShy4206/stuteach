<?php
require_once "include/adp_API.php";
require_once 'Date.php';
require_once 'db.php';

$module_name= basename(dirname(__FILE__));
$file = basename(__FILE__);
list($SubmitFile, $FileType)=explode(".", $file);
//debug_msg("第".__LINE__."行 _REQUEST ", $_REQUEST);

$CsID=$_POST['cs_id'];
$PaperVol=$_POST['paper_vol'];  
$_SESSION["exam_type"]=$_POST['exam_type'];
$ep_id=$_POST['cs_id'].sprintf("%02d",$_POST['paper_vol']);
//debug_msg("第".__LINE__."行 _SESSION ", $_SESSION);
//debug_msg("第".__LINE__."行 _POST ", $_POST);

if(isset($_POST['test_num'])) {
	for($i=1;$i<=$_POST['test_num'];$i++){
		$xi="user_answer".$i;
		if(isset($_POST["$xi"])){
			$_POST['user_answer'].=$_POST["$xi"]._SPLIT_SYMBOL ;
	 	}else{
			unset($_POST['user_answer']);
			break;
		}
	}
}

//...................................................................判斷是否第一次作答
if((isset($_POST['user_answer']))&&(isset($_SESSION["selected_item"]))){
	$type_id_e = $_SESSION["type_id_e"];
	$type_id_s = $_SESSION["type_id_s"];
	$type_id_t = $_SESSION["type_id_t"];
	$num_e = $_SESSION["num_e"];
	$item_length = $_SESSION["item_length"];  
	//  $_POST['select_num']++;
	$select_num=$_POST['select_num']; //已做過題數

	$CsID=$_POST['CsID'];  //考卷的ID
	$selected_item=$_POST['selected_item'];  // 該卷編號
	$PaperVol=$_POST['PaperVol'];  //卷  
	//  $item_sn=$_POST['item_sn'];
	$item_sn=explode(_SPLIT_SYMBOL,$_POST['item_sn']);
	$a = $_POST['P_a'];
	$b = $_POST['P_b'];
	$c = $_POST['P_c'];
	$MIRT_d = $_SESSION["MIRT_d"];
	$next_one = 0; //條件終止前設為0
  // _SPLIT_SYMBOL-->@XX@
	$_SESSION["rec_user_answer"].=$_POST["user_answer"]; 
	//...$_SESSION["select_item_id"].= $item_sn."','";	//字串(前一次所有做過的試題id組成)
	$_SESSION["select_P_a"].= $a;	//字串(前一次所有做過的試題參數 a 組成)
	$_SESSION["select_P_b"].= $b;	//字串(前一次所有做過的試題參數 b 組成)
	$_SESSION["select_P_c"].= $c;	//字串(前一次所有做過的試題參數 c 組成)
	$_SESSION["PaperVol"]=$PaperVol;
	$h_exam=$_POST['h_exam']; //上一次能力值
	$select_item_id_S = $_SESSION["select_item_id_S"];
	$select_item_id_A = $_SESSION["select_item_id_A"];
  
	//$question=new Item_Structure4IRT($CsID,$selected_item,$PaperVol);
	//..............從資料撈出item_sn試題流水號
	//debug_msg("第".__LINE__."行 _POST ", $_POST);
	//debug_msg("第".__LINE__."行 item_sn ", $item_sn);
	for($i=0;$i<$_POST['test_num'];$i++){
		$sql="SELECT `op_ans` FROM `concept_item` 
		                WHERE `item_sn`= $item_sn[$i]";
		$result = $dbh->query($sql);
		while ($row = $result->fetchRow()){
			$questions[$i] = $row[op_ans];    
		}
	}

	//.............................................end
	//.....................................記錄上一筆作答情形
	$user_answers = explode(_SPLIT_SYMBOL,$_POST['user_answer']);
	for($i=0;$i<$_POST['test_num'];$i++){
		if($user_answers[$i]==$questions[$i]){   //答對時
			//字串(前一次所有做過的試題ans,再加上本次答對1) 
			$_SESSION["select_ans"].= "1"._SPLIT_SYMBOL;
		}else{   //答錯時 
			//字串(前一次所有做過的試題ans,再加上本次答對0)
		 	$_SESSION["select_ans"].= "0"._SPLIT_SYMBOL;
		}
	}

	//前一次全部作答情況(對錯,a,b,c參數)
	$X_item = explode(_SPLIT_SYMBOL,$_SESSION["select_ans"]);
	$A_item = explode(_SPLIT_SYMBOL,$_SESSION["select_P_a"]);
	$B_item = explode(_SPLIT_SYMBOL,$_SESSION["select_P_b"]);
	$C_item = explode(_SPLIT_SYMBOL,$_SESSION["select_P_c"]); 
	//  debug_msg("第".__LINE__."行 data ", $X_item);
	//  debug_msg("第".__LINE__."行 data ", $A_item);
	//  debug_msg("第".__LINE__."行 data ", $B_item);
	//  debug_msg("第".__LINE__."行 data ", $C_item);
	//......................由資料庫檢查已做過幾題
	$sql="SELECT `a` FROM `concept_item_parameter` WHERE `item_sn` 
	                in ('".$select_item_id_A."')";
	$nn =0;
	$result = $dbh->query($sql);
	while ($data = $result->fetchRow())	{
		$nn++;  
	}
	$tmp = $nn; //暫存要做第幾題
	//...................................................................end
	$h_exam_b=$h_exam;
  
	//....................................... 判斷用 何種 能力估計的程式
	//........................................function_U
	//  echo $select_num;
	if($type_id_t == 1){
    	include("select_rule/IRT/MLE.php"); //...使用MLE進行能力估計
	}elseif($type_id_t == 2){
    	include("select_rule/IRT/EAP.php"); //...使用EAP進行能力估計
	}
	//............. 判斷用 何種 能力估計的程式...end
  
	//..................... 判斷用 何種 選題法的程式
	if(($type_id_e == 1) and ($type_id_s ==1)){
		include("select_rule/IRT/FI.php"); //使用無曝光控制 與 FI進行選題
	}elseif(($type_id_e > 1) and ($type_id_s ==1)){
		include("select_rule/IRT/FI_EP.php"); //使用曝光率控制 與 FI進行選題
	}
	//................. 判斷用 何種 選題法的程式...end

	//............  選出下一試題編號與試題參數
//	echo $select_num;
	for($i=1;$i<=$n;$i++){
		$sql=mysql_query("SELECT concept_item.item_num,concept_item.cs_id,concept_item.paper_vol,
		                    concept_item.op_ans,concept_item_parameter.a,concept_item_parameter.b,concept_item_parameter.c,concept_item_parameter.sub FROM concept_item,concept_item_parameter 
		                    WHERE concept_item.item_sn = '$testlet_item_sn[$i]' and concept_item_parameter.item_sn = '$testlet_item_sn[$i]'");
		while($row=mysql_fetch_array($sql)){  
	    	$testlet_selected_item[$i] = $row[0];
	    	$CsID = $row[1];
	    	$PaperVol = $row[2]; 
	    	$op_anss[$i] = $row[3];
	    	$P_aa[$i] = $row[4];
			$P_bb[$i] = $row[5];
			$P_cc[$i] = $row[6]; 
			$P_ssub[$i] = $row[7];   
		}
		mysql_free_result($sql);    
		  
		$question[$i]=new Item_Structure4IRT($CsID, $testlet_selected_item[$i], $PaperVol);  //呼叫試題圖檔
  	}

	//...................................................................end
}else{
	//沒有輸入任何值，直接送出答案 
	if($_SESSION["selected_item"]){
	    //$item_sn=$_SESSION["item_sn"];
	    $item_sn = explode(_SPLIT_SYMBOL,$_SESSION["item_sn"]);
	    $CsID=$_SESSION["CsID"];
	    $PaperVol=$_SESSION["PaperVol"];
	    //$selected_item=$_SESSION["selected_item"];
	    $selected_item = explode(_SPLIT_SYMBOL,$_SESSION["selected_item"]);
		//debug_msg("第".__LINE__."行 data ", $item_sn);
		//debug_msg("第".__LINE__."行 data ", $selected_item);
	    $select_num=$_SESSION["select_num"];
	    $h_exam_b=$_SESSION["h_exam_b"];
	    $h_exam=$_SESSION["h_exam"];
	    $error_d=$_SESSION["error_d"];
	    $h_exam_A=$_SESSION["h_exam_A"];
	    $select_item_id_A =$_SESSION["select_item_id_A"];
	    $select_item_sub_A =$_SESSION["select_item_sub_A"];
	    $select_item_id_S =$_SESSION["select_item_id_S"];
	    $h_exam_S=$_SESSION["h_exam_S"];
	    $type_id_e = $_SESSION["type_id_e"];
	    $type_id_s = $_SESSION["type_id_s"];
	    $type_id_t = $_SESSION["type_id_t"];
	    $num_e = $_SESSION["num_e"];
	    $item_length = $_SESSION["item_length"];
	    $MIRT_d = $_SESSION["MIRT_d"];
	    $n=$_SESSION["test_num"];

		//由資料庫檢查已做過幾題
	    $sql=mysql_query("SELECT `a` FROM `concept_item_parameter` WHERE `item_sn` 
	                      in ('".$select_item_id_A."')");
	    $nn =0;
	    while($row=mysql_fetch_array($sql)){    
			$nn++;  
	    }
	    mysql_free_result($sql);
	    $tmp = $nn; //暫存要做第幾題
	    for($i=1;$i<=$n;$i++){
			$testlet_item_sn[$i]=$item_sn[$i-1];
			$testlet_selected_item[$i]=$selected_item[$i-1];
		}
	    //.............................end      
	}else{
		//初始測驗時
	    //..........................找出選擇IRT施測類型
	    $select_num=0;
	    $tmp=0;
	    $AuthCourseID=$_SESSION['AuthCourseID'];
	    $sql="SELECT * FROM `exam_course_access_irt` WHERE (`cs_id`=$CsID) and (`course_id`=$AuthCourseID) and (`paper_vol`=$PaperVol)";
	    $result = $dbh->query($sql);
		while ($row = $result->fetchRow()) {
			$type_id_e = $row[type_id_e]; //  曝光率控制法  1.FI 2.ASHCOF_FI
			$type_id_s = $row[type_id_s]; //  選題法
			$type_id_t = $row[type_id_t]; //  能力估計法 1.MLE 2.EAP
			$num_e = $row[num_e]; //  更新曝光的人數
			$item_length = $row[item_length]; //  測驗時間
			$test_time = $row[test_time]; //  題組測驗數量
	    }
	    $_SESSION["type_id_e"] = $type_id_e;
	    $_SESSION["type_id_s"] = $type_id_s;
	    $_SESSION["type_id_t"] = $type_id_t;
	    $_SESSION["num_e"] = $num_e;
	    $_SESSION["item_length"] = $item_length;
	    $_SESSION["test_time"] = $test_time;
	    $_SESSION["test_time_sec"]=$_SESSION["test_time"]*60;
	    $_SESSION["item_length_done"] =0;
	    unset($_SESSION["select_ans"]); //將select_ans的session歸零
	    unset($_SESSION["select_item_id"]);//將select_item_id的session歸零
	    unset($_SESSION["select_P_a"]);
	    unset($_SESSION["select_P_b"]);
	    unset($_SESSION["select_P_c"]);
	    
	    $_SESSION["start_time"]=date("U");       //初始化時間
	    $_SESSION["date"]= date("Y-m-d, H:i:s");  //開始測驗時間
	    //......................................end
	    $h_exam = 0;   //起始能力值
	    $_SESSION["h_exam_S"] = "";
	    $_SESSION["h_exam_A"] = $h_exam._SPLIT_SYMBOL;
	    $_SESSION["select_item_id_S"] = ""; 
	    $_SESSION["select_item_id_A"] = "";
	    $_SESSION["select_item_sub_A"] = "0@XX@0@XX@0@XX@0@XX@0@XX@0@XX@0@XX@0@XX@0@XX@";
	    $error_d =3;
	
	    //.........................決定向度
	    $MIRT_d = 0;
	    $sql=mysql_query("SELECT distinct `dim` FROM `concept_item_parameter` WHERE (`cs_id`=$CsID)");
	    while($row=mysql_fetch_array($sql)){
			$MIRT_d++;
	    }
	    mysql_free_result($sql); 
	    $_SESSION["MIRT_d"] = $MIRT_d; 
	    //...................................決定向度end     
	    //.................................. 判斷用 何種 選題法的程式
	    if(($type_id_e == 1) and ($type_id_s ==1)){
			include("select_rule/IRT/FI.php"); //....使用FI進行選題
	    }elseif(($type_id_e > 1) and ($type_id_s ==1)){
			include("select_rule/IRT/FI_EP.php"); //使用曝光率控制進行選題
	    }
	    //........... 判斷用 何種 選題法的程式...end
	    //debug_msg("第".__LINE__."行 n ", $n);
		for($i=1;$i<=$n;$i++){
			$sqlstr="SELECT `item_num`,`cs_id`,`paper_vol`
	              FROM concept_item WHERE `item_sn` = '$testlet_item_sn[$i]' ";
	        //debug_msg("第".__LINE__."行 sqlstr ", $sqlstr);
			$sql=mysql_query($sqlstr);
			while($row=mysql_fetch_array($sql)){  
				$testlet_selected_item[$i] = $row[0];
				$CsID = $row[1];
				$PaperVol = $row[2]; 
				//debug_msg("第".__LINE__."行 testlet_selected_item ", $testlet_selected_item);
	   		}
	   	}
		mysql_free_result($sql);   
		$_SESSION['StartTimestamp']=time();
		$_SESSION['StopTimestamp']=$_SESSION['StartTimestamp']+$_SESSION["test_time_sec"];    
	}
	
	for($i=1;$i<=$n;$i++){
		$sql=mysql_query("SELECT concept_item.op_ans,concept_item_parameter.a
	                    ,concept_item_parameter.b,concept_item_parameter.c,concept_item_parameter.sub 
	                    FROM concept_item,concept_item_parameter 
	                    WHERE concept_item.item_sn = '$testlet_item_sn[$i]' and concept_item_parameter.item_sn = '$testlet_item_sn[$i]'");	
		while($row=mysql_fetch_array($sql)){
			$op_anss[$i] = $row[0];
			$P_aa[$i] = $row[1]; 
			$P_bb[$i] = $row[2];
			$P_cc[$i] = $row[3];
			$P_ssub[$i] = $row[4];
		}
		mysql_free_result($sql);   
		$question[$i]=new Item_Structure4IRT($CsID, $testlet_selected_item[$i], $PaperVol); //呼叫試題圖檔
	}
}
  //debug_msg("第".__LINE__."行 question ", $question);
  //debug_msg("第".__LINE__."行 n ", $n);
  //debug_msg("第".__LINE__."行 _SESSION ", $_SESSION);
  //debug_msg("第".__LINE__."行 select_num ", $select_num);
  //debug_msg("第".__LINE__."行 tmp ", $tmp);
if($select_num == $tmp){
	//判斷測驗是否中止
	$sql= mysql_query("SELECT sub_test_num FROM exam_paper_subscale WHERE exam_paper_id='".$ep_id."' ");
	while($row=mysql_fetch_array($sql)){
		$select_item_sub_S_tmp=$row[0];
	}
	$select_item_sub_S_tmp=explode(_SPLIT_SYMBOL,$select_item_sub_S_tmp);
  	$test_end=array_sum($select_item_sub_S_tmp);
  	//debug_msg("第".__LINE__."行 select_item_sub_S_tmp ", $select_item_sub_S_tmp);
  	//debug_msg("第".__LINE__."行 test_end ", $test_end);

	if($select_num <=$test_end){
		for($x=1;$x<=$n;$x++){
			//echo $_SESSION["select_item_sub_A"];
			$tableH=0;
			$question_select_pic=$question[$x]->get_item_select_pic();
			$a = $select_num - $n +$x; 
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
	                <td width="50%" scope="col" align="left">第'.$a.'題【'.$testlet_selected_item[$x].'】</td>';
	        if(isset($_SESSION[test_time]) and intval($_SESSION[test_time])>0){
				echo '<td width="50%" scope="col" align="right">剩餘時間：<font color="#a52a2a"><strong><span id="span_timer"></span></strong></font></td>';
			}
			echo '</tr>
	            </table></td>
	          </tr>';
			if(isset($showfig)){		unset($showfig);	}
			$PImgProp['item_filename']=GetImageSize($_SESSION['cs_path'].$question[$x]->item_filename);
			$showfig=explode(".", $question[$x]->item_filename);
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

			$ii=$question[$x]->get_item_select_num();
			for($i=0;$i<$ii;$i++){
	        	$PImgProp['op_pieces'.$i]=GetImageSize($_SESSION['cs_path'].$question[$x]->op_pieces[$i]);
	        	$tableH+=$PImgProp['op_pieces'.$i][1];
			}
			$tableH+=56;
			echo '<table width="100%" height="'.$tableH.'" border="0" cellpadding="2" cellspacing="2" class="line01">';
			$the_user_answer = "user_answer".$x;
			$ii=$question[$x]->get_item_select_num();
			for($i=0;$i<$ii;$i++){
				if(isset($showfig)){		unset($showfig);	}
				$showfig=explode(".", $question[$x]->op_pieces[$i]);
				$showfig[0]=str2compiler($showfig[0]);
				echo "<tr><td width=\"50\" align=\"center\" scope=\"col\"><input type=\"radio\" name=\"$the_user_answer\" value=\"".($i+1)."\"></td><td width=\"650\" align=\"left\"  class=\"s_title\" height=\"".$PImgProp['op_pieces'.$i][1]."\"><img border=\"0\" src=\"viewfig.php?list=".$showfig[0]."&tpp=".$showfig[1]."\"></td></tr>\n";
			}
			echo "</table>";
			echo '</td></tr></table>';
			$selected_itemm .= $testlet_selected_item[$x]._SPLIT_SYMBOL;
			$item_snn .= $testlet_item_sn[$x]._SPLIT_SYMBOL;
			$P_a.=$P_aa[$x]._SPLIT_SYMBOL;
			$P_b.=$P_bb[$x]._SPLIT_SYMBOL;
			$P_c.=$P_cc[$x]._SPLIT_SYMBOL;
			$P_sub.=$P_ssub[$x]._SPLIT_SYMBOL;
			$op_ans.=$op_anss[$x]._SPLIT_SYMBOL;
		}

		echo "<input type=\"hidden\" name=\"h_exam\" value=".$h_exam.">"."\n";
		echo "<input type=\"hidden\" name=\"cs_id\" value=".$CsID.">"."\n";
		echo "<input type=\"hidden\" name=\"CsID\" value=".$CsID.">"."\n";
		echo "<input type=\"hidden\" name=\"paper_vol\" value=".$PaperVol.">"."\n";
		echo "<input type=\"hidden\" name=\"PaperVol\" value=".$PaperVol.">"."\n";
		echo "<input type=\"hidden\" name=\"select_num\" value=".$select_num.">"."\n";
		echo "<input type=\"hidden\" name=\"selected_item\" value=".$selected_itemm.">"."\n";     
		echo "<input type=\"hidden\" name=\"item_sn\" value=".$item_snn.">"."\n";
		echo "<input type=\"hidden\" name=\"P_a\" value=".$P_a.">"."\n";
		echo "<input type=\"hidden\" name=\"P_b\" value=".$P_b.">"."\n";
		echo "<input type=\"hidden\" name=\"P_c\" value=".$P_c.">"."\n";
		echo "<input type=\"hidden\" name=\"test_num\" value=".$n.">"."\n";
		echo "<input type=\"hidden\" name=\"exam_type\" value=".$_SESSION["exam_type"].">"."\n";
		echo "<input name=\"op\" type=\"hidden\" value=\"modload\">"."\n";
		echo "<input name=\"name\" type=\"hidden\" value=\"IRT\">"."\n";   
		echo '<input name="file" type="hidden" value="'.$_REQUEST['file'].'">'."\n";
		echo '<input name="screen" type="hidden" value="all">'."\n";
		echo "<center><input type=\"submit\" value=\"選擇完畢，進入下一題\" name=\"submit\"  class=\"butn01\">\n";
		echo '</form>';
		echo "<table>
		    <center>
		    <tr>
		    <td><div align=\"center\">";
		if($user_data->access_level==9){
        	if (($select_num)>1){
          		echo "前一次能力估計值".$h_exam_b."　能力估計值".$h_exam."　前後能力估計的差距".$error_d;
        	}
        	$ShowInfo="<P>ans:".$op_ans."　鑑別度".$P_a."　難度".$P_b."　猜測度".$P_c."　指標".$P_sub;
			$ShowInfo=str_replace(_SPLIT_SYMBOL, "", $ShowInfo);

        	echo $ShowInfo;
		}
		echo "</div>
          </td>
          </tr>
          </center>
          </table>";          
		//....暫存資料，當沒有送出答案時，所以給的資料
		$_SESSION["CsID"]=$CsID;
		$_SESSION["PaperVol"]=$PaperVol;
		$_SESSION["selected_item"]=$selected_itemm;
		$_SESSION["item_sn"]=$item_snn;
		$_SESSION["select_num"]=$select_num;
		$_SESSION["h_exam_b"]=$h_exam_b;
		$_SESSION["h_exam"]=$h_exam;
		$_SESSION["error_d"]=$error_d;
		$_SESSION["test_num"]=$n;        
		//.................end  
  
		$NowtimeStamp=time();
		//debug_msg("第".__LINE__."行 NowtimeStamp ", $NowtimeStamp);
		//debug_msg("第".__LINE__."行 StopTimestamp ", $_SESSION[StopTimestamp]);
		//debug_msg("第".__LINE__."行 test_time_sec ", $_SESSION[test_time_sec]);
		if($NowtimeStamp>=$_SESSION['StopTimestamp'] and $_SESSION["test_time_sec"]>0){
			$next_one = 1; //結束測驗
		}

	}else{ 
		$next_one = 1; //結束測驗
		$_SESSION["select_num"]=$select_num;
	}
	//debug_msg("第".__LINE__."行 next_one ", $next_one);
	if($next_one==1){ 
	//die();   
		include_once("record/result.php"); //...結果呈現 與 作答紀錄
	}

}else{
	echo "<br>測驗中，請勿重新整理視窗或回到上一頁，請重新參加測驗<P>";  
	//session_destroy(); //清除全部session
	$RedirectTo="modules.php?op=main";
	echo '<a href="'.$RedirectTo.'">[ 按此返回 ]</a>';
}



if(isset($_SESSION[test_time]) and intval($_SESSION[test_time])>0){
	$redirectURL=_ADP_URL."modules.php?op=modload&name=".$module_name."&file=".$SubmitFile."&screen=all&h_exam=".$h_exam."&cs_id=".$CsID."&CsID=".$CsID."&paper_vol=".$PaperVol."&select_num=".$select_num."&selected_item=".$selected_itemm."&item_sn=".$item_snn."&P_a=".$P_a."&P_b=".$P_b."&P_c=".$P_c."&test_num=".$n."&exam_type=".$_SESSION["exam_type"];

	//$redirectURL="Location: modules.php?op=modload&name=".$module_name."&file=".$SubmitFile."&opt=EP2course&course[0]=".$CourseID[0]."&course[1]=".$CourseID[1]."&course[2]=".$CourseID[2];
	//$redirectURL="";
	//debug_msg("第".__LINE__."行 _POST ", $_POST);

	$ItemTestTime=$_SESSION['StopTimestamp']-time();
	echo '
	<script language="JavaScript1.3">
		var timer = 0;
		var mytime = '.$ItemTestTime.';
		var t1 = (new Date()).getTime()+0;
		counter();
		//倒數計時
		function counter(){	
			if(mytime < 0){
				//alert("時間到！請按下「確定」");
				//var t3=t2-t1;
				window.location.href = "'.$redirectURL.'";
				
			}else{
				var sec=mytime%60;
				var min=(mytime - sec)/60;
				document.getElementById(\'span_timer\').innerHTML = min+"分"+sec+"秒<br><br>";
				//暫停一秒
				setTimeout("counter()", 1000);
	
				//倒數
				mytime--;
				timer++;
			}
		}

	</script>';
	//echo $ptr;
}


?>
