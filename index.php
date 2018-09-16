<!DOCTYPE html>
<html>
  <head>
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
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/cardinfo.js"></script>
  </head>
  <body class="center">
      <div class="chart-container" id="chart-container">
        <canvas id="mycanvas"></canvas>
      </div>
      
      <div class="p-t-50 p-b-60">
        <form class="contact100-form validate-form" id="post_price">
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
      </div>
      
      <!-- javascript -->
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
      <script type="text/javascript" src="js/linegraph.js"></script>
      <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
      <script src="vendor/bootstrap/js/popper.js"></script>
      <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
      <script src="vendor/select2/select2.min.js"></script>
      <script src="js/main.js"></script>
      <script>doWork();</script>
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
                      url : "../informationdata.php?player_id=" + player_id,
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

                        $('#chart-container').before('<div id="card-info"><p class="m1-txt1 p-b-36" style="padding-top: 5em; text-align: center;"><b>' + cname[0] + '</b><br><b>Position:</b> ' + pos[0] + ' - <b>Overall:</b> ' + ovr[0] + '<br><br><b>Highest Price:</b> ' + number_format(max_value[0], 0) + '<br><b>Average Price:</b> ' + number_format(avg_value[0], 0) + '<br><b>Lowest Price:</b> ' + number_format(min_value[0], 0) + '</p></div>');
                      },
                      error : function(data) {
                      }
                    });
                    
                    document.getElementById('input-data').value = '';
                    ctx = $("#mycanvas");

                    LineGraph = new Chart(ctx, {
                      type: 'line',
                      data: chartdata
                    });
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