<?php
    // Get JSON Data from BTC-E
    function get($url) {
        return json_decode(file_get_contents($url), true);
    }

    // Check for valid inputs and sanitise
    function validate($val) {
        if($val != "") {
            if(is_numeric($val) == true && $val >= 0) {
                return $val;
            } else {
                die("Input Error");
            }
        } else {
            die("Input Error");
        }
    }
    
    // Truncate BTC/LTC Value to 8 places without rounding
    // truncate($value, places)
    function truncate($val, $f) {
        if(($p = strpos($val, '.')) !== false) {
            $val = floatval(substr($val, 0, $p + 1 + $f));
        }
        return $val;
    }
    
    // Stackoverflow function to read the lines from a CSV file
    function read_csv($filename) {
        $handle = fopen($filename, "r");
        echo '<table border = "2" align = "center">';

        // Displaying headers
        $csvcontents = fgetcsv($handle);
        echo '<tr>';
        foreach($csvcontents as $headercolumn) {
            echo "<th>$headercolumn</th>";
        }
        echo '</tr>';
        
        // Displaying contents
        while($csvcontents = fgetcsv($handle)) {
            echo '<tr>';
            foreach($csvcontents as $column) {
                echo "<td align = 'right'>$column</td>";
            }
            echo '</tr>';
        }
        echo '</table>';
        fclose($handle);
    }

    // LTC Stats
    $LTC_USD = get("https://btc-e.com/api/2/ltc_usd/ticker");
    $LTC_USD_LAST = round(validate($LTC_USD["ticker"]["last"]),2);

    // BTC Stats
    $BTC_USD = get("https://btc-e.com/api/2/btc_usd/ticker");
    $BTC_USD_LAST = round(validate($BTC_USD["ticker"]["last"]),2);

    // Logic
    $POWER_COST = 50; // Change me if needed
    $LTC_THIS_WEEK = validate($_REQUEST['ltc_this_week']);
    $POWER_LTC = truncate($POWER_COST / $LTC_USD_LAST, 8);
    $TOTAL_LTC_TO_SHARE = $LTC_THIS_WEEK - $POWER_LTC;

    // Adrian
    $ADRIAN_LTC = truncate($TOTAL_LTC_TO_SHARE / 4, 8);
    $ADRIAN_USD = round(($ADRIAN_LTC * $LTC_USD_LAST),2);

    // Dushan
    $DUSHAN_LTC = truncate($TOTAL_LTC_TO_SHARE / 4, 8);
    $DUSHAN_USD = round(($DUSHAN_LTC * $LTC_USD_LAST),2);

    // Jarid
    $JARID_LTC = truncate($TOTAL_LTC_TO_SHARE / 4, 8);
    $JARID_USD = round(($JARID_LTC * $LTC_USD_LAST),2);

    // Jordan (With extra for Power)
    $JORDAN_LTC = truncate(($TOTAL_LTC_TO_SHARE / 4) + $POWER_LTC, 8);
    $JORDAN_USD = round(($JORDAN_LTC * $LTC_USD_LAST),2);
    
    // Deal with the stats section
    $STATS = get('stats.json');
    $STATS_ARR = array (
        'total_ltc' => $STATS["total_ltc"] + $LTC_THIS_WEEK,
        'total_usd' => $STATS["total_usd"] + ($LTC_THIS_WEEK * $LTC_USD_LAST),
        'total_pwr' => $STATS["total_pwr"] + $POWER_LTC,
        'total_adrian_ltc' => $STATS["total_adrian_ltc"] + $ADRIAN_LTC,
        'total_dushan_ltc' => $STATS["total_dushan_ltc"] + $DUSHAN_LTC,
        'total_jarid_ltc' => $STATS["total_jarid_ltc"] + $JARID_LTC,
        'total_jordan_ltc' => $STATS["total_jordan_ltc"] + $JORDAN_LTC,
        'total_adrian_usd' => $STATS["total_adrian_usd"] + $ADRIAN_USD,
        'total_dushan_usd' => $STATS["total_dushan_usd"] + $DUSHAN_USD,
        'total_jarid_usd' => $STATS["total_jarid_usd"] + $JARID_USD,
        'total_jordan_usd' => $STATS["total_jordan_usd"] + $JORDAN_USD
    );
    
    $STATS_STRING = base64_encode(serialize($STATS_ARR));
?>

