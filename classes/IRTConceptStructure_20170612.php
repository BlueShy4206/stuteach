<?php
require_once "include/adp_API.php";

class IRTConceptStructure{
  
  //設定
  private $D=1; //IRT D
  
  //目前用不到，但 sql 要存
  public $type_id_e;  //曝光率控制法  1.FI 2.ASHCOF_FI
  public $type_id_s;  //選題法
  public $type_id_t;  //能力估計法 1.MLE 2.EAP
  public $num_e;  //更新曝光的人數
  public $item_length;  //題組測驗數量
  public $exam_type;
  //end
  
  public $AuthCourseID; //course_id
  public $h_exam; //目前估計的能力
  public $h_exam_b; //前一次測驗估計的能力
  public $h_exam_A; //theta的歷次記錄
  public $error_d;  //兩次 theta 差
  public $select_item_id_S; //選題的歷次記錄
  public $select_item_id_A; //選題的歷次記錄
  public $select_item_sn; //目前選到的試題 sn
  public $select_item_num;  //目前選到的試題 num
  public $rec_user_answer;  //紀錄受試者答案
  public $select_P_a; //紀錄已做過試題的 a
  public $select_P_b;
  public $select_P_c;
  public $select_sub; //記錄已作過試題的 sub
  public $response; //紀錄response
  public $select_num; //目前總題數
  public $in_testlet; //是否處於題組狀況中
  public $testlet_info; //題組資訊
  
  private $concept_id;
  private $paper_vol;
  private $ep_id;
  private $sub_n; //子測驗數
  private $item_info; //試題資料
  private $item_if_testlet; //試題是否為題組題
  private $item_left; //剩餘試題
  
  //CK 專用
  public $step; //測驗階段
  //step=1 預試試題階段，只做預試的試題，不算分。
  //step=2 正式測驗階段，每一個 sub 輪流出題，直到時間停止或達到每個 sub 最大出題數
  private $pretest_sub_score_name="新增試題"; //新增試題的 sub_score_name
  private $pretest_sub; //預試試題的sub
  private $pretest_info;  //預試試題資料
  public $sub_score_name; //子測驗名稱
  public $select_item_sub_A;  //各子測驗已出題數
  public $max_sub_itemlength;  //各 sub 最大施測題數(array)
  public $pretest_rec_user_answer;  //預試階段紀錄受試者答案
  public $pretest_response; //預試階段紀錄response
  public $pretest_select_item_id; //預試階段的選題記錄
  public $pretest_select_num; //預試階段目前題數
  public $pretest_item_num_order; //預試試題順序
  
  function __construct($concept_id, $paper_vol){
    global $dbh;
    
    $this->AuthCourseID=$_SESSION["AuthCourseID"];
    $this->concept_id=$this->cs_id=$concept_id;
    $this->paper_vol=$paper_vol;
    $this->ep_id=$this->concept_id.sprintf("%02d",$this->paper_vol);
    
    //建立 type_id 試卷施測類型
    $sql="SELECT * FROM exam_course_access_irt WHERE cs_id='.$this->concept_id.' and course_id='.$this->AuthCourseID.' and paper_vol='.$this->paper_vol.'";
    $result = $dbh->query($sql);
    while ($row = $result->fetchRow()){
  		$this->type_id_e = $row['type_id_e']; //  曝光率控制法  1.FI 2.ASHCOF_FI
  		$this->type_id_s = $row['type_id_s']; //  選題法
  		$this->type_id_t = $row['type_id_t']; //  能力估計法 1.MLE 2.EAP
  		$this->num_e = $row['num_e']; //  更新曝光的人數
  		$this->item_length = $row['item_length']; //  題組測驗數量
    }
    //end
    
    //建立 $this->select_item_sub_A
    $sql="SELECT sub FROM concept_info_dim WHERE cs_id = '{$this->concept_id}'";
    $result = $dbh->query($sql);
    while ($data = $result->fetchRow()){
      $this->sub_n=$data['sub'];
    }
    for($i=0;$i<$this->sub_n;$i++){
      if($i==0){
        $this->select_item_sub_A="0";
      }else{
        $this->select_item_sub_A.=_SPLIT_SYMBOL."0";      
      }           
    }
    //建立 $this->select_item_sub_A end
    
    //建立試題資料 $this->item_info[題號]
    //建立剩餘試題號碼 $this->item_left[題號]=題號
    $this->make_item_info_and_left();
    //建立試題資料 end
    
    //CK 專用
      //建立預試試題資料
      $this->make_pretest_sub();
      $this->make_pretest_itemleft();  //$this->pretest_info
      //建立預試試題資料 end
      
      //建立各 sub 最大施測題數
      $sql="SELECT sub_test_num FROM exam_paper_subscale WHERE exam_paper_id='{$this->ep_id}'";
      $result = $dbh->query($sql);
      while ($data = $result->fetchRow()){
        $this->max_sub_itemlength=explode(_SPLIT_SYMBOL,$data['sub_test_num']);
      }
      //建立各 sub 最大施測題數 end
      
      //建立 sub_score_name
      $sql="SELECT DISTINCT sub_score_name FROM concept_info_dim WHERE cs_id = '{$this->concept_id}'";
      $result = $dbh->query($sql);
      while ($data = $result->fetchRow()){
        $this->sub_score_name=$data['sub_score_name'];
      }
    
  }
  
