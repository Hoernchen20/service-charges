<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>Nebenkostenabrechnung - Kosten pro Mieter</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="author" content="Felix Horn">
    <meta http-equiv="language" content="de">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script type="text/JavaScript" src="inc/menue.js"></script>
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
        <h2>Verwalten - Kosten pro Mieter</h2>

          <?php
            include 'dbconnect.php';
            
            $query = 'SELECT
                        tenant.id, tenant.name, tenant.persons,
                        DATE_FORMAT(tenant.entry, \'%d.%m.%Y\') AS entry,
                        DATE_FORMAT(tenant.extract, \'%d.%m.%Y\') AS extract,
                        apartment.name AS apartment_name
                      FROM
                        tenant
                      INNER JOIN
                        apartment ON apartment.id = tenant.apartment_id
                      ORDER BY tenant.extract DESC';
            $result = mysqli_query($db, $query);
            
            while($row = mysqli_fetch_object($result)) {
              echo '<table>
                      <caption>' . $row->name . ' - ' . 
                        $row->persons . ' Personen - ' . 
                        $row->entry . ' - ' . $row->extract . ' - ' . 
                        $row->apartment_name . '</caption>
                      <thead>
                        <tr>
                          <th id="year">Jahr</th>
                          <th id="usage">Zweck</th>
                          <th id="amount">Kosten</th>
                        </tr>
                      </thead>
                      <tbody>';
              
              $query_apartment = 'SELECT
                                    costs_tenant.id, costs_tenant.year, costs_tenant.usage, costs_tenant.amount
                                  FROM
                                    costs_tenant
                                  WHERE costs_tenant.tenant_id = ' . $row->id . '
                                  ORDER BY costs_tenant.year DESC, costs_tenant.usage ASC';
              $result_apartment = mysqli_query($db, $query_apartment);
            
              while($row_apartment = mysqli_fetch_object($result_apartment)) {
                echo "<tr>\n";
                echo '<td headers="year">' . $row_apartment->year . "</td>\n";
                echo '<td headers="usage">' . $row_apartment->usage . "</td>\n";
                echo '<td headers="amount">' . $row_apartment->amount . "</td>\n</tr>\n";
              }
              
              
              echo '</tbody>
                  </table>';
              echo '<p class="menue">
                      <a href="#" onclick="fenster_param(\'costs_tenant_new\',\'' . $row->id . '\')">Neue Kosten pro Mieter erfassen</a>
                    </p> ';
            }
            mysqli_close($db);
          ?>
         
      </div>
  </body>
</html>
