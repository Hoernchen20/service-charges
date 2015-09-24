<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<?php
    include 'dbconnect.php';
    
    $get_year = $_GET['year'];
    $get_apartment_id = $_GET['apartment_id'];
    $get_tenant_id = $_GET['tenant_id'];

    $query = 'SELECT
                apartment.name AS apartment_name, house.name AS house_name,
                apartment.size AS apartment_size, house.size AS house_size,
                ROUND((apartment.size * 100 / house.size ), 2) AS apartment_percent
              FROM
                apartment
              RIGHT JOIN
                house ON apartment.house_id = house.id
              WHERE apartment.id =' . $get_apartment_id;
    $result = mysqli_query($db, $query);
    
    while($row = mysqli_fetch_object($result)) {
		  $apartment_name = $row->apartment_name;
		  $house_name = $row->house_name;
      $apartment_size = $row->apartment_size;
      $house_size = $row->house_size;
      $apartment_percent = $row->apartment_percent;
    }
    
    mysqli_close($db);

    
    
?>
<html>
    <head>
	    <title><?php echo 'Nebenkosten ' . $get_year . ': ' . $house_name
	                        . ' - ' . $apartment_name; ?></title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta name="author" content="Felix Horn">
        <link rel="stylesheet" type="text/css" href="../styles.css">
    </head>

    <body>
      <div class="rahmen">
        <div class="topnavi">
          <ul>
            <li><a href="index.htm">Home</a></li>
          </ul>
        </div>
        <div class="inhalt">
          <p>
	        <?php
	          echo 'Wohnfläche: ' . $apartment_size . 'm²';
	          
		      ?>
		      </p>
		    </div>
		  </div>
    </body>
</html>
