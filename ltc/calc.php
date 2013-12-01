<?php

    // Get JSON Data from BTC-E
    function get($url)
    {
        $feed = file_get_contents($url);
        return json_decode($feed, true);
    }

    // Check for valid inputs and sanitise
    function validate($val)
    {
        if($val != "")
        {
            if(is_numeric($val) == true && $val >= 0)
            {
                return $val;
            }
            else
            {
                die("Input Error");
            }
        }
        else
        {
            die("Input Error");
        }
    }
    
    // Truncate BTC/LTC Value to 8 places without rounding
    // truncate($value, places)
    function truncate($val, $f="0")
    {
        if(($p = strpos($val, '.')) !== false)
        {
            $val = floatval(substr($val, 0, $p + 1 + $f));
        }
        return $val;
    }

    // LTC Stats
    $LTC_USD = get("https://btc-e.com/api/2/ltc_usd/ticker");
    $LTC_USD_LAST = round(validate($LTC_USD["ticker"]["last"]),2);

    // BTC Stats
    $BTC_USD = get("https://btc-e.com/api/2/btc_usd/ticker");
    $BTC_USD_LAST = round(validate($BTC_USD["ticker"]["last"]),2);

    // Logic
    $POWER_COST = 50; // Change me if needed
    $LTC_THIS_WEEK = validate($_POST['ltc_this_week']);
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

?>

<html>
    <head>
        <title>Money Calculator</title>
        <style>
            body
            {
                font-family:arial;
                text-align:center;
            }
            table.center
            {
                margin-left:auto;
                margin-right:auto;
            }
        </style>
    </head>
    <body>
        <h1>Money Calculator</h1>
        <?php
            // Done in a single PHP Block for performance
            echo "<p>Bitcoin Price (USD): $BTC_USD_LAST<br />Litecoin Price (USD): $LTC_USD_LAST</p>";
            echo "<p>Power in LTC for this week: $POWER_LTC<br />Total LTC Mined this week: $LTC_THIS_WEEK</p>";
        ?>
        <h1>Total Received This Week:</h1>
        <table class = "center" border = "1">
            <tr><td>Adrian</td><td><?php echo "$ADRIAN_LTC LTC" ?></td><td><?php echo "$ADRIAN_USD USD" ?></td></tr>
            <tr><td>Jarid</td><td> <?php echo "$JARID_LTC LTC" ?></td><td><?php echo "$JARID_USD USD" ?></td></tr>
            <tr><td>Dushan</td><td><?php echo "$DUSHAN_LTC LTC" ?></td><td><?php echo "$JARID_USD USD" ?></td></tr>
            <tr><td>Jordan</td><td><?php echo "$JORDAN_LTC LTC" ?></td><td><?php echo "$JORDAN_USD USD" ?></td></tr>
        </table>
        <br />        
        <form method = "POST" action = "record.php">
            <input type = "hidden" name = "LTC_THIS_WEEK" value = "<?php echo $LTC_THIS_WEEK ?>" />
            <input type = "hidden" name = "POWER_LTC" value = "<?php echo $POWER_LTC ?>" />
            <input type = "hidden" name = "LTC_USD" value = "<?php echo $LTC_USD_LAST ?>" />
            <input type = "hidden" name = "AM_LTC" value = "<?php echo $ADRIAN_LTC ?>" />
            <input type = "hidden" name = "JS_LTC" value = "<?php echo $JARID_LTC ?>" />
            <input type = "hidden" name = "DKW_LTC" value = "<?php echo $DUSHAN_LTC ?>" />
            <input type = "hidden" name = "JK_LTC" value = "<?php echo $JORDAN_LTC ?>" />
            <input type = "submit" value = "Save" />
        </form>
        <h1>Records:</h1>
        <!-- To Do: PHP Code to read and print contents of CSV -->
    </body>
</html>
