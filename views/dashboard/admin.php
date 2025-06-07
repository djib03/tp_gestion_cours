<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Gestion des Cours</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard-container">
        <h3 class="dashboard-title">Administration - Gestion des Cours</h3>

        <!-- Messages de notification -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Section Gestion des Matières -->
        <div class="section-matieres">
            <h3>Gestion des Matières</h3>

            <!-- Formulaire pour ajouter une nouvelle matière -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nom_matiere">Nom de la matière :</label>
                    <input type="text" id="nom_matiere" name="nom_matiere" required>
                </div>
                <div class="form-group">
                    <label for="description">Description :</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <button type="submit" name="ajouter_matiere" class="btn-submit">Ajouter la matière</button>
            </form>

            <!-- Liste des Matières existantes -->
            <h4 class="section-title">Liste des Matières</h4>
            <?php if (isset($matieres) && count($matieres) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Enseignant</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($matieres as $matiere): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($matiere['nom']); ?></td>
                            <td><?php echo htmlspecialchars($matiere['description']); ?></td>
                            <td>
                                <div class="enseignant-info">
                                    <?php 
                                    echo $matiere['nom_enseignant'] ? 
                                        htmlspecialchars($matiere['nom_enseignant'] . ' ' . $matiere['prenom_enseignant']) : 
                                        '<span class="no-data">Non attribué</span>';
                                    ?>
                                </div>
                                <form method="POST" class="attribution-form">
                                    <input type="hidden" name="matiere_id" value="<?php echo $matiere['id']; ?>">
                                    <select name="enseignant_id">
                                        <option value="">Sélectionner un enseignant</option>
                                        <?php foreach ($enseignants as $enseignant): ?>
                                            <option value="<?php echo $enseignant['id']; ?>">
                                                <?php echo htmlspecialchars($enseignant['nom'] . ' ' . $enseignant['prenom']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" name="attribuer_enseignant">Attribuer</button>
                                </form>
                            </td>
                            <td>
                                <form method="POST" class="delete-form">
                                    <input type="hidden" name="matiere_id" value="<?php echo $matiere['id']; ?>">
                                    <button type="submit" name="supprimer_matiere" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette matière ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-data">Aucune matière enregistrée.</p>
            <?php endif; ?>
        </div>

        <!-- Section Gestion des Étudiants -->
        <div class="section-notes">
            <h3>Gestion des Étudiants</h3>

            <!-- Formulaire pour ajouter un étudiant -->
            <h4 class="section-title">Ajouter un Étudiant</h4>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nom_etudiant">Nom :</label>
                    <input type="text" id="nom_etudiant" name="nom_etudiant" required>
                </div>
                <div class="form-group">
                    <label for="prenom_etudiant">Prénom :</label>
                    <input type="text" id="prenom_etudiant" name="prenom_etudiant" required>
                </div>
                <div class="form-group">
                    <label for="tel_etudiant">Téléphone :</label>
                    <input type="text" id="tel_etudiant" name="tel_etudiant" required>
                </div>
                <div class="form-group">
                    <label for="email_etudiant">Email :</label>
                    <input type="email" id="email_etudiant" name="email_etudiant" required>
                </div>
                <div class="form-group">
                    <label for="mot_de_passe_etudiant">Mot de passe :</label>
                    <input type="password" id="mot_de_passe_etudiant" name="mot_de_passe_etudiant" required>
                </div>
                <div class="form-group">
                    <label for="annee_entree">Année d'entrée :</label>
                    <input type="date" id="annee_entree" name="annee_entree" required>
                </div>
                <button type="submit" name="ajouter_etudiant" class="btn-submit">Ajouter l'étudiant</button>
            </form>

            <!-- Liste des Étudiants -->
            <h4 class="section-title">Liste des Étudiants</h4>
            <?php if (isset($etudiants) && count($etudiants) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Année d'entrée</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($etudiants as $etudiant): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($etudiant['nom']); ?></td>
                            <td><?php echo htmlspecialchars($etudiant['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($etudiant['email']); ?></td>
                            <td><?php echo htmlspecialchars($etudiant['telephone']); ?></td>
                            <td><?php echo htmlspecialchars($etudiant['annee_entree']); ?></td>
                            <td>
                                <form method="POST" class="delete-form">
                                    <input type="hidden" name="etudiant_id" value="<?php echo $etudiant['id']; ?>">
                                    <button type="submit" name="supprimer_etudiant" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-data">Aucun étudiant enregistré.</p>
            <?php endif; ?>
        </div>

        <!-- Section Gestion des Enseignants -->
        <div class="section-notes">
            <h3>Gestion des Enseignants</h3>

            <!-- Formulaire pour ajouter un enseignant -->
            <h4 class="section-title">Ajouter un Enseignant</h4>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nom_enseignant">Nom :</label>
                    <input type="text" id="nom_enseignant" name="nom_enseignant" required>
                </div>
                <div class="form-group">
                    <label for="prenom_enseignant">Prénom :</label>
                    <input type="text" id="prenom_enseignant" name="prenom_enseignant" required>
                </div>
                <div class="form-group">
                    <label for="tel_enseignant">Téléphone :</label>
                    <input type="text" id="tel_enseignant" name="tel_enseignant" required>
                </div>
                <div class="form-group">
                    <label for="email_enseignant">Email :</label>
                    <input type="email" id="email_enseignant" name="email_enseignant" required>
                </div>
                <div class="form-group">
                    <label for="mot_de_passe_enseignant">Mot de passe :</label>
                    <input type="password" id="mot_de_passe_enseignant" name="mot_de_passe_enseignant" required>
                </div>
                <div class="form-group">
                    <label for="date_prise_fonction">Date de prise de fonction :</label>
                    <input type="date" id="date_prise_fonction" name="date_prise_fonction" required>
                </div>
                <div class="form-group">
                    <label for="departement">Département :</label>
                    <input type="text" id="departement" name="departement" required>
                </div>
                <div class="form-group">
                    <label for="indice">Indice :</label>
                    <input type="number" step="0.1" id="indice" name="indice" required>
                </div>
                <button type="submit" name="ajouter_enseignant" class="btn-submit">Ajouter l'enseignant</button>
            </form>

            <!-- Liste des Enseignants -->
            <h4 class="section-title">Liste des Enseignants</h4>
            <?php if (isset($enseignants) && count($enseignants) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Département</th>
                            <th>Date de prise de fonction</th>
                            <th>Indice</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($enseignants as $enseignant): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($enseignant['nom']); ?></td>
                            <td><?php echo htmlspecialchars($enseignant['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($enseignant['email']); ?></td>
                            <td><?php echo htmlspecialchars($enseignant['telephone']); ?></td>
                            <td><?php echo htmlspecialchars($enseignant['departement']); ?></td>
                            <td><?php echo htmlspecialchars($enseignant['date_prise_fonction']); ?></td>
                            <td><?php echo htmlspecialchars($enseignant['indice']); ?></td>
                            <td>
                                <form method="POST" class="delete-form">
                                    <input type="hidden" name="enseignant_id" value="<?php echo $enseignant['id']; ?>">
                                    <button type="submit" name="supprimer_enseignant" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet enseignant ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-data">Aucun enseignant enregistré.</p>
            <?php endif; ?>
        </div>

        <a href="/tp_gestion_cours/logout.php" class="btn-logout">Déconnexion</a>
    </div>
</body>
</html>