<?php
define('PATH_ROOT', __DIR__);
session_start();
unset($_SESSION["sessionTarot"]);
header("Location: index.php");