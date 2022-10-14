<?php
include_once "db.php";
include_once "header.php";
//include "form.php";

  
  function access_level_name(){
    $con = getdb();

  }


  function select_all_filter(){
    $mysqli = getdb();
    $sql = "SELECT * FROM options";
    $resultg = $mysqli->query($sql);  
    $array = array();
    while($row = $resultg->fetch_assoc()) {
      $empname = $row['pkey'];
      $areaname = $row['pname'];
      $array[$empname][$areaname] = $row['percent'];
    }

    return $array;
  }

  function space_remove($string){
    $string = preg_replace('/\s+/', '', $string);

    return $string;
  }

// Time each employee spent in each Area Name (e.g Freya, how many hours did she spend in each Restaurant Floor, Lower Ground Cafe etc)
function time_each_emp_area($uid,$area_name,$access_level_name,$access_level_ename,$get_date){
  $mysqli = getdb();
  $arraytotaltime = array();
  $exl_data_arr = array();

  $total_hours = array();
  $i= 1;
  $j = 0;
  $unique_emp = $uid;
   filter_data(); // display all filter data only echo

  $percent_filter = select_all_filter();
  foreach(array_unique($uid) as $value=>$fullname){

     foreach($area_name as $varea){
       if($varea=='') continue;
       $ttime = array();
    $sqlgroup = "SELECT (ts_total_time) FROM timesheet WHERE unid = '$value' AND area_name = '$varea'  AND approved = true";
    $resultg = $mysqli->query($sqlgroup);  

   while($row = $resultg->fetch_assoc()) {

     $ttime[] = $row['ts_total_time'];
   }
   $arraytotaltime[$value][$varea] = array_sum($ttime);

     }
  }
  select_all_filter();
  $dates = get_date($get_date);



          $xl_arr = array();
          $emp_tip_amount = array();
          $table = "<table id='customers'><thead>";
          $table .="<tr>
                 <th>Name </th>
                <th>Access Level </th>";
                $xl_arr['Area Name']='';
                foreach($area_name as $arname){
                  $xl_arr[$arname] = '';
                  $table .="<th> {$arname} </th>";
                }
                 $table .= "<th>Tip Distribution($)</th><th>Bonus Distribution($)</th>"; // Tip and bonus column
                $table .="</tr></thead><tbody>";



          foreach( $arraytotaltime as $key=>$value ){
            $table .= "<tr>";
            $xl_arr['Area Name'] = $uid[$key];
            $table .= "<td class='emp-td-name'>{$uid[$key]}</td>";
            $table .="<td class='emp-td-name'>".$access_level_ename[$key]."</td>";

            foreach($value as $area => $hour){
              $greencls = $hour>0?'green':'';
              $area_emp = space_remove($area.$uid[$key]);
              $class = "hv_".$i;
              $table .= "<td class='chnage_percent {$greencls} {$area_emp}'><div class='dpwrap'>";
              $table .="<span style='width: 25%;'>{$hour}</span><span style='width: 8%;'>&times;</span>";
              

            // employ wise percent
            if(isset($percent_filter[$key][$area])){
              $percentF = $percent_filter[$key][$area];
            }elseif(isset($percent_filter[$area]['area'])){
              // area wise percent
               $percentF = $percent_filter[$area]['area'];
               
            }else{
              $percentF = 100;
            }
            $percent_hourF= round((($hour * $percentF) / 100),2); 
            
              $table .="<span style='width: 25%;'><input type='hidden' class='hours_percent' id='".$class."' value='".$percentF."'/><small class='small_percent'>".$percentF."</small></span><span style='width: 8%;     font-size: 12px;'>%</span>";
              $table .="<span style='width: 8%;     padding: 0 3px;'> =</span><span class='".$class."' ".$class."='".$hour."' area_name='".$area."' employee='".$key."' count='".$j."'>{$percent_hourF}</span>";

              $table .="</div></td>";

              $total_hours[$area][$i] = $percent_hourF;
              $emp_tip_amount[$key][$area] = $percent_hourF;

              $xl_arr[$area] = array('hours'=>$hour,'percent'=>100,$i=>$hour,"final"=>$i); 
              $i++;

            }
            $j++;
            $exl_data_arr[] = $xl_arr;

            $sum_emp_total_time = array_sum($emp_tip_amount[$key]);

            $table .= "<td class='sum_emp_total_time update_emp' data-bonustip='". $sum_emp_total_time."' >". $sum_emp_total_time."</td><td class='update_emp'>". $sum_emp_total_time."</td>";
            $table .= "</tr>";
          }
          
          // total hours calulate
          $tip_calulate_arear = array();
          $jencode = json_encode($total_hours);
          $xlsencode = json_encode($exl_data_arr);

          $table .= "<tr class='total_hours'><td></td><td class='emp-td-name'><b class='total_hours_count' total_hours='".$jencode."' xls_data = '".$xlsencode."'>Total</b> </td>";


          foreach($total_hours as $area_key => $total_h){
            $suma_area = array_sum($total_h);
            $table .= "<td class='".str_replace(' ', '',$area_key)."'  ><b>". ($suma_area)."</b></td>";
            $tip_calulate_arear[$area_key] = $suma_area;

          }
          $table .= "<td><b class='sum-tip'>0.00</b></td><td><b class='sum-bonus'>0.00</b></td>"; // tip and bonus amount sum calculate
          $table .= "</tr>";
          // end

   $table .= "</tbody></table>";
        include "filter.php";

  return $arraytotaltime;   
}   //time_each_emp_area() end



