<?php
    $count = 10;
    $prev = 1;
    $ctr = 0;
    for ($min = 0; $ctr < $count; $min += $prev) {
        echo "{$min}\n";
        $ctr += 1;
        $prev = $min + $prev;
        echo "{$prev}\n";
        $ctr += 1;
    }
?>