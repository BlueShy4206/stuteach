<?php
require_once "include/adp_API.php";

##################   UserData   取使用者資料物件     ##############################

class UserData {
	var $uid;  //流水號
	var $user_id;  //使用者代號
	var $uname;  //中文姓名
	var $email;  //電子郵件信箱
	var $sex;   //性別
	var $birthday;  //生日
	var $organization_id;   //服務學校代碼
	var $access_level;   //存取等級
	var $city_code;     //縣市代碼
	var $grade;      //年級  
	var $class_name;     //班級
	var $viewpass;   //密碼
	var $firm_id;   //補習班(施測地點)代號

	function UserData($user_id) {
		global $dbh;
		
		$this->user_id = $user_id;
		if (!empty($user_id)) {
			$sql = "select * from user_info, user_status where user_info.user_id = '{$this->user_id}' and user_status.user_id = '{$this->user_id}'";   
			$result = $dbh->query($sql);
			while ($data = $result->fetchRow()) {
				$this->uid=$data['uid'];
				$this->uname=$data['uname'];
				$this->email=$data['email'];
				$this->sex=$data['sex'];
				$this->birthday=$data['birthday'];
				$this->organization_id=$data['organization_id'];
				$this->city_code=$data['city_code'];
				$this->grade=$data['grade'];
				$this->class_name=$data['class'];
				$this->cht_class=num2chinese($data['grade']).'年'.num2chinese($data['class']).'班';
				$this->viewpass=pass2compiler($data['viewpass']);
				$this->access_level=$data['access_level'];
				$this->identity=$data['identity'];
				$this->tel=$data['tel'];
				$this->mobil=$data['mobil'];
				$this->address=$data['address'];
				$this->class_group=$data['class_group'];
				$this->firm_id=$data['firm_id'];
				$this->login_freq=$data['login_freq'];
				$this->auth_stop_time=$data['auth_stop_time'];
				$this->auth_start_time=$data['auth_start_time'];
				$this->starttimestamp=$data['starttimestamp'];
				$this->stoptimestamp=$data['stoptimestamp'];
				$this->lastip=$data['lastip'];
				$this->auth_start_date=$data['auth_start_date'];
				$this->auth_stop_date=$data['auth_stop_date'];
				$this->identity->$data['identity'];
			}

			$sql = "select type, name from organization where organization_id = '{$this->organization_id}'";
			$result = $dbh->query($sql);
			$data = $result->fetchRow();
			$this->organization_type=$data['type'];
			$this->organization_name=$data['name'];
			$this->city_name=id2city($this->city_code);
			$this->user_level=id2level($this->access_level);
			$this->firm=id2firm($this->firm_id);
			$this->cht_organization=id2CityOrg($this->organization_id);
		}
	}

	function get_parents($user_id){
		global $dbh;
		
		$sql = "select * from user_parents where user_id = '".$user_id."'";
		//echo $sql;
		$result = $dbh->query($sql);
		$data = $result->fetchRow();
		$sql = "select family_relation from user_family_relation where care_age_id = '{$data['care_age_id']}'";
		$data1 =& $dbh->getOne($sql);
		$data['family_relation']=$data1;
		$data['city_name']=id2city($data['city_code']);

		return $data;
	}

	function get_family($user_id){
		global $dbh;

		$sql = "select * from user_family where user_id = '{$user_id}'";
		$result = $dbh->query($sql);
		$i=1;
		while($data = $result->fetchRow()){
			$this->family[$i]=$data;
			$this->family[$i]['school']=id2org($data['organization_id']);
			$this->family[$i]['grade_class']=$data['grade'].'年'.$data['class'].'班';
			$i++;
		}
		return $this->family;
	}
	
	function getCourse(){
		global $dbh;

		//require_once "CourseData.php";

		$sql = "select * from user_course where user_id = '".$this->user_id."' order by course_id";
		$result = $dbh->query($sql);
		$i=0;
		while($data = $result->fetchRow()){
			$course[$i]=$data['course_id'];
			$i++;
		}

		return $course;
	}

}

##################   FirmData   取補習班資料物件     ##############################

class FirmData {

	function FirmData($firm_id) {
		global $dbh;
		
		$this->firm_id = $firm_id;
		if (!empty($firm_id)) {
			$sql= "select * from firm where firm_id='{$firm_id}'";
			$result = $dbh->query($sql);
			while ($data = $result->fetchRow()) {
				$this->city_code=$data['city_code'];
				$this->name=$data['name'];
				$this->address=$data['address'];
				$this->telno=$data['telno'];
				$this->auth_nums=$data['auth_nums'];
				$this->auth_ip=$data['auth_ip'];
				$this->auth_starttime=$data['auth_starttime'];
				$this->auth_stoptime=$data['auth_stoptime'];
				$this->start_date=$data['start_date'];
				$this->stop_date=$data['stop_date'];
				$this->cht_name=id2CityFirm($this->firm_id);
				$this->city=id2city($this->city_code);
			}
			
			$sql= "select access_level from user_access";
			$result = $dbh->query($sql);
			while ($data = $result->fetchRow()) {
				$now=time();
				$sql2= "select count(user_info.user_id) from user_info, user_status WHERE user_info.user_id=user_status.user_id AND user_info.firm_id='$firm_id' AND user_status.access_level='".$data['access_level']."' AND user_status.auth_stop_time>'$now'";
				//$this->access[sql.$data['access_level']] =$sql2;
				$this->access[$data['access_level']] =& $dbh->getOne($sql2);
			}
		}
	}
}

##################   ConceptData  取單元概念物件     ##############################

Class ConceptData{
	
	//變數名稱
	
	//function
	function ConceptData($cs_sn){
		global $dbh;

		$sql = "select * from concept_info where cs_sn = '$cs_sn'"; 
		//echo "<br>item sql= $sql <br>";
		$result = $dbh->query($sql);
		while ($data = $result->fetchRow()) {
			$this->cs_sn=$cs_sn;
			$this->cs_id=$data['cs_id'];
			$this->publisher_id=$data['publisher_id'];
			$this->subject_id=$data['subject_id'];
			$this->vol=$data['vol'];
			$this->unit=$data['unit'];
			$this->grade=$data['grade'];
			$this->concept_name=$data['concept'];
			$this->matrix_map_file=$data['matrix_map'];
			$this->remedy_file=$data['remedy_file'];
			$this->item_remedy_file=$data['item_remedy_file'];
			$this->percent_map=$data['percent_map'];
			$this->percent_gif=$data['percent_gif'];
			$this->structure_gif=$data['structure_gif'];
			$this->indicator_relation=$data['indicator_relation'];
			$this->indicator_item=$data['indicator_item'];
			$this->indicator_threshold=$data['indicator_threshold'];
			$this->indicator_item_nums=$data['indicator_item_nums'];
			$this->indicator_item_relation=$data['indicator_item_relation'];
			$this->remedy_instruction=$data['remedy_instruction'];
			$this->book_ref=$data['book_ref'];
		}        
		$this->cs_file[0]=$this->matrix_map_file;
		$this->cs_file[1]=$this->remedy_file;
		$this->cs_file[2]=$this->item_remedy_file;
		$this->cs_file[3]=$this->percent_map;
		$this->cs_file[4]=$this->percent_gif;  
		$this->cs_file[5]=$this->structure_gif;
		$this->cs_file[6]=$this->indicator_relation;
		$this->cs_file[7]=$this->indicator_item;  
		$this->cs_file[8]=$this->indicator_threshold;
		$this->cs_file[9]=$this->indicator_item_nums;
		$this->cs_file[10]=$this->indicator_item_relation;
		$this->cs_file[11]=$this->book_ref;
		//補救教學有多檔，放在最後！
		$this->cs_file[12]=$this->remedy_instruction;

		
		$this->file_path=_ADP_CS_UPLOAD_PATH.$this->publisher_id."/".$this->subject_id."/".$this->vol."/".$this->unit."/";
		$this->url_path=_ADP_EXAM_DB_PATH.$this->publisher_id."/".$this->subject_id."/".$this->vol."/".$this->unit."/";
		$this->matrix_map_file_path=$this->file_path.$this->matrix_map_file;
		$this->remedy_file_path=$this->file_path.$this->remedy_file;
		$this->item_remedy_file_path=$this->file_path.$this->item_remedy_file;
		$this->percent_map_path=$this->file_path.$this->percent_map;
		$this->percent_gif_path=$this->file_path.$this->percent_gif;
		$this->structure_gif_path=$this->file_path.$this->structure_gif;

		$this->matrix_map_url=$this->url_path.$this->matrix_map_file;
		$this->remedy_url=$this->url_path.$this->remedy_file;
		$this->item_remedy_url=$this->url_path.$this->item_remedy_file;
		$this->percent_map_url=$this->url_path.$this->percent_map;
		$this->percent_gif_url=$this->url_path.$this->percent_gif;
		$this->structure_gif_url=$this->url_path.$this->structure_gif;
		if($this->indicator_item_nums!=""){
			$this->cs_items=read_excel($this->file_path.$this->indicator_item_nums, __LINE__);
		}
		if($this->remedy_instruction!=""){  //取得補救教學教材
			$this->RI_pieces = explode(_SPLIT_SYMBOL, $this->remedy_instruction);
		}
	}

	function get_indicator_item_relation(){
		if($this->indicator_item_relation!=""){
			$this->II_relation=read_excel($this->file_path.$this->indicator_item_relation, __LINE__);;
		}
		return $this->II_relation;
	}
}



