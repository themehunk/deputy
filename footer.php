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
  var $area_name = $('.'+$vclass).attr( 'area_name' ); // area name




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



$("#get_data").click(function(){
    $tipamount = $('#tipamount').val();
    $f_kvar = $('#f_kvar').val();
    $s_kvar = $('#s_kvar').val();
    $f_rvar = $('#f_rvar').val();
    $s_rvar = $('#s_rvar').val();
    $kitchen_dist = $('#kitchen_tip').attr('kitchen_dist');



    $tip_kitchen = $tipamount*($f_kvar/$s_kvar)/$kitchen_dist;
    $('#kitchen_tip b').text($tip_kitchen.toFixed(2));

    $tip_restaurant = $tipamount*($f_rvar/$s_rvar)/update_ariables["Restaurant Floor"];
    $('#restaurent_tip b').text($tip_restaurant.toFixed(2));

});





</script>