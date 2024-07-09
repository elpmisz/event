<?php
$menu = "service";
$page = "service-registration";
include_once(__DIR__ . "/../layout/header.php");

$param = (isset($params) ? explode("/", $params) : die(header("Location: /error")));
$uuid = (isset($param[0]) ? $param[0] : die(header("Location: /error")));

use App\Classes\Registration;

$REGISTRATION = new Registration();
$row = $REGISTRATION->registration_view([$uuid]);
$id = (!empty($row['id']) ? $row['id'] : "");
$uuid = (!empty($row['uuid']) ? $row['uuid'] : "");
$user = (!empty($row['user']) ? $row['user'] : "");
$code = (!empty($row['code']) ? $row['code'] : "");
$customer_name = (!empty($row['customer_name']) ? $row['customer_name'] : "");
$country = (!empty($row['country']) ? $row['country'] : "");
$country_name = (!empty($row['country_name']) ? $row['country_name'] : "");
$email = (!empty($row['email']) ? $row['email'] : "");
$company = (!empty($row['company']) ? $row['company'] : "");
$type = (!empty($row['type']) ? $row['type'] : "");
$event = (!empty($row['event']) ? $row['event'] : "");
$event_name = (!empty($row['event_name']) ? $row['event_name'] : "");
$package = (!empty($row['package']) ? $row['package'] : "");
$package_name = (!empty($row['package_name']) ? $row['package_name'] : "");
$active = (!empty($row['status']) && intval($row['status']) === 1 ? "checked" : "");
$inactive = (!empty($row['status']) && intval($row['status']) === 2 ? "checked" : "");
?>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow">
      <div class="card-header">
        <h4 class="text-center">รายละเอียด</h4>
      </div>
      <div class="card-body">
        <form action="/registration/edit" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
          <div class="row mb-2" style="display: none;">
            <label class="col-xl-2 offset-xl-2 col-form-label">ID</label>
            <div class="col-xl-4">
              <input type="text" class="form-control form-control-sm" name="id" value="<?php echo $id ?>" readonly>
            </div>
          </div>
          <div class="row mb-2" style="display: none;">
            <label class="col-xl-2 offset-xl-2 col-form-label">UUID</label>
            <div class="col-xl-4">
              <input type="text" class="form-control form-control-sm" name="uuid" value="<?php echo $uuid ?>" readonly>
            </div>
          </div>
          <div class="row mb-2" style="display: none;">
            <label class="col-xl-2 offset-xl-2 col-form-label">USER</label>
            <div class="col-xl-4">
              <input type="text" class="form-control form-control-sm" name="user" value="<?php echo $user ?>" readonly>
            </div>
          </div>
          <div class="row mb-2" style="display: none;">
            <label class="col-xl-2 offset-xl-2 col-form-label">EVENT</label>
            <div class="col-xl-4">
              <input type="text" class="form-control form-control-sm" name="event" value="<?php echo $event ?>" readonly>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">CODE</label>
            <div class="col-xl-4">
              <input type="text" class="form-control form-control-sm" name="code" value="<?php echo $code ?>" required>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">CUSTOMER</label>
            <div class="col-xl-4">
              <input type="text" class="form-control form-control-sm" name="name" value="<?php echo $customer_name ?>" required>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">E-Mail</label>
            <div class="col-xl-4">
              <input type="email" class="form-control form-control-sm" name="email" value="<?php echo $email ?>">
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">COMPANY</label>
            <div class="col-xl-6">
              <input type="text" class="form-control form-control-sm" name="company" value="<?php echo $company ?>">
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">TYPE</label>
            <div class="col-xl-4">
              <select class="form-control form-control-sm type-select" name="type" required>
                <?php echo "<option value='{$type}'>{$type}</option>"; ?>
              </select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">COUNTRY</label>
            <div class="col-xl-4">
              <select class="form-control form-control-sm country-select" name="country">
                <?php echo "<option value='{$country}'>{$country_name}</option>"; ?>
              </select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2" style="display: none;">
            <label class="col-xl-2 offset-xl-2 col-form-label">EVENT</label>
            <div class="col-xl-2">
              <input type="text" class="form-control form-control-sm event-id" value="<?php echo $event ?>" readonly>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">EVENT</label>
            <div class="col-xl-6">
              <input type="text" class="form-control form-control-sm" value="<?php echo $event_name ?>" readonly>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2 package-div">
            <label class="col-xl-2 offset-xl-2 col-form-label">PACKAGE</label>
            <div class="col-xl-4">
              <select class="form-control form-control-sm package-select" name="package_id" required>
                <?php echo "<option value='{$package}'>{$package_name}</option>"; ?>
              </select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>

          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2">สถานะ</label>
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
              <a href="/registration" class="btn btn-sm btn-danger btn-block">
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
<script>
  $(".type-select").select2({
    placeholder: "-- ประเภท --",
    allowClear: true,
    width: "100%",
    ajax: {
      url: "/registration/type-select",
      method: "POST",
      dataType: "json",
      delay: 100,
      processResults: function(data) {
        return {
          results: data
        };
      },
      cache: true
    }
  });

  $(".country-select").select2({
    placeholder: "-- ประเทศ --",
    allowClear: true,
    width: "100%",
    ajax: {
      url: "/customer/country-select",
      method: "POST",
      dataType: "json",
      delay: 100,
      processResults: function(data) {
        return {
          results: data
        };
      },
      cache: true
    }
  });

  let event = ($(".event-id").val() ? $(".event-id").val() : "");
  $(".package-select").select2({
    placeholder: "-- PACKAGE --",
    allowClear: true,
    width: "100%",
    ajax: {
      url: "/registration/package-select",
      method: "POST",
      dataType: "json",
      delay: 100,
      data: function(params) {
        return {
          q: params.term,
          event: event
        };
      },
      processResults: function(data) {
        return {
          results: data
        };
      },
      cache: true
    }
  });
</script>