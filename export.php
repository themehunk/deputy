<?php
function getdb(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "exports";
    try {
       
        $conn = mysqli_connect($servername, $username, $password, $db);
         //echo "Connected successfully"; 
        }
    catch(exception $e)
        {
        echo "Connection failed: " . $e->getMessage();
        }
        return $conn;
    }





if(isset($_POST["Import"])){
    
    $filename=$_FILES["file"]["tmp_name"];    
     if($_FILES["file"]["size"] > 0)
     {
        $file = fopen($filename, "r");
        $table = array();
        $tableHead = array(); 
        $hours=array();
        $i= 0;

        $con = getdb();

          while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
           {

            print_r($getData);
            $name = $getData[1].$getData[2];
            $unid= strtolower($name);


            $newcost = str_replace('$', '', $getData[4]);


             $sql = "INSERT into timesheet (unid,fnam,lanme,area_name,ts_date,ts_cost,ts_total_time,approved,ts_access_level) 
                   values ('".$unid."','".$getData[1]."','".$getData[2]."','".$getData[3]."','".$getData[0]."','".$newcost."','".$getData[5]."','".$getData[6]."','".$getData[7]."')";
                 
                 
             //    $result = mysqli_query($con, $sql);
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
           
           fclose($file);  
     }
  }   





  function get_all_records(){
    $con = getdb();
    $Sql = "SELECT * FROM timesheet";
    $result = mysqli_query($con, $Sql);  
//GROUP BY area_name

  //  print_r($result2);

    
    $uid = array();
    $area_name = array();
    
    if (mysqli_num_rows($result) > 0) {
     while($row = mysqli_fetch_assoc($result)) {
            $name = $row['unid'];
            $areaname = $row['area_name'];
        $uid[]=$name;
        $area_name[] = $areaname;
     }
    
     
     print_r(array_unique($uid));
     print_r(array_unique($area_name));

     foreach(array_unique($uid) as $value){

        foreach(array_unique($area_name) as $varea){
      echo $sqlgroup = "SELECT count(ts_total_time) FROM timesheet WHERE unid = ".$value." AND area_name = ".$varea.";
      while($row = mysqli_fetch_assoc($sqlgroup)) {
        print_r($row);
      }

        }
     }

    //  $sqlgroup = "SELECT DISTINCT unid FROM timesheet";
    //  $resultg = mysqli_query($con, $sqlgroup);  
 
    // $result2 =  mysqli_fetch_assoc($resultg);

} else {
     echo "you have no records";
}
}

get_all_records();