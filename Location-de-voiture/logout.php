<?php
session_start();

session_destroy();

//rediriger vers la page de login
header("location: login.php");
exit;
?>