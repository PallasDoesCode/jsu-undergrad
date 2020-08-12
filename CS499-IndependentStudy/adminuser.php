<?php
	include 'header.php';
	include 'UserModel.php';

	include_once('DatabaseModule.php');
	$dbMod = new DatabaseModule();
	$connection = $dbMod->connect();
	$userModel = new UserModel($connection);
	
	//	Redirect the user if they are not logged in
    if(!$loginModel->IsUserLoggedIn())
	{
        header("location: login.php?action=loginError");
    }	
	
	//	Redirect the user if they are logged in and but are not an admin
	else if ($loginModel->IsUserLoggedIn() && !$loginModel->isAdmin())
	{
		header("location: myFiles.php");
	}
?>

<div class="container">
    <br />
	
    <!-- User Information -->
	<div id="userOptions">
		<button id="toggleBtn" type="button" ><input id="selectAll" type="checkbox" /></button>
		<button id="add" type="button" name="add">Add</button>
		<button id="delete" type="button" name="delete">Delete</button>
		<button id="edit" type="button" name="edit">Change Password</button>
	</div>
	
	<br /><br />
	
    <table id="userTable">
		<tr>
			<th></th>
			<th class="columnHeading">Username</th>
			<th class="columnHeading">E-mail</th>
			<th class="columnHeading">Full Name</th>
			<th class="columnHeading">Last Logged In</th>
			<th class="columnHeading"># of Files Uploaded</th>
			<th class="columnHeading">Approved User</th>
		</tr>
		
		<?php
			$requestedUsers = $userModel->QueryUserInfo(0, $userModel->TotalNumberOfUsers());
			
			if ( is_array($requestedUsers) )
			{
				foreach ( $requestedUsers as $user )
				{
					if ($user['isOnline'] == 1)
					{
						$user['lastLogin'] = "Online Now!";
					}

					else if ($user['lastLogin'] == null)
					{
						$user['lastLogin'] = "Never";
					}

					if ($user['isApprovedUser'] == 1)
					{
						$user['isApprovedUser'] = "Yes";
					}

					else
					{
						$user['isApprovedUser'] = "No";
					}

					echo '<tr class="tcontent">';
					echo '<td><input type="checkbox" class="userCheckbox" /></td>';
					echo '<td class="userDetailsCell" width=200>' . $user['username'] . '</td>';
					echo '<td class="userDetailsCell" width=200>' . $user['email'] . '</td>';
					echo '<td class="userDetailsCell" width=200>' . $user['name'] . '</td>';
					echo '<td class="userDetailsCell" width=200>' . $user['lastLogin'] . '</td>';
					echo '<td class="userDetailsCell" width=200>' . $user['numberOfFiles'] . '</td>';
					echo '<td class="userDetailsCell" width=200>' . $user['isApprovedUser'] . '</td';
					echo '</tr>';
				}			
			}
		?>
    </table>
</div>

<?php
	include 'footer.php';
?>