<?php
$modeWIndow=true;
$GLOBALS["Config"]["DATABASE"]["DBSERVER"]		=	"localhost";
$GLOBALS["Config"]["DATABASE"]["DBUSER"]		=	"usertarot";
$GLOBALS["Config"]["DATABASE"]["DBPASSWORD"]	=	"changeme";
$GLOBALS["Config"]["DATABASE"]["DBNAME"]		=	"tarot";

$GLOBALS["Config"]["URL"]["ROOT"] 			=	'http://'.$_SERVER["HTTP_HOST"].'/tarot/';
$GLOBALS["Config"]["URL"]["IMG"]			=	$GLOBALS["Config"]["URL"]["ROOT"]."images/";
$GLOBALS["Config"]["URL"]["KIT"]			=	$GLOBALS["Config"]["URL"]["IMG"]."kits/0/";
$GLOBALS["Config"]["URL"]["LOGO"]			=	$GLOBALS["Config"]["URL"]["KIT"]."kits/0/logos/";
$GLOBALS["Config"]["URL"]["PORTRAIT"]		=	$GLOBALS["Config"]["URL"]["IMG"]."portraits/";

$GLOBALS["Config"]["PATH"]["ROOT"]			=	tools::forWindows(realpath("./"));
$GLOBALS["Config"]["PATH"]["IMG"]			=	tools::forWindows(realpath("./")."/images/");
$GLOBALS["Config"]["PATH"]["PORTRAIT"]		=	tools::forWindows($GLOBALS["Config"]["PATH"]["IMG"]."/portraits/");
$GLOBALS["Config"]["PATH"]["PAGE"]			=	tools::forWindows(realpath(".")."/pages/");

$GLOBALS["Config"]["SITE"]["DEBUG"]			=	TRUE;

setlocale (LC_TIME, "fr");