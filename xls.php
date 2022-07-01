<?php


function filterData(&$str){ 


    // if(is_array($str)){
    //     array_walk($str, 'filterData'); 

    // }else{
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 

   // }
}

function cellsToMergeByColsRow($start = -1, $end = -1, $row = -1){
    $merge = 'A1:A1';
    if($start>=0 && $end>=0 && $row>=0){
        $start = PHPExcel_Cell::stringFromColumnIndex($start);
        $end = PHPExcel_Cell::stringFromColumnIndex($end);
        $merge = "$start{$row}:$end{$row}";
    }
    return $merge;
}


if(isset($_POST['xls'])){

// Excel file name for download 
$fileName = "codexworld_export_data-" . date('Ymd') . ".xls"; 
 
// Headers for download 
header("Content-Disposition: attachment; filename=\"$fileName\""); 
header("Content-Type: application/vnd.ms-excel"); 


$xls_data = $_POST['xls'];

//print_r($xls_data);



$flag = false; 

    foreach($xls_data as $row) { 
        if(!$flag) { 
            // display column names as first row 
            echo implode("\t\t\t", array_keys($row)) . "\n"; 

            $head =  "Persons Percent \t\t Hours \t Percent(%) \t Total Hours";
            $head .=  "\t Hours \t Percent \t Total Hours";
            $head .=  "\t Hours \t Percent \t Total Hours";
            $head .=  "\t Hours \t Percent \t Total Hours";
            echo $head."\n";
            $flag = true; 
        } 
        // filter data 
     

        $impl ='';

        foreach($row as $key=>$row2) { 

        if(is_array($row2)){
                $multi = $row2['final'];
            $impl .= "\t".$row2['hours']."\t".$row2['percent']."\t".$row2[$multi]; 

                //$impl .= implode("\t", array_values($row3)); 

        }else{
        $impl .= $row2."\t"; 
        }

    }


     echo $impl."\n";


    } 

// total 
$xls_total = $_POST['total'];

 $total = "Total \t\t\t\t";

 $total .=  implode("\t \t \t", array_values($xls_total)); 

 echo $total;
// foreach($xls_total as $key=>$value){
//     $total .= $value."\t";
//     echo  $total;
// }


}
exit;


return;
$data = array( 
  array("NAME" => "John Doe", "EMAIL" => "john.doe@gmail.com", "GENDER" => "Male", "COUNTRY" => "United States"), 
  array("NAME" => "Gary Riley", "EMAIL" => "gary@hotmail.com", "GENDER" => "Male", "COUNTRY" => "United Kingdom"), 
  array("NAME" => "Edward Siu", "EMAIL" => "siu.edward@gmail.com", "GENDER" => "Male", "COUNTRY" => "Switzerland"), 
  array("NAME" => "Betty Simons", "EMAIL" => "simons@example.com", "GENDER" => "Female", "COUNTRY" => "Australia"), 
  array("NAME" => "Frances Lieberman", "EMAIL" => "lieberman@gmail.com", "GENDER" => "Female", "COUNTRY" => "United Kingdom") 
);


// Excel file name for download 
$fileName = "codexworld_export_data-" . date('Ymd') . ".xls"; 
 
// Headers for download 
header("Content-Disposition: attachment; filename=\"$fileName\""); 
header("Content-Type: application/vnd.ms-excel"); 
// function filterData(&$str){ 
//     $str = preg_replace("/\t/", "\\t", $str); 
//     $str = preg_replace("/\r?\n/", "\\n", $str); 
//     if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
// }
$flag = false; 
foreach($data as $row) { 
    if(!$flag) { 
        // display column names as first row 
        echo implode("\t", array_keys($row)) . "\n"; 
        $flag = true; 
    } 
    // filter data 
    array_walk($row, 'filterData'); 
    echo implode("\t", array_values($row)) . "\n"; 
} 
 
exit;

