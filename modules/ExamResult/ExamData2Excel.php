<?php
require_once "HTML/QuickForm.php";
require_once "include/adp_API.php";
require_once "classes/PHPExcel.php";
require_once "classes/PHPExcel/IOFactory.php";

$module_name = basename(dirname(__FILE__));
$SubmitFile=basename(__FILE__);
$SubmitFile=str_replace(".php", "", $SubmitFile);

//要加上 access_level 控制
if ($_REQUEST['opt1'] == 'databseSelected' and $user_data->access_level>70) {
    $_SESSION['my_db']=$_REQUEST['database_name'];
    //EXAM_DATA_EXPORT_header();
    //-- 顯示主畫面
    //echo '<hr>';
    //listCS($_REQUEST['database_name']);
    itemBankExportExcel($_POST['database_name']);
}
EXAM_DATA_EXPORT_header();
//-- 顯示主畫面
listDatabase();


function listDatabase()
{
    global $dbh, $module_name, $SubmitFile, $dbtype,$hostspec,$db_user,
$db_user_passwd;

    $form = new HTML_QuickForm('frmadduser', 'post', $_SERVER['PHP_SELF']);
    $select1[0] = '選擇資料庫';
    $select2[0][0] = '資料表';
    $select3[0][0][0] = '考試資料';

    $sql = "SHOW DATABASES ";
    $result = $dbh->query($sql);
    while ($row = $result->fetchRow()) { 
       
        //if (false !== (strpos($row['Database'], "irtstuteach_"))) {
         if (substr($row['Database'], 0, 12) == "irtstuteach_") { //update by coway 2017.9.7
        
            //$database_name[$row['Database']] = $row['Database'];
            $db_dbn= $row['Database'];
            $select1[$db_dbn] = $db_dbn;

            $DSN=$dbtype."://".$db_user.":".$db_user_passwd."@".$hostspec."/".$db_dbn;
            $options = array('debug'=> 2 );
            $dbh2 =& DB::connect($DSN, $options);
            $dbh2->query("SET NAMES utf8");
            $dbh2->setFetchMode(DB_FETCHMODE_ASSOC);

            $sql2 = "show tables";
            $result2 = $dbh2->query($sql2);
            while ($row2 = $result2->fetchRow()) {
                //debug_msg("第".__LINE__."行 row2 ", $row2);
                $a=$row2['Tables_in_'.$db_dbn];
                if (strpos($a, 'exam_record_')!== false) {
                    $select2[$db_dbn][$a] = $a;
                    $db_table=$a;
                    $sql3 = "SELECT distinct a.course_id, a.exam_title, b.sn, b.year, b.name FROM ".$db_dbn.".".$db_table." a 
     LEFT JOIN  ".$db_dbn.".course b
     ON a.course_id=b.course_id
     ";
     //echo   $sql3;
                    $result3 = $dbh2->query($sql3);
                    //debug_msg("第".__LINE__."行 sql3 ", $sql3);
                    while ($row3 = $result3->fetchRow()) {
                        //debug_msg("第".__LINE__."行 row3 ", $row3);
                        $mycourse=$row3[course_id].'-'.$row3[exam_title];
                        $select3[$db_dbn][$a][$mycourse] = $row3[sn].'【'.$row3[year].'】'.$row3[name];
                    }
                }

            }
        }
    }
    $_SESSION[$sel3]=$select3;
    //-- 顯示選單
    echo "<br>  以下僅列出您有存取權限的資料庫與資料表<br>";
    $form->addElement('header', 'myheader', '請選擇資料庫-資料表：');  //標頭文字
    $sel =$form->addElement('hierselect', 'database_name', '');
    $sel->setOptions(array($select1,$select2,$select3));
    $form->addElement('hidden', 'op', 'modload');
    $form->addElement('hidden', 'name', $module_name);
    $form->addElement('hidden', 'file', $SubmitFile);
    $form->addElement('hidden', 'opt1', 'databseSelected');
    //$form->addRule('database_name', '「資料庫」不可空白！', 'nonzero',null, 'client', null, null);
    $form->addRule('database_name', '「資料庫」不可空白！', null, null, 'client', null, null);
    $form->addElement('submit', 'btnSubmit', '選擇完畢，送出！');

    //debug_msg("第".__LINE__."行 selected ", $selected);
    $form->setDefaults($selected);
    $form->display();
}


