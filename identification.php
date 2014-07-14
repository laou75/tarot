<?php
define('PATH_ROOT', __DIR__);
session_start();
include("include/config.inc.php");
$db= new Db();
$_GET["id"]=999;
$tpl= new Template($db);