<?php
  include 'inc/dbconnect.inc.php';
  include 'inc/html_functions.inc.php';
  
  PrintHtmlHeader("Nebenkostenabrechnung - Auswertung");
  PrintHtmlTopNavigation();
?>
    <div class="inhalt">
      <h2>Auswerten - Kosten pro Mieter und Monat</h2>
      <?php
            /*$query = 'SELECT
                        tenant.id, tenant.name, tenant.persons,
                        DATE_FORMAT(tenant.entry, \'%d.%m.%Y\') AS entry,
                        DATE_FORMAT(tenant.extract, \'%d.%m.%Y\') AS extract,
                        apartment.name AS apartment_name
                      FROM
                        tenant
                      INNER JOIN
                        apartment ON apartment.id = tenant.apartment_id
                      ORDER BY tenant.extract DESC';
            $result = mysqli_query($db, $query);
            
            while($row = mysqli_fetch_object($result)) {
              echo '<table>
                      <caption>' . $row->name . ' - ' . 
                        $row->persons . ' Personen - ' . 
                        $row->entry . ' - ' . $row->extract . ' - ' . 
                        $row->apartment_name . '</caption>
                      <thead>
                        <tr>
                          <th id="year">Jahr</th>
                          <th id="usage">Zweck</th>
                          <th id="amount">Kosten</th>
                        </tr>
                      </thead>
                      <tbody>';
              
              $query_apartment = 'SELECT
                                    costs_tenant.id, costs_tenant.year, costs_tenant.usage, costs_tenant.amount
                                  FROM
                                    costs_tenant
                                  WHERE costs_tenant.tenant_id = ' . $row->id . '
                                  ORDER BY costs_tenant.year DESC, costs_tenant.usage ASC';
              $result_apartment = mysqli_query($db, $query_apartment);
            
              while($row_apartment = mysqli_fetch_object($result_apartment)) {
                echo "<tr>\n";
                echo '<td headers="year">' . $row_apartment->year . "</td>\n";
                echo '<td headers="usage">' . $row_apartment->usage . "</td>\n";
                echo '<td headers="amount">' . $row_apartment->amount . "</td>\n</tr>\n";
              }
              
              
              echo '</tbody>
                  </table>';
              echo '<p class="menue">
                      <a href="#" onclick="fenster_param(\'costs_tenant_new\',\'' . $row->id . '\')">Neue Kosten pro Mieter erfassen</a>
                    </p> ';
            }*/
            mysqli_close($db);
          ?>
         
      </div>
  </body>
</html>
