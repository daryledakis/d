<?php
    date_default_timezone_set('America/Los_Angeles');
    set_time_limit(0);
    ob_start();
    /* 
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    $host = "sql-core-slave.importgenius.com";
//    $host = "localhost";
    $user = "core";
    $pass = "ajnin_edoc_12";

    $database = "core";

//    $nbsp = PHP_EOL;
    $nbsp = "<br />";

    $konek = mysql_connect($host, $user, $pass) or die("Unable to connect to {$host}: ".mysql_error());
    $db = mysql_select_db($database);
    
    $sql = "SELECT users.id, users.username, users.amount, users.utype, (CASE WHEN (users.utype < 100 AND users.utype != 15) AND users.cancelled = 1 AND users.datecancelled > DATE(NOW()) THEN 'Pending Cancellation' WHEN (users.utype < 100 AND users.utype != 15) AND users.status = 0 AND users.datecancelled != '0000-00-00' THEN 'Canceled' WHEN (users.utype < 100 AND users.utype != 15) AND users.status = 1 AND users.payschedule = 1 AND (users.suspendon <= CURDATE() AND users.suspendon != '0000-00-00') THEN 'Canceled' WHEN (users.utype < 100 AND users.utype != 15) AND users.status = 0 AND users.contract = 0 THEN 'Awaiting TOS' WHEN (users.utype < 100 AND users.utype != 15) AND users.status = 1 AND users.suspended = 1 AND users.welcome = 0 THEN 'Initial Decline' WHEN (users.utype < 100 and users.utype != 15) and users.status = 1 and users.suspended = 1 THEN 'Suspended' WHEN (users.utype < 100 AND users.utype != 15) AND users.status = 0 AND users.suspended = 0 AND users.contract = 1 THEN 'TOS Uploaded' WHEN (users.utype=15) THEN 'One Time Report' WHEN (users.utype < 100 AND users.utype != 15) AND users.status = 1 AND users.suspended = 0 AND users.cancelled = 0 THEN 'Active' ELSE '' END) AS `status`, utype.data_display AS plan_type, users.dateclosed, (SELECT COUNT(*) FROM available_countries WHERE available_countries.userid = users.id AND available_countries.astatus = 1) AS countries
            FROM users
            LEFT JOIN dataset AS utype ON users.utype = utype.data_value AND utype.data_code = 'U_TYPE'
            WHERE users.utype IN (12, 14, 16, 20, 24, 21, 22, 23, 26, 27, 28, 29, 30) AND (users.payschedule = 0 OR users.payschedule = '' OR users.payschedule IS NULL) AND users.paytype NOT IN (6, 8) AND users.deleted = 0 #AND users.username = 'josephfattal'
            ORDER BY users.dateclosed ASC;";
    //echo $sql.$nbsp.$nbsp;
    $que = mysql_query($sql, $konek) or die("SQL: ".mysql_error());
    $num = mysql_num_rows($que);

    $todelete = array();
    if ($num > 0) {
        
        $heavily_discounted_amount = [
            "12" => 25,
            "14" => 75,
            "16" => 175,
            "20" => 0,
            "24" => 25,
            "21" => 75,
            "22" => 175,
            "23" => 175,
            "26" => 25,
            "27" => 75,
            "28" => 175,
            "29" => 0,
            "30" => 25
        ];
        
        $countries_amount = [0 => 0];
        $y = 25;
        for ($x = 1; $x <= 15; $y+=25) {
            $countries_amount[$x] = $y;
            $x++;
        }
        
        $output = " <table border = 1>
                        <thead>
                            <th>Username</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Account Type</th>
                            <th>Closed Date</th>
                            <th>Number of Additional Countries</th>
                        </thead>
                        <tbody>";
        while ($row = mysql_fetch_assoc($que)) {
            $id = $row['id']; $username = $row['username']; $amount = $row['amount']; $status = $row['status'];
            $utype = $row['utype']; $plan_type = $row['plan_type']; $dateclosed = $row['dateclosed']; $countries = $row['countries'];

            if ($amount <= ($heavily_discounted_amount[$utype] + $countries_amount[$countries])) {
                $output .= "<tr>
                                <td>{$username}</td>
                                <td>{$amount}</td>
                                <td>{$status}</td>
                                <td>{$plan_type}</td>
                                <td>{$dateclosed}</td>
                                <td>{$countries}</td>
                            </tr>";
            }
        }

        $output .= "</tbody></table>";
    }
    #echo $output;

    $data_filename = "Heavily Discounted Users Report.xls";
    $data_fopen = fopen($data_filename, 'w') or die('Cannot open file:  '.$data_filename);
    fwrite($data_fopen, $output);
    ob_flush();
