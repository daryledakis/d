<?php

    /* 
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    $host = "sql-core-master.importgenius.com";
//    $host = "localhost";
    $user = "core";
    $pass = "ajnin_edoc_12";
    $database = "core";
    
    $konek = mysql_connect($host, $user, $pass) or die("Unable to connect to {$host}: ".mysql_error());

    $db = mysql_select_db($database);
    
    $unqualifiedsql = "SELECT `lid` FROM `site_leads` WHERE `stage` = 1 ORDER BY `lid`";
    $unqualifiedque = mysql_query($unqualifiedsql, $konek) or die("Unqualified SQL Error: ".mysql_error());
    $unqualifiednum = mysql_num_rows($unqualifiedque);
    $unqualified = "";
    $unqualified_array = "";
    if (!empty($unqualifiednum)) {
        while ($unqualifiedrow = mysql_fetch_array($unqualifiedque)) {
            $unqualified .= empty($unqualified) ? $unqualifiedrow['lid'] : ", {$unqualifiedrow['lid']}";
            $unqualified_array[] = $unqualifiedrow['lid'];
        }
    }
    
    echo "UNQUALIFIED LEADS COUNT: [{$unqualifiednum}]".PHP_EOL;
//    echo $unqualified.PHP_EOL;

    $duplicatessql = "SELECT lid FROM `site_leads` WHERE `duplicate_of` IS NOT NULL OR `duplicate_of` != '' GROUP BY lid ORDER BY lid";
    $duplicateque = mysql_query($duplicatessql, $konek) or die("SQL Error: ".mysql_error());
    $duplicatenum = mysql_num_rows($duplicateque);
    $duplicates = "";
    $duplicates_array = "";
    if (!empty($duplicatenum)) {
        while ($duplicatesrow = mysql_fetch_array($duplicateque)) {
            $duplicates .= empty($duplicates)? $duplicatesrow['lid'] : ",{$duplicatesrow['lid']}";
            $duplicates_array[] = $duplicatesrow['lid'];
        }
    }

    echo "DUPLICATE LEADS COUNT: [{$duplicatenum}]".PHP_EOL;
//     echo $duplicates.PHP_EOL;

    $actives = "";
    $actives_array = array();
    $sql = "SELECT `leadid` FROM `users_activities` WHERE `leadid` IN ({$duplicates}) GROUP BY `leadid`";
    $que = mysql_query($sql, $konek) or die("Error users_activities: ".mysql_error());
    $num = mysql_num_rows($que);
    if (!empty($num)) {
        while ($row = mysql_fetch_assoc($que)) {
            $actives .= empty($actives) ? $row['leadid'] : ",{$row['leadid']}";
            $actives_array[] = $row['leadid'];
        }

       echo "ACTIVE LEADS COUNT: [{$num}]".PHP_EOL;
//        echo $actives.PHP_EOL;

       $inactives_array = array_diff($duplicates_array, $actives_array);
       $inactives_num = count($inactives_array);
       $inactives = implode(",", $inactives_array);
       echo "INACTIVE LEADS COUNT: [{$inactives_num}]".PHP_EOL;
//        echo $inactives;

       if (!empty($inactives)) {
           // Remove inactive duplicates
           //$delsql = "SELECT COUNT(*) AS bilang FROM `site_leads` WHERE lid IN ({$inactives})";
//           $delsql = "DELETE FROM `site_leads` WHERE lid IN ({$inactives})";
//            $delque = mysql_query($delsql, $konek) or die("Delete error: ".mysql_error());
           //$delnum = mysql_num_rows($delque);
//            $delnum = mysql_affected_rows();
           //echo $delsql.PHP_EOL;
//            echo "DELETED COUNT: [{$delnum}]".PHP_EOL;
       }
    }

    $filename = "site_leads_2015_inactive.txt";
//    header("Content-type: application/vnd.ms-excel");
//    header("Content-Disposition: attachment; filename=$filename");
    $handle = fopen($filename, 'w') or die('Cannot open file:  '.$filename);
    fwrite($handle, $inactives);
    
    /**
    foreach ($duplicates as $d) {
        $sql = "SELECT COUNT(*) AS inactive FROM `users_activities` WHERE `leadid` = {$d}";
        //echo $sql."<br />";
        $que = mysql_query($sql, $konek) or die("Error users_activities: ".mysql_error());
        $row = mysql_fetch_assoc($que); echo "<br />";
        print_r($row);
        if (!empty($row['inactive'])) {
            $inactives[] = $d;
        }
    }
    **/
     