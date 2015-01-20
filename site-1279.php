<?php
    date_default_timezone_set('America/Los_Angeles');
    set_time_limit(0);
//    ob_start();
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

    $nbsp = PHP_EOL;
//    $nbsp = "<br />";

    $konek = mysql_connect($host, $user, $pass) or die("Unable to connect to {$host}: ".mysql_error());
    $db = mysql_select_db($database);
    
    $today = date("Y-m-d");
    
    $sql = "SELECT
                `logs`.`tran_id` AS 'clientid',
                DATE_FORMAT(
                        LEFT(`logs`.log_date, 10),
                        '%m-%d-%Y'
                ) AS 'date_format',
                LEFT(`logs`.log_date, 10) AS 'date',
                `logs`.`event_name` AS 'event_name',
                `core_users`.`username` AS 'core_user',
                `logs`.`payment_method`,
                `pay_type_table`.`data_display` AS 'payment_method_display',
                `utype_table`.`data_display` AS 'account_type',
                `users`.`username`
            FROM
                (`logs`)
            LEFT JOIN `core_users` ON `user_id` = `id`
            LEFT JOIN `users` ON `logs`.`tran_id` = `users`.`id`
            LEFT JOIN `dataset` AS `pay_type_table` ON (
                `logs`.`payment_method` = `pay_type_table`.data_value
                AND `pay_type_table`.`data_code` = 'PAY_TYPE'
            )
            LEFT JOIN `dataset` AS `utype_table` ON (
                `users`.`utype` = `utype_table`.`data_value`
                AND `utype_table`.`data_code` = 'U_TYPE'
            )
            WHERE
                event_name LIKE '%export%'
            AND `module_name` IN (
                'client',
                'arb',
                'process',
                'reactivation'
            )
            ORDER BY
                `log_date` DESC";
        //echo $sql.$nbsp.$nbsp;
        $que = mysql_query($sql, $konek) or die("SQL: ".mysql_error());
        $num = mysql_num_rows($que);
        
        if ($num > 0) {
            $output = " <table>
                            <thead>
                                <th>Date</th>
                                <th>X export credit</th>
                                <th>Core User</th>
                                <th>Pay Type</th>
                                <th>Payment</th>
                                <th>Charge User</th>
                                <th>Account Type</th>
                                <th>Client Username</th>
                            </thead>
                            <tbody>";
            while ($row = mysql_fetch_assoc($que)) {
                $clientid = $row['clientid']; $date_format = $row['date_format']; $date = $row['date']; $event_name = $row['event_name'];
                $core_user = $row['core_user']; $payment_method = $row['payment_method']; $payment_method_display = $row['payment_method_display'];
                $account_type = $row['account_type']; $username = $row['username']; $payment = ""; $charge_user = "";
                
                # Payment Method 1 = CHECK, 2 = CREDIT CARD
                if ($payment_method == 1) {
                    $paysql = " SELECT
                                    `checks`.`chkamt`,
                                    CONCAT(core_users.firstname, ' ', core_users.lastname) AS 'charge_user'
                                FROM `checks` 
                                LEFT JOIN `core_users` ON `checks`.`author` = `core_users`.`id`
                                WHERE LEFT(chkdate, 10) = '{$date}' AND `clientid` = {$clientid}";
//                    echo $paysql.$nbsp;
                    $payque = mysql_query($paysql, $konek) or die("PAYSQL 1: ".mysql_error());
                    $paynum = mysql_num_rows($payque);
                    if ($paynum) {
                        while ($payrow = mysql_fetch_assoc($payque)) {
                            $payment = $payrow['chkamt']; $charge_user = $payrow['charge_user'];
                            $output .= "<tr>
                                            <td>{$date_format}</td>
                                            <td>{$event_name}</td>
                                            <td>{$core_user}</td>
                                            <td>{$payment_method_display}</td>
                                            <td>{$payment}</td>
                                            <td>{$charge_user}</td>
                                            <td>{$account_type}</td>
                                            <td>{$username}</td>
                                        </tr>";
                        }
                    }
                    else {
                        $output .= "<tr>
                                        <td>{$date_format}</td>
                                        <td>{$event_name}</td>
                                        <td>{$core_user}</td>
                                        <td>{$payment_method_display}</td>
                                        <td>{$payment}</td>
                                        <td>{$charge_user}</td>
                                        <td>{$account_type}</td>
                                        <td>{$username}</td>
                                    </tr>";
                    }
                }
                else {
                    // @TODO
                    $cidsql = "SELECT `cid` FROM `cardtrans` WHERE `clientid` = {$clientid}";
//                    echo $cidsql.$nbsp;
                    $cidque = mysql_query($cidsql, $konek) or die("CIDSQL: ".mysql_error());
                    $cidnum = mysql_num_rows($cidque);
                    if ($cidnum) {
                        list($cid) = mysql_fetch_row($cidque);
                        
                        $paysql = " SELECT 
                                        `cardhistory`.`amount`,
                                        CONCAT(core_users.firstname, ' ', core_users.lastname) AS 'charge_user'
                                    FROM `cardhistory`
                                    LEFT JOIN `core_users` ON `cardhistory`.`userid` = `core_users`.`id`
                                    WHERE `cid` = {$cid} AND `response` = 'Approved' AND `action` != 'void' AND LEFT(datecreated, 10) = '{$date}'";
                        $payque = mysql_query($paysql, $konek) or die("PAYSQL 2: ".mysql_error());
                        $paynum = mysql_num_rows($payque);
                        if ($paynum) {
                            list($payment, $charge_user) = mysql_fetch_row($payque);
                        }
                    }
                    $output .= "<tr>
                                    <td>{$date_format}</td>
                                    <td>{$event_name}</td>
                                    <td>{$core_user}</td>
                                    <td>{$payment_method_display}</td>
                                    <td>{$payment}</td>
                                    <td>{$charge_user}</td>
                                    <td>{$account_type}</td>
                                    <td>{$username}</td>
                                </tr>";
                }
            }
            $output .= "</tbody></table>";
//            echo $output;
        }

        $filename = "site-1279.xls";
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$filename");

        $handle = fopen($filename, 'w') or die('Cannot open file:  '.$filename);
        $fwrite = fwrite($handle, $output);
        echo $nbsp.$fwrite.$nbsp;
//    ob_flush();
