<?php
    $ip = gethostbyname('www.google .com');
    $out = "The following URLs are equivalent:<br />\n";
    $out .= 'http://www.example.com/, http://' . $ip . '/, and http://' . sprintf("%u", ip2long($ip)) . "/<br />\n";
    echo $out;
?>