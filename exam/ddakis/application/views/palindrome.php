<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
        <script src="<?php echo $root; ?>jquery-1.9.1.js" type = "text/javascript" ></script>
	<title>HANNAH: Paldindrome Checker</title>

	<style type="text/css">

	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body{
		margin: 0 15px 0 15px;
	}
	
	p.footer{
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}
	
	#container{
		margin: 10px;
		border: 1px solid #D0D0D0;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
	}
        
        .success {
            color: green;
        }
        
        .error {
            color: red;
        }
	</style>
</head>
<body>

<div id="container">
	<h1>HANNAH: Paldindrome Checker</h1>

	<div id="body">
            <form id="palindromeform">
                <p>Enter the text: <input type = "text" name = "text" id = "text" /></p>
		<p><input type = "button" name = "cmdsubmit" id = "cmdsubmit" value = "Submit" /></p>
            </form>
            <div id ="result"></div>
	</div>

	<!--<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>-->
        <p class="footer" id="footer"><a href="#"><-- Back To The Future</a></p>
</div>

</body>
</html>
<script type ="text/javascript">
    $(document).ready(function() {
        $('#footer').on('click', function() {
            location = "<?php echo $root; ?>";
        });
        
        $('#text').on('keydown', function(e) {
            var code = e.which;
            if (code == 13) {
                $('#cmdsubmit').click();
                return false;
            }
        });
        
        $('#cmdsubmit').on('click', function() {
            var text = $.trim($('#text').val());
            if (text) {
                $.ajax({
                    url: '<?php echo $is_it_a_palindrome_url; ?>',
                    type: 'POST',
                    data: {
                        text: text
                    },
                    success: function(data) {
                        //var etohtml = data > 0 ? text+" is a palindrome." : text+" is NOT a palindrome.";
                        var etohtml = data > 0 ? "TRUE" : "FALSE";
                        var etoclass = data > 0 ? "success" : "error";
                        $("#result").removeAttr('class').addClass(etoclass).html(etohtml);
                        $('#text').focus();
                    },
                    error: function(data) {
                    }
                });
            }
        });
    });
</script>