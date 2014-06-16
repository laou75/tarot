<?
/*
session_name("tarot_session");
session_start();
*/
global    $num_session;

if (!isset($session_id))
	$_SESSION['session_id']=0;

if (!isset($num_session))
{
	$num_session = uniqid(rand());
	session_register("numSession");
}

/*
	echo "<pre>";
	print_r($GLOBALS);
	echo "</pre>";
*/
if (array_key_exists("sessionTarot", $_SESSION))
{
	$sessionAdmin = $_SESSION["sessionTarot"];
}
else
{
	Header("location: identification.php");
	exit();
//	exit("Pas de session");
//	HeaderLocation($basehrefAdmin."identification.php");
//	exit($basehrefAdmin);
/*
	echo "<pre>";
	print_r($_SESSION);
	echo "</pre>";
*/
}
?>