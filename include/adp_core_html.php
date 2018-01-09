<?php

// $Id: adp_core_html.php
//require_once( "adp_core_auth.php" );

function OpenTable2($width) {
	echo '<table width="'.$width.'" border="0" cellspacing="4" cellpadding="0">
                <tr>
                  <td colspan="3" scope="col"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td scope="col"><img src="'._THEME_IMG.'main_lt.gif" width="12" height="12" /></td>
                      <td width="98%" background="'._THEME_IMG.'main_ct.gif" scope="col"></td>
                      <td scope="col"><img src="'._THEME_IMG.'main_rt.gif" width="12" height="12" /></td>
                    </tr>
                    <tr>
                      <td background="'._THEME_IMG.'main_lc.gif"></td>
                      <td align="center">';
}

function CloseTable2() {
    	echo '</td>
                      <td background="'._THEME_IMG.'main_rc.gif"></td>
                    </tr>
                    <tr>
                      <td><img src="'._THEME_IMG.'main_ld.gif" width="12" height="12" /></td>
                      <td background="'._THEME_IMG.'main_cd.gif"></td>
                      <td><img src="'._THEME_IMG.'main_rd.gif" width="12" height="12" /></td>
                    </tr>
                  </table></td>
                </tr>';
	echo '</td></tr></table>';
}

function OpenTable() {
    echo "<table width=\"98%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"0000ff\"><tr><td>\n";
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"ffffff\"><tr><td>\n";
}

function CloseTable() {
    echo "</td></tr></table></td></tr></table>\n";
}


function CS_table_header($module_name){
	
	OpenTable();
	//echo "<td><center><font class=\"title\"><b>單元結構編製</b></font><br>";
	//echo "[ <a href=\"modules.php?op=modload&name=".$module_name."&file=index\">新增單元結構</a> ]";
	//echo "[ <a href=\"modules.php?op=modload&name=".$module_name."&file=creatREMEDY\">新增補救教學</a> ]";
	//echo "</center></td>";
	CloseTable();
}

function CS_ITEM_table_header(){
	OpenTable();
	//echo "<td><center><font class=\"title\"><b>試卷庫編製</b></font><br>";
	//echo "[ <a href=\"modules.php?op=modload&name=ConceptStructure&file=creatEP&opt=creatEP\">新增試卷</a> ]";
	//echo "[ <a href=\"modules.php?op=modload&name=ConceptStructure&file=creatITEM&opt=creatITEM\">新增試題</a> ]";
	//echo "[ <a href=\"modules.php?op=modload&name=ConceptStructure&file=modifyEP&opt=modifyEP\">編修試卷</a> ]";
	//echo "</center></td>";
	CloseTable();
}

function USER_MANAGE_table_header(){
	OpenTable();
	echo "<td><center><font class=\"title\"><b>使用者管理</b></font><br>";
	echo "[ <a href=\"modules.php?op=modload&name=UserManage&file=index\">新增使用者</a> ]";
	echo "[ <a href=\"modules.php?op=modload&name=UserManage&file=batchADD\">批次新增使用者</a> ]";
	echo "[ <a href=\"modules.php?op=modload&name=UserManage&file=importUSER\">匯入使用者</a> ]";
	echo "[ <a href=\"modules.php?op=modload&name=UserManage&file=modifyUSER&opt=chooseUSER\">查詢/編修使用者資料</a> ]";
	echo "</center></td>";
	CloseTable();
}

function EXAM_RESULT_table_header(){
	OpenTable();
	echo "<td><center><font class=\"title\"><b>施測結果查詢</b></font><br>";
	//echo "[ <a href=\"modules.php?op=modload&name=ExamResult&file=classResults&set_opt=classExamResults\">班級作答反應</a> ]";
	echo "[ <a href=\"modules.php?op=modload&name=ExamResult&file=classErrorStatistics&set_opt=classErrorStatistics\">班級學習狀態統計</a> ]";
	echo "[ <a href=\"modules.php?op=modload&name=ExamResult&file=classReports\">學生診斷報告</a> ]";
	//echo "[ <a href=\"modules.php?op=modload&name=UserManage&file=modifyUSER&opt=chooseUSER\">查詢/編修使用者資料</a> ]";
	echo "</center></td>";
	CloseTable();
}

