<?php

include ("classes/Jpgraph/jpgraph.php");
include ("classes/Jpgraph/jpgraph_radar.php");
//include_once ("include/adp_core_function.php");

define('_ADP_TMP_UPLOAD_PATH' , dirname($_SERVER['SCRIPT_FILENAME'])."/data/tmp/");

$data_line_color=array("red","blue","green","purple","darkred");
$ball_color=array("red","bluegreen","green","purple","gray");
//$data_fill_color=array("lightblue","gray","green","purple","");
if(isset($_GET['q'])){
	$import_data_path=_ADP_TMP_UPLOAD_PATH.$_GET['q'].".csv";
	$fp = fopen($import_data_path, "r");
	$j=0;
	while ( $ROW = fgetcsv($fp, 1024) ) {  // 在資料列有內容時（長度大於 0），才做以下動作
		if ( strlen($ROW[0]) && $j>0 && $ROW[0]!='') { 
			for($i=0;$i<sizeof($ROW);$i++){
				$ROW[$i]=iconv("big5", "UTF-8", $ROW[$i]);   //轉成utf-8
			}
			//畫線段
			${plot.$j} = new RadarPlot($ROW);
			//$plot = new RadarPlot($_SESSION['IIrelation'][4]);
			//$_GET['qqq']
			${plot.$j}->SetLegend('卷'.$j, 16);
			${plot.$j}->SetColor($data_line_color[$j-1]);
			if($j>1){
				${plot.$j}->SetFill(false);
			}else{
				${plot.$j}->SetFillColor('yellow');
			}
			${plot.$j}->SetLineWeight(3);
			/*
			${plot.$j}->mark->SetType(MARK_FILLEDCIRCLE);
			${plot.$j}->mark->SetFillColor($ball_color[$j-1]);
			${plot.$j}->mark->SetWidth(15);
			*/
			${plot.$j}->mark->SetType(MARK_IMG_SBALL, $ball_color[$j-1]);
			${plot.$j}->mark->SetWidth(5);
			//${plot.$j}->mark->SetType(MARK_IMG_DIAMOND,5,0.6);
			$graph->Add(${plot.$j});
		}elseif($j==0 && $ROW[0]!=''){
			/*for($i=0;$i<sizeof($ROW);$i++){
				//$a[$i]=iconv("big5", "UTF-8", $ROW[$i]);   //轉成utf-8
				if($_SERVER["HTTP_HOST"]=="210.240.187.203" || $_SERVER["SERVER_NAME"]=="ksat.ladder100.com"){
					$a=array("數與量","幾何","代數","統計與機率");
				}else{
					$a[]=$ROW[$i];
				}
			}*/
			if($_GET[report]==2){  //下載版
				$w=600;
				$h=400;
			}else{
				$w=600;
				$h=400;
			}
			$a=array("數與量","幾何","代數","統計與機率");
			//畫底圖
			$graph = new RadarGraph($w,$h,"auto");
			$graph->SetImgFormat('png');
			$graph->SetColor("white");
			$graph->SetShadow();
			$graph->SetScale("lin",0,100);
			//$graph->SetScale("lin",0,50);
			$graph->yscale->ticks->Set(20,10);
			$graph->SetCenter(0.45,0.5);
			$graph->SetSize(0.7);
			$graph->axis->SetFont(FF_CHINESE,FS_BOLD);
			$graph->grid->SetLineStyle("dashed");
			$graph->grid->SetColor("navy");
			$graph->grid->Show();
			$graph->HideTickMarks();
			// Setup graph titles
			//$graph->title->Set($_GET['report']);
			$graph->title->SetFont(FF_CHINESE,FS_BOLD);
			$graph->SetTitles($a);
			$graph->legend->SetFont(FF_CHINESE,FS_BOLD);
		}
		$j++;
	}
	fclose($fp);
}
//$graph->Stroke(_ADP_TMP_UPLOAD_PATH.$_GET['q'].".png");
if(isset($_GET['report'])){
	$graph->Stroke(_ADP_TMP_UPLOAD_PATH.$_GET['q'].".png");
}else{
	$graph->Stroke();
}
//$graph->Stroke(_ADP_TMP_UPLOAD_PATH.$_GET['q'].".png");
?>
