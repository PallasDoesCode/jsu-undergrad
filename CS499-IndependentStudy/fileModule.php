<?php
	//This is the database file module for uploading and downloading user files
	
	class FileModule
	{
		private $dbConnection;
		
		function FileModule($connection)
		{
			$this->dbConnection = $connection; //use the connection that was given to me
		}
		
		// Upload a file based on username
		function upload($userName, $projectName, $fileName, $public, $xmlFileName)
		{
			$target_dir = "uploads/";
			

			if (!is_dir($target_dir))
			{
				mkdir($target_dir);
			}

			// Generate a pseudo-random ID to be used as the uploaded file name.
			// This prevents two users from uplaoding different files with the same name.
			$id = uniqid(rand(), true);
			$id = str_replace(".", "", $id);

			// This is a temporary solution used to get the name of the file to be stored.
			// Once the code has be created to parse any unformatted files and we know that
			// all files are formatted before calling this function then we will just get the
			// extension of the formatted file and use that.
			$fileExt = pathinfo($xmlFileName, PATHINFO_EXTENSION);

			$storedFileName = $id . "." . $fileExt;
			$target_dir = $target_dir . $storedFileName;

			if(!$this->fileExists($userName, $projectName))
			{
				if (copy($xmlFileName, $target_dir))
				{
					unlink($xmlFileName);
					$datetime = date('Y-m-d H:i:s');
					$stmt = $this->dbConnection->prepare("INSERT INTO files VALUES(?, ?, ?, ?, ?, ?)");
					$stmt->bind_param("ssssss", $userName, $projectName, $fileName, $storedFileName, $public, $datetime);
					if(!$stmt->execute())
					{
						echo "<br />";
						echo($stmt->error);
						$stmt->close();
						return false;
					}
					else
					{
						$stmt->close();
					}
					return true;
				}
			}
			return false;
		}
		
		/*
			This function gets files' information based on username and stores it in a
			2d array, which it then returns.  To access the data in this array, you have
			to specify the row number first, followed by the associated name of the
			variable that you wish to access.
			
			Ex: $filesArray[0]["fileName"] returns the first row's fileName field,
				  whereas $filesArray[0]["lastUpdate"] returns the first row's lastUpdate
				  field
		*/
		function getFilesInfo($userName)
		{
			//file name, public, last updated
			$stmt = $this->dbConnection->prepare("SELECT fileName, projectName, public, lastUpdate
			                                      FROM files
			                                      WHERE owner = ?
			                                      ORDER BY lastUpdate ASC");
			$stmt->bind_param("s", $userName);
			$stmt->execute();
			$stmt->bind_result($fileName, $projectName, $public, $lastUpdate);
			$filesArray = array();
			for($i = 0; $stmt->fetch(); $i++)
			{
				$filesArray[$i] = array( "fileName" => $fileName,
										 "projectName" => $projectName,
				                         "public" => $public,
				                         "lastUpdate" => $lastUpdate );
			}
			$stmt->close();
			return $filesArray;
		}
		
		/*
			This function gets the public files' information and stores it in a
			2d array, which it then returns.  To access the data in this array, you have
			to specify the row number first, followed by the associated name of the
			variable that you wish to access.
			
			Ex: $filesArray[0]["Owner"] returns the first row's Owner field,
				  whereas $filesArray[0]["fileName"] returns the first row's fileName
				  field
		*/
		function getPublicFilesInfo() {
			$stmt = $this->dbConnection->prepare("SELECT owner, fileName, projectName, lastUpdate
			                                      FROM files
			                                      WHERE public = 1");
		    $stmt->execute();
		    $stmt->bind_result($owner, $fileName, $projectName, $lastUpdate);
		    $filesArray = array();
		    for($i = 0; $stmt->fetch(); $i++) {
		    	$filesArray[i] = array( "owner" => $owner,
		    	                        "fileName" => $fileName,
										"projectName" => $projectName,
		    	                        "lastUpdate" => $lastUpdate);
		    }
		    $stmt->close();
		    return $filesArray;
		}
		
		//get file contents based on the file's owner and filename
		function getFileContents($owner, $fileName) {

			$stmt = $this->dbConnection->prepare("SELECT fileContents
			                                      FROM files
			                                      WHERE owner = ? AND fileName = ?");
			$stmt->bind_param("ss", $owner, $fileName);
			$stmt->execute();
			$stmt->bind_result($file);
			$stmt->fetch();
			$stmt->close();
			return $file;
		}
		
		//This function deletes a file from the database
		//TEST
		function deleteFile($Owner, $fileName) {
		
			$stmt = $this->dbConnection->prepare("DELETE FROM files
			                                      WHERE fileName = ? AND Owner = ?");
		    $stmt->bind_param("ss", $fileName, $Owner);
		    if($stmt->execute()) {
		    	$stmt->close();
		    	return true;
			}
			else {
				$stmt->close();
				return false;
			}
		
		}
		
		//internal function, check if username is valid; not yet used
		function validUserName($userName) {
			if($stmt = $this->dbConnection->prepare("SELECT COUNT(username)
													FROM usersinfo
													WHERE username = ?"));
			$stmt->bind_param("s", $userName);
			$stmt->execute();
			$stmt->bind_result($userNameCount);
			$stmt->fetch();
			$stmt->close();
			if ($userNameCount != 0) { //the username must exist
				return true;
			}
			else return false;
		}
		
		//internal function, check if filename exists under the username
		function fileExists($userName, $projectName)
		{
			if($stmt = $this->dbConnection->prepare("SELECT COUNT(projectName)
													 FROM files
													 WHERE owner = ? AND projectName = ?"));
			$stmt->bind_param("ss", $userName, $projectName);
			$stmt->execute();
			$stmt->bind_result($fileCount);
			$stmt->fetch();
			$stmt->close();

			if ($fileCount != 0) // The file must exist
			{
				return true;
			}
			
			else
			{
				return false;	
			}
		}
	}
?>