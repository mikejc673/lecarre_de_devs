<?php
// app/views/auth/register.php
// La variable $error est disponible si une erreur de validation survient
?>

<h2>Inscription</h2>

<form action="<?php echo BASE_URL; ?>register" method="POST">
    <label for="email">Email :</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars(isset($_POST['email']) ? $_POST['email'] : ''); ?>" required>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required>

    <label for="confirm_password">Confirmer le mot de passe :</label>
    <input type="password" id="confirm_password" name="confirm_password" required>

    <input type="submit" value="S'inscrire">
</form>

<p>Déjà un compte ? <a href="<?php echo BASE_URL; ?>login">Connectez-vous ici</a>.</p>