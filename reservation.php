<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/config.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$room_id = isset($_GET['room_id']) ? (int)$_GET['room_id'] : 0;
$errors = [];
$success = false;

// Récupérer les informations de la chambre
$stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ? AND status = 'available'");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$room = $stmt->get_result()->fetch_assoc();

if (!$room) {
    header('Location: rooms.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    
    //  dates
    $today = date('Y-m-d');
    if ($check_in < $today) {
        $errors[] = "La date d'arrivée ne peut pas être dans le passé";
    }
    if ($check_out <= $check_in) {
        $errors[] = "La date de départ doit être après la date d'arrivée";
    }
    
    //  la chambre est disponible pour ces dates
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM reservations 
                           WHERE room_id = ? 
                           AND status = 'confirmed'
                           AND ((check_in_date <= ? AND check_out_date >= ?) 
                           OR (check_in_date <= ? AND check_out_date >= ?)
                           OR (check_in_date >= ? AND check_out_date <= ?))");
    $stmt->bind_param("issssss", $room_id, $check_out, $check_in, $check_out, $check_in, $check_in, $check_out);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    if ($result['count'] > 0) {
        $errors[] = "Cette chambre n'est pas disponible pour les dates sélectionnées";
    }
    
    if (empty($errors)) {
        // Calculer le nombre de nuits et le prix total
        $check_in_date = new DateTime($check_in);
        $check_out_date = new DateTime($check_out);
        $interval = $check_in_date->diff($check_out_date);
        $nights = $interval->days;
        $total_price = $nights * $room['price_per_night'];
        
        // Créer la réservation
        $stmt = $conn->prepare("INSERT INTO reservations (user_id, room_id, check_in_date, check_out_date, total_price) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iissd", $_SESSION['user_id'], $room_id, $check_in, $check_out, $total_price);
        
        if ($stmt->execute()) {
            $success = true;
        } else {
            $errors[] = "Une erreur est survenue lors de la réservation";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation - Hôtel de Luxe</title>
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
                        <h2 class="card-title text-center mb-4">Réserver une chambre</h2>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                Votre réservation a été effectuée avec succès ! Vous recevrez un email de confirmation.
                                <div class="mt-3">
                                    <a href="client/dashboard.php" class="btn btn-primary">Voir mes réservations</a>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php foreach ($errors as $error): ?>
                                            <li><?php echo $error; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <div class="room-details mb-4">
                                <h4><?php echo htmlspecialchars($room['room_type']); ?></h4>
                                <p><?php echo htmlspecialchars($room['description']); ?></p>
                                <p><strong>Prix par nuit:</strong> <?php echo number_format($room['price_per_night'], 2); ?> €</p>
                            </div>

                            <form method="POST" action="">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="check_in" class="form-label">Date d'arrivée</label>
                                        <input type="date" class="form-control" id="check_in" name="check_in" 
                                               min="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="check_out" class="form-label">Date de départ</label>
                                        <input type="date" class="form-control" id="check_out" name="check_out" 
                                               min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Confirmer la réservation</button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 