<?php

namespace App\Classes;

class Validation
{
  public function alert($alert, $text, $url = null)
  {
    $_SESSION['alert'] = $alert;
    $_SESSION['text'] = $text;
    if (!empty($url)) {
      die(header("Location: {$url}"));
    }
  }

  public function input($data)
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  public function int($data)
  {
    $data = filter_var($this->input($data), FILTER_VALIDATE_INT);
    return $data;
  }

  public function bool($data)
  {
    $data = filter_var($this->input($data), FILTER_VALIDATE_BOOLEAN);
    return $data;
  }

  public function url($data)
  {
    $data = filter_var($this->input($data), FILTER_VALIDATE_URL);
    return $data;
  }

  public function email($data)
  {
    $regex = "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix";
    $data = strtolower($data);
    $data = filter_var($this->input($data), FILTER_SANITIZE_EMAIL);
    $data = preg_match($regex, $data);
    return $data;
  }

  public function password($data)
  {
    $data = preg_match("@[A-Z]@", $data); // Upper
    $data = preg_match("@[a-z]@", $data); // Lower
    $data = preg_match("@[0-9]@", $data); // Number
    $data = preg_match("@[^\w]@", $data); // Special
    return $data;
  }

  public function month()
  {
    $data = ["January", "February", "March", "April", "May ", "June", "July", "August", "September", "October", "November", "December"];
    return $data;
  }

  public function month_name($month)
  {
    $data = ["", "January", "February", "March", "April", "May ", "June", "July", "August", "September", "October", "November", "December"];
    return $data[$month];
  }

  public function month_th()
  {
    $data = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
    return $data;
  }

  public function month_th_name($month)
  {
    $data = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
    return $data[$month];
  }

  public function input_type()
  {
    $data = ["TEXT", "NUMBER", "SELECT", "DATE"];
    return $data;
  }

  public function input_type_name($type)
  {
    $data = ["", "TEXT", "NUMBER", "SELECT", "DATE"];
    return $data[$type];
  }

