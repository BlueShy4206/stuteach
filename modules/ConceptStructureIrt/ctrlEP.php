<?php
require_once "HTML/QuickForm.php";
require_once "include/adp_API.php";

$module_name = basename(dirname(__FILE__));


OpenTable();
echo "<td><center><font class=\"title\"><b>試卷存取控制</b></font></center></td>";
CloseTable();
//-- 顯示主畫面
echo '<br>
	   <table width="95%" border="1" cellpadding="0" cellspacing="0">
		  <tr>
		    <td align="center" valign="top" bordercolor="#FFCC33">';
		    listCLASS();  //一進來的選單畫面
		    //debug_msg("第".__LINE__."行 _REQUEST ", $_REQUEST);
echo '  </td>
      </tr>
     </table>';
echo '<br>
	   <table width="95%" border="1" cellpadding="0" cellspacing="0">
		  <tr>
		    <td align="center" valign="top" bordercolor="#FFCC33">';
        if($_REQUEST['opt']=='EP2class'){
	       EP2class($_REQUEST['class']);
        }elseif($_REQUEST['opt']=='EPadd2class' && $_REQUEST['EPids']!=''){
	       EPadd2class($_REQUEST['EPids']);
        }elseif($_REQUEST['opt']=='delClassEP' && $_REQUEST['OpenEPids']!=''){
	       class_delEP($_REQUEST['OpenEPids']);
        }elseif(isset($_REQUEST['opt'])){
        //預設值（刪除或新增的功能選錯）
        $RedirectTo="Location: modules.php?op=modload&name=".$module_name."&file=ctrlEP";
	      Header($RedirectTo);
        }
        /*echo '1'.$_REQUEST['opt'].'<br>';
        echo '2'.$_REQUEST['class'].'<br>';
        echo '3'.$_REQUEST['EPids'].'<br>'; 
        echo '4'.$_REQUEST['OpenEPids'].'<br>';*/
echo   '</td>
      </tr>
     </table>';

function listCLASS(){
	global $dbh, $module_name;
	//echo $dbh;
	//echo $module_name;
	$form = new HTML_QuickForm('frmadduser','post',$_SERVER['PHP_SELF']);
	//-- 尋找目前已建立之班級，並初始化"關聯選單"
	$select1[0]='分區';
	$select2[0][0]='學校名稱';
	$select3[0][0][0]='年級';
	$select4[0][0][0][0]='班級';
	
	$sql = "select city_code, organization_id, grade, class from user_info WHERE city_code!=0 GROUP BY city_code, organization_id, grade, class";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$cc=$row['city_code'];
		$oi=$row['organization_id'];
		$gr=$row['grade'];
		$cl=$row['class'];
	
		$select1[$cc]=id2city($cc);
		$select2[$cc][$oi]=id2org($oi);
		$select3[$cc][$oi][$gr]=$gr."年";
		$select4[$cc][$oi][$gr][$cl]=$cl."班";
	}

	//-- 顯示選單
	//echo "☆★☆ 班級列表 ☆★☆<br>";
	$form->addElement('header', 'myheader', '<center> 班級列表 </canter>');  //標頭文字
	// Create the Element
	$sel =& $form->addElement('hierselect', 'class', '請選擇班級：');
	// And add the selection options
	$sel->setOptions(array($select1, $select2, $select3, $select4));
	
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file','ctrlEP');
	$form->addElement('hidden','opt','EP2class');
	$form->addRule('class', '「班級」不可空白！', 'nonzero',null, 'client', null, null);
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->display();
}


