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

.hours_percent{
  width: 65%;
}

.dpwrap {
width:90%;
  display: flex;
  flex-wrap: wrap;
}

.dpwrap span {
  width: 15%;
}
.export_button {
    width: 100%;
    text-align: center;
    margin-top: 50px;
}
button#button {
    background: #0d54f1;
    border: none;
    padding: 10px 45px;
    font-size: 20px;
    color: #fff;
}
button#button:hover{
  background: #1d64ff;

}
hr.hrline {
    margin-top: 50px;
}
</style>
<?php
//include "form.php";
function getdb(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "exports";


    // $servername = "127.0.0.1";
    // $username = "uq1xcmwyqogdj";
    // $password = "hbzbtbiu6vso";
    // $db = "dbf7texqu3kunx";
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

        if($i>0){
            $name = $getData[1].$getData[2];
            $unid= strtolower($name);


            $newcost = str_replace('$', '', $getData[4]);


             $sql = "INSERT into timesheet (unid,fnam,lanme,area_name,ts_date,ts_cost,ts_total_time,approved,ts_access_level) 
                   values ('".$unid."','".$getData[1]."','".$getData[2]."','".$getData[3]."','".$getData[0]."','".$newcost."','".$getData[5]."','".$getData[6]."','".$getData[7]."')";
                 
                 
             //  $result = mysqli_query($con, $sql);

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
           
           fclose($file);  
     }
  }   


