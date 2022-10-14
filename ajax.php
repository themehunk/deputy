<?php

include_once "db.php";

function space_remove($string){
    $string = preg_replace('/\s+/', '', $string);

    return $string;
  }

aj_filter_data();
$area = isset($_POST['area'])?$_POST['area']:'';
$level = isset($_POST['level'])?$_POST['level']:'';
$weight = isset($_POST['weight'])?$_POST['weight']:'';

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

function filter_data_table($filter_result,$weight,$group){
    $area_emp_class = array();
    $table = "<table id='customers'><thead>";
                 //   $table .= ($group=='area')?"<th> Area Time </th>":"<tr> <th>  Name </th>";
        $table .="<tr> <th>  Name </th>";
        $table .="<th> Area Time </th>";
        $table .="<th> Total Time </th>";
        $table .="</tr></thead>";
     $total_time = array();
     $access_level_emp = array();
    while($row = $filter_result ->fetch_assoc()) {
        $fname = $row['fnam'];
        $lname = $row['lanme'];
        $area_name = $row['area_name'];
        $string = $area_name.$fname.$lname;
        $area_emp_class[] = space_remove($string);

      $access_level_emp[]= $row;
  
      $hour   = $row['ts_total_time'];
      $total_hours=(($hour*$weight)/100); 
      $total_time[] =$total_hours;
  
       $table .= "<tr>";
       $table .=  "<td class='emp-td-name'>".$fname.' '. $lname."</td>";
        $table .= ($group=='area')?"<td class='emp-td-name'>".$row['area_name']."</td>":"<td class='emp-td-name'>".$fname.' '. $lname."</td>";
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
    return $area_emp_class;
  }


  function aj_filter_data(){
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
    //   $filter_query = "SELECT sum(ts_total_time) as ts_total_time,area_name,fnam,lanme FROM timesheet  WHERE
    //   unid IN ($final_emp)  AND approved = TRUE GROUP BY unid ORDER BY area_name DESC";

      $filter_query = "SELECT  unid, ts_total_time,area_name,fnam,lanme FROM timesheet  WHERE ($final_area) AND 
      ts_access_level IN ($final_level)  AND approved = TRUE  ORDER BY area_name DESC";
       $group = 'area';

       $filter_result = $mysqli->query($filter_query); 
       $mysqli->close();
     $class_arr = filter_data_table($filter_result,$weight,$group);

   //  print_r($class_arr);
     print_r(json_encode($class_arr));

  }



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






