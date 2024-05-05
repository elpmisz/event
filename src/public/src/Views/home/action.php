<?php
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
include_once(__DIR__ . "/../../../vendor/autoload.php");

use App\Classes\User;
use App\Classes\System;
use App\Classes\Validation;
use Firebase\JWT\JWT;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

define("JWT_SECRET", "SECRET-KEY");
define("JWT_ALGO", "HS512");

$USER = new User();
$SYSTEM = new System();
$VALIDATION = new Validation();

$system = $SYSTEM->read();
$system_name = (!empty($system['name']) ? $system['name'] : "");
$username_email = (!empty($system['email']) ? $system['email'] : "");
$password_email = (!empty($system['password_email']) ? $system['password_email'] : "");

$MAIL = new PHPMailer(true);
$MAIL->SMTPDebug = SMTP::DEBUG_OFF;
$MAIL->isSMTP();
$MAIL->Host = "smtp.gmail.com";
$MAIL->SMTPAuth = true;
$MAIL->Username = $username_email;
$MAIL->Password = $password_email;
$MAIL->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
$MAIL->Port = 465;
$MAIL->CharSet = "UTF-8";
$MAIL->SMTPOptions = [
  "ssl" => [
    "verify_peer" => false,
    "verify_peer_name" => false,
    "allow_self_signed" => true
  ]
];

$param = (isset($params) ? explode("/", $params) : header("Location: /error"));
$action = (isset($param[0]) ? $param[0] : die(header("Location: /error")));
$param1 = (isset($param[1]) ? $param[1] : "");
$param2 = (isset($param[2]) ? $param[2] : "");

if ($action === "login") {
  try {
    $email = (isset($_POST['email']) ? $VALIDATION->input($_POST['email']) : "");
    $password = (isset($_POST['password']) ? $VALIDATION->input($_POST['password']) : "");

    $count = $USER->user_count([$email]);
    $verify = $USER->user_verify([$email], $password);

    if (intval($count) === 0 || intval($verify) === 0) {
      $VALIDATION->alert("danger", "อีเมล หรือรหัสผ่านไม่ถูกต้อง!", "/");
    }

    $status = $USER->user_status([$email]);
    if (intval($status) === 2) {
      $VALIDATION->alert("danger", "กรุณาติดต่อผู้ดูแลระบบ!", "/");
    }

    $now = time();
    $payload = [
      "iat" => $now,
      "exp" => $now + (4 * 60 * 60),
      "data" => $email,
    ];
    $encode = JWT::encode($payload, JWT_SECRET, JWT_ALGO);
    setcookie("jwt", $encode);

    $VALIDATION->alert("success", "เข้าสู่ระบบเรียบร้อยแล้ว!", "/home");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "forgot") {
  try {
    $email = (isset($_POST['email']) ? $VALIDATION->input($_POST['email']) : "");
    $random_password = $VALIDATION->get_rand_numbers(6);
    $hash_password = password_hash($random_password, PASSWORD_DEFAULT);
    $user = $USER->user_view_email([$email]);

    $USER->forgot_password([$hash_password, $email]);

    try {
      $MAIL->setFrom($username_email, "E-MAIL NOTIFICATION");
      $MAIL->addAddress("worachit.p@gmail.com", $user['fullname']);

      $MAIL->isHTML(true);
      $MAIL->Subject = "[NEW PASSSWORD] {$system_name} SYSTEM";
      $MAIL->Body = $VALIDATION->forgot_email($random_password);
      $MAIL->send();

      $VALIDATION->alert("success", "ส่งรหัสผ่านไปที่ อีเมล เรียบร้อยแล้ว!", "/");
    } catch (Exception $e) {
      $VALIDATION->alert("danger", "E-MAIL ERROR!", "/");
    }
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "logout") {
  try {
    $VALIDATION->logout();
    $VALIDATION->alert("success", "ออกจากระบบเรียบร้อยแล้ว!", "/");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}
