<?php
$menu = "setting";
$page = "setting-user";
include_once(__DIR__ . "/../layout/header.php");

use App\Classes\User;

$param = (isset($params) ? explode("/", $params) : die(header("Location: /error")));
$uuid = (isset($param[0]) ? $param[0] : die(header("Location: /error")));

$USER = new User();

$row = $USER->user_view_uuid([$uuid]);
$uuid = (!empty($row['uuid']) ? $row['uuid'] : "");
$email = (!empty($row['email']) ? $row['email'] : "");
$firstname = (!empty($row['firstname']) ? $row['firstname'] : "");
$lastname = (!empty($row['lastname']) ? $row['lastname'] : "");
$contact = (!empty($row['contact']) ? $row['contact'] : "");
$user = (intval($row['level']) === 1 ? "checked" : "");
$admin = (intval($row['level']) === 9 ? "checked" : "");
$active = (intval($row['status']) === 1 ? "checked" : "");
$inactive = (intval($row['status']) === 2 ? "checked" : "");
?>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow">
      <div class="card-header">
        <h4 class="text-center">รายละเอียด</h4>
      </div>
      <div class="card-body">
        <form action="/user/update" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">

          <div class="row mb-2" style="display: none;">
            <label class="col-xl-2 offset-xl-2 col-form-label">UUID</label>
            <div class="col-xl-4">
              <input type="text" class="form-control form-control-sm" name="uuid" value="<?php echo $uuid ?>" readonly>
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
            <label class="col-xl-2 offset-xl-2 col-form-label">ชื่อ</label>
            <div class="col-xl-4">
              <input type="text" class="form-control form-control-sm" name="firstname" value="<?php echo $firstname ?>" required>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">นามสกุล</label>
            <div class="col-xl-4">
              <input type="text" class="form-control form-control-sm" name="lastname" value="<?php echo $lastname ?>" required>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">ติดต่อ</label>
            <div class="col-xl-4">
              <textarea class="form-control form-control-sm" name="contact" rows="4"><?php echo $contact ?></textarea>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">สิทธิ์</label>
            <div class="col-xl-8">
              <div class="row pb-2">
                <div class="col-xl-3">
                  <label class="form-check-label px-3">
                    <input class="form-check-input" type="radio" name="level" value="1" <?php echo $user ?> required>
                    <span class="text-info">ผู้ใช้งาน</span>
                  </label>
                </div>
                <div class="col-xl-3">
                  <label class="form-check-label px-3">
                    <input class="form-check-input" type="radio" name="level" value="9" <?php echo $admin ?> required>
                    <span class="text-primary">ผู้ดูแลระบบ</span>
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">สถานะ</label>
            <div class="col-xl-8">
              <div class="row pb-2">
                <div class="col-xl-3">
                  <label class="form-check-label px-3">
                    <input class="form-check-input" type="radio" name="status" value="1" <?php echo $active ?> required>
                    <span class="text-success">ใช้งาน</span>
                  </label>
                </div>
                <div class="col-xl-3">
                  <label class="form-check-label px-3">
                    <input class="form-check-input" type="radio" name="status" value="2" <?php echo $inactive ?> required>
                    <span class="text-danger">ระงับการใช้งาน</span>
                  </label>
                </div>
              </div>
            </div>
          </div>

          <div class="row justify-content-center mb-2">
            <div class="col-xl-3 mb-2">
              <button type="submit" class="btn btn-sm btn-success btn-block">
                <i class="fas fa-check pr-2"></i>ยืนยัน
              </button>
            </div>
            <div class="col-xl-3 mb-2">
              <a href="/user" class="btn btn-sm btn-danger btn-block">
                <i class="fa fa-arrow-left pr-2"></i>กลับ
              </a>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>


<?php include_once(__DIR__ . "/../layout/footer.php"); ?>