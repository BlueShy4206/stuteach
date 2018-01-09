<?php
$functions = 1;

$module_name = basename(dirname(__FILE__));
//get_lang($module_name);
//include("includes/big5_func.inc.php");

//include ("config.php");
//$imagesdir="images/forum/icons";
//$subjecticonsdir="images/forum/message";
//$rankimagesdir="images/forum/special";
//$search_results ="20";
//$module_member = "Memberslist";
//$url_smiles = "images/forum/smilies";

function msgid2type($id) { 
	global $dbh;
	$sql = "select msg_type from priv_msgs_type WHERE msg_type_id='$id'";
	$data =& $dbh->getOne($sql);
	return $data;
}

function bbdecode($message) {

                // Undo [code]
                $code_start_html = "<!-- BBCode Start --><TABLE BORDER=0 ALIGN=CENTER WIDTH=85%><TR><TD><font size=-1>Code:</font><HR></TD></TR><TR><TD><FONT SIZE=-1><PRE>";
                $code_end_html = "</PRE></FONT></TD></TR><TR><TD><HR></TD></TR></TABLE><!-- BBCode End -->";
                $message = str_replace($code_start_html, "[code]", $message);
                $message = str_replace($code_end_html, "[/code]", $message);

                // Undo [quote]
                $quote_start_html = "<!-- BBCode Quote Start --><TABLE BORDER=0 ALIGN=CENTER WIDTH=85%><TR><TD><font size=-1>Quote:</font><HR></TD></TR><TR><TD><FONT SIZE=-1><BLOCKQUOTE>";
                $quote_end_html = "</BLOCKQUOTE></FONT></TD></TR><TR><TD><HR></TD></TR></TABLE><!-- BBCode Quote End -->";
                $message = str_replace($quote_start_html, "[quote]", $message);
                $message = str_replace($quote_end_html, "[/quote]", $message);

                // Undo [b] and [i]
                $message = preg_replace("#<!-- BBCode Start --><B>(.*?)</B><!-- BBCode End -->#s", "[b]\\1[/b]", $message);
                $message = preg_replace("#<!-- BBCode Start --><I>(.*?)</I><!-- BBCode End -->#s", "[i]\\1[/i]", $message);

                // Undo [url] (long form)
                $message = preg_replace("#<!-- BBCode u2 Start --><A HREF=\"([a-z]+?://)(.*?)\" TARGET=\"_blank\">(.*?)</A><!-- BBCode u2 End -->#s", "[url=\\1\\2]\\3[/url]", $message);

                // Undo [url] (short form)
                $message = preg_replace("#<!-- BBCode u1 Start --><A HREF=\"([a-z]+?://)(.*?)\" TARGET=\"_blank\">(.*?)</A><!-- BBCode u1 End -->#s", "[url]\\3[/url]", $message);

                // Undo [email]
                $message = preg_replace("#<!-- BBCode Start --><A HREF=\"mailto:(.*?)\">(.*?)</A><!-- BBCode End -->#s", "[email]\\1[/email]", $message);

                // Undo [img]
                $message = preg_replace("#<!-- BBCode Start --><IMG SRC=\"(.*?)\" BORDER=\"0\"><!-- BBCode End -->#s", "[img]\\1[/img]", $message);

                // Undo lists (unordered/ordered)

                // <li> tags:
                $message = str_replace("<!-- BBCode --><LI>", "[*]", $message);

                // [list] tags:
                $message = str_replace("<!-- BBCode ulist Start --><UL>", "[list]", $message);

                // [list=x] tags:
                $message = preg_replace("#<!-- BBCode olist Start --><OL TYPE=([A1])>#si", "[list=\\1]", $message);

                // [/list] tags:
                $message = str_replace("</UL><!-- BBCode ulist End -->", "[/list]", $message);
                $message = str_replace("</OL><!-- BBCode olist End -->", "[/list]", $message);

                return($message);
}