function team_hours(){
  $mysqli = getdb();
$sqlgroup = "SELECT area_name, SUM(ts_total_time) as ttime FROM timesheet  WHERE approved = true  GROUP BY area_name";
$resultg = $mysqli->query($sqlgroup);  
$table = "<table id='customers'><thead>";
$table .= "<tr><th>Team Name</th><th> Total Time</th></tr></thead>";
while($row = $resultg->fetch_assoc()) {
if($row['area_name'] !=''){
  $table .= "<tr><td>".$row['area_name']."</td><td>".$row['ttime']."</td></tr>";
  
}
}
$table .="</table>";

echo "<h3>(c)Sum of all hours for whole team in Area Name (e.g Total of all hours attributed to Restaurant Floor) - do not include WEP, Apprentice (variable)
      </h3>";
echo $table;
}

// restaurent tip 
function restaurant_tip($arraytotaltime,$total_tip,$variable){
  $mysqli = getdb();
  $rtip = array();
  $total_rastorant_hour = array();
  $total_kitchen_hour = array();

  foreach($arraytotaltime as $data=>$tvalue){
     foreach($tvalue as $rkey => $totaltieme){
       
       if($totaltieme>0 && $rkey == "Restaurant Floor"){

         $total_rastorant_hour[]= ($totaltieme);

       }

     }

  }



  // totala restaurent tip
 $totoalrast_sum = array_sum($total_rastorant_hour);
  $tip = $total_tip*$variable/$totoalrast_sum;
  $tip = round($tip,2);

  echo "<h3>Restaurant floor tip  : <span id='restaurent_tip'><b>{$tip}</b></span> tip/hour</h3>";

}

// Kitchen tip 
function kitchen_tip($total_tip,$variable){
  $con = getdb();

  $sql = "SELECT DISTINCT unid FROM timesheet WHERE area_name ='Kitchen' AND ts_access_level NOT IN ('WEP','Apprentice')";
  $kitchen_result = mysqli_query($con, $sql);  
  $total_dk_emp = array();
  while($row = mysqli_fetch_assoc($kitchen_result)) {
   $total_dk_emp[] = $row['unid'];
  }

 $distinct_emp = count($total_dk_emp);

   $tip_kitchen = $total_tip*$variable/$distinct_emp;
   $tip_kitchen = round($tip_kitchen,2);

  echo " <h3> Kitchen Tip   : <span id='kitchen_tip' kitchen_dist = '".$distinct_emp."'> <b>{$tip_kitchen}</b></span> tip/hour</h3>";
}

