<?php

namespace App\Classes;

use PDO;
use PDOException;

class Database
{
  private $dbCon = null;
  private $dbStmt = null;
  private $dbHost = "database";
  private $dbUser = "root";
  private $dbPass = "P@ssw0rd#db";
  private $dbName = "event";
  private $dbChar = "utf8";
  private $dbOptions = [
    PDO::ATTR_PERSISTENT          => true,
    PDO::ATTR_ERRMODE             => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES    => false,
  ];
  private $dbError;

  public function __construct()
  {
    $dns = "mysql:host=" . $this->dbHost . ";dbname=" . $this->dbName . ";charset=" . $this->dbChar;
    try {
      $this->dbCon = new PDO($dns, $this->dbUser, $this->dbPass, $this->dbOptions);
    } catch (PDOException $e) {
      $this->dbError = "Failed to connect to DB: " . $e->getMessage();
      die($this->dbError);
    }
  }

  public function getConnection()
  {
    return $this->dbCon;
  }

  public function __destruct()
  {
    if ($this->dbStmt !== null) {
      $this->dbStmt = null;
    }
    if ($this->dbCon !== null) {
      $this->dbCon = null;
    }
  }
}
