<?php

namespace App\Classes;

use PDO;

class Customer
{
  private $dbcon;

  public function __construct()
  {
    $db = new Database();
    $this->dbcon = $db->getConnection();
  }

  public function hello()
  {
    return "CUSTOMER CLASS";
  }

  public function customer_count($data)
  {
    $sql = "SELECT COUNT(*) FROM event.customer WHERE name = ? AND country = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchColumn();
  }

  public function country_id($data)
  {
    $sql = "SELECT id FROM event.country WHERE name_en = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    $row = $stmt->fetch();
    return (!empty($row['id']) ? $row['id'] : "");
  }

  public function customer_insert($data)
  {
    $sql = "INSERT INTO event.customer(`uuid`, `name`, `email`, `company`, `country`) VALUES (uuid(),?,?,?,?)";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function customer_view($data)
  {
    $sql = "SELECT a.id,a.`uuid`,a.`name`,a.email,a.company,a.country,CONCAT(b.name_th,' [',b.name_en,']') country_name,a.status
    FROM event.customer a
    LEFT JOIN event.country b
    ON a.country = b.id
    WHERE a.id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetch();
  }

  public function customer_update($data)
  {
    $sql = "UPDATE event.customer SET
    name = ?,
    email = ?,
    company = ?,
    country = ?,
    status = ?,
    updated = NOW()
    WHERE id = ?";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function customer_delete($data)
  {
    $sql = "UPDATE event.customer SET
    status = 0,
    updated = NOW()
    WHERE uuid = ?";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function customer_export()
  {
    $sql = "SELECT a.`name`,a.email, a.company,CONCAT(b.name_th,' [',b.name_en,']') country_name,
    (
      CASE
        WHEN a.status = 1 THEN 'ใช้งาน'
        WHEN a.status = 2 THEN 'ระงับการใช้งาน'
        ELSE NULL
      END
    ) status_name,
    DATE_FORMAT(a.created,'%d/%m/%Y, %H:%i น.') created
    FROM event.customer a
    LEFT JOIN event.country b
    ON a.country = b.id
    WHERE a.`status` IN (1,2)";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_NUM);
  }


  public function customer_data()
  {
    $sql = "SELECT COUNT(*) FROM event.customer WHERE status IN (1,2)";
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

    $sql = "SELECT a.id,a.`uuid`,a.`name`,a.email,a.country,
    CONCAT(b.name_th,' [',b.name_en,']') country_name,
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
    FROM event.customer a
    LEFT JOIN event.country b
    ON a.country = b.id
    WHERE a.`status` IN (1,2) ";

    if (!empty($keyword)) {
      $sql .= " AND (a.name LIKE '%{$keyword}%') ";
    }

    if ($filter_order) {
      $sql .= " ORDER BY {$column[$order_column]} {$order_dir} ";
    } else {
      $sql .= " ORDER BY a.status ASC, b.name_en ASC, a.name ASC ";
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
      $status = "<a href='/customer/edit/{$row['id']}' class='badge badge-{$row['status_color']} font-weight-light'>{$row['status_name']}</a> <a href='javascript:void(0)' class='badge badge-danger font-weight-light btn-delete' id='{$row['id']}'>ลบ</a>";
      $data[] = [
        $status,
        $row['name'],
        $row['email'],
        $row['country_name'],
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

  public function country_select($keyword)
  {
    $sql = "SELECT a.id,CONCAT(a.name_th,' [',a.name_en,']') `text`
    FROM event.country a
    WHERE a.`status` = 1 ";
    if (!empty($keyword)) {
      $sql .= " AND (a.name_th LIKE '%{$keyword}%' OR a.name_en LIKE '%{$keyword}%') ";
    }
    $sql .= " ORDER BY a.name_en ASC LIMIT 50";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function last_insert_id()
  {
    return $this->dbcon->lastInsertId();
  }
}
