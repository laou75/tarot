<?php
session_start();
$id = isset($_GET["id"]) ? $_GET["id"] : 1;
if (!isset($_SESSION['sessionTarot']) && ($id!=$GLOBALS["Config"]["SITE"]["PAGELOGIN"]))
{
//    header("location: identification.php");
//    header("location: index.php?id=".$GLOBALS["Config"]["SITE"]["PAGELOGIN"]);
    $_GET["id"] = $GLOBALS["Config"]["SITE"]["PAGELOGIN"];
}