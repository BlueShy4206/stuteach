<?php
##############   輸出學生歷來學習資料物件     ###############
require_once "adp_core_class.php";

Class Diag_Report_TCC{
	var $Student_Id;
	var $Concept_Object;
	var $test_times;
	var $student_basic_data;
	var $concept_data;
	var $student_concept_history_score_percentage;
	var $remedy_data;
	var $threshold;
	var $date;
	var $score;
	var $time;
	var $percentage;
	var $student_pass_rate;

	function Diag_Report_TCC($input_student,$input_concept){
		global $dbh;

		$this->stu_data=new UserData($input_student);
		$this->Concept_Object=new Concept_Structure($input_concept,1);
		$this->Concept_Object->set_remedy_data();
		if($this->Concept_Object->check_indicator()==1){
			$this->Concept_Object->set_indicator_sequence();
		}
		$this->cs_id=$input_concept;
		$temp_remedy_data=$this->Concept_Object->get_remedy_data();
		$this->threshold=$temp_remedy_data->get_threshold();
		$this->Student_Id=$input_student;
		$this->student_basic_data['firm']=id2CityFirm($this->stu_data->firm_id);
		$this->student_basic_data['sn']=$input_student;
		$this->student_basic_data['學生姓名']=$this->stu_data->uname;
		$this->student_basic_data['學生性別']=$this->stu_data->sex;
		$this->student_basic_data['city']=$this->stu_data->city_name;
		$this->student_basic_data['學校']=$this->stu_data->organization_name;
		$this->student_basic_data['年級']=num2chinese($this->stu_data->grade).'年級';
		$this->student_basic_data['班級']=num2chinese($this->stu_data->grade)."年".num2chinese($this->stu_data->class_name)."班";

      $this->concept_title=CSid2FullName($this->cs_id);
      //debug_msg("第".__LINE__."行 this ", $this);
      //die();
		$sql = "select * from exam_record where user_id = '".$input_student."' and cs_id='".$input_concept."' ORDER BY exam_sn ASC";
		//echo "<br>$sql<br>";
		$result = $dbh->query($sql);
		$this->test_times=$result->numRows();
		while ($data = $result->fetchRow()) {
			$this->date[]=$data['date'];
			$this->time[]=$data['during_time'];
			$this->score[]=$data['score'];
			$this->percentage[]=$data['degree'];
			$this->remedy_rate[]=$data['remedy_rate'];
			$this->questions[]=$data['questions'];   //試卷內容題號(即出題順序)
			$this->org_res[]=$data['org_res'];    //原始作答情形
			$this->binary_res[]=$data['binary_res'];   //二元作答情形
			$this->done_items[]=$data['done_items'];   //施測題數
			$this->total_items[]=$data['total_items'];   //試卷總題數
			$this->exam_title[]=$data['exam_title'];   //卷別
		}
		for($j=0;$j<count($this->remedy_rate);$j++){
			$this->pieces = explode(_SPLIT_SYMBOL, $this->remedy_rate[$j]);
			for($i=0;$i<count($this->pieces);$i++){
				$this->student_pass_rate[$j][$i]=$this->pieces[$i];    //第j次第i個概念通過百分比
			}
			//debug_msg("第".__LINE__."行 this->remedy_rate ", $this->remedy_rate);
			//debug_msg("第".__LINE__."行 this->student_pass_rate ", $this->student_pass_rate);
		}
		$this->remedy_data=$this->Concept_Object->get_remedy_data()->get_structure();
		$this->threshold=$this->Concept_Object->get_remedy_data()->get_threshold();
		//debug_msg("第".__LINE__."行 this->remedy_data ", $this->remedy_data);
		//debug_msg("第".__LINE__."行 this->threshold ", $this->threshold);
	}

	function print_header(){
		$print_data='<div id="diagnosisTEC">
			<table width="670" border="0" align="center" cellpadding="2" cellspacing="0">';
      $print_data.='<tr>
    <td valign="top" scope="col">
  <tr>
    <td align="center" valign="top" scope="col"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td scope="col" align="center"><img src="'._THEME_IMG.'diag-1.jpg" /></td>
        </tr>
    </table>
      </td>
      </tr>';

		return $print_data;
	}

	function print_foreword(){
      global $dbh;
      //debug_msg("第".__LINE__."行 this->score ", $this->score);
      $upper_score=ceil($this->score[$this->test_times-1]/10)*10;
      if($upper_score<=59){
         $upper_score=59;
      }
      $sql = "select judgment from exam_judgment WHERE upper_score>='$upper_score' AND lower_score<='$upper_score'";
      //debug_msg("第".__LINE__."行 sql ", $sql);
      $result = $dbh->query($sql);
		while ($data = $result->fetchRow()) {
         $this->judgment[]=$data[judgment];
      }
      $seed=mt_rand(1, count($this->judgment));
		$choosen=explode(_SPLIT_SYMBOL, $this->judgment[$seed-1]);
		$user_str=array( $this->stu_data->uname, $this->Concept_Object->cs_name);
      //debug_msg("第".__LINE__."行 user_str ", $user_str);
      for($i=0;$i<count($choosen);$i++){
         if(!empty($user_str[$i]))  $user_str[$i]='【<font color="#0000FF">'.$user_str[$i].'</font>】';
         $foreword.=$choosen[$i].$user_str[$i];
      }

      $print_data='<tr>
    <td valign="top" scope="col">
  <tr>
    <td align="center" valign="top" scope="col"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td scope="col" align="left" class="d_title">'.$foreword.'</td>
        </tr>
    </table></td>
  </tr>';

		return $print_data;
	}

	function print_student_basic_data(){

		$print_data='
  <tr>
    <td align="center" valign="top" scope="col">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top" scope="col"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="100%" colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2">';

		$print_data.='<table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr>
                  <td scope="col" align="center"><img src="'._THEME_IMG.'diag_t1.jpg" /></td>
               </tr>
                  <tr>
                    <td align="left" scope="col" bgcolor="#9BD8E7"><span class="d_title"> 基 本 資 料</span></td>
                  </tr>
                  <tr>
                    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="20%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title01" scope="col">測驗地點</td>
                        <td width="30%" align="left" class="d_s_title02" scope="col">　'.$this->student_basic_data['firm'].'</td>
                        <td width="20%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title02" scope="col">測驗學號</td>
                        <td width="30%" align="left" class="d_s_title03" scope="col">　'.$this->student_basic_data['sn'].'</td>
                      </tr>
                      <tr>
                        <td width="20%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title04" scope="col">學生姓名</td>
                        <td align="left" class="d_s_title05">　'.$this->student_basic_data['學生姓名'].'</td>
                        <td width="20%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title05" scope="col">學生性別</td>
                        <td align="left" class="d_s_title06">　'.$this->student_basic_data['學生性別'].'</td>
                      </tr>
                      <tr>
                        <td width="20%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title04" scope="col">縣市地點</td>
                        <td align="left" class="d_s_title05">　'.$this->student_basic_data['city'].'</td>
                        <td width="20%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title05" scope="col">就讀學校</td>
                        <td align="left" class="d_s_title06">　'.$this->student_basic_data['學校'].'</td>
                      </tr>
                      <tr>
                        <td width="20%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title07" scope="col">就讀年級</td>
                        <td align="left" class="d_s_title08">　'.$this->student_basic_data['年級'].'</td>
                        <td width="20%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title08" scope="col">就讀班級</td>
                        <td align="left" class="d_s_title09">　'.$this->student_basic_data['班級'].'</td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                  <td scope="col" align="center"><img src="'._THEME_IMG.'diag_t2.jpg" /></td>
               </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              ';

		return $print_data;
	}

	//-- 最近一次(本次)施測成績表格
	function print_least_data(){
		$print_data='<tr>
                <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr>
                  <td scope="col" align="center"><img src="'._THEME_IMG.'diag_t1.jpg" /></td>
               </tr>
                  <tr>
                    <td align="left" scope="col" bgcolor="#9BD8E7"><span class="d_title"> 本 次 測 驗 結 果</span></td>
                  </tr>
                  <tr>
                    <td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="d_line">
                        <tr>
                          <td width="20%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title01" scope="col">施測單元</td>
                          <td height="35" colspan="3" align="left" class="d_s_title03" scope="col">　'.$this->concept_title.'</td>
                          </tr>
                        <tr>
                          <td width="20%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title04" scope="col">測驗總題數</td>
                          <td width="30%" align="left" class="d_s_title05">　'.$this->total_items[$this->test_times-1].'</td>
                          <td width="20%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title05" scope="col">施測題數</td>
                          <td width="30%" align="left" class="d_s_title06">　'.$this->done_items[$this->test_times-1].'</td>
                        </tr>
                        <tr>
                          <td width="20%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title04" scope="col">施測日期</td>
                          <td align="left" class="d_s_title05">　'.$this->date[$this->test_times-1].'</td>
                          <td width="20%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title05" scope="col">施測時間</td>
                          <td align="left" class="d_s_title06">　'.$this->time[$this->test_times-1].'</td>
                        </tr>
                        <tr>
                          <td width="20%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title07" scope="col">測驗成績</td>
                          <td align="left" class="d_s_title08">　'.$this->score[$this->test_times-1].'</td>
                          <td width="20%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title08" scope="col">百分等級</td>
                          <td align="left" class="d_s_title09">　'.$this->percentage[$this->test_times-1].'</td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                  <td scope="col" align="center"><img src="'._THEME_IMG.'diag_t2.jpg" /></td>
               </tr>
                </table></td>
              </tr>
              ';

		return $print_data;
	}

	//-- 最近一次(本次)作答情形
	function print_least_user_ans(){
		$print_data="";
		$this->myquestions = explode(_SPLIT_SYMBOL, $this->questions[$this->test_times-1]);
		$this->user_ans = explode(_SPLIT_SYMBOL, $this->org_res[$this->test_times-1]);   //原始作答的陣列
		$this->user_ans_num=count($this->user_ans)-1;
		for($i=0;$i<$this->user_ans_num;$i++){
			$print_data.=$this->myquestions[$i].'】->'.$this->user_ans[$i]."<br>";
		}

		//echo $print_data;
		return $print_data;
	}


	function print_graphic_data($q_cs_id, $report_for_pc){
		//global $report_for_pc;

		$pic_w=intval($this->Concept_Object->PImgProp['percent_gif'][0]);  //圖片寬度
		$mini=1;
		while($pic_w>650){
			$mini=$mini-0.01;
			$pic_w=ceil(intval($this->Concept_Object->PImgProp['percent_gif'][0])*$mini);
		}
		$pic_h=ceil(intval($this->Concept_Object->PImgProp['percent_gif'][1])*$mini);  //輸出圖片高度
		if(isset($showfig)){		unset($showfig);	}
		$showfig=explode(".", $this->Concept_Object->percent_gif);
		$showfig[0]=str2compiler($showfig[0]);
		//print_r($showfig);
		$print_data='<tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr>
                  <td scope="col" align="center"><img src="'._THEME_IMG.'diag_t1.jpg" /></td>
               </tr>
                  <tr>
                    <td align="left" scope="col" bgcolor="#9BD8E7"><span class="d_title"> 百 分 等 級 圖</span></td>
                  </tr>
                  <tr>
                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td scope="col" align="center" bgcolor="#9BD8E7">';

		if($report_for_pc==1){  //僅在電腦上顯示
			$print_data.='<img src="'._ADP_URL.'viewfig2.php?list='.$showfig[0].'&tpp='.$showfig[1].'&cs_id='.$q_cs_id.'" width="'.$pic_w.'" height="'.$pic_h.'" /></td>';
		}else{
			$orgfile=$_SESSION['cs_path'].$this->Concept_Object->percent_gif;
			$newfile=_ADP_TMP_UPLOAD_PATH.$this->Concept_Object->percent_gif;
			$tmp_url=_ADP_URL."data/tmp/";
			copy($orgfile, $newfile);
			$print_data.='<img src="'.$tmp_url.$this->Concept_Object->percent_gif.'" width="'.$pic_w.'" height="'.$pic_h.'" /></td>';
		}
        $print_data.='</tr>
                    </table></td>
                  </tr>
                  <tr>
                  <td scope="col" align="center"><img src="'._THEME_IMG.'diag_t2.jpg" /></td>
               </tr>
                </table></td>
              </tr>';

		return $print_data;
	}

	function print_sturcture_gif(){
		$pic_w=intval($this->Concept_Object->PImgProp['sturcture_gif'][0]);  //圖片寬度
		$mini=1;
		while($pic_w>650){
			$mini=$mini-0.01;
			$pic_w=ceil(intval($this->Concept_Object->PImgProp['sturcture_gif'][0])*$mini);
		}
		$pic_h=ceil(intval($this->Concept_Object->PImgProp['sturcture_gif'][1])*$mini);  //輸出圖片高度
		$print_data='<p align="center"><u><b><font size="4">本單元試卷知識結構圖</font></b></u></p><p align="center"><img border="0" src="'.$this->Concept_Object->get_sturcture_gif_url().'" width="'.$pic_w.'" height="'.$pic_h.'"></p><br>'."\n";

		return $print_data;
	}


	function print_concept_history_data(){

		$print_data='<tr>
                <td colspan="2"><br><table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr>
                  <td scope="col" align="center"><img src="'._THEME_IMG.'diag_t1.jpg" /></td>
               </tr>
                  <tr>
                    <td align="left" scope="col" bgcolor="#9BD8E7"><span class="d_title"> 本 單 元 學 習 紀 錄</span></td>
                  </tr>
                  <tr>
                    <td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="d_tableline">
                        <tr>
                          <td height="35" colspan="6" align="center" class="" scope="col">'.$this->concept_title.'</td>
                          </tr>
                        <tr><td><table width="100%" border="0" cellpadding="0" cellspacing="0" class=""><tr>
                          <td width="15%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title31" scope="col">次數</td>
                          <td width="24%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title31" scope="col">測驗日期</td>
                          <td width="13%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title31" scope="col">時間</td>
                          <td width="12%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title31" scope="col">成績</td>
         <!-- <td width="12%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title31" scope="col">百分等級</td>  -->
                          <td width="24%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title32" scope="col">題數</td>
                        </tr>';

		for($i=0;$i<$this->test_times;$i++){
			$print_data.="<tr>\n";
			$print_data.='<td width="15%" height="35" align="center" class="d_s_title31" scope="col">'.($i+1)."</td>\n";
			$print_data.='<td width="24%" align="left" class="d_s_title31">　'.$this->date[$i]."</td>\n";
			$print_data.='<td width="13%" align="center" class="d_s_title31">'.$this->time[$i]."</td>\n";
			$print_data.='<td width="12%" height="35" align="center" class="d_s_title31" scope="col">'.$this->score[$i]."</td>\n";
			//$print_data.='<td width="12%" align="center" class="d_s_title31">'.$this->percentage[$i]."</td>\n";
			$print_data.='<td width="24%" align="center" class="d_s_title32">'.$this->done_items[$i]."題/".$this->total_items[$i]."題</td>\n";
			//$print_data.='<td width="24%" align="center" class="d_s_title32">'.$this->total_items[$i]."題</td>\n";
			$print_data.="</tr>\n";
		}
		$print_data.='</table></td>
                  </tr>
                </table></td></tr>
                <tr>
                  <td scope="col" align="center"><img src="'._THEME_IMG.'diag_t2.jpg" /></td>
               </tr>
                </table></td>
              </tr>';

		return $print_data;
	}

	//---學習概念列表
	function print_remedy_data($report_for_pc){
		$print_data='<tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr>
                  <td scope="col" align="center"><img src="'._THEME_IMG.'diag_t1.jpg" /></td>
               </tr>
                  <tr>
                    <td align="left" class="d_title" scope="col" bgcolor="#9BD8E7"> 概 念 診 斷 報 告</td>
                  </tr>
					<tr>
                    <td align="left" scope="col" bgcolor="#9BD8E7">說明：◎表示<font color="0000ff">通過</font>該概念；Ｘ表示<font color="ff0000">未通過</font>該概念。
					</td>
                  </tr>
                  <tr>
                    <td><table width="100%" border="0" cellpadding="4" cellspacing="0" class="d_tableline">
                      <tr>';
		$print_data.='<td width="50%" colspan="2" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title41">概念列表</td>';
		$tmp="";
		if($this->test_times>1){
			$tmp='colspan="'.$this->test_times.'"';
		}
		$print_data.='<td '.$tmp.' width="30%" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title41">診斷結果</td>'."\n";
		/*
		if($report_for_pc==1){  //僅在電腦上顯示
			$print_data.='<td width="20%" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title42">線上學習</td>';
		}
		*/
		$print_data.="</tr>\n";

		for($i=0;$i<sizeof($this->remedy_data);$i++){
			for($j=0;$j<$this->test_times;$j++){
				if($this->student_pass_rate[$j][$i]>=$this->threshold[$i]){
					$this->student_pass_rate2[$j][$i]="◎";
				}else{
					if($report_for_pc==1){
						$this->student_pass_rate2[$j][$i]='<font color="ff0000">Ｘ</font>'.'<br><a href="javascript:hi'.$i.$j.'()" >查詢</a>'.
					'<script language="javascript" type="text/javascript">
<!--
function hi'.$i.$j.'()
{
strFeatures = "toolbar=0,menubar=0,location=0,directories=0,status=0,scrollbars=yes,resizable=yes";
window.open("modules.php?op=modload&name=ExamResult&file=viewErrors&screen=all&q_user_id='.$this->Student_Id.'&q_ep_id='.$this->exam_title[$j].'&remedy='.($i+1).'" , "123", strFeatures);
}
//-->
</script>';
					}else{
						$this->student_pass_rate2[$j][$i]="Ｘ";
					}
				}
			}
		}

		for($i=0;$i<sizeof($this->remedy_data);$i++){
			$print_data.="<tr>\n";
			//處理分隔
			if(strpos($this->remedy_data[$i], "】") == TRUE){
            $rem = explode("】", $this->remedy_data[$i]);
            $rem[0].="】";
         }else{
            $rem[0]="【概念".($i+1)."】";
            $rem[1]=$this->remedy_data[$i];
			}
			$print_data.='<td width="14%" align="right" class="d_s_title42">'.$rem[0]."</td>\n";
			$print_data.='<td width="36%" align="left" class="d_s_title41">'.$rem[1]."</td>\n";
			for($j=0;$j<$this->test_times;$j++){
				$print_data.='<td width="'.(30/$this->test_times).'%" align="center" class="d_s_title41">'.($this->student_pass_rate2[$j][$i]).'</td>';
			}
/*
			if($report_for_pc==1){  //僅在電腦上顯示
				if(isset($showfig)){		unset($showfig);	}
				$showfig=explode(".", $this->Concept_Object->RI_pieces[$i]);
				$showfig[0]=str2compiler($showfig[0]);
				$print_data.='<td width="20%" align="center" class="d_s_title42">';
				$exec_file=$_SESSION['cs_path'].$this->Concept_Object->RI_pieces[$i];
				if(file_exists($exec_file) && is_file($exec_file)){
					$print_data.='<a href="'._ADP_URL.'viewfig2.php?list='.$showfig[0].'&tpp='.$showfig[1].'&cs_id='.$this->cs_id.'" target="_blank">教材'.($i+1).'</a>';
				}else{
					$print_data.="　";
				}
				$print_data.='</td>';
			}
*/
			$print_data.="</tr>\n";
		}

		$print_data.='</table></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                  <td scope="col" align="center"><img src="'._THEME_IMG.'diag_t2.jpg" /></td>
               </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
          </table></td>
        </tr>

      </table>
    </td>
  </tr>';

		return $print_data;
	}

	//---錯誤概念列表
	function print_error_concept($report_for_pc){
		$print_data='<tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr>
                  <td scope="col" align="center"><img src="'._THEME_IMG.'diag_t1.jpg" /></td>
               </tr>
                  <tr>
                    <td align="left" class="d_title" scope="col" bgcolor="#9BD8E7"> 錯 誤 概 念 診 斷 報 告</td>
                  </tr>
					<tr class="d_s_title33">
                    <td align="left" scope="col" bgcolor="#FFFFFF" class="d_s_title33">說明：Ｘ表示<font color="ff0000">未通過</font>該概念。<hr>測完【'.$this->Concept_Object->cs_name.'】這個單元，我們發現【'.$this->stu_data->uname.'】仍有小小的地方需要改進哦！下面是你在本試卷中錯誤的概念列表，可以點「查詢」看看錯了哪一道題目。如果你想把錯的地方再學一次，可以點「動畫」進入學習。對的地方不要忘記，錯的地方要再次學習並理解吸收，你會有更傑出的表現。
					</td>
                  </tr>
                  <tr>
                    <td><table width="100%" border="0" cellpadding="4" cellspacing="0" class="d_tableline">
                      <tr>';
		$print_data.='<td width="50%" colspan="2" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title41">本卷錯誤概念列表</td>';
		$print_data.='<td width="30%" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title41">診斷結果</td>'."\n";
		if($report_for_pc==1){  //僅在電腦上顯示
			$print_data.='<td width="20%" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title42">線上學習</td>';
		}
		$print_data.="</tr>\n";

		for($i=0;$i<sizeof($this->remedy_data);$i++){
			for($j=0;$j<$this->test_times;$j++){
				if($this->student_pass_rate[$j][$i]>=$this->threshold[$i]){
					$this->student_pass_rate1[$j][$i]="◎";
				}else{
					if($report_for_pc==1){
						$this->student_pass_rate1[$j][$i]='<font color="ff0000">Ｘ</font>'.' <a href="javascript:hi'.$i.$j.'()" >查詢</a>'.
					'<script language="javascript" type="text/javascript">
<!--
function hi'.$i.$j.'()
{
strFeatures = "toolbar=0,menubar=0,location=0,directories=0,status=0,scrollbars=yes,resizable=yes";
window.open("modules.php?op=modload&name=ExamResult&file=viewErrors&screen=all&q_user_id='.$this->Student_Id.'&q_ep_id='.$this->exam_title[$j].'&remedy='.($i+1).'" , "123", strFeatures);
}
//-->
</script>';
					}else{
						$this->student_pass_rate1[$j][$i]="Ｘ";
					}
				}
			}
		}

		for($i=0;$i<sizeof($this->remedy_data);$i++){
         if($this->student_pass_rate[$this->test_times-1][$i]<$this->threshold[$i]){
            $print_data.="<tr>\n";
            if(strpos($this->remedy_data[$i], "】") == TRUE){
               $rem = explode("】", $this->remedy_data[$i]);
               $rem[0].="】";
            }else{
               $rem[0]="【概念".($i+1)."】";
               $rem[1]=$this->remedy_data[$i];
			   }
			   $print_data.='<td width="14%" align="right" class="d_s_title42">'.$rem[0]."</td>\n";
			   $print_data.='<td width="36%" align="left" class="d_s_title41">'.$rem[1]."</td>\n";
//			echo "<td width=\"10%\">".$status[$i]."</td>\n";
			   for($j=0;$j<$this->test_times;$j++){
               $print_data.='<td width="30%" align="center" class="d_s_title41">'.($this->student_pass_rate1[$j][$i]).'</td>';
			   }

			   if($report_for_pc==1){  //僅在電腦上顯示
               if(isset($showfig)){		unset($showfig);	}
               $showfig=explode(".", $this->Concept_Object->RI_pieces[$i]);
               $showfig[0]=str2compiler($showfig[0]);
               $print_data.='<td width="20%" align="center" class="d_s_title42">';
               $exec_file=$_SESSION['cs_path'].$this->Concept_Object->RI_pieces[$i];
               if(file_exists($exec_file) && is_file($exec_file)){
                  $print_data.='<a href="'._ADP_URL.'viewfig2.php?list='.$showfig[0].'&tpp='.$showfig[1].'&cs_id='.$this->cs_id.'" target="_blank">教材'.($i+1).'</a>';
               }else{
                  $print_data.="　";
               }
               $print_data.='</td>';
            }
			   $print_data.="</tr>\n";
			}
      }

		$print_data.='</table></td>
              </tr>
              <tr>
                  <td scope="col" align="center"><img src="'._THEME_IMG.'diag_t2.jpg" /></td>
               </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
          </table></td>
        </tr>

      </table>
    </td>
  </tr>';

		return $print_data;
	}

	//---錯誤題目列表
	function print_error_items($report_for_pc){
		$print_data='<tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr>
                  <td scope="col" align="center"><img src="'._THEME_IMG.'diag_t1.jpg" /></td>
               </tr>
                  <tr>
                    <td align="left" class="d_title" scope="col" bgcolor="#9BD8E7"> 錯 誤 題 目 列 表</td>
                  </tr>
					<tr class="d_s_title33">
                    <td align="left" scope="col" bgcolor="#FFFFFF" class="d_s_title33">測完【'.$this->Concept_Object->cs_name.'】這個單元，我們發現【'.$this->stu_data->uname.'】做錯了下列題目。錯的地方要再次學習並理解吸收，你會有更傑出的表現。
					</td>
                  </tr><tr>
                    <td><table width="100%" border="0" cellpadding="4" cellspacing="0" class="d_tableline">
                      <tr><td>';

      $BinaryRes = explode(_SPLIT_SYMBOL, $this->binary_res[0]);
      $OrgRes = explode(_SPLIT_SYMBOL, $this->org_res[0]);
      $ItemNums = explode(_SPLIT_SYMBOL, $this->questions[0]);
      for($i=0;$i<sizeof($ItemNums)-1;$i++){
         $arry[$ItemNums[$i]-$this->Concept_Object->ceil_item_num][0]=$BinaryRes[$item_num[$i]-1];
         $arry[$ItemNums[$i]-$this->Concept_Object->ceil_item_num][1]=$OrgRes[$i];
      }
      for($i=0;$i<count($BinaryRes)-1;$i++){
         //echo "<br> $i <br>";
         if($BinaryRes[$i]=='0'){  //做錯的題目
            $OrgUserAns=$arry[$item[$i]][1];
            $print_data.=$this->get_items_html($this->exam_title[$this->test_times-1], $i+1, $OrgUserAns);
         }
      }




		$print_data.='</td></tr></table></td>
              </tr>
              <tr>
                  <td scope="col" align="center"><img src="'._THEME_IMG.'diag_t2.jpg" /></td>
               </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
          </table></td>
        </tr>

      </table>
    </td>
  </tr>';

		return $print_data;
	}


	function get_items_html($q_ep_id, $ItemNum, $OrgUserAns){

		$paper_vol=intval(substr($q_ep_id, 9, 2));
		//debug_msg("第".__LINE__."行 item-i ", $item[$i]);
		$my_item=new Item_Structure($this->cs_id,$ItemNum,$paper_vol);
		$question_select_pic=$my_item->get_item_select_pic();
		//debug_msg("第".__LINE__."行 my_item ", $my_item);
		//debug_msg("第".__LINE__."行 question_select_pic ", $question_select_pic);
		$cs_path=$this->Concept_Object->cs_path;
      echo '<fieldset style="font-size:13px;color:#660000">
			<LEGEND class="s_title"><font class="title"><b>第'.$ItemNum.'題錯誤分析</b></font></LEGEND>';
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
		if($OrgUserAns==5){  //學生選了第五個答案
			echo '<tr><td width="100" class="s_title">學生答案：</td><td class="s_title"><img border="0" src="'._ADP_URL.'images/5th.gif"></td></tr>';
		}else{
			$showfig=explode(".", $my_item->op_pieces[$OrgUserAns-1]);
			$showfig[0]=str2compiler($showfig[0]);
			$showpic=modify_pic_pix($cs_path.$my_item->op_pieces[$OrgUserAns-1], '550');
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
		echo '</fieldset><br>';


      return $print_data;
	}


   function print_radar_gif($used_file, $report_for_pc){
      global $dbh;

      $rem_str=$this->Concept_Object->get_remedy_data()->get_structure();
      $remedy2item=$this->Concept_Object->get_remedy_data()->get_remedy2item();   //補救概念所對應之題目

      $sql = "select item_num, edu_parameter from concept_item where exam_paper_id = '".$this->exam_title[$this->test_times-1]."' ORDER BY item_num ASC";
      $result = $dbh->query($sql);
      while ($data = $result->fetchRow()) {
         $ItemEduPara[$data[item_num]]=$data[edu_parameter];
      }
      //先只處理一次的狀況
      //$BinaryRes = explode(_SPLIT_SYMBOL, $this->binary_res[$this->test_times-1]);
      $BinaryRes = explode(_SPLIT_SYMBOL, $this->binary_res[0]);
      array_pop($BinaryRes);
      //$this->binary_res[]
      $comment='<font color="#0000FF">'.$this->stu_data->uname.'</font>';
      //$m=array(44,72,62,86);
      $sql = "SELECT * FROM `exam_edu_parameter` WHERE `show` =1 order by sn ASC";
      $result =$dbh->query($sql);
      $i=0;
      while ($row=$result->fetchRow()){
         //$header.=iconv("big5", "UTF-8", $row[edu_parameter]).",";
         //$header.=$row[edu_parameter].",";
         $EduParaSn[]=$row[sn];
         $EduPara[]=$row[edu_parameter];
         $EduParaExPlain[]=$row[explanation];
         //$comment.="在「".$row[edu_parameter].'」方面答對了'.$m[$i]."％，";
         $i++;
      }
      $ii=count($EduParaSn);
      $jj=count($ItemEduPara);
      $kk=sizeof($remedy2item);
      for($i=0;$i<$ii;$i++){
         //debug_msg("第".__LINE__."行 i ", $i);
         //echo "<br>";
         $RemedyRec=array();  //各向度的補救概念
         $EduParaCount[$EduParaSn[$i]]=0;   //各向度的題目數
         $EduParaCorrectNums[$EduParaSn[$i]];   //各向度的答對題目數
         for($j=1;$j<=$jj;$j++){
            $pieces1 = explode(_SPLIT_SYMBOL, $ItemEduPara[$j]);
            if (in_array($EduParaSn[$i], $pieces1)) {
            //if(strpos(_SPLIT_SYMBOL.$ItemEduPara[$j], $EduParaSn[$i]) >0){ //該題為某向度
               $EduParaCount[$EduParaSn[$i]]++;
               if($BinaryRes[$j-1]>=1){ //該題答對
                  $EduParaCorrectNums[$EduParaSn[$i]]++;
                  $SoFarCorrectItemNum=$j; //記錄題號
               }else{
                  $EduParaWrongNums[$EduParaSn[$i]].="--".$j;
                  $SoFarWrongItemNum=$j;
                  for($k=0;$k<$kk;$k++){
                     $pieces = explode(_SPLIT_SYMBOL, $remedy2item[$k+1]);
                     if (in_array($j, $pieces)) {
                        if(!in_array(($k+1), $RemedyRec)){
                           $RemedyRec[]=($k+1);  //紀錄補救概念代號
                           //$EduParaWrongRemedy[$EduParaSn[$i]].=($k+1).", ";
                           $EduParaWrongRemedy[$EduParaSn[$i]][]=($k+1);
                           break;
                        }
                     }
                  }
               }
            }
         }
         if(count($EduParaWrongRemedy[$EduParaSn[$i]])>=1){
            sort($EduParaWrongRemedy[$EduParaSn[$i]]);
         }
         //debug_msg("第".__LINE__."行 RemedyRec ", $RemedyRec);
         unset($RemedyRec);

         if($EduParaCount[$EduParaSn[$i]]>0){
            $EduParaCorrectRate[$EduParaSn[$i]]=$EduParaCorrectNums[$EduParaSn[$i]]*100/$EduParaCount[$EduParaSn[$i]];
            $rate=ceil($EduParaCorrectRate[$EduParaSn[$i]]);
         }else{
            //$EduParaCorrectRate[$EduParaSn[$i]]=100;
            unset($EduPara[$i]);
            unset($EduParaExPlain[$i]);
            //unset($EduParaSn[$i]);
         }
         //debug_msg("第".__LINE__."行 EduParaCorrectRate ", $EduParaCorrectRate);
         //debug_msg("第".__LINE__."行 EduParaSn ", $EduParaSn);
         if($i==count($EduParaSn)-1){   $symbol="。";
         }else{  $symbol="；";    }

         if($i!=0){   $StartWord="而";
         }else{
                  $StartWord="";}

         $rate=ceil($EduParaCorrectRate[$EduParaSn[$i]]);
         //$word=array("舉例來說", "這表示", "也就是說");
         //$seed=rand(1, count($word));


         if($rate>=100){
            $ExampleComment="";
         }else{
            /*
            for($k=0;$k<count($remedy2item);$k++){
               //$esc.=$remedy2item[$k+1]."----".strval($SoFarCorrectItemNum)."<br>";
               $ConceptID[$i][$k+1]++;
               if(strpos(_SPLIT_SYMBOL.$remedy2item[$k+1], strval($SoFarWrongItemNum)) == TRUE){
                  $WrongExample=$rem_str[$k-1];

                  if(strpos($WrongExample, "】") == TRUE){
                     $rem = explode("】", $WrongExample);
                     $WrongExample=$rem[1];
                  }

               }
            }
            */
            $seed=rand(1, count($EduParaWrongRemedy[$EduParaSn[$i]]));
            $WrongExample=$rem_str[$EduParaWrongRemedy[$EduParaSn[$i]][$seed-1]-1];
            //debug_msg("第".__LINE__."行 seed ", $seed);
            //debug_msg("第".__LINE__."行 seed0 ", $EduParaWrongRemedy[$EduParaSn[$i]][$seed-1]);
            if(strpos($WrongExample, "】") == TRUE){
               $rem = explode("】", $WrongExample);
               $WrongExample=$rem[1];
            }
            $ExampleComment="請加強《".$WrongExample."》的學習".$symbol;
         }

         //$ExampleComment=$word[$seed-1]."，"."針對《概念".$EduParaWrongRemedy[$EduParaSn[$i]]."》需要再加強".$symbol;
         if(isset($EduPara[$i])){
            $comment.=$StartWord.'對於【<font color="#0000FF">'.$this->Concept_Object->cs_name.'</font>】的「'.$EduPara[$i].'」學習完成度為<font color="#CC0000">'.$rate."％</font>，".$ExampleComment."<br>";
            $header.=$EduPara[$i].",";
         }
      }
      if(isset($EduParaCorrectRate)){
         $content[] = implode(",", $EduParaCorrectRate);
               $this->radarfile=time().rand(100000,999999);
   		$_SESSION[q]=$this->radarfile;
   		$filename=$this->radarfile.'.csv';

   		creat_csv($filename, $header, $content);

   //================

   		$print_data='<tr>
                   <td colspan="2">&nbsp;</td>
                 </tr>
                 <tr>
                   <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                     <td scope="col" align="center"><img src="'._THEME_IMG.'diag_t1.jpg" /></td>
                  </tr>
                     <tr>
                       <td align="left" class="d_title" scope="col" bgcolor="#9BD8E7"> 學 習 概 況 分 析 圖 </td>
                     </tr>
                     <tr>
                       <td align="center" class="d_title" scope="col" bgcolor="#9BD8E7"><table width="99%" border="0" cellspacing="0" cellpadding="0" bgcolor="#9BD8E7"><tr><td align="left" scope="col" bgcolor="#FFFFFF" class="d_s_title33">'.$comment.'</td></tr></table>';

         $print_data.='</td></tr><tr>
                       <td><table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#9BD8E7"><tr><td align="center" scope="col"><br>';
   		if($report_for_pc==1){   //僅在電腦上顯示
   			$print_data.='<img src="'._ADP_URL.$used_file.'?q='.$this->radarfile.'" />';
   		}else{
   			$tmp_url=_ADP_URL."data/tmp/";
   			file(_ADP_URL.$used_file."?q=".$this->radarfile."&report=".$report_for_pc);  //產生學習狀態雷達圖於tmp中
   			$print_data.='<img src="'.$tmp_url.$this->radarfile.'.png" />';
   		}

   		$print_data.='<br></td></tr><tr><td align="left"><br><font color="#FF0000">說明：1.紅色點的位置其數值愈大，代表本單元該能力愈強。<br>　　　2.紅色點所圍的黃色區域範圍愈大，代表本單元的學習狀態愈完整！<br><br></td></tr>
                           </table></td>
                     </tr>';

         $print_data.='<tr>
                     <td align="center" class="d_title" scope="col" bgcolor="#9BD8E7"><table width="99%" border="0" cellpadding="0" cellspacing="0" class=""><tr>
                             <td width="100%" height="35" align="left" background="'._THEME_IMG.'tit_bg03.gif" class="d_sg_text32" scope="col"  colspan="2">參數說明：</td>
                           </tr><tr>
                             <td width="35%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_sg_text31" scope="col">能力參數</td>
                             <td width="65%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_sg_text32" scope="col">參數意涵</td>
                           </tr>';
         foreach($EduPara as $key => $value){
   			$print_data.="<tr>\n";
   			$print_data.='<td width="35%" height="35" align="left" class="d_sg_text31" scope="col" bgcolor="#FFFFFF">'.$value."</td>\n";
   			$print_data.='<td width="65%" align="left" class="d_sg_text32" bgcolor="#FFFFFF"><font color="#0000FF">'.$EduParaExPlain[$key]."</font></td>\n";
   			$print_data.="</tr>\n";
   		}
         $print_data.='</table></td>
                  </tr>';

         $print_data.='<tr>
                     <td scope="col" align="center"><img src="'._THEME_IMG.'diag_t2.jpg" /></td>
                  </tr>
                   </table></td>
                 </tr>';
      }else{
         $print_data="";
      }

/*
      debug_msg("第".__LINE__."行 BinaryRes ", $BinaryRes);
      debug_msg("第".__LINE__."行 EduParaCorrectNums ", $EduParaCorrectNums);
      debug_msg("第".__LINE__."行 EduParaWrongNums ", $EduParaWrongNums);
      //debug_msg("第".__LINE__."行 EduParaCount ", $EduParaCount);
      //debug_msg("第".__LINE__."行 esc ", $esc);
      debug_msg("第".__LINE__."行 EduParaWrongRemedy ", $EduParaWrongRemedy);
      debug_msg("第".__LINE__."行 remedy2item ", $remedy2item);
*/
   

		return $print_data;
	}

	function print_feet(){

		$print_data='</table></div>';
		return $print_data;
	}

}


?>