  //公用function
  public function get_csid(){return $this->concept_id;}
  public function get_paper_vol(){return $this->paper_vol;}
  public function get_epid(){return $this->ep_id;}
  public function get_item_a($item_num){return $this->item_info[$item_num]["a"];}
  public function get_item_b($item_num){return $this->item_info[$item_num]["b"];}
  public function get_item_c($item_num){return $this->item_info[$item_num]["c"];}
  public function get_item_op_ans($item_num){return $this->item_info[$item_num]["op_ans"];}
  public function get_item_sub($item_num){return $this->item_info[$item_num]["sub"];}
  public function get_pretest_sub(){return $this->pretest_sub;}
  public function get_sub_n(){return $this->sub_n;}
  public function get_item_if_testlet($item_num){return $this->item_if_testlet[$item_num];}
    //刪除 $this->item_info["item_left"] 裡的題目
    public function del_item_left($item_num){
      unset($this->item_info["item_left"][$item_num]);
    }
    
    //從 item_num 取得 item_sn
    public function get_item_sn_by_num($item_num){return $this->item_info[$item_num]["item_sn"];}
    
    //[CK]刪除 $this->pretest_info["item_left"] 裡的題目
    public function del_pretest_left($item_num){
      unset($this->pretest_info["item_left"][$item_num]);     
    }
    
    //取得MLE
    public function get_MLE(){
      $A_item=explode(_SPLIT_SYMBOL,$this->select_P_a);
      array_pop($A_item);
      $B_item=explode(_SPLIT_SYMBOL,$this->select_P_b);
      array_pop($B_item);
      $C_item=explode(_SPLIT_SYMBOL,$this->select_P_c);
      array_pop($C_item);
      $X_item=explode(_SPLIT_SYMBOL,$this->response);
      array_pop($X_item);
      $h_exam=$this->h_exam;
      return $this->MLE($A_item,$B_item,$C_item,$X_item,$h_exam);    
    }
    
    //取得EAP
    public function get_EAP(){
      $A_item=explode(_SPLIT_SYMBOL,$this->select_P_a);
      array_pop($A_item);
      $B_item=explode(_SPLIT_SYMBOL,$this->select_P_b);
      array_pop($B_item);
      $C_item=explode(_SPLIT_SYMBOL,$this->select_P_c);
      array_pop($C_item);
      $X_item=explode(_SPLIT_SYMBOL,$this->response);
      array_pop($X_item);
      $h_exam=$this->h_exam;
      return $this->EAP($A_item,$B_item,$C_item,$X_item);
    }
    
