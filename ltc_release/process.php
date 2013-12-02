<?php
    // Data
    $date = date('Y/m/d H:i:s');
    $ltc_week = $_POST['LTC_THIS_WEEK'];
    $power_ltc = $_POST['POWER_LTC'];
    $ltc_usd = $_POST['LTC_USD'];
    $adrian = $_POST['AM_LTC'];
    $jarid = $_POST['JS_LTC'];
    $dushan = $_POST['DKW_LTC'];
    $jordan = $_POST['JK_LTC'];
    $ip_addr = $_SERVER['REMOTE_ADDR'];
    $stats_string = $_POST['STATS_INFO'];
    $stats_arr = unserialize(base64_decode($stats_string));
    
    // Append new line to CSV
    $file = fopen('history.csv', 'a+');
    fwrite($file, "$date, $ltc_week, $power_ltc, $ltc_usd, $adrian, $jarid, $dushan, $jordan, $ip_addr\n");
    fclose($file);
    
    // Append new line to JSON File
    $file = fopen('stats.json', 'w');
    fwrite($file, json_encode($stats_arr));
    fclose($file);
    
    // Redirect back home
    header('Location: index.php');
    exit;
?>