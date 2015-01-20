<?php

set_time_limit(0);
ini_set('memory_limit', '-1');
ini_set('output_buffering', 'Off');
ini_set('output_buffering', FALSE);
ini_set('output_buffering', 0);

// Variables
$filename = "IpToCountry.csv.gz";
$filename_csv = 'IpToCountry.csv';
$delete = FALSE;
$server = 'localhost';
$username = 'script';
$password = 'ajnin_edoc_12';
$database = 'site';
$new_link = TRUE;

// Functions
/**
 * e
 * Echoes a string with a new line
 * @param string $string
 */
if (!function_exists('e')) {

    function e($string) {
        if (substr($string, -1, 1) !== '.') {
            echo "\n{$string}.";
        } else {
            echo "\n{$string}";
        }
    }

}

/**
 * IPV4 CSV (gz)
 * http://software77.net/geo-ip/?DL=1&x=Download
 *
 * IPV6 Range (gz)
 * http://software77.net/geo-ip/?DL=7&x=Download
 */
e('Start');
$delete = TRUE;

if ($delete) {
    if (file_exists($filename) === TRUE) {
        e('Deleting existing file...');
        unlink($filename);
        echo 'OK!';
    }
    if (file_exists($filename_csv) === TRUE) {
        e('Deleting existing file...');
        unlink($filename_csv);
        echo 'OK!';
    }

    e('Downloading...');
    exec("wget -O {$filename} http://software77.net/geo-ip/?DL=1&x=Download");
    echo 'OK!';
}

e('Reading ' . $filename);
if (file_exists($filename) === TRUE) {
    echo "OK!";

    exec("gzip -d {$filename_csv}");
    if (file_exists($filename_csv) === TRUE) {

//    e('Opening ' . $filename . '...');
//    $zd = gzopen($filename, "r") or die('Failed!');
//    echo 'OK!';
//    e('Extracting ' . $filename . '...');
//    $contents = gzread($zd, filesize($filename)) or die('Failed!');
//    echo 'OK!';
//    gzclose($zd);
//    e('Saving ' . $filename_csv . '...');
//    file_put_contents($filename_csv, $contents) or die('Failed!');
//    echo 'OK!';


        e('Opening ' . $filename_csv . '...');
        $handle = fopen($filename_csv, "r") or die('Failed!');
        echo 'OK!';

        if (file_exists($filename_csv) && filesize($filename_csv) > 0) {
            e('Connecting to ' . $server);
            $link_identifier = mysql_connect($server, $username, $password, $new_link) or die('Failed! ' . mysql_error());
            echo 'OK!';
            e('Selecting ' . $database);
            mysql_select_db($database) or die('Failed! ' . mysql_error()) or die('Failed! ' . mysql_error());
            echo 'OK!';

            e('Emptying tables...');
            $query = "TRUNCATE TABLE `ip_countries`;";
            mysql_query($query, $link_identifier) or die(mysql_error($link_identifier) . "\n\n" . $query . "\n\n");
            $query = "TRUNCATE TABLE `ip_countries_2`;";
            mysql_query($query, $link_identifier) or die(mysql_error($link_identifier) . "\n\n" . $query . "\n\n");
            $query = "TRUNCATE TABLE `ip_countries_3`;";
            mysql_query($query, $link_identifier) or die(mysql_error($link_identifier) . "\n\n" . $query . "\n\n");
            e('OK!');

            $ctr = 1;
            while ($data = fgetcsv($handle, NULL, ",", '"')) {
                if (count($data) > 0 && substr($data[0], 0, 1) !== '#') {
                    $row['ip_start'] = $data[0];
                    $row['ip_end'] = $data[1];
                    $row['misc'] = $data[2];
                    $row['ip6'] = $data[3];
                    $row['country1'] = $data[4];
                    $row['country2'] = $data[5];
                    $row['country3'] = substr($data[6], 0, 20);
                    $row['continent'] = '';

                    $query = "SELECT `continents_code` FROM `countries` WHERE `countries_iso_code_2` = '{$data[4]}' ";
                    $result = mysql_query($query, $link_identifier) or die(mysql_error($link_identifier) . "\n\n" . $query . "\n\n");

                    if ($result && mysql_num_rows($result) > 0) {
                        $result = mysql_fetch_assoc($result);
                        $row['continent'] = $result['continents_code'];
                    }

                    $values = array();
                    foreach ($row as $field => $value) {
                        $values[] = " '" . mysql_real_escape_string($value, $link_identifier) . "' ";
                    }
                    $values = implode(', ', $values);

                    // ip_countries_3
                    $query = "INSERT INTO `ip_countries_3` VALUES (NULL, {$values});";
                    $result = mysql_query($query, $link_identifier) or die(mysql_error($link_identifier) . "\n\n" . $query . "\n\n");
//                    echo "\n{$ctr} ip_countries3\t {$row['ip_start']} {$row['ip_end']} {$row['country1']}";

                    $values = array();
                    unset($row['continent']);
                    foreach ($row as $field => $value) {
                        $values[] = " '" . mysql_real_escape_string($value, $link_identifier) . "' ";
                    }
                    $values = implode(', ', $values);

                    // ip_countries_2
                    $query = "INSERT INTO `ip_countries_2` VALUES ({$values});";
                    $result = mysql_query($query, $link_identifier) or die(mysql_error($link_identifier) . "\n\n" . $query . "\n\n");
//                    echo "\n{$ctr} ip_countries2\t {$row['ip_start']} {$row['ip_end']} {$row['country1']}";

                    // ip_countries
                    $query = "INSERT INTO `ip_countries` VALUES ({$values});";
                    $result = mysql_query($query, $link_identifier) or die(mysql_error($link_identifier) . "\n\n" . $query . "\n\n");
//                    echo "\n{$ctr} ip_countries\t {$row['ip_start']} {$row['ip_end']} {$row['country1']}";

                    $ctr++;
                }
            }
        } else {
            die("Failed to extract " . $filename_csv);
        }
    } else {
        echo "Failed! File not found.";
    }
}
e('Done');
echo "\n";