// select employee data
function select_filter_emp(){
  $mysqli = getdb();
  $sql = "SELECT pkey,pname FROM options WHERE pkey != 'notes_amount'";
  $result = $mysqli->query($sql);

  $select_emp = array();
  while($row =$result->fetch_assoc()) {
    $string =  $row['pkey'].$row['pname'];
   $fstring = str_replace(' ', '', $string);

    $select_emp[$fstring]=$fstring;
  }
  
  return $select_emp;
}

  // update and insert employee data
  function insert_filter_employee($employee,$weight){
    $mysqli = getdb();
    $data = '';
    $employee_diff = select_filter_emp();


    $insert_arr=array_diff($employee,$employee_diff);

    $update_arr=array_diff($employee,$insert_arr);

    if(!empty($insert_arr)){

      foreach($insert_arr as $value){
        $data .= "('$value', 'employee',$weight),";
      }
      $data = preg_replace("/,$/", '', $data);
      $sql = "INSERT INTO options (pkey, pname, percent) VALUES $data";
      $result = $mysqli->query($sql);
    }
    if(!empty($update_arr)){

      foreach( $update_arr as $uvalue){

        $sql = "UPDATE options SET percent='$weight' WHERE pkey='$uvalue' AND pname ='employee'";
        $result = $mysqli->query($sql);
    }

    }
  }
//***************************************************************************** */


 // update and insert employee data
 function insert_access_level_employee($access_level_emp,$weight){
  $mysqli = getdb();
  $emp_area = select_filter_emp();

  foreach($access_level_emp as $key=>$data){
  $string =  $data['unid'].$data['area_name'];
  $fstring = str_replace(' ', '', $string);

  $pkey  = $data['unid'];
  $pname = $data['area_name'];

  if (array_key_exists($fstring,$emp_area))
  {
  $sql = "UPDATE options SET percent='$weight' WHERE pkey='$pkey' AND pname ='$pname'";
  $result = $mysqli->query($sql);

  }
else
  {
  $sql = "INSERT INTO options (pkey, pname, percent) VALUES ('$pkey','$pname',$weight)";
  $result = $mysqli->query($sql);
  }
  }
}
//***************************************************************************** */

// select employee data
function select_filter_area(){
  $mysqli = getdb();
  $sql = "SELECT * FROM options WHERE pname = 'area'";


  $result = $mysqli->query($sql);

  $select_emp = array();
  while($row =$result->fetch_assoc()) {
    $select_emp[] = $row['pkey'];
  }
  
  $mysqli->close();
    return $select_emp;
}

  // update and insert employee data
  function insert_filter_area($area,$weight){
    $mysqli = getdb();
    $data = '';
    $area_diff = select_filter_area();

    $insert_arr=array_diff($area,$area_diff);

    $update_arr=array_diff($area,$insert_arr);

    if(!empty($insert_arr)){

      foreach($insert_arr as $value){
        $data .= "('$value', 'area',$weight),";
      }
      $data = preg_replace("/,$/", '', $data);
      $sql = "INSERT INTO options (pkey, pname, percent) VALUES $data";
      $result = $mysqli->query($sql);
    }
    if(!empty($update_arr)){

      foreach( $update_arr as $uvalue){

        $sql = "UPDATE options SET percent='$weight' WHERE pkey='$uvalue' AND pname ='area'";
        $result = $mysqli->query($sql);

    }

    }
  }

  //***************************************************************************** */
