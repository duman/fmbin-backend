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
  var time = getParameterByName('time');
  if (time === null) { time = 24; }
  $.ajax({
    url : "../p/data.php?player_id=" + player_id + "&time=" + time,
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
            pointRadius: 6,
            pointHoverRadius: 6,
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

      var timeFormat = "MM/DD/YYYY HH:mm";

      LineGraph = new Chart(ctx, {
        type: 'line',
        data: chartdata,
        options: {
          scales: {
            xAxes: [{
              ticks: {
                display: false //this will remove only the label
              }
            }]
          },
          /*pan: {
            // Boolean to enable panning
            enabled: true,

            // Panning directions. Remove the appropriate direction to disable 
            // Eg. 'y' would only allow panning in the y direction
            mode: 'x',
            rangeMin: {
              // Format of min pan range depends on scale type
              x: 1
            },
            rangeMax: {
              // Format of max pan range depends on scale type
              x: 10
            },
            // Function called once panning is completed
            // Useful for dynamic data loading
            onPan: function() { console.log('I was panned!!!'); }
          },*/
          layout: {
            padding: {
                right: 20,
            }
          },
          responsive: true,
          legend: false,
          legendCallback: function(chart) {
              var legendHtml = [];
              legendHtml.push('<ul>');
              legendHtml.push('<li>');
              legendHtml.push('<span class="chart-legend" style="background-color: #3b5998"></span>');
              legendHtml.push('<span class="chart-legend-label-text"><p class="m1-txt1" style="font-size: 14px; text-align: center;">Data is shown for the last <b>' + time + ' hours</b></p></span>');
              legendHtml.push('</li>');
              legendHtml.push('</ul>');
              return legendHtml.join("");
          }
        }
      });
      $('#my-legend-con').html(LineGraph.generateLegend());
    },
    error : function(data) {
    }
  });
});