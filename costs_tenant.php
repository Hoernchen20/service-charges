<?php
  include 'inc/dbconnect.inc.php';
  include 'inc/html_functions.inc.php';
  
  PrintHtmlHeader("Nebenkostenabrechnung - Kosten pro Mieter");
  PrintHtmlTopNavigation();
?>
    <div class="inhalt">
      <h2>Verwalten - Kosten pro Mieter</h2>
      <?php
        $query_tenant = 'SELECT
                           tenant.id, tenant.name, tenant.persons,
                           DATE_FORMAT(tenant.entry, \'%d.%m.%Y\') AS entry,
                           DATE_FORMAT(tenant.extract, \'%d.%m.%Y\') AS extract,
                           apartment.name AS apartment_name
                         FROM
                           tenant
                         INNER JOIN
                           apartment ON apartment.id = tenant.apartment_id
                         ORDER BY tenant.extract DESC';
        $result_tenant = mysqli_query($db, $query_tenant);

        while($row_tenant = mysqli_fetch_object($result_tenant)) {
          echo '<table>
                  <caption>' . $row_tenant->name . ' - ' . 
                    $row_tenant->persons . ' Personen - ' . 
                    $row_tenant->entry . ' - ' . $row_tenant->extract . ' - ' . 
                    $row_tenant->apartment_name . '</caption>
                  <thead>
                    <tr>
                      <th id="year">Jahr</th>
                      <th id="usage">Zweck</th>
                      <th id="amount">Kosten</th>
                      <th id="change">Ändern</th>
                      <th id="change">Löschen</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <td headers="name" colspan="5">
                        <a href="#" onclick="fenster_param(\'costs_tenant_new\',\'' . $row_tenant->id . '\')">Neue Kosten pro Mieter erfassen</a>
                      </td>
                    </tr>
                  </tfoot>
                  <tbody>';
              
          $query_costs = 'SELECT
                            costs_tenant.id, costs_tenant.year, costs_tenant.usage, costs_tenant.amount
                          FROM
                            costs_tenant
                          WHERE costs_tenant.tenant_id = ' . $row_tenant->id . '
                          ORDER BY costs_tenant.year DESC, costs_tenant.usage ASC';
          $result_costs = mysqli_query($db, $query_costs);

          while($row_costs = mysqli_fetch_object($result_costs)) {
            echo "<tr>\n";
            echo '<td headers="year">' . $row_costs->year . "</td>\n";
            echo '<td headers="usage">' . $row_costs->usage . "</td>\n";
            echo '<td headers="amount" class="right">' . number_format($row_costs->amount, 2, ',', '') . "€</td>\n";
            echo '<td headers="change"><a href="#" onclick="fenster_param(\'costs_tenant_change\', \'' . $row_costs->id . "')\">Ändern</a></td>\n";
            echo '<td headers="delete"><a href="#" onclick="fenster_param(\'costs_tenant_delete\', \'' . $row_costs->id . "')\">Löschen</a></td>\n</tr>\n";
          }

          echo '</tbody>
              </table>';
        }
        mysqli_close($db);
      ?>
    </div>
  </body>
</html>
