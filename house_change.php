<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>Nebenkostenabrechnung - Haus ändern</title>
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
      
      $query = 'UPDATE
                  house
                SET
                  house.name = \'' . $post_name . '\',
                  house.size = \'' . $post_size . '\'
                WHERE
                  house.id = ' . $_GET['param'];
      $result = mysqli_real_query($db, $query);
    }
    
    /*
     * Check $_GET['param'] */
    if ( !(ctype_digit($_GET['param'])) ) {
      exit('Error: Param');
    }
    
    $query_house = 'SELECT
                      house.name, house.size
                    FROM
                      house
                    WHERE
                      house.id = ' . $_GET['param'];
    $result_house = mysqli_query($db, $query_house);
    mysqli_close($db);
    
    echo '<body';
    if ($result) {
      echo ' onload="window.opener.location.href=\'house.php\'; window.close();"';
    } 
    echo '>';
    echo '<div class="head">
            <h1>Haus ändern</h1>
          </div>
          <div class="inhalt">
            <form action="house_change.php?param=' . $_GET['param'] . '" method="post">';

    while($row_house = mysqli_fetch_object($result_house)) {
      echo '<p>
              <label for="name">Name:</label>
              <input type="text" name="name" class="feld" value="' . $row_house->name . '"/>
            </p>';
      echo '<p>
              <label for="size">Wohnfläche:</label>
              <input type="text" name="size" class="feld" value="' . $row_house->size . '"/>
            </p>';
    }
  ?>
        <p style="text-align: center">
          <input type="submit" value="Speichern" />
        </p>
      </form>
    </div>
  </body>
</html>