function EP2class($class){
	global $dbh, $module_name;
	
	$listCLASS='<a href="modules.php?op=modload&name='.$module_name.'&file=ctrlEP">選擇其他班級</a>';
	echo '<br>現在的班級是：'.id2city($class[0]).'&nbsp;'.id2org($class[1]).'&nbsp;&nbsp;'.$class[2].'年'.$class[3].'班&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<hr>';

	echo '<TABLE BORDER="1" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
          <FORM ACTION="modules.php?op=modload&name='.$module_name.'&file=ctrlEP" METHOD="POST">';
        echo '<tr>              
              <td bgcolor="#FFFFCC" align="center"  rowspan="2">
                <font size="3"><b>適性功能</b></font>
              </td>  
              <td bgcolor="#FFFFCC" VALIGN="TOP" align="center">';             
        echo '<TABLE BORDER="0" WIDTH="100%">
                <tr>              
                  <td align="center">試題反應模式<br>(a)</td>
                  <td align="center">能力估計法<br>(b)</td>
                  <td align="center">選題法<br>(c)</td>
                  <td align="center">曝光率控制<br>(d)</td>
                  <td align="center">更新曝光的<br>人數(e)</td>
                  <td align="center">測驗最大<br>長度(f)</td>
                </tr>';
                  
             // 0
             //............................................................................測驗最大長度
	           //echo ' ';
             /*echo '<select name="item_l">';
             echo '<option value="">測驗最大長度</option>';
	           $num_e_tmp = array(25,30,35,40,45,50,99);
	           $num_e_tmp1 = array(25,30,35,40,45,50,全);
             for ($i=0;$i<count($num_e_tmp);$i++){
		           echo '<option value="'.$num_e_tmp[$i].'">'.$num_e_tmp1[$i].'</option>';
	           }
             echo '</select>';  */
             
             //............................................................................測驗最大長度..end
             // 1
	           //............................................................................IRT選單
	           echo '<tr><td align="center">';
             echo '<select name="ExamType_irt">';  
             //echo '<option value="">試題反應模式</option>';
	           $sql="SELECT * FROM exam_type_irt ORDER BY type_id_irt";
	           $result = $dbh->query($sql);
	           while ($data = $result->fetchRow()) {
		          echo '<option value="'.$data['type_id_irt'].'">'.$data['type_id_irt'].'.'.$data['type_irt'].'</option>';
	           }
             echo '</select></td>';
             //...........................................................................IRT選單..end
             // 2
	           //............................................................................能力估計法選單
	           echo '<td align="center">';
             echo '<select name="ExamType_t">'; 
             //echo '<option value="">能力估計法</option>'; 
	           $sql="SELECT * FROM exam_type_irt_t ORDER BY type_id_t";
	           $result = $dbh->query($sql);
	           while ($data = $result->fetchRow()) {
		          echo '<option value="'.$data['type_id_t'].'">'.$data['type_id_t'].'.'.$data['type_t'].'</option>';
	           }
             echo '</select></td>';  
             //............................................................................能力估計法選單..end
             // 3
             //............................................................................選題法選單
	           echo '<td align="center">';
             echo '<select name="ExamType_s">';
             //echo '<option value="">選題法</option>';
	           $sql="SELECT * FROM exam_type_irt_s ORDER BY type_id_s";
	           $result = $dbh->query($sql);
	           while ($data = $result->fetchRow()) {
		           echo '<option value="'.$data['type_id_s'].'">'.$data['type_id_s'].'.'.$data['type_s'].'</option>';
	           }
             echo '</select></td>';  
             //............................................................................選題法選單..end
             
             // 4
             //............................................................................曝光率控制選單
	           echo '<td align="center">';
             echo '<select name="ExamType_e">';
             //echo '<option value="">曝光率控制</option>';
	           $sql="SELECT * FROM exam_type_irt_e ORDER BY type_id_e";
	           $result = $dbh->query($sql);
	           while ($data = $result->fetchRow()) {
		           echo '<option value="'.$data['type_id_e'].'">'.$data['type_id_e'].'.'.$data['type_e'].'</option>';
	           }
             echo '</select></td>';  
             //............................................................................曝光率控制選單..end
             // 5
             //............................................................................更新曝光的人數
	           echo '<td align="center">';
             echo '<select name="num_e">';
             //echo '<option value="">更新曝光的人數</option>';
	           $num_e_tmp = array(0,1,5,10,30,50,100,500,1000);
	           $num_e_tmp1 = array(無,1,5,10,30,50,100,500,1000);
             for ($i=0;$i<count($num_e_tmp);$i++){
		           echo '<option value="'.$num_e_tmp[$i].'">'.$num_e_tmp1[$i].'</option>';
	           }
             echo '</select></td>';  
             //............................................................................更新曝光的人數..end  
             
             echo '<td align="center"><input type="text" name="item_l" size=2 value="30">';
             echo '</td></tr></table>'; 
            
            
	           echo '<font size="3" face="arial">';	
	           echo '<INPUT TYPE="HIDDEN" NAME="city_code" VALUE="'.$class[0].'">';
	           echo '<INPUT TYPE="HIDDEN" NAME="school_id" VALUE="'.$class[1].'">';
	           echo '<INPUT TYPE="HIDDEN" NAME="grade" VALUE="'.$class[2].'">';
	           echo '<INPUT TYPE="HIDDEN" NAME="class" VALUE="'.$class[3].'">';
                   
	       echo '<tr>
	              <td bgcolor="#FFFFCC" >                
               </td>            
             </tr>';
        
        echo '<tr>
              <td bgcolor="#FFFFCC" align="center" WIDTH="10%">
               <font size="3"><b>尚未開放<br>試卷</b></font>
              </td>    
              <td bgcolor="#FFFFCC" VALIGN="TOP" align="center">
                <font size="3" face="arial">
                <SELECT NAME="EPids[]" SIZE="10" MULTIPLE>';  
                $sql="SELECT sn, exam_paper_id, type_id_irt, type_id_e, type_id_s, type_id_t, num_e, item_length FROM exam_paper_access_irt WHERE school_id='".$class[1]."' AND grade ='".$class[2]."' AND class='".$class[3]."' ORDER BY exam_paper_id";
	              $result = $dbh->query($sql);
	              while ($data = $result->fetchRow()) {
		              $current_EP[]=$data['exam_paper_id'].sprintf("%02d",$data['type_id_irt']).sprintf("%02d",$data['type_id_e']).sprintf("%02d",$data['type_id_s']).sprintf("%02d",$data['type_id_t']).sprintf("%04d",$data['num_e']).sprintf("%02d",$data['item_length']);  //已開放試卷及施測類型
		              $EP_sn[]=$data['sn'];
	              }
	              $sql = "SELECT distinct exam_paper_id FROM concept_item WHERE (exam_paper_id != -1) ";
	              for($i=0;$i<sizeof($current_EP);$i++){
		              $currEPid=substr($current_EP[$i],0,11);   //取出試卷id
		              $sql .= "AND (exam_paper_id != $currEPid) ";
	              }
	             $sql .= "ORDER BY exam_paper_id ASC";
	             $result = $dbh->query($sql);
	             while ($data = $result->fetchRow())	{
		            $paper_info=explode_ep_id($data['exam_paper_id']);
		            $paper_title=id2publisher($paper_info[0]).id2subject($paper_info[1]).'第'.$paper_info[2].'冊第'.$paper_info[3].'單元-卷'.$paper_info[4];
		            echo '<OPTION VALUE="'.$data['exam_paper_id'].'">'.$paper_title.'　　　　　　　　'.'</OPTION>\n';
	             }
	       echo '</SELECT><br>
	       <div align="center">新增或刪除
                  <select name="opt">
                    <option value="EPadd2class">新增 ↓</option>
                    <option value="delClassEP">↑ 刪除</option>
                  </select>         	              
	               <INPUT TYPE="SUBMIT" NAME="SUBMIT" VALUE="送出">
                </div> 
              </td>                        
            </tr>';
            
      
       echo '<tr>              
              <td bgcolor="#FFFFCC" align="center">
                <font size="3"><b>已開放<br>試卷</b></font>
              </td>';
                
       echo '<td bgcolor="#FFFFCC" VALIGN="TOP" align="center">
              <TABLE BORDER="0" CELLPADDING="5" CELLSPACING="0">
               <tr>
                  <td>';
              echo '<SELECT NAME="OpenEPids[]" SIZE="10" MULTIPLE>';
	           for($i=0;$i<sizeof($current_EP);$i++){
  		         $currEPid=substr($current_EP[$i],0,11);   //取出試卷id
  		         /*$exam_type_irt_id=substr($current_EP[$i],11,2);   //取出施測類型
      		     $exam_type_e_id=substr($current_EP[$i],13,2);   //取出IRT類型
	    	       $exam_type_s_id=substr($current_EP[$i],15,2);   //取出施測類型
		           $exam_type_t_id=substr($current_EP[$i],17,2);   //取出施測類型*/
		           $exam_type_irt_id=substr($current_EP[$i],12,1);   //取出IRT類型
      		     $exam_type_e_id=substr($current_EP[$i],14,1);   //取出曝光率控制類型
	    	       $exam_type_s_id=substr($current_EP[$i],16,1);   //取出選題法類型
		           $exam_type_t_id=substr($current_EP[$i],18,1);   //取出能力估計法類型
		           $num_e=substr($current_EP[$i],19,4);   //取出更新曝光的人數類型
		           $item_l=substr($current_EP[$i],23,2);   //取出施測類型
               //$num_e=substr($current_EP[$i],20,3);   //取出施測類型
		           $paper_info=explode_ep_id($currEPid);
      		     //$paper_title=id2publisher($paper_info[0]).id2subject($paper_info[1]).'第'.$paper_info[2].'冊第'.$paper_info[3].'單元-卷'.$paper_info[4].'【'.$exam_type=id2ExamType_t($exam_type_id).'】<br>';		
               //完整字串$paper_title=id2publisher($paper_info[0]).id2subject($paper_info[1]).'第'.$paper_info[2].'冊第'.$paper_info[3].'單元-卷'.$paper_info[4].'【'.$exam_type_irt=id2ExamType_irt($exam_type_irt_id).'/'.$exam_type_t=id2ExamType_t($exam_type_t_id).'/'.$exam_type_s=id2ExamType_s($exam_type_s_id).'/'.$exam_type_e=id2ExamType_e($exam_type_e_id).'】<br>';
               //$paper_title=id2publisher($paper_info[0]).id2subject($paper_info[1]).'第'.$paper_info[2].'冊第'.$paper_info[3].'單元-卷'.$paper_info[4].'【'.$exam_type_irt_id.'/'.$exam_type_t_id.'/'.$exam_type_s_id.'/'.$exam_type_e_id.'/'.$num_e.'】<br>';
	   	         $paper_title=id2publisher($paper_info[0]).id2subject($paper_info[1]).'第'.$paper_info[2].'冊第'.$paper_info[3].'單元-卷'.$paper_info[4].'【'.$exam_type_irt_id.'/'.$exam_type_t_id.'/'.$exam_type_s_id.'/'.$exam_type_e_id.'/'.$num_e.'/'.$item_l.'】<br>';
	   	         
	   	         //$paper_title=id2publisher($paper_info[0]).id2subject($paper_info[1]).'第'.$paper_info[2].'冊第'.$paper_info[3].'單元-卷'.$paper_info[4].'【'.$exam_type_t=id2ExamType_t($exam_type_t_id).'/'.$exam_type_e=id2ExamType_e($exam_type_e_id).'】';
               //$paper_title=id2publisher($paper_info[0]).id2subject($paper_info[1]).'第'.$paper_info[2].'冊第'.$paper_info[3].'單元-卷'.$paper_info[4].'【'.$exam_filename_t=id2Examfilename_t($exam_type_t_id).'/'.$exam_filename_e=id2Examfilename_e($exam_type_e_id).'】';    		
               $del='<a href="modules.php?op=modload&name='.$module_name.'&file=ctrlEP&opt=delClassEP&EP_sn='.$EP_sn[$i].'&class[0]='.$class[0].'&class[1]='.$class[1].'&class[2]='.$class[2].'&class[3]='.$class[3].'"><img src="'._ADP_URL.'images/delete.png" alt="刪除試題" border="0" align="texttop"></a>';
   		         //echo $del.'&nbsp;'.$paper_title."<br>";
	   	         echo '<OPTION VALUE="'.$EP_sn[$i].'">'.$paper_title.'</OPTION>\n';
	           }
	           echo '</SELECT><br>';
	           //echo "</td></tr></TABLE></FORM>";
	          echo '</td>
               <tr>
              </TABLE>
             </td>
            </tr>';
    //說明欄位
    echo '<tr>
            <td bgcolor="#FFFFCC" colspan="2">
            適性功能說明【a/b/c/d/e/f】';
          echo '<br>a：';
                $sql="SELECT * FROM exam_type_irt ORDER BY type_id_irt";
	              $result = $dbh->query($sql);
	              while ($data = $result->fetchRow()) {
		            echo $data['type_id_irt'].'='.$data['type_irt'].'；';
	              }
          echo  '<br>b：';
                $sql="SELECT * FROM exam_type_irt_t ORDER BY type_id_t";
	              $result = $dbh->query($sql);
	              while ($data = $result->fetchRow()) {
		            echo $data['type_id_t'].'='.$data['type_t'].'；';
	              }
	        echo  '<br>c：';
                $sql="SELECT * FROM exam_type_irt_s ORDER BY type_id_s";
	              $result = $dbh->query($sql);
	              while ($data = $result->fetchRow()) {
		            echo $data['type_id_s'].'='.$data['type_s'].'；';
	              }	        
          echo  '<br>d：';
                $sql="SELECT * FROM exam_type_irt_e ORDER BY type_id_e";
	              $result = $dbh->query($sql);
	              while ($data = $result->fetchRow()) {
		            echo $data['type_id_e'].'='.$data['type_e'].'；';
	              }	 
           echo  '<br>e：更新曝光的人數';
           echo  '<br>f：測驗最大長度';
    echo  ' </td>
          </tr>';
            
    echo '</FORM>
        </TABLE>';
}

