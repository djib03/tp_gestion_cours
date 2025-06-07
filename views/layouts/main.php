<?php require '/wamp64/www/tp_gestion_cours/includes/header.php'; ?>
<h2>Bienvenue, <?php echo htmlspecialchars($role); ?></h2>
<?php
if (isset($data)) {
    extract($data);
}
echo $content;
?>
<?php require '/wamp64/www/tp_gestion_cours/includes/footer.php'; ?>