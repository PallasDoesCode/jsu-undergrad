<html>
<head>
	<link rel="stylesheet" href="stylesheet.css" type="text/css" />
</head>
<body>
<h3 class="adminEditHeader">New Password</h3>
<form class="ChangeEmail" name="ChangeEmail" method="post" action="">
	<p>
		<input type="text" name="n_password" placeholder="new password" />
	</p>
	<p>
		<input type="text" name="n_password_c" placeholder="confirm password" />
	</p>
	<div class="buttonContainer">
		<p class="editButtons">
			<input type="submit" name="button" id="button" value="Submit" />
		</p>
		<p class="editButtons">
			<input type="button" value="Cancel" onclick="self.close()" />
		</p>
</form>

</body>
</html>