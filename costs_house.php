<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>Nebenkostenabrechnung - Kosten pro Haus</title>
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
            <li>test</li>
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
      <h2>Verwalten - Kosten pro Haus</h2>
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
                  <tbody>';
              
          $query_costs = 'SELECT
                                costs_house.id, costs_house.year, costs_house.usage, costs_house.amount
                              FROM
                                costs_house
                              WHERE costs_house.house_id = ' . $row_house->id . '
                              ORDER BY costs_house.year DESC, costs_house.usage ASC';
          $result_costs = mysqli_query($db, $query_costs);
            
          while($row_costs = mysqli_fetch_object($result_costs)) {
            echo "<tr>\n";
            echo '<td headers="year">' . $row_costs->year . "</td>\n";
            echo '<td headers="usage">' . $row_costs->usage . "</td>\n";
            echo '<td headers="amount">' . $row_costs->amount . "</td>\n";
            echo '<td headers="change"><a href="#" onclick="fenster_param(\'costs_house_change\', \'' . $row_costs->id . "')\">Ändern</a></td>\n</tr>\n";
          }
    
          echo '</tbody>
              </table>';
          echo '<p class="menue">
                  <a href="#" onclick="fenster_param(\'costs_house_new\',\'' . $row_house->id . '\')">Neue Kosten pro Haus erfassen</a>
                </p> ';
        }
        mysqli_close($db);
      ?>
    </div>
  </body>
</html>
