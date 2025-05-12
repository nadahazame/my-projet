<?php
// Vérifier si une session n'est pas déjà active avant d'en démarrer une
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/config.php';

$success_message = '';
$error_message = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    
    if ($name && $email && $subject && $message) {
        $success_message = "Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.";
        
        
        $name = $email = $subject = $message = '';
    } else {
        $error_message = "Veuillez remplir tous les champs du formulaire.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez-nous - Hôtel Simple</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section" style="height: 40vh;">
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <h1>Contactez-nous</h1>
                <p>Nous sommes à votre écoute</p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">Nous contacter</h2>
            
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="contact-form">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom complet</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Sujet</label>
                                <input type="text" class="form-control" id="subject" name="subject" value="<?php echo isset($subject) ? htmlspecialchars($subject) : ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Envoyer le message</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="contact-info">
                        <h3 class="mb-4">Informations de contact</h3>
                        
                        <div class="contact-info-item">
                            <i class="fas fa-map-marker-alt contact-info-icon"></i>
                            <div>
                                <h5>Adresse</h5>
                                <p>12 Boulvarde hassane 2 , Marrakech</p>
                            </div>
                        </div>
                        
                        <div class="contact-info-item">
                            <i class="fas fa-phone contact-info-icon"></i>
                            <div>
                                <h5>Téléphone</h5>
                                <p>+212 762071851</p>
                            </div>
                        </div>
                        
                        <div class="contact-info-item">
                            <i class="fas fa-envelope contact-info-icon"></i>
                            <div>
                                <h5>Email</h5>
                                <p>contact@hotelsimple.com</p>
                            </div>
                        </div>
                        
                        <div class="contact-info-item">
                            <i class="fas fa-clock contact-info-icon"></i>
                            <div>
                                <h5>Horaires</h5>
                                <p>Réception: 24h/24, 7j/7</p>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h5>Suivez-nous</h5>
                            <div class="social-links">
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container-fluid p-0">
            <div class="ratio ratio-21x9">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.9916256937595!2d2.292292615509614!3d48.873779979288475!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66fc4e0b4b0a1%3A0x5f2d1c9c9c9c9c9c!2sArc%20de%20Triomphe!5e0!3m2!1sfr!2sfr!4v1620000000000!5m2!1sfr!2sfr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 