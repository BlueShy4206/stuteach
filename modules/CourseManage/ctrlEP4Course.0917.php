<?php
require_once "HTML/QuickForm.php";
require_once "include/adp_API.php";
require_once "CourseData.php";

if($user_data->access_level<=70){
	Header("Location: index.php");
}

$module_name = basename(dirname(__FILE__));
$SubmitFile=basename(__FILE__);
$SubmitFile=str_replace(".php", "", $SubmitFile);
//-- 顯示主畫面
CM_table_header($module_name);
//debug_msg(__LINE__."行  _REQUEST", $_REQUEST);
//-- 顯示主畫面
echo '<br>
	<table width="95%" border="1" cellpadding="0" cellspacing="0">
		<tr>
		<td align="center" valign="top" bordercolor="#FFCC33">';
		//listEP();
		listCourse();  //一進來的選單畫面
		//debug_msg("第".__LINE__."行 _REQUEST ", $_REQUEST);
echo '</td></tr></table>';
echo '<br>
	<table width="95%" border="1" cellpadding="0" cellspacing="0">
		<tr>
		<td align="center" valign="top" bordercolor="#FFCC33">';

if($_REQUEST['opt']=='EP2course'){
	//EP2course($_REQUEST['ep']);
	EP2course($_REQUEST['course']);

}elseif($_REQUEST['opt']=='EPadd2Course' && $_REQUEST['EPids']!=''){
	EPadd2course($_REQUEST['course'], $_REQUEST[EPids], $_REQUEST[ExamType], $_REQUEST[TestTime]);
}elseif($_REQUEST['opt']=='delCourseEP' && $_REQUEST['OpenEPids']!=''){
	course_delEP($_REQUEST['OpenEPids'], $_REQUEST['course']);
}elseif(isset($_REQUEST['opt'])){
	//預設值（刪除或新增的功能選錯）
	$RedirectTo="Location: modules.php?op=modload&name=".$module_name."&file=".$SubmitFile;
	Header($RedirectTo);
}

echo '</td></tr></table>';

function listCourse(){
	global $dbh, $module_name, $SubmitFile;

	$form = new HTML_QuickForm('frmadduser','post',$_SERVER['PHP_SELF']);
	//-- 尋找目前已建立之課程，並初始化"關聯選單"
	$select1[0]='學年度';
	$select2[0][0]='學期';
	$select3[0][0][0]='課程';

	$sql = "select * from course GROUP BY year,seme,name ";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$ye=$row['year'];
		$se=$row['seme'];
		$name=$row['name'];
		$cid=$row['course_id'];

		$select1[$ye]=$ye."學年度";
		$select2[$ye][$se]="第".$se."學期";
		$select3[$ye][$se][$cid]=$name;
	}

	//-- 顯示選單
	//echo "☆★☆ 課程列表 ☆★☆<br>";
	$form->addElement('header', 'myheader', '<center>☆★☆ 課程與試卷存取控制 ☆★☆</canter>'); 
	// Create the Element
	$sel =& $form->addElement('hierselect', 'course', '請選擇課程：');
	// And add the selection options
	$sel->setOptions(array($select1, $select2, $select3));
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file',$SubmitFile);
	$form->addElement('hidden','opt','EP2course');
	$form->addRule('course', '「課程」不可空白！', 'nonzero',null, 'client', null, null);
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->display();
}


