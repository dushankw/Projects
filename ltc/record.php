<?php

    // To Do: 
    // Lock file to properly solve concurrency issues
    // Create code to check CSV exists and is writable
    // Possibly add referer check to prevent spam?
    // Possibly improve CSV writing?
    // Clean up CSS on this page
    
    // Get POSTed values
    $date = date('Y/m/d H:i:s');
    $ltc_week = $_POST['LTC_THIS_WEEK'];
    $power_ltc = $_POST['POWER_LTC'];
    $ltc_usd = $_POST['LTC_USD'];
    $adrian = $_POST['AM_LTC'];
    $jarid = $_POST['JS_LTC'];
    $dushan = $_POST['DKW_LTC'];
    $jordan = $_POST['JK_LTC'];
    
    // Append new line to file
    $file = fopen('history.csv', 'a+');
    fwrite($file, "$date, $ltc_week, $power_ltc, $ltc_usd, $adrian, $jarid, $dushan, $jordan\n");
    fclose($file);
    
    // Provide link to file
    echo "<h1><a href = 'history.csv'>Click Here for CSV</a></h1>";
    
?>
