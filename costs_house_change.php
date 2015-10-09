<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>Nebenkostenabrechnung - Kosten pro Haus ändern</title>
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
      if ( !(ctype_digit($_POST['year'])) || $_POST['year'] < 1970 || $_POST['year'] > 2100 || strlen($_POST['year']) != 4) {
        exit('Fehler: Jahr');
      }
       
      $post_usage = mysqli_real_escape_string($db, $_POST['usage']);      
      
      if ( !(is_numeric($_POST['amount'])) ) {
        if ( ctype_digit(str_replace(',', '', $_POST['amount'])) ) {
          $amount = str_replace(',', '.', $_POST['amount']);
        } else {
          exit('Fehler: Kosten');
        }
      } else {
        $amount = $_POST['amount'];
      }

      $query = 'UPDATE
                  costs_house
                SET
                  costs_house.year = \'' . $_POST['year'] . '\',
                  costs_house.usage = \'' . $post_usage . '\',
                  costs_house.amount = \'' . $amount . '\'
                WHERE
                  costs_house.id = ' . $_GET['param'];
      $result = mysqli_real_query($db, $query);
    }
    
    $query_costs = 'SELECT
                      costs_house.year, costs_house.usage, costs_house.amount
                    FROM
                      costs_house
                    WHERE costs_house.id =' . $_GET['param'];
    $result_costs = mysqli_query($db, $query_costs);
    mysqli_close($db);
    
    echo '<body';
    if ($result) {
      echo ' onload="window.opener.location.href=\'costs_house.php\'; window.close();"';
    } 
    echo '>';
    echo '<div class="head">
            <h1>Kosten pro Haus ändern</h1>
          </div>
          <div class="inhalt">
            <form action="costs_house_change.php?param=' . $_GET['param'] .'" method="post">';

    while($row_costs = mysqli_fetch_object($result_costs)) {
      echo '<p>
              <label for="year">Jahr (JJJJ):</label>
              <input type="text" name="year" class="feld" value="' . $row_costs->year . '"/>
            </p>';
      echo '<p>
              <label for="usage">Zweck:</label>
              <input type="text" name="usage" class="feld" value="' . $row_costs->usage . '"/>
            </p>';
      echo '<p>
              <label for="amount">Kosten:</label>
              <input type="text" name="amount" class="feld" value="' . number_format($row_costs->amount, 2, ',', '') . '"/>
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

