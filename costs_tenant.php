<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>Nebenkostenabrechnung - Kosten pro Mieter</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="author" content="Felix Horn">
    <meta http-equiv="language" content="de">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script type="text/JavaScript" src="inc/menue.inc.js"></script>
  </head>
  <body>
    <div class="head">
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
    <div class="inhalt">
      <h2>Verwalten - Kosten pro Mieter</h2>
      <?php
        include 'inc/dbconnect.inc.php';

        $query_tenant = 'SELECT
                           tenant.id, tenant.name, tenant.persons,
                           DATE_FORMAT(tenant.entry, \'%d.%m.%Y\') AS entry,
                           DATE_FORMAT(tenant.extract, \'%d.%m.%Y\') AS extract,
                           apartment.name AS apartment_name
                         FROM
                           tenant
                         INNER JOIN
                           apartment ON apartment.id = tenant.apartment_id
                         ORDER BY tenant.extract DESC';
        $result_tenant = mysqli_query($db, $query_tenant);

        while($row_tenant = mysqli_fetch_object($result_tenant)) {
          echo '<table>
                  <caption>' . $row_tenant->name . ' - ' . 
                    $row_tenant->persons . ' Personen - ' . 
                    $row_tenant->entry . ' - ' . $row_tenant->extract . ' - ' . 
                    $row_tenant->apartment_name . '</caption>
                  <thead>
                    <tr>
                      <th id="year">Jahr</th>
                      <th id="usage">Zweck</th>
                      <th id="amount">Kosten</th>
                      <th id="change">Ändern</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <td headers="name" colspan="4">
                        <a href="#" onclick="fenster_param(\'costs_tenant_new\',\'' . $row_tenant->id . '\')">Neue Kosten pro Mieter erfassen</a>
                      </td>
                    </tr>
                  </tfoot>
                  <tbody>';
              
          $query_costs = 'SELECT
                            costs_tenant.id, costs_tenant.year, costs_tenant.usage, costs_tenant.amount
                          FROM
                            costs_tenant
                          WHERE costs_tenant.tenant_id = ' . $row_tenant->id . '
                          ORDER BY costs_tenant.year DESC, costs_tenant.usage ASC';
          $result_costs = mysqli_query($db, $query_costs);

          while($row_costs = mysqli_fetch_object($result_costs)) {
            echo "<tr>\n";
            echo '<td headers="year">' . $row_costs->year . "</td>\n";
            echo '<td headers="usage">' . $row_costs->usage . "</td>\n";
            echo '<td headers="amount" class="right">' . number_format($row_costs->amount, 2, ',', '') . "€</td>\n";
            echo '<td headers="change"><a href="#" onclick="fenster_param(\'costs_tenant_change\', \'' . $row_costs->id . "')\">Ändern</a></td>\n</tr>\n";
          }

          echo '</tbody>
              </table>';
        }
        mysqli_close($db);
      ?>
    </div>
  </body>
</html>
