<?php
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
include_once(__DIR__ . "/../../../vendor/autoload.php");

use App\Classes\User;
use App\Classes\Validation;

$USER = new User();
$VALIDATION = new Validation();

$param = (isset($params) ? explode("/", $params) : header("Location: /error"));
$action = (isset($param[0]) ? $param[0] : die(header("Location: /error")));
$param1 = (isset($param[1]) ? $param[1] : "");
$param2 = (isset($param[2]) ? $param[2] : "");

if ($action === "create") {
  try {
    $email = (isset($_POST['email']) ? $VALIDATION->input($_POST['email']) : "");
    $firstname = (isset($_POST['firstname']) ? $VALIDATION->input($_POST['firstname']) : "");
    $lastname = (isset($_POST['lastname']) ? $VALIDATION->input($_POST['lastname']) : "");
    $contact = (isset($_POST['contact']) ? $VALIDATION->input($_POST['contact']) : "");

    $default_password = $USER->default_password();
    $hash_password = password_hash($default_password, PASSWORD_DEFAULT);

    $USER->login_insert([$email, $hash_password]);
    $login = $USER->last_insert_id();
    $USER->user_insert([$login, $firstname, $lastname, $contact]);

    $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!", "/user");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "update") {
  try {
    $uuid = (isset($_POST['uuid']) ? $VALIDATION->input($_POST['uuid']) : "");
    $email = (isset($_POST['email']) ? $VALIDATION->input($_POST['email']) : "");
    $firstname = (isset($_POST['firstname']) ? $VALIDATION->input($_POST['firstname']) : "");
    $lastname = (isset($_POST['lastname']) ? $VALIDATION->input($_POST['lastname']) : "");
    $contact = (isset($_POST['contact']) ? $VALIDATION->input($_POST['contact']) : "");
    $level = (isset($_POST['level']) ? $VALIDATION->input($_POST['level']) : "");
    $status = (isset($_POST['status']) ? $VALIDATION->input($_POST['status']) : "");

    $USER->admin_update([$email, $level, $status, $firstname, $lastname, $contact, $uuid]);
    $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!", "/user");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "change") {
  try {
    $uuid = (isset($_POST['uuid']) ? $VALIDATION->input($_POST['uuid']) : "");
    $password = (isset($_POST['password']) ? $VALIDATION->input($_POST['password']) : "");
    $password2 = (isset($_POST['password2']) ? $VALIDATION->input($_POST['password2']) : "");

    if ($password !== $password2) {
      $VALIDATION->alert("danger", "รหัสผ่านไม่ตรงกัน!", "/user/change");
    }

    $hash_password = password_hash($password, PASSWORD_DEFAULT);
    $USER->change_password([$hash_password, $uuid]);
    $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!", "/");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "profile") {
  try {
    $uuid = (isset($_POST['uuid']) ? $VALIDATION->input($_POST['uuid']) : "");
    $email = (isset($_POST['email']) ? $VALIDATION->input($_POST['email']) : "");
    $firstname = (isset($_POST['firstname']) ? $VALIDATION->input($_POST['firstname']) : "");
    $lastname = (isset($_POST['lastname']) ? $VALIDATION->input($_POST['lastname']) : "");
    $contact = (isset($_POST['contact']) ? $VALIDATION->input($_POST['contact']) : "");

    $USER->user_update([$firstname, $lastname, $contact, $uuid]);
    $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!", "/user/profile");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "user-data") {
  try {
    $result = $USER->user_data();
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}