##################   ItemData  取試題資料物件     ##############################

Class ItemData{

	//function
	function ItemData($cs_id, $paper_vol, $item_num){
		global $dbh;

		$this->cs_id=$cs_id;
		$this->paper_vol=$paper_vol;
		$this->item_num=$item_num;

		$sql = "select * from concept_item, concept_info where concept_item.cs_id = '$cs_id' and concept_item.paper_vol='$paper_vol' and concept_item.item_num='$item_num' and concept_item.cs_id=concept_info.cs_id";
		//echo "<br>item sql= $sql <br>";
		$result = $dbh->query($sql);
		while ($data = $result->fetchRow()) {
			$this->publisher_id=$data['publisher_id'];
			$this->subject_id=$data['subject_id'];
			$this->vol=$data['vol'];
			$this->unit=$data['unit'];
			$this->grade=$data['grade'];
			$this->concept_name=$data['concept'];
			$this->exam_paper_id=$data['exam_paper_id'];
			//$this->matrix_map_file=$data['matrix_map'];
			$this->item_filename=$data['item_filename'];
			$this->op_filename=$data['op_filename'];
			$this->sol_content=$data['op_content'];
			$this->item_correct_answer=$data['op_ans'];
			$this->score=$data['points'];
			$this->selected=$data['item_num'];
			$this->item_sn=$data['item_sn'];
			$this->edu_parameter=$data['edu_parameter'];
			//$this->item_remedy_file=$data['item_remedy_file'];
			//print_r($data);
		}
		$pic_path=_ADP_EXAM_DB_PATH.$this->publisher_id."/".$this->subject_id."/".$this->vol."/".$this->unit."/";
		$this->item_data[0]=$pic_path.$this->item_filename;
		$this->op_pieces = explode(_SPLIT_SYMBOL, $this->op_filename);   //題目選項圖片檔名的陣列
		$this->sol_pieces = explode(_SPLIT_SYMBOL, $this->sol_content);   //答案詳解選項圖片檔名的陣列
		for($i=1;$i<=count($this->op_pieces)-1;$i++){
			$this->item_data[$i]=$pic_path.$this->op_pieces[($i-1)];
         if($this->sol_pieces[($i-1)]!=""){
            $this->sol_item_data[$i]=$pic_path.$this->sol_pieces[($i-1)];
         }else{
            $this->sol_item_data[$i]="";
         }
      }
		$this->item_data['ans']=$this->item_correct_answer;   //本題正確答案
		$this->item_data['points']=$this->score;        //本題配分
	}

	function getItemData(){
		return $this->item_data;
	}
	function getSolItemData(){
		return $this->sol_item_data;
	}
	function getEduParameter(){
      $this->EduParameterAry = explode(_SPLIT_SYMBOL, $this->edu_parameter);
		return $this->edu_parameter;
	}
}


##################   RemedyData  取補救概念物件     ##############################
Class RemedyData{

	//member function
        
	function RemedyData($cs_id){
		global $dbh;
	/*藉由$concept_id去試題結構表中抓補救教學結構表的位置並填入$structure_path即可*/
		$this->cs_id=$cs_id;
		$sql = "select * from concept_info where cs_id = '{$this->cs_id}'";   
		$result = $dbh->query($sql);
		while ($data = $result->fetchRow()) {
			$this->publisher_id=$data['publisher_id'];
			$this->subject_id=$data['subject_id'];
			$this->vol=$data['vol'];
			$this->unit=$data['unit'];
			$this->grade=$data['grade'];
			$this->threshold=$data['threshold'];
			$this->matrix_map_file=$data['matrix_map'];
			$this->remedy_file=$data['remedy_file'];
		}
		$_SESSION['cs_path']=_ADP_CS_UPLOAD_PATH.$this->publisher_id."/".$this->subject_id."/".$this->vol."/".$this->unit."/";
		//$_SESSION['cs_path']=_CSDB_PATH.$this->publisher_id."/".$this->subject_id."/".$this->vol."/".$this->unit."/";
		$matrix_map_file_path=$_SESSION['cs_path'].$this->matrix_map_file;
		$structure_path=$_SESSION['cs_path'].$this->remedy_file; //由名稱去找對應的檔案存放位置
		//echo "<br>matrix路徑=   $matrix_map_file_path<br>";
		//echo "<br>remedy路徑= $structure_path<br>";
		$structure_temp=read_excel($structure_path, __LINE__);
		for($i=0;$i<sizeof($structure_temp);$i++){
			$this->structure[$i]=$structure_temp[$i][0];
			$this->threshold[$i]=$structure_temp[$i][1];
		}
		$this->concept_num=sizeof($this->structure);
	}

	function get_concept_id(){return $this->concept_id;}

	function get_structure(){return $this->structure;} 
	
	function get_threshold(){return $this->threshold;}
		
	function get_concept_num(){return $this->concept_num;} 
		
}

##################   ClassData   取班級資料物件     ##############################

class ClassData {

	var $user_id;  //使用者代號
	var $uname;  //中文姓名

	function ClassData($cl_item) {
		global $dbh;
		
		$i=0;
		$sql="select * from user_info, user_status WHERE user_info.organization_id='$cl_item[1]' and user_info.grade='$cl_item[2]' and user_info.class='$cl_item[3]' and user_info.user_id=user_status.user_id ORDER BY user_info.user_id";
		$result =$dbh->query($sql);
		while ($row=$result->fetchRow()){
		
			$this->member[$i] = $row['uid']._SPLIT_SYMBOL.$row['user_id']._SPLIT_SYMBOL.$row['uname']._SPLIT_SYMBOL.$row['access_level'];
			$i++;
		}
	}

	function getClassMember(){
		return $this->member;
	}
}


##################   ClassData   取班級資料物件(ck測驗考區考生專用)     ##############################

class ClassDataCk {

	var $user_id;  //使用者代號
	var $uname;  //中文姓名