function listEP(){
	global $dbh, $module_name, $SubmitFile;

	$form = new HTML_QuickForm('frmadduser','post',$_SERVER['PHP_SELF']);
	//-- 初始化"關聯選單"
	$select1[0]='版本';
	$select2[0][0]='領域';
	$select3[0][0][0]='冊別';
	$select4[0][0][0][0]='單元名稱';
	$select5[0][0][0][0][0]='卷別';

	//$sql = "select a.exam_paper_id from concept_item a, concept_info_plus b WHERE  a.cs_id=b.cs_id GROUP BY exam_paper_id ";
	$sql = "select exam_paper_id from concept_item GROUP BY exam_paper_id ";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()){
		$EPid=$row[exam_paper_id];
		$cs_id=EPid2CSid($EPid);
		$EP_info=explode_ep_id($EPid);
		$paper_vol=intval($EP_info[4]);
		$pid=intval($EP_info[0]);
		$sid=intval($EP_info[1]);
		$vid=intval($EP_info[2]);
		$uid=intval($EP_info[3]);
		$subject=id2subject($sid);
		$paper_vol=intval($EP_info[4]);
		$select1[$pid]=id2publisher($pid);
		$select2[$pid][$sid]=$subject;
		$select3[$pid][$sid][$vid]='第'.$vid.'冊';
		$select4[$pid][$sid][$vid][$uid]='第'.$uid.'單元-'.id2csname($cs_id);
		$select5[$pid][$sid][$vid][$uid][$paper_vol]='卷'.$paper_vol;
	}

	//-- 顯示選單
	//echo "☆★☆ 課程列表 ☆★☆<br>";
	$form->addElement('header', 'myheader', '<center>☆★☆ 課程與試卷存取控制 ☆★☆</canter>'); 
	// Create the Element
	$sel =& $form->addElement('hierselect', 'ep', '請選擇試卷：');
	// And add the selection options
	$sel->setOptions(array($select1, $select2, $select3, $select4, $select5));
	$form->addElement('hidden','op','modload');
	$form->addElement('hidden','name',$module_name);
	$form->addElement('hidden','file',$SubmitFile);
	$form->addElement('hidden','opt','EP2course');
	$form->addRule('course', '「課程」不可空白！', 'nonzero',null, 'client', null, null);
	$form->addElement('submit','btnSubmit','選擇完畢，送出！');
	$form->display();
}


