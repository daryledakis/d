<?php

?>
<!DOCTYPE html>
<html>
    <head>
        <link href = "css/style.css" rel = "stylesheet" type = "text/css" />
        <script src = "js/jquery-1.10.2.js" type = "text/javascript"></script>
        <script>
            $(document).ready(function() {
                $('#amount').focus();

                $('#amount').on('keyup', function(e) {
                    var code = e.which;
                    if (code === 13) {
                        $('#compute').click();
                    }
                });

                $('#compute').on('click', function() {
                    var amount = parseInt($.trim($('#amount').val()));
                    var total = 0;
                    var html = "<table border = '1'><thead><th>Week #</th><th>Amount to Save</th><th>Total Savings</th></thead><tbody>";
                    console.log($.isNumeric(amount));
                    if ($.isNumeric(amount)) {

                        for (var x = 1; x <= 52; x++) {
                            total += (amount * x);
                            html += "<tr><td class = 'center'>" + x + "</td><td class = 'right'>" + amount * x + "</td><td class = 'right'>" + total + "</td></tr>";
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