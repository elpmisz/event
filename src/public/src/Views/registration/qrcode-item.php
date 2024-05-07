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

$http = ($_SERVER['REQUEST_SCHEME'] ? $_SERVER['REQUEST_SCHEME'] : "");
$host = ($_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : "");
$server = "{$http}://{$host}/registration";

ob_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>TICKET</title>
  <style>
    .card {
      width: 200px;
      border: 2px solid;
      padding: 10px;
      text-align: center;
      border-radius: 10px;
    }

    .container {
      padding: 2px 16px;
    }
  </style>
</head>

<body>
  <div class="card">
    <h4>TICKET</h4>
    <h5><?php echo $row['event_name'] ?></h5>
    <barcode code="<?php echo $server ?>/qrcode-item-detail/<?php echo $row['id'] ?>" type="QR" size="1.5" disableborder="1">
      <h4>
        <?php echo $row['customer_name'] ?><br>
        <?php echo $row['type_name'] ?><br>
        <?php echo $row['country_name'] ?><br>
      </h4>
  </div>
</body>

</html>
<?php
$html = ob_get_contents();
ob_end_clean();

$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'default_font' => 'garuda']);
$mpdf->WriteHTML($html);
$date = date('Ymd');
$mpdf->Output("ticket.pdf", 'I');
