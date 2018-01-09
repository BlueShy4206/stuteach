<?php

ini_set('display_errors','1');
error_reporting(E_ALL);

require_once "include/adp_API.php";

CLASS GPCMConceptStructure{

  public $parameter_data;
  public $response_data; 
  //設定
  private $D=1; //IRT D

  //for simulation
  function __construct($filename){
  	require_once '/classes/PHPExcel.php';
    require_once '/classes/PHPExcel/IOFactory.php';
  
   // 設定要被讀取的檔案，不使用中文檔名
   //$file = 'import.xlsx';
  
   try {
       $objPHPExcel = PHPExcel_IOFactory::load($filename);
   } catch(Exception $e) {
       die('Error loading file "'.pathinfo($file,PATHINFO_BASENAME).'": '.$e->getMessage());
   }
   $objPHPExcel->setActiveSheetIndex(0);  
   $this->parameter_data=$objPHPExcel->getActiveSheet()->toArray(null, true, true, false);
   array_shift($this->parameter_data);
   $objPHPExcel->setActiveSheetIndex(1);
   $this->response_data=$objPHPExcel->getActiveSheet()->toArray(null, true, true, false);
  }

  public function get_EAP($A_record,$B_record,$category_record){
    $h_hat=$this->EAP($A_record,$B_record,$category_record);
    return $h_hat;
  }
  
  //GPCM P
  private function P($A_item,$B_item,$h_theta,$category){
    //$P=$P_u/$P_l
    $B_item_explode=explode(_SPLIT_SYMBOL,$B_item);
    if(end($B_item_explode)==''){
      array_pop($B_item_explode);
    }
    $D=$this->D;
    $P_l=1;
    $P_u=0;
    $tmp=0;
    foreach($B_item_explode as $key=>$b){
      $tmp=$tmp+$D*$A_item*($h_theta-$b);
      $P_l=$P_l+exp($tmp);
      if($category>0 && $key<=$category-1){
        $P_u=$P_u+exp($tmp);
      }
    }
    if($category==0){
      $P=1/$P_l;      
    }else{
      $P=$P_u/$P_l;
    }
    return $P;  
  }
  
  //GPCM information
  private function information($A_item,$B_item,$h_theta,$scoring_array){
    //get P_array , $T_hat
    $D=$this->D;
    $T_hat=0;
    foreach($scoring_array as $score_key => $score){
      $P_array[$score_key]=$this->P($A_item,$B_item,$h_theta,$score_key);
      $T_hat=$T_hat+$score*$P_array[$score_key];
    }
    
    $tmp=0;
    foreach($B_item as $key => $value){
      $tmp=$tmp+pow(($scoring_array[$key]-$T_hat),2)*$P_array[$key];      
    }
    $information=pow(($D*$A_item),2)*$tmp;
    return $information;
  }
  
  private function EAP($A_record,$B_record,$category_record){
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
      $select_num=count($category_record);
      //for($i=0;$i<($select_num-1);$i++){
      for($i=0;$i<($select_num);$i++){
        $P=$this->P($A_record[$i],$B_record[$i],$Xk,$category_record[$i]);
        $L=$L*$P;                             
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

}
?>