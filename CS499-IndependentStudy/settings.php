<?php 
    include 'header.php';
    //if the user is not logged in then it will redirect them to the login page
    if(!$loginModel->IsUserLoggedIn())
	{
        header("location: login.php?action=loginError");
    }
?>

	<div id="container">
		<form id="settingsForm" action="settingsUpload.php" method="post" enctype="multipart/form-data">

			<label for="firstName">First Name</label>
			<input type="text" name="firstName" size="40">

			<br /><br />

			<label for="lastName">Last Name</label>
			<input type="text" name="lastName" size="40">

			<br /><br />

			<label for="userName">Username</label>
			<input type="text" name="userName" size="40">
			
			<br /><br />
			
			<label for="email">Email</label>
			<input type="text" name="email" size="40">
			
			<br /><br />

			<label for="oldPassword">Current password</label>
			<input type="password" name="oldPassword" />

			<br /><br />

			<label for="newPassword">New password</label>
			<input type="password" name="newPassword" />

			<br /><br />

			<label for="confirmPassword">Confirm password</label>
			<input type="password" name="confirmPassword"/>

			<br /><br />

			<center><button type="submit" name="submit">Save changes</button></center>
		</form>
	</div>

