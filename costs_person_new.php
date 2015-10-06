<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>Nebenkostenabrechnung - Neue Kosten pro Person erfassen</title>
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
      
      /*
       * Check $_GET['param'] */
      if ( !(ctype_digit($_GET['param'])) ) {
        exit('Error: Param');
      }
      
      $query = 'INSERT INTO
                  costs_person
                VALUES (\'\',\'' .
                  $_POST['year'] . '\',\'' .
                  $post_usage . '\',\'' .
                  $amount . '\',\'' .
                  $_GET['param'] . '\')';
      $result = mysqli_real_query($db, $query);
      mysqli_close($db);
    }
    
    echo '<body';
    if ($result) {
      echo ' onload="window.opener.location.href=\'costs_person.php\'; window.close();"';
    } 
    echo '>';
  ?>
    <div class="head">
      <h1>Neue Kosten pro Person erfassen</h1>
    </div>
    <div class="inhalt">
      <form action="costs_person_new.php?param=<?php echo $_GET['param']; ?>" method="post">
        <p>
          <label for="year">Jahr (JJJJ):</label>
          <input type="text" name="year" class="feld" />
        </p>
        <p>
          <label for="usage">Zweck:</label>
          <input type="text" name="usage" class="feld" />
        </p>
        <p>
          <label for="amount">Kosten:</label>
          <input type="text" name="amount" class="feld" />
        </p>
        <p style="text-align: center">
          <input type="submit" value="Eingeben" />
        </p>
      </form>
    </div>
  </body>
</html>

