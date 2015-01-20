<?php
/**
 * Configuration file to be used by run.php
 *
 * PHP Version 5.3
 *
 * @category Scripts
 * @package  Normal_Index_Visible
 * @author   Erickson Reyes <erickson@importgenius.com>
 * @license  http://www.importgenius.com ImportGenius
 * @link     http://www.importgenius.com ImportGenius
 */
$config['limit'] = 600000;
$config['chunkThrottle'] = 5;
$config['chunkAmount'] = 1000;

$indexer = '10.184.1.175';
//$indexer = 'localhost';
$site_db = 'localhost';

$db['us']['source'] = array(
    'server' => $indexer,
    'username' => 'site',
    'password' => 'ajnin_edoc_12',
    'database' => 'igdata'
);

$db['us']['destination'] = array(
    'server' => $site_db,
    'username' => 'site',
    'password' => 'ajnin_edoc_12',
    'database' => 'site'
);


$config['countries_latin'] = array(
    'ar' => 'Argentina',
    'ar_ex' => 'Argentina Export',
    'cl' => 'Chile',
    'cl_ex' => 'Chile Export',
    'co' => 'Colombia',
    'co_ex' => 'Colombia Export',
    'cr' => 'Costa Rica',
    'cr_ex' => 'Costa Rica Export',
    'ec' => 'Ecuador',
    'ec_ex' => 'Ecuador Export',
    'in' => 'India',
    'pa' => 'Panama',
    'pa_ex' => 'Panama Export',
    'pe' => 'Peru',
    'pe_ex' => 'Peru Export',
    'py' => 'Paraguay',
    'py_ex' => 'Paraguay Export',
    'uy' => 'Uruguay',
    'uy_ex' => 'Uruguay Export',
    've' => 'Venezuela',
    've_ex' => 'Venezuela Export'
);

$db['latin']['source'] = array(
    'server' => $indexer,
    'username' => 'site',
    'password' => 'ajnin_edoc_12',
    'database' => 'iglatindata'
);

$db['latin']['destination'] = array(
    'server' => $site_db,
    'username' => 'site',
    'password' => 'ajnin_edoc_12',
    'database' => 'site'
);
