<?php
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
include_once(__DIR__ . "/../../../vendor/autoload.php");

use App\Classes\System;
use App\Classes\Validation;

$SYSTEM = new System();
$VALIDATION = new Validation();

$param = (isset($params) ? explode("/", $params) : header("Location: /error"));
$action = (isset($param[0]) ? $param[0] : die(header("Location: /error")));
$param1 = (isset($param[1]) ? $param[1] : "");
$param2 = (isset($param[2]) ? $param[2] : "");

if ($action === "update") {
  try {
    $name = (isset($_POST['name']) ? $VALIDATION->input($_POST['name']) : "");
    $email = (isset($_POST['email']) ? $VALIDATION->input($_POST['email']) : "");
    $password = (isset($_POST['password']) ? $VALIDATION->input($_POST['password']) : "");
    $default = (isset($_POST['default']) ? $VALIDATION->input($_POST['default']) : "");

    $SYSTEM->update([$name, $email, $password, $default]);
    $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!", "/system");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}
