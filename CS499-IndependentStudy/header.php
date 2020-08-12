<?php
	require('LoginModel.php');
	require('DatabaseModule.php');
	
    $loginBar;
    $loginError = "";
    $dbMod = new DatabaseModule();
    $connection = $dbMod->connect();
    $loginModel = new LoginModel($connection);
    
    if(isset($_POST['action']))
	{
        if ($_POST['action'] == "logout")
		{
            $loginModel->LogoutUser();
        }
    }
    if((isset($_GET['action'])))
	{
        if($_GET['action'] == "loginError")
		{
            $loginError = "You must be logged in to access that page";
        }
    }
	
	date_default_timezone_set("US/Central"); 
?>

<!DOCTYPE html>
<html lang="en">
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Discourse Analysis - Welcome!</title>
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

</head>
<body>
    <div id="header">
        <img src="logo.jpg" height="200" width="80%" alt="Logo" />
        <div id="menu">
            <ul id="navigation">
                <li><a class="navButton" href="home.php">Home</a></li>
                <li><a class="navButton" href="myFiles.php">My Files</a></li>
				<?php
					if($loginModel->IsUserLoggedIn() && $loginModel->IsAdmin())
					{
						echo '<li>
								<a class="navButton" href="adminuser.php">Administrative Options</a>
							  </li>
						';
					}					
				
                    if (!($loginModel->IsUserLoggedIn()))
                    {
                        echo '<li>
                                <a class="navButton" href="">Login / Register</a>
                                <ul>
                                    <li><a class="navButton" href="login.php">Login</a></li>
                                    <li><a class="navButton" href="register.php">Register</a></li>
                              </li>
                        ';
                    }

                    else
                    {
                        echo '<li>
                                <a class="navButton">Welcome, ' . $loginModel->GetUsersFirstName() . '!</a>
                                <ul>
                                    <li><a class="navButton" href="settings.php">Account (Coming Soon)</a></li>
                                    <li><a class="navButton" href="logout.php">Logout</a></li>
                                </ul>
                              </li>
                        ';
                    }
                ?>
            </ul>
        </div>
        <div class="triangle-l"></div>
        <div class="triangle-r"></div>
    </div>