<?php
// app/views/annonces/add_edit.php
// Les variables $annonce (si modification) et $categories sont disponibles
// Les variables $error sont disponibles si une erreur de validation survient
$isEditing = isset($annonce) && $annonce !== null;
?>

<h2><?php echo $isEditing ? 'Modifier une annonce' : 'Ajouter une annonce'; ?></h2>

<form action="<?php echo BASE_URL; ?>annonce/<?php echo $isEditing ? 'edit/' . htmlspecialchars($annonce['id_annonce']) : 'add'; ?>" method="POST">
    <label for="titre">Titre de l'annonce :</label>
    <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($isEditing ? ($annonce['titre'] ?? '') : (isset($_POST['titre']) ? $_POST['titre'] : '')); ?>" required>

    <label for="description">Description :</label>
    <textarea id="description" name="description" rows="8" required><?php echo htmlspecialchars($isEditing ? ($annonce['description'] ?? '') : (isset($_POST['description']) ? $_POST['description'] : '')); ?></textarea>

    <label for="prix">Prix (€) :</label>
    <input type="number" id="prix" name="prix" step="0.01" min="0" value="<?php echo htmlspecialchars($isEditing ? ($annonce['prix'] ?? '') : (isset($_POST['prix']) ? $_POST['prix'] : '')); ?>" required>

    <label for="categorie">Catégorie :</label>
    <select id="categorie" name="categorie" required>
        <option value="">Sélectionnez une catégorie</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?php echo htmlspecialchars($category['id_categorie']); ?>"
                <?php
                // Sélectionne la catégorie actuelle lors de la modification ou si elle a été soumise
                if (($isEditing && $annonce['id_categorie'] == $category['id_categorie']) || (isset($_POST['categorie']) && $_POST['categorie'] == $category['id_categorie'])) {
                    echo 'selected';
                }
                ?>
            >
                <?php echo htmlspecialchars($category['nom_categorie']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="submit" value="<?php echo $isEditing ? 'Modifier l\'annonce' : 'Ajouter l\'annonce'; ?>">
</form>

<p><a href="<?php echo BASE_URL; ?>my-annonces">Retour à mes annonces</a></p>