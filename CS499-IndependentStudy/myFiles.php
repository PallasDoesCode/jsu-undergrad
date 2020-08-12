<?php 
    include 'header.php';
    //if the user is not logged in then it will redirect them to the login page
    if(!$loginModel->IsUserLoggedIn())
	{
        header("location: login.php?action=loginError");
    }
    include 'fileModule.php';	
?>

<div class="container">
    <br />
	<div id="fileOptions">
		<form action="GraphicalInterface.php" method="post">
			<input type="Hidden" name="owner" />
			<input type="Hidden" name="filename" />
			<button id="toggleBtn" type="button" ><input id="selectAll" type="checkbox" /></button>
			<button id="uploadBtn" type="button" >Upload New File</button>
			<button id="editBtn" type="Submit" >Edit In Workspace</button>
		</form>
	</div>
	
	<br /><br />
	
    <div id="projectTable">
    	<?php
    		//get files from database here and put them into a table
    		$fileMod = new FileModule($connection);
    		$userName = $loginModel->getUserName();
    		$fileArray = $fileMod->getFilesInfo($userName);
    	
    		echo "<table id='myFileTable'>
    					<tr>
    						<th></th>
    						<th>Project Name</th>
    						<th>Public</th>
    						<th>Last Update</th>
    					</tr>";
    		for($i = 0; $i < count($fileArray); $i++) // Loop through every row
            { 
    			$row = $fileArray[$i];
    			$projectName = $row['projectName'];
                $fileName = $row['fileName'];
    			
    			if ($row['public'])
				{ 
					$public = "Yes";
				}
    			else
				{
					$public = "No";
				}
    			
    			$lastUpdate = $row['lastUpdate'];
    			
    			echo "<tr>";
    			echo "<td><input type='checkbox' class='userCheckbox' id='$fileName'></td>
    				  <td>$projectName</td>
    				  <td>$public</td>
    				  <td>$lastUpdate</td>";
    		 	echo "</tr>";
    		}
    		echo "</table>";
    
    	?>
    </div>

<?php include 'footer.php';?>

