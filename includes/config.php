<?php

    //database configuration
    $host       = "localhost";
    $user       = "whatsgro_whatsgroupuser";
    $pass       = "jolk{o3}frIL";
    $database   = "whatsgro_whatsgroup";

    $connect = new mysqli($host, $user, $pass, $database);

    if (!$connect) {
        die ("connection failed: " . mysqli_connect_error());
    } else {
        $connect->set_charset('utf8');
    }
	
	$ENABLE_RTL_MODE = 'false';

?>