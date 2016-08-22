<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>Nebenkostenabrechnung - Wohnung pro Monat</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="author" content="Felix Horn">
    <meta http-equiv="language" content="de">
    <link rel="stylesheet" media="screen" type="text/css" href="styles.css">
    <link rel="stylesheet" media="print" type="text/css" href="printer.css">
    <script type="text/JavaScript" src="inc/menue.inc.js"></script>
  </head>
  <body>
    <div class="head">
      <h1>Nebenkostenabrechnung</h1>
    </div>
    <div class="topnavi">
      <ul>
        <li>
          <h3>Verwalten</h3>
          <ul class="subnavi">
            <li><a href="house.php">Haus</a></li>
            <li><a href="apartment.php">Wohnung</a></li>
            <li><a href="tenant.php">Mieter</a></li>
            <li><a href="costs_house.php">Kosten pro Haus</a></li>
            <li><a href="costs_person.php">Kosten pro Person</a></li>
            <li><a href="costs_tenant.php">Kosten pro Mieter</a></li>
            <li><a href="payment.php">Zahlungen</a></li>
          </ul>
        </li>
      </ul>
      <ul>
        <li>
          <h3>Erfassen</h3>
          <ul class="subnavi">
            <li>test</li>
          </ul>
        </li>
      </ul>
      <ul>
        <li>
          <h3>Auswerten</h3>
          <ul class="subnavi">
<!--            <li><a href="analysis_tenant_month.php">Mieter pro Monat</a></li> -->
            <li><a href="analysis_apartment_month.php">Wohnung pro Monat</a></li>
            <li><a href="analysis_apartment_year.php">Wohnung pro Jahr</a></li>
          </ul>
        </li>
      </ul>
    </div>
    <div class="inhalt">
    <?php
