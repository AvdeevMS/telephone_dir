$(document).ready(function(){

  var city_id = $("#city option:selected").val();
  $("#street option[data!=" + city_id + "]").hide();
  $("#street [data=" + city_id + "]").attr("selected","selected");
  
});

function select_city(city){
  var city_id = city.value;  
  $("#street option[data!=" + city_id + "]").hide();
  $("#street option[data=" + city_id + "]").show();
  $("#street [data=" + city_id + "]").attr("selected","selected");
}