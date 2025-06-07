<h3>Gestion des Matières</h3>

<!-- Formulaire pour ajouter une nouvelle matière -->
<form method="POST" action="">
    <label>Nom de la matière : <input type="text" name="nom_matiere" required></label><br>
    <label>Description : <textarea name="description" required></textarea></label><br>
    <button type="submit" name="ajouter_matiere">Ajouter la matière</button>
</form>

<!-- Liste des Matières existantes -->
<h4>Liste des Matières</h4>
<?php if (isset($matieres) && count($matieres) > 0): ?>
    <table border="1">
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
                    <?php 
                    echo $matiere['nom_enseignant'] ? 
                        htmlspecialchars($matiere['nom_enseignant'] . ' ' . $matiere['prenom_enseignant']) : 
                        'Non attribué';
                    ?>
                    <form method="POST" style="display:inline;">
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
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="matiere_id" value="<?php echo $matiere['id']; ?>">
                        <button type="submit" name="supprimer_matiere">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucune matière enregistrée.</p>
<?php endif; ?>

<!-- Formulaire pour ajouter un étudiant -->
<h3>Ajouter un Étudiant</h3>
<form method="POST" action="">
    <label>Nom : <input type="text" name="nom_etudiant" required></label><br>
    <label>Prénom : <input type="text" name="prenom_etudiant" required></label><br>
    <label>Téléphone : <input type="text" name="tel_etudiant" required></label><br>
    <label>Email : <input type="email" name="email_etudiant" required></label><br>
    <label>Mot de passe : <input type="password" name="mot_de_passe_etudiant" required></label><br>
    <label>Année d'entrée : <input type="date" name="annee_entree" required></label><br>
    <button type="submit" name="ajouter_etudiant">Ajouter l'étudiant</button>
</form>

<!-- Formulaire pour ajouter un enseignant -->
<h3>Ajouter un Enseignant</h3>
<form method="POST" action="">
    <label>Nom : <input type="text" name="nom_enseignant" required></label><br>
    <label>Prénom : <input type="text" name="prenom_enseignant" required></label><br>
    <label>Téléphone : <input type="text" name="tel_enseignant" required></label><br>
    <label>Email : <input type="email" name="email_enseignant" required></label><br>
    <label>Mot de passe : <input type="password" name="mot_de_passe_enseignant" required></label><br>
    <label>Date de prise de fonction : <input type="date" name="date_prise_fonction" required></label><br>
    <label>Département : <input type="text" name="departement" required></label><br>
    <label>Indice : <input type="number" step="0.1" name="indice" required></label><br>
    <button type="submit" name="ajouter_enseignant">Ajouter l'enseignant</button>
</form>

<!-- Liste des Étudiants -->
<h3>Liste des Étudiants</h3>
<?php if (isset($etudiants) && count($etudiants) > 0): ?>
    <table border="1">
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
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="etudiant_id" value="<?php echo $etudiant['id']; ?>">
                        <button type="submit" name="supprimer_etudiant">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucun étudiant enregistré.</p>
<?php endif; ?>

<!-- Liste des Enseignants -->
<h3>Liste des Enseignants</h3>
<?php if (isset($enseignants) && count($enseignants) > 0): ?>
    <table border="1">
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
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="enseignant_id" value="<?php echo $enseignant['id']; ?>">
                        <button type="submit" name="supprimer_enseignant">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucun enseignant enregistré.</p>
<?php endif; ?>

<a href="/tp_gestion_cours/logout.php">Déconnexion</a>