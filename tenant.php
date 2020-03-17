<?php
  include 'inc/dbconnect.inc.php';
  include 'inc/html_functions.inc.php';
  
  PrintHtmlHeader("Nebenkostenabrechnung - Mieter");
  PrintHtmlTopNavigation();
?>
    <div class="inhalt">
      <h2>Verwalten - Mieter</h2>
      <?php
        if ( $_GET == NULL ) {
          $query = 'SELECT
                      house.id, house.name
                      FROM
                        house
                      ORDER BY house.name ASC';
        } else {
          /*
           * Check $_GET */
          if ( !(ctype_digit($_GET['house_id'])) ) {
            exit('Error: Param');
          } else {
            $GetHouseId = $_GET['house_id'];
          }

          $query = 'SELECT
                      house.id, house.name
                      FROM
                        house
                      WHERE house.id = ' . $GetHouseId;
        }
        
        $result = mysqli_query($db, $query);
            
        while($row = mysqli_fetch_object($result)) {
          echo '<table>
                  <caption>' . $row->name . '</caption>
                  <thead>
                    <tr>
                      <th id="name">Name</th>
                      <th id="persons">Personen</th>
                      <th id="entry">Einzug</th>
                      <th id="extract">Auszug</th>
                      <th id="apartment_name">Wohnung</th>
                      <th id="change">Ändern</th>
                      <th id="delete">Löschen</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <td headers="name" colspan="7">
                        <a href="#" onclick="fenster_param(\'tenant_new\',\'' . $row->id . '\')">Neuen Mieter anlegen</a>
                      </td>
                    </tr>
                  </tfoot>
                  <tbody>';
             
          $query_tenant = 'SELECT
                             tenant.id, tenant.name, tenant.persons,
                             DATE_FORMAT(tenant.entry, \'%d.%m.%Y\') AS entry,
                             DATE_FORMAT(tenant.extract, \'%d.%m.%Y\') AS extract,
                             apartment.name AS apartment_name
                           FROM
                             tenant
                           INNER JOIN
                             apartment ON apartment.id = tenant.apartment_id
                           WHERE apartment.house_id = ' . $row->id . '
                           ORDER BY apartment.name ASC, tenant.extract IS NULL DESC, tenant.extract DESC';
          $result_tenant = mysqli_query($db, $query_tenant);
          
          while($row_tenant = mysqli_fetch_object($result_tenant)) {
            echo "<tr>\n";
            echo '<td headers="name">' . $row_tenant->name . "</td>\n";
            echo '<td headers="persons">' . $row_tenant->persons . "</td>\n";
            echo '<td headers="entry">' . $row_tenant->entry . "</td>\n";
            echo '<td headers="extract">' . $row_tenant->extract . "</td>\n";
            echo '<td headers="apartment_name">' . $row_tenant->apartment_name . "</td>\n";
            echo '<td headers="change"><a href="#" onclick="fenster_two_param(\'tenant_change\',\'' . $row->id . '\',\'' . $row_tenant->id . "')\">Ändern</a></td>\n";
            echo '<td headers="delete"><a href="#" onclick="fenster_two_param(\'tenant_delete\',\'' . $row->id . '\',\'' . $row_tenant->id . "')\">Löschen</a></td>\n</tr>\n";
          }
            
          echo '</tbody>
              </table>';
        }
        mysqli_close($db);
      ?>   
    </div>
  </body>
</html>
