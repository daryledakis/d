<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$host = "sql-core-slave.importgenius.com";
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

$sql = "SELECT ( CASE WHEN users.business = '' THEN 'No Business Name' ELSE users.business END ) AS 'Business', CONCAT_WS( ' ', core_users.firstname, core_users.lastname ) AS 'Salesperson', ds1.data_display AS 'Account Type', ds2.data_display AS 'Pay Type', ( CASE WHEN users.terms = 0 THEN '12' ELSE users.terms END ) AS 'Terms', ( CASE WHEN users.amount = 0 THEN ds1.description ELSE users.amount END ) AS 'Amount', ( CASE WHEN users.payschedule = 1 THEN 'Upfront' ELSE 'Recurring' END ) AS 'Pay Schedule', ds3.data_display AS 'Lead Source', users.dateclosed AS 'Date Closed', users. STATUS AS 'Status', users.suspended AS 'Suspended', ( SELECT cancellation_date FROM cardcancel WHERE clientid = users.id AND ( cancellation_date BETWEEN '2015-08-01' AND '2015-08-31' ) AND result = 4 ORDER BY cancellation_date DESC LIMIT 1 ) AS 'Cancellation Request Date', ds4.data_display AS 'Cancellation Status', ( SELECT log_date FROM `logs` WHERE event_name IN ('Deactivate', 'Cancelled') AND module_name IN ('client', 'process', 'arb') AND tran_id = users.id ORDER BY log_date DESC LIMIT 1 ) AS Actual_Cancellation_Date, ( SELECT log_date FROM `logs` WHERE event_name IN ('Deactivate', 'Cancelled') AND module_name IN ('client', 'process', 'arb') AND tran_id = users.id AND log_date < Actual_Cancellation_Date ORDER BY log_date DESC LIMIT 1 ) AS Previous_Cancellation_Date, ( SELECT reasonlost FROM cardcancel WHERE `created` = Actual_Cancellation_Date AND clientid = users.id LIMIT 1 ) AS Reason_Lost, users.username, users.id AS clientid FROM cardcancel a LEFT JOIN users ON a.clientid = users.id LEFT JOIN core_users ON users.salesperson = core_users.id LEFT JOIN dataset ds1 ON users.utype = ds1.data_value AND ds1.data_code = 'U_TYPE' LEFT JOIN dataset ds2 ON users.paytype = ds2.data_value AND ds2.data_code = 'PAY_TYPE' LEFT JOIN dataset ds4 ON a.result = ds4.data_value AND ds4.data_code = 'CANCEL_RESULT' LEFT JOIN users_background ub ON users.id = ub.clientid LEFT JOIN dataset ds3 ON ub.lead_source = ds3.data_value AND ds3.data_code = 'LEAD_SOURCE' WHERE ( a.result IN (4) /** AND a.cancellation_date between '2015-08-01' and date(now()) */ AND a.username NOT LIKE '%echosigntest%' AND users.email NOT LIKE '%devtest_%' AND users.deleted = 0 AND users.paytype != '6' AND users.paytype != '8' AND (users.utype BETWEEN 6 AND 30) AND users.utype != 15 AND users.utype != 17 AND users.utype != 18 AND users. STATUS = 0 AND users.welcome != 0 ) AND ( users.id IN ( SELECT tran_id FROM `logs` WHERE event_name IN ('Deactivate', 'Cancelled') AND module_name IN ('client', 'process', 'arb') AND ( log_date BETWEEN '2015-08-01' AND '2015-08-31' ) ORDER BY log_date DESC )) GROUP BY users.id ORDER BY users.username";
$que = mysql_query($sql, $konek) or die("SQL: ".mysql_error());
$num = mysql_num_rows($que);
$noclient = "";
$noclient_array = array();

if ($num > 0) {
    while ($row = mysql_fetch_array($que)) {
        $id = $row['clientid']; $actual_cancellation_date = $row['Actual_Cancellation_Date'];
        $previous_cancellation_date = $row['Previous_Cancellation_Date'];
    }
}

echo $nbsp;
echo "Leads w/o a Client count: [{$num}]".$nbsp;
echo $nbsp;
//    die();
