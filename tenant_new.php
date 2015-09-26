<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>Nebenkostenabrechnung - Neuen Mieter anlegen</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="author" content="Felix Horn">
    <meta http-equiv="language" content="de">
    <link rel="stylesheet" type="text/css" href="styles.css">
  </head>
  <?php
    include 'dbconnect.php';
    $result = FALSE;
    if ($_POST) {
      $query = 'INSERT INTO
                  tenant
                VALUES (\'\',\'' .
                  $_POST['name'] . '\',\'' .
                  $_POST['persons'] . '\',\'' .
                  $_POST['entry'] . '\',\'' .
                  $_POST['extract'] . '\',\'' .
                  $_POST['apartment_id'] . '\')';
      $result = mysqli_real_query($db, $query);
    }
    
    $query_apartment = 'SELECT
                            apartment.id, apartment.name
                          FROM
                            apartment
                          WHERE apartment.house_id =' . $_GET['param'] .
                          ' ORDER BY apartment.name ASC';
    $result_apartment = mysqli_query($db, $query_apartment);
    
    mysqli_close($db);
    
    echo '<body';
    if ($result) {
      echo ' onload="window.opener.location.href=\'tenant.php\'; window.close();"';
    } 
    echo '>';
  ?>
      <div class="head">
        <h1>Neuen Mieter anlegen</h1>
      </div>
      <div class="inhalt">
        <form action="tenant_new.php?param=<?php echo $_GET['param']; ?>" method="post">
          <p>
            <label for="name">Name</label>
            <input type="text" name="name" class="feld" />
          </p>
          <p>
            <label for="persons">Personen</label>
            <input type="text" name="persons" class="feld" />
          </p>
          <p>
            <label for="entry">Einzug</label>
            <input type="text" name="entry" class="feld" />
          </p>
          <p>
            <label for="extract">Auszug</label>
            <input type="text" name="extract" class="feld" />
          </p>
          <!-- Auswahlfeld für Wohnung 
          ein <select> über php mit daten aus der tabelle generieren-->
          <p>
            <label for="apartment_id">Wohnung</label>
            <select name="apartment_id">
              <?php
                while($row_apartment = mysqli_fetch_object($result_apartment)) {
                  echo '<option value="' . $row_apartment->id . '">' . $row_apartment->name ."</option>\n";
                }
              ?>
            </select>
          </p>
          <p style="text-align: center">
            <input type="submit" value="Eingeben" />
          </p>
        </form>
      </div>
  </body>
</html>

