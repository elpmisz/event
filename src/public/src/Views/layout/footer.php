</div>
</div>

<?php if (!empty($_SESSION['alert']) && !empty($_SESSION['text'])) : ?>
  <div style="position: absolute; top: 120px; right: 0; width: 400px;">
    <div class="toast hide" data-delay="3000">
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
<script src="/vendor/datatables/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="/vendor/datatables/datatables/media/js/dataTables.bootstrap4.min.js"></script>
<script src="/vendor/select2/select2/dist/js/select2.min.js"></script>
<script src="/vendor/moment/moment/min/moment.min.js"></script>
<script src="/vendor/pnikolov/bootstrap-daterangepicker/js/daterangepicker.min.js"></script>
<script src="/styles/js/sweetalert2.all.min.js"></script>
<script src="/styles/js/axios.min.js"></script>
<script src="/styles/js/main.js"></script>
<script src="/styles/js/chart.min.js"></script>
<script src="/styles/js/chartjs-plugin-datalabels.js"></script>
<script>
  $(".toast").toast("show");

  $(document).on("click", "#sidebarCollapse", function() {
    $("#sidebar, #content").toggleClass("active");
    $(".collapse.in").toggleClass("in");
  });

  $(".dropdown").hover(function() {
    $(".dropdown-menu", this).stop().fadeIn(500);
  }, function() {
    $(".dropdown-menu", this).stop().fadeOut(500);
  });

  $(document).on("click", ".logout-btn", function(e) {
    e.preventDefault();
    Swal.fire({
      title: "ยืนยันที่จะออกจากระบบ?",
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "ยืนยัน",
      cancelButtonText: "ปิด",
    }).then((result) => {
      if (result.value) {
        window.location.href = "/logout";
      } else {
        return false;
      }
    })
  });
</script>
</body>

</html>