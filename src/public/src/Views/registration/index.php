<?php
$menu = "service";
$page = "service-registration";
include_once(__DIR__ . "/../layout/header.php");
?>
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow">
      <div class="card-header">
        <h4 class="text-center">REGISTRATION</h4>
      </div>
      <div class="card-body">
        <div class="row justify-content-end mb-2">
          <div class="col-xl-3 mb-2">
            <button class="btn btn-info btn-sm btn-block" data-toggle="modal" data-target="#import-modal">
              <i class="fas fa-upload pr-2"></i>นำข้อมูลเข้า
            </button>
          </div>
          <div class="col-xl-3 mb-2">
            <a href="/registration/export" class="btn btn-success btn-sm btn-block">
              <i class="fas fa-download pr-2"></i>นำข้อมูลออก
            </a>
          </div>
          <div class="col-xl-3 mb-2">
            <a href="/registration/create" class="btn btn-primary btn-sm btn-block">
              <i class="fas fa-plus pr-2"></i>เพิ่ม
            </a>
          </div>
        </div>
        <div class="row justify-content-end mb-2">
          <div class="col-xl-3 mb-2">
            <select class="form-control form-control-sm type-select"></select>
          </div>
          <div class="col-xl-3 mb-2">
            <select class="form-control form-control-sm country-select"></select>
          </div>
          <div class="col-xl-3 mb-2">
            <select class="form-control form-control-sm event-select"></select>
          </div>
          <div class="col-xl-3 mb-2">
            <select class="form-control form-control-sm package-select"></select>
          </div>
        </div>

        <div class="row mb-2">
          <div class="col-xl-12">
            <div>
              <table class="table table-bordered table-hover data">
                <thead>
                  <tr>
                    <th width="10%">#</th>
                    <th width="10%">CODE</th>
                    <th width="10%">CUSTOMER</th>
                    <th width="10%">TYPE</th>
                    <th width="10%">COUNTRY</th>
                    <th width="30%">EVENT</th>
                    <th width="10%">PACKAGE</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="import-modal" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <form action="/registration/import" method="POST" class="needs-validation import" novalidate enctype="multipart/form-data">
          <div class="row mb-2">
            <label class="col-xl-4 col-form-label text-right">เอกสาร</label>
            <div class="col-xl-8">
              <input type="file" class="form-control form-control-sm" name="file" required>
              <div class="invalid-feedback">
                กรุณาเลือกเอกสาร!
              </div>
            </div>
          </div>
          <div class="row justify-content-center mb-2">
            <div class="col-xl-4 mb-2">
              <button type="submit" class="btn btn-success btn-sm btn-block btn-submit">
                <i class="fas fa-check pr-2"></i>ยืนยัน
              </button>
            </div>
            <div class="col-xl-4 mb-2">
              <button class="btn btn-danger btn-sm btn-block" data-dismiss="modal">
                <i class="fa fa-times mr-2"></i>ปิด
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="process-modal" data-backdrop="static">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-body">
        <h1 class="text-center"><span class="pr-5">Processing...</span><i class="fas fa-spinner fa-pulse"></i></h1>
      </div>
    </div>
  </div>
</div>


<?php include_once(__DIR__ . "/../layout/footer.php"); ?>
<script>
  filter_datatable();

  $(document).on("change", ".type-select, .country-select, .package-select", function() {
    let type = ($(".type-select").val() ? $(".type-select").val() : "");
    let country = ($(".country-select").val() ? $(".country-select").val() : "");
    let package = ($(".package-select").val() ? $(".package-select").val() : "");
    if (type || country || package) {
      $(".data").DataTable().destroy();
      filter_datatable(type, country, package);
    } else {
      $(".data").DataTable().destroy();
      filter_datatable();
    }
  });

  function filter_datatable(type, country, package) {
    let datatable = $(".data").DataTable({
      scrollX: true,
      serverSide: true,
      searching: true,
      order: [],
      ajax: {
        url: "/registration/data",
        type: "POST",
        data: {
          type: type,
          country: country,
          package: package
        }
      },
      columnDefs: [{
        targets: [0, 1, 6],
        className: "text-center",
      }]
    });
  };

  $(document).on("click", ".btn-delete", function(e) {
    let uuid = ($(this).prop("id") ? $(this).prop("id") : "");

    e.prregistrationDefault();
    Swal.fire({
      title: "ยืนยันที่จะทำรายการ?",
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "ยืนยัน",
      cancelButtonText: "ปิด",
    }).then((result) => {
      if (result.value) {
        axios.post("/registration/delete", {
          uuid: uuid
        }).then((res) => {
          let result = res.data;
          if (parseInt(result) === 200) {
            location.reload()
          } else {
            location.reload()
          }
        }).catch((error) => {
          console.log(error);
        });
      } else {
        return false;
      }
    })
  });

  $("#import-modal").on("hidden.bs.modal", function() {
    $(this).find("form")[0].reset();
  })

  $(document).on("change", "input[name='file']", function() {
    let fileSize = ($(this)[0].files[0].size) / (1024 * 1024);
    let fileExt = $(this).val().split(".").pop().toLowerCase();
    let fileAllow = ["xls", "xlsx", "csv"];
    let convFileSize = fileSize.toFixed(2);
    if (convFileSize > 10) {
      Swal.fire({
        icon: "error",
        title: "LIMIT 10MB!",
      })
      $(this).val("");
    }

    if ($.inArray(fileExt, fileAllow) == -1) {
      Swal.fire({
        icon: "error",
        title: "เฉพาะไฟล์ XLS XLSX CSV!",
      })
      $(this).val("");
    }
  });

  $(document).on("submit", ".import", function() {
    $("#import-modal").modal("hide");
    $("#process-modal").modal("show");
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
</script>