function make_clickable($text) {

        // pad it with a space so we can match things at the start of the 1st line.
        $ret = " " . $text;

        // matches an "xxxx://yyyy" URL at the start of a line, or after a space.
        // xxxx can only be alpha characters.
        // yyyy is anything up to the first space, newline, or comma.
        $ret = preg_replace("#([\n ])([a-z]+?)://([^, \n\r]+)#i", "\\1<!-- BBCode auto-link start --><a href=\"\\2://\\3\" target=\"_blank\">\\2://\\3</a><!-- BBCode auto-link end -->", $ret);

        // matches a "www.xxxx.yyyy[/zzzz]" kinda lazy URL thing
        // Must contain at least 2 dots. xxxx contains either alphanum, or "-"
        // yyyy contains either alphanum, "-", or "."
        // zzzz is optional.. will contain everything up to the first space, newline, or comma.
        // This is slightly restrictive - it's not going to match stuff like "forums.foo.com"
        // This is to keep it from getting annoying and matching stuff that's not meant to be a link.
        $ret = preg_replace("#([\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^, \n\r]*)?)#i", "\\1<!-- BBCode auto-link start --><a href=\"http://www.\\2.\\3\\4\" target=\"_blank\">www.\\2.\\3\\4</a><!-- BBCode auto-link end -->", $ret);

        // matches an email@domain type address at the start of a line, or after a space.
        // Note: before the @ sign, the only valid characters are the alphanums and "-", "_", or ".".
        // After the @ sign, we accept anything up to the first space, linebreak, or comma.
        $ret = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([^, \n\r]+)#i", "\\1<!-- BBcode auto-mailto start --><a href=\"mailto:\\2@\\3\">\\2@\\3</a><!-- BBCode auto-mailto end -->", $ret);

        // Remove our padding..
        $ret = substr($ret, 1);

        return($ret);
}

