<?php
    set_time_limit(0);
    ob_start();
    /* 
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

//    $host = "sql-core-master.importgenius.com";
//    $user = "core";
//    $pass = "ajnin_edoc_12";
//    $database = "core";
    $host = "localhost";
    $user = "core";
    $pass = "ajnin_edoc_12";
    $database = "daryle";

//    $nbsp = PHP_EOL;
    $nbsp = "<br />";

    $konek = mysql_connect($host, $user, $pass) or die("Unable to connect to {$host}: ".mysql_error());

    $db = mysql_select_db($database);

    $sql = "SELECT `lid`, `clientid`, `id` FROM `site_leads` WHERE `hide` = 0 AND `stage` = 10";
    $que = mysql_query($sql, $konek) or die("SQL: ".mysql_error());
    $num = mysql_num_rows($que);
    
    $updated_activities = 0;
    $deleted_leads = 0;
    $unmerged = 0;
    
    if ($num > 0) {
        while ($row = mysql_fetch_array($que)) {
            $lid = $row['lid']; $clientid = $row['clientid']; $id = $row['id'];
            
            if (!empty($clientid) || !empty($id)) {
                $etoid = !empty($clientid) ? $clientid : $id;
                // Transfer the activities to the Client record
                $updatesql = "UPDATE `users_activities` SET `clientid` = {$etoid} WHERE `leadid` = {$lid}";
//                $updatesql = "SELECT COUNT(*) FROM `users_activities` WHERE `leadid` = {$lid}";
                $updateque = mysql_query($updatesql, $konek) or die("Updatesql: ".mysql_error());
                $updated_activities += mysql_affected_rows();
//                $updated_activities += mysql_num_rows($updateque);
                
                // Mark it as deleted.
//                $delsql = "UPDATE `site_leads` SET `deleted` = 1 WHERE `lid` = {$lid} LIMIT 1";
//                $delque = mysql_query($delsql, $konek) or die("Delsql: ".mysql_error());
//                $deleted_leads += mysql_affected_rows();
            }
            else {
                $unmerged += 1;
            }
        }
    }
     
    echo $nbsp;
    echo "Total Closed-Won Leads: [{$num}]".$nbsp;

    echo "Updated Activities: [{$updated_activities}]".$nbsp;

//    echo "Leads marked as deleted: [{$deleted_leads}]".$nbsp;

    echo "Unmerged leads: [{$unmerged}]".$nbsp;
    echo $nbsp;
    
//    $filename = "core-404_clients.xls";
//    header("Content-type: application/vnd.ms-excel");
//    header("Content-Disposition: attachment; filename=$filename");
    
//    $handle = fopen($filename, 'w') or die('Cannot open file:  '.$filename);
//    fwrite($handle, $nolead);
    
    ob_flush();