<?php
echo "<h3>Vos Matières</h3>";
?>

<h4>Liste des Matières enseignée</h4>
<?php if (isset($data['matieres']) && count($data['matieres']) > 0): ?>
    <ul>
    <?php foreach ($data['matieres'] as $matiere): ?>
        <li>
            <?php echo htmlspecialchars($matiere['nom']) . " - " . htmlspecialchars($matiere['description']); ?>
            <!-- Formulaire pour saisir une note -->
            <form method="POST" action="" style="display:inline;">
                <select name="etudiant_id" required>
                    <option value="">Sélectionner un étudiant</option>
                    <?php 
                    $stmt = $pdo->query("SELECT u.id, u.nom, u.prenom FROM Utilisateur u JOIN Etudiant e ON u.id = e.id");
                    $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($etudiants as $etudiant): 
                    ?>
                        <option value="<?php echo $etudiant['id']; ?>">
                            <?php echo htmlspecialchars($etudiant['nom'] . ' ' . $etudiant['prenom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="matiere_id" value="<?php echo htmlspecialchars($matiere['id']); ?>">
                <input type="number" name="valeur" step="0.1" min="0" max="20" required placeholder="Note /20">
                <button type="submit" name="saisir_note">Saisir Note</button>
            </form>
        </li>
    <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucune matière enseignée.</p>
<?php endif; ?>

<!-- Affichage des messages -->
<?php if (isset($_SESSION['message'])): ?>
    <div class="success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<a href="/tp_gestion_cours/logout.php">Déconnexion</a>