function PERSON_EXAM_RESULT_table_header($user_id,$uname){
	OpenTable();
	echo '<td><center><font class="title"><b>'.$user_id.'【'.$uname.'】歷來測驗結果查詢</b></font><br>';
	//echo "[ <a href=\"modules.php?op=modload&name=ExamResult&file=personResults\">個人作答結果查詢</a> ]";
	//echo "[ <a href=\"modules.php?op=modload&name=ExamResult&file=classResults&set_opt=classErrorStatistics\">班級學習狀態統計</a> ]";
	//echo "[ <a href=\"modules.php?op=modload&name=UserManage&file=importUSER\">匯入使用者</a> ]";
	//echo "[ <a href=\"modules.php?op=modload&name=UserManage&file=modifyUSER&opt=chooseUSER\">查詢/編修使用者資料</a> ]";
	echo "</center></td>";
	CloseTable();
}

/*
function IMPORT_USER_table_header(){
	OpenTable();
	echo '<td><center><font class="title"><b>管理使用者資料</b></font><br>';
	//echo "[ <a href=\"modules.php?op=modload&name=UserManage&file=importUSER\">匯入學生帳號</a> ]";
	echo "[ <a href=\"modules.php?op=modload&name=UserManage&file=importAllUSER\">匯入所有帳號</a> ]";
	//echo "[ <a href=\"modules.php?op=modload&name=UserManage&file=importUSER\">匯入使用者</a> ]";
	echo "[ <a href=\"modules.php?op=modload&name=UserManage&file=modifyUSER&opt=chooseUSER\">查詢/編修使用者資料</a> ]";
	echo "</center></td>";
	CloseTable();
}
*/

function IMPORT_USER_table_header(){
	OpenTable();
	echo '<td><center><font class="title"><b>管理使用者資料</b></font><br>';
	//echo '[ <a href="modules.php?op=modload&name=ConceptStructure&file=addDepartment">新增科系</a> ]';
	echo "[ <a href=\"modules.php?op=modload&name=UserManage&file=importAllUSERwithID\">匯入所有帳號</a> ]";
	//echo "[ <a href=\"modules.php?op=modload&name=UserManage&file=importUSER\">匯入使用者</a> ]";
	echo "[ <a href=\"modules.php?op=modload&name=UserManage&file=modifyUSER&opt=chooseUSER\">查詢/編修使用者資料</a> ]";
	echo "</center></td>";
	CloseTable();
}


function IMPORT_ITEM_table_header(){
	OpenTable();
	echo '<td><center><font class="title"><b>試題結構控制</b></font><br>';
	//echo "[ <a href=\"modules.php?op=modload&name=UserManage&file=importUSER\">匯入學生帳號</a> ]";
	//echo "[ <a href=\"modules.php?op=modload&name=ConceptStructure&file=ctrlEP1\">匯入題組結構/能力結構</a> ]";
	echo "[ <a href=\"modules.php?op=modload&name=ConceptStructure&file=ctrlEP1\">匯入能力結構</a> ]";
	echo "[ <a href=\"modules.php?op=modload&name=ConceptStructure&file=ctrlEP_subitem\">編輯能力指標出題數</a> ]"."<br>";
	//echo "[ <a href=\"modules.php?op=modload&name=UserManage&file=importUSER\">匯入使用者</a> ]";
	echo "[ <a href=\"modules.php?op=modload&name=ConceptStructure&file=ctrlEP_testlet\">查詢/編修題組結構</a> ]";
	echo "[ <a href=\"modules.php?op=modload&name=ConceptStructure&file=ctrlEP_talent\">查詢/編修能力結構</a> ]";
	echo "[ <a href=\"modules.php?op=modload&name=ConceptStructure&file=ctrlEP_abc\">查詢/編修試題參數</a> ]";
  echo "[ <a href=\"modules.php?op=modload&name=ConceptStructure&file=itemBankExport2Excel\">匯出試題參數</a> ]";
	//echo "[ <a href=\"modules.php?op=modload&name=ConceptStructure&file=creatREMEDY\">編修補救教學檔案</a> ]";
	echo "</center></td>";
	CloseTable();
}

function IRT_result_table_header(){
	OpenTable();
	echo '<td><center><font class="title"><b>作答結果查詢</b></font><br>';
	echo "[ <a href=\"modules.php?op=modload&name=ConceptStructure&file=ctrlEP_test_result\">各題作答狀況</a> ]";
	//echo "[ <a href=\"modules.php?op=modload&name=ConceptStructure&file=ctrlEP_test_result4stu\">學生作答狀況</a> ]";
	echo "[ <a href=\"modules.php?op=modload&name=ConceptStructure&file=ctrlEP_test_result_downclass\">試卷作答狀況下載</a> ]";
	echo "</center></td>";
	CloseTable();
}

