<?php

    if(count($_POST) > 0)
    {
        function validate($val)
        {
            if($val != "")
            {
                if(is_numeric($val) == true && $val >= 0)
                {
                    
                    return floor($val);
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }

        $hd = trim(validate($_POST['hd']));
        $di = trim(validate($_POST['di']));
        $cr = trim(validate($_POST['cr']));
        $pa = trim(validate($_POST['pa']));
        $units = trim(validate($_POST['units']));

        if($hd != false && $di != false && $cr != false && $pa != false && $units != false)
        {
            if(($hd + $di + $cr + $pa) == $units)
            {
                $gpa = (($hd * 48) + ($di * 36) + ($cr * 24) + ($pa * 12)) / ($units * 12);
                $twodp = number_format($gpa, 2, '.', '');
                $out = "Your GPA is: $twodp";
            }
            else
            {
                $out = "Logic Error: Your number of credits does not equal your total number of units";
            }
        }
        else
        {
            $out = "Input Error: Either you left a field blank or entered an illegal value";
        }
    }

?>

<html>
    <head>
        <title>RMIT GPA Calculator</title>
        <style>
            body
            {
                font-family:arial;
            }
            .t1
            {
                margin-bottom:0;
            }
            .t2
            {
                margin-top:0;
            }
        </style>
    </head>
    <body>
        <h1 class = "t1">RMIT GPA Calculator</h1>
        <p class = "t2">Written by Dushan (<a href = "http://dushan.it">http://dushan.it</a>)</p>
        <!-- Source of GPA Data: http://www.rmit.edu.au/students/gradingbasis/gpa#n04 -->

        <?php echo "<h3>{$out}</h3>"; ?>

        <form method = "POST" action = "<?php echo $PHP_SELF; ?>">
            <table>
                <tr>
                    <td>Number of HD grades:</td>
                    <td><input type = "text" name = "hd" value = "<?php echo htmlentities($hd); ?>" /></td>
                </tr>
                <tr>
                    <td>Number of DI grades:</td>
                    <td><input type = "text" name = "di" value = "<?php echo htmlentities($di); ?>" /></td>
                </tr>
                <tr>
                    <td>Number of CR grades:</td>
                    <td><input type = "text" name = "cr" value = "<?php echo htmlentities($cr); ?>" /></td>
                </tr>
                <tr>
                    <td>Number of PA grades:</td>
                    <td><input type = "text" name = "pa" value = "<?php echo htmlentities($pa); ?>" /></td>
                </tr>
                <tr>
                    <td>Number of Units Taken:</td>
                    <td><input type = "text" name = "units" value = "<?php echo htmlentities($units); ?>" /></td>
                </tr>
            </table>
            <br />
            <input type = "submit" value = "Calculate" />
            <input type = "reset" value = "Reset" />
        </form>
    </body>
</html>