	function ClassDataCK($cl_item) {
		global $dbh;
		
		$i=0;
		$sql="select * from user_info, user_status WHERE user_info.organization_id='$cl_item[1]' and user_info.exarea='$cl_item[2]' and user_info.user_id=user_status.user_id ORDER BY user_info.user_id";
		$result =$dbh->query($sql);
		while ($row=$result->fetchRow()){
		
			$this->member[$i] = $row['uid']._SPLIT_SYMBOL.$row['user_id']._SPLIT_SYMBOL.$row['uname']._SPLIT_SYMBOL.$row['access_level'];
			$i++;
		}
	}

	function getClassMember(){
		return $this->member;
	}
}


##############   Print_Student_Data   輸出學生歷來學習資料物件（虛擬多點計分）     ###############

Class Print_Poly_Item_Data{
	/*
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
      */
	  
	function Print_Poly_Item_Data($input_student,$input_concept){
		global $dbh;
		list($q_cs_ida, $q_cs_unit) = split(_SPLIT_SYMBOL, $input_concept);
		$q_cs_id=$q_cs_ida.sprintf("%02d", $q_cs_unit);
		//debug_msg("第".__LINE__."行 q_cs_id ", $q_cs_id);
		$sql = "select cs_sn from concept_info where cs_id = '$q_cs_id'";
		$cs_sn =& $dbh->getOne($sql);
		$this->CS=new ConceptData($cs_sn);
		//debug_msg("第".__LINE__."行 CS ", $this->CS);
		$this->stu_data=new UserData($input_student);
		for($i=0;$i<sizeof($this->CS->cs_items[0]);$i++){
			$_SESSION['cs_path']="";
			$this->concept_id[$i]=$q_cs_ida.sprintf("%02d", $this->CS->cs_items[0][$i]);
			$ary=explode_cs_id($this->concept_id[$i]);
			for($k=0;$k<sizeof($ary);$k++){
				$_SESSION['cs_path'].=$ary[$k].'/';
			}
			$_SESSION['cs_path']=_ADP_CS_UPLOAD_PATH.$_SESSION['cs_path'];
			//debug_msg("第".__LINE__."行 this->concept_id ", $this->concept_id);
			$this->remedy_data[$i]=new Remedy_Structure($this->concept_id[$i]);
		}
		//debug_msg("第".__LINE__."行 this->remedy_data ", $this->remedy_data);
		
		$this->concept_title=CSid2FullName($input_concept);
		//debug_msg("第".__LINE__."行 this->concept_title ", $this->concept_title);
		$this->Student_Id=$input_student;
		$this->student_basic_data['firm']=id2CityFirm($this->stu_data->firm_id);
		$this->student_basic_data['sn']=$input_student;
		$this->student_basic_data['學生姓名']=$this->stu_data->uname;
		$this->student_basic_data['學生性別']=$this->stu_data->sex;
		$this->student_basic_data['city']=$this->stu_data->city_name;
		$this->student_basic_data['學校']=$this->stu_data->organization_name;
		$this->student_basic_data['年級']=num2chinese($this->stu_data->grade).'年級';
		$this->student_basic_data['班級']=num2chinese($this->stu_data->grade)."年".num2chinese($this->stu_data->class_name)."班";
		$this->student_basic_data['idn']=$this->stu_data->identity;
                           
		$sql = "select * from exam_record where user_id = '".$input_student."' and cs_id='".$input_concept."' ORDER BY paper_vol ASC"; 
		//echo "<br>$sql<br>";
		$result = $dbh->query($sql);
		$this->test_times=$result->numRows();
		while ($data = $result->fetchRow()) {
			$this->date[]=$data['date'];
			$this->time[]=$data['during_time'];
			$this->score[]=$data['score'];
			$this->percentage[]=$data['degree'];
			//$this->remedy_rate[]=$data['remedy_rate'];
			$this->questions[]=$data['questions'];   //試卷內容題號(即出題順序)
			$this->org_res[]=$data['org_res'];    //原始作答情形
			$this->binary_res[]=$data['binary_res'];   //二元作答情形
			$this->done_items[]=$data['done_items'];   //施測題數
			$this->total_items[]=$data['total_items'];   //試卷總題數
			$this->exam_title[]=$data['exam_title'];   //卷別
			//$binary_res = explode(_SPLIT_SYMBOL, $row['binary_res']);
			//debug_msg("第".__LINE__."行 this ", $this);
		}
		$this->IIR=$this->CS->get_indicator_item_relation();
		//debug_msg("第".__LINE__."行 this->IIR ", $this->IIR);
		for($i=0;$i<sizeof($this->binary_res);$i++){
			$this->b_res[$i] = explode(_SPLIT_SYMBOL, $this->binary_res[$i]);
			array_pop($this->b_res[$i]);
			for($j=0;$j<count($this->IIR[1]);$j++){
				$corr=$tested=0;
				for($k=$this->IIR[2][$j];$k<=$this->IIR[3][$j];$k++){
					if($this->b_res[$i][$k-1]==1 || $this->b_res[$i][$k-1]==2){
						$corr++;
					}
					if($this->b_res[$i][$k-1]==1 || $this->b_res[$i][$k-1]==0){
						$tested++;
					}
				}
				$this->iitem_tested[$i][$j]=$tested;
				$this->iitem_corr[$i][$j]=$corr;
				$this->iitem_corr_percent[$i][$j]=ceil($corr*100/$this->IIR[4][$j]);
			}
			$content[$i]= implode(",", $this->iitem_corr_percent[$i]);
			$header = implode(",", $this->IIR[1]);
		}
		//debug_msg("第".__LINE__."行 this->iitem_corr ", $this->iitem_corr);
		//debug_msg("第".__LINE__."行 this->b_res ", $this->b_res);
		$this->radarfile=time().rand(100000,999999);
		$_SESSION[q]=$this->radarfile;
		$filename=$this->radarfile.'.csv';
		creat_csv($filename, $header, $content);
		
	}
    
	function print_header(){
		$print_data='<div id="diagnosis">
			<table width="670" border="0" align="center" cellpadding="2" cellspacing="0">';

		return $print_data;
	}

	function print_student_basic_data(){
		
		$print_data='<tr>
				<td valign="top" scope="col">
  <tr>
    <td align="center" valign="top" scope="col"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td scope="col" align="center"><img src="'._THEME_IMG.'diag-1.gif" width="651" height="93" /></td>
        </tr>
    </table>';

	$print_data.='</td>
  </tr>
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
                    <td align="center" scope="col"><span class="d_title">基 本 資 料</span></td>
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
                <td colspan="2">&nbsp;</td>
              </tr>';

		return $print_data;
	}

	//-- 最近一次(本次)施測成績表格
	function print_least_data(){
		
		$print_data='<tr>
                <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="center" scope="col"><span class="d_title">本 次 測 驗 結 果</span></td>
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
                </table></td>
              </tr><tr>
                <td colspan="2">&nbsp;</td>
              </tr>';
				
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
                    <td align="center" scope="col"><span class="d_title">百 分 等 級 圖</span></td>
                  </tr>
                  <tr>
                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td scope="col"><img src="'._THEME_IMG.'main_lt.gif" width="12" height="12" /></td>
                        <td width="98%" background="'._THEME_IMG.'main_ct.gif" scope="col"></td>
                        <td scope="col"><img src="'._THEME_IMG.'main_rt.gif" width="12" height="12" /></td>
                      </tr>
                      <tr>
                        <td background="'._THEME_IMG.'main_lc.gif"></td>
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="center" scope="col">';
						
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
                        <td background="'._THEME_IMG.'main_rc.gif"></td>
                      </tr>
                      <tr>
                        <td><img src="'._THEME_IMG.'main_ld.gif" width="12" height="12" /></td>
                        <td background="'._THEME_IMG.'main_cd.gif"></td>
                        <td><img src="'._THEME_IMG.'main_rd.gif" width="12" height="12" /></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr><tr>
                <td colspan="2">&nbsp;</td>
              </tr>';

		return $print_data;
	}

	function print_radar_gif($used_file, $report_for_pc){

		//$used_file="plotradar.php";
		//$_SESSION['IIrelation']=$this->CS->get_indicator_item_relation();
		//debug_msg("第".__LINE__."行 _SESSION[IIrelation] ", $_SESSION['IIrelation']);
		$print_data='<tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="center" scope="col"><span class="d_title">能 力 指 標 雷 達 圖</span></td>
                  </tr>
                  <tr>
                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td scope="col"><img src="'._THEME_IMG.'main_lt.gif" width="12" height="12" /></td>
                        <td width="98%" background="'._THEME_IMG.'main_ct.gif" scope="col"></td>
                        <td scope="col"><img src="'._THEME_IMG.'main_rt.gif" width="12" height="12" /></td>
                      </tr>
                      <tr>
                        <td background="'._THEME_IMG.'main_lc.gif"></td>
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="center" scope="col">';
		if($report_for_pc==1){   //僅在電腦上顯示
			$print_data.='<img src="'._ADP_URL.$used_file.'?q='.$this->radarfile.'" /></td>';
		}else{
			$tmp_url=_ADP_URL."data/tmp/";
			file(_ADP_URL.$used_file."?q=".$this->radarfile."&report=".$report_for_pc);  //產生學習狀態雷達圖於tmp中
			$print_data.='<img src="'.$tmp_url.$this->radarfile.'.png" width="450" height="300" /></td>';
		}

		$print_data.='</tr>
                        </table></td>
                        <td background="'._THEME_IMG.'main_rc.gif"></td>
                      </tr>
                      <tr>
                        <td><img src="'._THEME_IMG.'main_ld.gif" width="12" height="12" /></td>
                        <td background="'._THEME_IMG.'main_cd.gif"></td>
                        <td><img src="'._THEME_IMG.'main_rd.gif" width="12" height="12" /></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>';
		
		return $print_data;
	}
                     

	function print_indicator_data(){
		
		$print_data='<tr>
                <td colspan="2"><br><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="center" scope="col"><span class="d_title">學 習 紀 錄</span></td>
                  </tr>
                  <tr>
                    <td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="d_tableline">
                        <tr>
                          <td height="35" colspan="6" align="center" class="" scope="col">'.$this->concept_title.'</td>
                          </tr>
                        <tr><td><table width="100%" border="0" cellpadding="0" cellspacing="0" class=""><tr>
                          <td width="15%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title31" scope="col">項目</td>
                          <td width="13%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title31" scope="col">總題數</td>
                          <td width="24%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title31" scope="col">各次施測題數</td>
                          <td width="24%" height="35" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title32" scope="col">各次答對率(％)</td>
                        </tr>';
                        
		for($j=0;$j<count($this->IIR[1]);$j++){
			$print_data.="<tr>\n";
			$print_data.='<td width="15%" height="35" align="center" class="d_s_title31" scope="col">'.$this->IIR[1][$j]."</td>\n";
			$print_data.='<td width="13%" align="center" class="d_s_title31">'.$this->IIR[4][$j]."</td>\n";
			$this->iitem_corr_per=$this->iitem_tested_per="";
			for($i=0;$i<count($this->iitem_corr_percent);$i++){
				$this->iitem_tested_per.=$this->iitem_tested[$i][$j];
				$this->iitem_corr_per.=$this->iitem_corr_percent[$i][$j];
				if($i<count($this->iitem_corr_percent)-1){
					if($i==0 && $j==0){
						$ex_corr=$this->iitem_corr_per."[".($i+1)."]";
						$ex_tested=$this->iitem_tested_per."[".($i+1)."]";
					}
					$this->iitem_corr_per.="[".($i+1)."]／";
					$this->iitem_tested_per.="[".($i+1)."]／";
				}else{
					$this->iitem_corr_per.="[".($i+1)."]";
					$this->iitem_tested_per.="[".($i+1)."]";
				}
			}
			//implode(",", $this->iitem_corr_percent[$i]);
			$print_data.='<td width="24%" align="center" class="d_s_title31">'.$this->iitem_tested_per."</td>\n";
			$print_data.='<td width="24%" align="center" class="d_s_title32">'.$this->iitem_corr_per."</td>\n";
			$print_data.="</tr>\n";
		}
		$i=$j=1;
		$print_data.='</table></td>
                  </tr>
                </table></td></tr>
                </table><font color="0000ff">說明：'.$ex_tested.'表示第'.$i.'次的施測題數為'.$this->iitem_tested[$i-1][$j-1]."題，　".$ex_corr.'表示第'.$i.'次的答對率為'.$this->iitem_corr_percent[$i-1][$j-1].'%</font></td>
              </tr><tr>
                <td colspan="2">&nbsp;</td>
              </tr>';

		//echo $print_data;
		return $print_data;
	}
                     
	//---學習概念列表
	function print_remedy_data($report_for_pc){	
		global $dbh, $input_student;

		$print_data='<tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2"><form id="form1" name="form1" method="post" action="'.$_SERVER["PHP_SELF"].'"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="center" class="d_title" scope="col">概 念 診 斷 報 告</td>
                  </tr>
					<tr>
                    <td align="left" scope="col">說明：◎表示<font color="0000ff">通過</font>該概念；Ｘ表示<font color="ff0000">未通過</font>該概念。
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
		if($report_for_pc==1){  //僅在電腦上顯示
			$print_data.='<td width="20%" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title42">線上學習</td>';
		}
		$print_data.="</tr>\n";
		/*
		$print_data.="<tr>\n";
		for($i=0;$i<$this->test_times;$i++){
			$print_data.='<td width="'.(30/$this->test_times).'%" align="center" background="'._THEME_IMG.'tit_bg03.gif" class="d_s_title">'.($i+1).'</td>';
		}
		
		$print_data.="</tr>\n";
		*/
///修改  this->concept_id
		//debug_msg("第".__LINE__."行 q_ep_id ", sizeof($this->remedy_data));
		//die();
		for($k=0;$k<sizeof($this->remedy_data);$k++){
			list($tmpcs, $cs2, $p_vol)=explode(_SPLIT_SYMBOL, $this->exam_title[$k]);
			//for($j=0;$j<sizeof($this->CS->cs_items[0]);$j++){
			//	$q_ep_id=$tmpcs.sprintf("%02d", $this->CS->cs_items[0][$j]).sprintf("%02d", $p_vol);
			//	$this->exam_title2[$j]=$q_ep_id;
			//	debug_msg("第".__LINE__."行 q_ep_id ", $q_ep_id);
			
			//}
			$sql = "select remedy_rate, exam_title from exam_record where user_id = '".$this->Student_Id."' and cs_id='".$this->concept_id[$k]."' ORDER BY exam_sn ASC"; 
			$result = $dbh->query($sql);
			while ($data = $result->fetchRow()) {
				$this->remedy_rate[$k][]=$data['remedy_rate'];
				$this->exam_title2[$k][]=$data['exam_title'];
			}
			
			for($j=0;$j<count($this->remedy_rate[$k]);$j++){
				$this->pieces = explode(_SPLIT_SYMBOL, $this->remedy_rate[$k][$j]);
				for($i=0;$i<count($this->pieces);$i++){
					$this->student_pass_rate[$k][$j][$i]=$this->pieces[$i];    //第j次第i個概念通過百分比
				}
			}
			//debug_msg("第".__LINE__."行 this->student_pass_rate ", $this->student_pass_rate);
			//die();
			$this->remedy_structure[$k]=$this->remedy_data[$k]->get_structure();
			$this->threshold[$k]=$this->remedy_data[$k]->get_threshold();
		//---修改至此

			for($i=0;$i<sizeof($this->remedy_structure[$k]);$i++){
				for($j=0;$j<$this->test_times;$j++){
					if($this->student_pass_rate[$k][$j][$i]>=$this->threshold[$k][$i]){
						$this->student_pass_rate[$k][$j][$i]="◎";
					}else{
						if($report_for_pc==1){
							$this->student_pass_rate[$k][$j][$i]='<font color="ff0000">Ｘ</font>'.'<br><a href="javascript:hi'.$k.$i.$j.'()" >查詢</a>'.
					'<script language="javascript" type="text/javascript">
<!--
function hi'.$k.$i.$j.'()
{
strFeatures = "toolbar=0,menubar=0,location=0,directories=0,status=0,scrollbars=yes,resizable=yes";
window.open("modules.php?op=modload&name=ExamResult&file=viewErrors&scr=all&q_user_id='.$this->Student_Id.'&q_ep_id='.$this->exam_title2[$k][$j].'&remedy='.($i+1).'" , "123", strFeatures);
}
//-->
</script>';
					}else{
						$this->student_pass_rate[$k][$j][$i]="Ｘ";
					}
				}
			}
		}
			/*
			for($i=0;$i<sizeof($this->Concept_Object->RI_pieces);$i++){
					if($this->Concept_Object->RI_pieces[$i]=""){
					$remedy_inst_url[$i]="　";
				}else{
					$remedy_inst_url[$i]='<a href="'.$_SESSION['pic_path'].$this->Concept_Object->RI_pieces[$i].'" target="_blank">教材'.($i+1).'</a>';
				}
			}
			print_r($remedy_inst_url);
			*/
			for($i=0;$i<sizeof($this->remedy_structure[$k]);$i++){
				$print_data.="<tr>\n";
				//處理分隔
				$rem = explode("】", $this->remedy_structure[$k][$i]);
				$print_data.='<td width="14%" align="left" class="d_s_title42">'.$rem['0']."】</td>\n";
				$print_data.='<td width="36%" align="left" class="d_s_title41">'.$rem['1']."</td>\n";
//				echo "<td width=\"10%\">".$status[$i]."</td>\n";
				for($j=0;$j<$this->test_times;$j++){
					$print_data.='<td width="'.(30/$this->test_times).'%" align="center" class="d_s_title41">'.($this->student_pass_rate[$k][$j][$i]).'</td>';
				}

				if($report_for_pc==1){  //僅在電腦上顯示
					if(isset($showfig)){		unset($showfig);	}
					$showfig=explode(".", $this->CS->RI_pieces[$i]);
					$showfig[0]=str2compiler($showfig[0]);
					$print_data.='<td width="20%" align="center" class="d_s_title42">';
					$_SESSION['cs_path']="";
					$ary=explode_cs_id($this->concept_id[$k]);
					for($kk=0;$kk<sizeof($ary);$kk++){
						$_SESSION['cs_path'].=$ary[$kk].'/';
					}
					$_SESSION['cs_path']=_ADP_CS_UPLOAD_PATH.$_SESSION['cs_path'];
					$exec_file=$_SESSION['cs_path'].$this->CS->RI_pieces[$i];
					if(file_exists($exec_file) && is_file($exec_file)){
						$print_data.='<a href="'._ADP_URL.'viewfig2.php?list='.$showfig[0].'&tpp='.$showfig[1].'&cs_id='.$this->cs_id.'" target="_blank">教材'.($i+1).'</a>';	
						/*
						$orgfile=$_SESSION['cs_path'].$this->Concept_Object->RI_pieces[$i];
						$newfile=_ADP_TMP_UPLOAD_PATH.$this->Concept_Object->RI_pieces[$i];
						$tmp_url=_ADP_URL."data/tmp/";
						copy($orgfile, $newfile);
						//$print_data.='<img src="'.$tmp_url.$this->Concept_Object->percent_gif.'" width="'.$pic_w.'" height="'.$pic_h.'" /></td>';

						$print_data.='<a href="'.$tmp_url.$this->Concept_Object->RI_pieces[$i].'" target="_blank">教材'.($i+1).'</a>';
						*/
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
                </table></td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
          </table></td>
        </tr>
      </table>
    </form></td>
  </tr>';
		
		//echo $print_data;
		return $print_data;
	}

	function print_feet(){

		$print_data='</table></div>';
		return $print_data;
	}

}


Class Concept_Structure{
	//member data
	//private $concept_id;
	//private $cs_path;
	private $structure;
	private $back_structure;
	private $item_num;
	private $item_sequence;
	private $item_data;
	private $item_vol;
	private $remedy_data;
	private $percent_map;
	private $concept_percentage_graphic;
	private $indicator_relation;//能力指標間的關係(excel矩陣檔)
	private $indicator_item;//能力指標下有什麼題目 (excel矩陣檔)
	private $indicator_threshold;//能力指標的閥值(excel矩陣檔)
	private $is_indicator;//是否做能力指標  0->不做(當作單元)  1->做(能力指標)
	private $indicator_sequence;
        //member function
	function Concept_Structure($concept_id,$item_vol){  //建構函數
		global $dbh;
		//以下需要修正的如說明所示
		$this->item_vol=$item_vol;
		$this->concept_id=$concept_id;
		/*************************************************************************
		*藉由$concept_id找出試題結構儲存的位置，並將結果儲存入$structure_path中***
		**************************************************************************/
		$sql = "select * from concept_info where cs_id = '{$this->concept_id}'";
		$result = $dbh->query($sql);
		while ($data = $result->fetchRow()) {
			$this->publisher_id=$data['publisher_id'];
			$this->subject_id=$data['subject_id'];
			$this->vol=$data['vol'];
			$this->unit=$data['unit'];
			$this->grade=$data['grade'];
		//	$this->threshold=$data['threshold'];
			$this->matrix_map_file=$data['matrix_map'];
			$this->percent_map=$data['percent_map'];
			$this->percent_gif=$data['percent_gif'];
			$this->sturcture_gif=$data['structure_gif'];
			$this->indicator_relation_file=$data['indicator_relation'];
			$this->indicator_item_file=$data['indicator_item'];
			$this->indicator_threshold_file=$data['indicator_threshold'];
			$this->iitem_nums_file=$data['indicator_item_nums'];
			$this->remedy_instruction=$data['remedy_instruction'];
			$this->cs_name=$data['concept'];   //概念名稱
			//debug_msg("第".__LINE__."行 data ", $data);
		}

		$this->cs_path=_ADP_CS_UPLOAD_PATH.$this->publisher_id."/".$this->subject_id."/".$this->vol."/".$this->unit."/";
		//$this->cs_path=_REAL_CSDB_PATH.$this->publisher_id."/".$this->subject_id."/".$this->vol."/".$this->unit."/";

		$_SESSION['cs_path']=$this->cs_path;
		//echo $_SESSION['cs_path'];
		$_SESSION['pic_path']=_ADP_EXAM_DB_PATH.$this->publisher_id."/".$this->subject_id."/".$this->vol."/".$this->unit."/";
		$structure_path=$this->cs_path.$this->matrix_map_file;

		//debug_msg("第".__LINE__."行 structure_path ", $structure_path);
//		chk_file_exist($structure_path, __LINE__);
//		$this->structure=read_excel($structure_path, __LINE__);
		//echo "<br>結構圖檔= $structure_path<br>";
		$percentage_map_path=$_SESSION['cs_path'].$this->percent_map;//百分比表位置
		//debug_msg("第".__LINE__."行 percentage_map_path ", $percentage_map_path);

//		chk_file_exist($percentage_map_path, __LINE__);
//		$this->percentage_map=read_excel($percentage_map_path, __LINE__);
		//debug_msg("第".__LINE__."行 percentage_map ", $this->percentage_map);
		$this->concept_percentage_graphic=$_SESSION['pic_path'].$this->percent_gif;//百分圖表
		$this->sturcture_gif_url=$_SESSION['pic_path'].$this->sturcture_gif;//知識結構圖

		if(file_exists($this->cs_path.$this->percent_gif) && $this->percent_gif!=""){
			$this->PImgProp['percent_gif']=GetImageSize($this->cs_path.$this->percent_gif);   //取得圖表內容
		}
		if(file_exists($this->cs_path.$this->sturcture_gif) && $this->sturcture_gif!=""){
			$this->PImgProp['sturcture_gif']=GetImageSize($this->cs_path.$this->sturcture_gif);   //取得圖表內容
		}
		if($this->remedy_instruction!=""){  //取得補救教學教材
			$this->RI_pieces = explode(_SPLIT_SYMBOL, $this->remedy_instruction);
		}
		$this->ceil_item_num=0;
		$this->floor_item_num=sizeof($this->structure)-1;
		//--能力指標
		$indicator_relation_path=$this->cs_path.$this->indicator_relation_file;
		$indicator_item_path=$this->cs_path.$this->indicator_item_file;
		$indicator_threshold_path=$this->cs_path.$this->indicator_threshold_file;
		$iitem_nums_path=$this->cs_path.$this->iitem_nums_file;
/*
debug_msg("第".__LINE__."行 indicator_relation_path ", $indicator_relation_path);
debug_msg("第".__LINE__."行 indicator_item_path ", $indicator_item_path);
debug_msg("第".__LINE__."行 indicator_threshold_path ", $indicator_threshold_path);
debug_msg("第".__LINE__."行 this->iitem_nums_file ", $this->iitem_nums_file);
debug_msg("第".__LINE__."行 iitem_nums_path ", $iitem_nums_path);
*/
		if(file_exists($indicator_relation_path) && file_exists($indicator_item_path) && file_exists($indicator_threshold_path) && file_exists($iitem_nums_path) && $this->indicator_relation_file!="" && $this->indicator_item_file!="" && $this->indicator_threshold_file!="" && $this->iitem_nums_file!=""){  //四個檔案都存在，可以做能力指標多點計分

			$this->indicator_relation=read_excel($indicator_relation_path, __LINE__);
			//debug_msg("第".__LINE__."行 indicator_relation ", $indicator_relation);
			$this->indicator_item=read_excel($indicator_item_path, __LINE__);
			//debug_msg("第".__LINE__."行 indicator_item_path ", $indicator_item_path);
			$this->indicator_threshold=read_excel($indicator_threshold_path, __LINE__);
			//debug_msg("第".__LINE__."行 indicator_threshold_path ", $indicator_threshold_path);
			$this->iitem_nums=read_excel($iitem_nums_path, __LINE__);
			//debug_msg("第".__LINE__."行 iitem_nums ", $this->iitem_nums);
			$this->ceil_item[0]=0;
			$this->floor_item[0]=$this->iitem_nums[1][0]-1;
			for($i=1;$i<sizeof($this->iitem_nums[1]);$i++){
				$this->ceil_item[$i]=$this->floor_item[$i-1]+1;
				$this->floor_item[$i]=$this->iitem_nums[1][$i]+$this->floor_item[$i-1];
			}
			//debug_msg("第".__LINE__."行 this->ceil_item ", $this->ceil_item);
			//debug_msg("第".__LINE__."行 this->floor_item ", $this->floor_item);
			$this->ceil_item_num=$this->ceil_item[$this->unit-1];
			$this->floor_item_num=$this->floor_item[$this->unit-1];
			$this->total_test_vol=sizeof($this->iitem_nums[1]);  //本次多點計分共分成幾卷
			$this->is_indicator=1;
		}
		//-- end of 能力指標

		$this->item_num=sizeof($this->structure);
		//debug_msg("第".__LINE__."行 this->item_num ", $this->item_num);

		/* 獨立呼叫，降低系統負擔 960203
		$this->set_item_data();
		$this->set_remedy_data();

		if($this->is_indicator==1){
			$this->set_indicator_sequence();//move here for indicator
		}
		$this->set_item_sequence();//move here for indicator
		*/
		/*
		for($i=0;$i<$this->item_num;$i++){    //$this->structure_level  判斷階層
			$this->structure_level[$i]=array_sum($this->structure[$i]);
		}
		*/

	}

	function get_concept_id(){return $this->concept_id;}

	function get_structure(){return $this->structure;}

	function set_structure_null(){
		for($i=0;$i<$this->item_num;$i++){
			for($j=0;$j<$this->item_num;$j++){
				$this->structure[$i][$j]=0;
			}
		}
	}

	function get_upper_item($selected_item){  //不可用於有多個上位節點的狀況
		$item_level=$this->structure_level[$selected_item-1];
		//echo "item_level=  $item_level<br>";
		//die();
		if($item_level==0){  //最上層
			$upper_item="none";
		}else{
			for($i=0;$i<count($this->structure_level);$i++){
				//debug_msg("structure_level ".__LINE__, $this->structure_level);
				//echo $i."<br>";
				if($this->structure_level[$i]==$item_level-1 && $this->structure[$selected_item-1][$i]==1){
					$upper_item=$i+1;
				}
				//echo "第 $i   ".$this->structure_level[$i]."==  ".($item_level-1)."  "    .$this->structure[$selected_item-1][$i]."==1  <br>";
			}
		}
		return $upper_item;
	}

	function get_item_num(){return $this->item_num;}

	function get_item_sequence(){return $this->item_sequence;}

	//擷取能力指標相關表格
	function get_item_indicator_relation(){return $this->indicator_relation;}

	function get_item_indicator_item(){return $this->indicator_item;}

	function get_item_indicator_threshold(){return $this->indicator_threshold[0];}

	function check_indicator(){return $this->is_indicator;}

	function set_indicator_sequence(){
		$all_link_number;
		for($i=0;$i<count($this->indicator_threshold[0]);$i++){
			$link_number=0;
			for($j=0;$j<count($this->indicator_threshold[0]);$j++){
				$link_number=$link_number+$this->indicator_relation[$j][$i];
			}
			$all_link_number[$i]=$link_number;
		}
		$sequence_index=0;
		$from_big_to_small=$all_link_number;
		rsort($from_big_to_small);
		for($i=0;$i<count($this->indicator_threshold[0]);$i++){
			for($j=0;$j<count($this->indicator_threshold[0]);$j++){
				if($all_link_number[$j]==$from_big_to_small[$i]){
					$this->indicator_sequence[$sequence_index]=$j+1;
					$sequence_index=$sequence_index+1;
					$all_link_number[$j]=-1;
					$j=count($this->indicator_threshold[0])+1;
				}
			}
		}
	}

	function get_indicator_sequence(){
		return $this->indicator_sequence;
	}
    //adding OK

	function get_selected_item_data($each_item_number){
		return $this->item_data[$each_item_number-1];
	}

	function get_percentage_map(){
		return $this->percentage_map;
	}

	function set_item_sequence(){
		$all_link_number;
		for($i=0;$i<$this->item_num;$i++){
			$link_number=0;
			for($j=0;$j<$this->item_num;$j++){
				$link_number=$link_number+$this->structure[$j][$i];
			}
			$all_link_number[$i]=$link_number;
			$this->view_all_link_number[$i]=$link_number;
		}
		$sequence_index=0;
		$from_big_to_small=$all_link_number;
		rsort($from_big_to_small);

		//研究以下這一段
		for($i=0;$i<$this->item_num;$i++){
			for($j=0;$j<$this->item_num;$j++){
				if($all_link_number[$j]==$from_big_to_small[$i]){
					$this->item_sequence[$sequence_index]=$j+1;
					$sequence_index=$sequence_index+1;
					$all_link_number[$j]=-1;
					$j=$this->item_num+1;
				}
			}
		}

		if($this->is_indicator==1){
			$sequence=$this->item_sequence;
			$indicator_map;
			$new_sequence;
				  //print_r($this->indicator_threshold);
			for($sequence_index=0;$sequence_index<count($sequence);$sequence_index++){
				$item_index=$sequence[$sequence_index];
				for($learning_index=0;$learning_index<count($this->indicator_threshold[0]);$learning_index++){
					if($this->indicator_item[$learning_index][$item_index-1]==1){
						$indicator_map[$sequence_index]=$learning_index;
					}
				}
			}
				  //print_r($indicator_map);
			$new_sequence_index=0;
			for($learning_index=0;$learning_index<count($this->indicator_threshold[0]);$learning_index++){
				for($start_index=0;$start_index<count($sequence);$start_index++){
					if($indicator_map[$start_index]==$this->indicator_sequence[$learning_index]-1){
						$new_sequence[$new_sequence_index++]=$sequence[$start_index];
					}
				}
			}
			$this->item_sequence=$new_sequence;
			//debug_msg("第".__LINE__."行 this->item_sequence ", $this->item_sequence);
		}

	}

	function set_item_data(){
		if($this->is_indicator==1){  //能力指標
			$this->init_item_nums=$this->iitem_nums[1][$this->unit-1];
			for($i=0;$i<$this->init_item_nums;$i++){
				$this->item_data[$this->ceil_item_num+$i]=new Item_Structure($this->concept_id,($i+1),$this->item_vol);
			}
		}else{   //一般單元
			for($i=0;$i<$this->item_num;$i++){
				$this->item_data[$i]=new Item_Structure($this->concept_id,($i+1),$this->item_vol);
			}
		}
	}

	function set_structure_back(){
		$this->structure=$this->back_structure;
	}

	function set_remedy_data(){
		$this->remedy_data=new Remedy_Structure($this->concept_id);
	}

    function get_remedy_data(){
		return $this->remedy_data;
	}

	function get_concept_percentage_graphic(){
		return $this->concept_percentage_graphic;
	}

	function get_sturcture_gif_url(){
		return $this->sturcture_gif_url;
	}

}


Class Concept_Structure4IRT{
	//member data
	//private $concept_id;
	//private $cs_path;
	private $structure;
	private $back_structure;
	private $item_num;
	private $item_sequence;
	private $item_data;
	private $item_vol;
	private $remedy_data;
	private $percent_map;
	private $concept_percentage_graphic;
	private $indicator_relation;//能力指標間的關係(excel矩陣檔)
	private $indicator_item;//能力指標下有什麼題目 (excel矩陣檔)
	private $indicator_threshold;//能力指標的閥值(excel矩陣檔)
	private $is_indicator;//是否做能力指標  0->不做(當作單元)  1->做(能力指標)
	private $indicator_sequence;
        //member function
	function Concept_Structure($concept_id,$item_vol){  //建構函數
		global $dbh;
		//以下需要修正的如說明所示
		$this->item_vol=$item_vol;
		$this->concept_id=$concept_id;
		/*************************************************************************
		*藉由$concept_id找出試題結構儲存的位置，並將結果儲存入$structure_path中***
		**************************************************************************/
		$sql = "select * from concept_info where cs_id = '{$this->concept_id}'";
		$result = $dbh->query($sql);
		while ($data = $result->fetchRow()) {
			$this->publisher_id=$data['publisher_id'];
			$this->subject_id=$data['subject_id'];
			$this->vol=$data['vol'];
			$this->unit=$data['unit'];
			$this->grade=$data['grade'];
		//	$this->threshold=$data['threshold'];
			$this->matrix_map_file=$data['matrix_map'];
			$this->percent_map=$data['percent_map'];
			$this->percent_gif=$data['percent_gif'];
			$this->sturcture_gif=$data['structure_gif'];
			$this->indicator_relation_file=$data['indicator_relation'];
			$this->indicator_item_file=$data['indicator_item'];
			$this->indicator_threshold_file=$data['indicator_threshold'];
			$this->iitem_nums_file=$data['indicator_item_nums'];
			$this->remedy_instruction=$data['remedy_instruction'];
			$this->cs_name=$data['concept'];   //概念名稱
			//debug_msg("第".__LINE__."行 data ", $data);
		}

		$this->cs_path=_ADP_CS_UPLOAD_PATH.$this->publisher_id."/".$this->subject_id."/".$this->vol."/".$this->unit."/";

		$_SESSION['cs_path']=$this->cs_path;
		$_SESSION['pic_path']=_ADP_EXAM_DB_PATH.$this->publisher_id."/".$this->subject_id."/".$this->vol."/".$this->unit."/";
	}

	function get_concept_id(){return $this->concept_id;}

	function get_structure(){return $this->structure;}

	function set_structure_null(){
		for($i=0;$i<$this->item_num;$i++){
			for($j=0;$j<$this->item_num;$j++){
				$this->structure[$i][$j]=0;
			}
		}
	}


	function get_item_num(){return $this->item_num;}

	function get_item_sequence(){return $this->item_sequence;}

	function get_selected_item_data($each_item_number){
		return $this->item_data[$each_item_number-1];
	}

	function set_item_data(){
      //一般單元
		for($i=0;$i<$this->item_num;$i++){
			$this->item_data[$i]=new Item_Structure($this->concept_id,($i+1),$this->item_vol);
		}
	}

}



Class Item_Structure{
	
	//變數名稱
	private $item_pic;//題目圖片版本
	private $item_select_num;//題目總共的選項
	private $item_select_pic;//題目選項圖片版本
	private $item_correct_answer;//題目正確選項
	private $selected;
	private $concept_id;
	private $remedy_concept;
	private $score;
	private $vol;
	
	//function
	function Item_Structure($concept_name,$item_selected_num,$item_vol){
		global $dbh;
		/*藉由$concept_name和$item_selected_num和$item_vol去試題資料表抓資料填入底下相關欄位
		$this->item_pic代表試題本題圖片放的位置
		$this->item_select_num該試題有幾個選項
		$this->item_correct_answer該試題正確答案是說多少
		$this->selected該試題本身代表第幾題
		$this->concept_id該試題本身隸屬哪一個結構
		$this->remedy_concept該試題在其補救教學結構下屬於第幾個概念
		$this->score本身是幾分
		$this->paper_vol本身是第幾版(A卷，B卷，...)
		$this->item_select_pic個選項的圖片位置，一維度矩陣*/

		$sql = "select * from concept_item, concept_info where concept_item.cs_id = '$concept_name' and concept_item.paper_vol='$item_vol' and concept_item.item_num='{$item_selected_num}' and concept_item.cs_id=concept_info.cs_id"; 
		//debug_msg("sql ".__LINE__ , $sql);
		$result = $dbh->query($sql);
		while ($data = $result->fetchRow()) {
			$this->publisher_id=$data['publisher_id'];
			$this->subject_id=$data['subject_id'];
			$this->vol=$data['vol'];
			$this->unit=$data['unit'];
			$this->grade=$data['grade'];
			$this->exam_paper_id=$data['exam_paper_id'];
			$this->matrix_map_file=$data['matrix_map'];
			$this->item_filename=$data['item_filename'];
			$this->op_filename=$data['op_filename'];
			$this->item_correct_answer=$data['op_ans'];
			$this->score=$data['points'];
			$this->selected=$data['item_num'];
			$this->item_remedy_file=$data['item_remedy_file'];
			//debug_msg("第".__LINE__."行 data ", $data);
		}
		
		$this->op_pieces = explode(_SPLIT_SYMBOL, $this->op_filename);   //題目選項圖片檔名的陣列
		$this->item_select_num=count($this->op_pieces)-1;
		$this->paper_vol=$item_vol;
		$this->item_pic=$_SESSION['pic_path'].$this->item_filename;
		//echo "<br>圖片路徑＝ $this->item_pic ";
        $this->concept_id=$concept_name;
		for($i=0;$i<$this->item_select_num;$i++){
			$this->item_select_pic[$i]=$_SESSION['pic_path'].$this->op_pieces[$i];
			//echo "<br>圖片路徑＝ $this->item_pic ";
		}

		//--  對應補救教學概念///讀檔
		$this->item_remedy_path=$_SESSION['cs_path'].$this->item_remedy_file;
		//debug_msg("第".__LINE__."行 item_remedy_path ", $this->item_remedy_path);
		//die();
		$item_remedy_data=read_excel($this->item_remedy_path, __LINE__);
		//debug_msg("item_remedy_data ".__LINE__, $item_remedy_data);
		$this->remedy_concept=$item_remedy_data[1][$this->selected-1];
	}

	function get_item_pic(){return $this->item_pic;}
    
	function get_item_select_num(){return $this->item_select_num;}
           
	function get_item_select_pic(){return $this->item_select_pic;}
           
	function get_item_correct_answer(){return $this->item_correct_answer;}
           
	function get_item_selected(){return $this->selected;}
           
	function get_item_concept_id(){return $this->concept_id;}
           
	function get_item_remedy_concept(){return $this->remedy_concept;}
           
	function get_item_score(){return $this->score;}
           
	function get_item_vol(){return $this->paper_vol;}
}

Class Item_Structure4IRT{

	//變數名稱
	private $item_pic;//題目圖片版本
	private $item_select_num;//題目總共的選項
	private $item_select_pic;//題目選項圖片版本
	private $item_correct_answer;//題目正確選項
	private $selected;
	private $concept_id;
	private $score;
	private $vol;

	//function
	function Item_Structure4IRT($cs_id,$item_num,$paper_vol){
		global $dbh;
		/*藉由$concept_name和$item_selected_num和$item_vol去試題資料表抓資料填入底下相關欄位
		$this->item_pic代表試題本題圖片放的位置
		$this->item_select_num該試題有幾個選項
		$this->item_correct_answer該試題正確答案是說多少
		$this->selected該試題本身代表第幾題
		$this->concept_id該試題本身隸屬哪一個結構
		$this->remedy_concept該試題在其補救教學結構下屬於第幾個概念
		$this->score本身是幾分
		$this->paper_vol本身是第幾版(A卷，B卷，...)
		$this->item_select_pic個選項的圖片位置，一維度矩陣*/

		$sql = "select * from concept_item where cs_id = '$cs_id' and paper_vol = '".$paper_vol."' and item_num='$item_num'";
		//debug_msg("sql ".__LINE__ , $sql);
		$result = $dbh->query($sql);
		while ($data = $result->fetchRow()) {
			$this->exam_paper_id=$data['exam_paper_id'];
			$this->item_filename=$data['item_filename'];
			$this->op_filename=$data['op_filename'];
			$this->item_correct_answer=$data['op_ans'];
			$this->score=$data['points'];
			$this->selected=$data['item_num'];
			//debug_msg("第".__LINE__."行 data ", $data);
		}
      $csid_ary=explode_cs_id($cs_id);
      $this->cs_path=_ADP_CS_UPLOAD_PATH.$csid_ary[0]."/".$csid_ary[1]."/".$csid_ary[2]."/".$csid_ary[3]."/";
		$_SESSION['cs_path']=$this->cs_path;
		//echo $_SESSION['cs_path'];
		$_SESSION['pic_path']=_ADP_EXAM_DB_PATH.$csid_ary[0]."/".$csid_ary[1]."/".$csid_ary[2]."/".$csid_ary[3]."/";

		$this->op_pieces = explode(_SPLIT_SYMBOL, $this->op_filename);   //題目選項圖片檔名的陣列
		$this->item_select_num=count($this->op_pieces)-1;
		$this->paper_vol=$paper_vol;
		$this->item_pic=$_SESSION['pic_path'].$this->item_filename;
		//echo "<br>圖片路徑＝ $this->item_pic ";
      $this->concept_id=$cs_id;
		for($i=0;$i<$this->item_select_num;$i++){
			$this->item_select_pic[$i]=$_SESSION['pic_path'].$this->op_pieces[$i];
			//echo "<br>圖片路徑＝ $this->item_pic ";
		}
		//debug_msg("第".__LINE__."行 this ", $this);
	}

	function get_item_pic(){return $this->item_pic;}

	function get_item_select_num(){return $this->item_select_num;}

	function get_item_select_pic(){return $this->item_select_pic;}

	function get_item_correct_answer(){return $this->item_correct_answer;}

	function get_item_selected(){return $this->selected;}

	function get_item_concept_id(){return $this->concept_id;}

	function get_item_remedy_concept(){return $this->remedy_concept;}

	function get_item_score(){return $this->score;}

	function get_item_vol(){return $this->paper_vol;}
}



Class Remedy_Structure{

	//member data
	private $concept_id;
	private $structure;
	private $threshold;
	private $concept_num;
	//member function
        
	function Remedy_Structure($concept_id){
		global $dbh;
	/*藉由$concept_id去試題結構表中抓補救教學結構表的位置並填入$structure_path即可*/
		$this->concept_id=$concept_id;
		$sql = "select * from concept_info where cs_id = '{$concept_id}'";   
		$result = $dbh->query($sql);
		while ($data = $result->fetchRow()) {
			$this->publisher_id=$data['publisher_id'];
			$this->subject_id=$data['subject_id'];
			$this->vol=$data['vol'];
			$this->unit=$data['unit'];
			$this->grade=$data['grade'];
			$this->threshold=$data['threshold'];
			$this->matrix_map_file=$data['matrix_map'];
			$this->remedy_file=$data['remedy_file'];
			$this->item_remedy_file=$data['item_remedy_file'];
		}
		
		$matrix_map_file_path=$_SESSION['cs_path'].$this->matrix_map_file;
		$structure_path=$_SESSION['cs_path'].$this->remedy_file; //由名稱去找對應的檔案存放位置
		$this->IRF_path=$_SESSION['cs_path'].$this->item_remedy_file;
		//debug_msg("structure_path ".__LINE__, $structure_path);
//		$structure_temp=read_excel($structure_path, __LINE__);
		for($i=0;$i<sizeof($structure_temp);$i++){
			$this->structure[$i]=$structure_temp[$i][0];
			$this->threshold[$i]=$structure_temp[$i][1];
		}
		$this->concept_num=sizeof($this->structure);
	}

	function get_concept_id(){return $this->concept_id;}

	function get_structure(){return $this->structure;} 
	
	function get_threshold(){return $this->threshold;}
		
	function get_concept_num(){return $this->concept_num;} 

	function get_remedy2item(){
		//debug_msg("IRF_path ".__LINE__, $this->IRF_path);
		$this->IRF_str=read_excel($this->IRF_path, __LINE__);
		$this->IRFcount=sizeof($this->IRF_str[1]);
		for($i=0;$i<sizeof($this->IRF_str[1]);$i++){
			$this->remedy2item[$this->IRF_str[1][$i]].=$this->IRF_str[0][$i]._SPLIT_SYMBOL;
		}

		return $this->remedy2item;
	} 
		
}


Class Student_Structure{

	//member_data 
	var $response;

	var $school_name;
	var $class_name;
	var $student_name;

	
	//member_function
	function Student_Structure($school_id,$class_name,$user_id){
		$this->student_name= id2uname($user_id);
		$this->school_name=id2org($school_id);
		$this->class_name=$class_name;
	}
             
	function initial_response($item_num){  //初始作答反應
		for($i=0;$i<$item_num;$i++){
			$this->response[$i]=-1;
		}
		//debug_msg("response", $this->response);
	}
              
	function set_response($input_response){	
		$this->response=$input_response; 
	}
              
	function get_response(){	return $this->response;	}
	      
	function get_school_name(){	return $this->organization_name; }
              
	function get_class_name(){	return $this->class_name; }
              
	function get_student_name(){	return $this->uname; }
}








?>
