<?php
global    $num_session;

if (!isset($session_id))
	$_SESSION['session_id']=0;

if (!isset($num_session))
{
	$num_session = uniqid(rand());
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