<?php

/**
 * This module handles the commands the admin
 * will be able to use to control users and 
 * their files. 
 *
 * All of these functions will check 
 * admin creditentials before proceeding
 *
 **/

class AdminUserModule
{

	private $dbConnect;
	function AdminUserModule($dbConnect)
	{
		$this->dbConnect = $dbConnect;
	}
	/**
	 * Changes a username from one username to another.
	 * This will notify the user of the username change, and the new username.
	 * 
	 * @param string $oldUsername the current name of the user
	 * @param string $newUsername the name the user will have after it is changed
	 *
	 * @return String returns error Text
	 *
	 */
	function ChangeUsername($oldUsername, $newUsername)
	{
		if ( strlen($newUsername) > 3)
		{
			// Check if Old Username exist. Our interface should always have the correct old User name
			if ($stmt = $this->dbConnect->prepare("SELECT COUNT(username) AS UsernameCount FROM SELECT (usersinfo.username FROM usersinfo) AS usernamestable WHERE username = ?") )
			{
				$stmt->bind_param("s", $oldUsername);
				$stmt->execute();
				$stmt->bind_result($usernameCount);
				$stmt->fetch();
				$stmt->close();
				if($usernameCount == 0)
				{
					return "Error: There is no username in the database with that name";
				}
			}
			// Checks temp user table and the user table for copys of users that already exist.
			if ($stmt = $this->dbConnect->prepare("SELECT COUNT(username) AS UsernameCount FROM ((SELECT tempusersinfo.username FROM tempusersinfo) UNION (SELECT usersinfo.username FROM usersinfo)) AS usernamestable WHERE username = ?") )
			{
				$stmt->bind_param("s", $newUsername);
				$stmt->execute();
				$stmt->bind_result($usernameCount);
				$stmt->fetch();
				$stmt->close();
				if($usernameCount > 0)
				{
					return "Error: The new username already exist.";
				}
			}
			// Updates the username
			if ($stmt = $this->dbConnect->prepare("UPDATE usersinfo SET username = ? WHERE username = ?") )
			{
				$stmt->bind_param("ss", $newUsername, $oldUsername);
				$stmt->execute();
				$stmt->close();
				
				// TODO email user of their name change
			}
			
		}
		else 
		{
			return "Error: Username is too short";
		}
	}
	
	/**
	 * Changes the password of a given user. This will notify the
	 * user of a password change.
	 * 
	 * @param string $username The name of the user who password will be changed.
	 * @param string $newPassword The new password for the user.
	 *
	 * @return bool Returns false is the change failed for some reason
	 *
	 */
	function ChangePassword($username, $newPassword)
	{
		if ( $stmt = $this->dbConnect->prepare("SELECT COUNT(username) AS UsernameCount FROM SELECT (usersinfo.username FROM userinfo) AS usernamestable WHERE username = ?")) 
		{
			$stmt->bind_param("s", $username);
			$stmt->execute();
			$stmt->bind_result($usernameCount);
			$stmt->fetch();
			$stmt->close();
			if ( $usernameCount == 0 )
			{
				return "Error: There is no username in the database with that name";
			}
			else
			{
				if ( $stmt = $this->dbConnect->prepare("UPDATE userinfo SET password = ? WHERE username = ? ") )
				{
					$stmt->bind_param("ss", $newPassword, $username);
					$stmt->execute();
					$stmt->close();
					
					// TODO email user of their password change.
				}
			}
			
		}
	}
	
