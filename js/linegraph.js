function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

$(document).ready(function(){
  var player_id = getParameterByName('player_id');
  $.ajax({
    url : "../data.php?player_id=" + player_id,
    type : "GET",
    success : function(data){
      last_report = [];
      price_value = [];

      for(var i in data) {
        last_report.push(data[i].last_report);
        price_value.push(data[i].price_value);
      }

      chartdata = {
        labels: last_report,
        datasets: [
          {
            label: "Price",
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

      ctx = $("#mycanvas");

      LineGraph = new Chart(ctx, {
        type: 'line',
        data: chartdata
      });
    },
    error : function(data) {
    }
  });
});