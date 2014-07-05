<?php
include("include/config.inc.php");
include("include/test_session.inc.php");
include("class/db.class.php");
include("class/template.class.php");

function __autoload($classname)
{
    if (file_exists('class/' . strtolower($classname) . '.class.php'))
    {
        include_once('class/' . strtolower($classname) . '.class.php');
    }
}