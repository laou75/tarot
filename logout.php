<?php
session_start();
unset($_SESSION["sessionTarot"]);
header("Location: index.php");