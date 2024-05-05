<?php
$menu = "setting";
$page = "setting-system";

use App\Classes\System;

include_once(__DIR__ . "/../layout/header.php");

$SYSTEM = new System();
$system = $SYSTEM->read();

$name = (isset($system['name']) ? $system['name'] : "");
$email = (isset($system['email']) ? $system['email'] : "");
$password = (isset($system['password_email']) ? $system['password_email'] : "");
$default = (isset($system['password_default']) ? $system['password_default'] : "");
?>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow">
      <div class="card-header">
        <h4 class="text-center">ตั้งค่าระบบ</h4>
      </div>
      <div class="card-body">
        <form action="/system/update" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">

          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">ชื่อระบบ</label>
            <div class="col-xl-4">
              <input type="text" class="form-control form-control-sm" name="name" value="<?php echo $name ?>" required>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">อีเมล</label>
            <div class="col-xl-4">
              <input type="email" class="form-control form-control-sm" name="email" value="<?php echo $email ?>" required>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">รหัสผ่าน (อีเมล)</label>
            <div class="col-xl-4">
              <input type="password" class="form-control form-control-sm" name="password" value="<?php echo $password ?>" required>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
            <div class="col-xl-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="email-show">
                <label class="form-check-label" for="email-show">
                  แสดง
                </label>
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">รหัสผ่าน (ตั้งต้น)</label>
            <div class="col-xl-4">
              <input type="password" class="form-control form-control-sm" name="default" value="<?php echo $default ?>" required>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
            <div class="col-xl-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="default-show">
                <label class="form-check-label" for="default-show">
                  แสดง
                </label>
              </div>
            </div>
          </div>

          <div class="row justify-content-center mb-2">
            <div class="col-xl-3 mb-2">
              <button type="submit" class="btn btn-sm btn-success btn-block">
                <i class="fas fa-check pr-2"></i>ยืนยัน
              </button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>


<?php include_once(__DIR__ . "/../layout/footer.php"); ?>
<script>
  $(document).on("click", "#email-show", function() {
    let checked = $(this).is(':checked');
    if (checked) {
      $('input[name="password"]').prop("type", "text");
    } else {
      $('input[name="password"]').prop("type", "password");
    }
  });

  $(document).on("click", "#default-show", function() {
    let checked = $(this).is(':checked');
    if (checked) {
      $('input[name="default"]').prop("type", "text");
    } else {
      $('input[name="default"]').prop("type", "password");
    }
  });
</script>