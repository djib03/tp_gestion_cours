<?php
require_once '/wamp64/www/tp_gestion_cours/src/models/Note.php';

$pdo = new PDO("mysql:host=localhost;dbname=escep_gestion_cours", "root", "");
$noteModel = new Note($pdo);
$term = $_GET['term'] ?? '';
$suggestions = $noteModel->suggestMatieres($term);
header('Content-Type: application/json');
echo json_encode($suggestions);
?>