function forumerror($e_code) {
    global $sitename, $header, $footer;
    if ($e_code == "0001") {
        $error_msg = "Could not connect to the forums database.";
    }
    if ($e_code == "0002") {
        $error_msg = "The forum you selected does not exist. Please go back and try again.";
    }
    if ($e_code == "0003") {
        $error_msg = "Password Incorrect.";
    }
    if ($e_code == "0004") {
        $error_msg = "Could not query the topics database.";
    }
    if ($e_code == "0005") {
        $error_msg = "Error getting messages from the database.";
    }
    if ($e_code == "0006") {
        $error_msg = "Please enter the Nickname and the Password.";
    }
    if ($e_code == "0007") {
        $error_msg = "You are not the Moderator of this forum therefore you can't perform this function.";
    }
    if ($e_code == "0008") {
        $error_msg = "You did not enter the correct password, please go back and try again.";
    }
    if ($e_code == "0009") {
        $error_msg = "Could not remove posts from the database.";
    }
    if ($e_code == "0010") {
        $error_msg = "Could not move selected topic to selected forum. Please go back and try again.";
    }
    if ($e_code == "0011") {
        $error_msg = "Could not lock the selected topic. Please go back and try again.";
    }
    if ($e_code == "0012") {
        $error_msg = "Could not unlock the selected topic. Please go back and try again.";
    }
    if ($e_code == "0013") {
        $error_msg = "Could not query the database. <BR>Error: mysql_error()";
    }
    if ($e_code == "0014") {
        $error_msg = "No such user or post in the database.";
    }
    if ($e_code == "0015") {
        $error_msg = "Search Engine was unable to query the forums database.";
    }
    if ($e_code == "0016") {
        $error_msg = "That user does not exist. Please go back and search again.";
    }
    if ($e_code == "0017") {
        $error_msg = "You must type a subject to post. You can't post an empty subject. Go back and enter the subject";
    }
    if ($e_code == "0018") {
        $error_msg = "You must choose message icon to post. Go back and choose message icon.";
    }
    if ($e_code == "0019") {
        $error_msg = "You must type a message to post. You can't post an empty message. Go back and enter a message.";
    }
    if ($e_code == "0020") {
        $error_msg = "Could not enter data into the database. Please go back and try again.";
    }
    if ($e_code == "0021") {
        $error_msg = "Can't delete the selected message.";
    }
    if ($e_code == "0022") {
        $error_msg = "An error ocurred while querying the database.";
    }
    if ($e_code == "0023") {
        $error_msg = "Selected message was not found in the forum database.";
    }
    if ($e_code == "0024") {
        $error_msg = "You can't reply to that message. It wasn't sent to you.";
    }
    if ($e_code == "0025") {
        $error_msg = "You can't post a reply to this topic, it has been locked. Contact the administrator if you have any question.";
    }
    if ($e_code == "0026") {
        $error_msg = "The forum or topic you are attempting to post to does not exist. Please try again.";
    }
    if ($e_code == "0027") {
        $error_msg = "You must enter your username and password. Go back and do so.";
    }
    if ($e_code == "0028") {
        $error_msg = "You have entered an incorrect password. Go back and try again.";
    }
    if ($e_code == "0029") {
        $error_msg = "Couldn't update post count.";
    }
    if ($e_code == "0030") {
        $error_msg = "The forum you are attempting to post to does not exist. Please try again.";
    }
    if ($e_code == "0031") {
        return(0);
    }
    if ($e_code == "0032") {
        $error_msg = "Error doing DB query in check_user_pw()";
    }
    if ($e_code == "0033") {
        $error_msg = "Error doing DB query in get_pmsg_count";
    }
    if ($e_code == "0034") {
        $error_msg = "Error doing DB query in check_username()";
    }
    if ($e_code == "0035") {
        $error_msg = "You can't edit a post that's not yours.";
    }
    if ($e_code == "0036") {
        $error_msg = "You do not have permission to edit this post.";
    }
    if ($e_code == "0037") {
        $error_msg = "You did not supply the correct password or do not have permission to edit this post. Please go back and try again.";
    }
    if (!isset($header)) {
        include("header.php");
    }
    OpenTable2();
    echo "<center><font size=\"2\"><b>$sitename Error</b></font><br><br>";
    echo "Error Code: $e_code<br><br><br>";
    echo "<b>ERROR:</b> $error_msg<br><br><br>";
    echo "[ <a href=\"javascript:history.go(-1)\">Go Back</a> ]<br><br>";
    CloseTable2();
    include("footer.php");
    die("");
}