function EP2course($course){
	global $dbh, $module_name, $SubmitFile;
	$CO=new CourseData($course[2]);

	//debug_msg(__LINE__."行  ep", $ep);

	//$listCOURSE='<a href="modules.php?op=modload&name='.$module_name.'&file='.$SubmitFile.'">選擇其他課程</a>';
	echo '<br>現在的課程是：<font color=red><b>'.$CO->FullName.'</b></font><hr>';

	$sql="SELECT * FROM course ORDER BY year,seme,name ";
	//debug_msg(__LINE__."行  sql", $sql);
	$result = $dbh->query($sql);
	$IDcount=0;
	while ($data = $result->fetchRow()) {
		$AllCourseID[$IDcount]=$data['course_id'];
		$AllCourseName[$data['course_id']]=$data[year]."學年度第".$data[seme]."學期-".$data[name];
		$IDcount++;
	}
	$sql="SELECT sn, course_id FROM exam_course_access_irt WHERE exam_paper_id ='".$EPid."' ORDER BY year,seme,course_id";
	//debug_msg(__LINE__."行  sql", $sql);
	$result = $dbh->query($sql);
	$IN=0;
	while ($data = $result->fetchRow()) {
		$InCourseID[$IN]=$data['course_id'];
		$InCourseSN[$data['course_id']]=$data['sn'];
		$IN++;
	}
	if($IN==0){
		$OutCourseID=$AllCourseID;
	}elseif($IDcount==0){
		die('目前沒有任何課程！請先新增課程。');
	}else{
		$OutCourseID=array_diff($AllCourseID, $InCourseID);
	}

//debug_msg(__LINE__."行  AllCourseID", $AllCourseID);
//debug_msg(__LINE__."行  InCourseID", $InCourseID);
//debug_msg(__LINE__."行  OutCourseID", $OutCourseID);

	echo "<center>";
	echo '<TABLE BORDER="1" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
          <FORM ACTION="modules.php?op=modload&name='.$module_name.'&file='.$SubmitFile.'" METHOD="POST">';
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
                  <td align="center">題組試題<br>數量(f)</td>
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
             echo '<select name="ExamType[irt]">';  
             //echo '<option value="">試題反應模式</option>';
	         //  $sql="SELECT * FROM exam_type_irt ORDER BY type_id_irt";
	           $sql="SELECT * FROM exam_type_irt WHERE type_id_irt=1";
	           $result = $dbh->query($sql);
	           while ($data = $result->fetchRow()) {
		          echo '<option value="'.$data['type_id_irt'].'">'.$data['type_id_irt'].'.'.$data['type_irt'].'</option>';
	           }
             echo '</select></td>';
             //...........................................................................IRT選單..end
             // 2
	           //............................................................................能力估計法選單
	           echo '<td align="center">';
             echo '<select name="ExamType[t]">'; 
             //echo '<option value="">能力估計法</option>'; 
	         //$sql="SELECT * FROM exam_type_irt_t ORDER BY type_id_t";
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
             echo '<select name="ExamType[s]">';
             //echo '<option value="">選題法</option>';
	         //$sql="SELECT * FROM exam_type_irt_s ORDER BY type_id_s";
	           $sql="SELECT * FROM exam_type_irt_s WHERE type_id_s=1";
	           $result = $dbh->query($sql);
	           while ($data = $result->fetchRow()) {
		           echo '<option value="'.$data['type_id_s'].'">'.$data['type_id_s'].'.'.$data['type_s'].'</option>';
	           }
             echo '</select></td>';  
             //............................................................................選題法選單..end
             
             // 4
             //............................................................................曝光率控制選單
	           echo '<td align="center">';
             echo '<select name="ExamType[e]">';
             //echo '<option value="">曝光率控制</option>';
	         //$sql="SELECT * FROM exam_type_irt_e ORDER BY type_id_e";
	           $sql="SELECT * FROM exam_type_irt_e WHERE type_id_e=1";
			   $result = $dbh->query($sql);
	           while ($data = $result->fetchRow()) {
		           echo '<option value="'.$data['type_id_e'].'">'.$data['type_id_e'].'.'.$data['type_e'].'</option>';
	           }
             echo '</select></td>';  
             //............................................................................曝光率控制選單..end
             // 5
             //............................................................................更新曝光的人數
	           echo '<td align="center">';
             echo '<select name="ExamType[num_e]">';
             //echo '<option value="">更新曝光的人數</option>';
	         //  $num_e_tmp = array(0,1,5,10,30,50,100,500,1000);
	         //  $num_e_tmp1 = array(無,1,5,10,30,50,100,500,1000);
	         $num_e_tmp = array(0);
	         $num_e_tmp1 = array(無);
             for ($i=0;$i<count($num_e_tmp);$i++){
		           echo '<option value="'.$num_e_tmp[$i].'">'.$num_e_tmp1[$i].'</option>';
	           }
             echo '</select></td>';  
             //............................................................................更新曝光的人數..end  
             
             echo '<td align="center"><input type="text" name="ExamType[item_l]" size=2 value="0">';
             echo '</td></tr></table>'; 
            
            
	           echo '<font size="3" face="arial">';	
	           echo '<INPUT TYPE="HIDDEN" NAME="course[0]" VALUE="'.$course[0].'">';
	           echo '<INPUT TYPE="HIDDEN" NAME="course[1]" VALUE="'.$course[1].'">';
	           echo '<INPUT TYPE="HIDDEN" NAME="course[2]" VALUE="'.$course[2].'">';
                   
	       echo '<tr>
	              <td bgcolor="#FFFFCC" >                
               </td>            
             </tr>';
        echo '<tr>
              <td bgcolor="#FFFFCC" align="center" WIDTH="10%">
               <font size="3"><b>測驗時間</b></font>
              </td>  '; 
		echo '<td bgcolor="#FFFFCC" VALIGN="TOP" align="center"><br>
                <font size="3" face="arial">
				<SELECT NAME="TestTime">';
		echo '<OPTION VALUE="0">不限制</OPTION>'."\n";
		for ($itime=1;$itime<=24;$itime++){
			$iitime=$itime*10;
			echo '<OPTION VALUE="'.$iitime.'">'.$iitime.'分鐘</OPTION>'."\n";
		}  
		echo '</select><br><br>';
		echo '</td>
		      </tr>';  
        echo '<tr>
              <td bgcolor="#FFFFCC" align="center" WIDTH="10%">
               <font size="3"><b>尚未開放<br>試卷</b></font>
              </td>    
              <td bgcolor="#FFFFCC" VALIGN="TOP" align="center">
                <font size="3" face="arial">
                <SELECT NAME="EPids[]" SIZE="10" MULTIPLE>';  
                $sql="SELECT sn, exam_paper_id, type_id_irt, type_id_e, type_id_s, type_id_t, num_e, item_length, test_time FROM exam_course_access_irt WHERE year='".$course[0]."' AND seme ='".$course[1]."' AND course_id='".$course[2]."' ORDER BY exam_paper_id";
	              $result = $dbh->query($sql);
	              while ($data = $result->fetchRow()) {
		              $current_EP[]=$data['exam_paper_id'].sprintf("%02d",$data['type_id_irt']).sprintf("%02d",$data['type_id_e']).sprintf("%02d",$data['type_id_s']).sprintf("%02d",$data['type_id_t']).sprintf("%04d",$data['num_e']).sprintf("%02d",$data['item_length']).sprintf("%03d",$data['test_time']);  //已開放試卷及施測類型
		              $EP_sn[]=$data['sn'];
	              }
	              $sql = "SELECT distinct exam_paper_id FROM concept_item WHERE (exam_paper_id != -1) ";
	              $ii=sizeof($current_EP);
	              for($i=0;$i<$ii;$i++){
		              $currEPid=substr($current_EP[$i],0,11);   //取出試卷id
		              $sql .= "AND (exam_paper_id != $currEPid) ";
	              }
	             $sql .= "ORDER BY exam_paper_id ASC";
	             $result = $dbh->query($sql);
	             while ($data = $result->fetchRow())	{
		            $paper_info=explode_ep_id($data['exam_paper_id']);
		            
		            $paper_title=id2publisher($paper_info[0]).id2subject($paper_info[1]).'第'.$paper_info[2].'冊第'.$paper_info[3].'單元-卷'.$paper_info[4];
		            echo '<OPTION VALUE="'.$data['exam_paper_id'].'">'.$paper_title.'　　　　　　　　'.'</OPTION>'."\n"; 
		            debug_msg(__LINE__."行 paper_info", $paper_info);
					debug_msg(__LINE__."行 paper_title", $paper_title); 
	             }
	       echo '</SELECT><br>
	       <div align="center">新增或刪除
                  <select name="opt">
                    <option value="EPadd2Course">新增 ↓</option>
                    <option value="delCourseEP">↑ 刪除</option>
                  </select>         	              
	               <INPUT TYPE="SUBMIT" NAME="SUBMIT" VALUE="送出">
                </div> 
              </td>                        
            </tr>';
            
      debug_msg(__LINE__."行 current_EP", $current_EP);
       echo '<tr>              
              <td bgcolor="#FFFFCC" align="center">
                <font size="3"><b>已開放<br>試卷</b></font>
              </td>';
                
       echo '<td bgcolor="#FFFFCC" VALIGN="TOP" align="center">
              <TABLE BORDER="0" CELLPADDING="5" CELLSPACING="0">
               <tr>
                  <td>';
              echo '<SELECT NAME="OpenEPids[]" SIZE="10" MULTIPLE>'."\n";
              $ii=sizeof($current_EP);
	           for($i=0;$i<$ii;$i++){
					$currEPid=substr($current_EP[$i],0,11);   //取出試卷id/
					$exam_type_irt_id=substr($current_EP[$i],12,1);   //取出IRT類型
					$exam_type_e_id=substr($current_EP[$i],14,1);   //取出曝光率控制類型
					$exam_type_s_id=substr($current_EP[$i],16,1);   //取出選題法類型
					$exam_type_t_id=substr($current_EP[$i],18,1);   //取出能力估計法類型
					$num_e=substr($current_EP[$i],19,4);   //取出更新曝光的人數類型
					$item_l=substr($current_EP[$i],23,2);   //取出施測類型
					$test_time=substr($current_EP[$i],25,3);   //取出施測類型
					$paper_title=id2publisher($paper_info[0]).id2subject($paper_info[1]).'第'.$paper_info[2].'冊第'.$paper_info[3].'單元-卷'.$paper_info[4].'【'.$exam_type_irt_id.'/'.$exam_type_t_id.'/'.$exam_type_s_id.'/'.$exam_type_e_id.'/'.$num_e.'/'.$item_l.'/'.$test_time.'】<br>';
	   	         
	   	         echo '<OPTION VALUE="'.$EP_sn[$i].'">'.$paper_title.'</OPTION>'."\n";
	           }
	           echo '</SELECT><br>';
	          echo '</td>
               <tr>
              </TABLE>
             </td>
            </tr>';
    //說明欄位
    echo '<tr>
            <td bgcolor="#FFFFCC" colspan="2">
            適性功能說明【a/b/c/d/e/f/g】';
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
           echo  '<br>f：題組試題數量';
           echo  '<br>測驗長度由試題結構控制之編輯能力指標出題數控制，目前設定每份測驗對不同班級均為相同出題數，但可選擇不同題組數。';
           echo  '<br>g：測驗時間(分鐘)';
    echo  ' </td>
          </tr>';
            
    echo '</FORM>
        </TABLE><br><br>';

}

