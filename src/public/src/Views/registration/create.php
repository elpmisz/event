<?php
$menu = "service";
$page = "service-registration";
include_once(__DIR__ . "/../layout/header.php");
?>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow">
      <div class="card-header">
        <h4 class="text-center">เพิ่ม</h4>
      </div>
      <div class="card-body">
        <form action="/registration/create" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">CODE</label>
            <div class="col-xl-4">
              <input type="text" class="form-control form-control-sm" name="code" required>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">CUSTOMER</label>
            <div class="col-xl-4">
              <input type="text" class="form-control form-control-sm" name="name" required>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">E-Mail</label>
            <div class="col-xl-4">
              <input type="email" class="form-control form-control-sm" name="email">
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">COMPANY</label>
            <div class="col-xl-6">
              <input type="text" class="form-control form-control-sm" name="company">
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">TYPE</label>
            <div class="col-xl-4">
              <select class="form-control form-control-sm type-select" name="type" required></select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">COUNTRY</label>
            <div class="col-xl-4">
              <select class="form-control form-control-sm country-select" name="country" required></select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">EVENT</label>
            <div class="col-xl-4">
              <select class="form-control form-control-sm event-select" name="event_id" required></select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2 package-div">
            <label class="col-xl-2 offset-xl-2 col-form-label">PACKAGE</label>
            <div class="col-xl-4">
              <select class="form-control form-control-sm package-select" name="package_id" required></select>
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

  $(".package-div").hide();
  $(document).on("change", ".event-select", function() {
    let event = ($(this).val() ? $(this).val() : "");
    if (event) {
      $(".package-div").show();
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
    } else {
      $(".package-div").hide();
    }
  });

  $(".event-select").select2({
    placeholder: "-- EVENT --",
    allowClear: true,
    width: "100%",
    ajax: {
      url: "/registration/event-select",
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
</script>