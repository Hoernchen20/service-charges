<?php
  include 'inc/dbconnect.inc.php';
  include 'inc/html_functions.inc.php';
  
  PrintHtmlHeader("Nebenkostenabrechung - Häuser");
  PrintHtmlTopNavigation();
?>
    <div class="inhalt">
      <h2>Verwalten - Haus</h2>
      <table>
        <thead>
          <tr>
            <th id="name">Name</th>
            <th id="size">Wohnfläche</th>
            <th id="size">Mieter</th>
            <th id="size">Kosten</th>
            <th id="change">Ändern</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <td headers="name" colspan="5"><a href="#" onclick="fenster('house_new')">Neues Haus anlegen</a></td>
          </tr>
        </tfoot>
        <tbody>
        <?php
 
          $query = 'SELECT
                      house.id, house.name, house.size
                    FROM
                      house
                    ORDER BY house.name ASC';
          $result = mysqli_query($db, $query);
    
          while($row = mysqli_fetch_object($result)) {
            echo "<tr>\n";
            echo '<td headers="name"><a href="apartment.php?house_id=' . $row->id . '">' . $row->name . "</a></td>\n";
            echo '<td headers="size">' . $row->size . "m²</td>\n";
            echo '<td headers="Mieter"><a href="tenant.php?house_id=' . $row->id . '">ansehen' . "</a></td>\n";
            echo '<td headers="Kosten"><a href="costs_house.php?house_id=' . $row->id . '">ansehen' . "</a></td>\n";
            echo '<td headers="change"><a href="#" onclick="fenster_param(\'house_change\', \'' . $row->id . "')\">Ändern</a></td>\n</tr>\n";
          }
          mysqli_close($db);
        ?>
        </tbody>
      </table>
    </div>
  </body>
</html>