// filter - area and employee data
function filter_data(){
  if((isset($_POST['filter']) || isset($_POST['Weight_filter'])) && (isset($_POST['area']) || isset($_POST['employee'])) ){
    $mysqli = getdb();
    $final_level = true;
    $final_area = "";
    
    $weight = isset($_POST['weight']) && $_POST['weight']!=''?$_POST['weight']:0;

    if(isset($_POST['area']) ){
      $area = $_POST['area'];
      if(!isset($_POST['filter'])) {
     // insert_filter_area($area,$weight);

      }

      foreach($area as $singel){
          $final_area .="area_name = '".$singel."' OR ";
      }
     $final_area =  substr_replace($final_area ,"",-3);
    }

    if(isset($_POST['level']) ){
      $level = $_POST['level'];
    $levels = implode('\', \'', $level);
    $final_level = "'" . $levels . "'";
    }

    if(isset($_POST['employee']) && !empty($_POST['employee'])){

      $employee = $_POST['employee'];
      if(!isset($_POST['filter'])) {
        insert_filter_employee($employee,$weight);
      }
     
      $employes = implode('\', \'', $employee);
      $final_emp = "'" . $employes . "'";

      $filter_query = "SELECT sum(ts_total_time) as ts_total_time,area_name,fnam,lanme FROM timesheet  WHERE
      unid IN ($final_emp)  AND approved = TRUE GROUP BY unid ORDER BY area_name DESC";

      $group = 'emp';

    }else{
      $filter_query = "SELECT  unid,ts_total_time,area_name,fnam,lanme FROM timesheet  WHERE ($final_area) AND 
      ts_access_level IN ($final_level)  AND approved = TRUE ORDER BY area_name DESC";
       $group = 'area';
    }

        $filter_result = $mysqli->query($filter_query); 
        $mysqli->close();
      return filter_data_table($filter_result,$weight,$group);
    }

}

function filter_data_table($filter_result,$weight,$group){

  $table = "<table id='customers'><thead>";
               //   $table .= ($group=='area')?"<th> Area Time </th>":"<tr> <th>  Name </th>";
      $table .="<tr> <th>  Name </th>";
      $table .="<th> Area Time </th>";
      $table .="<th> Total Time </th>";
      $table .="</tr></thead>";
   $total_time = array();
   $access_level_emp = array();
  while($row = $filter_result ->fetch_assoc()) {
    $area_name = $row['area_name'];
    $access_level_emp[]= $row;

    $hour   = $row['ts_total_time'];
    $total_hours=(($hour*$weight)/100); 
    $total_time[] =$total_hours;

     $table .= "<tr>";
     $table .=  "<td class='emp-td-name'>".$row['fnam'].' '. $row['lanme']."</td>";
      $table .= ($group=='area')?"<td class='emp-td-name'>".$row['area_name']."</td>":"<td class='emp-td-name'>".$row['fnam'].' '. $row['lanme']."</td>";
       $table .= "<td class='td_time'> <div class='dpwrap1'>";
       $table .="<span>".round($hour,2)."</span><span> &times; {$weight}%</span>";
       $table .="<span style='padding: 5px;'> =</span><span><b>".round($total_hours,2)."</b></span>";
       $table .="</div></td>";  
     //  $table .=  "<td>".$row['area_name']."</td>";  
     $table .= "</tr>";
   
 
  }
//  $table .="<tr><td>Total Hours</td><td><b>".array_sum($total_time)."</b></td></tr>";
  $table .="</table>";


  if(isset($_POST['Weight_filter'])){
    insert_access_level_employee($access_level_emp,$weight);
  }

  return $table;
}


function get_date($get_date){
  $dates=array();
  array_multisort(array_map('strtotime', $get_date), $get_date);
  $dates['start'] =  date("d M Y", strtotime($get_date[0]));
  $dates['last'] = date("d M Y", strtotime($get_date[array_key_last($get_date)]));
  return $dates;
}


