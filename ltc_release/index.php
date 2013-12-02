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
        return json_decode(file_get_contents($url), true);
    }
    
    // Deal with the stats section
    $STATS = get('stats.json');
    $STATS_ARR = array(
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
                <h3>Enter LTC for this Week:</h3>
                <form method = "POST" action = "calc.php">
                    <input type = "text" name = "ltc_this_week" />
                    <input type = "submit" value = "Submit" />
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