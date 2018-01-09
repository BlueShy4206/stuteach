<?php
$module_name = basename(dirname(__FILE__));
require_once "include/adp_API.php";
include_once("modules/".$module_name."/myFunctions.php");
include_once("modules/".$module_name."/language/lang-chinese.php");
$bgcolor4="#DEDEE6";
$bgcolor1="#FFFFFF";
$bgcolor2="#FFFFFF";
$bgcolor3="#FFFFFF";

if(!$auth->checkAuth()){
	FEETER();
	die();
}
$msg_id=$_REQUEST['msg_id'];
$reply=$_REQUEST['reply'];
$send=$_REQUEST['send'];
$message=$_REQUEST['message'];
$subject=$_REQUEST['subject'];
$to_user=$_REQUEST['to_user'];
$msg_type_id=$_REQUEST['msg_type_id'];
$delete=$_REQUEST['delete'];
$total_messages=$_REQUEST['total_messages'];

$forumpage = 1;

if($cancel) {
    header("Location: modules.php?op=modload&name=Private_Messages&file=index");
}


echo '<script language="JavaScript" src="include/chk_data.js"></script>';

if($_REQUEST['submit']) {
    if($subject == '') {
        forumerror(0017);
    }

    $message = str_replace("\n", "<br>", $message);
    //$message = make_clickable($message);

    $msg_time = date("Y-m-d H:i");
	$sql= "select user_id from user_info where user_id='$to_user'";
	$result =$dbh->query($sql);
    $row = $result->fetchRow();
    if ($row['user_id'] == "") {   //檢查是否收件人帳號是否存在
        OpenTable();
        echo "<center>"._USERNOTINDB."<br>"
    	    .""._CHECKNAMEANDTRY."<br><br>"
			."<font class=\"content\">【 <a href=\"modules.php?op=modload&name=Private_Messages&file=index\">"._BACKTOINDEX."</a> 】</font></center>";
        CloseTable();
        FEETER();
		die();
    } else {	
        $query = 'INSERT INTO priv_msgs (msg_type_id, subject, from_userid, to_userid, msg_time, msg_text, read_msg) VALUES (?,?,?,?,?,?,?)';
		$data = array($msg_type_id, $subject, $user_data->user_id, $to_user, $msg_time, $message, 0);
		$result =$dbh->query($query, $data);
	}
	OpenTable();
	echo "<center>"._MSGPOSTED."<br><br><a href=\"modules.php?op=modload&name=Private_Messages&file=index\">"._RETURNTOPMSG."</a></center>";
	CloseTable();
}

if ($_REQUEST['delete_messages_x'] && $_REQUEST['delete_messages_y']) {  //刪除多重簡訊
    for ($i=0;$i<$total_messages;$i++) {
		$my_msg=$_REQUEST[msg_id][$i];
		$sql = "DELETE FROM priv_msgs WHERE msg_id='$my_msg' AND to_userid='$user_data->user_id'";
		
		$result =$dbh->query($sql);
		if(!$result) {
    	    forumerror(0021);
		} else {
			$status =1;
		}
    }
    if ($status) {
        OpenTable();
        echo "<center>"._MSGDELETED."<br><br><a href=\"modules.php?op=modload&name=Private_Messages&file=index\">"._RETURNTOPMSG."</a></center>";
        CloseTable();
    }    
}

if ($delete) {  //刪除簡訊
    $sql = "DELETE FROM priv_msgs WHERE msg_id='$msg_id' AND to_userid='$user_data->user_id'";
    $result =$dbh->query($sql);
	if(!$result) {
        forumerror(0021);
    } else {
        OpenTable();
        echo "<center>"._MSGDELETED."<br><a href=\"modules.php?op=modload&name=Private_Messages&file=index\">"._RETURNTOPMSG."</a></center>";
        CloseTable();
    }
}

