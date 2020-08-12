<?php
	include 'header.php';

	if(!$loginModel->IsUserLoggedIn())
	{
        header("location: login.php?action=loginError");
    }
?>

	<div id="container">
		<br /><br /><br /><br />
		<center><h2>Please choose your file to upload</h2></center>
		<form id="uploadForm" action="fileUpload.php" method="post" enctype="multipart/form-data">
			<label for="projectName">Project Name:</label>
			<input type="text" name="projectName" size="40" placeholder="Project Name" required>
			
			<br><br>
			
			<label for="fileaddress">File Location:</label>
			<input type="file" name="fileaddress" size="40" required>
			
			<br><br>
			
			<label for="public">Check to make this file public</label>
			<input type="checkbox" name="public" class="pull-right">
			
			<br><br>
			
			<label for="useDefault">Check to use the default conjunction list</label>
			<input type="checkbox" name="useDefault" class="pull-right" checked>
			
			<br><br>
			
			<label for="isFormatted">Check if text file is formatted</label>
			<input type="checkbox" name="isFormatted" class="pull-right">
			
			<br><br>
			
			<center><button type="submit" name="submit">Submit Query</button></center>
		</form>
	</div>

<?php
	include 'footer.php';
?>