<?php
session_start();
require_once 'includes/config.php';
require_login();

$user_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';

// Récupérer les informations actuelles de l'utilisateur
$stmt = $conn->prepare("SELECT first_name, last_name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = clean_input($_POST['first_name']);
    $last_name = clean_input($_POST['last_name']);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $errors = [];

    // Validation des champs
    if (empty($first_name)) $errors[] = "Le prénom est requis";
    if (empty($last_name)) $errors[] = "Le nom est requis";
    if (empty($email)) $errors[] = "L'email est requis";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Format d'email invalide";

    // Vérifier si l'email existe déjà pour un autre utilisateur
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->bind_param("si", $email, $user_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $errors[] = "Cet email est déjà utilisé par un autre utilisateur";
    }

    // Si un nouveau mot de passe est fourni
    if (!empty($new_password)) {
        if (strlen($new_password) < 6) {
            $errors[] = "Le nouveau mot de passe doit contenir au moins 6 caractères";
        }
        if ($new_password !== $confirm_password) {
            $errors[] = "Les nouveaux mots de passe ne correspondent pas";
        }
        
        // Vérifier l'ancien mot de passe
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();
        
        if (!password_verify($current_password, $user_data['password'])) {
            $errors[] = "Le mot de passe actuel est incorrect";
        }
    }

    if (empty($errors)) {
        if (!empty($new_password)) {
            // Mise à jour avec nouveau mot de passe
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, password = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $first_name, $last_name, $email, $hashed_password, $user_id);
        } else {
            // Mise à jour sans mot de passe
            $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?");
            $stmt->bind_param("sssi", $first_name, $last_name, $email, $user_id);
        }

        if ($stmt->execute()) {
            $success_message = "Votre profil a été mis à jour avec succès";
            // Mettre à jour les données affichées
            $user['first_name'] = $first_name;
            $user['last_name'] = $last_name;
            $user['email'] = $email;
        } else {
            $error_message = "Une erreur est survenue lors de la mise à jour du profil";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mon profil - Hôtel Simple</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Modifier mon profil</h2>

                        <?php if (!empty($success_message)): ?>
                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                        <?php endif; ?>

                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo $error; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="first_name" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="last_name" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>

                            <hr class="my-4">

                            <h5>Changer le mot de passe (optionnel)</h5>
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Mot de passe actuel</label>
                                <input type="password" class="form-control" id="current_password" name="current_password">
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" class="form-control" id="new_password" name="new_password">
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                                <a href="client/dashboard.php" class="btn btn-secondary">Retour au tableau de bord</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 