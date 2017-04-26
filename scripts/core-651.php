<?php

$modules = [1, 2, 3, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52];
$utype = 20;
$query = "";

foreach ($modules AS $module_id) {
    $enabled = $module_id == 9 ? 0 : 1;
    $query .= "INSERT INTO `core_modules_permission` SET `module_id` = {$module_id}, `utype` = {$utype}, `enabled` = {$enabled};<br />";
}

echo $query;