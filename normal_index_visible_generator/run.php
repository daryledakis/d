<?php
/**
 * This is to update the normal_index_visible tables
 *
 * PHP Version 5.3
 *
 * @category Scripts
 * @package  Normal_Index_Visible
 * @author   Erickson Reyes <erickson@importgenius.com>
 * @license  http://www.importgenius.com ImportGenius
 * @link     http://www.importgenius.com ImportGenius
 */
error_reporting(E_ALL);
set_time_limit(0);
ini_set('memory_limit', '-1');
ini_set('output_buffering', 'Off');
ini_set('output_buffering', true);
ini_set('output_buffering', 0);
date_default_timezone_set("America/New_York");

if (file_exists('config.php') === false) {
    throw new Exception('Missing config.php file');
}
if (file_exists('vendor/autoload.php') === false) {
    throw new Exception('Missing vendor/autoload.php file');
}

echo "\n\nNormal Index Visible Generator";
echo "\n\nLoading required files... ";
require 'vendor/autoload.php';
require 'config.php';


/**
 * Process data
 *
 * @param string $normal_index_table_name         Normal Index Table Name
 * @param string $normal_index_visible_table_name Normal Index Visible Table Name
 * @param string $country_name                    Country Name
 * @param array  $db                              Database Config
 *
 * @global array $config Configuration
 *
 * @return null
 */
function process(
    $normal_index_table_name,
    $normal_index_visible_table_name,
    $country_name,
    $db
) {
    global $config;

    /**
     * Normal Index
     */
    echo "OK!\nCreating MySQL Factory Driver for Normal Index... ";
    $NormalIndexMySQLDriver = new NormalIndexFactoryMySQLDriver($db['source']);

    echo "OK!\nCreating Normal Index Factory... ";
    $NormalIndex = new NormalIndexFactory($NormalIndexMySQLDriver);


    if (isset($config['chunkAmount'])) {
        echo "OK!\nSetting chunk amount to {$config['chunkAmount']}... ";
        $NormalIndexMySQLDriver->setChunkAmount($config['chunkAmount']);
    }

    if (isset($config['chunkThrottle'])) {
        echo "OK!\nSetting chunk throttle to {$config['chunkThrottle']} seconds... ";
        $NormalIndexMySQLDriver->setQueryThrottle($config['chunkThrottle']);
    }

    echo "OK!\nConnecting to Normal Index source... ";
    $NormalIndex->connect();

    if (isset($config['limit'])) {
        echo "OK!\nSetting the limit to {$config['limit']}... ";
        $NormalIndex->setLimit($config['limit']);
    }

    echo "OK!\nSetting table name to {$normal_index_table_name}... ";
    $NormalIndex->setTableName($normal_index_table_name);

    echo "OK!\nFetching {$config['limit']} records... ";
    $NormalIndexRecords = $NormalIndex->getRecords();
    $NormalIndexRecordsCount = count($NormalIndexRecords);
    echo "OK! {$NormalIndexRecordsCount} records found.\n\n";


    /**
     * Normal Index Visible
     */
    echo "Creating MySQL Factory Driver for Normal Index Visible... ";
    $NormalIndexVisibleMySQLDriver = new NormalIndexVisibleFactoryMySQLDriver(
        $db['destination']
    );

    echo "OK!\nCreating Normal Index Visible Factory...";
    $NormalIndexVisible = new NormalIndexVisibleFactory(
        $NormalIndexVisibleMySQLDriver
    );

    echo "OK!\nConnecting to Normal Index Visible destination... ";
    $NormalIndexVisible->connect();

    echo "OK!\nSetting table name to {$normal_index_visible_table_name}"
    . " [{$country_name}]... ";
    $NormalIndexVisible->setTableName($normal_index_visible_table_name);

    echo "OK!\nTruncating {$normal_index_visible_table_name} table... ";
    $NormalIndexVisible->truncate();

    echo "OK!\nSaving Normal Index records to Normal Index Visible... "
    . "[{$country_name}]...";
    foreach ($NormalIndexRecords as $NormalIndexRecord) {
        $NormalIndexVisible->insert($NormalIndexRecord);
    }

    echo "OK!\nUpdating table record count... ";
    /**
     * Table record count
     */
    $NormalIndexVisible->setTableName('table_record_count');
    $NormalIndexVisible->update(
        array('count' => $NormalIndexRecordsCount),
        array('name' => $normal_index_visible_table_name)
    );
}


echo "\n\nStart... ";
/**
 * US
 */
process('normal_index', 'normal_index_visible', 'US', $db['us']);

/**
 * Latin
 */
foreach ($config['countries_latin'] as $abbr => $country_name) {
    process(
        'normal_index_' . $abbr, 'normal_index_' . $abbr . '_visible',
        $country_name,
        $db['latin']
    );
}

echo "\nDone!\n\n";