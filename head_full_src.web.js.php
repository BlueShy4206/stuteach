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

<!-- start 因應建構題新增 106.10.26 by yen -->
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
	 crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E="
	 crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
	 crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp"
	 crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
	 crossorigin="anonymous"></script>

	 <script src="http://d3js.org/d3.v3.min.js"></script>
	  <script src="http://mbostock.github.com/d3/d3.v2.js"></script>
<!-- end 因應建構題新增 106.10.26 by yen -->

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