	/**
	 *
	 * Changes the email of the give user. Will notify both emails of the change.
	 *
	 * @param string $username The name of the user who email will be changed.
	 * @param string $newEmail The new email address the user will have set.
	 *
	 * @return bool Return false if the email could not be changed
	 */
	function ChangeEmail($username, $newEmail)
	{
		$oldEmail = '';
		if ( $stmt = $this->dbConnect->prepare("SELECT COUNT(username) AS UsernameCount FROM SELECT (usersinfo.username FROM userinfo) AS usernamestable WHERE username = ?") )
		{

			$stmt->bind_param("s", $username);
			$stmt->execute();
			$stmt->bind_result($usernameCount);
			$stmt->fetch();
			$stmt->close();
			
			if ( $usernameCount == 0 )
			{
				return "Error: THere is no username in the database with that name";
			}
			else 
			{
				if ( $stmt = $this->dbConnect->prepare("SELECT Email FROM `usersinfo` WHERE username = ?") )
				{
					$stmt->bind_param("s", $username);
					$stmt->execute();
					$stmt->bind_result($oldEmail);
					$stmt->fetch();
					$stmt->close();
				}
				if ( $stmt = $this->dbConnect->prepare( "" ) )
				{
					$stmt->bind_param("ss", $newEmail, $username);
					$stmt->execute();
					$stmt->close();
					
					// TODO email both emails of the email change
				}
			}
		}
	
	}
	
	/**
	 *
	 * Changes the real name of the given user. The user will be notified
	 * of the name change
	 *
	 * @param string $username The name of the user who real name will be changed.
	 * @param string $newName The new real name of the user.
	 *
	 * @return bool Return false if the real name could not be changed.
	 *
	 */
	 
	function ChangeName($username, $newName)
	{
		if ( $stmt = $this->dbConnect->prepare("SELECT COUNT(username) AS UsernameCount FROM SELECT (usersinfo.username FROM userinfo) AS usernamestable WHERE username = ?") )
		{
			$stmt->bind_param("s", $username);
			$stmt->execute();
			$stmt->bind_result($usernameCount);
			$stmt->fetch();
			$stmt->close();
			
			if ( $usernameCount == 0 )
			{
				return "Error: There is no username in the database with the name";
			}
			else 
			{
				if ( $stmt == $this->dbConect->prepare("UPDATE userinfo SET email = ? WHERE username = ?" ) )
				{
					$stmt->bind_param("ss", $newName, $username);
					$stmt->exectute();
					$stmt->close();
					
					// TODO email user of their password change
				}
			}
		}
	}
	
	/**
	 * Will not specify until we declare what Delete user will require
	 */
	function DeleteUser()
	{
		
	}
	
	/**
	 * Grabs the entry for given user
	 *
	 * @param $user the user we wish to view
	 * 
	 * @return an Array of the request user 
	 * 	return['Username'] = Username, return['Email'] = User Email, and return['Name'] = User Full Name to access data in array
	 */
	function ViewUser($user)
	{
		if( $stmt = $this->dbConnect->prepare( "SELECT username, email, name FROM usersinfo WHERE username = ? " ) )
		{
			$stmt->bind_param("s", $user);
			if(!$stmt->execute() )
			{
			}
			
			// R in the variable name stands for "result"
			$stmt->bind_result($r_uname, $r_email, $r_name);
			$stmt->fetch();
			
			$row['username'] = $r_uname;
			$row['email'] = $r_email;
			$row['name'] = $r_name;
			
			
			$stmt->close();
			return $row;	
		}
	
	}

	/**
	 * Grabs the total number of users in the database
	 *
	 * @return int of number of users or false is the query can not run for some reason
	 */
	function TotalNumberOfUsers()
	{
		
			if ($result = $this->dbConnect->query( "SELECT COUNT(username) AS UsernameCount FROM usersinfo" ) )
			{
				
				$row = $result->fetch_assoc();
				$result->free();
				return $row['UsernameCount'];
			}
			else 
			{
				return false;
			}
			
	}

	/**
	 * Emails the specified email address of the change
	 *
	 *
	 */
	function SendNotification($subject, $message, $email)
	{
		$sentmail = mail($email, $subject, $message);
	}
} 

class AdminFileModule
{
	function EditFileOwner()
	{
	
	}
	
	function ChangeFilePermissions()
	{
	
	}
	
	function EditFilename()
	{
	
	}
	
	function EditFileLocation()
	{
	
	}
	
	Function DeleteFile()
	{
	
	}
	
	Function ViewFile()
	{
	
	}
	
	Function GetUserFileList()
	{
	
	}
	
	Function AddFileToUser()
	{
	
	}
	
	
}

?>