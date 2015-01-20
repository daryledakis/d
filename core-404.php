<?php
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
    $database = "core";
//    $host = "localhost";
//    $user = "core";
//    $pass = "ajnin_edoc_12";
//    $database = "daryle";

    $nbsp = PHP_EOL;
//    $nbsp = "<br />";

    $konek = mysql_connect($host, $user, $pass) or die("Unable to connect to {$host}: ".mysql_error());

    $db = mysql_select_db($database);

    $sql = "SELECT  `users`.`id` AS 'clientid',
                    CONCAT('http://core.importgenius.com/ccards#', users.username) AS 'core_link',
                    CONCAT(users.firstname, ' ', users.lastname) AS 'fullname',
                    `users`.`email` AS 'email',
                    `users`.`business` AS 'business',
                    `users`.`phone` AS 'phone',
                    CONCAT(core_users.firstname, ' ', core_users.lastname) AS 'salesperson',
                    `users`.`dateclosed` AS 'dateclosed'
            FROM    `users`
            LEFT JOIN `core_users` ON `users`.`salesperson` = `core_users`.`id`";
    $que = mysql_query($sql, $konek) or die("SQL: ".mysql_error());
    $num = mysql_num_rows($que);
    $nolead = " <table border = '1'>
                    <thead>
                        <tr>
                            <th align = 'center'>Core Link</td>
                            <th align = 'center'>Fullname</td>
                            <th align = 'center'>E-mail</td>
                            <th align = 'center'>Business</td>
                            <th align = 'center'>Phone Number</td>
                            <th align = 'center'>Salesperson</td>
                        </tr>
                    </thead>
                    <tbody>";
    $nolead_array = array();
    $merged_ctr = 0;
    $unmerged_ctr = 0;
    
    if ($num > 0) {
        while ($row = mysql_fetch_array($que)) {
            $clientid = $row['clientid']; $core_link = $row['core_link']; $fullname = $row['fullname']; $email = $row['email'];
            $business = $row['business']; $phone = $row['phone']; $salesperson = $row['salesperson']; $dateclosed = $row['dateclosed'];
            
            if ($dateclosed == "0000-00-00") {
                $nolead .= "<tr>
                                <td>{$core_link}</td>
                                <td>{$fullname}</td>
                                <td>{$email}</td>
                                <td>{$business}</td>
                                <td>{$phone}</td>
                                <td>{$salesperson}</td>
                            </tr>";
                $unmerged_ctr += 1;
            }
            else {
                $leadsql = "SELECT `lid` FROM `site_leads` WHERE `clientid` = {$clientid} OR `id` = {$clientid}";
                $leadque = mysql_query($leadsql, $konek) or die("Leadsql: ".mysql_error());
                $leadnum = mysql_num_rows($leadque);
                list($lid) = mysql_fetch_array($leadque);
//                echo $leadsql."|".$lid.$nbsp;
                
                if (is_null($lid) OR $lid === 0) {
                    $nolead .= "<tr>
                                    <td>{$core_link}</td>
                                    <td>{$fullname}</td>
                                    <td>{$email}</td>
                                    <td>{$business}</td>
                                    <td>{$phone}</td>
                                    <td>{$salesperson}</td>
                                </tr>";
                    $unmerged_ctr += 1;
                }
                else {
                    $merged_ctr += 1;
                }
            }
        }
        $nolead .= "</tbody></table>";
    }
     
    echo $nbsp;
    echo "Total Client records: [{$num}]".$nbsp;

    echo "Clients unmerged: [{$unmerged_ctr}]".$nbsp;

    echo "Clients merged to a lead: [{$merged_ctr}]".$nbsp;
    echo $nbsp;
    
    $filename = "core-404_clients.xls";
//    header("Content-type: application/vnd.ms-excel");
//    header("Content-Disposition: attachment; filename=$filename");
    
    $handle = fopen($filename, 'w') or die('Cannot open file:  '.$filename);
    fwrite($handle, $nolead);
    
    ob_flush();