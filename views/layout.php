<?php
// views/layout.php

// Les variables $currentUser, $matiereService, $noteService sont disponibles
// car elles sont définies dans index.php et sont dans le même scope.
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Cours ESCEP</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="/tp_gestion_cours/assets/css/style.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php?page=home">ESCEP Gestion des Cours</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if ($currentUser): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=dashboard">Tableau de Bord</a>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link text-white me-2">Bonjour, <?= htmlspecialchars($currentUser->getPrenom()) ?>!</span>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-light btn-sm" href="index.php?action=logout">Déconnexion</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=login">Se connecter</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-4">
        <?php
        // Inclure la vue spécifique basée sur la variable $viewFile définie dans index.php
        if (file_exists(__DIR__ . '/' . $viewFile)) {
            include __DIR__ . '/' . $viewFile;
        } else {
            // Fallback pour page non trouvée si 404.php n'est pas utilisé
            echo "<div class='alert alert-danger text-center' role='alert'>Page non trouvée.</div>";
        }
        ?>
    </main>

    <footer class="footer mt-auto py-3 bg-light border-top">
        <div class="container text-center">
            <span class="text-muted">&copy; <?= date('Y') ?> ESCEP. Tous droits réservés.</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>