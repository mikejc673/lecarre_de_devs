<?php
// app/views/annonces/detail.php
// La variable $annonce est disponible
?>

<?php if ($annonce): ?>
    <div class="annonce-detail">
        <h2><?php echo htmlspecialchars($annonce['titre']); ?></h2>
        <p class="price"><?php echo htmlspecialchars(number_format($annonce['prix'], 2, ',', ' ')); ?> €</p>
        <p><strong>Description :</strong></p>
        <p><?php echo nl2br(htmlspecialchars($annonce['description'])); ?></p>
        <div class="meta-info">
            <p>Catégorie : <strong><?php echo htmlspecialchars($annonce['nom_categorie']); ?></strong></p>
            <p>Publié par : <strong><?php echo htmlspecialchars($annonce['user_email']); ?></strong></p>
            <p>Publié le : <strong><?php echo (new DateTime($annonce['date_publication']))->format('d/m/Y à H:i'); ?></strong></p>
        </div>
        <p><a href="<?php echo BASE_URL; ?>">Retour à la liste des annonces</a></p>
    </div>
<?php else: ?>
    <div class="alert alert-danger">
        <p>L'annonce demandée n'existe pas ou a été supprimée.</p>
        <p><a href="<?php echo BASE_URL; ?>">Retour à l'accueil</a></p>
    </div>
<?php endif; ?>