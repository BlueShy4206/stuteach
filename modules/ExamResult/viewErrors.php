<?php
//require_once "auth_chk.php";


echo '<center><a href="javascript:close();">★關閉視窗★</a><br><br>';
//echo "<pre>";
//print_r($_REQUEST);
$cs_id=EPid2CSid($_GET['q_ep_id']);
$cs_ary=explode_cs_id($cs_id);
$cs_path=_ADP_CS_UPLOAD_PATH;
for($i=0;$i<sizeof($cs_ary);$i++){
	$cs_path.=$cs_ary[$i].'/';
}
//debug_msg("第".__LINE__."行 cs_path ", $cs_path);
$ep_fullname=EPid2FullName($_GET['q_ep_id']);
$CS=new Concept_Structure($cs_id,1);
$test=new Remedy_Structure($cs_id);
//debug_msg("第".__LINE__."行 test ", $test);
$rem_str=$test->get_structure();
//debug_msg("第".__LINE__."行 rem_str ", $rem_str);
$remedy2item=$test->get_remedy2item();   //補救概念所對應之題目
//debug_msg("第".__LINE__."行 remedy2item ", $remedy2item);
$item = explode(_SPLIT_SYMBOL, $test->remedy2item[$_GET[remedy]]);
//debug_msg("第".__LINE__."行 item ", $item);

echo '
<TABLE width=730 cellpadding="0" cellspacing="0" border="0" align="center">';
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
debug_msg("第".__LINE__."行 作答順序 item_num ", $item_num);
debug_msg("第".__LINE__."行 原始答案 org_res ", $org_res);
debug_msg("第".__LINE__."行 二元答案 binary_res ", $binary_res);
*/
//作答反應說明：
//０->實際做錯
//１->實際做對
//２->預測會做對(實際上被省略而未做)
//３->預測會做對但實際上做錯
//４->預測會做對且實際上做對
for($i=0;$i<sizeof($item_num)-1;$i++){
	$arry[$item_num[$i]-$CS->ceil_item_num][0]=$binary_res[$item_num[$i]-1];
	$arry[$item_num[$i]-$CS->ceil_item_num][1]=$org_res[$i];
}
/*
debug_msg("第".__LINE__."行 arry ", $arry);
debug_msg("第".__LINE__."行 上下標 ", $CS->ceil_item_num.'-'.$CS->floor_item_num);
debug_msg("第".__LINE__."行 item ", $item);
*/
for($i=0;$i<sizeof($item)-1;$i++){
	if($arry[$item[$i]][0]=='0'){  //做錯的題目
		$paper_vol=intval(substr($_GET['q_ep_id'], 9, 2));
		//debug_msg("第".__LINE__."行 item-i ", $item[$i]);
		$my_item=new Item_Structure($cs_id,$item[$i],$paper_vol);
		$question_select_pic=$my_item->get_item_select_pic();
		//debug_msg("第".__LINE__."行 my_item ", $my_item);
		//debug_msg("第".__LINE__."行 question_select_pic ", $question_select_pic);
		echo '<TR><TD width="100%" valign=top>
			<fieldset style="font-size:13px;color:#660000">
			<LEGEND class="s_title"><font class="title"><b>錯誤題目</b></font></LEGEND>';
		echo '<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">';

		if(isset($showfig)){		unset($showfig);	}
		$showfig=explode(".", $my_item->item_filename);

		$PImgProp['item_filename']=GetImageSize($cs_path.$my_item->item_filename);
		$showpic=modify_pic_pix($cs_path.$my_item->item_filename, '550');
		//debug_msg("第".__LINE__."行 PImgProp[item_filename] ", $PImgProp['item_filename']);
		//debug_msg("第".__LINE__."行 showpic ", $showpic);
		$showfig[0]=str2compiler($showfig[0]);
		echo '<tr><td width="100" align="right" class="s_title">題目：</td><td class="s_title"><img border="0" src="viewfig.php?list='.$showfig[0].'&tpp='.$showfig[1].'"  width="'.$showpic[0].'" height="'.$showpic[1].'"></td></tr>';
		echo '<tr><td width="100" align="right" class="s_title">所有選項：</td><td class="s_title">';
		for($ii=0;$ii<sizeof($question_select_pic);$ii++){
			if(isset($showfig)){		unset($showfig);	}
			$showfig=explode(".", $my_item->op_pieces[$ii]);
			$showfig[0]=str2compiler($showfig[0]);
			$showpic=modify_pic_pix($cs_path.$my_item->op_pieces[$ii], '550');
			echo '<img border="0" src="viewfig.php?list='.$showfig[0].'&tpp='.$showfig[1].'"  width="'.$showpic[0].'" height="'.$showpic[1].'"><br>';
		}
		//echo '<img border="0" src="'._ADP_URL.'images/5th.gif"><br>';
		echo '</td></tr>';
		//echo '<tr><td width="120" align="right">所有選項：</td><td><img border="0" src="'.$my_item->get_item_select_pic.'"></td></tr>';

		if(isset($showfig)){		unset($showfig);	}
		//print_r($arry[$item[$i]][1]);
		if($arry[$item[$i]][1]==5){  //學生選了第五個答案
			echo '<tr><td width="100" class="s_title">學生答案：</td><td class="s_title"><img border="0" src="'._ADP_URL.'images/5th.gif"></td></tr>';
		}else{
			$showfig=explode(".", $my_item->op_pieces[$arry[$item[$i]][1]-1]);
			$showfig[0]=str2compiler($showfig[0]);
			$showpic=modify_pic_pix($cs_path.$my_item->op_pieces[$arry[$item[$i]][1]-1], '550');
			//echo '<tr><td width="100" class="s_title">學生答案：</td><td><img border="0" src="'.$question_select_pic[$arry[$item[$i]][1]-1].'"></td></tr>';
			echo '<tr><td width="100" align="right" class="s_title">學生答案：</td><td class="s_title"><img border="0" src="viewfig.php?list='.$showfig[0].'&tpp='.$showfig[1].'" width="'.$showpic[0].'" height="'.$showpic[1].'"></td></tr>';
		}
		if(isset($showfig)){		unset($showfig);	}
		$showfig=explode(".", $my_item->op_pieces[$my_item->get_item_correct_answer()-1]);
		$showfig[0]=str2compiler($showfig[0]);
		$showpic=modify_pic_pix($cs_path.$my_item->op_pieces[$my_item->get_item_correct_answer()-1], '550');
		//echo '<tr><td width="100" class="s_title">正確答案：</td><td><img border="0" src="'.$question_select_pic[$my_item->get_item_correct_answer()-1].'"></td></tr>';
		echo '<tr><td width="100" align="right" class="s_title">正確答案：</td><td class="s_title"><img border="0" src="viewfig.php?list='.$showfig[0].'&tpp='.$showfig[1].'" width="'.$showpic[0].'" height="'.$showpic[1].'"></td></tr>';
		echo '</table>';
		echo '</fieldset><br></TD></TR>';
	}
}

echo '</table>';

?>
