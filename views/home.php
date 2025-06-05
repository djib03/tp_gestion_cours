<?php
// views/home.php
// Les variables comme $currentUser sont disponibles ici car incluses via layout.php
?>
<div class='p-5 mb-4 bg-light rounded-3 shadow-sm'>
    <div class='container-fluid py-5'>
        <h1 class='display-5 fw-bold'>Bienvenue à l'ESCEP !</h1>
        <p class='col-md-8 fs-4'>Votre plateforme de gestion des cours et des notes.</p>
        <?php if (!$currentUser): ?>
            <a class='btn btn-primary btn-lg' href='index.php?action=login'>Se connecter maintenant (Exemple Etudiant ID 1)</a>
        <?php else: ?>
             <a class='btn btn-success btn-lg' href='index.php?page=dashboard'>Accéder au tableau de bord</a>
        <?php endif; ?>
    </div>
</div>