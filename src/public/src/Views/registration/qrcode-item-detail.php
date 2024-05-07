<?php
session_start();
ini_set("display_errors", 1);
ini_set('memory_limit', '-1');
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
include_once(__DIR__ . "/../../../vendor/autoload.php");

$param = (isset($params) ? explode("/", $params) : die(header("Location: /error")));
$id = (isset($param[0]) ? $param[0] : die(header("Location: /error")));

use App\Classes\Registration;

$REGISTRATION = new Registration();
$row = $REGISTRATION->item_detail([$id]);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QRCODE DETAIL</title>
  <link rel="stylesheet" href="/vendor/twitter/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/vendor/fortawesome/font-awesome/css/all.min.css">
  <link rel="stylesheet" href="/vendor/datatables/datatables/media/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="/vendor/select2/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="/vendor/pnikolov/bootstrap-daterangepicker/css/daterangepicker.min.css">
  <link rel="stylesheet" href="/styles/css/style.css">
</head>

<body>
  <div class="container mt-5">
    <div class="row mb-2">
      <div class="col-xl-12">
        <div class="card shadow">
          <div class="card-header text-center">DETAIL</div>
          <div class="card-body">
            <div class="row mb-2">
              <label class="col-xl-2 offset-xl-2 col-form-label">EVENT</label>
              <div class="col-xl-6 text-underline">
                <?php echo $row['event_name'] ?>
              </div>
            </div>
            <div class="row mb-2">
              <label class="col-xl-2 offset-xl-2 col-form-label">TOPIC</label>
              <div class="col-xl-6 text-underline">
                <?php echo str_replace("\n", "<br>", $row['topic']) ?>
              </div>
            </div>
            <div class="row mb-2">
              <label class="col-xl-2 offset-xl-2 col-form-label">DATE</label>
              <div class="col-xl-4 text-underline">
                <?php echo $row['date'] ?>
              </div>
            </div>
            <div class="row mb-2">
              <label class="col-xl-2 offset-xl-2 col-form-label">CODE</label>
              <div class="col-xl-4 text-underline">
                <?php echo $row['code'] ?>
              </div>
            </div>
            <div class="row mb-2">
              <label class="col-xl-2 offset-xl-2 col-form-label">CUSTOMER</label>
              <div class="col-xl-6 text-underline">
                <?php echo $row['customer_name'] ?>
              </div>
            </div>
            <div class="row mb-2">
              <label class="col-xl-2 offset-xl-2 col-form-label">E-MAIL</label>
              <div class="col-xl-6 text-underline">
                <?php echo $row['email'] ?>
              </div>
            </div>
            <div class="row mb-2">
              <label class="col-xl-2 offset-xl-2 col-form-label">COMPANY</label>
              <div class="col-xl-6 text-underline">
                <?php echo $row['company'] ?>
              </div>
            </div>
            <div class="row mb-2">
              <label class="col-xl-2 offset-xl-2 col-form-label">COUNTRY</label>
              <div class="col-xl-6 text-underline">
                <?php echo $row['country_name'] ?>
              </div>
            </div>
            <div class="row mb-2">
              <label class="col-xl-2 offset-xl-2 col-form-label">PACKAGE</label>
              <div class="col-xl-6 text-underline">
                <?php echo $row['package_name'] ?>
              </div>
            </div>
            <div class="row mb-2">
              <label class="col-xl-2 offset-xl-2 col-form-label">TYPE</label>
              <div class="col-xl-6 text-underline">
                <?php echo $row['type_name'] ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="/vendor/components/jquery/jquery.min.js"></script>
  <script src="/vendor/twitter/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/vendor/datatables/datatables/media/js/jquery.dataTables.min.js"></script>
  <script src="/vendor/datatables/datatables/media/js/dataTables.bootstrap4.min.js"></script>
  <script src="/vendor/select2/select2/dist/js/select2.min.js"></script>
  <script src="/vendor/moment/moment/min/moment.min.js"></script>
  <script src="/vendor/pnikolov/bootstrap-daterangepicker/js/daterangepicker.min.js"></script>
  <script src="/styles/js/sweetalert2.all.min.js"></script>
  <script src="/styles/js/axios.min.js"></script>
  <script src="/styles/js/main.js"></script>
  <script src="/styles/js/chart.min.js"></script>
  <script src="/styles/js/chartjs-plugin-datalabels.js"></script>
</body>

</html>