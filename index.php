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
    <div class="chart-container">
      <canvas id="mycanvas"></canvas>
    </div>

    <form class="contact100-form validate-form">
      <div class="wrap-input100 m-b-10 validate-input" data-validate = "Price is required">
        <input class="s2-txt1 placeholder0 input100" type="text" name="name" placeholder="Price" autocomplete="off">
        <span class="focus-input100"></span>
      </div>

      <div class="w-full">
        <button class="flex-c-m s2-txt2 size4 bg1 bor1 hov1 trans-04">
          Submit
        </button>
      </div>
    </form>
    
    <!-- javascript -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
    <script type="text/javascript" src="js/linegraph.js"></script>
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="vendor/bootstrap/js/popper.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/select2/select2.min.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>