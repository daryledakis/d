<?php
    date_default_timezone_set('America/Los_Angeles');
    set_time_limit(0);
    ob_start();
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

//    $nbsp = PHP_EOL;
    $nbsp = "<br />";

    $konek = mysql_connect($host, $user, $pass) or die("Unable to connect to {$host}: ".mysql_error());
    $db = mysql_select_db($database);
    
    $today = date("Y-m-d");
    
    for ($week = 2; $week <= 8; $week += 1) {
        $week_passed = date("Y-m-d", strtotime("{$today} -{$week} weeks"));
        
        $sql = "SELECT
                    `users`.`id`,
                    `users`.`username`,
                    `users`.`firstname`,
                    `users`.`email`,
                    `users`.`suspendon`,
                    `users`.`rspecialist`,
                    `core_users`.`email` AS `rspecialist_email`,
                    CONCAT(core_users.firstname, ' ', core_users.lastname) AS rspecialist_fullname,
                    `core_users`.`email_signature` AS `rspecialist_email_signature`
                FROM
                    `users`
                LEFT JOIN
                    `core_users` ON `users`.`rspecialist` = `core_users`.`id`
                WHERE
                    `suspended` = 1 AND
                    `suspendon` = '{$week_passed}'";
        //echo $sql.$nbsp.$nbsp;
        $que = mysql_query($sql, $konek) or die("SQL: ".mysql_error());
        $num = mysql_num_rows($que);
        
        if ($num > 0) {
            while ($row = mysql_fetch_assoc($que)) {
                $id = $row['id']; $username = $row['username']; $firstname = $row['firstname']; $email = $row['email']; $suspendon = $row['suspendon'];
                $rspecialist = $row['rspecialist']; $rspecialist_fullname = $row['rspecialist_fullname'];
                $rspecialist_email = $row['rspecialist_email']; $rspecialist_email_signature = $row['rspecialist_email_signature'];
                
                if ($week === 2) {
$subject = "Action Required: Don’t lose access to ImportGenius.com!";
$message = "Hi {$firstname},
                    
I've tried calling you about your account with Import Genius but haven’t gotten through yet. It seems your credit card was declined the last time we tried to run it.

Just sign in to your account and you’ll be prompted for the new card info.

Thanks!
                                
{$rspecialist_email_signature}";
                }
                else if ($week === 3) {
$subject = "Return to Import Genius for Less!";
$message = "Hi {$firstname},

I haven't heard back from you! Your ImportGenius.com account is currently suspended due to a billing issue with your card.

But don’t worry! This is a simple fix. And we’re going to make it worth your while to resolve the problem.

The issue is probably caused by a recently expired, canceled or maxed-out credit card. To give you an incentive to update your card and restore your account, I’d like to offer you a discount!

Simply give me a call at 855-573-9977 to take advantage of this offer.

Thank you!

{$rspecialist_email_signature}";
                }
                else if ($week === 4) {
$subject = "Upgraded Features for your ImportGenius.com Account";
$message = "Hi {$firstname},

Have you heard about all the changes we’ve made at ImportGenius.com?

We’ve added a team of account managers to help you find the data you need, generate reports, gather contact information for companies in our database and more.

I’d love to earn your business again. If you have any questions or concerns about this offer or our data services, please give me a call at 855-573-9977 or just reply to this e-mail.

{$rspecialist_email_signature}";
                }
                else if ($week === 5) {
$subject = "Quick Question About ImportGenius.com";
$message = "Hi {$firstname},

What did you think about the ImportGenius.com service? Did we have the trade data you were looking for?  Were you happy with the service we provided?  Any suggestions for how we can improve?

We’d love to hear from you so we can try to get better.

{$rspecialist_email_signature}";
                }
                else if ($week === 6) {
$subject = "Are you still involved in international trade?";
$message = "Hi {$firstname},

Are you still working in the international trade industry? What did you do with the ImportGenius.com database when you were a subscriber?

I’d love to earn your business back. You can reach me at any time at 855-573-9977 if you have any questions.

{$rspecialist_email_signature}";
                }
                else if ($week === 7) {
$subject = "Special Offer from Import Genius for {$username}";
$message = "Hi {$firstname},

We miss you at Import Genius! Are you still working in international trade? Let us help you get started again so you do not miss out on any valuable information.

Since it has been so long since you have used your account we would love to invite you back at a discounted rate and apply a second month free just for you for you!

Please contact me at 855-573-9977 right away to advantage of this AMAZING offer!

{$rspecialist_email_signature}";
                }
                else {
$subject = "Last Chance to Reactivate Your Import Genius Account";
$message = "Hi {$firstname},

I have been trying to get in touch with you about your ImportGenius.com account. I have not had much luck, so unless I hear otherwise, I will assume you have decided not to move forward with our service.

Let me know if there is anything further I can do. If there is someone else in your organization responsible for this account, please forward this email along -- or let me know and I will follow up accordingly.

If you would like to return your account to good standing please contact me at 855-573-9977.

{$rspecialist_email_signature}";
                }
                //echo $subject.$nbsp;
                //echo $message.$nbsp;
                //echo "<hr />";
                //$email = "crash_overload03@yahoo.com";
                //shell_exec('echo "'.$message.'" | mutt -s "'.$subject.'" '.$email.' -b daryle@codeninja.co -e "my_hdr From: '.$rspecialist_fullname.' <'.$rspecialist_email.'>"');
                shell_exec('echo "'.$message.'" | mutt -s "'.$subject.'" '.$email.' -b activities@importgenius.com -e "my_hdr From: '.$rspecialist_fullname.' <'.$rspecialist_email.'>"');
            }
        }
    
    }
    
    ob_flush();
