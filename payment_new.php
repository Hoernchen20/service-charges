<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>Nebenkostenabrechnung - Neue Zahlung erfassen</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="author" content="Felix Horn">
    <meta http-equiv="language" content="de">
    <link rel="stylesheet" type="text/css" href="styles.css">
  </head>
  <?php
    include 'inc/php_functions.inc.php';
    include 'inc/dbconnect.inc.php';
    $result = FALSE;
    
    /*
    * Check $_GET['param'] */
    if ( !(ctype_digit($_GET['param'])) ) {
      exit('Error: param');
    }
    
    if ($_POST) {
      /* 
       * Check input */
      $post_date = strtodate($_POST['date']);
      if ( ($post_date == false) || ($post_date == 'NULL') ) {
        exit('Fehler: Buchungsdatum');
      }
      
      $post_for_date = strtodate( '00.' . $_POST['for_month'] . '.' . $_POST['for_year'] );
      if ($post_for_date == false) {
        exit('Fehler: Datum');
      }
      
      if ( !(ctype_digit($_POST['amount_kind'])) ) {
        exit('Fehler: Art');
      }
      
      if ( !(is_numeric($_POST['amount'])) ) {
        if ( ctype_digit(str_replace(',', '', $_POST['amount'])) ) {
          $amount = str_replace(',', '.', $_POST['amount']);
        } else {
          exit('Fehler: Kosten');
        }
      } else {
        $amount = $_POST['amount'];
      }
      
      /*
       * Put in database */
      $query = 'INSERT INTO
                  payment
                VALUES (\'\',\'' .
                  $_GET['param'] . '\',' .
                  $post_date . ',' .
                  $post_for_date . ',\'' .
                  $_POST['amount_kind'] . '\',\'' .
                  $amount . '\')';
      $result = mysqli_real_query($db, $query);
    }
    mysqli_close($db);
    
    echo '<body';
    if ($result) {
      echo ' onload="window.opener.location.href=\'payment.php?id=' . $_GET['param'] . '\'; window.close();"';
    } 
    echo '>';
  ?>
    <div class="head">
      <h1>Neue Zahlung erfassen</h1>
    </div>
    <div class="inhalt">
      <form action="payment_new.php?param=<?php echo $_GET['param']; ?>" method="post">
        <p>
          <label for="date">Buchung (TT.MM.YYYY):</label>
          <input type="text" name="date" class="feld" />
        </p>
        <p>
          <label for="for_month">Für Monat:</label>
          <input type="text" name="for_month" class="feld" />
        </p>
        <p>
          <label for="for_year">Für Jahr:</label>
          <input type="text" name="for_year" class="feld" />
        </p>
        <p>
          <label for="amount_kind">Art:</label>
          <select name="amount_kind">
            <option value="0">Miete</option>
            <option value="1">Nebenkosten</option>
            <option value="2">Kaution</option>
          </select>
        </p>
        <p>
          <label for="amount">Betrag</label>
          <input type="text" name="amount" class="feld" />
        </p>
        <p style="text-align: center">
          <input type="submit" value="Eingeben" />
        </p>
      </form>
    </div>
  </body>
</html>
