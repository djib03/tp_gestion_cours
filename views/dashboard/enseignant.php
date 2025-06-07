<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Enseignant - Gestion des Cours</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard-container">
        <h3 class="dashboard-title">Espace Enseignant</h3>

        <!-- Messages de notification -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Section Liste des matières -->
        <div class="section-matieres">
            <h3>Vos Matières</h3>
            <h4 class="section-title">Liste des Matières enseignées</h4>
            <?php if (isset($data['matieres']) && !empty($data['matieres'])): ?>
                <ul class="matieres-list">
                <?php foreach ($data['matieres'] as $matiere): ?>
                    <li class="matiere-item">
                        <strong><?php echo htmlspecialchars($matiere['nom']); ?></strong>
                        <p><?php echo htmlspecialchars($matiere['description']); ?></p>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="no-data">Aucune matière enseignée.</p>
            <?php endif; ?>
        </div>

        <!-- Section Saisie des notes -->
        <div class="section-notes">
            <h3>Saisie des Notes</h3>
            <h4 class="section-title">Ajouter une nouvelle note</h4>
            <form method="POST" action="" class="notes-form">
                <div class="form-group">
                    <label for="matiere_id">Sélectionner une matière :</label>
                    <select name="matiere_id" id="matiere_id" required>
                        <option value="">Choisir une matière</option>
                        <?php if (isset($data['matieres']) && !empty($data['matieres'])): ?>
                            <?php foreach ($data['matieres'] as $matiere): ?>
                                <option value="<?php echo $matiere['id']; ?>">
                                    <?php echo htmlspecialchars($matiere['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="etudiant_id">Sélectionner un étudiant :</label>
                    <select name="etudiant_id" id="etudiant_id" required>
                        <option value="">Choisir un étudiant</option>
                        <?php if (isset($data['etudiants']) && !empty($data['etudiants'])): ?>
                            <?php foreach ($data['etudiants'] as $etudiant): ?>
                                <option value="<?php echo $etudiant['id']; ?>">
                                    <?php echo htmlspecialchars($etudiant['nom'] . ' ' . $etudiant['prenom']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="note">Note :</label>
                    <input type="number" id="note" name="valeur" 
                           step="0.1" min="0" max="20" required 
                           placeholder="Note sur 20">
                </div>

                <button type="submit" name="saisir_note" class="btn-submit">Saisir la Note</button>
            </form>
        </div>

        <!-- Section Consultation des notes -->
        <div class="section-notes">
            <h3>Consultation des Notes</h3>
            <h4 class="section-title">Notes saisies</h4>
            <?php if (isset($data['notes']) && !empty($data['notes'])): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Étudiant</th>
                            <th>Matière</th>
                            <th>Note</th>
                            <th>Date de saisie</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data['notes'] as $note): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($note['nom_etudiant'] . ' ' . $note['prenom_etudiant']); ?></td>
                            <td><?php echo htmlspecialchars($note['nom_matiere']); ?></td>
                            <td><span class="note-value"><?php echo htmlspecialchars($note['valeur']); ?>/20</span></td>
                            <td><?php echo htmlspecialchars($note['date_saisie']); ?></td>
                            <td>
                                <form method="POST" class="delete-form">
                                    <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">
                                    <button type="submit" name="modifier_note" class="btn-edit">Modifier</button>
                                    <button type="submit" name="supprimer_note" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette note ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-data">Aucune note saisie pour le moment.</p>
            <?php endif; ?>
        </div>

        <!-- Section Statistiques -->
        <div class="section-matieres">
            <h3>Statistiques</h3>
            <h4 class="section-title">Résumé de vos cours</h4>
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-number"><?php echo isset($data['matieres']) ? count($data['matieres']) : 0; ?></div>
                    <div class="stat-label">Matières enseignées</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo isset($data['etudiants']) ? count($data['etudiants']) : 0; ?></div>
                    <div class="stat-label">Étudiants</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo isset($data['notes']) ? count($data['notes']) : 0; ?></div>
                    <div class="stat-label">Notes saisies</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">
                        <?php 
                        if (isset($data['notes']) && !empty($data['notes'])) {
                            $total = 0;
                            foreach ($data['notes'] as $note) {
                                $total += $note['valeur'];
                            }
                            echo number_format($total / count($data['notes']), 1);
                        } else {
                            echo '0';
                        }
                        ?>
                    </div>
                    <div class="stat-label">Moyenne générale</div>
                </div>
            </div>
        </div>

        <a href="/tp_gestion_cours/logout.php" class="btn-logout">Déconnexion</a>
    </div>
</body>
</html>