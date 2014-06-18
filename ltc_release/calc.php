<?php
    // Get JSON Data from BTC-E
    function get($url) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1, // Return response as string, needed so we can process
            CURLOPT_URL => "$url",
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0' // Give BTC-E a real user agent incase they block strange ones
        ));
        $response = curl_exec($curl);
        return json_decode($response, true);
        curl_close($curl);
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

    // BTC Stats
    $BTC_USD = get("https://btc-e.com/api/2/btc_usd/ticker");
    $BTC_USD_LAST = round(validate($BTC_USD["ticker"]["last"]),2);

    // Logic
    $POWER_COST = 40; // Change me if needed
    $BTC_THIS_WEEK = validate($_REQUEST['btc_this_week']);
    $USD_THIS_WEEK = round(($BTC_THIS_WEEK * $BTC_USD_LAST),2);
    $POWER_BTC = truncate($POWER_COST / $BTC_USD_LAST, 8);
    $TOTAL_BTC_TO_SHARE = $BTC_THIS_WEEK - $POWER_BTC;

    // Adrian
    $ADRIAN_BTC = truncate($TOTAL_BTC_TO_SHARE / 4, 8);
    $ADRIAN_USD = round(($ADRIAN_BTC * $BTC_USD_LAST),2);

    // Dushan
    $DUSHAN_BTC = truncate($TOTAL_BTC_TO_SHARE / 4, 8);
    $DUSHAN_USD = round(($DUSHAN_BTC * $BTC_USD_LAST),2);

    // Jarid (With extra for Power)
    $JARID_BTC = truncate(($TOTAL_BTC_TO_SHARE / 4) + $POWER_BTC, 8);
    $JARID_USD = round(($JARID_BTC * $BTC_USD_LAST),2);

    // Jordan
    $JORDAN_BTC = truncate($TOTAL_BTC_TO_SHARE / 4, 8);
    $JORDAN_USD = round(($JORDAN_BTC * $BTC_USD_LAST),2);
    
    // Deal with the stats section
    $STATS = get('stats.json');
    $STATS_ARR = array (
        'total_btc' => $STATS["total_btc"] + $BTC_THIS_WEEK,
        'total_usd' => $STATS["total_usd"] + round(($BTC_THIS_WEEK * $BTC_USD_LAST),2),
        'total_pwr' => $STATS["total_pwr"] + $POWER_BTC,
        'total_adrian_btc' => $STATS["total_adrian_btc"] + $ADRIAN_BTC,
        'total_dushan_btc' => $STATS["total_dushan_btc"] + $DUSHAN_BTC,
        'total_jarid_btc' => $STATS["total_jarid_btc"] + $JARID_BTC - $POWER_BTC,
        'total_jordan_btc' => $STATS["total_jordan_btc"] + $JORDAN_BTC,
        'total_adrian_usd' => $STATS["total_adrian_usd"] + $ADRIAN_USD,
        'total_dushan_usd' => $STATS["total_dushan_usd"] + $DUSHAN_USD,
        'total_jarid_usd' => $STATS["total_jarid_usd"] + $JARID_USD - $POWER_COST,
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
                echo "<p>Bitcoin Price (USD): $BTC_USD_LAST</p>";
                echo "<p>Total BTC Mined this week: $BTC_THIS_WEEK</p>";
                echo "<p>Total USD Mined this week: $USD_THIS_WEEK</p>";
                echo "<p>Power in BTC for this week: $POWER_BTC</p>";
            ?>
            <div id = "left">           
                <br /><br /><br /><br />
                <h1>Please pay as follows:</h1>
                <table class = "center" border = "1" cellpadding = "6">
                    <tr><td>Adrian</td><td><?php echo "$ADRIAN_BTC BTC" ?></td><td><?php echo "$ADRIAN_USD USD" ?></td></tr>
                    <tr><td>Dushan</td><td><?php echo "$DUSHAN_BTC BTC" ?></td><td><?php echo "$DUSHAN_USD USD" ?></td></tr>
                    <tr><td>Jarid</td><td> <?php echo "$JARID_BTC BTC" ?></td><td><?php echo "$JARID_USD USD" ?></td></tr>
                    <tr><td>Jordan</td><td><?php echo "$JORDAN_BTC BTC" ?></td><td><?php echo "$JORDAN_USD USD" ?></td></tr>
                </table>
                <br />
                <form method = "POST" action = "process.php">
                    <input type = "hidden" name = "BTC_THIS_WEEK" value = "<?php echo $BTC_THIS_WEEK ?>" />
                    <input type = "hidden" name = "POWER_BTC" value = "<?php echo $POWER_BTC ?>" />
                    <input type = "hidden" name = "BTC_USD" value = "<?php echo $BTC_USD_LAST ?>" />
                    <input type = "hidden" name = "AM_BTC" value = "<?php echo $ADRIAN_BTC ?>" />
                    <input type = "hidden" name = "JS_BTC" value = "<?php echo $JARID_BTC ?>" />
                    <input type = "hidden" name = "DKW_BTC" value = "<?php echo $DUSHAN_BTC ?>" />
                    <input type = "hidden" name = "JK_BTC" value = "<?php echo $JORDAN_BTC ?>" />
                    <input type = "hidden" name = "STATS_INFO" value = "<?php echo $STATS_STRING ?>" />
                    <input type = "submit" value = "Save This Data" />
                </form>
            </div>
            
            <div id = "right">          
                <h1>Stats:</h1>
                <table class = "center" border = "1" cellpadding = "6">
                    <tr><td>Total BTC Mined:</td><td><?php echo $STATS_ARR['total_btc']; echo " BTC" ?></td></tr>
                    <tr><td>Total USD Mined:</td><td><?php echo $STATS_ARR['total_usd']; echo " USD" ?></td></tr>
                    <tr><td>Total Power Costs:</td><td> <?php echo $STATS_ARR['total_pwr']; echo " BTC" ?></td></tr>
                    <tr><td>Adrian's total BTC:</td><td> <?php echo $STATS_ARR['total_adrian_btc']; echo " BTC" ?></td></tr>
                    <tr><td>Adrian's total USD:</td><td> <?php echo $STATS_ARR['total_adrian_usd']; echo " USD" ?></td></tr>
                    <tr><td>Dushan's total BTC:</td><td> <?php echo $STATS_ARR['total_dushan_btc']; echo " BTC" ?></td></tr>
                    <tr><td>Dushan's total USD:</td><td> <?php echo $STATS_ARR['total_dushan_usd']; echo " USD" ?></td></tr>
                    <tr><td>Jarid's total BTC:</td><td> <?php echo $STATS_ARR['total_jarid_btc']; echo " BTC" ?></td></tr>
                    <tr><td>Jarid's total USD:</td><td> <?php echo $STATS_ARR['total_jarid_usd']; echo " USD" ?></td></tr>
                    <tr><td>Jordan's total BTC:</td><td> <?php echo $STATS_ARR['total_jordan_btc']; echo " BTC" ?></td></tr>
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
