
        
        

        
          
          

          

          
          
          /*
           * Summe Kosten */
          echo "<tr>
                  <td class=\"sum\" headers=\"usage\" colspan=\"3\">Summe</td>\n
                  <td class=\"sum right\" headers=\"sum\">" . number_format($costs_sum, 2, ',', '') . "€</td>\n
                </tr>\n";
          
          
            echo '<h2>' . $house_name . ' - ' . $apartment_name . "</h2>\n";
            echo '<h2>Zusammenfassung' . "</h2>\n";
            echo '<p>Mieter: ' . $old_tenant_name . "</p>\n";
            echo '<table class="analysis">
                  <thead>
                    <tr>
                      <th id="usage">Monat</th>
                      <th class="right" id="amount">Betrag</th>
                    </tr>
                  </thead>
                  <tbody>';
            for ($i = 1; $i < 13; $i++) {
              echo "<tr>
                      <td headers=\"usage\">" . ReturnMonthName($i) . "</td>\n
                      <td class=\"right\" headers=\"sum\">" . number_format($costs_diff_month[$i], 2, ',', '') . "€</td>\n
                    </tr>\n";
            }
            
            echo "<tr>
                    <td class=\"sum\" headers=\"usage\">Summe Betrag</td>\n
                    <td class=\"sum right\" headers=\"sum\">" . number_format(array_sum($costs_diff_month), 2, ',', '') . "€</td>\n
                  </tr>\n";
                    
            echo '</tbody>
              </table>';
                  
            
            $costs_diff_month = array_fill(1, 12, 0);
          }

          
          
          
          
                
          
          
          /*
           * Gezahlte Nebenkosten */
          $payment_amount = GetMonthAmountExtra($db, $tenant_id, $get_year, $month);
          
          echo "<td headers=\"usage\" colspan=\"3\">Gezahlt</td>\n";
          echo '<td headers="sum" class="right">' . number_format($payment_amount, 2, ',', '') . "€</td>\n</tr>\n";
          
          /*
           * Differenz zwischen Kosten und gezahlten Nebenkosten */
          $costs_diff = $costs_sum - $payment_amount;
          $costs_diff_month[$month] = $costs_diff;
          echo "<tr>
                  <td class=\"sum\" headers=\"usage\" colspan=\"3\">Betrag</td>\n
                  <td class=\"sum right\" headers=\"sum\">" . number_format($costs_diff, 2, ',', '') . "€</td>\n
                </tr>\n";
          
          echo '</tbody>
              </table>';
              
          if ($month == 12) {
            echo '<h2>Zusammenfassung' . "</h2>\n";
            echo '<p>Mieter: ' . $tenant_name . "</p>\n";
            echo '<table class="analysis">
                  <thead>
                    <tr>
                      <th id="usage">Monat</th>
                      <th class="right" id="amount">Betrag</th>
                    </tr>
                  </thead>
                  <tbody>';
            for ($i = 1; $i < 13; $i++) {
              echo "<tr>
                      <td headers=\"usage\">" . ReturnMonthName($i) . "</td>\n
                      <td class=\"right\" headers=\"sum\">" . number_format($costs_diff_month[$i], 2, ',', '') . "€</td>\n
                    </tr>\n";
            }
            echo "<tr>
                    <td class=\"sum\" headers=\"usage\">Summe Betrag</td>\n
                    <td class=\"sum right\" headers=\"sum\">" . number_format(array_sum($costs_diff_month), 2, ',', '') . "€</td>\n
                  </tr>\n";

            echo '</tbody>
              </table>';
          }
      
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        echo "<tr>
                <td headers=\"usage\">" . ReturnMonthName($i) . "</td>\n
                <td class=\"right\" headers=\"sum\">" . number_format($costs_diff_month[$i], 2, ',', '') . "€</td>\n
              </tr>\n";
      }
      echo "<tr>
              <td class=\"sum\" headers=\"usage\">Summe Betrag</td>\n
              <td class=\"sum right\" headers=\"sum\">" . number_format(array_sum($costs_diff_month), 2, ',', '') . "€</td>\n
            </tr>\n";
