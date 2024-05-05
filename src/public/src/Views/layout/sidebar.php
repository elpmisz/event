<?php
$home = (isset($menu) && ($menu === "home") ? "show" : "");
$home_index = ($page === "home-index" ? 'class="active"' : "");

$service_menu = (isset($menu) && ($menu === "service") ? "show" : "");
$service_event = ($page === "service-event" ? 'class="active"' : "");

$user_menu = (isset($menu) && ($menu === "user") ? "show" : "");
$user_profile = ($page === "user-profile" ? 'class="active"' : "");
$user_change = ($page === "user-change" ? 'class="active"' : "");

$setting_menu = (isset($menu) && ($menu === "setting") ? "show" : "");
$setting_system = ($page === "setting-system" ? 'class="active"' : "");
$setting_user = ($page === "setting-user" ? 'class="active"' : "");
$setting_customer = ($page === "setting-customer" ? 'class="active"' : "");
$setting_customer_type = ($page === "setting-customer-type" ? 'class="active"' : "");
$setting_country = ($page === "setting-country" ? 'class="active"' : "");
?>
<nav id="sidebar">
  <ul class="list-unstyled <?php echo $home ?>">
    <li <?php echo $home_index ?>>
      <a href="/home">รายงาน</a>
    </li>
    <li>
      <a href="#user-menu" data-toggle="collapse" class="dropdown-toggle">ข้อมูลส่วนตัว</a>
      <ul class="collapse list-unstyled <?php echo $user_menu ?>" id="user-menu">
        <li <?php echo $user_profile ?>>
          <a href="/user/profile">
            <i class="fa fa-address-book pr-2"></i>
            รายละเอียด
          </a>
        </li>
        <li <?php echo $user_change ?>>
          <a href="/user/change">
            <i class="fa fa-key pr-2"></i>
            เปลี่ยนรหัสผ่าน
          </a>
        </li>
      </ul>
    </li>
    <li>
      <a href="#service-menu" data-toggle="collapse" class="dropdown-toggle">
        บริการ
      </a>
      <ul class="collapse list-unstyled <?php echo $service_menu ?>" id="service-menu">
        <li <?php echo $service_event ?>>
          <a href="/event">
            <i class="fa fa-bars pr-2"></i>
            EVENT
          </a>
        </li>
      </ul>
    </li>
    <?php if (intval($user['level']) === 9) : ?>
      <li>
        <a href="#setting-menu" data-toggle="collapse" class="dropdown-toggle">ตั้งค่า</a>
        <ul class="collapse list-unstyled <?php echo $setting_menu ?>" id="setting-menu">
          <li <?php echo $setting_system ?>>
            <a href="/system">
              <i class="fa fa-gear pr-2"></i>
              ตั้งค่าระบบ
            </a>
          </li>
          <li <?php echo $setting_user ?>>
            <a href="/user">
              <i class="fa fa-user-tie pr-2"></i>
              ผู้ใช้งาน
            </a>
          </li>
          <li <?php echo $setting_customer ?>>
            <a href="/customer">
              <i class="fa fa-users pr-2"></i>
              ข้อมูลลูกค้า
            </a>
          </li>
          <li <?php echo $setting_customer_type ?>>
            <a href="/customer-type">
              <i class="fa fa-user-cog pr-2"></i>
              ข้อมูลประเภทลูกค้า
            </a>
          </li>
          <li <?php echo $setting_country ?>>
            <a href="/country">
              <i class="fa fa-globe pr-2"></i>
              ข้อมูลประเทศ
            </a>
          </li>
        </ul>
      </li>
    <?php endif; ?>
  </ul>
</nav>