<?php
// app/views/layout/header.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Carré 2 Devs - Petites Annonces</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    </head>
<body>
    <header>
        <div class="container">
            <h1><a href="<?php echo BASE_URL; ?>">Le Carré 2 Devs</a></h1>
            <nav>
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>">Accueil</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="<?php echo BASE_URL; ?>annonce/add">Ajouter une annonce</a></li>
                        <li><a href="<?php echo BASE_URL; ?>my-annonces">Mes annonces</a></li>
                        <li>Bonjour, <?php echo htmlspecialchars($_SESSION['user_email']); ?> !</li>
                        <li><a href="<?php echo BASE_URL; ?>logout">Déconnexion</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo BASE_URL; ?>register">Inscription</a></li>
                        <li><a href="<?php echo BASE_URL; ?>login">Connexion</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container">
        <?php
        // Affichage des messages de session (succès, erreur, bienvenue)
        if (isset($_SESSION['welcome_message'])) {
            echo '<div class="alert alert-info">' . htmlspecialchars($_SESSION['welcome_message']) . '</div>';
            unset($_SESSION['welcome_message']);
        }
        if (isset($success)) { // Message passé via le contrôleur
            echo '<div class="alert alert-success">' . htmlspecialchars($success) . '</div>';
        }
        if (isset($error)) { // Message passé via le contrôleur
            echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
        }
        // Pour les messages d'erreur/succès qui viennent d'une redirection
        if (isset($_SESSION['success_message'])) {
            echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
            unset($_SESSION['success_message']);
        }
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
            unset($_SESSION['error_message']);
        }
        ?>