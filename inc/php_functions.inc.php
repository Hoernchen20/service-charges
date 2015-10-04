<?php
function strtodate ($str_date) {
  if (empty($str_date)) {
    return 'NULL';
  } else if ( (6 < strlen($str_date)) && (strlen($str_date) < 11) ) {
    list($day, $month, $year) = explode(".", $str_date);
    if (checkdate($month, $day, $year)) {
      return "'" . $year . '-' . $month . '-' . $day . "'";
    } else if ($day == 0) {
      $day = 1;
      if (checkdate($month, $day, $year)) {
        $day = 0;
        return "'" . $year . '-' . $month . '-' . $day . "'";
      }
    } else {
      return false;
    }
  } else {
    return false;
  }
}
?>
