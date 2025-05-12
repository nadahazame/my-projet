<?php
session_start();
require_once '../includes/config.php';

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

$success_message = '';
$error_message = '';

// Traitement des actions sur les réservations
if (isset($_GET['action']) && isset($_GET['id'])) {
    $reservation_id = (int)$_GET['id'];
    $action = $_GET['action'];
    
    if ($action === 'confirm' || $action === 'cancel') {
        $new_status = $action === 'confirm' ? 'confirmed' : 'cancelled';
        
        $stmt = $conn->prepare("UPDATE reservations SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $reservation_id);
        
        if ($stmt->execute()) {
            $success_message = "Réservation " . ($action === 'confirm' ? 'confirmée' : 'annulée') . " avec succès";
        } else {
            $error_message = "Une erreur est survenue";
        }
    }
}

// Récupération des réservations avec les informations des utilisateurs et des chambres
$stmt = $conn->prepare("
    SELECT r.*, u.first_name, u.last_name, u.email, rm.room_type, rm.room_number 
    FROM reservations r 
    JOIN users u ON r.user_id = u.id 
    JOIN rooms rm ON r.room_id = rm.id 
    ORDER BY r.created_at DESC
");
$stmt->execute();
$reservations = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Réservations - Administration</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main class="container mt-5">
        <h1 class="mb-4">Gestion des Réservations</h1>

        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Chambre</th>
                                <th>Dates</th>
                                <th>Prix Total</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($reservation = $reservations->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($reservation['email']); ?></small>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($reservation['room_type']); ?><br>
                                        <small class="text-muted">N° <?php echo htmlspecialchars($reservation['room_number']); ?></small>
                                    </td>
                                    <td>
                                        <?php 
                                        echo date('d/m/Y', strtotime($reservation['check_in_date'])) . ' - ' . 
                                             date('d/m/Y', strtotime($reservation['check_out_date'])); 
                                        ?>
                                    </td>
                                    <td><?php echo number_format($reservation['total_price'], 2); ?> €</td>
                                    <td>
                                        <?php
                                        $status_class = [
                                            'pending' => 'warning',
                                            'confirmed' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                        $status_text = [
                                            'pending' => 'En attente',
                                            'confirmed' => 'Confirmée',
                                            'cancelled' => 'Annulée'
                                        ];
                                        ?>
                                        <span class="badge bg-<?php echo $status_class[$reservation['status']]; ?>">
                                            <?php echo $status_text[$reservation['status']]; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($reservation['status'] === 'pending'): ?>
                                            <div class="btn-group">
                                                <a href="?action=confirm&id=<?php echo $reservation['id']; ?>" 
                                                   class="btn btn-sm btn-success"
                                                   onclick="return confirm('Confirmer cette réservation ?')">
                                                    Confirmer
                                                </a>
                                                <a href="?action=cancel&id=<?php echo $reservation['id']; ?>" 
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Annuler cette réservation ?')">
                                                    Annuler
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 