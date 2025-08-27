<?php
// app/views/layout/404.php
// Vue d'erreur 404
?>

<div class="error-page">
    <div class="error-content">
        <h1>404</h1>
        <h2>Page non trouvée</h2>
        <p>Désolé, la page que vous recherchez n'existe pas ou a été déplacée.</p>
        <div class="error-actions">
            <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">Retour à l'accueil</a>
            <a href="javascript:history.back()" class="btn btn-secondary">Page précédente</a>
        </div>
    </div>
</div>
