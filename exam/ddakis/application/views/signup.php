<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
        <script src="<?php echo $root; ?>jquery-1.9.1.js" type = "text/javascript" ></script>
	<title>Le Signup Form</title>

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
        
        .required {
            color: red;
        }
        
        .label {
            text-align: right;
        }
        
        TABLE {
            width: 50%;
        }
        </style>
</head>
<body>

<div id="container">
	<h1>Le Signup Form</h1>

	<div id="body">
            <form id="numbersform">
                <table>
                    <thead></thead>
                    <tbody>
                        <tr>
                            <td class = "label"><label>First Name: <span class = "required">*</span></label></td>
                            <td><input type = "text" name = "first_name" id = "first_name" /></td>
                        </tr>
                        <tr>
                            <td class = "label"><label>Last Name: <span class = "required">*</span></label></td>
                            <td><input type = "text" name = "last_name" id = "last_name" /></td>
                        </tr>
                        <tr>
                            <td class = "label"><label>Username: <span class = "required">*</span></label></td>
                            <td><input type = "text" name = "username" id = "username" /><span id = "username_error"></span></td>
                        </tr>
                        <tr>
                            <td class = "label"><label>Password: <span class = "required">*</span></label></td>
                            <td><input type = "password" name = "password" id = "password" /></td>
                        </tr>
                        <tr>
                            <td class = "label"><label>Email Address: <span class = "required">*</span></label></td>
                            <td><input type = "text" name = "email" id = "email" /><span id = "email_error"></span></td>
                        </tr>
                        <tr>
                            <td class = "label">Phone: <span class = "required">*</span></label></td>
                            <td><input type = "text" name = "phone" id = "phone" /><span id = "phone_error"></span></td>
                        </tr>
                        <tr>
                            <td colspan ="2" align = "center"><input type = "button" name = "cmdsubmit" id = "cmdsubmit" value = "Submit" /></td>
                        </tr>
                    </tbody>
                </table>
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
            var first_name = $.trim($('#first_name').val());
            var last_name = $.trim($('#last_name').val());
            var username = $.trim($('#username').val());
            var password = $.trim($('#password').val());
            var email = $.trim($('#email').val());
            var phone = $.trim($('#phone').val());
            if (first_name && last_name && username && password && email && phone) {
                // Username validation
                var username_validation = check_username(username);
                if (username_validation == 0 || !username_validation) {
                    // Email format validation
                    var email_validation = check_email(email);
                    if (email_validation == 0 || !email_validation) {
                        // Phone validation
                        //var phone_validation = check_phone(phone);
                        //if (phone_validation == 1 || phone_validation) {
                            $.ajax({
                                url: '<?php echo $insert_user_url; ?>',
                                type: 'POST',
                                async: true,
                                data: {
                                    first_name: first_name,
                                    last_name: last_name,
                                    username: username,
                                    password: password,
                                    email: email,
                                    phone: phone
                                },
                                success: function(data) {
                                    if (data > 0) {
                                        $("#result").removeAttr('class').addClass('success').html('User successfully added.');
                                    }
                                    else {
                                        $("#result").removeAttr('class').addClass('error').html(data);
                                    }
                                },
                                error: function(data) {
                                }
                            });
                    }
                }
                
            }
        });
    });
    
    function check_email() {
        var email = $.trim($('#email').val());
        var at_sign_position = email.indexOf("@");
        var period_position = email.lastIndexOf(".");
        if (at_sign_position < 1 || period_position < at_sign_position + 2 || period_position + 2 >= email.length) {
            $('#email_error').addClass('error').text('Invalid email address.');
            return 0;
        }
        else {
            $('#email_error').removeAttr('class').text('');
            var result = "";
            // Check for existing email address
            $.ajax({
                url: '<?php echo $check_email_url; ?>',
                type: 'POST',
                async: true,
                data: {
                    email: email
                },
                success: function(data) {
                    if (data > 0) {
                        $('#email_error').addClass('error').text('Email exists.');
                        result = data;
                    }
                    else {
                        $('#email_error').removeAttr('class').text('');
                        result = data;
                    }
                },
                error: function(data) {
                }
            });
            return result;
        }
    }
    
    function check_username(username) {
        var result = "";
        $.ajax({
            url: '<?php echo $check_username_url; ?>',
            type: 'POST',
            async: true,
            data: {
                username: username
            },
            success: function(data) {
                if (data > 0) {
                    $('#username_error').addClass('error').text('Username exists.');
                    result = data;
                }
                else {
                    $('#username_error').removeAttr('class').text('');
                    result = data;
                }
            },
            error: function(data) {
            }
        });
        return result;
    }
    
    function check_phone(phone) {
        var result = "";
        $.ajax({
            url: '<?php echo $check_phone_url; ?>',
            type: 'POST',
            async: true,
            data: {
                phone: phone
            },
            success: function(data) {
                if (data == 0) {
                    $('#phone_error').addClass('error').text('Numbers only.');
                    result = data;
                }
                else {
                    $('#phone_error').removeAttr('class').text('');
                    result = data;
                }
            },
            error: function(data) {
            }
        });
        return result;
    }
</script>