function EPadd2class($EPids){
	global $dbh, $module_name;

	$sid=$_REQUEST['school_id'];
	$gid=$_REQUEST['grade'];
	$cid=$_REQUEST['class'];
	//echo "8".$cid;
  $con = 0;  //是否存入資料判斷條件
  $i = 0;
  if ($_REQUEST['ExamType_irt']==''){ $mirt_c[$i]=0;  //  $mirt_c[$i] 表示第i種選題條件 為0表示未設定
  }else{  $con++;$mirt_c[$i]=1;
  }
  $i++;
  if ($_REQUEST['ExamType_t']==''){ $mirt_c[$i]=0;    
  }else{  $con++;$mirt_c[$i]=1;
  }
  $i++;
  if ($_REQUEST['ExamType_s']==''){ $mirt_c[$i]=0;    
  }else{  $con++;$mirt_c[$i]=1;
  }
  $i++;
  if ($_REQUEST['ExamType_e']==''){ $mirt_c[$i]=0;    
  }elseif($_REQUEST['ExamType_e']==1){  $con++;$mirt_c[$i]=1;$_REQUEST['num_e']=0;$mirt_c[$i+1]=1;
  }else{if($_REQUEST['num_e']==''){$mirt_c[$i]=1;$mirt_c[$i+1]=0;
    }elseif($_REQUEST['num_e']==0){$mirt_c[$i]=1;$mirt_c[$i+1]=0;
    }else{$con++;$mirt_c[$i]=1;$mirt_c[$i+1]=1;
    }
  }
  $i++;  
  if($con!=$i)
  {   
    include "warn_windows.php";
  }
  else
  {
    while(list($null, $curr_EPid) = each($EPids)) {
		  $cs_id=EPid2CSid($curr_EPid);
		  $EP_info=explode_ep_id($curr_EPid);
		  $paper_vol=intval($EP_info[4]);
		  $query = 'INSERT INTO exam_paper_access_irt (cs_id, paper_vol, exam_paper_id, school_id, grade, class, type_id_irt, type_id_e, type_id_s, type_id_t, num_e, item_length) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)';
		  $data = array($cs_id, $paper_vol, $curr_EPid, $sid, $gid, $cid, $_REQUEST['ExamType_irt'], $_REQUEST['ExamType_e'], $_REQUEST['ExamType_s'], $_REQUEST['ExamType_t'], $_REQUEST['num_e'], $_REQUEST['item_l']);
		  
		  $result =$dbh->query($query, $data);
		  if (PEAR::isError($result)) {
			 echo "錯誤訊息：".$result->getMessage()."<br>";
			 echo "錯誤碼：".$result->getCode()."<br>";
			 echo "除錯訊息：".$result->getDebugInfo()."<br>";
		  }else{
			 $RedirectTo="Location: modules.php?op=modload&name=".$module_name."&file=ctrlEP&opt=EP2class&class[0]=".$_REQUEST['city_code']."&class[1]=".$sid."&class[2]=".$gid."&class[3]=".$cid;
			 Header($RedirectTo);
		  }
	 }
  }        
}
function class_delEP($EP_sn){
	global $dbh, $module_name;
   //debug_msg("第".__LINE__."行 _REQUEST ", $_REQUEST);
   //debug_msg("第".__LINE__."行 EP_sn ", $EP_sn);
   //die();
	$ccd=$_REQUEST['city_code'];
	$sid=$_REQUEST['school_id'];
	$gid=$_REQUEST['grade'];
	$cid=$_REQUEST['class'];
   for($i=0;$i<count($EP_sn);$i++){
      $sql="DELETE FROM exam_paper_access_irt WHERE sn='".$EP_sn[$i]."' AND school_id='".$sid."'";
      $result = $dbh->query($sql);
      if (PEAR::isError($result)) {
         echo "錯誤訊息：".$result->getMessage()."<br>";
         echo "錯誤碼：".$result->getCode()."<br>";
         echo "除錯訊息：".$result->getDebugInfo()."<br>";
         die();
      }
   }
   $RedirectTo="Location: modules.php?op=modload&name=".$module_name."&file=ctrlEP&opt=EP2class&class[0]=".$ccd."&class[1]=".$sid."&class[2]=".$gid."&class[3]=".$cid;
	 Header($RedirectTo);	
}
?>

