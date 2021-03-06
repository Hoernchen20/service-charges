<?php
  include 'inc/dbconnect.inc.php';
  include 'inc/html_functions.inc.php';
  
  PrintHtmlHeader("Nebenkostenabrechnung - Zahlungen");
  PrintHtmlTopNavigation();
?>
    <div class="inhalt">
      <h2>Verwalten - Zahlungen</h2>
      <?php
        include 'inc/dbconnect.inc.php';

        /*
         * Auswahlliste mit Mietern ausgeben */
        $query_tenant = 'SELECT
                           tenant.id, tenant.name, tenant.persons,
                           DATE_FORMAT(tenant.entry, \'%d.%m.%Y\') AS entry,
                           DATE_FORMAT(tenant.extract, \'%d.%m.%Y\') AS extract,
                           apartment.name AS apartment_name
                         FROM
                           tenant
                         INNER JOIN
                           apartment ON apartment.id = tenant.apartment_id
                         ORDER BY apartment.name ASC, tenant.extract IS NULL DESC, tenant.extract DESC';
        $result_tenant = mysqli_query($db, $query_tenant);

        echo '<p>
                <select name="tenant_id" onChange="location.href=this.options[this.selectedIndex].value">
                  <option value="#">Mieter wählen</option>' . "\n";
        
        while($row_tenant = mysqli_fetch_object($result_tenant)) {
          if ($row_tenant->id == $_GET['id']) {
            $tenant_id = $row_tenant->id;
            $tenant_name = $row_tenant->name;
            $tenant_persons = $row_tenant->persons;
            $tenant_entry = $row_tenant->entry;
            $tenant_extract = $row_tenant->extract;
            $tenant_apartment_name = $row_tenant->apartment_name;
          }
          echo '<option value="payment.php?id=' . $row_tenant->id . '">' . $row_tenant->name . ' - ' . 
                    $row_tenant->persons . ' Personen - ' . 
                    $row_tenant->entry . ' - ' . $row_tenant->extract . ' - ' . 
                    $row_tenant->apartment_name ."</option>\n";
        }
        echo '</select>
            </p>';

        if ($_GET) {
        /*
         * Check $_GET['param'] */
        if ( !(ctype_digit($_GET['id'])) ) {
          exit('Error: id');
        }  
        echo '<table>
                  <caption>' . $tenant_name . ' - ' . 
                    $tenant_persons . ' Personen - ' . 
                    $tenant_entry . ' - ' . $tenant_extract . ' - ' . 
                    $tenant_apartment_name . '</caption>
                  <thead>
                    <tr>
                      <th id="date">Buchungsdatum</th>
                      <th id="for_date">für Monat / Jahr</th>
                      <th id="amount_kind">Zweck</th>
                      <th id="amount">Betrag</th>
                      <th id="change">Ändern</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <td headers="name" colspan="5">
                        <a href="#" onclick="fenster_param(\'payment_new\',\'' . $tenant_id . '\')">Neue Zahlung erfassen</a>
                      </td>
                    </tr>
                  </tfoot>
                  <tbody>';
              
          $query_payment = 'SELECT
                              payment.id,
                              DATE_FORMAT(payment.date, \'%d.%m.%Y\') AS date,
                              MONTH(payment.for_date) AS month,
                              YEAR(payment.for_date) AS year,
                              payment.amount_kind, payment.amount
                            FROM
                              payment
                            WHERE payment.tenant_id = ' . $tenant_id . '
                            ORDER BY payment.date DESC';
          $result_payment = mysqli_query($db, $query_payment);

          while($row_payment = mysqli_fetch_object($result_payment)) {
            echo "<tr>\n";
            echo '<td headers="date">' . $row_payment->date . "</td>\n";
            echo '<td headers="for_date">' . $row_payment->month . ' / ' . $row_payment->year . "</td>\n";
            
            if ($row_payment->amount_kind == 0) {
              echo '<td headers="amount_kind">Miete' . "</td>\n";
            } else if ($row_payment->amount_kind == 1) {
              echo '<td headers="amount_kind">Nebenkosten' . "</td>\n";
            } else if ($row_payment->amount_kind == 2) {
              echo '<td headers="amount_kind">Kaution' . "</td>\n";
            }
            
            echo '<td headers="extra" class="right">' . number_format($row_payment->amount, 2, ',', '') . "€</td>\n";
            echo '<td headers="change"><a href="#" onclick="fenster_two_param(\'payment_change\',\'' . $row_payment->id . '\',\'' . $_GET['id'] . "')\">Ändern</a></td>\n</tr>\n";
          }

          echo '</tbody>
              </table>';
        }
        mysqli_close($db);
      ?>
    </div>
  </body>
</html>
