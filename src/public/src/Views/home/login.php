<?php
session_start();
ini_set("display_errors", "1");
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
include_once(__DIR__ . "/../../../vendor/autoload.php");

$jwt = (isset($_COOKIE['jwt']) ? $_COOKIE['jwt'] : "");
if (!empty($jwt)) {
  die(header("Location: /home "));
}

use App\Classes\System;

$SYSTEM = new System();
$system = $SYSTEM->read();
$system_name = (isset($system['name']) ? $system['name'] : "");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?php echo $system_name ?></title>
  <link href="/vendor/twitter/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/vendor/fortawesome/font-awesome/css/all.min.css" rel="stylesheet">
  <link href="/styles/css/style.css" rel="stylesheet">
</head>

<body>

  <div class="container">

    <div class="row justify-content-center my-5">
      <div class="col-xl-5">
        <div class="card shadow">
          <div class="card-body">
            <form action="/login" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
              <div class="row justify-content-center my-3">
                <h3 class="text-center"><?php echo $system_name ?></h3>
              </div>
              <div class="row mb-2 mx-3">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fa fa-envelope"></i></div>
                  </div>
                  <input type="email" class="form-control" name="email" placeholder="อีเมล" required>
                  <div class="invalid-feedback">
                    กรุณากรอกข้อมูล!
                  </div>
                </div>
              </div>
              <div class="row mb-2 mx-3">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fa fa-lock"></i></div>
                  </div>
                  <input type="password" class="form-control" name="password" placeholder="รหัสผ่าน" required>
                  <div class="invalid-feedback">
                    กรุณากรอกข้อมูล!
                  </div>
                </div>
              </div>

              <div class="row my-3 mx-3">
                <button class="btn btn-success btn-sm btn-block">
                  <i class="fa fa-check pr-2"></i>เข้าสู่ระบบ
                </button>
              </div>
              <div class="row mb-2 justify-content-center">
                <a href="javascript:void(0)" data-toggle="modal" data-target="#forgot-password">
                  ลืมรหัสผ่าน?
                </a>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="forgot-password" data-backdrop="static">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header mx-auto">
          <h5 class="modal-title">ลืมรหัสผ่าน</h5>
        </div>
        <div class="modal-body">
          <form action="/forgot" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>

            <div class="row mb-2 mx-3">
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fa fa-envelope"></i></div>
                </div>
                <input type="email" class="form-control" name="email" placeholder="อีเมล" required>
                <div class="invalid-feedback">
                  กรุณากรอกข้อมูล!
                </div>
              </div>
            </div>

            <div class="row mb-2 justify-content-center">
              <div class="col-xl-4 mb-2">
                <button type="submit" class="btn btn-success btn-sm btn-block">
                  <i class="fa fa-check mr-2"></i>ยืนยัน
                </button>
              </div>
              <div class="col-xl-4 mb-2">
                <button type="button" class="btn btn-danger btn-sm btn-block" data-dismiss="modal">
                  <i class="fa fa-times mr-2"></i>ปิด
                </button>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>

  <?php if (!empty($_SESSION['alert']) && !empty($_SESSION['text'])) : ?>
    <div style="position: absolute; top: 120px; right: 0; width: 400px;">
      <div class="toast hide" data-delay="5000">
        <div class="toast-header bg-<?php echo $_SESSION['alert'] ?>">
          <strong class="mr-auto text-white">NOTIFICATION</strong>
          <button type="button" class="ml-5 close" data-dismiss="toast">
            <i class="fa fa-times text-white"></i>
          </button>
        </div>
        <div class="toast-body">
          <h5 class="text-<?php echo $_SESSION['alert'] ?>"><?php echo $_SESSION['text'] ?></h5>
        </div>
      </div>
    </div>
  <?php
  endif;
  unset($_SESSION['alert'], $_SESSION['text']);
  ?>

  <script src="/vendor/components/jquery/jquery.min.js"></script>
  <script src="/vendor/twitter/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/styles/js/main.js"></script>
  <script>
    $(".toast").toast("show");
  </script>
</body>

</html>