// get all records
  function get_all_records(){
     $total_tip = 1030;
     $rvar = 2/3;
     $kvar = 1/3;

    if(isset($_POST['get_data'])){
    $total_tip = (isset($_POST['tipamount']) && $_POST['tipamount'] != '')?$_POST['tipamount']:$total_tip;
    $rvar= (isset($_POST['rvar']) && $_POST['rvar'] != '')?$_POST['rvar']:$rvar;
    echo $kvar= (isset($_POST['kvar']) && $_POST['kvar'] != '')?$_POST['kvar']:$kvar;

    }


    $mysqli = getdb();

    $Sql = "SELECT * FROM timesheet WHERE approved = true";
    $result = $mysqli->query($Sql);  
    $uid = array();
    $area_name = array();
    $access_level_name = array();
    $get_date =array();
    $access_level_ename =array();
    
    if ($result->num_rows > 0) {
     while($row = $result -> fetch_assoc()) {

            $name = $row['unid'];
            $areaname = $row['area_name'];
        $uid[$name]=$row['fnam'].' '. $row['lanme'];
        $area_name[] = $areaname;
        $access_level_name[]=$row['ts_access_level'];
        $get_date[] = $row['ts_date'];
        $access_level_ename[$name]=$row['ts_access_level'];
     }
    

     $unique_emp = array_unique($uid);
     $area_name = array_filter(array_unique($area_name));
     $access_level_name = array_unique($access_level_name);

     $arraytotaltime = time_each_emp_area($unique_emp,$area_name,$access_level_name,$access_level_ename, $get_date);
     ?>
<!-- <div class="export_button">
<button id="button" value="xls">Export</button>

<a href="javascript:void(0)" id="dlbtn" style="display:none;">
    <button type="button" id="mine">Export</button>
</a> -->
</div>
<?php
//include_once "form.php";




   //  restaurant_tip($arraytotaltime,$total_tip,$rvar);
 
    // NOT IN ('WEP','Apprentice')
    //kitchen_tip($total_tip,$kvar);


} else {
     echo "you have no records";
}
}

get_all_records();


function update_calculate_data(){
  $mysqli = getdb();
  $Sql = "SELECT * FROM options  WHERE pkey = 'notes_amount'";

      if(isset($_POST['calculate'])){
        $result = $mysqli->query($Sql); 

        $calculate = isset($_POST)?$_POST:array();
        $jshon_data = serialize($calculate);

      if ($result->num_rows != true) {
        $sql = "INSERT INTO options (pkey, pname, percent) VALUES ('notes_amount', '$jshon_data',1)";
       $insert = $mysqli->query($sql);
       echo "<b>Insert successfully.</b>";
      }else{
        $sql = "UPDATE options SET pname='$jshon_data' WHERE pkey ='notes_amount'";
        $update = $mysqli->query($sql);
        echo "<b>Updated successfully.</b>";
      }

    }
    $result = $mysqli->query($Sql); 
    $row = $result->fetch_assoc();
   return $row;
}



function tip_caluclation($tip_calulate_arear){
  $return = array();
  $return['tip_amount'] = '';
  $return['bonus_amount'] = '';
  $return['sheet_notes'] = '';
  $return['per_hour_tip'] =0;
  $return['per_hour_bonus'] = 0;
  $sum_tip_amount = array_sum($tip_calulate_arear);
  $calculate_data  = update_calculate_data();

  if($calculate_data){
   $jshon_decode = unserialize($calculate_data['pname']);
   $return['tip_amount'] =  $jshon_decode['tip_amount'];
   $return['bonus_amount'] = $jshon_decode['bonus_amount'];
   $return['sheet_notes'] = $jshon_decode['sheet_notes'];

   $return['per_hour_tip'] = ($return['tip_amount']!='')?round($return['tip_amount']/$sum_tip_amount,2):0;
   $return['per_hour_bonus'] = ($return['bonus_amount']!='')?round($return['bonus_amount']/$sum_tip_amount,2):0;
   }

   return $return;

}




include_once "footer.php";