/*      error_reporting (E_ALL);
      ini_set ('display_errors', 'On');*/
      include 'inc/dbconnect.inc.php';
      include 'inc/php_functions.inc.php';
      
      $costs_diff = 0;
      $costs_diff_month = array_fill(1, 12, 0);
      $old_house = '';
      $data[][26] = array();
      $data_copied = 0;
      
      PrintSelectionBar($db, "analysis_apartment_year.php");
      
      if ($_GET) {
        /*
         * Check $_GET */
        if ( !(ctype_digit($_GET['year'])) ) {
          exit('Error: Param');
        } else {
          $get_year = $_GET['year'];
        }
          
        if ( !(ctype_digit($_GET['apartment_id'])) ) {
          exit('Error: Param');
        } else {
          $get_apartment_id = $_GET['apartment_id'];
        }
        /*
         * Grunddaten auslesen */
        $query = 'SELECT
                    apartment.name AS apartment_name, house.name AS house_name,
                    apartment.size AS apartment_size, house.size AS house_size,
                    house.id,
                    (apartment.size * 100 / house.size) AS apartment_percent
                  FROM
                    apartment
                  RIGHT JOIN
                    house ON apartment.house_id = house.id
                  WHERE apartment.id =' . $get_apartment_id;
        $result = mysqli_query($db, $query);
              
        while($row = mysqli_fetch_object($result)) {
          $apartment_name = $row->apartment_name;
          $house_name = $row->house_name;
          $apartment_size = $row->apartment_size;
          $house_size = $row->house_size;
          $house_id = $row->id;
          $apartment_percent = $row->apartment_percent;
        }
        
        /* 
         * Kosten pro Haus */
        $num_costs_house = 0;
        $query_costs_house = 'SELECT
                                costs_house.usage, costs_house.amount
                              FROM
                                costs_house
                              WHERE costs_house.house_id = (
                                SELECT
                                  apartment.house_id
                                FROM
                                  apartment
                                WHERE apartment.id =' . $get_apartment_id . ')
                                  AND costs_house.year = ' . $get_year;                         
                                
        $result_costs_house = mysqli_query($db, $query_costs_house);
        while($row_costs_house = mysqli_fetch_object($result_costs_house)) {
          $sum = $row_costs_house->amount/100*$apartment_percent/12;
          
          $data[$num_costs_house+1][0] = $row_costs_house->usage;
          $data[$num_costs_house+1][1] = $row_costs_house->amount;
          $data[$num_costs_house+1][2] = $apartment_percent / 12;
          $data[$num_costs_house+1][3] = $sum;
          
          for ($i = 1; $i < 12; $i++) {
            $data[$num_costs_house+1][$i*2+2] = $data[$num_costs_house+1][2];
            $data[$num_costs_house+1][$i*2+3] = $data[$num_costs_house+1][3];
          }
          
          $data[$num_costs_house + 1][26] = $apartment_percent;
          $num_costs_house += 1;
        }
        
        

        for ($month = 1; $month < 13; $month++) {
          /*
           * Mieter abfragen */
          $sum_persons = GetSumPersons($db, $house_id, $get_year, $month);
          
          $query = 'SELECT
                      tenant.id, tenant.name, tenant.persons
                    FROM
                      tenant
                    WHERE tenant.apartment_id = ' . $get_apartment_id . '
                      AND tenant.entry <= \'' . $get_year . '-' . $month . '-02\' 
                      AND (tenant.extract >= \'' . $get_year . '-' . $month . '-02\' 
                      OR tenant.extract IS NULL)';

          $result = mysqli_query($db, $query);
          while($row = mysqli_fetch_object($result)) {
            $tenant_id = $row->id;
            $tenant_name = $row->name;
            $persons = $row->persons;
            $persons_percent = $persons/$sum_persons*100;
          }
          
          /*
           * Wenn sich der Mieter ändert Array zwischen speichern */
          static $old_tenant_name = NULL;
          if ($old_tenant_name == NULL) {
            $old_tenant_name = $tenant_name;
          }
          
          static $old_persons = NULL;
          if ($old_persons == NULL) {
            $old_persons = $persons;
          }
          
          static $old_persons_percent = NULL;
          if ($old_persons_percent == NULL) {
            $old_persons_percent = $persons_percent;
          }
          
          if ($old_tenant_name != $tenant_name || $month == 12) {
            $data_copy = $data;
            $data_copied = 1;
          
            for ($i = 1; $i < count($data_copy); $i++) {
              $data_copy[$i][27] = 0;
              for ($j = 1; $j < 13; $j++){
                if ($data_copy[$i][$j*2+1] == NULL) {
                  break;
                }
                $data_copy[$i][27] += $data_copy[$i][$j*2+1];
              }
            }
            
            
          $old_tenant_name = $tenant_name;
          $old_persons = $persons;
          $old_persons_percent = $persons_percent;
          }
          
          /* 
           * Kosten pro Person */
          $num_costs_person = 0;
          $query_costs_person = 'SELECT
                                   costs_person.usage, costs_person.amount
                                 FROM
                                   costs_person
                                 WHERE costs_person.house_id = (
                                   SELECT
                                     apartment.house_id
                                   FROM
                                     apartment
                                   WHERE apartment.id =' . $get_apartment_id . ')
                                     AND costs_person.year = ' . $get_year;                         
                                  
          $result_costs_person = mysqli_query($db, $query_costs_person);
          while($row_costs_person = mysqli_fetch_object($result_costs_person)) {
            $sum = $row_costs_person->amount/100*$persons_percent/12;
            echo 'Monat: ' . $month;
            $data[$num_costs_house + 1 + $num_costs_person][0] = $row_costs_person->usage;
            $data[$num_costs_house + 1 + $num_costs_person][1] = $row_costs_person->amount;
            $data[$num_costs_house + 1 + $num_costs_person][$month*2] = $persons_percent / 12;
            $data[$num_costs_house + 1 + $num_costs_person][$month*2+1] = $sum;
            
            $data[$num_costs_house + 1 + $num_costs_person][26] = $persons_percent;
            $num_costs_person += 1;
          }
          
          /*
           * Kosten pro Mieter */
          $num_costs_tenant = 0;
          
          /* 
           * Abfragen, wie viele Monate der Mieter in der Wohnung gewohnt hat,
           * um die Kosten pro Mieter auf einen einzelnen Monat runter zurechnen */
          $query_tenant_month = 'SELECT
                                   YEAR( tenant.entry ) AS entry_year, MONTH( tenant.entry ) AS entry_month,
                                   YEAR( tenant.extract ) AS extract_year, MONTH( tenant.extract ) AS extract_month
                                 FROM
                                   tenant
                                 WHERE tenant.id = '. $tenant_id;
          $result_tenant_month = mysqli_query($db, $query_tenant_month);
          while($row_tenant_month = mysqli_fetch_object($result_tenant_month)) {
            /* NULL-Werte abfangen, um weitere if-Anweisungen zu vermeiden */
            if ($row_tenant_month->extract_year == NULL) {
              $row_tenant_month->extract_year = $get_year+1;
            }
            
            /* Monate ausrechnen */
            if ($row_tenant_month->entry_year < $get_year && $row_tenant_month->extract_year == $get_year) {
              $tenant_month = $row_tenant_month->extract_month;
            } else if ($row_tenant_month->entry_year < $get_year && $row_tenant_month->extract_year > $get_year) {
              $tenant_month = 12;
            } else if ($row_tenant_month->entry_year == $get_year && $row_tenant_month->extract_year == $get_year) {
              $tenant_month = $row_tenant_month->extract_month - $row_tenant_month->entry_month + 1;
            }else if ($row_tenant_month->entry_year == $get_year && $row_tenant_month->extract_year > $get_year) {
              $tenant_month = 12-$row_tenant_month->entry_month+1;
            }
          }
              
          $query_costs_tenant = 'SELECT
                                   costs_tenant.usage, costs_tenant.amount
                                 FROM
                                   costs_tenant
                                 WHERE costs_tenant.tenant_id = ' . $tenant_id . '
                                   AND costs_tenant.year = ' . $get_year;

          $result_costs_tenant = mysqli_query($db, $query_costs_tenant);
          while($row_costs_tenant = mysqli_fetch_object($result_costs_tenant)) {
            $sum = $row_costs_tenant->amount/$tenant_month;
            $sum = round($sum, 2);
            
            $data[$num_costs_house + $num_costs_person + 1 + $num_costs_tenant][0] = $row_costs_tenant->usage;
            $data[$num_costs_house + $num_costs_person + 1 + $num_costs_tenant][1] = $row_costs_tenant->amount;
            $data[$num_costs_house + $num_costs_person + 1 + $num_costs_tenant][$month*2] = 100;
            $data[$num_costs_house + $num_costs_person + 1 + $num_costs_tenant][$month*2+1] = $sum;
            $num_costs_tenant += 1;
          }
          
          if ($data_copied == 1) {
            echo '<h2>' . $house_name . ' - ' . $apartment_name . "</h2>\n";
            echo '<p>Mieter: ' . $old_tenant_name . '<br>Wohnfläche: ' . $apartment_size . 'm² von ' . $house_size . 'm² (' . number_format($apartment_percent, 2, ',', '') . "%)</p>\n";
            echo '<table class="analysis">
                  <thead>
                    <tr>
                      <th></th>
                      <th>Januar</th>
                      <th>Februar</th>
                      <th>März</th>
                      <th>April</th>
                      <th>Mai</th>
                      <th>Juni</th>
                      <th>Juli</th>
                      <th>August</th>
                      <th>September</th>
                      <th>Oktober</th>
                      <th>November</th>
                      <th>Dezember</th>
                      <th>Summe</th>
                    </tr>
                  </thead>
                <tbody>';
            for ($i = 1; $i < count($data_copy); $i++) {
              echo '<tr>
                      <td>' . $data_copy[$i][0] . '<br>' . number_format($data_copy[$i][1], 2, ',', '') . '€</td>
                      <td>' . number_format($data_copy[$i][2], 2, ',', '') . '%<br>' . number_format($data_copy[$i][3], 2, ',', '') . '</td>
                      <td>' . number_format($data_copy[$i][4], 2, ',', '') . '%<br>' . number_format($data_copy[$i][5], 2, ',', '') . '€</td>
                      <td>' . number_format($data_copy[$i][6], 2, ',', '') . '%<br>' . number_format($data_copy[$i][7], 2, ',', '') . '€</td>
                      <td>' . number_format($data_copy[$i][8], 2, ',', '') . '%<br>' . number_format($data_copy[$i][9], 2, ',', '') . '€</td>
                      <td>' . number_format($data_copy[$i][10], 2, ',', '') . '%<br>' . number_format($data_copy[$i][11], 2, ',', '') . '€</td>
                      <td>' . number_format($data_copy[$i][12], 2, ',', '') . '%<br>' . number_format($data_copy[$i][13], 2, ',', '') . '€</td>
                      <td>' . number_format($data_copy[$i][14], 2, ',', '') . '%<br>' . number_format($data_copy[$i][15], 2, ',', '') . '€</td>
                      <td>' . number_format($data_copy[$i][16], 2, ',', '') . '%<br>' . number_format($data_copy[$i][17], 2, ',', '') . '€</td>
                      <td>' . number_format($data_copy[$i][18], 2, ',', '') . '%<br>' . number_format($data_copy[$i][19], 2, ',', '') . '€</td>
                      <td>' . number_format($data_copy[$i][20], 2, ',', '') . '%<br>' . number_format($data_copy[$i][21], 2, ',', '') . '€</td>
                      <td>' . number_format($data_copy[$i][22], 2, ',', '') . '%<br>' . number_format($data_copy[$i][23], 2, ',', '') . '€</td>
                      <td>' . number_format($data_copy[$i][24], 2, ',', '') . '%<br>' . number_format($data_copy[$i][25], 2, ',', '') . '€</td>
                      <td>' . number_format($data_copy[$i][26], 2, ',', '') . '%<br>' . number_format($data_copy[$i][27], 2, ',', '') . '€</td>
                    </tr>';
            }
            echo '</tbody>
                </table>';
          }
        }
      }     
      mysqli_close($db);
    ?>
    </div>
  </body>
</html>