<html>
    <head>
        <title>Money Calculator</title>
        <link rel = "stylesheet" type = "text/css" href = "styles.css" />
    </head>
    <body>
        <div id = "content">
            <h1>Money Calculator</h1>
            <?php
                // Done in a single PHP Block for performance
                echo "<p>Bitcoin Price (USD): $BTC_USD_LAST<br />Litecoin Price (USD): $LTC_USD_LAST</p>";
                echo "<p>Total LTC Mined this week: $LTC_THIS_WEEK<br />Power in LTC for this week: $POWER_LTC</p>";
            ?>
            <div id = "left">           
                <br /><br /><br /><br />
                <h1>Please pay as follows:</h1>
                <table class = "center" border = "1" cellpadding = "6">
                    <tr><td>Adrian</td><td><?php echo "$ADRIAN_LTC LTC" ?></td><td><?php echo "$ADRIAN_USD USD" ?></td></tr>
                    <tr><td>Dushan</td><td><?php echo "$DUSHAN_LTC LTC" ?></td><td><?php echo "$JARID_USD USD" ?></td></tr>
                    <tr><td>Jarid</td><td> <?php echo "$JARID_LTC LTC" ?></td><td><?php echo "$JARID_USD USD" ?></td></tr>
                    <tr><td>Jordan</td><td><?php echo "$JORDAN_LTC LTC" ?></td><td><?php echo "$JORDAN_USD USD" ?></td></tr>
                </table>
                <br />
                <form method = "POST" action = "process.php">
                    <input type = "hidden" name = "LTC_THIS_WEEK" value = "<?php echo $LTC_THIS_WEEK ?>" />
                    <input type = "hidden" name = "POWER_LTC" value = "<?php echo $POWER_LTC ?>" />
                    <input type = "hidden" name = "LTC_USD" value = "<?php echo $LTC_USD_LAST ?>" />
                    <input type = "hidden" name = "AM_LTC" value = "<?php echo $ADRIAN_LTC ?>" />
                    <input type = "hidden" name = "JS_LTC" value = "<?php echo $JARID_LTC ?>" />
                    <input type = "hidden" name = "DKW_LTC" value = "<?php echo $DUSHAN_LTC ?>" />
                    <input type = "hidden" name = "JK_LTC" value = "<?php echo $JORDAN_LTC ?>" />
                    <input type = "hidden" name = "STATS_INFO" value = "<?php echo $STATS_STRING ?>" />
                    <input type = "submit" value = "Save This Data" />
                </form>
            </div>
            
            <div id = "right">          
                <h1>Stats:</h1>
                <table class = "center" border = "1" cellpadding = "6">
                    <tr><td>Total LTC Mined:</td><td><?php echo $STATS_ARR['total_ltc']; echo " LTC" ?></td></tr>
                    <tr><td>Total USD Mined:</td><td><?php echo $STATS_ARR['total_usd']; echo " USD" ?></td></tr>
                    <tr><td>Total Power Costs:</td><td> <?php echo $STATS_ARR['total_pwr']; echo " LTC" ?></td></tr>
                    <tr><td>Adrian's total LTC:</td><td> <?php echo $STATS_ARR['total_adrian_ltc']; echo " LTC" ?></td></tr>
                    <tr><td>Adrian's total USD:</td><td> <?php echo $STATS_ARR['total_adrian_usd']; echo " USD" ?></td></tr>
                    <tr><td>Dushan's total LTC:</td><td> <?php echo $STATS_ARR['total_dushan_ltc']; echo " LTC" ?></td></tr>
                    <tr><td>Dushan's total USD:</td><td> <?php echo $STATS_ARR['total_dushan_usd']; echo " USD" ?></td></tr>
                    <tr><td>Jarid's total LTC:</td><td> <?php echo $STATS_ARR['total_jarid_ltc']; echo " LTC" ?></td></tr>
                    <tr><td>Jarid's total USD:</td><td> <?php echo $STATS_ARR['total_jarid_usd']; echo " USD" ?></td></tr>
                    <tr><td>Jordan's total LTC:</td><td> <?php echo $STATS_ARR['total_jordan_ltc']; echo " LTC" ?></td></tr>
                    <tr><td>Jordan's total USD:</td><td> <?php echo $STATS_ARR['total_jordan_usd']; echo " USD" ?></td></tr>
                </table>
            </div>
        </div>
        
        <div id = "records">
            <h1>Records:</h1>
            <?php read_csv('history.csv'); ?>
        </div>
    </body>
</html>