// Time each employee spent in each Area Name (e.g Freya, how many hours did she spend in each Restaurant Floor, Lower Ground Cafe etc)
function time_each_emp_area($uid,$area_name){
  $con = getdb();
  $arraytotaltime = array();
  $exl_data_arr = array();

  $total_hours = array();
  $i= 1;
  $j = 0;
  foreach(array_unique($uid) as $value=>$fullname){

     foreach($area_name as $varea){
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


          $xl_arr = array();
          $table = "<table id='customers'><thead>";
          $table .="<tr>
                <th> Area Name </th>";
                $xl_arr['Area Name']='';
                foreach($area_name as $arname){
                  $xl_arr[$arname] = '';
                  $table .="<th> {$arname} </th>";
                }
              
                $table .="</tr></thead><tbody>";
          foreach( $arraytotaltime as $key=>$value ){
            $table .= "<tr>";
            $xl_arr['Area Name'] = $uid[$key];
            $table .= "<td>{$uid[$key]}</td>";
            foreach($value as $area => $hour){
              $class = "hv_".$i;
              $table .= "<td><div class='dpwrap'>";
              $table .="<span>{$hour}</span><span> &times;</span>";

              $table .="<span><input class='hours_percent' id='".$class."' value='100'/></span><span>%</span>";
              $table .="<span> =</span><span class='".$class."' ".$class."='".$hour."' area_name='".$area."' count='".$j."'>{$hour}</span>";

              $table .="</div></td>";

              $total_hours[$area][$i] = $hour;

              $xl_arr[$area] = array('hours'=>$hour,'percent'=>100,$i=>$hour,"final"=>$i); 
              $i++;

            }
            $j++;
            $exl_data_arr[] = $xl_arr;
            $table .= "</tr>";
          }
          
          // total hours calulate

          $jencode = json_encode($total_hours);
          $xlsencode = json_encode($exl_data_arr);

          $table .= "<tr><td><b class='total_hours_count' total_hours='".$jencode."' xls_data = '".$xlsencode."'>Total Hours</b> </td>";


          foreach($total_hours as $area_key => $total_h){
            $table .= "<td class='".str_replace(' ', '',$area_key)."'  ><b>". array_sum($total_h)."</b></td>";

          }

          $table .= "</tr>";
          // end

        $table .= "</tbody></table>";

        echo $table;

  return $arraytotaltime;
}



function team_hours(){
  $con = getdb();
$sqlgroup = "SELECT area_name, SUM(ts_total_time) as ttime FROM timesheet  WHERE ts_access_level NOT IN ('WEP','Apprentice') GROUP BY area_name";
$resultg = mysqli_query($con, $sqlgroup);  
$table = "<table id='customers'><thead>";
$table .= "<tr><th>Team Name</th><th> Total Time</th></tr></thead>";
while($row = mysqli_fetch_assoc($resultg)) {
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
  $tip = $total_tip*$variable/$totoalrast_sum;
  $tip = round($tip,2);

  echo "<h3>(d) 1 Restaurant floor tip - (Total tip amount * 2/3-variable)/ (Total of all hours attributed to Restaurant Floor) = Tip per hour.</h3>";
  echo "<span>Restaurant floor tip  : {$tip} tip/hour</span>";

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

  echo "<h3>(d) 1 Kitchen tip - (Total tip amount * 1/3-variable)/Number of distinct people in the kitchen - do not include WEP, Apprentice (variable)</h3>";
  echo "<span> Kitchen tip  : {$tip_kitchen} tip/hour</span>";

}


// get all records
  function get_all_records(){
//print_r($_POST);
     $total_tip = 1030;
     $rvar = 2/3;
     $kvar = 1/3;

if(isset($_POST['get_data'])){
 $total_tip = (isset($_POST['tipamount']) && $_POST['tipamount'] != '')?$_POST['tipamount']:$total_tip;
 $rvar= (isset($_POST['rvar']) && $_POST['rvar'] != '')?$_POST['rvar']:$rvar;
echo $kvar= (isset($_POST['kvar']) && $_POST['kvar'] != '')?$_POST['kvar']:$kvar;

}


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

     $arraytotaltime = time_each_emp_area($unique_emp,$area_name);
     ?>
<div class="export_button">
<button id="button" value="xls">Export</button>

<a href="javascript:void(0)" id="dlbtn" style="display:none;">
    <button type="button" id="mine">Export</button>
</a>
</div>
<?php
include_once "form.php";


	  //team_hours();

     // restauranr tip

     restaurant_tip($arraytotaltime,$total_tip,$rvar);
 
    // NOT IN ('WEP','Apprentice')
    kitchen_tip($total_tip,$kvar);


} else {
     echo "you have no records";
}
}

get_all_records();


?>
<hr class="hrline">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
     $total_hours_json = JSON.parse($('.total_hours_count').attr( 'total_hours' ));
     $xls_data_json = JSON.parse($('.total_hours_count').attr( 'xls_data' ));

     var update_ariables  = {};
   $.each($total_hours_json, function (index, value) {
    update_ariables[index] = 0;
         $.each(value, function (index1, value1) {
          update_ariables[index] += value1;

        });

  });

  //export_data($xls_data_json,update_ariables);

  function export_data($xls_data_json,update_ariables){
    $("#button").click(function(){

        $.ajax({
            type: "POST",
            url: 'xls.php',
            data: {"xls":$xls_data_json,'total':update_ariables},
            success: function(response)
            {


              var file = new Blob([response], {type: 'text/xls'});
				    dlbtn.href = URL.createObjectURL(file);
				     dlbtn.download = 'myfile.xls';
          $( "#mine").click();
                  //console.log(response);
           }
       });
      });

  }

jQuery(".hours_percent").on("input", function() {
   $vclass = $(this).attr('id');
   $percent = $(this).val();   // how many percent
   $total_hours = parseFloat($('.'+$vclass).attr( $vclass ));
  $after_perc_hours = $total_hours * $percent / 100;   // after percent change data get
   $('.'+$vclass).text($after_perc_hours);
   $area_name = $('.'+$vclass).attr( 'area_name' ); // area name




   $count = $('.'+$vclass).attr( 'count' );  // index xl array

  
   $jshon_index = $vclass.replace(/[^\d.-]/g, '');
  // only fron data change calculation
   $total_hours_json[$area_name][$jshon_index] = $after_perc_hours;
   //var update_ariables  = {};
   $.each($total_hours_json, function (index, value) {
    update_ariables[index] = 0;
         $.each(value, function (index1, value1) {
          update_ariables[index] += value1;

        });

  });
  $('.'+$area_name.replace(/\s/g, '')+'  b').text(update_ariables[$area_name]);




  // Xls json data update
   $xls_data_json[$count][$area_name][$jshon_index] = $after_perc_hours;
   $xls_data_json[$count][$area_name]['percent'] = $percent;


if($area_name === "Restaurant Floor"){
  var res_tip = (1030*2/3)/update_ariables[$area_name];
  console.log(res_tip);

}else if($area_name === "Kitchen"){
  var kitchen_tip = (1030*1/3)/5;
  console.log(kitchen_tip);

}


});
export_data($xls_data_json,update_ariables);




</script>