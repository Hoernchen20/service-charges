<?php
  include 'inc/dbconnect.inc.php';
  include 'inc/html_functions.inc.php';
  
  PrintHtmlHeader("Nebenkostenabrechnung - Kosten pro Person");
  PrintHtmlTopNavigation();
?>
    <div class="inhalt">
      <h2>Verwalten - Kosten pro Person</h2>
      <?php
        $query_house = 'SELECT
                          house.id, house.name
                        FROM
                          house
                        ORDER BY house.name ASC';
        $result_house = mysqli_query($db, $query_house);
        
        while($row_house = mysqli_fetch_object($result_house)) {
          echo '<table>
                  <caption>' . $row_house->name . '</caption>
                  <thead>
                    <tr>
                      <th id="year">Jahr</th>
                      <th id="usage">Zweck</th>
                      <th id="amount">Kosten</th>
                      <th id="change">Ändern</th>
                      <th id="delete">Löschen</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <td headers="name" colspan="5">
                        <a href="#" onclick="fenster_param(\'costs_person_new\',\'' . $row_house->id . '\')">Neue Kosten pro Person erfassen</a>
                      </td>
                    </tr>
                  </tfoot>
                  <tbody>';
              
          $query_costs = 'SELECT
                            costs_person.id, costs_person.year, costs_person.usage, costs_person.amount
                          FROM
                            costs_person
                          WHERE costs_person.house_id = ' . $row_house->id . '
                          ORDER BY costs_person.year DESC, costs_person.usage ASC';
          $result_costs = mysqli_query($db, $query_costs);
          
          while($row_costs = mysqli_fetch_object($result_costs)) {
            echo "<tr>\n";
            echo '<td headers="year">' . $row_costs->year . "</td>\n";
            echo '<td headers="usage">' . $row_costs->usage . "</td>\n";
            echo '<td headers="amount" class="right">' . number_format($row_costs->amount, 2, ',', '') . "€</td>\n";
            echo '<td headers="change"><a href="#" onclick="fenster_param(\'costs_person_change\', \'' . $row_costs->id . "')\">Ändern</a></td>\n";
            echo '<td headers="delete"><a href="#" onclick="fenster_param(\'costs_person_delete\', \'' . $row_costs->id . "')\">Löschen</a></td>\n</tr>\n";
          }
              
          echo '</tbody>
              </table>';
        }
        mysqli_close($db);
    ?>
    </div>
  </body>
</html>
