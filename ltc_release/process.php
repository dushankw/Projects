<?php
    // Data
    date_default_timezone_set('Australia/Melbourne');
    $date = date('Y/m/d H:i:s');
    $btc_week = $_POST['BTC_THIS_WEEK'];
    $power_btc = $_POST['POWER_BTC'];
    $btc_usd = $_POST['BTC_USD'];
    $adrian = $_POST['AM_BTC'];
    $jarid = $_POST['JS_BTC'];
    $dushan = $_POST['DKW_BTC'];
    $jordan = $_POST['JK_BTC'];
    $ip_addr = $_SERVER['REMOTE_ADDR'];
    $stats_string = $_POST['STATS_INFO'];
    $stats_arr = unserialize(base64_decode($stats_string));
    
    // Append new line to CSV
    $file = fopen('history.csv', 'a+');
    fwrite($file, "$date, $btc_week, $power_btc, $btc_usd, $adrian, $dushan, $jarid, $jordan, $ip_addr\n");
    fclose($file);
    
    // Append new line to JSON File
    $file = fopen('stats.json', 'w');
    fwrite($file, json_encode($stats_arr));
    fclose($file);
    
    // Redirect back home
    header('Location: index.php');
    exit;
?>
