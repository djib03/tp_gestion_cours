<?php
// views/dashboard.php
// Les variables $currentUser, $matiereService, $noteService sont disponibles
?>
<?php if ($currentUser): ?>
    <div class='row'>
        <div class='col-md-12'>
            <h2 class='mb-4 text-center'>Tableau de Bord de <?= htmlspecialchars($currentUser->getPrenom()) ?> <?= htmlspecialchars($currentUser->getNom()) ?></h2>
        </div>
    </div>

    <?php if ($currentUser instanceof Etudiant): ?>
        <div class='row'>
            <div class='col-lg-6 mb-4'>
                <div class='card shadow-sm h-100'>
                    <div class='card-header'><h3>Vos Notes</h3></div>
                    <div class='card-body'>
                        <?php
                        $notes = $currentUser->consulterNotes($noteService);
                        if (empty($notes)) {
                            echo "<div class='alert alert-info' role='alert'>Aucune note enregistrée pour le moment.</div>";
                        } else {
                            echo "<ul class='list-group list-group-flush'>";
                            foreach ($notes as $note) {
                                $matiere = $matiereService->getMatiereById($note->getMatiereId());
                                echo "<li class='list-group-item d-flex justify-content-between align-items-center'>" . htmlspecialchars($matiere ? $matiere->getNom() : 'Matière Inconnue') . " <span class='badge bg-primary rounded-pill'>" . htmlspecialchars($note->getValeur()) . "</span></li>";
                            }
                            echo "</ul>";
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class='col-lg-6 mb-4'>
                <div class='card shadow-sm h-100'>
                    <div class='card-header'><h3>Moyenne Générale</h3></div>
                    <div class='card-body text-center'>
                        <p class='display-4 text-success fw-bold'><?= number_format($currentUser->calculerMoyenne($noteService), 2) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class='row'>
            <div class='col-lg-12'>
                <div class='card shadow-sm'>
                    <div class='card-header'><h3>Matières non notées</h3></div>
                    <div class='card-body'>
                        <?php
                        $matieresNonNotees = $currentUser->getMatieresNonNotees($matiereService, $noteService);
                        if (empty($matieresNonNotees)) {
                            echo "<div class='alert alert-success' role='alert'>Vous avez été noté dans toutes les matières !</div>";
                        } else {
                            echo "<ul class='list-group list-group-flush'>";
                            foreach ($matieresNonNotees as $matiere) {
                                echo "<li class='list-group-item'>" . htmlspecialchars($matiere->getNom()) . "</li>";
                            }
                            echo "</ul>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php
    // TODO: Ajoutez ici la logique pour Enseignant et Administrateur
    /*
    if ($currentUser instanceof Enseignant) {
        echo "<div class='row'><div class='col-lg-12'>";
        echo "<div class='card shadow-sm'>";
        echo "<div class='card-header'><h3>Mes Matières Dispensées</h3></div>";
        echo "<div class='card-body'>";
        // Code pour afficher les matières de l'enseignant
        echo "</div></div></div></div>";
    }

    if ($currentUser instanceof Administrateur) {
        echo "<div class='row'><div class='col-lg-12'>";
        echo "<div class='card shadow-sm'>";
        echo "<div class='card-header'><h3>Panneau d'Administration</h3></div>";
        echo "<div class='card-body'>";
        // Code pour gérer les utilisateurs, matières, etc.
        echo "</div></div></div></div>";
    }
    */
    ?>
<?php else: ?>
    <div class='alert alert-danger text-center' role='alert'>Accès non autorisé. Veuillez vous connecter.</div>
<?php endif; ?>