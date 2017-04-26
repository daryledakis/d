<?php
    date_default_timezone_set('America/Los_Angeles');
    set_time_limit(0);
    ob_start();
    /* 
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    $host = "sql-core-web-write.importgenius.private.com";
//    $host = "localhost";
    $user = "core";
    $pass = "ajnin_edoc_12";

    $database = "core";

    $nbsp = PHP_EOL;
//    $nbsp = "<br />";

    $konek = mysql_connect($host, $user, $pass) or die("Unable to connect to {$host}: ".mysql_error());
    $db = mysql_select_db($database);

    $sql = "SELECT lid, email, phone, phone_ext, alt_phone, alt_phone_ext, alt_phone_2, alt_phone_2_ext FROM site_leads
            WHERE 
            stage != 1 AND deleted = 0 AND email NOT LIKE '%echosigntest%' AND email NOT LIKE '%devtest%' AND email NOT LIKE '%importgenius%' AND email NOT LIKE '%codeninja%' AND email != 'ryang600@yahoo.com';
            ;";
    echo $sql.$nbsp.$nbsp;
    $que = mysql_query($sql, $konek) or die("SQL: ".mysql_error());
    $num = mysql_num_rows($que);

    $affected = 0;
    if ($num > 0) {
        while ($row = mysql_fetch_assoc($que)) {
            $lid = $row['lid'];
            $phones = [$row['phone'], $row['alt_phone'], $row['alt_phone_2']];
            list ($phone, $alt_phone, $alt_phone_2) = sanitizePhone($phones);

            $updatesql = "UPDATE site_leads SET phone = '{$phone}', alt_phone = '{$alt_phone}', alt_phone_2 = '{$alt_phone_2}' WHERE lid = {$lid}";
//            echo $updatesql.$nbsp.$nbsp;
            $updateque = mysql_query($updatesql, $konek) or die("Updatesql: ".mysql_error());
            $affected += mysql_affected_rows();
        }
    }

    function sanitizePhone($phone) {
        if (!is_array($phone)) {
            return preg_replace("/[^A-Za-z0-9,*\/]/", "", $phone);
        }

        $sanitizedPhone = [];
        foreach ($phone AS $val) {
            $sanitizedPhone[] = preg_replace("/[^A-Za-z0-9,*\/]/", "", $val);
        }

        return $sanitizedPhone;
    }

    echo "Affected: {$affected}".$nbsp.$nbsp;
    ob_flush();
