<?php

function PrintHtmlHeader ($Title) {
  echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
  <html>
    <head>
      <title>' . $Title . '</title>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <meta name="author" content="Felix Horn">
      <meta http-equiv="language" content="de">
      <link rel="stylesheet" media="screen" type="text/css" href="styles.css">
      <link rel="stylesheet" media="print" type="text/css" href="printer.css">
      <script type="text/JavaScript" src="inc/menue.inc.js"></script>
    </head>
  <body>
  ';
}

function PrintHtmlTopNavigation () {
  echo '<div class="head">
    <h1>Nebenkostenabrechnung</h1>
  </div>
  <div class="topnavi">
    <ul>
      <li>
        <h3>Verwalten</h3>
        <ul class="subnavi">
          <li><a href="house.php">Haus</a></li>
          <li><a href="apartment.php">Wohnung</a></li>
          <li><a href="tenant.php">Mieter</a></li>
          <li><a href="costs_house.php">Kosten pro Haus</a></li>
          <li><a href="costs_person.php">Kosten pro Person</a></li>
          <li><a href="costs_tenant.php">Kosten pro Mieter</a></li>
          <li><a href="payment.php">Zahlungen</a></li>
        </ul>
      </li>
    </ul>
    <ul>
      <li>
        <h3>Erfassen</h3>
        <ul class="subnavi">
        </ul>
      </li>
    </ul>
    <ul>
      <li>
        <h3>Auswerten</h3>
        <ul class="subnavi">
<!--            <li><a href="analysis_tenant_month.php">Mieter pro Monat</a></li> -->
          <li><a href="analysis_apartment_month.php">Wohnung pro Monat</a></li>
          <li><a href="analysis_apartment_year.php">Wohnung pro Jahr</a></li>
        </ul>
      </li>
    </ul>
  </div>
  ';
}
?>
