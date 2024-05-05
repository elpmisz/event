<?php
$menu = "user";
$page = "user-change";
include_once(__DIR__ . "/../layout/header.php");

use App\Classes\User;

$USER = new User();

$row = $USER->user_view_email([$email]);
$uuid = (!empty($row['uuid']) ? $row['uuid'] : "");
?>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow">
      <div class="card-header">
        <h4 class="text-center">เปลี่ยนรหัสผ่าน</h4>
      </div>
      <div class="card-body">
        <form action="/user/change" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">

          <div class="row mb-2" style="display: none;">
            <label class="col-xl-2 offset-xl-2 col-form-label">UUID</label>
            <div class="col-xl-4">
              <input type="text" class="form-control form-control-sm" name="uuid" value="<?php echo $uuid ?>" readonly>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">รหัสผ่านใหม่</label>
            <div class="col-xl-4">
              <input type="password" class="form-control form-control-sm" name="password" required>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <label class="col-xl-4 offset-xl-4 h5 message-password"></label>
          <div class="row mb-2">
            <span class="col-xl-2 offset-xl-2">ยืนยัน รหัสผ่านใหม่</span>
            <div class="col-xl-4">
              <input type="password" class="form-control form-control-sm" name="password2" required>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
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
  $(".message-password").hide();
  $(document).on("keyup", "input[name='password2']", function() {
    let password = $("input[name='password']").val();
    let password2 = $(this).val();
    $(".message-password").show();

    if (password === password2) {
      $(".message-password").text("รหัสผ่าน ตรงกัน!");
      $(".message-password").addClass("text-success");
      $(".message-password").removeClass("text-danger");
      $("button[type='submit']").prop("disabled", false);
    } else {
      $(".message-password").text("รหัสผ่าน ไม่ตรงกัน!");
      $(".message-password").addClass("text-danger");
      $(".message-password").removeClass("text-success");
      $("button[type='submit']").prop("disabled", true);
    }
  });
</script>