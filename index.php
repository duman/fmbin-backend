<?php

$DS = DIRECTORY_SEPARATOR;
file_exists(__DIR__ . $DS . 'core' . $DS . 'Handler.php') ? require_once __DIR__ . $DS . 'core' . $DS . 'Handler.php' : die('Handler.php not found');
file_exists(__DIR__ . $DS . 'core' . $DS . 'Config.php') ? require_once __DIR__ . $DS . 'core' . $DS . 'Config.php' : die('Config.php not found');

use AjaxLiveSearch\core\Config;
use AjaxLiveSearch\core\Handler;

if (session_id() == '') {
    session_start();
}

    $handler = new Handler();
    $handler->getJavascriptAntiBot();
?>

<?php
$servername = "localhost";
$database = "admin_fmbin";
$username = "admin_fmbin";
$password = "anka0606ankA";

// Create connection

$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT cname, ovr, pos FROM information";
$result = $conn->query($sql);

$cname = "";
$ovr = "";
$pos = "";

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $cname = $row["cname"];
        $ovr = $row["ovr"];
        $pos = $row["pos"];
    }
} else {
    echo "0 results";
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="user-scalable=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>BETA</title>
    <style>
      .chart-container {
        width: 640px;
        height: auto;
      }
    </style>
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/fontello.css">
    <link rel="stylesheet" type="text/css" href="css/animation.css">
    <link rel="stylesheet" type="text/css" href="css/ajaxlivesearch.min.css">
    <link rel="stylesheet" type="text/css" href="css/cards.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/cardinfo.js"></script>
  </head>
  <body class="center">
      <div style="clear: both">
        <input type="text" class='mySearch' id="ls_query" placeholder="Type to start searching ...">
      </div>
      <div class="fm-card" style="position: absolute; margin-top: 2em; margin-left: 13%;">
        <img class="background" src="https://cdn-p2.fifarenderz.com/fifamobile/images/backgrounds/backgrounds_RVS2.png">
        <img class="player-img" src="https://cdn-p2.fifarenderz.com/fifamobile/images/players/p190778_R.png" onerror="this.src='https://eaassets-a.akamaihd.net/fifa/u/f/fm18/prod2/s/static/players/players_18/p0.png'">
        <img class="club-img" src="https://cdn-p2.fifarenderz.com/fifamobile/images/programs/program_17_RETRO.png">
        <span class="rating"><?php echo $ovr; ?></span>
        <span class="position"><?php echo $pos; ?></span>
        <span class="name"><?php echo $cname; ?></span>
        <span class="rank"></span>
      </div>
      <div class="all-under" id="all-under" style="margin-left: 40em;">
        <div class="chart-container" id="chart-container">
          <center><canvas id="mycanvas" style="padding-left: 2%; padding-right: 2%;"></canvas></center>
        </div>
        <div id="my-legend-con" class="legend-con"></div>
        
        <div class="p-t-50 p-b-60" style="display: flex;">
          <form class="contact100-form validate-form" id="post_price" style="width: 100%; padding-right: 2%; padding-left: 2%;">
            <div class="wrap-input100 m-b-10 validate-input" data-validate = "Price is required">
              <input class="s2-txt1 placeholder0 input100" id="input-data" type="text" name="price" placeholder="Price" autocomplete="off">
              <span class="focus-input100"></span>
            </div>

            <div class="w-full">
              <button class="flex-c-m s2-txt2 size4 bg1 bor1 hov1 trans-04" id="submit" name="submit" type="submit">
                Submit
              </button>
            </div>
          </form>
          <form action="index.php" method="post" name="time-submit" onsubmit="setData()" style="padding-right: 2%;">
            <select id="time-values" name="time" class="wrap-input100 m-b-10 input100">
              <option name="time" value="1">Hourly</option>
              <option name="time" value="6">6 Hours</option>
              <option name="time" value="12">12 Hours</option>
              <option name="time" value="24">1 Day</option>
              <option name="time" value="72">3 Days</option>
              <option name="time" value="168">1 Week</option>
              <option name="time" value="672">1 Month</option>
              <option name="time" value="2016">3 Months</option>
            </select>
            <button type="submit" value="Submit" class="flex-c-m s2-txt2 size4 bg1 bor1 hov1 trans-04" id="submit-time" name="submit">
              Select
            </button>
          </form>
        </div>
      </div>
      <!-- javascript -->
      <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
      <script type="text/javascript" src="js/ajaxlivesearch.min.js"></script>
      <script>
      jQuery(document).ready(function(){
          jQuery(".mySearch").ajaxlivesearch({
              loaded_at: <?php echo time(); ?>,
              token: <?php echo "'" . $handler->getToken() . "'"; ?>,
              max_input: <?php echo Config::getConfig('maxInputLength'); ?>,
              onResultClick: function(e, data) {
                  // get the index 0 (first column) value
                  var selectedOne = jQuery(data.selected).find('td').eq('0').text();

                  // set the input value
                  jQuery('#ls_query').val(selectedOne);

                  var url = '?player_id='+selectedOne;
                  window.location = url;

                  // hide the result
                  jQuery("#ls_query").trigger('ajaxlivesearch:hide_result');
              },
              onResultEnter: function(e, data) {
                  // do whatever you want
                  // jQuery("#ls_query").trigger('ajaxlivesearch:search', {query: 'test'});
              },
              onAjaxComplete: function(e, data) {

              }
          });
      })
      </script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
      <script type="text/javascript" src="js/linegraph.js"></script>
      <script src="vendor/bootstrap/js/popper.js"></script>
      <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
      <script src="vendor/select2/select2.min.js"></script>
      <script src="js/main.js"></script>
      <script>
      function getParameterByName(name, url) {
          if (!url) url = window.location.href;
          name = name.replace(/[\[\]]/g, '\\$&');
          var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
              results = regex.exec(url);
          if (!results) return null;
          if (!results[2]) return '';
          return decodeURIComponent(results[2].replace(/\+/g, ' '));
      }

      function setData(){
          var select = document.getElementById('time-values');
          var time_id = select.options[select.selectedIndex].value;
          var parameters = "?player_id=" + getParameterByName('player_id') + "&time="+ time_id;
          window.open("https://beta.fmbin.com/" + parameters,"_self"); // TODO: change this link when out of beta
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

      function submit() {
        $.ajax({
          url : "../maxid.php",
          type : "GET",
          success : function(data){
            for(var i in data) {
              maxid.push(data[i].max_id);
            }
            firstmaxid = maxid[0];
            maxid = [];
          },
          error : function(data) {
          }
        });
        var player_id = getParameterByName('player_id');
        var time = getParameterByName('time');
        if (time === null) { time = 6; }
        $("form").submit(function(e) {
          e.preventDefault();
          var input = document.getElementById('input-data').value;
          if (input !== '') {
            $.ajax({
              type: 'POST',
              url: 'adddata.php?player_id=' + player_id,
              data: $('form').serialize(),
              success: function() {
                console.log("Data has been added successfully");
                firstmaxid++;
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

                        if(cname[0] !== null) {
                        $('#chart-container').before('<div id="card-info"><p class="m1-txt1 p-b-36" style="padding-top: 1em; text-align: center;"><b>' + cname[0] + '</b><br><b>Position:</b> ' + pos[0] + ' - <b>Overall:</b> ' + ovr[0] + '<br><br><b>Highest Price:</b> ' + number_format(max_value[0], 0) + '<br><b>Average Price:</b> ' + number_format(avg_value[0], 0) + '<br><b>Lowest Price:</b> ' + number_format(min_value[0], 0) + '</p></div>');
                      }
                      else {
                        $('#chart-container').before('<div id="card-info"><p class="m1-txt1 p-b-36" style="padding-top: 1em; text-align: center;"><b>Whoops!</b><br>There\'s <b>no data since last ' + time + ' hours</b> on this card. You can add a new price or you can try to increase the time interval for your search.</p></div>');
                      }
                      },
                      error : function(data) {
                      }
                    });
                    
                    document.getElementById('input-data').value = '';
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
              },
              error: function() {
                console.log("Could not add the data");
              }
            });
          }
        });
      }

      $(document).ready(function() {
        submit();
        doWork();
      });
    </script>
    <script type="text/javascript">
      $('input.input100').keyup(function(event) {
        // skip for arrow keys
        if(event.which >= 37 && event.which <= 40) return;
        // format number
        $(this).val(function(index, value) {
          return value
          .replace(/\D/g, "")
          .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
          ;
        });
      });
    </script>
  </body>
</html>