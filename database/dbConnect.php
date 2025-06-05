<?php
// database/dbConnect.php

$host = 'localhost'; // Hôte de la base de données (généralement localhost pour WAMP)
$db   = 'escep_gestion_cours'; // Nom de votre base de données (à créer dans phpMyAdmin)
$user = 'root';              // Utilisateur par défaut de WAMP
$pass = '';                  // Mot de passe par défaut de WAMP (souvent vide)
$charset = 'utf8mb4';        // Jeu de caractères recommandé pour MySQL

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Afficher les erreurs PDO sous forme d'exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Récupérer les résultats sous forme de tableau associatif
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Désactiver l'émulation des requêtes préparées pour une meilleure sécurité
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // echo "Connexion à la base de données réussie !"; // Peut être commenté en production
} catch (\PDOException $e) {
    // En cas d'erreur de connexion, affiche un message et arrête le script
    error_log("Erreur de connexion à la base de données: " . $e->getMessage());
    die("Impossible de se connecter à la base de données.");
}
?>