<?php
?>
<div class="dashboard-container">
    <h3 class="dashboard-title">Vos Matières</h3>

    <!-- Section Liste des matières -->
    <div class="section-matieres">
        <h4 class="section-title">Liste des Matières enseignées</h4>
        <?php if (isset($data['matieres']) && !empty($data['matieres'])): ?>
            <ul class="matieres-list">
            <?php foreach ($data['matieres'] as $matiere): ?>
                <li class="matiere-item">
                    <?php echo htmlspecialchars($matiere['nom']) . " - " . htmlspecialchars($matiere['description']); ?>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="no-data">Aucune matière enseignée.</p>
        <?php endif; ?>
    </div>

    <!-- Section Saisie des notes -->
    <div class="section-notes">
        <h4 class="section-title">Saisie des Notes</h4>
        <form method="POST" action="" class="notes-form">
            <div class="form-group">
                <label for="matiere_id">Sélectionner une matière :</label>
                <select name="matiere_id" id="matiere_id" required>
                    <option value="">Choisir une matière</option>
                    <?php foreach ($data['matieres'] as $matiere): ?>
                        <option value="<?php echo $matiere['id']; ?>">
                            <?php echo htmlspecialchars($matiere['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="etudiant_id">Sélectionner un étudiant :</label>
                <select name="etudiant_id" id="etudiant_id" required>
                    <option value="">Choisir un étudiant</option>
                    <?php foreach ($data['etudiants'] as $etudiant): ?>
                        <option value="<?php echo $etudiant['id']; ?>">
                            <?php echo htmlspecialchars($etudiant['nom'] . ' ' . $etudiant['prenom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="note">Note :</label>
                <input type="number" id="note" name="valeur" 
                       step="0.1" min="0" max="20" required 
                       placeholder="Note /20">
            </div>

            <button type="submit" name="saisir_note" class="btn-submit">Saisir la Note</button>
        </form>
    </div>

    <!-- Messages de notification -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <a href="/tp_gestion_cours/logout.php" class="btn-logout">Déconnexion</a>
</div>