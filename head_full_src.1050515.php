<?php
mb_internal_encoding('utf-8');
echo '<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="expires" content="0">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="pragma" content="no-cache">
<title>進行測驗</title>
<LINK REL="StyleSheet" HREF="themes/stuteach/css/front.css" TYPE="text/css">

</head>'."\n";

echo '<body BGCOLOR="#FFFFFF" ';

//只鎖滑鼠右鍵
//echo 'oncontextmenu="window.event.returnValue=false"';

echo ' onLoad="window.moveTo(0,0); window.resizeTo(screen.width,screen.height);" text="" link="" vlink="" alink="" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">';

//鎖右鍵及F5   by彥鈞 105.5.15
if($user_data->access_level<=5){
	echo '<script language="javascript" type="text/javascript" src="include/lock-keyboard.js"></script>';

}

                          
echo '<TABLE CELLPADDING="0" CELLSPACING="0" BORDER="0" align="center">
		<TR>
		<TD COLSPAN="3">';

//echo '<div id="Layer1" style="position:absolute; left:1px; top:1px; width:1px; height:1px; z-index:1">';

