<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'hotel_db');

// Création de la connexion
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);


if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}


date_default_timezone_set('Europe/Paris');

// Configuration des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fonction pour nettoyer les entrées
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fonction pour vérifier si l'utilisateur est connecté
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Fonction pour vérifier si l'utilisateur est admin
function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Fonction pour rediriger si non connecté
function require_login() {
    if (!is_logged_in()) {
        header('Location: /login.php');
        exit();
    }
}

// Fonction pour rediriger si non admin
function require_admin() {
    if (!is_admin()) {
        header('Location: /index.php');
        exit();
    }
}

// Modifier la structure de la table users pour augmenter la taille du champ password
$sql = "ALTER TABLE users MODIFY COLUMN password VARCHAR(255) NOT NULL";
if (!$conn->query($sql)) {
    error_log("Erreur lors de la modification de la table users: " . $conn->error);
} 