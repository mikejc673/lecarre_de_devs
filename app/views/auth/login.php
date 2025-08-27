<?php
// app/views/auth/login.php
// Les variables $error et $success sont disponibles si un message doit être affiché
?>

<h2>Connexion</h2>

<form action="<?php echo BASE_URL; ?>login" method="POST">
    <label for="email">Email :</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars(isset($_POST['email']) ? $_POST['email'] : ''); ?>" required>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required>

    <input type="submit" value="Se connecter">
</form>

<p>Pas encore de compte ? <a href="<?php echo BASE_URL; ?>register">Inscrivez-vous ici</a>.</p>