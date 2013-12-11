<?php
    
    /*
     * Put me in the document root (same location as doku.php)
     * Edit the $wiki variable to match your environment
     * 
     * If I'm called directly the die(); clause will run
     * ANYONE can POST to me, but this is only dangerous if the Wiki
     * itself is not configured well, see README for more info 
     */

    // The site
    $wiki = "http://wiki.yoursite.com";
    
    // Get inputs
    $pagename = $_POST['page'];
    $pattern = "/^[a-zA-Z0-9:]+$/";

    // Check submmited value matches regex, if so it is safe and can procede
    if (preg_match($pattern, $pagename))
    {
        header("location: $wiki/doku.php?id=$pagename&do=edit");
    }
    else
    {
        die("<a href='$wiki'>Try Again</a>");
    }

?>
