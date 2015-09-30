<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>Nebenkostenabrechnung - Wohnung</title>
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
      <h2>Verwalten - Wohnung</h2>
      <?php
        include 'inc/dbconnect.inc.php';
            
        $query = 'SELECT
                    house.id, house.name
                  FROM
                    house
                  ORDER BY house.name ASC';
        $result = mysqli_query($db, $query);
            
        while($row = mysqli_fetch_object($result)) {
          echo '<table>
                  <caption>' . $row->name . '</caption>
                  <thead>
                    <tr>
                      <th id="name">Name</th>
                      <th id="size">Wohnfläche</th>
                    </tr>
                  </thead>
                 <tbody>';
              
          $query_apartment = 'SELECT
                                apartment.id, apartment.name, apartment.size
                              FROM
                                apartment
                              WHERE apartment.house_id = ' . $row->id . '
                              ORDER BY apartment.name DESC';
          $result_apartment = mysqli_query($db, $query_apartment);
            
          while($row_apartment = mysqli_fetch_object($result_apartment)) {
            echo "<tr>\n";
            echo '<td headers="name">' . $row_apartment->name . "</td>\n";
            echo '<td headers="size">' . $row_apartment->size . "m²</td>\n</tr>\n";
          }
              
          echo '</tbody>
              </table>';
          echo '<p class="menue">
                  <a href="#" onclick="fenster_param(\'apartment_new\',\'' . $row->id . '\')">Neue Wohnung anlegen</a>
                </p> ';
        }
        mysqli_close($db);
      ?>
    </div>
  </body>
</html>