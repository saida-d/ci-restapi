<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to RestAPI Manager</title>
	 <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	<!-- Latest compiled and minified JavaScript -->
	
	<script src="<?php echo base_url()?>assets/js/jquery-2.1.1.js"></script>
	
	<script>
	 
	function trigger_login(){
		 
	    var re = new RegExp(/^.*\//);
        var rCPath= re.exec(window.location.href);
		var parsePath=$(location).attr('href');
		 
        var username= $('#username').val();
		var password= $('#password').val();		
		 
		$("#trigger_login").html('Please wait..');
		$.ajax({
		/* contentType: "application/json; charset=utf-8",
		 dataType: "json",*/
		 
		type: "POST",
		url: rCPath+"home/login_byapi",
		data: {username:username,password:password}
		})
		.success(function(msg) {
		 alert(msg);
		$("#trigger_login").html(' Login success ');
		 
		});
	 
	}
	</script>
</head>
<body>

<div id="container" >
    
    <div class="col-md-4">
	 &nbsp;
	</div>
	<div class="col-md-4">
	<h2>Login Form</h2><hr>
	 <form>
	<div class="form-group">
	  <label>Username</label>
	  <input type="text" class="form-control" name="username"   id="username" placeholder="Username">
	</div>

	<div class="form-group">
	  <label>Password</label>
	  <input type="password" class="form-control" name="password"  id="password" placeholder="*********">
	</div>
	<div class="form-group">
	  <button class="btn btn-primary" type="button" name="loginBtn" onclick='trigger_login()'>SignIn</button>
	</div>
	
	<div id="trigger_login"></div>
	</div>
	<div class="col-md-4">
	 &nbsp;
	</div>
	</form>
</div>

</body>
</html>