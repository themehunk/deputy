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
     echo "---------------------------";

     $arraytotaltime = array();
     foreach(array_unique($uid) as $value){

        foreach(array_unique($area_name) as $varea){
          if($varea=='') continue;
          $ttime = array();
       $sqlgroup = "SELECT (ts_total_time) FROM timesheet WHERE unid = '$value' AND area_name = '$varea'  AND ts_access_level NOT IN ('WEP','Apprentice')";
       $resultg = mysqli_query($con, $sqlgroup);  

      while($row = mysqli_fetch_assoc($resultg)) {

        $ttime[] = $row['ts_total_time'];
      }
      $arraytotaltime[$value][$varea] = array_sum($ttime);

        }
     }
     print_r($arraytotaltime);

     // restauranr tip
     $total_tip = 1030;
     $rtip = array();
     $total_rastorant_hour = array();
     $total_kitchen_hour = array();

     foreach($arraytotaltime as $data=>$tvalue){
        foreach($tvalue as $rkey => $totaltieme){
          
          if($totaltieme>0 && $rkey == "Restaurant Floor"){

            $total_rastorant_hour[]= ($totaltieme);

          }

        }

     // print_r($data);


     }
     // totala restaurent tip
    $totoalrast_sum = array_sum($total_rastorant_hour);
     echo $tip = round($total_tip*2/3,2)/$totoalrast_sum;
    // NOT IN ('WEP','Apprentice')

      $sql = "SELECT DISTINCT unid FROM timesheet WHERE area_name ='Kitchen' AND ts_access_level NOT IN ('WEP','Apprentice')";
     $kitchen_result = mysqli_query($con, $sql);  
     $total_dk_emp = array();
     while($row = mysqli_fetch_assoc($kitchen_result)) {
      $total_dk_emp[] = $row['unid'];
     }
echo "<br>";
     print_r(count($total_dk_emp));
     echo "<br>";

     echo $tip_kitchen = round($total_tip*1/3,2)/count($total_dk_emp);
     // kitche tip 


} else {
     echo "you have no records";
}
}

get_all_records();