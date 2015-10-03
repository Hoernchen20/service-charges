<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>Nebenkostenabrechnung - Kosten pro Person</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="author" content="Felix Horn">
    <meta http-equiv="language" content="de">
    <link rel="stylesheet" type="text/css" href="styles.css">
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
          </ul>
        </li>
      </ul>
      <ul>
        <li>
          <h3>Erfassen</h3>
          <ul class="subnavi">
          </ul>
        </li>
      </ul>
      <ul>
        <li>
          <h3>Auswerten</h3>
          <ul class="subnavi">
            <li><a href="analysis_tenant_month.php">Mieter pro Monat</a></li>
            <li><a href="analysis_apartment_month.php">Wohnung pro Monat</a></li>
          </ul>
        </li>
      </ul>
    </div>
    <div class="inhalt">
      <h2>Verwalten - Kosten pro Person</h2>
      <?php
        include 'inc/dbconnect.inc.php';

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
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <td headers="name" colspan="4">
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
            echo '<td headers="amount">' . $row_costs->amount . "</td>\n";
            echo '<td headers="change"><a href="#" onclick="fenster_param(\'costs_person_change\', \'' . $row_costs->id . "')\">Ändern</a></td>\n</tr>\n";
          }
              
          echo '</tbody>
              </table>';
        }
        mysqli_close($db);
    ?>
    </div>
  </body>
</html>
