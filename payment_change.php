<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>Nebenkostenabrechnung -Zahlung ändern</title>
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
    if ( !(ctype_digit($_GET['param_1'])) ) {
      exit('Error: Param_1');
    }
    if ( !(ctype_digit($_GET['param_2'])) ) {
      exit('Error: Param_2');
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

      $query = 'UPDATE
                  payment
                SET
                  payment.date = ' . $post_date . ',
                  payment.for_date = ' . $post_for_date . ',
                  payment.amount_kind = \'' . $_POST['amount_kind'] . '\',
                  payment.amount = \'' . $amount . '\'
                WHERE
                  payment.id = ' . $_GET['param_1'];
                  echo $query;
      $result = mysqli_real_query($db, $query);
    }
    
    $query_payment = 'SELECT
                        DATE_FORMAT(payment.date, \'%d.%m.%Y\') AS date,
                        MONTH(payment.for_date) AS month,
                        YEAR(payment.for_date) AS year,
                        payment.amount_kind, payment.amount
                      FROM
                        payment
                    WHERE payment.id =' . $_GET['param_1'];
    $result_payment = mysqli_query($db, $query_payment);
    mysqli_close($db);
    
    echo '<body';
    if ($result) {
      echo ' onload="window.opener.location.href=\'payment.php?id=' . $_GET['param_2'] . '\'; window.close();"';
    } 
    echo '>';
    echo '<div class="head">
            <h1>Zahlung ändern</h1>
          </div>
          <div class="inhalt">
            <form action="payment_change.php?param_1=' . $_GET['param_1'] . '&param_2=' . $_GET['param_2'] . '" method="post">';

    while($row_payment = mysqli_fetch_object($result_payment)) {
      echo '<p>
              <label for="date">Buchung (TT.MM.YYYY):</label>
              <input type="text" name="date" class="feld" value="' . $row_payment->date . '"/>
            </p>';
      echo '<p>
              <label for="for_month">Für Monat:</label>
              <input type="text" name="for_month" class="feld" value="' . $row_payment->month . '"/>
            </p>';
      echo '<p>
              <label for="for_year">Für Jahr:</label>
              <input type="text" name="for_year" class="feld" value="' . $row_payment->year . '"/>
            </p>';
      echo '<p>
              <label for="amount_kind">Art:</label>
              <select name="amount_kind">
              <option value="0"';
      if ($row_payment->amount_kind == 0) {
        echo ' selected';
      }
      echo '>Miete</option>
              <option value="1"';
      if ($row_payment->amount_kind == 1) {
        echo ' selected';
      }
      echo '>Nebenkosten</option>
              <option value="2"';
      if ($row_payment->amount_kind == 2) {
        echo ' selected';
      }
      echo '>Kaution</option>
            </select>
          </p>';
      echo '<p>
              <label for="amount">Betrag:</label>
              <input type="text" name="amount" class="feld" value="' . number_format($row_payment->amount, 2, ',', '') . '"/>
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

