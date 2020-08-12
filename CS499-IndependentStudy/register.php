<?php 

	include 'header.php';
	include_once dirname(__FILE__).'/secureimage/securimage.php';


    $securimage = new Securimage();
    $catpchaError = null;

    include_once('RegistrationModule.php');
    include_once('DatabaseModule.php');
    $dbMod = new DatabaseModule();
    $connection = $dbMod->connect();
    $regMod = new RegistrationModule($connection);
    
    $firstName = $lastName = $username = $password = $passwordAgain = $email = "";
    if(isset($_POST['firstName']))
    {
        $firstName = $_POST['firstName'];
    }

    if(isset($_POST['lastName']))
    {
        $lastName = $_POST['lastName'];
    }

    if(isset($_POST['username']))
    {
        $username = $_POST['username'];
    }

    if(isset($_POST['password']))
    {
        $password = $_POST['password'];
    }

    if(isset($_POST['passwordAgain']))
    {
        $passwordAgain = $_POST['passwordAgain'];
    }

    if(isset($_POST['email']))
    {
        $email = $_POST['email'];
    }
    
    if(isset($_POST['firstName']) || isset($_POST['lastName']))
    {
        $nameError = $regMod->InputName($_POST['firstName'] . " " . $_POST['lastName']);
    }

    if(isset($_POST['username']))
    {
        $usernameError = $regMod->InputUsername($_POST['username']);
    }

    if(isset($_POST['password']) || isset($_POST['passwordAgain']))
    {
        $passwordError = $regMod->InputPassword($_POST['password'], $_POST['passwordAgain']);
    }

    if(isset($_POST['email']))
    {
        $emailError = $regMod->InputEmail($_POST['email'],$_POST['email']);
    }
    
    if(isset($_POST['captcha_code']))
    {
        if($securimage->check($_POST['captcha_code']) == true)
        {
            if($regMod->RequestConfirmation())
            {
                echo '<meta http-equiv="REFRESH" content="0;url=registrationSuccess.php"></HEAD>';
                //TODO redirect to registration sucessful page
            }
        }

        else
        {
            $catpchaError = "The code did not match. Try Again";
        }
    }
    
    function checkIsset($str){
        if (isset($str))
        {
            echo $str;
        }

        else
        {
            return "";
        }
    }
?>

    <script type="text/javascript">
        var formError = Array();
        formError[false, false, false];
        $(document).ready(function()
		{
            $('#button').click(function(){
                if($('#username').val() == "")
				{
                    if(!formError[0])
					{
                        $("#username").after("<span><img src='Images/red-x.gif' alt='X'/></span>");
                    }

                    formError[0] = true;
                    return false;
                }

                if($("#password").val() != $("#passwordAgain").val() || $("#password").val() == "" || $("#passwordAgain").val() == "")
				{
                    if(!formError[1])
					{
                        $("#password").after("<span><img src='Images/red-x.gif' alt='X'/></span>");
                        $("#passwordAgain").after("<span><img src='Images/red-x.gif' alt='X'/><div style='color:red'>Passwords must match</div></span>");
                    }

                    formError[1] = true;
                    return false;
                }

                if($('#email').val() == "")
				{
                    if(!formError[2])
                    {
                        $("#email").after("<span><img src='Images/red-x.gif' alt='X'/></span>");
                    }

                    formError[2] = true;
                    return false;
                }
            });

            $('#refreshLink').click(function()
			{
                $('#captcha').attr("src","secureimage/securimage_show.php?num=" + Math.floor(Math.random()*11));
                return false;
            })
        }); 
    </script>
	
<div class="container">

    <br />
    <form name="RegForm" method="post" action="">
        <div class="regHeader">
            <div id="RegisterHeading">Register Here !<hr /></div>
            
            <p>
                    <input type="text" id="username" size="35" name="username" placeholder="Username" value="<?php checkIsset($username) ?>" />
            </p>
            <div class="registerErrorText">
                <p><?php if(isset($usernameError) && is_string($usernameError)){echo $usernameError;} ?></p>
            </div>
            <p>
                    <input type="password" id="password" size="35" name="password" placeholder="Password" value="<?php checkIsset($password) ?>" />
            </p>
            <p>
                    <input type="password" id="passwordAgain" size="35" name="passwordAgain" placeholder="Password Again" value="<?php checkIsset($passwordAgain) ?>" />
            </p>
            <div class="registerErrorText">
                <p><?php if(isset($passwordError) && is_string($passwordError)){echo $passwordError;} ?></p>
            </div>
            <p>
                    <input type="text" id="email" size="35" name="email" placeholder="Email Address" value="<?php checkIsset($email) ?>" />
            </p>
            <div class="registerErrorText">
                <p><?php if(isset($emailError) && is_string ($emailError)){echo $emailError;} ?></p>
            </div>
            <p>
                    <input type="text" name="firstName" size="35" placeholder="First Name" value="<?php checkIsset($firstName) ?>"/>
            </p>
            <p>
                    <input type="text" name="lastName" size="35" placeholder="Last Name" value="<?php checkIsset($lastName) ?>" />
            </p>
            <div class="registerErrorText">
                <p><?php if(isset($nameError) && is_string ($nameError)){echo $nameError;} ?></p>
            </div>
            <br />
            <table align="center">
                <tr>
                    <td>
                        <img id="captcha" src="secureimage/securimage_show.php" alt="CAPTCHA Image" />
                    </td>
                    <td>
                        <a id="refreshLink" href="#"><img src="secureimage/images/Refresh Icon.jpg" alt="Refresh Image"/></a>
                    </td>
                </tr>
                <tr>
                    <td>
                        Enter Code: <input type="text" name="captcha_code" size="10" maxlength="6" />
                    </td>
                </tr>
                <tr>
                    <td class="captchaErrorMsg" colspan="2" >
                        <?php checkIsset($catpchaError) ?>
                    </td>
                </tr>
            </table>
            <p>
                <input type="submit" name="button" id="button" value="Submit" />
            </p>
        <div>
    </form>
</div>

<?php include 'footer.php';?>