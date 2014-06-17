<?php
if (!isset($session_id))
	$_SESSION['session_id']=0;

if (!isset($GLOBALS['num_session']))
{
    $GLOBALS['num_session'] = uniqid(rand());
	session_register("numSession");
}

if (array_key_exists("sessionTarot", $_SESSION))
{
	$sessionAdmin = $_SESSION["sessionTarot"];
}
else
{
	Header("location: identification.php");
	exit();
}