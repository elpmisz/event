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
$items = $REGISTRATION->event_view([$id]);

$http = ($_SERVER['REQUEST_SCHEME'] ? $_SERVER['REQUEST_SCHEME'] : "");
$host = ($_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : "");
$server = "{$http}://{$host}/registration";

$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'default_font' => 'garuda']);
$html = "";
foreach ($items as $key => $item) : $key++;
  $html .= "<div class='column' style='float: left; width: 30%; padding: 0 10px;'>
  <div class='card' style='width: 200px; border: 2px solid; padding: 5px; margin: 10px; text-align: center; border-radius: 10px;'>
    <h4>TICKET</h4>
    <h5>{$item['event_name']}</h5>
    <barcode code='{$server}/qrcode-item-detail/{$item['uuid']}' type='QR' size='1.5' disableborder='1'>
      <h5>
        {$item['code']}<br>
        {$item['customer_name']}<br>
        {$item['type']}<br>
        {$item['country_name']}<br>
      </h5>
  </div>
</div>";

  if ($key % 6 == 0 && $key != COUNT($items)) {
    $html .= "<pagebreak />";
  }
endforeach;
$mpdf->WriteHTML($html);
$mpdf->Output("ticket.pdf", 'I');
