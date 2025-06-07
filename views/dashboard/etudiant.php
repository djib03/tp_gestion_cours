<h3>Vos Notes</h3>

<!-- Formulaire de recherche -->
<form method="POST" action="">
    <label>Rechercher une matière : <input type="text" name="search_matiere" value="<?php echo htmlspecialchars($data['search_matiere'] ?? ''); ?>"></label>
    <button type="submit">Rechercher</button>
</form>

<!-- Liste des notes -->
<h4>Liste des Notes</h4>
<?php if (isset($data['notes']) && count($data['notes']) > 0): ?>
    <ul>
    <?php foreach ($data['notes'] as $note): ?>
        <li><?php echo htmlspecialchars($note['nom']) . ": " . htmlspecialchars($note['valeur']); ?></li>
    <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucune note enregistrée.</p>
<?php endif; ?>

<!-- Moyenne générale -->
<h4>Moyenne Générale</h4>
<p><?php echo isset($data['moyenne_generale']) ? number_format($data['moyenne_generale'], 2) : '0.00'; ?></p>

<!-- Moyennes par matière -->
<h4>Moyennes par Matière</h4>
<?php if (!empty($data['moyennes_par_matiere'])): ?>
    <ul>
    <?php foreach ($data['moyennes_par_matiere'] as $matiere => $moyenne): ?>
        <li><?php echo htmlspecialchars($matiere) . ": " . number_format($moyenne, 2); ?></li>
    <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucune moyenne calculée.</p>
<?php endif; ?>

<a href="/tp_gestion_cours/logout.php">Déconnexion</a>