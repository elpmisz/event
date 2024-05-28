<?php

namespace App\Classes;

use PDO;

class Registration
{
  private $dbcon;

  public function __construct()
  {
    $db = new Database();
    $this->dbcon = $db->getConnection();
  }

  public function hello()
  {
    return "REGISTRATION CLASS";
  }

  public function registration_count($data)
  {
    $sql = "SELECT COUNT(*) FROM event.registration WHERE code = ? AND user = ? AND type =? AND event = ? AND package = ? AND status = 1";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchColumn();
  }

  public function registration_insert($data)
  {
    $sql = "INSERT INTO event.registration(`uuid`, `code`, `user`, `type`, `event`, `package`) VALUES(uuid(),?,?,?,?,?)";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchColumn();
  }

  public function registration_update($data)
  {
    $sql = "UPDATE event.registration SET 
    code = ?,
    type = ?,
    package = ?,
    status = ?,
    updated = NOW()
    WHERE uuid = ?";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function registration_view($data)
  {
    $sql = "SELECT a.id,a.uuid,a.code,b.uuid user,b.`name` customer_name,b.country,c.name_en country_name,
    b.email,b.company,a.`type`,a.event,d.`name` event_name,a.package,e.`name` package_name,
    a.status,
    DATE_FORMAT(a.created, '%d/%m/%Y, %H:%i น.') created
    FROM event.registration a
    LEFT JOIN event.customer b
    ON a.`user` = b.id
    LEFT JOIN event.country c
    ON b.country = c.id
    LEFT JOIN event.event_request d
    ON a.`event` = d.id
    LEFT JOIN event.event_item e
    ON a.package = e.id
    WHERE a.uuid = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetch();
  }

  public function registration_export()
  {
    $sql = "SELECT a.code,b.`name` customer_name,c.name_en country_name,
    b.email,b.company,a.`type`,d.`name` event_name,e.`name` package_name
    FROM event.registration a
    LEFT JOIN event.customer b
    ON a.`user` = b.id
    LEFT JOIN event.country c
    ON b.country = c.id
    LEFT JOIN event.event_request d
    ON a.`event` = d.id
    LEFT JOIN event.event_item e
    ON a.package = e.id
    WHERE a.status IN (1,2)
    ORDER BY a.status ASC, a.code ASC, a.type ASC";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_NUM);
  }

  public function customer_id($data)
  {
    $sql = "SELECT id FROM event.customer WHERE name = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    $row = $stmt->fetch();
    return (!empty($row['id']) ? $row['id'] : "");
  }

  public function country_id($data)
  {
    $sql = "SELECT id FROM event.country WHERE name_en = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    $row = $stmt->fetch();
    return (!empty($row['id']) ? $row['id'] : "");
  }


  public function event_id($data)
  {
    $sql = "SELECT id FROM event.event_request WHERE name = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    $row = $stmt->fetch();
    return (!empty($row['id']) ? $row['id'] : "");
  }

  public function package_id($data)
  {
    $sql = "SELECT id FROM event.event_item WHERE event_id = ? AND name = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    $row = $stmt->fetch();
    return (!empty($row['id']) ? $row['id'] : "");
  }

  public function event_select($keyword)
  {
    $sql = "SELECT a.id,a.`name` `text`
    FROM event.event_request a
    WHERE a.`status` = 1 ";
    if (!empty($keyword)) {
      $sql .= " AND a.name LIKE '%{$keyword}%' ";
    }
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function package_select($keyword, $data)
  {
    $sql = "SELECT a.id,CONCAT('[',a.price,'] ',a.`name`) `text`
    FROM event.event_item a
    WHERE a.`status` = 1
    AND a.event_id = ? ";
    if (!empty($keyword)) {
      $sql .= " AND a.name LIKE '%{$keyword}%' ";
    }
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function type_select($keyword)
  {
    $sql = "SELECT a.type id, a.type text
    FROM event.registration a ";
    if (!empty($keyword)) {
      $sql .= " WHERE (a.type LIKE '%{$keyword}%') ";
    }
    $sql .= " GROUP BY a.type
    ORDER BY a.type ASC 
    LIMIT 10";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function customer_select($keyword)
  {
    $sql = "SELECT a.id,CONCAT(a.`name`,' [',b.name_en,']') `text`
    FROM event.customer a
    LEFT JOIN event.country b
    ON a.country = b.id
    WHERE a.`status` = 1 ";
    if (!empty($keyword)) {
      $sql .= " AND (a.name LIKE '%{$keyword}%' OR b.name_th LIKE '%{$keyword}%' OR b.name_en LIKE '%{$keyword}%') ";
    }
    $sql .= " ORDER BY a.name ASC LIMIT 50";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function registration_data($type, $country, $package)
  {
    $sql = "SELECT COUNT(*) FROM event.registration WHERE status IN (1,2)";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    $total = $stmt->fetchColumn();

    $column = ["a.status", "a.code", "b.name", "a.type", "c.name_en", "d.name", "e.name"];

    $keyword = (isset($_POST['search']['value']) ? trim($_POST['search']['value']) : '');
    $filter_order = (isset($_POST['order']) ? $_POST['order'] : "");
    $order_column = (isset($_POST['order']['0']['column']) ? $_POST['order']['0']['column'] : "");
    $order_dir = (isset($_POST['order']['0']['dir']) ? $_POST['order']['0']['dir'] : "");
    $limit_start = (isset($_POST['start']) ? $_POST['start'] : "");
    $limit_length = (isset($_POST['length']) ? $_POST['length'] : "");
    $draw = (isset($_POST['draw']) ? $_POST['draw'] : "");

    $sql = "SELECT a.uuid,a.code,b.`name` customer_name,c.name_en country_name,b.email,b.company,a.`type` type_name,
    d.`name` event_name,e.`name` package_name,
    (
      CASE
        WHEN a.status = 1 THEN 'ใช้งาน'
        WHEN a.status = 2 THEN 'ระงับการใช้งาน'
        ELSE NULL
      END
    ) status_name,
    (
      CASE
        WHEN a.status = 1 THEN 'success'
        WHEN a.status = 2 THEN 'danger'
        ELSE NULL
      END
    ) status_color,
    DATE_FORMAT(a.created, '%d/%m/%Y, %H:%i น.') created
    FROM event.registration a
    LEFT JOIN event.customer b
    ON a.`user` = b.id
    LEFT JOIN event.country c
    ON b.country = c.id
    LEFT JOIN event.event_request d
    ON a.`event` = d.id
    LEFT JOIN event.event_item e
    ON a.package = e.id
    WHERE a.`status` IN (1,2) ";

    if (!empty($keyword)) {
      $sql .= " AND (a.code LIKE '%{$keyword}%' OR b.name LIKE '%{$keyword}%' OR b.email LIKE '%{$keyword}%' OR c.name_th LIKE '%{$keyword}%' OR c.name_en LIKE '%{$keyword}%' OR b.email LIKE '%{$keyword}%' OR b.company LIKE '%{$keyword}%' OR a.type LIKE '%{$keyword}%' OR d.name LIKE '%{$keyword}%' OR e.name LIKE '%{$keyword}%') ";
    }

    if (!empty($type)) {
      $sql .= " AND a.type = '{$type}' ";
    }

    if (!empty($country)) {
      $sql .= " AND b.country = '{$country}' ";
    }

    if (!empty($package)) {
      $sql .= " AND a.package = '{$package}' ";
    }

    if ($filter_order) {
      $sql .= " ORDER BY {$column[$order_column]} {$order_dir} ";
    } else {
      $sql .= " ORDER BY a.status ASC, a.code ASC, a.type ASC ";
    }

    $sql2 = "";
    if ($limit_length) {
      $sql2 .= " LIMIT {$limit_start}, {$limit_length}";
    }

    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    $filter = $stmt->rowCount();
    $stmt = $this->dbcon->prepare($sql . $sql2);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = [];
    foreach ($result as $row) {
      $status = "<a href='/registration/qrcode-item/{$row['uuid']}' class='badge badge-info font-weight-light' target='_blank'>QR CODE</a>
      <a href='/registration/edit/{$row['uuid']}' class='badge badge-{$row['status_color']} font-weight-light'>{$row['status_name']}</a> 
      <a href='javascript:void(0)' class='badge badge-danger font-weight-light btn-delete' id='{$row['uuid']}'>ลบ</a>";
      $data[] = [
        $status,
        $row['code'],
        $row['customer_name'],
        $row['type_name'],
        $row['country_name'],
        $row['event_name'],
        $row['package_name'],
      ];
    }

    $output = [
      "draw"    => $draw,
      "recordsTotal"  =>  $total,
      "recordsFiltered" => $filter,
      "data"    => $data
    ];
    return $output;
  }

  public function last_insert_id()
  {
    return $this->dbcon->lastInsertId();
  }
}
