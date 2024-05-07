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
    $sql = "SELECT COUNT(*) FROM event.registration_request WHERE event = ? AND package = ? AND type = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchColumn();
  }

  public function registration_insert($data)
  {
    $sql = "INSERT INTO event.registration_request(`uuid`, `event`, `package`, `type`) VALUES (uuid(),?,?,?)";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function registration_view($data)
  {
    $sql = "SELECT a.id,a.uuid,a.`event`,c.`name` event_name,a.`type`,e.`name` type_name,a.package,d.`name` package_name,d.price,a.`status`,
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
    FROM event.registration_request a
    LEFT JOIN event.event_request c
    ON a.`event` = c.id
    LEFT JOIN event.event_item d
    ON a.package = d.id
    LEFT JOIN event.customer_type e
    ON a.`type` = e.id
    WHERE a.`uuid` = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetch();
  }

  public function item_count($data)
  {
    $sql = "SELECT COUNT(*) FROM event.registration_item WHERE registration_id = ? AND code = ? AND customer_id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchColumn();
  }

  public function item_insert($data)
  {
    $sql = "INSERT INTO event.registration_item(`registration_id`, `code`, `customer_id`) VALUES (?,?,?)";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function item_view($data)
  {
    $sql = "SELECT b.id,c.`name` event_name,f.`name` customer_name,g.name_en country_name,
    CONCAT(f.`name`, ' [',g.name_en,']') fullname,
    d.`name` package_name,d.price,d.text,
    e.`name` type_name
    FROM event.registration_request a
    LEFT JOIN event.registration_item b
    ON a.id = b.registration_id
    LEFT JOIN event.event_request c
    ON a.`event` = c.id
    LEFT JOIN event.event_item d
    ON a.package = d.id
    LEFT JOIN event.customer_type e
    ON a.`type` = e.id
    LEFT JOIN event.customer f
    ON b.customer_id = f.id
    LEFT JOIN event.country g
    ON f.country = g.id
    WHERE a.`uuid` = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchAll();
  }

  public function item_detail($data)
  {
    $sql = "SELECT b.id,b.code,c.`name` event_name,c.topic,c.date,
    f.`name` customer_name,f.email,f.company,
    g.name_en country_name,
    d.`name` package_name,d.price,d.text,
    e.`name` type_name
    FROM event.registration_request a
    LEFT JOIN event.registration_item b
    ON a.id = b.registration_id
    LEFT JOIN event.event_request c
    ON a.`event` = c.id
    LEFT JOIN event.event_item d
    ON a.package = d.id
    LEFT JOIN event.customer_type e
    ON a.`type` = e.id
    LEFT JOIN event.customer f
    ON b.customer_id = f.id
    LEFT JOIN event.country g
    ON f.country = g.id
    WHERE b.id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetch();
  }

  public function registration_id($data)
  {
    $sql = "SELECT id FROM event.registration_request WHERE event = ? AND package = ? AND type = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    $row = $stmt->fetch();
    return (!empty($row['id']) ? $row['id'] : "");
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

  public function type_id($data)
  {
    $sql = "SELECT id FROM event.customer_type WHERE name = ?";
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
    $sql = "SELECT a.id, a.`name` `text`
    FROM event.customer_type a
    WHERE a.`status` = 1 ";
    if (!empty($keyword)) {
      $sql .= " AND a.name LIKE '%{$keyword}%' ";
    }
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

  public function registration_data()
  {
    $sql = "SELECT COUNT(*) FROM event.registration_request WHERE status IN (1,2)";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    $total = $stmt->fetchColumn();

    $column = ["a.status", "a.name_th", "a.name_en", "a.code"];

    $keyword = (isset($_POST['search']['value']) ? trim($_POST['search']['value']) : '');
    $filter_order = (isset($_POST['order']) ? $_POST['order'] : "");
    $order_column = (isset($_POST['order']['0']['column']) ? $_POST['order']['0']['column'] : "");
    $order_dir = (isset($_POST['order']['0']['dir']) ? $_POST['order']['0']['dir'] : "");
    $limit_start = (isset($_POST['start']) ? $_POST['start'] : "");
    $limit_length = (isset($_POST['length']) ? $_POST['length'] : "");
    $draw = (isset($_POST['draw']) ? $_POST['draw'] : "");

    $sql = "SELECT a.id,a.uuid,c.`name` event_name,e.`name` type_name,d.`name` package_name,d.price,
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
    FROM event.registration_request a
    LEFT JOIN event.event_request c
    ON a.`event` = c.id
    LEFT JOIN event.event_item d
    ON a.package = d.id
    LEFT JOIN event.customer_type e
    ON a.`type` = e.id
    WHERE a.`status` IN (1,2) ";

    if (!empty($keyword)) {
      $sql .= " AND (c.name LIKE '%{$keyword}%' OR d.name LIKE '%{$keyword}%' OR e.name LIKE '%{$keyword}%') ";
    }

    if ($filter_order) {
      $sql .= " ORDER BY {$column[$order_column]} {$order_dir} ";
    } else {
      $sql .= " ORDER BY a.status ASC, c.name ASC, a.type ASC ";
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
      $status = "<a href='/registration/qrcode-report/{$row['uuid']}' class='badge badge-info font-weight-light' target='_blank'>QR Code</a> <a href='/registration/edit/{$row['uuid']}' class='badge badge-{$row['status_color']} font-weight-light'>{$row['status_name']}</a> <a href='javascript:void(0)' class='badge badge-danger font-weight-light btn-delete' id='{$row['uuid']}'>ลบ</a>";
      $data[] = [
        $status,
        $row['event_name'],
        $row['type_name'],
        $row['package_name'],
        $row['price'],
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