    //[CK]取得各 sub MLE
    public function get_sub_MLE(){
      $A_item=explode(_SPLIT_SYMBOL,$this->select_P_a);
      array_pop($A_item);
      $B_item=explode(_SPLIT_SYMBOL,$this->select_P_b);
      array_pop($B_item);
      $C_item=explode(_SPLIT_SYMBOL,$this->select_P_c);
      array_pop($C_item);
      $X_item=explode(_SPLIT_SYMBOL,$this->response);
      array_pop($X_item);
      $sub_item=explode(_SPLIT_SYMBOL,$this->select_sub);
      array_pop($sub_item);
      $h_exam=0;
      for($i=0;$i<$this->select_num;$i++){
        $sub_A_item[$sub_item[$i]][]=$A_item[$i];
        $sub_B_item[$sub_item[$i]][]=$B_item[$i];
        $sub_C_item[$sub_item[$i]][]=$C_item[$i];
        $sub_X_item[$sub_item[$i]][]=$X_item[$i];        
      }
      for($i=1;$i<=$this->sub_n;$i++){
        if($i==$this->pretest_sub){
          $sub_MLE[$i]=NULL;          
        }else{
          $sub_MLE[$i]=$this->MLE($sub_A_item[$i],$sub_B_item[$i],$sub_C_item[$i],$sub_X_item[$i],$h_exam);
        }
      }
      $sub_MLE=implode(_SPLIT_SYMBOL,$sub_MLE);
      return $sub_MLE;
    }
  
  //內部用function
	
  //
  private function make_item_info_and_left(){
    global $dbh;
    $sql="SELECT concept_item_parameter.item_sn , concept_item.item_num , concept_item_parameter.a , concept_item_parameter.b , concept_item_parameter.c , concept_item_parameter.sub , concept_item.op_ans FROM concept_item_parameter,concept_item WHERE concept_item.exam_paper_id like '{$this->ep_id}' and concept_item.item_sn=concept_item_parameter.item_sn";
    $result = $dbh->query($sql);
    while ($row = $result->fetchRow()){
      $this->item_info[$row["item_num"]]["item_sn"]=$row["item_sn"];
      $this->item_info[$row["item_num"]]["a"]=$row["a"];
      $this->item_info[$row["item_num"]]["b"]=$row["b"];
      $this->item_info[$row["item_num"]]["c"]=$row["c"];
      $this->item_info[$row["item_num"]]["sub"]=$row["sub"];
      $this->item_info[$row["item_num"]]["item_num"]=$row["item_num"];
      $this->item_info[$row["item_num"]]["op_ans"]=$row["op_ans"];
      $this->item_info["item_left"][$row["item_num"]]=$row["item_num"];
      
      //建立題組資訊
      $this->item_if_testlet[$row["item_num"]]=0;
      $sql_testlet="SELECT testlet_sn,testlet_num,testlet_sub_num FROM concept_item_testlet WHERE item_sn='".$row["item_sn"]."' ";
      $result_testlet = mysql_query($sql_testlet);
      while($row_testlet=mysql_fetch_array($result_testlet)){
        $this->item_info[$row["item_num"]]["testlet_sn"]=$row_testlet["testlet_sn"];
        $this->item_info[$row["item_num"]]["testlet_num"]=$row_testlet["testlet_num"];
        $this->item_info[$row["item_num"]]["testlet_sub_num"]=$row_testlet["testlet_sub_num"];
        $this->item_if_testlet[$row["item_num"]]=1;
        //$_SESSION["item_info"][$row["item_num"]]["testlet_sn"]=$row_testlet["testlet_sn"];
        //$_SESSION["item_info"][$row["item_num"]]["testlet_num"]=$row_testlet["testlet_num"];
        //$_SESSION["item_info"][$row["item_num"]]["testlet_sub_num"]=$row_testlet["testlet_sub_num"];
        //$_SESSION["item_if_testlet"][$row["item_num"]]=1;        
      }      
    }  
  }

  
  //IRT information
  private function information($h_exam,$A_item,$B_item,$C_item)
  {
    $D = $this->D;
    $H = $h_exam;
    $B = $B_item;
    $A = $A_item;
    $C = $C_item;
    
    $L = pow($D*$A,2);
    $I = pow((1+exp(-$D*$A*($H-$B))),2);
    $information = ($L*(1-$C))/(($C+exp($D*$A*($H-$B)))*$I);
    return $information;
  }
  //IRT information end
  
