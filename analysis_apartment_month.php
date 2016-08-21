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
      include 'inc/dbconnect.inc.php';
      include 'inc/php_functions.inc.php';
      
      $costs_diff = 0;
      $costs_diff_month = array_fill(1, 12, 0);
      $old_house = '';
      
      PrintSelectionBar($db, "analysis_apartment_month.php");
      
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
        
        for ($month = 1; $month < 13; $month++) {
          /*
           * Mieter ausgeben */
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
           * Kumulierte Kosten */
          static $old_tenant_name = NULL;
          if ($old_tenant_name == NULL) {
            $old_tenant_name = $tenant_name;
          }
          
          if ($old_tenant_name != $tenant_name) {
            echo '<h2>' . $house_name . ' - ' . $apartment_name . "</h2>\n";
            echo '<h2>Zusammenfassung' . "</h2>\n";
            echo '<p>Mieter: ' . $old_tenant_name . "</p>\n";
            echo '<table class="analysis">
                  <thead>
                    <tr>
                      <th id="usage">Monat</th>
                      <th class="right" id="amount">Betrag</th>
                    </tr>
                  </thead>
                  <tbody>';
            for ($i = 1; $i < 13; $i++) {
              echo "<tr>
                      <td headers=\"usage\">" . ReturnMonthName($i) . "</td>\n
                      <td class=\"right\" headers=\"sum\">" . number_format($costs_diff_month[$i], 2, ',', '') . "€</td>\n
                    </tr>\n";
            }
            
            echo "<tr>
                    <td class=\"sum\" headers=\"usage\">Summe Betrag</td>\n
                    <td class=\"sum right\" headers=\"sum\">" . number_format(array_sum($costs_diff_month), 2, ',', '') . "€</td>\n
                  </tr>\n";
                    
            echo '</tbody>
              </table>';
                  
            $old_tenant_name = $tenant_name;
            $costs_diff_month = array_fill(1, 12, 0);
          }

          echo '<h2>' . $house_name . ' - ' . $apartment_name . "</h2>\n";
          /*
           * Monat ausgeben */
          echo '<h3>1.' . $month . '.' . $get_year . ' - ';
          switch ($month) {
            case 1: echo '31';
                    break;
            case 2: echo '28';
                    break;
            case 3: echo '31';
                    break;
            case 4: echo '30';
                    break;
            case 5: echo '31';
                    break;
            case 6: echo '30';
                    break;
            case 7: echo '31';
                    break;
            case 8: echo '31';
                    break;
            case 9: echo '30';
                    break;
            case 10: echo '31';
                     break;
            case 11: echo '30';
                     break;
            case 12: echo '31';
                     break;
          }
          echo '.' . $month . '.' . $get_year . "</h3>\n";
          
          echo '<p>Mieter: ' . $tenant_name . '<br>Personen: ' . $persons . ' von ' . $sum_persons . ' (' . number_format($persons_percent, 2, ',', '') . '
              %)<br>Wohnfläche: ' . $apartment_size . 'm² von ' . $house_size . 'm² (' . number_format($apartment_percent, 2, ',', '') . "%)</p>\n";
                
          /*
           * Tabelle für Kosten ausgeben 
           * 
           * Kosten pro Haus */
          $costs_sum = 0;
           
          echo '<table class="analysis">
                  <thead>
                    <tr>
                      <th id="usage">Zweck</th>
                      <th class="right" id="amount">Kosten</th>
                      <th class="right" id="percent">Anteil</th>
                      <th class="right" id="sum">Summe</th>
                    </tr>
                  </thead>
                  <tbody>';
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
            $costs_sum += $sum;
            echo "<tr>\n";
            echo '<td headers="usage">' . $row_costs_house->usage . "</td>\n";
            echo '<td headers="amount" class="right">' . number_format($row_costs_house->amount, 2, ',', '') . "€</td>\n";
            echo '<td headers="percent" class="right">' . number_format($apartment_percent, 2, ',', '') . "%</td>\n";
            echo '<td headers="sum" class="right">' . number_format($sum, 2, ',', '') . "€</td>\n</tr>\n";          
          }

          /* 
           * Kosten pro Person */
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
            $costs_sum += $sum;
            echo "<tr>\n";
            echo '<td headers="usage">' . $row_costs_person->usage . "</td>\n";
            echo '<td headers="amount" class="right">' . number_format($row_costs_person->amount, 2, ',', '') . "€</td>\n";
            echo '<td headers="percent" class="right">' . number_format($persons_percent, 2, ',', '') . "%</td>\n";
            echo '<td headers="sum" class="right">' . number_format($sum, 2, ',', '') . "€</td>\n</tr>\n";
          }

          /*
           * Kosten pro Mieter 
           * 
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
            $costs_sum += $sum;
            $sum = round($sum, 2);
            echo "<tr>\n";
            echo '<td headers="usage">' . $row_costs_tenant->usage . "</td>\n";
            echo '<td headers="amount" class="right">' . number_format($sum, 2, ',', '') . "€</td>\n";
            echo "<td headers=\"percent\" class=\"right\">100,00%</td>\n";
            echo '<td headers="sum" class="right">' . number_format($sum, 2, ',', '') . "€</td>\n</tr>\n";
          }
          
          /*
           * Summe Kosten */
          echo "<tr>
                  <td class=\"sum\" headers=\"usage\" colspan=\"3\">Summe</td>\n
                  <td class=\"sum right\" headers=\"sum\">" . number_format($costs_sum, 2, ',', '') . "€</td>\n
                </tr>\n";
          
          /*
           * Gezahlte Nebenkosten */
          $payment_amount = GetMonthAmountExtra($db, $tenant_id, $get_year, $month);
          
          echo "<td headers=\"usage\" colspan=\"3\">Gezahlt</td>\n";
          echo '<td headers="sum" class="right">' . number_format($payment_amount, 2, ',', '') . "€</td>\n</tr>\n";
          
          /*
           * Differenz zwischen Kosten und gezahlten Nebenkosten */
          $costs_diff = $costs_sum - $payment_amount;
          $costs_diff_month[$month] = $costs_diff;
          echo "<tr>
                  <td class=\"sum\" headers=\"usage\" colspan=\"3\">Betrag</td>\n
                  <td class=\"sum right\" headers=\"sum\">" . number_format($costs_diff, 2, ',', '') . "€</td>\n
                </tr>\n";
          
          echo '</tbody>
              </table>';
              
          if ($month == 12) {
            echo '<h2>Zusammenfassung' . "</h2>\n";
            echo '<p>Mieter: ' . $tenant_name . "</p>\n";
            echo '<table class="analysis">
                  <thead>
                    <tr>
                      <th id="usage">Monat</th>
                      <th class="right" id="amount">Betrag</th>
                    </tr>
                  </thead>
                  <tbody>';
            for ($i = 1; $i < 13; $i++) {
              echo "<tr>
                      <td headers=\"usage\">" . ReturnMonthName($i) . "</td>\n
                      <td class=\"right\" headers=\"sum\">" . number_format($costs_diff_month[$i], 2, ',', '') . "€</td>\n
                    </tr>\n";
            }
            echo "<tr>
                    <td class=\"sum\" headers=\"usage\">Summe Betrag</td>\n
                    <td class=\"sum right\" headers=\"sum\">" . number_format(array_sum($costs_diff_month), 2, ',', '') . "€</td>\n
                  </tr>\n";

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
