<?php
session_start();
if (!isset($_SESSION['sessionTarot']))
{
    header("location: identification.php");
//    exit();
}