<?php
require_once 'Date.php';
require_once "include/adp_API.php";

$module_name = basename(dirname(__FILE__));
//require_once("mainfile.php");
//get_lang($module_name);

include("modules/".$module_name."/myFunctions.php");
include("modules/".$module_name."/language/lang-chinese.php");
//include("modules/".$module_name."/auth.php");
$max_msg_per_page=5; //每頁最多簡訊數
$do=$_REQUEST['do'];
$cur_page=$_REQUEST['cur_page'];

if(!$auth->checkAuth()){
	FEETER();
	die();
}
$start_page=$_REQUEST['start_page'];

    $user_id=$user_data->user_id;
	echo '<script language="JavaScript" src="include/chk_data.js"></script>';
    OpenTable();
    echo "<center><font class=\"title\"><b>"._PRIVATEMESSAGES1."</b></font></center>";
    CloseTable();
	init_msg_db();
	$start_page = ($cur_page - 1) * $max_msg_per_page;
	
//<!-----------------------------------------------------
//                     跳頁
//-------------------------------------------------------
	echo '<table border="0" width="98%" cellspacing="0"><tr>
	<td align="right">';
	echo _TOTALNUM." $msg_total "._MESSAGENUM."，其中有 $newpms "._NEWMESSAGENUM;
	echo '</td><td align="right">';
	echo '第' . $cur_page . '/' . $page_total . '頁&nbsp; &nbsp; &nbsp;'; 
	echo '<select onChange="location.href=this.options[this.selectedIndex].value;">
	<option selected>跳頁選單</option>';

	for($t=1; $t <= $page_total; $t++) { 
		if($t != $cur_page) 
			echo "<option value=\"$PHP_SELF?op=modload&name=Private_Messages&file=index&do=list_msg&$search_list&cur_page=$t\">跳到第 $t 頁</option>";
	}
	echo '</select></font></td></tr>';

    echo "<br><table border=\"0\" cellspacing=\"1\" cellpadding=\"1\" align=\"center\" valign=\"middle\" width=\"98%\"><tr><td>"
	."<table border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">"
	."<form name=\"prvmsg\" method=\"post\" action=\"modules.php?op=modload&name=Private_Messages&file=index\">"
	."<input type=\"hidden\" name=\"file\" value=\"reply\">"
	."<tr bgcolor=\"$bgcolor2\" align=\"left\">"
	."<td bgcolor=\"$bgcolor2\" align=\"center\" valign=\"middle\"><input name=\"allbox\" onclick=\"CheckAll();\" type=\"checkbox\" value=\""._CHECKALL."\"></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\" valign=\"middle\">"._MSGSTATUS."</td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\" valign=\"middle\">"._MESSAGEICON."</td>"
	."<td><font class=\"content\" color=\"$textcolor2\"><b>"._FROM."</b></font></td>"
	."<td align=\"center\"><font class=\"content\" color=\"$textcolor\"><b>"._SUBJECT1."</b></font></td>"
	."<td align=\"center\"><font class=\"content\" color=\"$textcolor2\"><b>"._DATE."</b></font></td>"
	."</tr>";
	
	if($msg_total==0)  $start_page=0;
    $sql = "SELECT * FROM priv_msgs WHERE (to_userid = '$user_id') order by msg_id DESC LIMIT $start_page, $max_msg_per_page";

	$result =$dbh->query($sql);

	$num_rows = $result->numRows();
	if ($num_rows==0) {
	    echo "<td bgcolor=\"$bgcolor3\" colspan=\"6\" align=\"center\">"._DONTHAVEMESSAGES."</td></tr>\n";
	} else {
	    $display=1;
	}
	$count=($cur_page - 1) * $max_msg_per_page;
	while ($row=$result->fetchRow()) {
	    echo "<tr align=\"left\">";
	    echo "<td bgcolor=\"$bgcolor1\" valign=\"middle\" width=\"2%\" align=\"center\"><input type=\"checkbox\" onclick=\"CheckCheckAll();\" name=\"msg_id[$count]\" value=\"$row[msg_id]\"></td>";
	    if ($row['read_msg'] > 0) {
			echo "<td valign=\"top\" valign=\"middle\" width=\"5%\" align=\"center\" bgcolor=\"$bgcolor1\">&nbsp;</td>";
	    } else {
			echo "<td valign=\"top\" valign=\"middle\" width=\"10%\" align=\"center\" bgcolor=\"$bgcolor1\"><img src=\"images/newss.gif\" border=\"0\" align=\"middle\" alt=\""._NOTREAD."\"></td>";
	    }
		$msg_type=msgid2type($row[msg_type_id]);
	    echo "<td bgcolor=\"$bgcolor3\" valign=\"middle\" width=\"10%\" align=\"center\">$msg_type</td>";
		$poster_name = id2uname($row['from_userid']);
	    echo "<td bgcolor=\"$bgcolor1\" valign=\"middle\" width=\"10%\">$poster_name</td>"
			."<td bgcolor=\"$bgcolor3\" valign=\"middle\"><a href=\"modules.php?op=modload&name=Private_Messages&file=read&m_id=".$row['msg_id']."&start=$count&total_messages=$msg_total\">$row[subject]</a></td>"
			."<td bgcolor=\"$bgcolor1\" valign=\"middle\" align=\"center\" width=\"15%\"><font class=\"content\" color=\"$textcolor2\">$row[msg_time]</font></td></tr>";
	    $count++;
	}
	if ($display) {
		echo "<tr bgcolor=\"$bgcolor2\" align=\"left\">";
		echo "<td colspan=6 align='left'><a href='modules.php?op=modload&name=Private_Messages&file=reply&send=1'><img src='images/send.gif' border=0></a>&nbsp;<input type='image' src='images/delete.gif' name='delete_messages' value='delete_messages' border='0'></td></tr>";
		echo "<input type='hidden' name='total_messages' value='$msg_total'>";
		echo "</form>";
	}else {
		echo "<tr bgcolor=\"$bgcolor2\" align=\"left\">";
		echo "<td colspan=6 align='left'><a href='modules.php?op=modload&name=Private_Messages&file=reply&send=1'><IMG SRC='images/send.gif' border=0></a></td></tr>";
		echo "</form>";
	}
    echo "</table></td></tr></table>";

?>