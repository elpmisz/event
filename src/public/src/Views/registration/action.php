<?php
session_start();
ini_set("display_errors", 1);
ini_set('memory_limit', '-1');
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
include_once(__DIR__ . "/../../../vendor/autoload.php");

use App\Classes\registration;
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
$registration = new registration();
$VALIDATION = new Validation();

$user = $USER->user_view_email([$email]);

$param = (isset($params) ? explode("/", $params) : header("Location: /error"));
$action = (isset($param[0]) ? $param[0] : die(header("Location: /error")));
$param1 = (isset($param[1]) ? $param[1] : "");
$param2 = (isset($param[2]) ? $param[2] : "");

if ($action === "create") {
  try {
    $name = (isset($_POST['name']) ? $VALIDATION->input($_POST['name']) : "");
    $topic = (isset($_POST['topic']) ? $VALIDATION->input($_POST['topic']) : "");
    $date = (isset($_POST['date']) ? $VALIDATION->input($_POST['date']) : "");

    $conv = (!empty($date) ? explode("-", $date) : "");
    $start = date("Y-m-d", strtotime(str_replace("/", "-", trim($conv[0]))));
    $end = date("Y-m-d", strtotime(str_replace("/", "-", trim($conv[1]))));

    $count = $registration->registration_count([$name]);
    if (intval($count) > 0) {
      $VALIDATION->alert("danger", "ข้อมูลซ้ำในระบบ!", "/registration");
    }

    $registration->registration_insert([$name, $topic, $date, $start, $end]);
    $registration_id = $registration->last_insert_id();

    foreach ($_POST['item_name'] as $key => $value) {
      $item_name = (isset($_POST['item_name'][$key]) ? $VALIDATION->input($_POST['item_name'][$key]) : "");
      $item_price = (isset($_POST['item_price'][$key]) ? $VALIDATION->input($_POST['item_price'][$key]) : "");
      $item_text = (isset($_POST['item_text'][$key]) ? $VALIDATION->input($_POST['item_text'][$key]) : "");

      if (!empty($item_name)) {
        $registration->item_insert([$registration_id, $item_name, $item_price, $item_text]);
      }
    }

    $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!", "/registration");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "edit") {
  try {
    $id = (isset($_POST['id']) ? $VALIDATION->input($_POST['id']) : "");
    $uuid = (isset($_POST['uuid']) ? $VALIDATION->input($_POST['uuid']) : "");
    $name = (isset($_POST['name']) ? $VALIDATION->input($_POST['name']) : "");
    $topic = (isset($_POST['topic']) ? $VALIDATION->input($_POST['topic']) : "");
    $date = (isset($_POST['date']) ? $VALIDATION->input($_POST['date']) : "");
    $status = (isset($_POST['status']) ? $VALIDATION->input($_POST['status']) : "");

    $conv = (!empty($date) ? explode("-", $date) : "");
    $start = date("Y-m-d", strtotime(str_replace("/", "-", trim($conv[0]))));
    $end = date("Y-m-d", strtotime(str_replace("/", "-", trim($conv[1]))));

    $registration->registration_update([$name, $topic, $date, $start, $end, $status, $uuid]);

    foreach ($_POST['item__id'] as $key => $value) {
      $item__id = (isset($_POST['item__id'][$key]) ? $VALIDATION->input($_POST['item__id'][$key]) : "");
      $item__name = (isset($_POST['item__name'][$key]) ? $VALIDATION->input($_POST['item__name'][$key]) : "");
      $item__price = (isset($_POST['item__price'][$key]) ? $VALIDATION->input($_POST['item__price'][$key]) : "");
      $item__text = (isset($_POST['item__text'][$key]) ? $VALIDATION->input($_POST['item__text'][$key]) : "");

      if (!empty($item__id)) {
        $registration->item_update([$item__name, $item__price, $item__text, $item__id]);
      }
    }

    foreach ($_POST['item_name'] as $key => $value) {
      $item_name = (isset($_POST['item_name'][$key]) ? $VALIDATION->input($_POST['item_name'][$key]) : "");
      $item_price = (isset($_POST['item_price'][$key]) ? $VALIDATION->input($_POST['item_price'][$key]) : "");
      $item_text = (isset($_POST['item_text'][$key]) ? $VALIDATION->input($_POST['item_text'][$key]) : "");

      if (!empty($item_name)) {
        $registration->item_insert([$registration_id, $item_name, $item_price, $item_text]);
      }
    }
    $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!", "/registration/edit/{$uuid}");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "delete") {
  try {
    $data = json_decode(file_get_contents("php://input"), true);
    $uuid = $data['uuid'];

    if (!empty($uuid)) {
      $registration->registration_delete([$uuid]);
      $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!");
      echo json_encode(200);
    } else {
      $VALIDATION->alert("danger", "ระบบมีปัญหา ลองใหม่อีกครั้ง!");
      echo json_encode(500);
    }
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "item-delete") {
  try {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    if (!empty($id)) {
      $registration->item_delete([$id]);
      $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!");
      echo json_encode(200);
    } else {
      $VALIDATION->alert("danger", "ระบบมีปัญหา ลองใหม่อีกครั้ง!");
      echo json_encode(500);
    }
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
      $VALIDATION->alert("danger", "เฉพาะไฟล์ XLS XLSX CSV!", "/registration");
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
        $name_th = (isset($value[0]) ? $value[0] : "");
        $name_en = (isset($value[1]) ? $value[1] : "");
        $code = (isset($value[2]) ? $value[2] : "");

        $count = $registration->registration_count([$name_th]);
        if (intval($count) === 0) {
          $registration->registration_insert([$name_th, $name_en, $code]);
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
    $result = $registration->registration_data();
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}
