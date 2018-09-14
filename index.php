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

$sql = "SELECT player_id, name, pos, ovr FROM information WHERE player_id=" . $player_id;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["player_id"]. " - Name: " . $row["name"]. " " . $row["pos"]. " " . $row["ovr"] . "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>

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
  <body>
    <center>
      <div class="chart-container" style="margin-top: 10em;">
        <canvas id="mycanvas"></canvas>
      </div>
      
      <div class="p-t-50 p-b-60" style="width: 40em;">
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

      function submit() {
        var player_id = getParameterByName('player_id');
        $("form").submit(function(e) {
          e.preventDefault();
          $.ajax({
            type: 'POST',
            url: 'adddata.php?player_id=' + player_id,
            data: $('form').serialize(),
            success: function() {
              console.log("Data was added successfully");
              location.reload();
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
    </center>
  </body>
</html>