<?php

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
function filterData(&$str){ 
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
}
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

