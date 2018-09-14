$(document).ready(function(){
  $.ajax({
    url : "../data.php",
    type : "GET",
    success : function(data){
      console.log(data);

      var player_id = [];
      var price_value = [];

      for(var i in data) {
        player_id.push("UserID " + data[i].player_id);
        price_value.push(data[i].price_value);
      }

      var chartdata = {
        labels: player_id,
        datasets: [
          {
            label: "value",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "rgba(59, 89, 152, 0.75)",
            borderColor: "rgba(59, 89, 152, 1)",
            pointHoverBackgroundColor: "rgba(59, 89, 152, 1)",
            pointHoverBorderColor: "rgba(59, 89, 152, 1)",
            data: price_value
          }
        ]
      };

      var ctx = $("#mycanvas");

      var LineGraph = new Chart(ctx, {
        type: 'line',
        data: chartdata
      });
    },
    error : function(data) {

    }
  });
});