function IMPORT_CREATITEM_table_header(){
	OpenTable();
	echo '<td><center><font class="title"><b>題庫管理</b></font><br>';
	//echo "[ <a href=\"modules.php?op=modload&name=ConceptStructure&file=addPublisher\">新增版本</a> ]";
	//echo "[ <a href=\"modules.php?op=modload&name=ConceptStructure&file=addSubject\">新增科目</a> ]";
	echo "[ <a href=\"modules.php?op=modload&name=ConceptStructure&file=index\">修改測驗結構</a> ]";
	//echo "[ <a href=\"modules.php?op=modload&name=UserManage&file=importUSER\">匯入使用者</a> ]";
	echo "[ <a href=\"modules.php?op=modload&name=ConceptStructure&file=creatEP\">新增試卷</a> ]";
	echo "[ <a href=\"modules.php?op=modload&name=ConceptStructure&file=creatITEM\">新增試題</a> ]";
	echo '[ <a href="modules.php?op=modload&name=ConceptStructure&file=creatITEM_batch">批次新增試題</a> ]';
  echo "[ <a href=\"modules.php?op=modload&name=ConceptStructure&file=creatITEM_testlet\">新增試題(題組)</a> ]";
	echo "[ <a href=\"modules.php?op=modload&name=ConceptStructure&file=modifyEP\">修改試卷</a> ]";
	echo "</center></td>";
	CloseTable();
}

function CM_table_header($module_name){

	OpenTable();
	echo "<td><center><font class=\"title\"><b>場次管理</b></font><br>";
	echo '[ <a href="modules.php?op=modload&name='.$module_name.'&file=addCourse">新增場次</a> ]';
	echo '[ <a href="modules.php?op=modload&name='.$module_name.'&file=CourseMember">場次與考生對應</a> ]';
	echo '[ <a href="modules.php?op=modload&name='.$module_name.'&file=ctrlEP4Course">場次與試卷存取控制</a> ]';
	echo "</center></td>";
	CloseTable();
}

function ERROR_REPORT(){
	$report_url="modules.php?op=modload&name=AdaptiveTest&file=AdaptiveTestBox&opt=error_report";

	$report = "<a href=\"javascript:if (confirm('試題有錯誤？您確定要回報？')==true) self.location = '".$report_url."';\">問題回報</a>";

	//echo '<div id="Layer1" style="position:absolute; left:750px; top:450px; width:70px; height:20px; z-index:1; background-color: #FF66FF; layer-background-color: #FF66FF; border: 1px none #000000;"> ';
	echo '<div align="center"><img src="img/wrong.GIF" width="24" height="24" border="0" />'.$report.'</div>';
}

function FEETER(){
/*
   echo "</div></td>";
   echo '<TD WIDTH="42" background="images/frame_5.jpg"></TD>
   	</TR></TABLE>';

   echo '<TABLE CELLPADDING="0" CELLSPACING="0" BORDER="0" align="center">
   <TR>
   	<TD><IMG WIDTH="1024" HEIGHT="5" SRC="images/frame_6.jpg" BORDER="0"></TD>
   </TR>
   </TABLE>
   </BODY>
   </HTML>';
*/

echo '
</div>
<TABLE CELLPADDING="0" CELLSPACING="0" BORDER="0" align="center">
	<TR>
	<TD><IMG WIDTH="47" HEIGHT="3" SRC="images/tcc02_960_11.jpg" BORDER="0"></TD>
	<TD><IMG WIDTH="864" HEIGHT="3" SRC="images/tcc02_960_12.jpg" BORDER="0"></TD>
	<TD><IMG WIDTH="48" HEIGHT="3" SRC="images/tcc02_960_13.jpg" BORDER="0"></TD>
</TR>
</TABLE>
</BODY>
</HTML>';


}

function TableTitle($width, $title){
	echo '<table width="'.$width.'" border="0" cellspacing="2" cellpadding="2">
                <tr>
                  <td scope="col"><table width="100%" border="0" cellpadding="2" cellspacing="0" class="title">
                      <tr>
                        <td width="1%" scope="col"><img src="'._THEME_IMG.'li.gif" width="11" height="28" /></td>
                        <td width="99%" scope="col">'.$title.'</td>
                      </tr>
                  </table>';
   echo '</td></tr></table>';
}

function EXAM_DATA_EXPORT_header(){
    OpenTable();
    echo '<td><center><font class="title"><b>測驗結果查詢</b></font><br>';
    //echo "[ <a href=\"modules.php?op=modload&name=ExamResult&file=ctrlEP_test_result\">各題作答狀況</a> ]";
    //echo "[ <a href=\"modules.php?op=modload&name=ConceptStructure&file=ctrlEP_test_result4stu\">學生作答狀況</a> ]";
    echo '[ <a href="modules.php?op=modload&name=ExamResult&file=ExamData2Excel">測驗作答狀況下載</a> ]';
    echo "</center></td>";
    CloseTable();
}



?>