function EPadd2course($CourseID, $EPids, $ExamType, $TestTime){
	global $dbh, $module_name, $SubmitFile;

	$con = 0;  //是否存入資料判斷條件
	$i = 0;
	if ($ExamType['irt']==''){ 
		$mirt_c[$i]=0;  //  $mirt_c[$i] 表示第i種選題條件 為0表示未設定
	}else{  
		$con++;
		$mirt_c[$i]=1;
	}
	$i++;
	if ($ExamType['t']==''){ 
		$mirt_c[$i]=0;    
	}else{  
		$con++;
		$mirt_c[$i]=1;
	}
	$i++;
	if ($ExamType['s']==''){ 
		$mirt_c[$i]=0;    
	}else{  
		$con++;
		$mirt_c[$i]=1;
	}
	$i++;
	if ($ExamType['e']==''){ 
		$mirt_c[$i]=0;    
	}elseif($ExamType['e']==1){  
		$con++;
		$mirt_c[$i]=1;
		$ExamType['num_e']=0;
		$mirt_c[$i+1]=1;
	}else{
		if($ExamType['num_e']==''){
			$mirt_c[$i]=1;
			$mirt_c[$i+1]=0;
		}elseif($ExamType['num_e']==0){
			$mirt_c[$i]=1;
			$mirt_c[$i+1]=0;
		}else{
			$con++;
			$mirt_c[$i]=1;
			$mirt_c[$i+1]=1;
		}
	}
	$i++;  
	if($con!=$i){   
		include_once "modules/".$module_name."/warn_windows.php";
	}else{
		$ErrorCount=0;
		while(list($null, $curr_EPid) = each($EPids)) {
			$cs_id=EPid2CSid($curr_EPid);
			$EP_info=explode_ep_id($curr_EPid);
			$paper_vol=intval($EP_info[4]);
			$query = 'INSERT INTO exam_course_access_irt (cs_id, paper_vol, exam_paper_id, course_id, year, seme, type_id_irt, type_id_e, type_id_s, type_id_t, num_e, item_length, test_time) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)';
			$data = array($cs_id, $paper_vol, $curr_EPid, $CourseID[2], $CourseID[0], $CourseID[1], $ExamType['irt'], $ExamType['e'], $ExamType['s'], $ExamType['t'], $ExamType['num_e'], $ExamType['item_l'], $TestTime);
			$result =$dbh->query($query, $data);
			if (PEAR::isError($result)) {
				echo "錯誤訊息：".$result->getMessage()."<br>";
				echo "錯誤碼：".$result->getCode()."<br>";
				echo "除錯訊息：".$result->getDebugInfo()."<br>";
				$ErrorCount++;
			}
		}
		if($ErrorCount==0){  //沒有錯誤發生
			$RedirectTo="Location: modules.php?op=modload&name=".$module_name."&file=".$SubmitFile."&opt=EP2course&course[0]=".$CourseID[0]."&course[1]=".$CourseID[1]."&course[2]=".$CourseID[2];
			Header($RedirectTo);
		}else{
			die();
		}
	}
}

function course_delEP($SNs, $CourseID){
	global $dbh, $module_name, $SubmitFile;

	//debug_msg("第".__LINE__."行 SNs ", $SNs);
	//debug_msg("第".__LINE__."行 CourseID ", $CourseID);
	//die();

	while(list($null, $SN) = each($SNs)) {
		$sql="DELETE FROM exam_course_access_irt WHERE sn='".$SN."'";
		$result = $dbh->query($sql);
		if (PEAR::isError($result)) {
			echo "錯誤訊息：".$result->getMessage()."<br>";
			echo "錯誤碼：".$result->getCode()."<br>";
			echo "除錯訊息：".$result->getDebugInfo()."<br>";
			die();
		}
	}
	$RedirectTo="Location: modules.php?op=modload&name=".$module_name."&file=".$SubmitFile."&opt=EP2course&course[0]=".$CourseID[0]."&course[1]=".$CourseID[1]."&course[2]=".$CourseID[2];
	Header($RedirectTo);
}