  //最大訊息法選題
  public function FI($h_exam){
    $max_information=0;
    
    $item_left_keys=array_keys($this->item_info["item_left"]);
    $ii=count($item_left_keys);
    for($i=0;$i<$ii;$i++){
      $A_item=$this->item_info[$this->item_info["item_left"][$item_left_keys[$i]]]["a"];
      $B_item=$this->item_info[$this->item_info["item_left"][$item_left_keys[$i]]]["b"];
      $C_item=$this->item_info[$this->item_info["item_left"][$item_left_keys[$i]]]["c"];
      $item_sub=$this->item_info[$this->item_info["item_left"][$item_left_keys[$i]]]["sub"];
      $item_sn=$this->item_info[$this->item_info["item_left"][$item_left_keys[$i]]]["item_sn"];
      $item_num=$this->item_info[$this->item_info["item_left"][$item_left_keys[$i]]]["item_num"];
      $information=$this->information($h_exam,$A_item,$B_item,$C_item);
      if ($information>$max_information){
        $max_information = $information;
        $item_num_t=$item_num;
        $item_sn_t=$item_sn;
      }    
    }
    return $item_sn_t;
  }
  //最大訊息法選題 end
  
  //MLE
  private function MLE($A_item,$B_item,$C_item,$X_item,$h_exam){    
    $D = $this->D;    
    $h=0.01;
    $s=0;

    
    //modify h_exam
    /*
    $tmp=explode(_SPLIT_SYMBOL,$X_item);
    array_pop($tmp);
    $percent=array_sum($tmp)/sizeof($tmp);
    $h_exam=6.4*$percent-3.2;
    */
    
    //[CK]減去預試題數
    $select_num=sizeof($X_item);
    while (abs($h)>= 0.01){
      $h_exam_tmp_1 = $h_exam; 
      $f_1_sum = 0;
      $f_2_sum = 0; 
    
       for($i=0;$i<=($select_num-1);$i++)
      {    
        $U = $this->U($D,$A_item[$i],$B_item[$i],$h_exam);  
        $P = $this->P($C_item[$i],$U);  
        $f_1_sum_tmp = $this->f_1_sum_tmp($A_item[$i],$C_item[$i],$X_item[$i],$P);
        $f_1_sum = $f_1_sum+$f_1_sum_tmp; 
        $f_2_sum_tmp = $this->f_2_sum_tmp($A_item[$i],$C_item[$i],$X_item[$i],$P);
        $f_2_sum = $f_2_sum+$f_2_sum_tmp; 
      }
      $f_1 = $D*$f_1_sum;
      $f_2 = (-1)*pow($D,2)*$f_2_sum;
      if ($f_2 !=0)
      {
        $h = $f_1/$f_2;  
        $h_exam = $h_exam - $h;
      }
      elseif (($f_2 ==0) && ($X_item[($select_num-1)]==1))
      {
        $h_exam = 3.2;
      }
      elseif (($f_2 ==0) && ($X_item[($select_num-1)]==0))
      {
        $h_exam = -3.2;
      }
       
      if ($h_exam >= 3.2)
      {
        $h_exam = 3.2;
      }
      elseif ($h_exam <= -3.2)
      {
        $h_exam = -3.2;
      }
      $s=$s+1;
      
      if ($s > 30)
      //if ($s > 30 || abs($h_exam)==3.2 )
      {
        break;
      }  
    }
    $h_exam = round($h_exam,4);
    return $h_exam; 
   
  }
  private function U($D,$A_item,$B_item,$h_exam)   
  {   
    $U = pow((1+exp(-$D*$A_item*($h_exam-$B_item))),(-1));
    return $U;
  }
  private function P($C_item,$U) 
  {   
    $P = $C_item+(1-$C_item)*$U;
    return $P;
  }
  private function f_1_sum_tmp($A_item,$C_item,$X_item,$P)
  {   
    $f_1_sum_tmp = ($X_item-$P)*$A_item*($P-$C_item)/($P*(1-$C_item));
    return $f_1_sum_tmp;
  }
  private function f_2_sum_tmp($A_item,$C_item,$X_item,$P)
  {   
    $f_2_sum_tmp = $A_item*$A_item*($P-$C_item)*($P-$C_item)*(1-$P)/($P*(1-$C_item)*(1-$C_item));
    return $f_2_sum_tmp;
  }
  //MLE end
  
