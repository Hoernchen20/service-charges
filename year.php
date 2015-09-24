<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
  include 'dbconnect.php';
  
  $get_year = $_GET['year'];
  
  $query_house = 'SELECT
              house.id, house.name
            FROM
              house
            ORDER BY house.name ASC';
  $result_house = mysqli_query($db, $query_house);
//    $house_quantity = mysqli_num_rows($result);
    
    
    mysqli_close($db);
?>
<html>
<head>
    <title>Nebenkostenabrechnung <?php echo $get_year; ?></title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
    <div class="rahmen">
      <div class="inhalt">
        <h1>Nebenkostenabrechnung <?php echo $get_year; ?></h1>
          <?php
            while($row = mysqli_fetch_assoc($result_house)) {
              echo '<h2>' . $row[name] . '<h2>\n';
              echo '<h3>' . 
    } 
              }
            <h2><a href="tenant.php?year=<?php echo $get_year; ?>&amp;apartment_id=1">Erdgeschoss rechts</a></h2>
        </div>
    </div>
</html>

