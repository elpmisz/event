<?php

namespace App\Classes;

use PDO;

class User
{
  private $dbcon;

  public function __construct()
  {
    $db = new Database();
    $this->dbcon = $db->getConnection();
  }

  public function hello()
  {
    return "USER CLASS";
  }

  public function user_count($data)
  {
    $sql = "SELECT COUNT(*) FROM event.login WHERE email = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchColumn();
  }

  public function user_status($data)
  {
    $sql = "SELECT status FROM event.login WHERE email = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return (!empty($row['status']) ? $row['status'] : "");
  }

  public function user_verify($data, $password)
  {
    $sql = "SELECT password FROM event.login WHERE email = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return  password_verify($password, $row['password']);
  }

  public function default_password()
  {
    $sql = "SELECT password_default FROM event.system WHERE id = 1";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return (!empty($row['password_default']) ? $row['password_default'] : "");
  }

  public function login_insert($data)
  {

    $sql = "INSERT INTO event.login(uuid,email,password) VALUES(uuid(),?,?)";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function user_insert($data)
  {

    $sql = "INSERT INTO event.user(login,firstname,lastname,contact) VALUES(?,?,?,?)";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function user_view_uuid($data)
  {
    $sql = "SELECT a.uuid,a.email,a.`level`,a.status,b.firstname,b.lastname,
    CONCAT(b.firstname,' ',b.lastname) fullname,b.contact
    FROM event.login a
    LEFT JOIN event.user b
    ON a.id = b.login
    WHERE a.uuid = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function user_view_email($data)
  {
    $sql = "SELECT a.uuid,a.email,a.`level`,a.status,b.firstname,b.lastname,
    CONCAT(b.firstname,' ',b.lastname) fullname,b.contact,b.contact
    FROM event.login a
    LEFT JOIN event.user b
    ON a.id = b.login
    WHERE a.email = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function forgot_password($data)
  {
    $sql = "UPDATE event.login SET
    password = ?
    WHERE email = ?";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function change_password($data)
  {
    $sql = "UPDATE event.login SET
    password = ?
    WHERE uuid = ?";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function admin_update($data)
  {
    $sql = "UPDATE event.login a
    LEFT JOIN event.user b
    ON a.id = b.login SET
    a.email = ?,
    a.level = ?,
    a.status = ?,
    a.updated = NOW(),
    b.firstname = ?,
    b.lastname = ?,
    b.contact = ?
    WHERE a.uuid = ?";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function user_update($data)
  {
    $sql = "UPDATE event.login a
    LEFT JOIN event.user b
    ON a.id = b.login SET
    a.updated = NOW(),
    b.firstname = ?,
    b.lastname = ?,
    b.contact = ?
    WHERE a.uuid = ?";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }


  public function directory_email()
  {
    $sql = "SELECT email FROM event.directory_request GROUP BY email";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function user_data()
  {
    $sql = "SELECT COUNT(*) FROM event.login";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    $total = $stmt->fetchColumn();

    $column = ["a.status", "a.level", "a.email", "b.firstname", "b.lastname", "b.contact"];

    $keyword = (isset($_POST['search']['value']) ? trim($_POST['search']['value']) : '');
    $filter_order = (isset($_POST['order']) ? $_POST['order'] : "");
    $order_column = (isset($_POST['order']['0']['column']) ? $_POST['order']['0']['column'] : "");
    $order_dir = (isset($_POST['order']['0']['dir']) ? $_POST['order']['0']['dir'] : "");
    $limit_start = (isset($_POST['start']) ? $_POST['start'] : "");
    $limit_length = (isset($_POST['length']) ? $_POST['length'] : "");
    $draw = (isset($_POST['draw']) ? $_POST['draw'] : "");

    $sql = "SELECT a.uuid,a.email,b.firstname,b.lastname,b.contact,
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
    (
      CASE
        WHEN a.level = 1 THEN 'ผู้ใช้งาน'
        WHEN a.level = 9 THEN 'ผู้ดูแลระบบ'
        ELSE NULL
      END
    ) level_name,
    (
      CASE
        WHEN a.level = 1 THEN 'info'
        WHEN a.level = 9 THEN 'primary'
        ELSE NULL
      END
    ) level_color
    FROM event.login a
    LEFT JOIN event.user b
    ON a.id = b.login
    WHERE a.id != '' ";

    if (!empty($keyword)) {
      $sql .= " AND (a.email LIKE '%{$keyword}%' OR b.firstname LIKE '%{$keyword}%' OR b.lastname LIKE '%{$keyword}%' OR b.contact LIKE '%{$keyword}%') ";
    }

    if ($filter_order) {
      $sql .= " ORDER BY {$column[$order_column]} {$order_dir} ";
    } else {
      $sql .= " ORDER BY a.status ASC, a.level DESC, b.firstname ASC ";
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
      $status = "<a href='/user/edit/{$row['uuid']}' class='badge badge-{$row['status_color']} font-weight-light'>{$row['status_name']}</a>";
      $level = "<a href='javascript:void(0)' class='badge badge-{$row['level_color']} font-weight-light'>{$row['level_name']}</a>";
      $data[] = [
        $status,
        $level,
        $row['email'],
        ucfirst($row['firstname']),
        ucfirst($row['lastname']),
        $row['contact']
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
