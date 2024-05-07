<?php
session_start();
ini_set("display_errors", 1);
ini_set('memory_limit', '-1');
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
include_once(__DIR__ . "/../../../vendor/autoload.php");

$param = (isset($params) ? explode("/", $params) : die(header("Location: /error")));
$uuid = (isset($param[0]) ? $param[0] : die(header("Location: /error")));

use App\Classes\Registration;

$REGISTRATION = new Registration();
$items = $REGISTRATION->item_view([$uuid]);

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
    .column {
      float: left;
      width: 30%;
      padding: 0 10px;
    }

    .card {
      width: 200px;
      border: 2px solid;
      padding: 5px;
      margin: 10px;
      text-align: center;
      border-radius: 10px;
    }

    div {
      page-break-inside: avoid;
    }
  </style>
</head>

<body>
  <?php foreach ($items as $item) : ?>
    <div class="column">
      <div class="card">
        <h4>TICKET</h4>
        <h6><?php echo $item['event_name'] ?></h6>
        <barcode code="<?php echo $server ?>/qrcode-item-detail/<?php echo $item['id'] ?>" type="QR" size="1.5" disableborder="1">
          <h5>
            <?php echo $item['customer_name'] ?><br>
            <?php echo $item['type_name'] ?><br>
            <?php echo $item['country_name'] ?><br>
          </h5>
      </div>
    </div>
  <?php endforeach; ?>
</body>

</html>
<?php
$html = ob_get_contents();
ob_end_clean();

$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'default_font' => 'garuda']);
$mpdf->WriteHTML($html);
$mpdf->Output("ticket.pdf", 'I');
