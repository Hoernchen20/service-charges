<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<?php
    include 'dbconnect.php';
    
    $get_year = $_GET['year'];
    $get_apartment_id = $_GET['apartment_id'];
    
    $query = 'SELECT
                tenant.id, tenant.name, tenant.entry, tenant.extract
              FROM
                tenant
              WHERE tenant.apartment_id = ' . $get_apartment_id . '
                AND (YEAR(tenant.entry) <= ' . $get_year . ' <= YEAR(tenant.extract)
                OR (YEAR(tenant.entry) <= ' . $get_year . ' AND tenant.extract IS NULL))';
    $result = mysqli_query($db, $query);   
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
	          echo '<ul>';
	          while($row = mysqli_fetch_object($result)) {
	            if ($row->extract == NULL) {
	              $row->extract = 'Jetzt';
	            }
	            echo '<li><a href="month.php?year=' . $get_year
	              . '&amp;apartment_id=' . $get_apartment_id
	              . '&amp;tenant_id=' . $row->id . '">' . $row->name . ': ' . $row->entry . ' - ' . $row->extract . '</a></li>';
		        }
		        echo '</ul>';
	        ?>
		      </p>
		    </div>
		  </div>
    </body>
</html>
