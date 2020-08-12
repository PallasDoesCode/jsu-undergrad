<?php

	require('LoginModel.php');
	require('DatabaseModule.php');
	
    $dbMod = new DatabaseModule();
    $connection = $dbMod->connect();
    $loginModel = new LoginModel($connection);

    // Prevents the user from manually going to the page
    if(!$loginModel->IsUserLoggedIn())
    {
        header("location: login.php?action=loginError");
    }

    else
    {
        // Log the user out and clear any session variables
        $loginModel->LogoutUser();
        unset($_SESSION);
    	session_destroy();
    	session_write_close();

        header('Location: login.php');
    }
?>