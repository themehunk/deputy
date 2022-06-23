<style>
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}
</style>
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


// Time each employee spent in each Area Name (e.g Freya, how many hours did she spend in each Restaurant Floor, Lower Ground Cafe etc)
function time_each_emp_area($uid,$area_name){
  $con = getdb();
 // print_r($area_name);

  $arraytotaltime = array();
  foreach(array_unique($uid) as $value=>$fullname){

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
          echo "<h3>(a)Time each employee spent in each Area Name (e.g Freya, how many hours did she spend in each Restaurant Floor, Lower Ground Cafe etc)
          </h3>";
          $table = "<table id='customers'><thead>";
          $table .="<tr>
                <th> Area Name </th>";

                foreach($area_name as $arname){
                  $table .="<th> {$arname} </th>";
                }

                $table .="</tr></thead><tbody>";
          foreach( $arraytotaltime as $key=>$value ){

            $table .= "<tr>";
            $table .= "<td>{$uid[$key]}</td>";

            foreach($value as $area => $hour){
              $table .= "<td>{$hour}</td>";
            }
            $table .= "</tr>";
          }

        $table .= "</tbody></table>";

        echo $table;

  return $arraytotaltime;
}

// restaurent tip 
function restaurant_tip($arraytotaltime,$total_tip){
  $con = getdb();
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
  $tip = round(round($total_tip*2/3,2)/$totoalrast_sum,2);
  echo "<h3>(d) 1 Restaurant floor tip - (Total tip amount * 2/3-variable)/ (Total of all hours attributed to Restaurant Floor) = Tip per hour.</h3>";
  echo "<span>Restaurant floor tip  : {$tip} tip/hour</span>";

}

// Kitchen tip 
function kitchen_tip($total_tip){

  $con = getdb();

  $sql = "SELECT DISTINCT unid FROM timesheet WHERE area_name ='Kitchen' AND ts_access_level NOT IN ('WEP','Apprentice')";
  $kitchen_result = mysqli_query($con, $sql);  
  $total_dk_emp = array();
  while($row = mysqli_fetch_assoc($kitchen_result)) {
   $total_dk_emp[] = $row['unid'];
  }

 $distinct_emp = count($total_dk_emp);


   $tip_kitchen = round(round($total_tip*1/3,2)/$distinct_emp,2);

  echo "<h3>(d) 1 Kitchen tip - (Total tip amount * 1/3-variable)/Number of distinct people in the kitchen - do not include WEP, Apprentice (variable)</h3>";
  echo "<span> Kitchen tip  : {$tip_kitchen} tip/hour</span>";

}




// get all records
  function get_all_records(){
    $con = getdb();
    $Sql = "SELECT * FROM timesheet";
    $result = mysqli_query($con, $Sql);  

    $uid = array();
    $area_name = array();
    
    if (mysqli_num_rows($result) > 0) {
     while($row = mysqli_fetch_assoc($result)) {
            $name = $row['unid'];
            $areaname = $row['area_name'];
        $uid[$name]=$row['fnam'].' '. $row['lanme'];
        $area_name[] = $areaname;
     }
    

     $unique_emp = array_unique($uid);

     $area_name = array_filter(array_unique($area_name));

     $total_tip = 1030;
     $arraytotaltime = time_each_emp_area($unique_emp,$area_name);

     /*** 
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
  ******************************************/
     // restauranr tip
     restaurant_tip($arraytotaltime,$total_tip);
  /*** 
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

***/

     
    // NOT IN ('WEP','Apprentice')
    kitchen_tip($total_tip);
 /*****
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

     **/


} else {
     echo "you have no records";
}
}

get_all_records();