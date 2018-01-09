<?php
mb_internal_encoding('utf-8');
echo '<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="expires" content="0">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="pragma" content="no-cache">
<title>師資生學科適性測驗</title>
<LINK REL="StyleSheet" HREF="themes/stuteach/css/front.css" TYPE="text/css">
<link rel="stylesheet" href="themes/dhtmlgoodies_calendar.css" />
<script src="themes/dhtmlgoodies_calendar.js"></script>
<script type="text/JavaScript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
</head>'."\n";

echo '<body BGCOLOR="#FFFFFF" ';
if($_REQUEST['name']=='AdaptiveTest' && $_REQUEST['file']=='AdaptiveTestBox'){
//if($_REQUEST['name']=='IRT' && $_REQUEST['file']=='index'){
	echo 'oncontextmenu="window.event.returnValue=false"';
}
echo ' onLoad="window.moveTo(0,0); window.resizeTo(screen.width,screen.height); MM_preloadImages(\'images/1_02.gif\',\'images/2_02.gif\',\'images/3_02.gif\',\'images/4_02.gif\',\'images/5_02.gif\',\'images/6_02.gif\', \'images/7_02.gif\');" text="" link="" vlink="" alink="" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">';
echo '<TABLE width="960" CELLPADDING="0" CELLSPACING="0" BORDER="0" align="center">
	<TR>
		<TD COLSPAN="3"><IMG WIDTH="960" HEIGHT="163" SRC="images/stu_inner_1.jpg" BORDER="0"></TD>
	</TR></table>';