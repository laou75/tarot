<?php
session_start();
include("include/config.inc.php");
include("class/db.class.php");
include("class/session.class.php");
$_GET["id"]=999;
include("class/template.class.php");
function __autoload($classname)
{
    if (file_exists('class/' . strtolower($classname) . '.class.php'))
    {
        include_once('class/' . strtolower($classname) . '.class.php');
    }
}