function bbencode_priv($message) {
        global $prefix;
        // [CODE] and [/CODE] for posting code (HTML, PHP, C etc etc) in your posts.
        $matchCount = preg_match_all("#\[code\](.*?)\[/code\]#si", $message, $matches);

        for ($i = 0; $i < $matchCount; $i++)
        {
                $currMatchTextBefore = preg_quote($matches[1][$i]);
                $currMatchTextAfter = htmlspecialchars($matches[1][$i]);
                $message = preg_replace("#\[code\]$currMatchTextBefore\[/code\]#si", "<!-- BBCode Start --><TABLE BORDER=0 ALIGN=CENTER WIDTH=85%><TR><TD><font size=-1>Code:</font><HR></TD></TR><TR><TD><FONT SIZE=-1><PRE>$currMatchTextAfter</PRE></FONT></TD></TR><TR><TD><HR></TD></TR></TABLE><!-- BBCode End -->", $message);
        }

        // [QUOTE] and [/QUOTE] for posting replies with quote, or just for quoting stuff.
        $message = preg_replace("#\[quote\](.*?)\[/quote]#si", "<!-- BBCode Quote Start --><TABLE BORDER=0 ALIGN=CENTER WIDTH=85%><TR><TD><font size=-1>Quote:</font><HR></TD></TR><TR><TD><FONT SIZE=-1><BLOCKQUOTE>\\1</BLOCKQUOTE></FONT></TD></TR><TR><TD><HR></TD></TR></TABLE><!-- BBCode Quote End -->", $message);

        // [b] and [/b] for bolding text.
        $message = preg_replace("#\[b\](.*?)\[/b\]#si", "<!-- BBCode Start --><B>\\1</B><!-- BBCode End -->", $message);

        // [i] and [/i] for italicizing text.
        $message = preg_replace("#\[i\](.*?)\[/i\]#si", "<!-- BBCode Start --><I>\\1</I><!-- BBCode End -->", $message);

        // [url]www.phpbb.com[/url] code..
        $message = preg_replace("#\[url\](http://)?(.*?)\[/url\]#si", "<!-- BBCode Start --><A HREF=\"http://\\2\" TARGET=\"_blank\">\\2</A><!-- BBCode End -->", $message);

        // [url=www.phpbb.com]phpBB[/url] code..
        $message = preg_replace("#\[url=(http://)?(.*?)\](.*?)\[/url\]#si", "<!-- BBCode Start --><A HREF=\"http://\\2\" TARGET=\"_blank\">\\3</A><!-- BBCode End -->", $message);

        // [email]user@domain.tld[/email] code..
        $message = preg_replace("#\[email\](.*?)\[/email\]#si", "<!-- BBCode Start --><A HREF=\"mailto:\\1\">\\1</A><!-- BBCode End -->", $message);

        // [img]image_url_here[/img] code..
        $message = preg_replace("#\[img\](.*?)\[/img\]#si", "<!-- BBCode Start --><IMG SRC=\"\\1\"><!-- BBCode End -->", $message);

        // unordered list code..
        $matchCount = preg_match_all("#\[list\](.*?)\[/list\]#si", $message, $matches);

        for ($i = 0; $i < $matchCount; $i++)
        {
                $currMatchTextBefore = preg_quote($matches[1][$i]);
                $currMatchTextAfter = preg_replace("#\[\*\]#si", "<LI>", $matches[1][$i]);

                $message = preg_replace("#\[list\]$currMatchTextBefore\[/list\]#si", "<!-- BBCode ulist Start --><UL>$currMatchTextAfter</UL><!-- BBCode ulist End -->", $message);
        }

        // ordered list code..
        $matchCount = preg_match_all("#\[list=([a1])\](.*?)\[/list\]#si", $message, $matches);

        for ($i = 0; $i < $matchCount; $i++)
        {
                $currMatchTextBefore = preg_quote($matches[2][$i]);
                $currMatchTextAfter = preg_replace("#\[\*\]#si", "<LI>", $matches[2][$i]);

                $message = preg_replace("#\[list=([a1])\]$currMatchTextBefore\[/list\]#si", "<!-- BBCode olist Start --><OL TYPE=\\1>$currMatchTextAfter</OL><!-- BBCode olist End -->", $message);
        }

        return($message);
}

function init_msg_db()
{
	global $order, $do, $user_id, $newpms, $dbh;
	global $max_msg_per_page, $cur_page, $thread_total, $msg_total, $page_total, $status_bar;
	
	if(!$cur_page) {
		$cur_page=1;
	}

	if($do=="" || !isset($do)) {
		$do="list_msg";
		$order="desc";
	}


	$sql="select msg_id from priv_msgs WHERE to_userid = '$user_id'";
	$result =$dbh->query($sql);
	$msg_total = $result->numRows();
	//echo "<pre>";
	//print_r($result);
	//print_r($result->numRows());


	if ($msg_total%$max_msg_per_page!=0) { 
		$page_total=intval($msg_total/$max_msg_per_page)+1; 
	}else{ 
		$page_total=intval($msg_total/$max_msg_per_page); 
	}

	if($page_total==0) { $cur_page=0; }

	$sql="select msg_id from priv_msgs WHERE to_userid = '$user_id'";
	$result =$dbh->query($sql);
	$thread_total = $result->numRows();
	
	$sql="select msg_id from priv_msgs WHERE (to_userid = '$user_id' AND read_msg='0')";
	$result =$dbh->query($sql);
	$newpms = $result->numRows();

}

?>