  //EAP
  private function EAP($A_item,$B_item,$C_item,$X_item){
  
    //將能力區間切成41個點
    $D = $this->D;
    $q_length = 41;
    $q_points = array(4,3.8,3.6,3.4,3.2,3,2.8,2.6,2.4,2.2,2,1.8,1.6,1.4,1.2,1,0.8,0.6,0.4,0.2,0,-0.2,-0.4,-0.6,-0.8,-1,-1.2,-1.4,-1.6,-1.8,-2,-2.2,-2.4,-2.6,-2.8,-3,-3.2,-3.4,-3.6,-3.8,-4);
    //每一個能力區間出現的機率值
    $q_pro = array(0.00013383,0.000291947,0.000611902,0.001232219,0.002384088,0.004431848,0.007915452,0.013582969,0.02239453,0.035474593,0.053990967,0.078950158,0.110920835,0.149727466,0.194186055,0.241970725,0.289691553,0.333224603,0.36827014,0.391042694,0.39894228,0.391042694,0.36827014,0.333224603,0.289691553,0.241970725,0.194186055,0.149727466,0.110920835,0.078950158,0.053990967,0.035474593,0.02239453,0.013582969,0.007915452,0.004431848,0.002384088,0.001232219,0.000611902,0.000291947,0.00013383);
  
    $h_exam_u = 0;  //$h_exam的分子
    $h_exam_l = 0;  //$h_exam的分母
    for($k=0;$k<$q_length;$k++){
      $Xk = $q_points[$k];  //第i個能力點
      $W = $q_pro[$k];  //第i個能力點的出現機率
      $L_W = 0;
      $L = 1;
      
      //求likelihood
      $select_num=sizeof($X_item);
      for($i=0;$i<($select_num-1);$i++){
        $U = $this->U($D,$A_item[$i],$B_item[$i],$Xk);
        $P = $this->P($C_item[$i],$U);
        if($X_item[$i]==1){
          $L = $L*$P;
        }else{
          $L = $L*(1-$P);
        }                             
      }      
      $L_W = $L*$W; 
      $L_W_0 = round($L_W,4);
      $h_exam_u = $h_exam_u+($Xk*$L_W);  
      $h_exam_l = $h_exam_l+$L_W;             
    }
    $h_exam = $h_exam_u/$h_exam_l;
    $h_exam = round($h_exam,4);
    return $h_exam;
  }
  //EAP end
  
  //CK 專用 function
    //取得預試試題 sub
    private function make_pretest_sub(){
      global $dbh;
      $sql="SELECT sub_score_name FROM concept_info_dim WHERE cs_id='{$this->concept_id}'";
      $result = $dbh->query($sql);
      while ($data = $result->fetchRow()){
        $sub_score_name=$data["sub_score_name"];
      }
      $sub_score_name=explode(_SPLIT_SYMBOL,$sub_score_name);
      $ii=count($sub_score_name);
      for($i=0;$i<$ii;$i++){
        if($sub_score_name[$i]==$this->pretest_sub_score_name){
          $sub=$i+1;
        }
      }
      $this->pretest_sub=$sub;
    }
    //取得預試試題 sub end
    
    //建立預試試題剩餘試題號碼
    private function make_pretest_itemleft(){
      global $dbh;
      $sql="SELECT concept_item_parameter.item_sn , concept_item.item_num , concept_item_parameter.a , concept_item_parameter.b , concept_item_parameter.c , concept_item_parameter.sub FROM concept_item_parameter,concept_item WHERE concept_item.exam_paper_id like '{$this->ep_id}' and concept_item_parameter.sub='{$this->pretest_sub}' and concept_item.item_sn=concept_item_parameter.item_sn";
      $result = $dbh->query($sql);
      while ($row = $result->fetchRow()){
        $this->pretest_info["item_left"][$row["item_num"]]=$row["item_num"];
      }
    }
    //建立預試試題剩餘試題號碼 end

