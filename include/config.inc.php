<?php
$GLOBALS["Config"]["DATABASE"]["DBSERVER"]		=	"localhost";
$GLOBALS["Config"]["DATABASE"]["DBUSER"]		=	"usertarot";
$GLOBALS["Config"]["DATABASE"]["DBPASSWORD"]	=	"passtarot";
$GLOBALS["Config"]["DATABASE"]["DBNAME"]		=	"tarot";

$GLOBALS["Config"]["URL"]["ROOT"] 			=	'http://tarot.fr/';
$GLOBALS["Config"]["URL"]["IMG"]			=	$GLOBALS["Config"]["URL"]["ROOT"]."img/";
$GLOBALS["Config"]["URL"]["KIT"]			=	$GLOBALS["Config"]["URL"]["IMG"]."kits/0/";
$GLOBALS["Config"]["URL"]["LOGO"]			=	$GLOBALS["Config"]["URL"]["KIT"]."kits/0/logos/";
$GLOBALS["Config"]["URL"]["PORTRAIT"]		=	$GLOBALS["Config"]["URL"]["IMG"]."portraits/";

$GLOBALS["Config"]["PATH"]["ROOT"]			=	realpath("./");
$GLOBALS["Config"]["PATH"]["IMG"]			=	realpath("./")."/images/";
$GLOBALS["Config"]["PATH"]["PORTRAIT"]		=	$GLOBALS["Config"]["PATH"]["IMG"]."/portraits/";
$GLOBALS["Config"]["PATH"]["PAGE"]			=	realpath(".")."/pages/";

$GLOBALS["Config"]["SITE"]["DEBUG"]			=	TRUE;

setlocale (LC_TIME, "fr");

function my_autoloader($class) {
    if (file_exists('class/' . strtolower($class) . '.class.php'))
    {
        include_once('class/' . strtolower($class) . '.class.php');
    }
}

spl_autoload_register('my_autoloader');