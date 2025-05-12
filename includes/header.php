<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$isInSubfolder = strpos($_SERVER['PHP_SELF'], '/client/') !== false || strpos($_SERVER['PHP_SELF'], '/admin/') !== false;
$basePath = $isInSubfolder ? '../' : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hôtel Simple</title>
    <link rel="stylesheet" href="<?php echo $basePath; ?>css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .navbar {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            padding: 1rem 0;
        }
        .navbar-brand {
            font-weight: 600;
            color: #333;
        }
        .nav-link {
            color: #666;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: color 0.3s ease;
        }
        .nav-link:hover, .nav-link.active {
            color: #007bff;
        }
        .navbar-toggler {
            border: none;
            padding: 0.5rem;
        }
        .btn-outline-primary {
            border-color: #007bff;
            color: #007bff;
        }
        .btn-outline-primary:hover {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="<?php echo $basePath; ?>index.php">
                <i class="fas fa-hotel"></i> Hôtel Simple
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" 
                           href="<?php echo $basePath; ?>index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'rooms.php' ? 'active' : ''; ?>" 
                           href="<?php echo $basePath; ?>rooms.php">Chambres</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'reservation.php' ? 'active' : ''; ?>" 
                           href="<?php echo $basePath; ?>reservation.php">Réservation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>" 
                           href="<?php echo $basePath; ?>contact.php">Contact</a>
                    </li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <?php if($_SESSION['role'] === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $basePath; ?>admin/dashboard.php">Administration</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $basePath; ?>client/dashboard.php">Mon Compte</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="<?php echo $basePath; ?>logout.php">Déconnexion</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : ''; ?>" 
                               href="<?php echo $basePath; ?>login.php">Connexion</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</body>
</html> 