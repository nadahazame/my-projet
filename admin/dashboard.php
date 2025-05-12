<?php
session_start();
require_once '../includes/config.php';

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// Récupérer les statistiques
$stats = [
    'total_rooms' => $conn->query("SELECT COUNT(*) as count FROM rooms")->fetch_assoc()['count'],
    'available_rooms' => $conn->query("SELECT COUNT(*) as count FROM rooms WHERE status = 'available'")->fetch_assoc()['count'],
    'pending_reservations' => $conn->query("SELECT COUNT(*) as count FROM reservations WHERE status = 'pending'")->fetch_assoc()['count'],
    'total_reservations' => $conn->query("SELECT COUNT(*) as count FROM reservations")->fetch_assoc()['count']
];

// Récupérer les réservations en attente
$stmt = $conn->prepare("
    SELECT r.*, u.first_name, u.last_name, u.email, rm.room_type, rm.room_number 
    FROM reservations r 
    JOIN users u ON r.user_id = u.id 
    JOIN rooms rm ON r.room_id = rm.id 
    WHERE r.status = 'pending' 
    ORDER BY r.created_at DESC
");
$stmt->execute();
$pending_reservations = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Hôtel de Luxe</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main class="container mt-5">
        <h1 class="mb-4">Tableau de Bord Administrateur</h1>

        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Chambres</h5>
                        <p class="card-text display-6"><?php echo $stats['total_rooms']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Chambres Disponibles</h5>
                        <p class="card-text display-6"><?php echo $stats['available_rooms']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h5 class="card-title">Réservations en Attente</h5>
                        <p class="card-text display-6"><?php echo $stats['pending_reservations']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Réservations</h5>
                        <p class="card-text display-6"><?php echo $stats['total_reservations']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Actions Rapides</h5>
                        <div class="btn-group">
                            <a href="rooms.php" class="btn btn-primary">Gérer les Chambres</a>
                            <a href="reservations.php" class="btn btn-success">Gérer les Réservations</a>
                            <a href="users.php" class="btn btn-info">Gérer les Utilisateurs</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Réservations en attente -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Réservations en Attente</h5>
                
                <?php if ($pending_reservations->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Chambre</th>
                                    <th>Dates</th>
                                    <th>Prix Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($reservation = $pending_reservations->fetch_assoc()): ?>
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
                                            <div class="btn-group">
                                                <a href="confirm_reservation.php?id=<?php echo $reservation['id']; ?>" 
                                                   class="btn btn-sm btn-success">
                                                    Confirmer
                                                </a>
                                                <a href="cancel_reservation.php?id=<?php echo $reservation['id']; ?>" 
                                                   class="btn btn-sm btn-danger">
                                                    Refuser
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        Aucune réservation en attente.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 