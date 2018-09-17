function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

function removeData(chart) {
    chart.data.labels.pop();
    chart.data.datasets.forEach((dataset) => {
        dataset.data.pop();
    });
    chart.update();
}

function removeElement(id) {
    var elem = document.getElementById(id);
    return elem.parentNode.removeChild(elem);
}

var maxid = [];
firstmaxid = 0;
function doWork() {
    $.ajax({
      url : "../maxid.php",
      type : "GET",
      success : function(data){
        for(var i in data) {
          maxid.push(data[i].max_id);
        }
        if (maxid[0] > firstmaxid) {
            firstmaxid = maxid[0];
            maxid = [];
            var player_id = getParameterByName('player_id');
            var time = getParameterByName('time');
            if (time === null) { time = 6; }
            $.ajax({
            url : "../data.php?player_id=" + player_id + "&time=" + time,
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

              removeData(LineGraph);
              LineGraph.destroy();
              delete LineGraph;
              removeElement('mycanvas');
              $('#chart-container').append('<canvas id="mycanvas"></canvas>');
              removeElement('card-info');
              var cname = [];
              var pos = [];
              var ovr = [];
              var max_value = [];
              var avg_value = [];
              var min_value = [];

              $.ajax({
                url : "../informationdata.php?player_id=" + player_id + "&time=" + time,
                type : "GET",
                success : function(data){
                  for(var i in data) {
                    cname.push(data[i].cname);
                    pos.push(data[i].pos);
                    ovr.push(data[i].ovr);
                    max_value.push(data[i].max_value);
                    avg_value.push(data[i].avg_value);
                    min_value.push(data[i].min_value);
                  }

                  $('#chart-container').before('<div id="card-info"><p class="m1-txt1 p-b-36" style="padding-top: 1em; text-align: center;"><b>' + cname[0] + '</b><br><b>Position:</b> ' + pos[0] + ' - <b>Overall:</b> ' + ovr[0] + '<br><br><b>Highest Price:</b> ' + number_format(max_value[0], 0) + '<br><b>Average Price:</b> ' + number_format(avg_value[0], 0) + '<br><b>Lowest Price:</b> ' + number_format(min_value[0], 0) + '</p></div>');
                },
                error : function(data) {
                }
              });
              
              ctx = $("#mycanvas");

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
                  responsive: true,
                  legend: false,
                  legendCallback: function(chart) {
                      var legendHtml = [];
                      legendHtml.push('<ul>');
                      legendHtml.push('<li>');
                      legendHtml.push('<span class="chart-legend" style="background-color: #3b5998"></span>');
                      legendHtml.push('<span class="chart-legend-label-text"><p class="m1-txt1" style="font-size: 14px; text-align: center;">Data is shown for the last ' + time + ' hours</p></span>');
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
        }
        else {
          maxid = [];
        }
      },
      error : function(data) {
      }
    });
    repeater = setTimeout(doWork, 5000);
}

function number_format(number, decimals, dec_point, thousands_sep) {
    var n = !isFinite(+number) ? 0 : +number, 
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        toFixedFix = function (n, prec) {
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            var k = Math.pow(10, prec);
            return Math.round(n * k) / k;
        },
        s = (prec ? toFixedFix(n, prec) : Math.round(n)).toString().split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

var cname = [];
var pos = [];
var ovr = [];
var max_value = [];
var avg_value = [];
var min_value = [];
$(document).ready(function(){
  var player_id = getParameterByName('player_id');
  var time = getParameterByName('time');
  if (time === null) { time = 6; }
  $.ajax({
    url : "../informationdata.php?player_id=" + player_id + "&time=" + time,
    type : "GET",
    success : function(data){
      for(var i in data) {
        cname.push(data[i].cname);
        pos.push(data[i].pos);
        ovr.push(data[i].ovr);
        max_value.push(data[i].max_value);
        avg_value.push(data[i].avg_value);
        min_value.push(data[i].min_value);
      }

      $('#chart-container').before('<div id="card-info"><p class="m1-txt1 p-b-36" style="padding-top: 1em; text-align: center;"><b>' + cname[0] + '</b><br><b>Position:</b> ' + pos[0] + ' - <b>Overall:</b> ' + ovr[0] + '<br><br><b>Highest Price:</b> ' + number_format(max_value[0], 0) + '<br><b>Average Price:</b> ' + number_format(avg_value[0], 0) + '<br><b>Lowest Price:</b> ' + number_format(min_value[0], 0) + '</p></div>');
    },
    error : function(data) {
    }
  });
});