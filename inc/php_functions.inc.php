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

function ReturnMonthName ($number) {
  $month = array('Januar', 'Februar', 'März', 'April','Mai','Juni','Juli','August','September','Oktober','November','Dezember');
  return $month[$number-1];
}

function PrintSelectionBar ($db, $file_name) {
  /*
   * Eingabefeld für Jahr */
  echo '<div class="no_print">
          <form action="' . $file_name . '?year=' . 'apartment_id=' . '" method="get">
          <label for="year">Jahr</label>
          <input type="text" name="year" class="feld" />';

  /*
   * Auswahlliste mit Häusern ausgeben */
  $query_apartment = 'SELECT
                        house.name AS house_name, apartment.name AS apartment_name, apartment.id
                      FROM
                        apartment
                      LEFT JOIN
                        house ON house.id = apartment.house_id
                      ORDER BY house.name ASC, apartment.name ASC';
  $result_apartment = mysqli_query($db, $query_apartment);
  
  
  echo '<label for="apartment_id" />
          <select name="apartment_id">';
          
  while($row_apartment = mysqli_fetch_object($result_apartment)) {
    if ($row_apartment->house_name != $old_house) {
      echo '<optgroup label="' . $row_apartment->house_name . "\">\n";
    }
    
    echo '<option value="' . $row_apartment->id . '">' . $row_apartment->apartment_name . '</option>' . "\n";
    
    if ($row_apartment->house_name != $old_house) {
      $old_house = $row_apartment->house_name;
      echo "</optgroup>\n";
    }
  }
  
  echo '</select>
        <input type="submit" value="Auswerten" />
        </form>
      </div>';
}

function GetSumPersons ($db, $house_id, $year, $month) {
  $query_persons = 'SELECT
                      SUM(tenant.persons) AS sum_persons
                    FROM
                      tenant
                    LEFT JOIN
                      apartment ON tenant.apartment_id = apartment.id
                    WHERE apartment.house_id = ' . $house_id . '
                      AND tenant.entry <= \'' . $year . '-' . $month . '-02\' 
                      AND (tenant.extract >= \'' . $year . '-' . $month . '-02\' 
                      OR tenant.extract IS NULL)';                         

  $result_persons = mysqli_query($db, $query_persons);
  while($row_persons = mysqli_fetch_object($result_persons)) {
    return $row_persons->sum_persons;
  }
}

function GetMonthAmountExtra ($db, $tenant_id, $year, $month) {
  $query_payment = 'SELECT
                      SUM(payment.amount) AS amount
                    FROM
                      payment
                    WHERE 
                      payment.tenant_id = ' . $tenant_id . ' AND
                      YEAR( payment.for_date ) = \'' . $year . '\' AND
                      MONTH( payment.for_date ) = \'' . $month . '\' AND
                      payment.amount_kind = 1';

  $result_payment = mysqli_query($db, $query_payment);
  while($row_payment = mysqli_fetch_object($result_payment)) {
    return $row_payment->amount;
  }
}
?>
