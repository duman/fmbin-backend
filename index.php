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
  </head>
  <body class="center">
      <p class="m1-txt1 p-b-36" style="padding-top: 5em; text-align: center;">
      <?php
      $servername = "localhost";
      $username = "admin_fmbin";
      $password = "anka0606ankA";
      $dbname = "admin_fmbin";

      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      $player_id = mysqli_real_escape_string($conn, $_REQUEST['player_id']);

      $sql = "SELECT name, pos, ovr FROM information WHERE player_id=" . $player_id;
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
          // output data of each row
          while($row = $result->fetch_assoc()) {
              echo "<b>" . $row["name"]. "</b><br><b>Position:</b> " . $row["pos"]. " - <b>Overall:</b> " . $row["ovr"] . "<br>";
          }
      } else {
          echo "0 results";
      }

      $sql = "SELECT MAX(price_value), MIN(price_value) FROM players WHERE player_id =" . $player_id;
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
          // output data of each row
          while($row = $result->fetch_assoc()) {
              echo "<br><b>Highest Price:</b> " . number_format($row["MAX(price_value)"]) . "<br><b>Lowest Price:</b> " . number_format($row["MIN(price_value)"]) . "<br>";
          }
      } else {
          echo "Could not retrieve price data.";
      }

      $conn->close();
      ?>
      </p>
      <div class="chart-container">
        <canvas id="mycanvas"></canvas>
      </div>
      
      <div class="p-t-50 p-b-60">
        <form class="contact100-form validate-form" id="post_price">
          <div class="wrap-input100 m-b-10 validate-input" data-validate = "Price is required">
            <input class="s2-txt1 placeholder0 input100" type="text" name="price" placeholder="Price" autocomplete="off">
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
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
      <script type="text/javascript" src="js/linegraph.js"></script>
      <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
      <script src="vendor/bootstrap/js/popper.js"></script>
      <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
      <script src="vendor/select2/select2.min.js"></script>
      <script type="text/javascript" src="js/linegraph.js"></script>
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

      function removeData(chart) {
          chart.data.labels.pop();
          chart.data.datasets.forEach((dataset) => {
              dataset.data.pop();
          });
          chart.update();
      }

      function submit() {
        var player_id = getParameterByName('player_id');
        $("form").submit(function(e) {
          e.preventDefault();
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
                  $("#mycanvas").load(location.href+" #mycanvas>*","");
                  ctx = $("#mycanvas");

                  LineGraph = new Chart(ctx, {
                    type: 'line',
                    data: chartdata
                  });
                  $("#mycanvas").load(location.href+" #mycanvas>*","");
                },
                error : function(data) {
                }
              });
            },
            error: function() {
              console.log("Could not add the data");
            }
          });
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