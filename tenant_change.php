<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>Nebenkostenabrechnung - Mieter ändern</title>
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
    
    /*
     * Query for apartment <option> tag */
    $query_apartment = 'SELECT
                          apartment.id, apartment.name
                        FROM
                          apartment
                        WHERE apartment.house_id =' . $_GET['param_1'] . '
                        ORDER BY apartment.name ASC';
    $result_apartment = mysqli_query($db, $query_apartment);
    
    /*
     * Query for input field data */
    $query_tenant = 'SELECT
                       tenant.name, tenant.persons,
                       DATE_FORMAT(tenant.entry, \'%d.%m.%Y\') AS entry,
                       DATE_FORMAT(tenant.extract, \'%d.%m.%Y\') AS extract,
                       tenant.apartment_id
                     FROM
                       tenant
                     WHERE tenant.id =' . $_GET['param_2'];
    $result_tenant = mysqli_query($db, $query_tenant);
    
    if ($_POST) {
      /* 
       * Check input */
      $post_name = mysqli_real_escape_string($db, $_POST['name']);
      
      if ( !(ctype_digit($_POST['persons'])) || !($_POST['persons'] < 11) ) {
        exit('Fehler: Personen');
      }
      
      $post_entry = strtodate($_POST['entry']);
      if ( ($post_entry == false) || ($post_entry == 'NULL') ) {
        exit('Fehler: Einzugsdatum');
      }
      
      $post_extract = strtodate($_POST['extract']);
      if ($post_extract == false) {
        exit('Fehler: Auszugsdatum');
      }
      
      if ( !(ctype_digit($_POST['apartment_id'])) ) {
        exit('Fehler: Wohnung');
      }
      
      /*
       * Put in database */
      $query = 'UPDATE
                  tenant
                SET
                  tenant.name = \'' . $post_name . '\',
                  tenant.persons = \'' . $_POST['persons'] . '\',
                  tenant.entry = ' . $post_entry . ',
                  tenant.extract = ' . $post_extract . ',
                  tenant.apartment_id = \'' . $_POST['apartment_id'] . '\'
                WHERE
                  tenant.id = ' . $_GET['param_2'];
                  echo $query;
      $result = mysqli_real_query($db, $query) or die (mysqli_error($db));
    }

    mysqli_close($db);
    
    echo '<body';
    if ($result) {
      echo ' onload="window.opener.location.href=\'tenant.php\'; window.close();"';
    } 
    echo '>';
    echo '<div class="head">
            <h1>Mieter ändern</h1>
          </div>
          <div class="inhalt">
            <form action="tenant_change.php?param_1=' . $_GET['param_1'] . '&param_2=' . $_GET['param_2'] . '" method="post">';

    while($row_tenant = mysqli_fetch_object($result_tenant)) {
      echo '<p>
              <label for="name">Name:</label>
              <input type="text" name="name" class="feld" value="' . $row_tenant->name . '"/>
            </p>';
      echo '<p>
              <label for="persons">Personen:</label>
              <input type="text" name="persons" class="feld" value="' . $row_tenant->persons . '"/>
            </p>';
      echo '<p>
              <label for="entry">Einzug (TT.MM.YYYY):</label>
              <input type="text" name="entry" class="feld" value="' . $row_tenant->entry . '"/>
            </p>';
      echo '<p>
              <label for="extract">Auszug (TT.MM.YYYY):</label>
              <input type="text" name="extract" class="feld" value="' . $row_tenant->extract . '"/>
            </p>';
      echo '<p>
              <label for="apartment_id">Wohnung:</label>
              <select name="apartment_id">';
      while($row_apartment = mysqli_fetch_object($result_apartment)) {
        echo '<option value="' . $row_apartment->id . '" ';
        if ($row_tenant->apartment_id == $row_apartment->id) {
          echo 'selected';
        }
        echo '>' . $row_apartment->name ."</option>\n";
      }
    }
  ?> 
          </select>
        </p>
        <p style="text-align: center">
          <input type="submit" value="Speichern" />
        </p>
      </form>
    </div>
  </body>
</html>
