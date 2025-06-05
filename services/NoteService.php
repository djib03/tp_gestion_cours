<?php
// services/NoteService.php
require_once __DIR__ . '/../database/dbConnect.php'; // Inclure la connexion DB

class Note {
    private $id;
    private $valeur;
    private $etudiantId;
    private $matiereId;

    public function __construct($id, $valeur, $etudiantId, $matiereId) {
        $this->id = $id;
        $this->valeur = $valeur;
        $this->etudiantId = $etudiantId;
        $this->matiereId = $matiereId;
    }

    public function getId() { return $this->id; }
    public function getValeur() { return $this->valeur; }
    public function getEtudiantId() { return $this->etudiantId; }
    public function getMatiereId() { return $this->matiereId; }

    public function setValeur($valeur) { $this->valeur = $valeur; }
}

class NoteService {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function addNote($data) {
        // Utiliser INSERT ... ON DUPLICATE KEY UPDATE pour gérer l'unicité (etudiant_id, matiere_id)
        $stmt = $this->pdo->prepare("INSERT INTO Note (valeur, etudiant_id, matiere_id) VALUES (:valeur, :etudiant_id, :matiere_id)
                                    ON DUPLICATE KEY UPDATE valeur = :valeur");
        return $stmt->execute([
            'valeur' => $data['valeur'],
            'etudiant_id' => $data['etudiant_id'],
            'matiere_id' => $data['matiere_id']
        ]);
    }

    public function getNotesByEtudiantId($etudiantId) {
        $stmt = $this->pdo->prepare("SELECT * FROM Note WHERE etudiant_id = :etudiant_id");
        $stmt->execute(['etudiant_id' => $etudiantId]);
        $notes = [];
        while ($row = $stmt->fetch()) {
            $notes[] = new Note($row['id'], $row['valeur'], $row['etudiant_id'], $row['matiere_id']);
        }
        return $notes;
    }

    public function getNotesByMatiereId($matiereId) {
        $stmt = $this->pdo->prepare("SELECT * FROM Note WHERE matiere_id = :matiere_id");
        $stmt->execute(['matiere_id' => $matiereId]);
        $notes = [];
        while ($row = $stmt->fetch()) {
            $notes[] = new Note($row['id'], $row['valeur'], $row['etudiant_id'], $row['matiere_id']);
        }
        return $notes;
    }

    // Vous pouvez ajouter d'autres méthodes comme updateNote, deleteNote, getNoteById, etc.
}