<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>Nebenkostenabrechnung - Haus</title>
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
        <h2>Verwalten - Haus</h2>
        <table>
          <thead>
            <tr>
              <th id="name">Name</th>
              <th id="size">Wohnfläche</th>
            </tr>
          </thead>
          <tbody>
          <?php
            include 'dbconnect.php';
            
            $query = 'SELECT
                        house.id, house.name, house.size
                      FROM
                        house
                      ORDER BY house.name ASC';
            $result = mysqli_query($db, $query);
      
            while($row = mysqli_fetch_object($result)) {
              echo "<tr>\n";
              echo '<td headers="name">' . $row->name . "</td>\n";
              echo '<td headers="size">' . $row->size . "m²</td>\n</tr>\n";
            }
            mysqli_close($db);
          ?>
          </tbody>
        </table>
        <p class="menue">
          <a href="#" onclick="fenster('house_new')">Neues Haus anlegen</a>
        </p>
      </div>
  </body>
</html>
