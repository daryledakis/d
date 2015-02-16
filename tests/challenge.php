<?php
    $a = FALSE;
?>
<!DOCTYPE html>
<html>
    <head>
        <link href = "../css/style.css" rel = "stylesheet" type = "text/css" />
        <script src = "../js/jquery-1.10.2.js" type = "text/javascript"></script>
        <script>
            var Months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            function getDateFromWeek(w) {
                var y = new Date().getFullYear();
                var d = (1 + (w - 1) * 7); // 1st of January + 7 days for each week

                return new Date(y, 0, d);
            }
			
            $(document).ready(function() {
                $('#amount').val(20)
                            .focus()
                            .on('keyup', function(e) {
                                var code = e.which;
                                if (code === 13) {
                                    $('#compute').click();
                                }
                            });

                $('#compute').on('click', function() {
                    var amount = parseInt($.trim($('#amount').val()));
                    var total = 0;
                    var html = "<table border = '1'><thead><th>Date</th><th>Week #</th><th>Amount to Save</th><th>Total Savings</th></thead><tbody>";
//                    console.log($.isNumeric(amount));
                    if ($.isNumeric(amount)) {
                        var prevmonth = 0;
                        var amountmonth = 0;
                        for (var x = 1; x <= 52; x++) {
                            // Total Savings
                            total += (amount * x);
                            var etodate = getDateFromWeek(x);
//                            console.log(Months[etodate.getMonth()]);
                            if (prevmonth == etodate.getMonth()) {
                                // Total Savings per Month
                                amountmonth += (amount * x);
                                console.log(amount+"|"+x+"|"+amountmonth);
                            }   
                            else {
                                html += "</tr><tr><td colspan = 2>Savings per cut-off: </td><td class = 'right'>"+amountmonth+"</td><td>&nbsp;</td></tr><tr>";
                                console.log(amount+"|"+x+"|"+amountmonth);
                                console.log("--------");
                                amountmonth = amount * x;
                            }
                            html += "<tr><td class = 'left'>"+Months[etodate.getMonth()]+"</td><td class = 'center'>" + x + "</td><td class = 'right'>" + amount * x;
                            html += "<td class = 'right'>" + total + "</td></tr>";
                            prevmonth = etodate.getMonth(); 
                        }
                        html += "</tbody></table>";
                        $('#result').removeClass('error').addClass('success').text('Total: ' + total);
                        $('#chart').html(html);
                    }
                    else {
                        $('#result').removeClass('success').addClass('error').text('Numbers only please.');
                        $('#chart').html('');
                    }
                    $('#amount').focus();
                    //alert(amount);
                });
            });
        </script>
    </head>
    <body>
        <input type = "text" id = "amount" /> &nbsp;
        <button type = "button" id = "compute">Compute</button><br /> <br />
        <div id = "result"></div>
        <div id = "chart"></div>

    </body>
</html>