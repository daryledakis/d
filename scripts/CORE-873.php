<?php
    date_default_timezone_set('America/New_York');
    set_time_limit(0);
    ob_start();

    $host = "sql-core-web-write.importgenius.private.com";
//    $host = "localhost";
    $user = "core";
    $pass = "ajnin_edoc_12";

    $database = "core";

    $nbsp = PHP_EOL;
//    $nbsp = "<br />";

    $konek = mysql_connect($host, $user, $pass) or die("Unable to connect to {$host}: ".mysql_error());
    $db = mysql_select_db($database);
    
    $dateLogged = date("Y-m-d", strtotime("-2 day"));
    $dateNow = date("Y-m-d H:i:s");

    $sql = "UPDATE `iscan_blocked_usernames` SET `deleted` = 1, `date_deleted` = '{$dateNow}' WHERE LEFT(date_logged, 10) = '{$dateLogged}';";
    $que = mysql_query($sql, $konek) or die("SQL: ".mysql_error());
    $affected = mysql_affected_rows();
    ob_flush();
