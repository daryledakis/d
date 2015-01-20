use DBI;
use Time::Local;
use POSIX;

$dbh = DBI->connect('dbi:mysql:core:localhost:3306','site','ajnin_edoc_12');
print "\n";
    # Payment Type
    my $payment_type = 4;
    my $payment_type_desc = "";
    my $paysql = "SELECT `data_display` FROM `dataset` WHERE `data_code` = 'PAY_TYPE' AND `data_value` = '$payment_type'";
    my $payque = $dbh->prepare($paysql);
    $payque->execute();
    $payque_num = $payque->rows;
    $payment_type_desc = $payque->fetchrow();
    print $row;
print "\n";