function itemBankExportExcel($mydb)
{
    global $dbh, $module_name, $SubmitFile, $dbtype,$hostspec,$db_user,           $db_user_passwd;
    //debug_msg("第".__LINE__."行 _POST ", $_POST);
    $db_dbn=$mydb[0];
    $db_table=$mydb[1];
    $myfile=$_SESSION[$sel3][$mydb[0]][$mydb[1]][$mydb[2]];
    list($course_id,$ep_id)=explode("-",$mydb[2]);

    //die();
    //取出該題庫的試題參數
    $concept=EPid2ShortName($ep_id);
    $sql = "select concept_item.item_sn , concept_item.item_num , concept_item_parameter.a , concept_item_parameter.b , concept_item_parameter.c 
	from ".$db_dbn.".concept_item, ".$db_dbn.".concept_item_parameter 
	WHERE concept_item.exam_paper_id = '$ep_id' and concept_item.item_sn=concept_item_parameter.item_sn 
	order by concept_item.item_num ";
    //debug_msg("第".__LINE__."行 sql ", $sql);
    $result =$dbh->query($sql);
    $Mcount=0;
    $db_item=$chkItemSN=array();
    while ($row=$result->fetchRow()){
        $item_sn=$row['item_sn'];
        $item_num=$row['item_num'];
        $irt_a=$row['a'];
        $irt_b=$row['b'];
        $irt_c=$row['c'];
        $db_item[]=$item_num."【".$item_sn."】";
        //準備 excel 資料
        if($Mcount==0){
            $contentA[0]=[$db_dbn."-".$concept."\n"."ep_id:".$ep_id,"","","title"];
            $contentA[1]=["","","","a"];
            $contentA[2]=["","","","b"];
            $contentA[3]=array("","","","c");
            $contentA[4]=array("帳號","能力值","學科","姓名");
        }
        $contentA[0][]=$item_num."【".$item_sn."】";
        $contentA[1][]=$irt_a;
        $contentA[2][]=$irt_b;
        $contentA[3][]=$irt_c;
        $contentA[4][]=$item_num."【".$item_sn."】";
        $chkItemSN[]="【".$item_sn."】";
        $Mcount++;

    }
    //debug_msg("第".__LINE__."行 contentA[0] ", $contentA[0]);
    //debug_msg("第".__LINE__."行 contentA ", $contentA);
    //debug_msg("第".__LINE__."行 excel_content ", $excel_content);
    //取出該場次該科的作答資料(有IRT參數的試題)
    $sql = "SELECT * FROM ".$db_dbn.".".$db_table."  
     WHERE course_id='$course_id'
     ORDER BY user_id";
    //debug_msg("第".__LINE__."行 sql ", $sql);
    $result = $dbh->query($sql);
    $Icount = 0;
    while ($row = $result->fetchRow()) {
        //debug_msg("第".__LINE__."行 row ", $row);
        $user_id=$row[user_id];
        $UserTheta[$user_id]=$row[theta];
		$BinaryRes[$user_id]=$row[binary_res];
		$SelectItem[$user_id]=$row[select_item_id_S];
		//開始--取出能力估計
        $ability=explode(_SPLIT_SYMBOL, $row[exam_res]);
        if(end($ability)==''){
            array_pop($ability);
        }
        if($Icount==0){  //標題列
            $contentD[0][0]="course_id";
            $contentD[0][1]="user_id";
            $i=1;
            foreach($ability as $key=>$val){
                $contentD[0][]="item".$i;
                $i++;
            }
        }
        $contentD[$Icount+1][0]=$row[course_id];
        $contentD[$Icount+1][1]=$row[user_id];
        foreach($ability as $key=>$val){
            $contentD[$Icount+1][]=$val;
        }

        //debug_msg("第".__LINE__."行 contentD ", $contentD);
        //結束--取出能力估計

        //開始--取出被施測試題的id
        $used_item=explode(_SPLIT_SYMBOL, $row[select_item_id_S]);
        if(end($used_item)==''){
            array_pop($used_item);
        }
        if($Icount==0){
            $contentC[0][0]="course_id";
            $contentC[0][1]="user_id";
            $i=1;
            foreach($used_item as $key=>$val){
                $contentC[0][]="item".$i;
                $i++;
            }
        }
        $contentC[$Icount+1][0]=$row[course_id];
        $contentC[$Icount+1][1]=$row[user_id];
        foreach($used_item as $key=>$val){
            $contentC[$Icount+1][]=$val;
        }
        //結束--取出被施測試題的id

        //開始--取出作答時間
        $used_time=explode(_SPLIT_SYMBOL, $row[items_idle_time]);
        if(end($used_time)==''){
            array_pop($used_time);
        }
        if($Icount==0){
            $contentB[$Icount][0]="course_id";
            $contentB[$Icount][1]="user_id";
            $i=1;
            foreach($used_time as $key=>$val){
                $contentB[$Icount][]="item".$i;
                $i++;
            }
        }
        $contentB[$Icount+1][0]=$row[course_id];
        $contentB[$Icount+1][1]=$row[user_id];
        foreach($used_time as $key=>$val){
            $contentB[$Icount+1][]=$val;
        }
        //結束--取出作答時間

        $Icount++;
        //die();
    }
	//debug_msg("第".__LINE__."行 BinaryRes ", $BinaryRes);
	//debug_msg("第".__LINE__."行 SelectItem ", $SelectItem);
    //die();
    //取出前5題預試試題
    $db_table=str_replace("exam","pretest",$db_table);
    $sql = "SELECT a.*, b.uname FROM ".$db_dbn.".".$db_table." a, ".$db_dbn.".user_info b   
     WHERE a.course_id='$course_id' AND a.user_id=b.user_id 
     ORDER BY a.user_id";
    //debug_msg("第".__LINE__."行 sql ", $sql);
    $result = $dbh->query($sql);
    $Pcount = 0;
    $Acount=5;//A表的第五列開始寫入作答紀錄的二元資料
    while ($row = $result->fetchRow()) {
        //debug_msg("第".__LINE__."行 row ", $row);
        $user_id=$row[user_id];
        $ep_id=$row[exam_title];
        $uname=$row[uname];
        $ep_info=explode_ep_id($ep_id);
        $SubjectName=id2subject(intval($ep_info[1]));
        //開始--取預試前五題
        $pretest_item=explode(_SPLIT_SYMBOL, $row[select_item_id_S]);
        $pretest_binary=explode(_SPLIT_SYMBOL, $row[binary_res]);
        if(end($pretest_item)==''){
            array_pop($pretest_item);
        }
        if(end($pretest_binary)==''){
            array_pop($pretest_binary);
        }
        if($Pcount==0){
            $contentE[0][]="user_id";
            $pretest_i=0;
            foreach($pretest_item as $key=>$val){
                $pretest_i++;
                $contentE[0][]="item".$pretest_i;  
            }
        }
        $contentE[$Pcount+1][0]=$row[user_id];
        foreach($pretest_item as $key=>$val){
            $contentE[$Pcount+1][]=$val;
            $pItemBinary[$user_id][$val."】"]=$pretest_binary[$key];
        }
        //結束--取預試前五題

		//開始--取出二元作答資料
        $binary=explode(_SPLIT_SYMBOL, $BinaryRes[$user_id]);
        $select=explode(_SPLIT_SYMBOL, $SelectItem[$user_id]);
        if(end($binary)==''){
            array_pop($binary);
        }
        if(end($select)==''){
            array_pop($select);
        }
        foreach($binary as $key=>$val){
            $ItemBinary[$user_id][$select[$key]."】"]=$val;
        }

        $contentA[$Acount][0]=$user_id;   //帳號
        $contentA[$Acount][1]=$UserTheta[$user_id];  //能力值
        $contentA[$Acount][2]=$SubjectName;    //學科名稱
        $contentA[$Acount][3]=$uname;          //考生姓名
        //debug_msg("第".__LINE__."行 pItemBinary[$user_id] ", $pItemBinary[$user_id]);
        //掃描pretest的二元資料
        foreach($contentA[4] as $key=>$val){
        	if(strpos($val,"【")>0){
            	list($null,$pattern)=explode("【",$val);
            	if(array_key_exists($pattern, $pItemBinary[$user_id])){
	            	$contentA[$Acount][]=$pItemBinary[$user_id][$pattern];
            	}elseif(array_key_exists($pattern, $ItemBinary[$user_id])){
            		//掃描exam_reord的二元資料
	            	$contentA[$Acount][]=$ItemBinary[$user_id][$pattern];
            	}else{
            		//掃描不到資料就留空值
					$contentA[$Acount][]='';
				}
            }
        }
        //debug_msg("第".__LINE__."行 contentA ", $contentA);
        //結束--取出二元作答資料

        $Pcount++;
        $Acount++;
    }
    //debug_msg("第".__LINE__."行 pItemBinary ", $pItemBinary);  
    //debug_msg("第".__LINE__."行 ItemBinary ", $ItemBinary);
    //debug_msg("第".__LINE__."行 contentA ", $contentA);
    //die();
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->fromArray($contentA, null, 'A1',true);
    $objPHPExcel->getActiveSheet()->setTitle('作答反應');
    // 創建一個新的工作表
    $objWorksheet1 = $objPHPExcel->createSheet();
    $objWorksheet1->setTitle('作答時間(含前'.$pretest_i.'題為預試試題)');
    $objPHPExcel->setActiveSheetIndex(1);
    $objPHPExcel->getActiveSheet()->fromArray($contentB, null, 'A1',true);
    // 創建一個新的工作表
    $objWorksheet1 = $objPHPExcel->createSheet();
    $objWorksheet1->setTitle('被施測試題的id(不含前'.$pretest_i.'題)');
    $objPHPExcel->setActiveSheetIndex(2);
    $objPHPExcel->getActiveSheet()->fromArray($contentC, null, 'A1',true);
    // 創建一個新的工作表
    $objWorksheet1 = $objPHPExcel->createSheet();
    $objWorksheet1->setTitle('能力估計情況');
    $objPHPExcel->setActiveSheetIndex(3);
    $objPHPExcel->getActiveSheet()->fromArray($contentD, null, 'A1',true);
    // 創建一個新的工作表
    $objWorksheet1 = $objPHPExcel->createSheet();
    $objWorksheet1->setTitle('前'.$pretest_i.'題');
    $objPHPExcel->setActiveSheetIndex(4);
    $objPHPExcel->getActiveSheet()->fromArray($contentE, null, 'A1',true);


    $objPHPExcel->setActiveSheetIndex(0);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $filename=$myfile.'.xlsx';
    ob_clean();
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.$filename.'"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}

