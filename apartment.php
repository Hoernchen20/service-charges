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
            <li><a href="payment.php">Zahlungen</a></li>
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
<!--            <li><a href="analysis_tenant_month.php">Mieter pro Monat</a></li> -->
            <li><a href="analysis_apartment_month.php">Wohnung pro Monat</a></li>
            <li><a href="analysis_apartment_year.php">Wohnung pro Jahr</a></li>
          </ul>
        </li>
      </ul>
    </div>
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
          include 'inc/dbconnect.inc.php';
              
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
