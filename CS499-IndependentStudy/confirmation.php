<?php
include 'header.php';

include_once('RegistrationModule.php');
include_once('RegistrationModule.php');
include_once('DatabaseModule.php');
$dbMod = new DatabaseModule();
$connection = $dbMod->connect();
$regMod = new RegistrationModule($connection);
$result = false;

if(isset($_GET["registrationId"]))
{
    $result = $regMod->ConfirmUser($_GET["registrationId"]);    
}
?>
<div class="container">
    <center><?php if($result){ echo "Successfully registered";}else{echo "Invalid registration key";}?></center>
</div>

<?php include 'footer.php'; ?>
