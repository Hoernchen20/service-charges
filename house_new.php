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
    include 'inc/dbconnect.inc.php';
    $result = FALSE;
    
    if ($_POST) {
      /* 
       * Check input */
      $post_name = mysqli_real_escape_string($db, $_POST['name']);
      
      if ( !(is_numeric($_POST['size'])) ) {
        exit('Fehler: Wohnfläche');
      } else {
        $post_size = str_replace(',', '.', $_POST['size']);
      }
      
      $query = 'INSERT INTO
                  house
                VALUES (\'\',\'' .
                  $post_name . '\',\'' .
                  $post_size . '\')';
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
          <label for="name">Name:</label>
          <input type="text" name="name" class="feld" />
        </p>
        <p>
          <label for="size">Wohnfläche:</label>
          <input type="text" name="size" class="feld" />
        </p>
        <p style="text-align: center">
          <input type="submit" value="Eingeben" />
        </p>
      </form>
    </div>
  </body>
</html>

