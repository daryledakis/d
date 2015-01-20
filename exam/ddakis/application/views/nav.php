<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
        <script src="<?php echo $root; ?>jquery-1.9.1.js" type = "text/javascript" ></script>
	<title>The Red, Blue, and Gray Page</title>

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
        
        .selected {
            background-color: red;
        }
        
        .inline {
            display: inline-block;
            padding: 10px;
        }
        
        .menu {
            background-color: blue;
            color: white;
            font-size: large;
            height: auto;
            width: 100%;
            top: 0;
            left: 0;
            position: absolute;
            z-index: 999;
        }
        
        a {
            color: white;
            text-decoration: none;
        }
        
        .main {
            background-color: #eeeeee;
            color: gray;
            width: 100%;
            height: 93%;
            left: 0;
            position: absolute;
            font-size: 400px;
            text-align: center;
        }
        
        .shown {
            display: block;
        }
        
        .hidden {
            display: none;
        }
	</style>
</head>
<body>

    <div class="menu">
        <div id="1" class="inline selected"><a href="#">Navigation 1</a></div>
        <div id="2" class="inline"><a href="#">Navigation 2</a></div>
        <div id="3" class="inline"><a href="#">Navigation 3</a></div>
        <div id="4" class="inline"><a href="#">Navigation 4</a></div>
        <div id="5" class="inline"><a href="#">Navigation 5</a></div>
        <div id="6" class="inline"><a href="#">Navigation 6</a></div>
    </div>
    
    <div class="body">
        <div id="main6" class="main hidden"><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />6</div>
        <div id="main5" class="main hidden"><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />5</div>
        <div id="main4" class="main hidden"><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />4</div>
        <div id="main3" class="main hidden"><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />3</div>
        <div id="main2" class="main hidden"><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />2</div>
        <div id="main1" class="main shown"><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />1</div>
    </div>

</body>
</html>
<script type ="text/javascript">
    $(document).ready(function() {
        $('#footer').on('click', function() {
            location = "<?php echo $root; ?>";
        });
        
        $('.inline').on('click', function() {
            if ($(this).hasClass('selected')) {
            }
            else {
                $('.inline.selected').removeClass('selected');
                $(this).addClass('selected');
                var id = $(this).attr('id');
                var current_id = $('.main.shown').attr('id');
                current_id = current_id.replace('main', '');
                //alert(id+"|"+current_id);
                if (id > current_id) {
                    $('#main'+current_id)
                        .animate({left: '-1500px'}, 1000)
                        .queue(function() {
                            $('#main'+id).dequeue();
                        });
                    $('#main'+id)
                        .css('left','1500px')
                        .show()
                        .animate({left: '0px'}, 1000)
                        .queue(function() {
                            $('#main'+current_id).removeClass('shown').addClass('hidden');
                            $('#main'+id).removeClass('hidden').addClass('shown');
                            $('#main'+id).dequeue();
                        });
                }
                else {
                    $('#main'+current_id)
                        .animate({left: '1500px'}, 1000)
                        .queue(function() {
                            $('#main'+id).dequeue();
                        }
                    );
                    $('#main'+id)
                        .show()
                        .css('left','-1500px')
                        .animate({left: '0px'}, 1000)
                        .queue(function() {
                            $('#main'+current_id).removeClass('shown').addClass('hidden');
                            $('#main'+id).removeClass('hidden').addClass('shown');
                            $('#main'+id).dequeue();
                        });
                }
            }
        });
    });
</script>