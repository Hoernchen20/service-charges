<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>Nebenkostenabrechnung - Neues Haus anlegen</title>
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
                  house
                VALUES (\'\',\'' .
                  $_POST['name'] . '\',\'' .
                  $_POST['size'] . '\')';
      $result = mysqli_real_query($db, $query);
      mysqli_close($db);
    }
    
    echo '<body';
    if ($result) {
      echo ' onload="window.opener.location.href=\'house.php\'; window.close();"';
    } 
    echo '>';
  ?>
      <div class="head">
        <h1>Neues Haus anlegen</h1>
      </div>
      <div class="inhalt">
        <form action="house_new.php" method="post">
          <p>
            <label for="name">Name</label>
            <input type="text" name="name" class="feld" />
          </p>
          <p>
            <label for="size">Wohnfläche</label>
            <input type="text" name="size" class="feld" />
          </p>
          <p style="text-align: center">
            <input type="submit" value="Eingeben" />
          </p>
        </form>
      </div>
  </body>
</html>

