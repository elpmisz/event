<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Error</title>
  <link rel="stylesheet" href="/vendor/twitter/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/styles/css/style.css">
</head>

<body>

  <div class="container">
    <div class="row justify-content-center my-5">
      <div class="col-12">
        <h1 class="text-center text-danger display-1">404</h1>
        <h1 class="text-center text-danger display-1">ไม่พบข้อมูล!!!</h1>
      </div>
    </div>
  </div>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>
</body>

</html>
<script src="/vendor/components/jquery/jquery.min.js"></script>
<script>
  $(function() {
    setTimeout(function() {
      window.location.replace("/");
    }, 5000);
  });
</script>