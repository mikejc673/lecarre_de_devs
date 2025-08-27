<?php
// app/views/home/index.php
// Les variables $annonces et $categories sont disponibles grâce au extract($data) dans BaseController::loadView()
?>

<div class="home-page">
    <section class="hero">
        <h2>Bienvenue sur Le Carré 2 Devs</h2>
        <p>Découvrez et partagez des opportunités dans le monde du développement</p>
    </section>

    <div class="search-bar">
        <input type="text" placeholder="Rechercher...">
    </div>

    <div class="category-filters">
        <a href="#" class="category-filter-btn active" data-category-id="">Toutes les catégories</a>
        <?php foreach ($categories as $category): ?>
            <a href="#" class="category-filter-btn" data-category-id="<?php echo htmlspecialchars($category['id_categorie']); ?>">
                <?php echo htmlspecialchars($category['nom_categorie']); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <section class="annonces-grid">
        <?php if (!empty($annonces)): ?>
            <div class="annonces-container">
                <?php foreach ($annonces as $annonce): ?>
                    <div class="annonce-card" data-category-id="<?php echo $annonce['id_categorie']; ?>">
                        <div class="annonce-header">
                            <h3><?php echo htmlspecialchars($annonce['titre']); ?></h3>
                            <span class="category-badge">
                                <?php 
                                $categoryName = '';
                                foreach ($categories as $category) {
                                    if ($category['id_categorie'] == $annonce['id_categorie']) {
                                        $categoryName = $category['nom_categorie'];
                                        break;
                                    }
                                }
                                echo htmlspecialchars($categoryName);
                                ?>
                            </span>
                        </div>
                        <div class="annonce-content">
                            <p class="annonce-description">
                                <?php echo htmlspecialchars(substr($annonce['description'], 0, 150)); ?>
                                <?php if (strlen($annonce['description']) > 150): ?>...<?php endif; ?>
                            </p>
                            <div class="annonce-meta">
                                <span class="price"><?php echo number_format($annonce['prix'], 2); ?> €</span>
                                <span class="date"><?php echo date('d/m/Y', strtotime($annonce['date_publication'])); ?></span>
                            </div>
                        </div>
                        <div class="annonce-actions">
                            <a href="<?php echo BASE_URL; ?>annonce/detail/<?php echo $annonce['id_annonce']; ?>" class="btn btn-primary">
                                Voir détails
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-annonces">
                <p>Aucune annonce disponible pour le moment.</p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo BASE_URL; ?>annonce/add" class="btn btn-primary">Ajouter la première annonce</a>
                <?php else: ?>
                    <p><a href="<?php echo BASE_URL; ?>register">Inscrivez-vous</a> ou <a href="<?php echo BASE_URL; ?>login">connectez-vous</a> pour ajouter des annonces.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </section>
</div>

<script src="<?php echo BASE_URL; ?>public/js/main.js"></script>