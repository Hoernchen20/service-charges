<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>Nebenkostenabrechnung - Wohnung ändern</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="author" content="Felix Horn">
    <meta http-equiv="language" content="de">
    <link rel="stylesheet" type="text/css" href="styles.css">
  </head>
  <?php
    include 'inc/dbconnect.inc.php';
    $result = FALSE;
    
    /*
       * Check $_GET['param'] */
      if ( !(ctype_digit($_GET['param'])) ) {
        exit('Error: Param');
      }
    
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
                  apartment
                SET
                  apartment.name = \'' . $post_name . '\',
                  apartment.size = \'' . $post_size . '\'
                WHERE
                  apartment.id = ' . $_GET['param'];
      $result = mysqli_real_query($db, $query);
    }
    
    $query_apartment = 'SELECT
                          apartment.name, apartment.size
                        FROM
                          apartment
                        WHERE
                          apartment.id = ' . $_GET['param'];
    $result_apartment = mysqli_query($db, $query_apartment);
    mysqli_close($db);
    
    echo '<body';
    if ($result) {
      echo ' onload="window.opener.location.href=\'apartment.php\'; window.close();"';
    } 
    echo '>';
    echo '<div class="head">
              <h1>Wohnung ändern</h1>
            </div>
            <div class="inhalt">
              <form action="apartment_change.php?param=' . $_GET['param'] . '" method="post">';

    while($row_apartment = mysqli_fetch_object($result_apartment)) {
      echo '<p>
              <label for="name">Name:</label>
              <input type="text" name="name" class="feld" value="' . $row_apartment->name . '"/>
            </p>';
      echo '<p>
              <label for="size">Wohnfläche:</label>
              <input type="text" name="size" class="feld" value="' . $row_apartment->size . '"/>
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
