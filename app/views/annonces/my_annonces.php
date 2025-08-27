<?php
// app/views/annonces/my_annonces.php
// La variable $annonces (de l'utilisateur connecté) est disponible
?>

<h2>Mes annonces</h2>

<p><a href="<?php echo BASE_URL; ?>annonce/add" class="button">Ajouter une nouvelle annonce</a></p>

<?php if (empty($annonces)): ?>
    <p>Vous n'avez pas encore publié d'annonces.</p>
<?php else: ?>
    <div class="annonce-list">
        <?php foreach ($annonces as $annonce): ?>
            <div class="annonce-card">
                <h3><a href="<?php echo BASE_URL; ?>annonce/detail/<?php echo htmlspecialchars($annonce['id_annonce']); ?>">
                    <?php echo htmlspecialchars($annonce['titre']); ?>
                </a></h3>
                <p class="price"><?php echo htmlspecialchars(number_format($annonce['prix'], 2, ',', ' ')); ?> €</p>
                <p class="category">Catégorie : <?php echo htmlspecialchars($annonce['nom_categorie']); ?></p>
                <p class="date">Publié le : <?php echo (new DateTime($annonce['date_publication']))->format('d/m/Y H:i'); ?></p>
                <div class="annonce-actions">
                    <a href="<?php echo BASE_URL; ?>annonce/edit/<?php echo htmlspecialchars($annonce['id_annonce']); ?>" class="button">Modifier</a>
                    <form action="<?php echo BASE_URL; ?>annonce/delete/<?php echo htmlspecialchars($annonce['id_annonce']); ?>" method="POST" style="display:inline;">
                        <button type="submit" class="button button-danger delete-annonce-btn">Supprimer</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>