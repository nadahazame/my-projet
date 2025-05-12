<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hôtel Simple - Accueil</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-hotel"></i> Hôtel Simple
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="rooms.php">Chambres</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reservation.php">Réservation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Connexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <h1>Découvrez le confort et l'élégance</h1>
                <p>Une expérience hôtelière exceptionnelle au cœur de la ville</p>
                <div class="hero-buttons">
                    <a href="rooms.php" class="btn btn-primary">Nos Chambres</a>
                    <a href="reservation.php" class="btn btn-accent">Réserver</a>
                </div>
            </div>
        </div>
        <div class="hero-scroll-down">
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

    <!-- Services Section -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">Nos Services</h2>
            <div class="row">
                <div class="col-md-3">
                    <div class="service-item">
                        <i class="fas fa-wifi service-icon"></i>
                        <h4>WiFi Gratuit</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="service-item">
                        <i class="fas fa-parking service-icon"></i>
                        <h4>Parking</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="service-item">
                        <i class="fas fa-utensils service-icon"></i>
                        <h4>Restaurant</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="service-item">
                        <i class="fas fa-spa service-icon"></i>
                        <h4>Spa</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <p>Email: contact@hotelsimple.com</p>
                    <p>Tél: +33 1 23 45 67 89</p>
                </div>
                <div class="col-md-4">
                    <h5>Liens utiles</h5>
                    <ul class="list-unstyled">
                        <li><a href="about.php">À propos</a></li>
                        <li><a href="rooms.php">Chambres</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Suivez-nous</h5>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Ajouter la classe 'scrolled' à la navbar lors du défilement
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Faire défiler jusqu'à la section des services lors du clic sur l'icône de défilement
        document.querySelector('.hero-scroll-down').addEventListener('click', function() {
            const servicesSection = document.querySelector('.section');
            servicesSection.scrollIntoView({ behavior: 'smooth' });
        });
    </script>
</body>
</html> 