<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>Nebenkostenabrechnung - Mieter</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="author" content="Felix Horn">
    <meta http-equiv="language" content="de">
    <link rel="stylesheet" type="text/css" href="styles.css">
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
            </ul>
          </li>
        </ul>
        <ul>
          <li>
            <h3>Erfassen</h3>
            <ul class="subnavi">
              <li><a href="#">Kosten pro Haus</a></li>
              <li><a href="#">Kosten pro Wohnung</a></li>
              <li><a href="#">Kosten pro Mieter</a></li>
            </ul>
          </li>
        </ul>
        <ul>
          <li>
            <h3>Auswerten</h3>
            <ul class="subnavi">
              <li><a href="#">Mieter pro Monat</a></li>
            </ul>
          </li>
        </ul>
      </div>
      <div class="inhalt">
        <h2>Verwalten - Mieter</h2>

          <?php
            include 'dbconnect.php';
            
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
                          <th id="persons">Personen</th>
                          <th id="entry">Einzug</th>
                          <th id="extract">Auszug</th>
                          <th id="apartment_name">Wohnung</th>
                        </tr>
                      </thead>
                      <tbody>';
              
              $query_tenant = 'SELECT
                                    tenant.id, tenant.name, tenant.persons, tenant.entry, tenant.extract, apartment.name AS apartment_name
                                  FROM
                                    tenant
                                  INNER JOIN
                                    apartment ON apartment.id = tenant.apartment_id
                                  WHERE apartment.house_id = ' . $row->id . '
                                  ORDER BY tenant.extract DESC';
              $result_tenant = mysqli_query($db, $query_tenant);
            
              while($row_tenant = mysqli_fetch_object($result_tenant)) {
                echo "<tr>\n";
                echo '<td headers="name">' . $row_tenant->name . "</td>\n";
                echo '<td headers="persons">' . $row_tenant->persons . "</td>\n";
                echo '<td headers="entry">' . $row_tenant->entry . "</td>\n";
                echo '<td headers="extract">' . $row_tenant->extract . "</td>\n";
                echo '<td headers="apartment_name">' . $row_tenant->apartment_name . "</td>\n";                
              }
              
              echo '</tbody>
                  </table>';              
            }
            mysqli_close($db);
          ?>
          
      </div>
  </body>
</html>