if ($reply || $send) {
	/*
	OpenTable();
    echo "<center><font class=\"title\"><b>"._PRIVATEMESSAGES1."</b></font><br><br><font class=\"content\">【 <a href=\"modules.php?op=modload&name=Private_Messages&file=index\">"._BACKTOINDEX."</a> 】</font></center>";
    CloseTable();
	*/
	/*
	if ($uname != "") {
		$res = sql_num_rows(sql_query("select * from ".$user_prefix."_users where uname='$uname'", $dbi), $dbi);
		if ($res == 0) {
	    	title("$sitename: "._PRIVATEMESSAGE."");
	    	OpenTable();
	    	echo "<center><b>"._PRIVMSGERROR."</b><br><br>"
			.""._USERDOESNTEXIST."<br><br>"
			.""._GOBACK."</center>";
	    	CloseTable();
	    	include("footer.php");
	    	die();
		}
    }
	*/

    if ($reply) {
		$sql = "SELECT msg_type_id, subject, from_userid, to_userid FROM priv_msgs WHERE msg_id = $msg_id";
		$result =$dbh->query($sql);
		if (!$result) {
	    	forumerror(0022);
		}
		$row=$result->fetchRow();
		if (!$row) {
	    	forumerror(0023);
		}
		//$fromuserdata = get_userdata_from_id($row[from_userid], $db);
		$user_id=$user_data->user_id;
		$to_user_id=$row['from_userid'];
		$to_uname=id2uname($to_user_id);

		//$touserdata = get_userdata_from_id($row[to_userid], $db);
    }
    

	echo '<table width="100%" border="0" cellpadding="2" cellspacing="0" class="title">
                    <tr>
                      <td width="1%" scope="col"><img src="'._THEME_IMG.'li.gif" width="11" height="28" /></td>
                      <td width="99%" scope="col">問題回報/建議</td>
                    </tr>
                  </table>';
    echo "<FORM ACTION=\"modules.php?op=modload&name=Private_Messages&file=reply\" METHOD=\"POST\" NAME=\"coolsus\" onsubmit=\"return chk_data()\">";
	echo "<TABLE BORDER=\"1\" CELLPADDING=\"1\" CELLSPACEING=\"0\" ALIGN=\"CENTER\" VALIGN=\"TOP\" bordercolor=\"".$bgcolor4."\" WIDTH=\"98%\"><TR><TD bgcolor=\"$bgcolor1\">"
        ."<TABLE BORDER=\"0\" CALLPADDING=\"1\" CELLSPACEING=\"1\" WIDTH=\"100%\">"
        ."<TR BGCOLOR=\"$bgcolor1\">"
        ."<TD ALIGN=\"right\" width=\"25%\"><FONT COLOR=\"$textcolor2\"><b>"._ABOUTPOSTING."：</b></FONT></TD>"
        ."<TD ALIGN=\"left\"><FONT COLOR=\"$textcolor2\">"._ALLREGCANPOST."</FONT></TD>"
        ."</TR>"
        ."<TR>"
        ."<TD  ALIGN=\"right\" BGCOLOR=\"$bgcolor1\" width=\"25%\"><b>"._TO."：<b></TD>";
    if ($reply) {
        echo "<TD BGCOLOR=\"$bgcolor1\"><INPUT TYPE=\"HIDDEN\" NAME=\"to_user\" VALUE=\"".$to_user_id."\">".$to_user_id."【".$to_uname."】</TD>";
    } else {  //新簡訊
		echo "<TD BGCOLOR=\"$bgcolor1\">";
			
		$sql="SELECT user_info.user_id, user_info.uname from user_info, user_status WHERE user_status.access_level='81' AND user_status.user_id=user_info.user_id";
		$result = $dbh->query($sql);   
		echo '<select name="to_user">';
		while ($data = $result->fetchRow()) {
			echo '<option value="'.$data['user_id'].'">'.$data['uname'].'</option>';
		}
		echo '</select>';
		echo "</td>";
    }
    echo "</TR><TR>"
        ."<TD ALIGN=\"right\" BGCOLOR=\"$bgcolor3\" width=\"25%\"><b>"._SUBJECT."：<b></TD>";
    if ($reply) {
    	$str1="回覆: ";
    	if(strstr($row[subject], $str1))
        	echo "<TD  BGCOLOR=\"$bgcolor1\"><INPUT TYPE=\"TEXT\" NAME=\"subject\" VALUE=\""."$row[subject]\" SIZE=\"70\" MAXLENGTH=\"100\"></TD>";
		else
			echo "<TD  BGCOLOR=\"$bgcolor1\"><INPUT TYPE=\"TEXT\" NAME=\"subject\" VALUE=\""._RE.": $row[subject]\" SIZE=\"70\" MAXLENGTH=\"100\"></TD>";
    } else { 
        echo "<TD  BGCOLOR=\"$bgcolor1\"><INPUT TYPE=\"TEXT\" NAME=\"subject\" SIZE=\"70\" MAXLENGTH=\"100\"></TD>";
    }
    echo "</TR>"
        ."<TR VALIGN=\"TOP\">"
        ."<TD ALIGN=\"right\" BGCOLOR=\"$bgcolor3\" width=\"25%\"><b>"._MESSAGEICON."：<b></TD>"
        ."<TD ALIGN=\"left\" BGCOLOR=\"$bgcolor1\">";
    echo '<select name="msg_type_id">';
    $sql="select * from priv_msgs_type ORDER BY msg_type_id";
	$result =$dbh->query($sql);
	while ($row=$result->fetchRow()) {
		echo '<option value="'.$row['msg_type_id'].'">'.$row['msg_type'].'</option>';
    }
	echo '</select>';
    echo "</TD></TR>";

    if ($reply) {
        $sql = "SELECT p.msg_text, p.msg_time, u.uname FROM priv_msgs p, user_info u ";
        $sql .= "WHERE (p.msg_id = $msg_id) AND (p.from_userid = u.user_id)";
        if($result =$dbh->query($sql)) {
    	    $row=$result->fetchRow();
	    	//$text = desmile($row[msg_text]);
	    	$text = str_replace("<BR>", "\n", $row[msg_text]);
	    	//$text = FixQuotes($text);
	    	//$text = bbdecode($text);
//	    	$reply = "[quote]\n"._PMON." $row[msg_time], $row[uname] "._WROTE.":\n$text\n[/quote]";
			$reply = " $row[msg_time]， $row[uname] "._WROTE."：<BR><blockquote><p>$text\n</p></blockquote>";
		} else {
	    	$reply = "無法連結資料庫，請再試一次！\n";
		}
    }				
    if ($reply) {
        echo "<TR><TD ALIGN=\"right\" BGCOLOR=\"$bgcolor3\" width=\"25%\"><br><b>來訊內容：</b><br>";
        echo "</font></TD><TD ALIGN=\"left\" BGCOLOR=\"$bgcolor1\"><font color=\"#0000FF\">".$reply."</font></TD></TR>";
    }
        
    echo "<TR VALIGN=\"TOP\">"
        ."<TD ALIGN=\"right\" BGCOLOR=\"$bgcolor3\" width=\"25%\"><br><br><b>"._MESSAGEREPLY."：</b><br><br>";

    echo "</font></TD>"
        ."<TD ALIGN=\"left\" BGCOLOR=\"$bgcolor1\"><TEXTAREA NAME=\"message\" ROWS=\"18\" COLS=\"70\" WRAP=\"VIRTUAL\">";
    echo "</TEXTAREA><BR>";
//    putitems();

    echo "</TD></TR>";
	echo "<TR>"
        ."<TD BGCOLOR=\"$bgcolor1\" colspan=\"2\" ALIGN=\"CENTER\">"
        ."<INPUT TYPE=\"HIDDEN\" NAME=\"msg_id\" VALUE=\"$msg_id\">"
        ."<INPUT TYPE=\"SUBMIT\" NAME=\"submit\" class=\"butn01\" VALUE=\""._SUBMIT."\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<INPUT class=\"butn02\" TYPE=\"RESET\" VALUE=\""._CLEAR."\">&nbsp;&nbsp;&nbsp;";
    if ($reply) {
//        echo "&nbsp;<INPUT TYPE=\"SUBMIT\" NAME=\"cancel\" VALUE=\""._CANCELREPLY."\">";
		echo "【<a href=\"modules.php?op=modload&name=Private_Messages&file=index\">"._CANCELREPLY."</a>】";
    } else {
//        echo "&nbsp;<INPUT TYPE=\"SUBMIT\" NAME=\"cancel\" VALUE=\""._CANCELSEND."\">";
		echo "【<a href=\"modules.php?op=modload&name=Private_Messages&file=index\">"._CANCELSEND."</a>】";
    }
    echo "</TD>"
        ."</TR>"
        ."</TABLE></TD></TR></TABLE>"
        ."</FORM>"
        ."<BR>";
}

?>