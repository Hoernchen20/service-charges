<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>Nebenkostenabrechnung - Haus</title>
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
      <h2>Verwalten - Haus</h2>
      <table>
        <thead>
          <tr>
            <th id="name">Name</th>
            <th id="size">Wohnfläche</th>
            <th id="change">Ändern</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <td headers="name" colspan="3"><a href="#" onclick="fenster('house_new')">Neues Haus anlegen</a></td>
          </tr>
        </tfoot>
        <tbody>
        <?php
          include 'inc/dbconnect.inc.php';
 
          $query = 'SELECT
                      house.id, house.name, house.size
                    FROM
                      house
                    ORDER BY house.name ASC';
          $result = mysqli_query($db, $query);
    
          while($row = mysqli_fetch_object($result)) {
            echo "<tr>\n";
            echo '<td headers="name">' . $row->name . "</td>\n";
            echo '<td headers="size">' . $row->size . "m²</td>\n";
            echo '<td headers="change"><a href="#" onclick="fenster_param(\'house_change\', \'' . $row->id . "')\">Ändern</a></td>\n</tr>\n";
          }
          mysqli_close($db);
        ?>
        </tbody>
      </table>
    </div>
  </body>
</html>
