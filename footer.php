<hr class="hrline">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
$("#level-select,#area-select").click(function(){
document.getElementById("employee-select").setAttribute("disabled", "disabled");

// button disabled remove
document.getElementById("filter").removeAttribute("disabled");
document.getElementById("weight_filter").removeAttribute("disabled");


});

$("#employee-select").click(function(){
document.getElementById("level-select").setAttribute("disabled", "disabled");
document.getElementById("area-select").setAttribute("disabled", "disabled");
// button disabled remove
document.getElementById("filter").removeAttribute("disabled");
document.getElementById("weight_filter").removeAttribute("disabled");

});

$("#clear_filter").click(function(){


  document.getElementById("level-select").removeAttribute("disabled");
  document.getElementById("employee-select").removeAttribute("disabled");
  document.getElementById("area-select").removeAttribute("disabled");
  document.getElementById("area-select").value = "";
  document.getElementById("level-select").value = "";
  document.getElementById("employee-select").value = "";
  $( "td" ).removeClass( "red" );
});

var tiparr=[];
var bonusarr=[];
var tip_amount = $('#tip_amount').data('tip');
var  bonus_amount = $('#bonus_amount').data('bonus');
$('.sum_emp_total_time').each(function(i,item){
  let tip_total = $(item).data('bonustip')*tip_amount;
  let bonus_total = $(item).data('bonustip')*bonus_amount;

  $(this).text(tip_total.toFixed(2));
  $(this).next().text(bonus_total.toFixed(2));
  tiparr.push(tip_total);
  bonusarr.push(bonus_total);
})

 const sum_tip = tiparr.reduce((partialSum, a) => partialSum + a, 0);
 const sum_bonus = bonusarr.reduce((partialSum, a) => partialSum + a, 0);
 $('.sum-tip').text(sum_tip.toFixed(0));
 $('.sum-bonus').text(sum_bonus.toFixed(0));


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

  function apiSave($area_name,$employee,$percent){

    var request = $.ajax({
  url: "save.php",
  method: "POST",
  data: { area_name : $area_name,employee:$employee,percent:$percent },
  dataType: "html"
});
 
request.done(function( response ) {
//console.table(response);

});
 
request.fail(function( jqXHR, textStatus ) {
  console.log( "Request failed: " + textStatus );
});

  }

  jQuery(".chnage_percent").click(function() {
    jQuery(this).find('.hours_percent').attr( 'type','text' );
    
    
  });

jQuery(".hours_percent").change(function() {
  jQuery('.hours_percent').attr( 'type','hidden' );

   $vclass = $(this).attr('id');
   $percent = $(this).val();   // how many percent
   $total_hours = parseFloat($('.'+$vclass).attr( $vclass ));
  $after_perc_hours = $total_hours * $percent / 100;   // after percent change data get

//get out of here
if ($total_hours<1){
  return;
}

jQuery(this).next().text($percent);


   $('.'+$vclass).text($after_perc_hours.toFixed(2));
  var $area_name = $('.'+$vclass).attr( 'area_name' ); // area name
  var $employee = $('.'+$vclass).attr( 'employee' );
  apiSave($area_name,$employee,$percent);

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
  $('.'+$area_name.replace(/\s/g, '')+'  b').text(update_ariables[$area_name].toFixed(2));




  // Xls json data update
   $xls_data_json[$count][$area_name][$jshon_index] = $after_perc_hours;
   $xls_data_json[$count][$area_name]['percent'] = $percent;


if($area_name === "Restaurant Floor"){
  var res_tip = (1030*2/3)/update_ariables[$area_name];

}else if($area_name === "Kitchen"){
  var kitchen_tip = (1030*1/3)/5;

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


$("#weight_filter").mouseover( async function(event){
  console.log(event);

  await $(".formfilter").attr('id', 'filterdata');
});

$("#filter").mouseover( async function(event){
  console.log(event);

  await $(".formfilter").attr('id', 'filteraj');
});


$("#filteraj").submit(function(event){
  if($("#filteraj").length){

  console.log(event.target);
  let areaSelect = $( "#filteraj" );
  event.preventDefault();

  $.ajax({
        type: "POST",
        url: "ajax.php",
        data: areaSelect.serialize(), // serializes the form's elements.
        success: function(data)
        {
          $( "td" ).removeClass( "red" );

       //   console.log(data);
        //    $("#testing-data").html(data);
        var $json =  JSON.parse(data);
         for (var i=0; i < $json.length; i++) {
          $("."+$json[i]).addClass("red");
          }

        }
    });

}
  });



</script>