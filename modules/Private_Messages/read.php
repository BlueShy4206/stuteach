<?php

$module_name = basename(dirname(__FILE__));
require_once "include/adp_API.php";
include_once("modules/".$module_name."/myFunctions.php");
include_once("modules/".$module_name."/language/lang-chinese.php");

if(!$auth->checkAuth()){
	FEETER(); 
	die();
}
$start=$_REQUEST['start'];
$total_messages=$_REQUEST['total_messages'];

$user_id=$user_data->user_id;
$sql = "SELECT * from priv_msgs WHERE to_userid = '$user_id' order by msg_id DESC";
$result =$dbh->limitQuery($sql,$_REQUEST['start'],1);
if($result){
	$row=$result->fetchRow();
	
	//print_r($row);
	
	$sql = "UPDATE priv_msgs SET read_msg=read_msg+1 WHERE msg_id='$row[msg_id]'";
	$result =$dbh->query($sql);

    OpenTable();
    echo "<center><font class=\"title\"><b>"._PRIVATEMESSAGES1."</b></font><br><br><font class=\"content\">【 <a href=\"modules.php?op=modload&name=Private_Messages&file=index\">"._BACKTOINDEX."</a> 】</font></center>";
    CloseTable();
    echo "<br>"
		."<table border=\"0\" cellpadding=\"1\" cellpadding=\"0\" valign=\"top\" width=\"100%\"><tr><td>"
		."<table border=\"0\" cellpadding=\"3\" cellpadding=\"1\" width=\"100%\">"
		."<tr bgcolor=\"$bgcolor2\" align=\"left\">"
		."<td width=\"20%\" colspan=\"2\" align=\"center\"><b>"._SUBJECT1."：<font color=\"#ff0000\"> $row[subject]</font></b></td>"
		."</tr>";

    if (!$row) {
		echo "<td bgcolor=\"$bgcolor3\" colspan=\"2\" align=\"center\">"._DONTHAVEMESSAGES."</td></tr>\n";
    } else {
		$sql2="select user_info.uname, user_info.firm_id, user_status.access_level from user_info, user_status WHERE user_status.user_id='$row[from_userid]' AND user_info.user_id='$row[from_userid]'";

		$result2 =$dbh->query($sql2);
		$row2=$result2->fetchRow();

		$firm=id2CityFirm($row2['firm_id']);
		$access_name=id2level($row2['access_level']);
		echo "<tr bgcolor=\"$bgcolor3\" align=\"left\">\n";
		echo "<td valign=\"center\"><b>"._FROM."： $row2[uname]</b><br>$row[from_userid]<br> ".$firm.", ".$access_name." \n";
		
		$msg_type=msgid2type($row[msg_type_id]);
	    echo "</td><td><b><font color=\"#ff0000\">$msg_type</font></b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font class=\"content\">"._GETTIME."： $row[msg_time]</font>&nbsp;"
	    	."<hr noshade><br>\n";
	}

	//$message = FixQuotes($myrow[msg_text]);
	echo $row[msg_text]."<br>"
	    ."<hr noshade>\n";
	    
	echo "</td></tr>"
	    ."<tr bgcolor=\"$bgcolor1\" align=\"RIGHT\"><td width=\"20%\" COLSPAN=2 align=RIGHT><font color=\"$textcolor2\" SIZE=1>";

	$previous = $start-1;
	$next = $start+1;
	if ($previous >= 0) {
	    echo "<a href=\"modules.php?op=modload&name=Private_Messages&file=read&start=$previous&total_messages=$total_messages\">"._PREVIOUSMESSAGE."</a> || ";
	} else {
	    echo ""._PREVIOUSMESSAGE1." || ";
	}
	if ($next < $total_messages) {
	    echo "<a href=\"modules.php?op=modload&name=Private_Messages&file=read&start=$next&total_messages=$total_messages\">"._NEXTMESSAGE."</a></font>";
	} else {
	    echo ""._NEXTMESSAGE1."</font>";
	}	
	echo "</td></tr>"
	    ."<tr bgcolor=\"$bgcolor2\" align=\"left\"><td width=\"20%\" COLSPAN=\"2\" align=\"left\">"
	    ."<font color=\"$textcolor2\">"
	    ."<a href=\"modules.php?op=modload&name=Private_Messages&file=reply&reply=1&msg_id=$row[msg_id]\"><img src=\"images/reply.gif\" border=\"0\" alt=\"\"></a>\n"
	    ."&nbsp;<a href=\"modules.php?op=modload&name=Private_Messages&file=reply&delete=1&msg_id=$row[msg_id]\"><img src=\"images/delete.gif\" border=0 alt=\"\"></a>\n";
    echo "</font></td></tr></table></td></tr></table>";
}

?>