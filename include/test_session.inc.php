<?php
session_start();
if (!isset($_SESSION['sessionTarot']))
{
    Header("location: identification.php");
    exit();
}