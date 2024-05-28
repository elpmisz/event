<?php
session_start();
ini_set("display_errors", 1);
ini_set('memory_limit', '-1');
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
include_once(__DIR__ . "/../../../vendor/autoload.php");

use App\Classes\Customer;
use App\Classes\Validation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$CUSTOMER = new Customer();
$VALIDATION = new Validation();
$SPREADSHEET = new Spreadsheet();
$WRITER = new Xlsx($SPREADSHEET);


$SPREADSHEET->setActiveSheetIndex(0);
$ACTIVESHEET = $SPREADSHEET->getActiveSheet();

$STYLEHEADER = [
  'font' => [
    'bold' => true,
  ],
  'alignment' => [
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
  ],
  'borders' => [
    'allBorders' => [
      'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    ],
  ]
];

$data = $CUSTOMER->customer_export();
$columns = ["NAME", "E-MAIL", "COMPANY", "COUNTRY", "STATUS", "CREATED"];

ob_start();
$date = date('Y-m-d');
$filename = "{$date}_customer.csv";
header("Content-Encoding: UTF-8");
header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename={$filename}");
ob_end_clean();

$output = fopen("php://output", "w");
fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
fputcsv($output, $columns);

foreach ($data as $value) {
  fputcsv($output, $value);
}

fclose($output);
die();
