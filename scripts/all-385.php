<?php
    date_default_timezone_set('America/Los_Angeles');
    set_time_limit(0);
    ob_start();
    /* 
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    $host = "sql-core-master.importgenius.com";
    $user = "core";
    $pass = "ajnin_edoc_12";
//    $host = "localhost";
//    $user = "core";
//    $pass = "ajnin_edoc_12";

    $database = "core";

//    $nbsp = PHP_EOL;
    $nbsp = "<br />";

    $konek = mysql_connect($host, $user, $pass) or die("Unable to connect to {$host}: ".mysql_error());
    $db = mysql_select_db($database);
    
    $data = $data_full = "  <table border = 1>
                                <thead>
                                    <th>Username</th>
                                    <th>Business</th>
                                    <th>Date Created</th>
                                    <th>Number of Logins</th>
                                    <th>Number of Searches</th>
                                </thead>
                                <tbody>";

    $sql = "SELECT
                `id`, `username`, `business`, LEFT(`created`, 10) AS created
            FROM
                users
            WHERE
                username IN (   '3pld',
                                'abet',
                                'adan',
                                'agot',
                                'airw',
                                'airs',
                                'alsc',
                                'alkg',
                                'alkc',
                                'alfr',
                                'amgs',
                                'angl',
                                'angb',
                                'atcc',
                                'bona',
                                'babi',
                                'buin',
                                'camo',
                                'carg',
                                'cntw',
                                'comb',
                                'djpa',
                                'djpi',
                                'doti',
                                'dyna',
                                'ecar',
                                'erha',
                                'egis',
                                'eims',
                                'eush',
                                'expo',
                                'expa',
                                'ford',
                                'fhas',
                                'gaca',
                                'getr',
                                'gltr',
                                'glfr',
                                'grcl',
                                'grco',
                                'grtl',
                                'homa',
                                'ialn',
                                'ifcg',
                                'insh',
                                'inte',
                                'ints',
                                'irbr',
                                'jscd',
                                'jcor',
                                'jade',
                                'kenk',
                                'ksqu',
                                'lafa',
                                'levi',
                                'masf',
                                'mycs',
                                'meiv',
                                'mcsw',
                                'msil',
                                'mtal',
                                'mtai',
                                'mira',
                                'ndcu',
                                'netr',
                                'newm',
                                'ntex1',
                                'palo',
                                'phoe',
                                'proc',
                                'relc',
                                'rljo',
                                'raso',
                                'roco',
                                'rglo',
                                'rglv',
                                'sain',
                                'sati',
                                'ssti',
                                'sara',
                                'sarn',
                                'sckc',
                                'scch',
                                'scst',
                                'scmi',
                                'sifr',
                                'solm',
                                'soco',
                                'spgl',
                                'taiu',
                                'ties',
                                'tran',
                                'trab',
                                'trac',
                                'trsh',
                                'tynr',
                                'untr',
                                'uasf',
                                'vabi',
                                'wfwc',
                                'wild',
                                'wglo',
                                'woex')";
    $que = mysql_query($sql, $konek) or die("SQL: ".mysql_error());
    $num = mysql_num_rows($que);
    
    if ($num > 0) {
        while ($row = mysql_fetch_assoc($que)) {
            $clientid = $row['id']; $username = $row['username']; $business = $row['business']; $created = date("M d, Y", strtotime(date($row['created'])));
            
            /********************** JAN 2014 - SEPT 2014 ********************/
            $from = "2014-01-01";
            $to = "2014-09-30";
            
            // Date Created
            $data .= "<tr>
                        <td>{$username}</td>
                        <td>{$business}</td>
                        <td>{$created}</td>";
            
            /** Number of Logins **/
            $loginsql = "SELECT COUNT(*) AS bilang FROM `session_log` WHERE user_id = '{$clientid}' AND `action` = 'Login' AND LEFT(created, 10) BETWEEN '{$from}' AND '{$to}';";
            $loginque = mysql_query($loginsql, $konek) or die("LOGINSQL: ".mysql_error());
            list($logins) = mysql_fetch_row($loginque);
            $data .= "<td>{$logins}</td>";
            
            $searchsql = "SELECT COUNT(*) FROM search_log WHERE userid = '{$clientid}' AND LEFT(qdate, 10) BETWEEN '{$from}' AND '{$to}';";
            $searchque = mysql_query($searchsql, $konek) or die("SEARCHSQL: ".mysql_error());
            list($searches) = mysql_fetch_row($searchque);
            $data .= "<td>{$searches}</td></tr>";
            
            /********************** NOV 2010 - SEPT 2014 ********************/
            $from = "2010-11-01";
            $to = "2014-09-30";
            
            // Date Created
            $data_full .= "<tr>
                            <td>{$username}</td>
                            <td>{$business}</td>
                            <td>{$created}</td>";
            
            /** Number of Logins **/
            $loginsql = "SELECT COUNT(*) AS bilang FROM `session_log` WHERE user_id = '{$clientid}' AND `action` = 'Login' AND LEFT(created, 10) BETWEEN '{$from}' AND '{$to}';";
            $loginque = mysql_query($loginsql, $konek) or die("LOGINSQL: ".mysql_error());
            list($logins) = mysql_fetch_row($loginque);
            $data_full .= "<td>{$logins}</td>";
            
            $searchsql = "SELECT COUNT(*) FROM search_log WHERE userid = '{$clientid}' AND LEFT(qdate, 10) BETWEEN '{$from}' AND '{$to}';";
            $searchque = mysql_query($searchsql, $konek) or die("SEARCHSQL: ".mysql_error());
            list($searches) = mysql_fetch_row($searchque);
            $data_full .= "<td>{$searches}</td></tr>";
            
        }
    }
    $data .= "</tbody></table>";
    $data_full .= "</tbody></table>";

//    echo $data;
//    echo "<hr />";
//    echo $data_full;
    $data_filename = "USI_Import_Genius_2014_Usage.xls";
    $data_fopen = fopen($data_filename, 'w') or die('Cannot open file:  '.$data_filename);
    fwrite($data_fopen, $data);
    
    $data_full_filename = "USI_Import_Genius_Total_Usage.xls";
    $data_full_fopen = fopen($data_full_filename, 'w') or die('Cannot open file:  '.$data_full_filename);
    fwrite($data_full_fopen, $data_full);
    
    ob_flush();