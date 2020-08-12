<?php
	include('header.php');
	include('parser.php');

	//	This file will accept the file from "upload.php" to upload to the database
	function getFile($input, $defaultValue)
	{
		if(isset($_FILES[$input]) && $_FILES[$input]['size'] > 0)
		{
			$contents = file_get_contents($_FILES[$input]['tmp_name']);
			return $contents;
		}
		else
		{
			return $defaultValue;
		}
	}
	
	$parser = new Parser();
	
	$projectName = $_REQUEST['projectName'];
	$fileName = basename($_FILES['fileaddress']['name']);
	$tempFileName = $_FILES['fileaddress']['tmp_name'];
	$fileContents = file_get_contents($tempFileName);

	$formattedOption = isset($_REQUEST['isFormatted']);
	$publicOption = (int)isset($_REQUEST['public']);

	$userName = $loginModel->getUserName();

	//	Use the fileModule.php to upload files
	include 'fileModule.php';
	
	$fileMod = new FileModule($connection); // $connection comes from the header.php
	echo "<div class='container'>";

	$xmlFileName = realpath($tempFileName);
	if ($formattedOption)
	{
		$xml = $parser->parseFormattedText($fileContents);

		$parsedFileName = pathinfo($fileName, PATHINFO_FILENAME) . ".xml";
		file_put_contents($parsedFileName, $xml);
	}

	else
	{
		$xml = $parser->parseUnformattedText($fileContents);

		$parsedFileName = pathinfo($fileName, PATHINFO_FILENAME) . ".xml";
		file_put_contents($parsedFileName, $xml);
	}
	
	if($fileMod->upload($userName, $projectName, $fileName, $publicOption, $parsedFileName))
	{
		echo "<p>Your upload was successful!</p>";

	}
	else
	{
		echo "<p>Your upload was unsuccessful!</p>";
	}
	echo "</div>";
	
		
	//we can redirect the page or give a confirmation message
?>

