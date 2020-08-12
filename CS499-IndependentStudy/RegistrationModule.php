<?php
	/**
	 *
	 *
	 **/
	class RegistrationModule
	{
		private $dbConnect;
		private $username = "";
		private $password = "";
		private $email = "";
		private $name = "";

		/**
		 *
		 **/
		
		function RegistrationModule($dbConnect)
		{
			$this->dbConnect = $dbConnect;
		}

		/**
		 *
		 **/
		function InputName($name)
		{
			if (!empty($name))
			{
				$this->name = $name;
				return true;
			}
			else
			{
				return "Your name can not be empty.";
			}
		}

		/**
		 *
		 **/
		function InputUsername($username)
		{
			if(empty($username))
			{
			   return "Your username can not be blank."; 
			}
			elseif(!(strlen($username) >= 3))
			{
				return "Your username must be three characters or longer.";
			}
			else
			{
				if($stmt = $this->dbConnect->prepare("SELECT COUNT(username) AS UsernameCount FROM ((SELECT tempusersinfo.username FROM tempusersinfo) UNION (SELECT usersinfo.username FROM usersinfo)) AS usernamestable WHERE username = ?"))
				{
					$stmt->bind_param("s", $username);
					$stmt->execute();
					$stmt->bind_result($usernameCount);
					$stmt->fetch();
					$stmt->close();
					if($usernameCount == 0)
					{
						$this->username = $username;
						return true;
					}	
				}
				return "The entered username already exists";
			}
		}

		/**
		 *
		 **/
		function InputPassword($password, $confirmPassword)
		{
			if(!(strlen($password) >= 6))
			{
				return "The password does not meet the minimum length requirement";
			}
			elseif(!(strcmp($password ,$confirmPassword) == 0))
			{
				return "The passwords do not match";
			}
			else
			{
				// I'm using PHP's built-in cryptography function password_hash to
				// has the user's password before storing it in the database.
				$password = password_hash($password, PASSWORD_DEFAULT);
				$this->password = $password;

				return true;
			}
		}

		/**
		 *
		 **/
		function InputEmail($email, $confirmEmail)
		{
			if(!filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				return "The e-mail address provided is not valid.";
			}
			elseif(!strcmp($email ,$confirmEmail) == 0)
			{
				return "The email addresses provided do not match.";
			}
			else
			{
				$this->email = $email;
				return true;
			}
			return false;
		}

		/**
		 *
		 **/
		function RequestConfirmation()
		{
			if(strlen($this->username) > 0 && strlen($this->password) > 0 && strlen($this->email) > 0 && strlen($this->name) > 0)
			{
				$confirm_code=md5(uniqid(rand()));
				if($stmt = $this->dbConnect->prepare("INSERT INTO tempusersinfo(confirm_code, username, password, email, name)VALUES( ?, ?, ?, ?, ?)"))
					{
						$stmt->bind_param("sssss", $confirm_code, $this->username, $this->password, $this->email, $this->name);
						$result = $stmt->execute();
						$stmt->close();
						if($result)
						{
						
							$to=$this->email;

							// Your subject
							$subject="Your Discourse Analysis confirmation link here";

							// From
							$header="From: CS 491 Discourse Analysis Project <jsu.edu>";

							// Your message
							$message="Dear user, thank you for registering for Discourse Analysis \r\n";
							$message.="Click on this link to activate your account \r\n";
							//$message.="http://trc202.com/confirmation.php?registrationId=$confirm_code";
							$message.="http:/localhost/website2014/testlandingpage.php";

							// send email
							mail($to,$subject,$message,$header);
							
							
							return true;
						}
					}
			}
			return false;
		}


		/**
		 *
		 **/
		function ConfirmUser($key)
		{
			if($stmt = $this->dbConnect->prepare("SELECT COUNT(*) FROM tempusersinfo WHERE confirm_code = ?"))
			{
				$stmt->bind_param("s", $key);
				$stmt->execute();
				$stmt->bind_result($count);
				$stmt->fetch();
				$stmt->close();
				if($count > 0)
				{
					if($stmt = $this->dbConnect->prepare("SELECT username, email, password, name FROM tempusersinfo WHERE confirm_code= ? "))
					{
						$stmt->bind_param("s", $key);
						$stmt->execute();
						$stmt->bind_result($username, $email, $password, $name);
						$stmt->fetch();
						$stmt->close();
						if($stmt = $this->dbConnect->prepare("INSERT INTO usersinfo(username, password, email, name)VALUES(?, ?, ?, ?)"))
						{
							$stmt->bind_param("ssss", $this->username, $this->password, $this->email, $this->name);
							$stmt->execute();
							$stmt->close();
							if($stmt = $this->dbConnect->prepare("DELETE FROM tempusersinfo WHERE confirm_code = ?"))
							{
								$stmt->bind_param("s", $key);
								$stmt->execute();
								$stmt->close();
								
							}
							return true;
						}

					} 
					return false;
				}
			}
		}
	}
?>