    //預試隨機選題
    public function pretest_random_item(){
      $select_item_num=array_rand($this->pretest_info["item_left"],1);
      return $select_item_num;
    }
    //預試隨機選題 end
    
    //排出預試試題順序
    public function get_pretest_item_num_order(){
      $select_item_num_order=array_rand($this->pretest_info["item_left"],$this->max_sub_itemlength[$this->pretest_sub-1]);
      shuffle($select_item_num_order);
      return $select_item_num_order;
    }
    //排出預試試題順序 end
    
    //決定下一個 sub
    public function next_sub(){
    
      //目前 sub
      $sub=$this->get_item_sub($this->select_item_num);

      $select_item_sub_A=explode(_SPLIT_SYMBOL,$this->select_item_sub_A);
      do{
        $sub=(($sub)%$this->sub_n)+1;
      }while($select_item_sub_A[$sub-1]>=$this->max_sub_itemlength[$sub-1]);
      return $sub;
    
    }
    
    //最大訊息法，只找 $sub 子測驗
    public function FI_by_sub($sub){
      global $dbh;
      $max_information=0;
      
      $item_left_keys=array_keys($this->item_info["item_left"]);
      $ii=count($item_left_keys);
      for($i=0;$i<$ii;$i++){
        $A_item=$this->item_info[$this->item_info["item_left"][$item_left_keys[$i]]]["a"];
        $B_item=$this->item_info[$this->item_info["item_left"][$item_left_keys[$i]]]["b"];
        $C_item=$this->item_info[$this->item_info["item_left"][$item_left_keys[$i]]]["c"];
        $item_sub=$this->item_info[$this->item_info["item_left"][$item_left_keys[$i]]]["sub"];
        $item_sn=$this->item_info[$this->item_info["item_left"][$item_left_keys[$i]]]["item_sn"];
        $item_num=$this->item_info[$this->item_info["item_left"][$item_left_keys[$i]]]["item_num"];
        //因應 $sub 而做的特殊加權
        if($sub==$item_sub){
          $sub_information_times=1;
        }else{
          $sub_information_times=0;
        }
        $information=$this->information($this->h_exam,$A_item,$B_item,$C_item)*$sub_information_times;
        if ($information>$max_information){
          $max_information = $information;
          $item_num_t=$item_num;
          $item_sn_t=$item_sn;
        }    
      }
      
      //確認是否為題組，如果是要額外處理
      unset ($this->in_testlet);
      unset ($this->testlet_info);
      if($this->item_if_testlet[$item_num_t]==1){
        $this->in_testlet=1;  //判斷是否在題組中之flag
        $testlet_num_t=$this->item_info[$item_num_t]["testlet_num"];
        $sql=mysql_query("SELECT concept_item_testlet.item_sn,concept_item_testlet.testlet_sub_num,concept_item.item_num,concept_item_parameter.sub FROM concept_item_testlet,concept_item,concept_item_parameter WHERE concept_item_testlet.exam_paper_id='".$this->ep_id."' and concept_item_testlet.testlet_num='".$testlet_num_t."' and concept_item_testlet.item_sn=concept_item.item_sn and concept_item_testlet.item_sn=concept_item_parameter.item_sn ORDER BY concept_item_testlet.testlet_sub_num");
        while($row=mysql_fetch_array($sql)){
          $this->testlet_info[$row["testlet_sub_num"]]["item_sn"]=$row["item_sn"];
          $this->testlet_info[$row["testlet_sub_num"]]["item_num"]=$row["item_num"];
        }
        mysql_free_result($sql);
        $item_sn_t=$this->testlet_info[1]["item_sn"];
        $item_num_t=$this->testlet_info[1]["item_num"];
      }       
      return $item_num_t;
    }
    //最大訊息法，只找 $sub 子測驗 end
  
}
?>