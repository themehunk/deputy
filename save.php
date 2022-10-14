<?php
include_once "db.php";
if(isset($_POST['percent'])){
    print_r($_POST);
        $area_name = isset($_POST['area_name'])?$_POST['area_name']:'';
        $employee = isset($_POST['employee'])?$_POST['employee']:'';
        $percent = isset($_POST['percent'])?$_POST['percent']:'';

        insert_employ_data($area_name,$employee,$percent);


}

function insert_employ_data($area_name,$employee,$percent){
    $mysqli = getdb();
   $sql = "SELECT * FROM options WHERE pkey = '$employee' AND  pname = '$area_name'";
   $result = $mysqli->query($sql);
   if($result->num_rows>0){
        $insert_update = "UPDATE options SET percent ='$percent' WHERE pkey='$employee' AND pname ='$area_name'";
   }else{
    $data = "('$employee','$area_name','$percent')";
    $insert_update = "INSERT INTO options (pkey, pname, percent) VALUES $data";
   }

   $result = $mysqli->query($insert_update);
}