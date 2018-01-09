<?php

ini_set('display_errors','1');
error_reporting(E_ALL);

require_once "include/adp_API.php";
require_once 'Date.php';
require_once "IRTConceptStructure_sim.php";
//require_once "GPCMConceptStructure.php";
require_once 'db.php';
//require_once '/classes/PHPExcel.php';
//require_once '/classes/PHPExcel/IOFactory.php';

//$IRT=new GPCMConceptStructure('GPCM_rasch.xlsx');
$IRT=new IRTConceptStructure('GPCM_rasch.xlsx');


$ii=count($IRT->response_data);
$jj=count($IRT->parameter_data);
for($i=0;$i<$ii;$i++){
  unset($A_record,$B_record,$category_record);
  for($j=0;$j<$jj;$j++){
    $A_record[$j]=$IRT->parameter_data[$j][0];
    $B_record[$j]=$IRT->parameter_data[$j][1];
    $C_record[$j]=$IRT->parameter_data[$j][2];
    $category_record[$j]=$IRT->response_data[$i][$j];  
    //$h_hat[$i][$j]=$IRT->get_EAP($A_record,$B_record,$category_record);
    $h_hat[$i][$j]=$IRT->get_EAP($A_record,$B_record,$C_record,$category_record);
  }  
}
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->fromArray($h_hat, null, 'A1');
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2010');
//ob_clean();
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//header('Content-Disposition: attachment;filename="GPCM_hat.xlsx"');
//header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$objWriter->save('GPCM_hat.xlsx');
$objWriter->save('IRT_hat.xlsx');


?>