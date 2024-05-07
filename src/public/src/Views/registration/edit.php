<?php
$menu = "service";
$page = "service-registration";
include_once(__DIR__ . "/../layout/header.php");

$param = (isset($params) ? explode("/", $params) : die(header("Location: /error")));
$uuid = (isset($param[0]) ? $param[0] : die(header("Location: /error")));

use App\Classes\Registration;

$REGISTRATION = new Registration();
$row = $REGISTRATION->registration_view([$uuid]);
$items = $REGISTRATION->item_view([$uuid]);
$id = (!empty($row['id']) ? $row['id'] : "");
$uuid = (!empty($row['uuid']) ? $row['uuid'] : "");
$event = (!empty($row['event']) ? $row['event'] : "");
$event_name = (!empty($row['event_name']) ? $row['event_name'] : "");
$type = (!empty($row['type']) ? $row['type'] : "");
$type_name = (!empty($row['type_name']) ? $row['type_name'] : "");
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

          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">EVENT</label>
            <div class="col-xl-4">
              <select class="form-control form-control-sm event-select" name="event_id" required>
                <?php
                if (!empty($event)) {
                  echo "<option value='{$event}'>{$event_name}</option>";
                }
                ?>
              </select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2 package-div">
            <label class="col-xl-2 offset-xl-2 col-form-label">PACKAGE</label>
            <div class="col-xl-4">
              <select class="form-control form-control-sm package-select" name="package_id" required>
                <?php
                if (!empty($package)) {
                  echo "<option value='{$package}'>{$package_name}</option>";
                }
                ?>
              </select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">ประเภทลูกค้า</label>
            <div class="col-xl-4">
              <select class="form-control form-control-sm type-select" name="type_id" required>
                <?php
                if (!empty($type)) {
                  echo "<option value='{$type}'>{$type_name}</option>";
                }
                ?>
              </select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>

          <div class="row justify-content-center mb-2">
            <div class="col-sm-10">
              <div class="table-responsive">
                <table class="table table-bordered table-sm item-table">
                  <thead>
                    <tr>
                      <th width="10%">#</th>
                      <th width="80%">ลูกค้า</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($items as $item) : ?>
                      <tr>
                        <td class="text-center">
                          <a href="javascript:void(0)" class="badge badge-info font-weight-light item-qrcode" id="<?php echo $item['id'] ?>">QR Code</a>
                          <a href="javascript:void(0)" class="badge badge-danger font-weight-light item-delete" id="<?php echo $item['id'] ?>">ลบ</a>
                          <input type="hidden" class="form-control form-control-sm text-center" name="item__id[]" value="<?php echo $item['id'] ?>" readonly>
                        </td>
                        <td class="text-left">
                          <?php echo $item['fullname'] ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                    <tr class="item-tr">
                      <td class="text-center">
                        <button type="button" class="btn btn-sm btn-success item-increase">+</button>
                        <button type="button" class="btn btn-sm btn-danger item-decrease">-</button>
                      </td>
                      <td class="text-left">
                        <select class="form-control form-control-sm customer-select" name="customer_id" required></select>
                        <div class="invalid-feedback">
                          กรุณากรอกข้อมูล!
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
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
  $(".item-decrease").hide();
  $(document).on("click", ".item-increase", function() {
    $(".item-select").select2('destroy');
    let row = $(".item-tr:last");
    let clone = row.clone();
    clone.find("input, select, textarea, span").val("").empty();
    clone.find(".item-increase").hide();
    clone.find(".item-decrease").show();
    clone.find(".item-decrease").on("click", function() {
      $(this).closest("tr").remove();
    });
    row.after(clone);
    clone.show();

    $(".customer-select").select2({
      placeholder: "-- CUSTOMER --",
      allowClear: true,
      width: "100%",
      ajax: {
        url: "/registration/customer-select",
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
  });

  let event = ($(".event-select").val() ? $(".event-select").val() : "");
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
  }

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

  $(".customer-select").select2({
    placeholder: "-- CUSTOMER --",
    allowClear: true,
    width: "100%",
    ajax: {
      url: "/registration/customer-select",
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

  $(document).on("click", ".item-qrcode", function() {
    let id = $(this).prop("id");
    path = "/registration/qrcode-item/" + id;
    window.open(path);
  });
</script>