<?php
setlocale(LC_ALL, 'fr_FR');
setlocale(LC_TIME, "fr");

$GLOBALS["Config"]["DATABASE"]["DBSERVER"]		=	"localhost";
$GLOBALS["Config"]["DATABASE"]["DBUSER"]		=	"root";
$GLOBALS["Config"]["DATABASE"]["DBPASSWORD"]	=	"";
$GLOBALS["Config"]["DATABASE"]["DBNAME"]		=	"tarot";

$GLOBALS["Config"]["URL"]["ROOT"] 			=	'';$_SERVER['SERVER_NAME'];
$GLOBALS["Config"]["URL"]["IMG"]			=	$GLOBALS["Config"]["URL"]["ROOT"]."img/";
$GLOBALS["Config"]["URL"]["LOGO"]			=	$GLOBALS["Config"]["URL"]["IMG"]."logos/";
$GLOBALS["Config"]["URL"]["PORTRAIT"]		=	$GLOBALS["Config"]["URL"]["IMG"]."portraits/";

$GLOBALS["Config"]["PATH"]["ROOT"]			=	realpath("./");
$GLOBALS["Config"]["PATH"]["IMG"]			=	realpath($GLOBALS["Config"]["PATH"]["ROOT"].'/img/').'/';
$GLOBALS["Config"]["PATH"]["PORTRAIT"]		=	realpath($GLOBALS["Config"]["PATH"]["IMG"].'portraits/').'/';
$GLOBALS["Config"]["PATH"]["PAGE"]			=	realpath(".")."/pages/";
$GLOBALS["Config"]["PATH"]["JS"]			=	realpath(".")."/js/";

$GLOBALS["Config"]["SITE"]["DEBUG"]			=	TRUE;

$GLOBALS["Config"]["SITE"]["TITRE"]			=	'Tarot';
$GLOBALS["Config"]["SITE"]["PAGEDEFAULT"]   =   1;
$GLOBALS["Config"]["SITE"]["PAGELOGIN"]     =   999;
$GLOBALS["Config"]["SITE"]["MAXBYLIST"]     =   10;

spl_autoload_register(function ($class) {
    if (file_exists(PATH_ROOT.'/class/' . strtolower($class) . '.class.php'))
    {
        include_once(PATH_ROOT.'/class/' . strtolower($class) . '.class.php');
    }
    elseif (file_exists(PATH_ROOT.'/class/model/' . strtolower($class) . '.class.php'))
    {
        include_once(PATH_ROOT.'/class/model/' . strtolower($class) . '.class.php');
    }
});
