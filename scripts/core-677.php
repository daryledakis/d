<?php
    date_default_timezone_set('America/Los_Angeles');
    set_time_limit(0);
    ob_start();
    /* 
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

//    $host = "sql-core-master.importgenius.com";
    $host = "localhost";
    $user = "core";
    $pass = "ajnin_edoc_12";

    $database = "core";

//    $nbsp = PHP_EOL;
    $nbsp = "<br />";

    $konek = mysql_connect($host, $user, $pass) or die("Unable to connect to {$host}: ".mysql_error());
    $db = mysql_select_db($database);
    
    $sql = "SELECT id#, firstname, lastname, username, email
            FROM users
            WHERE deleted = 1   AND email NOT LIKE '%devtest%' 
                                AND email NOT LIKE '%importgenius%'
                                AND email NOT LIKE '%codeninja%' 
                                AND email NOT LIKE '%echo@%' 
                                AND email NOT LIKE '%echos%' 
                                AND username NOT LIKE '%echo@%' 
                                AND username NOT LIKE '%echos%' 
                                AND username NOT LIKE '%test%' 
                                AND email NOT LIKE '%test%' 
                                AND firstname NOT LIKE '%test%' 
                                AND lastname NOT LIKE '%test%' 
                                AND username NOT LIKE '%wasauna%' 
                                AND email NOT LIKE '%wasauna%' 
                                AND username IS NOT NULL 
                                AND email IS NOT NULL 
                                AND username != '' 
                                AND email != ''
            ORDER BY email;";
    //echo $sql.$nbsp.$nbsp;
    $que = mysql_query($sql, $konek) or die("SQL: ".mysql_error());
    $num = mysql_num_rows($que);

    $todelete = array();
    if ($num > 0) {
        while ($row = mysql_fetch_assoc($que)) {
            $id = $row['id']; $username = $row['username']; $firstname = $row['firstname']; $lastname = $row['lastname']; $email = $row['email'];
            
            $transsql = "SELECT cid FROM cardtrans WHERE clientid = {$id} AND NOT (cardtrans.ctype = 'Visa' AND RIGHT(cardtrans.cardno, 4) = '1111')";
            $transque = mysql_query($transsql, $konek) or die("Transsql: ".mysql_error());
            $transnum = mysql_num_rows($transque);
            
            if ($transnum) {
                while ($transrow = mysql_fetch_assoc($transque)) {
                    $cid = $transrow['cid'];
                    
                    $histsql = "SELECT `amount`, `response`, `action` FROM `cardhistory` WHERE `cid` = {$cid} AND valid = 1";
                    $histque = mysql_query($histsql, $konek) or die("Histsql: ".mysql_error());
                    $histnum = mysql_num_rows($histque);
                    if ($histnum) {
                        $refund = $void = 0;

                        while ($histrow = mysql_fetch_assoc($histque)) {
                            $amount = $histrow['amount']; $response = $histrow['response']; $action = $histrow['action'];
                            
                            $refund += strtolower($histrow['action']) == "refund" ? 1 : 0;
                            $void += strtolower($histrow['action']) == "void" ? 1 : 0;
                        }

                        if ($refund == 1 || $void == 1) {
                            $todelete[$id]['cid'] = $cid;
                        }
                    }
                }
            }

            $checkssql = "SELECT checks.chkid, checks.chkamt, checks.chktype, checks.chkdate FROM checks WHERE checks.clientid = {$id} AND checks.chkamt IS NOT NULL AND checks.chkamt != ''";
            $checksque = mysql_query($checkssql, $konek) or die("Checkssql: ".mysql_error());
            $checksnum = mysql_num_rows($checksque);
            echo $checkssql."|".$checksnum."<br />";
            if ($checksnum) {
                $checksrow = mysql_fetch_row($checksque);

                $todelete[$id]['check_id'] = $checksrow['chkid'];
            }
        }
    }
    print_r($todelete);
    ob_flush();
