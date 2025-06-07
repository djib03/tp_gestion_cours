<?php require '/wamp64/www/tp_gestion_cours/includes/header.php'; ?>
<h2>Connexion</h2>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST">
    <label>Email : <input type="email" name="email" required></label><br>
    <label>Mot de passe : <input type="password" name="mot_de_passe" required></label><br>
    <button type="submit" name="login">Se connecter</button>
</form>
<?php require '/wamp64/www/tp_gestion_cours/includes/header.php'; ?>