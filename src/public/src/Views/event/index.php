<?php
$menu = "service";
$page = "service-event";
include_once(__DIR__ . "/../layout/header.php");
?>
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow">
      <div class="card-header">
        <h4 class="text-center">EVENT</h4>
      </div>
      <div class="card-body">
        <div class="row justify-content-end mb-2">
          <div class="col-xl-3 mb-2">
            <a href="/event/export" class="btn btn-success btn-sm btn-block">
              <i class="fas fa-download pr-2"></i>นำข้อมูลออก
            </a>
          </div>
          <div class="col-xl-3 mb-2">
            <a href="/event/create" class="btn btn-primary btn-sm btn-block">
              <i class="fas fa-plus pr-2"></i>เพิ่ม
            </a>
          </div>
        </div>

        <div class="row mb-2">
          <div class="col-xl-12">
            <div>
              <table class="table table-bordered table-hover data">
                <thead>
                  <tr>
                    <th width="10%">#</th>
                    <th width="30%">ชื่อ</th>
                    <th width="10%">วันที่</th>
                    <th width="30%">หัวข้อ</th>
                    <th width="20%">แพ็คเกจ</th>
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


<?php include_once(__DIR__ . "/../layout/footer.php"); ?>
<script>
  filter_datatable();

  function filter_datatable() {
    let datatable = $(".data").DataTable({
      scrollX: true,
      serverSide: true,
      searching: true,
      order: [],
      ajax: {
        url: "/event/data",
        type: "POST",
      },
      columnDefs: [{
        targets: [0],
        className: "text-center",
      }]
    });
  };

  $(document).on("click", ".btn-delete", function(e) {
    let uuid = ($(this).prop("id") ? $(this).prop("id") : "");

    e.preventDefault();
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
        axios.post("/event/delete", {
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
</script>