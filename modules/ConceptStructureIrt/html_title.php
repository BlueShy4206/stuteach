<?php


function IRT_result_table_header($module_name){
	
	OpenTable();
	echo "<td><center><font class=\"title\"><b>作答結果查詢</b></font><br>";
	echo "[ <a href=\"modules.php?op=modload&name=".$module_name."&file=ctrlEP_test_result\">各題作答狀況</a> ]";
	echo "[ <a href=\"modules.php?op=modload&name=".$module_name."&file=ctrlEP_test_result4stu\">學生作答狀況</a> ]";
	//echo "</center></td>";
	CloseTable();
}

?>
