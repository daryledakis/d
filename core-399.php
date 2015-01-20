<?php

    /* 
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    $host = "sql-core-master.importgenius.com";
    $user = "core";
    $pass = "ajnin_edoc_12";
    $database = "core";
//    $host = "localhost";
//    $user = "core";
//    $pass = "ajnin_edoc_12";
//    $database = "daryle";

    $nbsp = PHP_EOL;
//    $nbsp = "<br />";

    $konek = mysql_connect($host, $user, $pass) or die("Unable to connect to {$host}: ".mysql_error());

    $db = mysql_select_db($database);

    $sql = "SELECT `lid`, `fullname`, `email`, `business` FROM `site_leads` WHERE `hide` = 0 AND `stage` = 10 AND (`clientid` IS NULL OR `clientid` = '' OR `clientid` = 0)";
    $que = mysql_query($sql, $konek) or die("SQL: ".mysql_error());
    $num = mysql_num_rows($que);
    $noclient = "";
    $noclient_array = array();
     
    if ($num > 0) {
        while ($row = mysql_fetch_array($que)) {
            $noclient .= empty($noclient) ? $row['lid'] : ",{$row['lid']}";
            $noclient_array[$row['lid']]['lid'] = $row['lid'];
            $noclient_array[$row['lid']]['fullname'] = $row['fullname'];
            $noclient_array[$row['lid']]['email'] = $row['email'];
            $noclient_array[$row['lid']]['business'] = $row['business'];
        }
    }
     
    echo $nbsp;
    echo "Leads w/o a Client count: [{$num}]".$nbsp;
    echo $nbsp;
//    die();

    if (count($noclient_array) > 0) {
        $affected = 0;
        $none = 0;
        $dups = 0;
        $cond = "";
        foreach ($noclient_array as $n) {
            $lid = $n['lid']; $fullname = addslashes($n['fullname']); $email = $n['email']; $business = addslashes($n['business']);
//            $checksql = "SELECT `id` FROM `users` WHERE CONCAT(firstname, ' ', lastname) = '{$fullname}' OR `email` = '{$email}' OR `business` = '{$business}'";
            
            $cond = !empty($business) ? "`business` = '{$business}'" : $cond;
            $cond = !empty($email) ? "`email` = '{$email}'" : $cond;
            
            if ($email == 'noemail@noemail.com') {
                $none += 1;
                continue;
            }
            
            $checksql = "SELECT `id`, `parentid`, `dateclosed` FROM `users` WHERE {$cond}";
//            echo $checksql.$nbsp;
            $checkque = mysql_query($checksql, $konek) or die("Checksql: ".mysql_error());
            $checknum = mysql_num_rows($checkque);

            // Only one matching Client
            if ($checknum == 1) {
                list($clientid, $parentid, $dateclosed) = mysql_fetch_array($checkque);

                $updatesql = "UPDATE `site_leads` SET `clientid` = {$clientid} WHERE `lid` = {$lid}";
                $updateque = mysql_query($updatesql, $konek) or die("Updatesql: ".mysql_error());
                $affected += mysql_affected_rows();
//                echo $updatesql."|".$affected.$nbsp.$nbsp;
            }
            // 2 or more matching Client
            else if ($checknum > 1) {
                //echo $n.$checknum.$nbsp;
                $dups += $checknum - 1;
                $temp = array();
                $parentid = 0;
                $dateclosed = 0;
                while ($temprow = mysql_fetch_array($checkque)) {
                    $temp[] = $temprow['id'];
                    $parentid = $temprow['parentid'] > 0 ? $temprow['id'] : $parentid;
                    $dateclosed = str_replace("-", "", $temprow['dateclosed']) > $dateclosed ? $temprow['id'] : $dateclosed;
                }

                if ($parentid > 0) {
                    $updatesql = "UPDATE `site_leads` SET `clientid` = {$parentid} WHERE `lid` = {$lid}";
                }
                else {
                    $updatesql = "UPDATE `site_leads` SET `clientid` = {$dateclosed} WHERE `lid` = {$lid}";
                }
                $updateque = mysql_query($updatesql, $konek) or die("Updatesql: ".mysql_error());
                $affected += mysql_affected_rows();
//                echo $updatesql."|".$affected.$nbsp.$nbsp;
            }
            // No matched Client (using the email)
            else {
                if (!empty($business)) {
                    $innersql = "SELECT `id`, `parentid`, `dateclosed` FROM `users` WHERE `business` = '{$business}'";
//                    echo $innersql.$nbsp;
                    $innerque = mysql_query($innersql, $konek) or die("Innersql: ".mysql_error());
                    $innernum = mysql_num_rows($innerque);

                    // Only one matching Client
                    if ($innernum == 1) {
                        list($clientid, $parentid, $dateclosed) = mysql_fetch_array($innerque);

                        $updatesql = "UPDATE `site_leads` SET `clientid` = {$clientid} WHERE `lid` = {$lid}";
                        $updateque = mysql_query($updatesql, $konek) or die("Updatesql: ".mysql_error());
                        $affected += mysql_affected_rows();
//                        echo $updatesql."|".$affected.$nbsp.$nbsp;
                    }
                    // 2 or more matching Client
                    else if ($innernum > 1) {
                        //echo $n.$checknum.$nbsp;
                        $dups += $innernum - 1;
                        $temp = array();
                        $parentid = 0;
                        $dateclosed = 0;
                        while ($temprow = mysql_fetch_array($innerque)) {
                            $temp[] = $temprow['id'];
                            $parentid = $temprow['parentid'] > 0 ? $temprow['id'] : $parentid;
                            $dateclosed = str_replace("-", "", $temprow['dateclosed']) > $dateclosed ? $temprow['id'] : $dateclosed;
                        }

                        if ($parentid > 0) {
                            $updatesql = "UPDATE `site_leads` SET `clientid` = {$parentid} WHERE `lid` = {$lid}";
                        }
                        else {
                            $updatesql = "UPDATE `site_leads` SET `clientid` = {$dateclosed} WHERE `lid` = {$lid}";
                        }
                        $updateque = mysql_query($updatesql, $konek) or die("Updatesql: ".mysql_error());
                        $affected += mysql_affected_rows();
//                        echo $updatesql."|".$affected.$nbsp.$nbsp;
                    }
                    else {
                        $none += 1;
                    }
                }
                else {
                    $none += 1;
                }
            }
        }

        echo $nbsp;
        echo "Leads merged with a Client: [{$affected}]".$nbsp;
        echo $nbsp;

        echo $nbsp;
        echo "Leads unmerged: [{$none}]".$nbsp;
        echo $nbsp;

        echo $nbsp;
        echo "Clients unmerged: [{$dups}]".$nbsp;
        echo $nbsp;
    }
     