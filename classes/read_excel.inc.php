<?php


function read_excel($path, $prog_line){
	require_once 'Excel/reader.php';
	chk_file_exist($path, $prog_line);
	$SUBTMP=explode(".", $path);
	$i=count($SUBTMP);
	$SubFile=$SUBTMP[$i-1];
	if($SubFile!='xls'){
		die ('<br><br>'.__LINE__.'錯誤！不是xls檔！');
	}
	$excel_data=new Spreadsheet_Excel_Reader();
	$excel_data->setOutputEncoding('utf-8');
	$excel_data->read($path);
	for ($i = 2; $i <= $excel_data->sheets[0]['numRows']; $i++) {
		for ($j = 2; $j <= $excel_data->sheets[0]['numCols']; $j++) {
			if($excel_data->sheets[0]['cells'][$i][$j]!=NULL){
				$data[$i-2][$j-2]=$excel_data->sheets[0]['cells'][$i][$j];
			}
		}
	}
	return $data;
}

//read_excel_2j 是較新的版本
function read_excel_2j($path, $prog_line){
	require_once 'Excel/reader_2j.php';
	chk_file_exist($path, $prog_line);
	$SUBTMP=explode(".", $path);
	$i=count($SUBTMP);
	$SubFile=$SUBTMP[$i-1];
	if($SubFile!='xls'){
		die ('<br><br>'.__LINE__.'錯誤！不是xls檔！');
	}
	$excel_data=new Spreadsheet_Excel_Reader();
	$excel_data->setOutputEncoding('utf-8');
	$excel_data->read($path);
	for ($i = 2; $i <= $excel_data->sheets[0]['numRows']; $i++) {
		for ($j = 2; $j <= $excel_data->sheets[0]['numCols']; $j++) {
			if($excel_data->sheets[0]['cells'][$i][$j]!=NULL){
				$data[$i-2][$j-2]=$excel_data->sheets[0]['cells'][$i][$j];
			}
		}
	}
	return $data;
}


?>
