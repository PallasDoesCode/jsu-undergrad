<html>
<head>
	<link rel="stylesheet" href="stylesheet.css" type="text/css" />
</head>
<body>
	<h3 class="ChangeRName">
	New Name 
	</h3>
	
	<form class="ChangeRName" name="ChangeRName" method="post" action="">
		<p class="field">
			<input type="text" name="firstName" placeholder="New First Name" />
		</p>
		<p class="field">
			<input type="text" name="lastName" placeholder="New Last Name" />
		</p>
		<div class="buttonContainer">
			<p class="editButtons">
				<input type="submit" name="button" id="button" value="Submit" />
			</p>
			<p class="editButtons">
				<input type="button" value="Cancel" onclick="self.close()" />
			</p>
		</div>
	</form>
</body>
</html>