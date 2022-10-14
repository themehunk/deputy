<?php
include_once "db.php";
function upload_check(){
   $mysqli = getdb();
   $sql = "SELECT * FROM timesheet";
   $result = $mysqli->query($sql); 
   if($result->num_rows){
      return true;
   }
   return false;
}
function reset_sheet(){
if(isset($_POST["Reset"])){
   $mysqli = getdb();
   $sql = "TRUNCATE TABLE timesheet";
   $result = $mysqli->query($sql); 
}
}
reset_sheet();


if(isset($_POST["Import"])){
    
     if($_FILES["file"]["size"] > 0)
     {
        $filename=$_FILES["file"]["tmp_name"];    

        $file = fopen($filename, "r");
        $table = array();
        $tableHead = array(); 
        $hours=array();
        $i= 0;

        $mysqli = getdb();

       $sheetid =  $mysqli->query("SELECT MAX(sheet) AS SheetID FROM timesheet");
       $row= $sheetid->fetch_assoc();
       
       $sheet = 1;
       if($row['SheetID']!=''){
        $sheet=1+$row['SheetID'];
       }

          while (($getData = fgetcsv($file, 10000, ",")) !== FALSE){
           if($i>0){
            $name = $getData[1].$getData[2];
            $unid= strtolower($name);
            $newcost = str_replace('$', '', $getData[4]);
           $valid_emp = (strval($getData[6]) == 'true' || strval($getData[6]) == 'TRUE')?1:0;

             $sql = "INSERT into timesheet (unid,sheet,fnam,lanme,area_name,ts_date,ts_cost,ts_total_time,approved,ts_access_level) 
                   values ('".$unid."','$sheet','".$getData[1]."','".$getData[2]."','".$getData[3]."','".$getData[0]."','".$newcost."','".$getData[5]."','".$valid_emp."','".$getData[7]."')";

            $result = $mysqli->query($sql);

				}
				$i++;

        // if(!isset($result))
        // {
        //   echo "<script type=\"text/javascript\">
        //       alert(\"Invalid File:Please Upload CSV File.\");
        //       window.location = \"index.php\"
        //       </script>";    
        // }
        // else {
        //     echo "<script type=\"text/javascript\">
        //     alert(\"CSV File has been successfully Imported.\");
        //     window.location = \"index.php\"
        //   </script>";
        // }
           }
         if(fclose($file)){
            header('Location: export.php');
         }  
           
     }
  } 
  
  
