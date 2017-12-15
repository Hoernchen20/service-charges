<?php
  include 'inc/dbconnect.inc.php';
  include 'inc/html_functions.inc.php';
  
  PrintHtmlHeader("Nebenkostenabrechnung - Wohnungen");
  PrintHtmlTopNavigation();
?>
    <div class="inhalt">
      <h2>Verwalten - Wohnung</h2>
      <?php
  
      error_reporting (E_ALL);
      ini_set ('display_errors', 'On');

        if ( $_GET == NULL ) {
          echo '<p>Kein Haus selektiert!</p>';
        } else {
          /*
           * Check $_GET */
          if ( !(ctype_digit($_GET['house_id'])) ) {
            exit('Error: Param');
          } else {
            $get_house_id = $_GET['house_id'];
          }
              
          $query = 'SELECT
                      house.name
                    FROM
                      house
                    WHERE house.id = ' . $get_house_id;
          $result = mysqli_query($db, $query);
              
          while($row = mysqli_fetch_object($result)) {
            $house_name = $row->name;
          }
          
          echo '<table>
                  <caption>' . $house_name . '</caption>
                  <thead>
                    <tr>
                      <th id="name">Name</th>
                      <th id="size">Wohnfläche</th>
                      <th id="change">Ändern</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <td headers="name" colspan="3">
                        <a href="#" onclick="fenster_param(\'apartment_new\',\'' . $get_house_id . '\')">Neue Wohnung anlegen</a>
                      </td>
                    </tr>
                  </tfoot>
                  <tbody>';
              
          $query_apartment = 'SELECT
                                apartment.id, apartment.name, apartment.size
                              FROM
                                apartment
                              WHERE apartment.house_id = ' . $get_house_id . '
                              ORDER BY apartment.name DESC';
          $result_apartment = mysqli_query($db, $query_apartment);
            
          while($row_apartment = mysqli_fetch_object($result_apartment)) {
            echo "<tr>\n";
            echo '<td headers="name">' . $row_apartment->name . "</td>\n";
            echo '<td headers="size">' . $row_apartment->size . "m²</td>\n";
            echo '<td headers="change"><a href="#" onclick="fenster_param(\'apartment_change\', \'' . $row_apartment->id . "')\">Ändern</a></td>\n</tr>\n";
          }
          echo '</tbody>
              </table>';
          mysqli_close($db);
        }
      ?>
    </div>
  </body>
</html>
