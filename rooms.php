<?php
// Vérifier si une session n'est pas déjà active avant d'en démarrer une
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/config.php';

// Récupération des chambres disponibles
$sql = "SELECT * FROM rooms WHERE status = 'available' ORDER BY room_type, price_per_night";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Chambres - Hôtel de Luxe</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="container mt-5">
        <h1 class="text-center mb-5">Nos Chambres</h1>

        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while($room = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($room['room_type']); ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted">Chambre <?php echo htmlspecialchars($room['room_number']); ?></h6>
                                <p class="card-text"><?php echo htmlspecialchars($room['description']); ?></p>
                                <ul class="list-unstyled">
                                    <li><strong>Capacité:</strong> <?php echo $room['capacity']; ?> personnes</li>
                                    <li><strong>Prix par nuit:</strong> <?php echo number_format($room['price_per_night'], 2); ?> €</li>
                                </ul>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <a href="reservation.php?room_id=<?php echo $room['id']; ?>" class="btn btn-primary">Réserver</a>
                                <?php else: ?>
                                    <a href="login.php" class="btn btn-secondary">Connectez-vous pour réserver</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        Aucune chambre disponible pour le moment.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 