<?php 
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
    
    // Deal with the stats section
    $STATS = get('stats.json');
    $STATS_ARR = array (
        'total_btc' => $STATS["total_btc"] + $BTC_THIS_WEEK,
        'total_usd' => $STATS["total_usd"] + ($BTC_THIS_WEEK * $BTC_USD_LAST),
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
?>

<html>
    <head>
        <title>Money Calculator</title>
        <link rel = "stylesheet" type = "text/css" href = "styles.css" />
    </head>
    <body>
        <div id = "content">
            <h1>Money Calculator</h1>
            <div id = "left">         
                <br /><br /><br /><br />
                <h3>Enter BTC for this Week:</h3>
                <form method = "POST" action = "calc.php">
                    <input type = "text" name = "btc_this_week" />
                    <input type = "submit" value = "Submit" />
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
