<?php
session_start();
ini_set("display_errors", 1);
ini_set('memory_limit', '-1');
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
include_once(__DIR__ . "/../../../vendor/autoload.php");

use App\Classes\Customer;
use App\Classes\Registration;
use App\Classes\User;
use App\Classes\Validation;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

try {
  define("JWT_SECRET", "SECRET-KEY");
  define("JWT_ALGO", "HS512");
  $jwt = (isset($_COOKIE['jwt']) ? $_COOKIE['jwt'] : "");
  if (empty($jwt)) {
    die(header("Location: /"));
  }
  $decode = JWT::decode($jwt, new Key(JWT_SECRET, JWT_ALGO));
  $email = (isset($decode->data) ? $decode->data : "");
} catch (Exception $e) {
  $msg = $e->getMessage();
  if ($msg === "Expired token") {
    die(header("Location: /logout"));
  }
}

$USER = new User();
$CUSTOMER = new Customer();
$REGISTRATION = new Registration();
$VALIDATION = new Validation();

$user = $USER->user_view_email([$email]);

$param = (isset($params) ? explode("/", $params) : header("Location: /error"));
$action = (isset($param[0]) ? $param[0] : die(header("Location: /error")));
$param1 = (isset($param[1]) ? $param[1] : "");
$param2 = (isset($param[2]) ? $param[2] : "");

if ($action === "create") {
  try {
    echo "<pre>";
    print_r($_POST);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "import") {
  try {
    $start = microtime(true);
    $file_name = (isset($_FILES['file']['name']) ? $_FILES['file']['name'] : '');
    $file_tmp = (isset($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'] : '');
    $file_allow = ["xls", "xlsx", "csv"];
    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

    if (!in_array($file_extension, $file_allow)) :
      $VALIDATION->alert("danger", "เฉพาะไฟล์ XLS XLSX CSV!", "/customer");
    endif;

    if ($file_extension === "xls") {
      $READER = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
    } elseif ($file_extension === "xlsx") {
      $READER = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    } else {
      $READER = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
    }

    $READ = $READER->load($file_tmp);
    $result = $READ->getActiveSheet()->toArray();
    $columns = $READ->getActiveSheet()->getHighestColumn();
    $columnsIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($columns);

    $data = [];
    foreach ($result as $value) {
      $data[] = array_map("trim", $value);
    }

    foreach ($data as $key => $value) {
      if (!in_array($key, [0])) {
        $code = (isset($value[0]) ? $value[0] : "");
        $customer = (isset($value[1]) ? $value[1] : "");
        $customer_id = $REGISTRATION->customer_id([$customer]);
        $country = (isset($value[2]) ? $value[2] : "");
        $country_id = $REGISTRATION->country_id([$country]);
        $email = (isset($value[3]) ? $value[3] : "");
        $company = (isset($value[4]) ? $value[4] : "");
        $type = (isset($value[5]) ? $value[5] : "");
        $type_id = $REGISTRATION->type_id([$type]);
        $event = (isset($value[6]) ? $value[6] : "");
        $event_id = $REGISTRATION->event_id([$event]);
        $package = (isset($value[7]) ? $value[7] : "");
        $package_id = $REGISTRATION->package_id([$event_id, $package]);

        $count = $CUSTOMER->customer_count([$customer, $email, $company, $country_id]);
        if (intval($count) === 0) {
          $CUSTOMER->customer_insert([$customer, $email, $company, $country_id]);
        }
      }
    }

    foreach ($data as $key => $value) {
      if (!in_array($key, [0])) {
        $code = (isset($value[0]) ? $value[0] : "");
        $customer = (isset($value[1]) ? $value[1] : "");
        $customer_id = $REGISTRATION->customer_id([$customer]);
        $country = (isset($value[2]) ? $value[2] : "");
        $country_id = $REGISTRATION->country_id([$country]);
        $email = (isset($value[3]) ? $value[3] : "");
        $company = (isset($value[4]) ? $value[4] : "");
        $type = (isset($value[5]) ? $value[5] : "");
        $type_id = $REGISTRATION->type_id([$type]);
        $event = (isset($value[6]) ? $value[6] : "");
        $event_id = $REGISTRATION->event_id([$event]);
        $package = (isset($value[7]) ? $value[7] : "");
        $package_id = $REGISTRATION->package_id([$event_id, $package]);

        $count = $REGISTRATION->registration_count([$event_id, $package_id, $type_id]);
        if (intval($count) === 0) {
          $REGISTRATION->registration_insert([$event_id, $package_id, $type_id]);
        }
      }
    }

    foreach ($data as $key => $value) {
      if (!in_array($key, [0])) {
        $code = (isset($value[0]) ? $value[0] : "");
        $customer = (isset($value[1]) ? $value[1] : "");
        $customer_id = $REGISTRATION->customer_id([$customer]);
        $country = (isset($value[2]) ? $value[2] : "");
        $country_id = $REGISTRATION->country_id([$country]);
        $email = (isset($value[3]) ? $value[3] : "");
        $company = (isset($value[4]) ? $value[4] : "");
        $type = (isset($value[5]) ? $value[5] : "");
        $type_id = $REGISTRATION->type_id([$type]);
        $event = (isset($value[6]) ? $value[6] : "");
        $event_id = $REGISTRATION->event_id([$event]);
        $package = (isset($value[7]) ? $value[7] : "");
        $package_id = $REGISTRATION->package_id([$event_id, $package]);
        $registration_id = $REGISTRATION->registration_id([$event_id, $package_id, $type_id]);

        $count = $REGISTRATION->item_count([$registration_id, $code, $customer_id]);
        if (intval($count) === 0) {
          $REGISTRATION->item_insert([$registration_id, $code, $customer_id]);
        }
      }
    }

    $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!", "/registration");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "data") {
  try {
    $result = $REGISTRATION->registration_data();
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "event-select") {
  try {
    $keyword = (isset($_POST['q']) ? $VALIDATION->input($_POST['q']) : "");
    $result = $REGISTRATION->event_select($keyword);
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "package-select") {
  try {
    $keyword = (isset($_POST['q']) ? $VALIDATION->input($_POST['q']) : "");
    $event = (isset($_POST['event']) ? $VALIDATION->input($_POST['event']) : "");
    $result = $REGISTRATION->package_select($keyword, [$event]);
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "type-select") {
  try {
    $keyword = (isset($_POST['q']) ? $VALIDATION->input($_POST['q']) : "");
    $result = $REGISTRATION->type_select($keyword);
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "customer-select") {
  try {
    $keyword = (isset($_POST['q']) ? $VALIDATION->input($_POST['q']) : "");
    $result = $REGISTRATION->customer_select($keyword);
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}
