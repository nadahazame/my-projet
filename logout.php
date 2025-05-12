<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$_SESSION = array();

// Détruire la session
session_destroy();


header('Location: index.php');
exit();
?> 