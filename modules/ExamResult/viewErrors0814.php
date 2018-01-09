<?php
require_once "head.php";  
require_once "auth_chk.php";  


echo '<center><a href="javascript:close();">★關閉視窗★</a><br><br>';
//echo "<pre>";
//print_r($_REQUEST);
$cs_id=EPid2CSid($_GET['q_ep_id']);
$ep_fullname=EPid2FullName($_GET['q_ep_id']);
$test=new Remedy_Structure($cs_id);
$rem_str=$test->get_structure();
$remedy2item=$test->get_remedy2item();   //補救概念所對應之題目
$item = explode(_SPLIT_SYMBOL, $test->remedy2item[$_GET[remedy]]);
debug_msg("第".__LINE__."行 item ", $item);
echo '
<TABLE width=700 cellpadding="0" cellspacing="0" border="0" align="center">';
echo '<tr><td><center><b><font class="title">○●○【'.id2uname($_GET['q_user_id']).'】 錯誤題目解說○●○</font></b></td></tr>';
echo '<TR><TD width="100%" valign=top>
<fieldset style="font-size:13px;color:#660000">
<LEGEND class="s_title"><font class="title"><b>試卷名稱</b></font></LEGEND><font class="title2">　
'.$ep_fullname.'</font></fieldset><br></TD></TR>';
echo '<TR><TD width="100%" valign=top>
<fieldset style="font-size:13px;color:#660000">
<LEGEND class="s_title"><font class="title"><b>需補救概念</b></font></LEGEND><font class="title2">　
'.$rem_str[$_GET[remedy]-1].'</font></fieldset><br></TD></TR>';

$sql="select * from exam_record WHERE user_id='{$_GET[q_user_id]}' and  exam_title='{$_GET[q_ep_id]}'";
//echo $sql."<br>";
$result =$dbh->query($sql);
$row=$result->fetchRow();
$item_num = explode(_SPLIT_SYMBOL, $row['questions']);
$org_res = explode(_SPLIT_SYMBOL, $row['org_res']);
$binary_res = explode(_SPLIT_SYMBOL, $row['binary_res']);
/*
print_r($item_num);
echo "原始答案<br>";
print_r($org_res);
echo "二元答案<br>";
print_r($binary_res);
*/
//作答反應說明：
//０->實際做錯
//１->實際做對
//２->預測會做對(實際上被省略而未做)
//３->預測會做對但實際上做錯
//４->預測會做對且實際上做對
for($i=0;$i<sizeof($item_num)-1;$i++){
	$arry[$item_num[$i]][0]=$binary_res[$item_num[$i]-1];
	$arry[$item_num[$i]][1]=$org_res[$i];
}

for($i=0;$i<sizeof($item)-1;$i++){
	if($arry[$item[$i]][0]=='0'){  //做錯的題目
		$paper_vol=intval(substr($_GET['q_ep_id'], 9, 2));
		$my_item=new Item_Structure($cs_id,$item[$i],$paper_vol);
		$question_select_pic=$my_item->get_item_select_pic();
		echo '<TR><TD width="100%" valign=top>
			<fieldset style="font-size:13px;color:#660000">
			<LEGEND class="s_title"><font class="title"><b>錯誤分析</b></font></LEGEND>';
		echo '<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">';

		if(isset($showfig)){		unset($showfig);	}
		$showfig=explode(".", $my_item->item_filename);
		$showfig[0]=str2compiler($showfig[0]);
		echo '<tr><td width="100" align="right" class="s_title">題目：</td><td class="s_title"><img border="0" src="viewfig.php?list='.$showfig[0].'&tpp='.$showfig[1].'"></td></tr>';
		echo '<tr><td width="100" align="right" class="s_title">所有選項：</td><td class="s_title">';
		for($ii=0;$ii<sizeof($question_select_pic);$ii++){
			if(isset($showfig)){		unset($showfig);	}
			$showfig=explode(".", $my_item->op_pieces[$ii]);
			$showfig[0]=str2compiler($showfig[0]);
			echo '<img border="0" src="viewfig.php?list='.$showfig[0].'&tpp='.$showfig[1].'"><br>';
		}
		echo '<img border="0" src="'._ADP_URL.'images/5th.gif"><br>';
		echo '</td></tr>';
		//echo '<tr><td width="100" align="right">所有選項：</td><td><img border="0" src="'.$my_item->get_item_select_pic.'"></td></tr>';

		if(isset($showfig)){		unset($showfig);	}
		//print_r($arry[$item[$i]][1]);
		if($arry[$item[$i]][1]==5){  //學生選了第五個答案
			echo '<tr><td width="100" class="s_title">學生答案：</td><td class="s_title"><img border="0" src="'._ADP_URL.'images/5th.gif"></td></tr>';
		}else{
			$showfig=explode(".", $my_item->op_pieces[$arry[$item[$i]][1]-1]);
			$showfig[0]=str2compiler($showfig[0]);
			//echo '<tr><td width="100" class="s_title">學生答案：</td><td><img border="0" src="'.$question_select_pic[$arry[$item[$i]][1]-1].'"></td></tr>';
			echo '<tr><td width="100" class="s_title">學生答案：</td><td class="s_title"><img border="0" src="viewfig.php?list='.$showfig[0].'&tpp='.$showfig[1].'"></td></tr>';
		}
		if(isset($showfig)){		unset($showfig);	}
		$showfig=explode(".", $my_item->op_pieces[$my_item->get_item_correct_answer()-1]);
		$showfig[0]=str2compiler($showfig[0]);
		//echo '<tr><td width="100" class="s_title">正確答案：</td><td><img border="0" src="'.$question_select_pic[$my_item->get_item_correct_answer()-1].'"></td></tr>';
		echo '<tr><td width="100" class="s_title">正確答案：</td><td class="s_title"><img border="0" src="viewfig.php?list='.$showfig[0].'&tpp='.$showfig[1].'"></td></tr>';
		echo '</table>';
		echo '</fieldset><br></TD></TR>';
	}
}

echo '</table>';

?>
