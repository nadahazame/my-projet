<?php
session_start();
require_once '../includes/config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Récupérer les informations de l'utilisateur
$stmt = $conn->prepare("SELECT first_name, last_name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Récupérer les réservations de l'utilisateur
$stmt = $conn->prepare("
    SELECT r.*, rm.room_type, rm.room_number 
    FROM reservations r 
    JOIN rooms rm ON r.room_id = rm.id 
    WHERE r.user_id = ? 
    ORDER BY r.created_at DESC
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$reservations = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Tableau de Bord - Hôtel de Luxe</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Mon Profil</h5>
                        <p class="card-text">
                            <strong>Nom:</strong> <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?><br>
                            <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?>
                        </p>
                        <a href="../edit_profile.php" class="btn btn-primary">MODIFIER MON PROFIL</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Mes Réservations</h5>
                        
                        <?php if ($reservations->num_rows > 0): ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
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
                                                        <a href="cancel_reservation.php?id=<?php echo $reservation['id']; ?>" 
                                                           class="btn btn-sm btn-danger"
                                                           onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                                            Annuler
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                Vous n'avez pas encore de réservation.
                                <a href="../rooms.php" class="alert-link">Voir nos chambres</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 