  public function letters($data)
  {
    $letters = ["", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK", "AL", "AM", "AN", "AO", "AP", "AQ", "AR", "AS", "AT", "AU", "AV", "AW", "AX", "AY", "AZ", "BA", "BB", "BC", "BD", "BE", "BF", "BG", "BH", "BI", "BJ", "BK", "BL", "BM", "BN", "BO", "BP", "BQ", "BR", "BS", "BT", "BU", "BV", "BW", "BX", "BY", "BZ", "CA", "CB", "CC", "CD", "CE", "CF", "CG", "CH", "CI", "CJ", "CK", "CL", "CM", "CN", "CO", "CP", "CQ", "CR", "CS", "CT", "CU", "CV", "CW", "CX", "CY", "CZ", "DA", "DB", "DC", "DD", "DE", "DF", "DG", "DH", "DI", "DJ", "DK", "DL", "DM", "DN", "DO", "DP", "DQ", "DR", "DS", "DT", "DU", "DV", "DW", "DX", "DY", "DZ", "EA", "EB", "EC", "ED", "EE", "EF", "EG", "EH", "EI", "EJ", "EK", "EL", "EM", "EN", "EO", "EP", "EQ", "ER", "ES", "ET", "EU", "EV", "EW", "EX", "EY", "EZ"];
    return $letters[$data];
  }

  public function logout()
  {
    setcookie("jwt", null, -1);
  }

  public function image_upload($tmp, $path)
  {
    $imageInfo   = (isset($tmp) ? getimagesize($tmp) : '');
    $imageWidth   = 1200;
    $imageHeight = (isset($imageInfo) ? round($imageWidth * $imageInfo[1] / $imageInfo[0]) : '');
    $imageType    = $imageInfo[2];

    if ($imageType === IMAGETYPE_PNG) {
      $imageResource = imagecreatefrompng($tmp);
      $imageX = imagesx($imageResource);
      $imageY = imagesy($imageResource);
      $imageTarget = imagecreatetruecolor($imageWidth, $imageHeight);
      imagecopyresampled($imageTarget, $imageResource, 0, 0, 0, 0, $imageWidth, $imageHeight, $imageX, $imageY);
      imagepng($imageTarget, $path);
    } elseif ($imageType === IMAGETYPE_JPEG) {
      $imageResource = imagecreatefromjpeg($tmp);
      $imageX = imagesx($imageResource);
      $imageY = imagesy($imageResource);
      $imageTarget = imagecreatetruecolor($imageWidth, $imageHeight);
      imagecopyresampled($imageTarget, $imageResource, 0, 0, 0, 0, $imageWidth, $imageHeight, $imageX, $imageY);
      imagejpeg($imageTarget, $path);
    } else {
      $imageResource = imagecreatefrompng($tmp);
      $imageX = imagesx($imageResource);
      $imageY = imagesy($imageResource);
      $imageTarget = imagecreatetruecolor($imageWidth, $imageHeight);
      imagecopyresampled($imageTarget, $imageResource, 0, 0, 0, 0, $imageWidth, $imageHeight, $imageX, $imageY);
      imagewebp($imageTarget, $path);
    }
  }

  public function file_unlink($data)
  {
    return unlink($data);
  }

  function line_notify($token, $text)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://notify-api.line.me/api/notify");
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "message={$text}");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $headers = array("Content-type: application/x-www-form-urlencoded", "Authorization: Bearer {$token}",);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
  }

  public function assign_rand_value($num)
  {

    // accepts 1 - 36
    switch ($num) {
      case "1":
        $rand_value = "a";
        break;
      case "2":
        $rand_value = "b";
        break;
      case "3":
        $rand_value = "c";
        break;
      case "4":
        $rand_value = "d";
        break;
      case "5":
        $rand_value = "e";
        break;
      case "6":
        $rand_value = "f";
        break;
      case "7":
        $rand_value = "g";
        break;
      case "8":
        $rand_value = "h";
        break;
      case "9":
        $rand_value = "i";
        break;
      case "10":
        $rand_value = "j";
        break;
      case "11":
        $rand_value = "k";
        break;
      case "12":
        $rand_value = "l";
        break;
      case "13":
        $rand_value = "m";
        break;
      case "14":
        $rand_value = "n";
        break;
      case "15":
        $rand_value = "o";
        break;
      case "16":
        $rand_value = "p";
        break;
      case "17":
        $rand_value = "q";
        break;
      case "18":
        $rand_value = "r";
        break;
      case "19":
        $rand_value = "s";
        break;
      case "20":
        $rand_value = "t";
        break;
      case "21":
        $rand_value = "u";
        break;
      case "22":
        $rand_value = "v";
        break;
      case "23":
        $rand_value = "w";
        break;
      case "24":
        $rand_value = "x";
        break;
      case "25":
        $rand_value = "y";
        break;
      case "26":
        $rand_value = "z";
        break;
      case "27":
        $rand_value = "0";
        break;
      case "28":
        $rand_value = "1";
        break;
      case "29":
        $rand_value = "2";
        break;
      case "30":
        $rand_value = "3";
        break;
      case "31":
        $rand_value = "4";
        break;
      case "32":
        $rand_value = "5";
        break;
      case "33":
        $rand_value = "6";
        break;
      case "34":
        $rand_value = "7";
        break;
      case "35":
        $rand_value = "8";
        break;
      case "36":
        $rand_value = "9";
        break;
    }
    return $rand_value;
  }

  public function get_rand_alphanumeric($length)
  {
    if ($length > 0) {
      $rand_id = "";
      for ($i = 1; $i <= $length; $i++) {
        mt_srand((float)microtime() * 1000000);
        $num = mt_rand(1, 36);
        $rand_id .= $this->assign_rand_value($num);
      }
    }
    return strtoupper($rand_id);
  }

  public function get_rand_numbers($length)
  {
    if ($length > 0) {
      $rand_id = "";
      for ($i = 1; $i <= $length; $i++) {
        mt_srand((float)microtime() * 1000000);
        $num = mt_rand(27, 36);
        $rand_id .= $this->assign_rand_value($num);
      }
    }
    return strtoupper($rand_id);
  }

  public function get_rand_letters($length)
  {
    if ($length > 0) {
      $rand_id = "";
      for ($i = 1; $i <= $length; $i++) {
        mt_srand((float)microtime() * 1000000);
        $num = mt_rand(1, 26);
        $rand_id .= $this->assign_rand_value($num);
      }
    }
    return strtoupper($rand_id);
  }

  public function forgot_email($password)
  {
    $text = "<table border='1' cellpading='0' cellspacing='0' style='border-collapse: collapse;' width='100%'>
        <tr>
          <td style='text-align: center;' colspan='4'>
            <h1>FORGOT PASSWORD</h1>
          </td>
        </tr>
        <tr>
          <td width='40%'><h4>NEW PASSWORD</h4></td>
          <td width='60%'><h4>{$password}</h4></td>
        </tr>
        <tr>
          <td width='40%'><h4>WEBSITE</h4></td>
          <td width='60%'><a href='http://local.event.com/' target='_blank'><strong>ENTER TO SITE</strong></a>
          </td>
        </tr>
        <tr>
          
        </tr>
      </table>";
    return $text;
  }
}
