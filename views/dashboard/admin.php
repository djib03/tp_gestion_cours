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
    <ul>
    <?php foreach ($matieres as $matiere): ?>
        <li>
            <?php echo $matiere['nom'] . " - " . $matiere['description']; ?>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="matiere_id" value="<?php echo $matiere['id']; ?>">
                <button type="submit" name="supprimer_matiere">Supprimer</button>
            </form>
        </li>
    <?php endforeach; ?>
    </ul>
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

<a href="/logout.php">Déconnexion</a>