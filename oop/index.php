<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
        <meta http-equiv="Content-Type" content="text/html;
        charset=iso-8859-1" />
        <title>OOP in PHP</title>
        <?php include("class_lib.php"); ?>
</head>
<body>
    <?php
        $daryle = new person("Daryle Dakis");
        $sarah = new employee("Sarah Cerin");

        echo "<pre>";
        echo "<p>Daryle's full name: {$daryle->getName()}<p>";
        echo "<p>Sarah's full name: {$sarah->getName()}<p>";
//        echo "<p>Show me the money: {$daryle->getPinnNumber()}</p>";
        echo "</pre>";
    ?>
</body>
</html>