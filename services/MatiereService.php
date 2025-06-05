<?php
// services/MatiereService.php
require_once __DIR__ . '/../database/dbConnect.php'; // Inclure la connexion DB

class Matiere {
    private $id;
    private $nom;
    private $description;
    private $enseignantId; // ID de l'enseignant qui dispense la matière

    public function __construct($id, $nom, $description, $enseignantId) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->enseignantId = $enseignantId;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getDescription() { return $this->description; }
    public function getEnseignantId() { return $this->enseignantId; }

    // Setters
    public function setNom($nom) { $this->nom = $nom; }
    public function setDescription($description) { $this->description = $description; }
    public function setEnseignantId($enseignantId) { $this->enseignantId = $enseignantId; }

    // Méthode pour le calcul de la moyenne de la matière (si c'est dans la classe Matiere)
    // Cette méthode a besoin d'accéder aux notes, donc elle doit être appelée via un service
    public function calculerMoyenneMatiere(NoteService $noteService) {
        $notes = $noteService->getNotesByMatiereId($this->id);
        if (empty($notes)) {
            return 0.0;
        }
        $sommeNotes = 0;
        foreach ($notes as $note) {
            $sommeNotes += $note->getValeur();
        }
        return $sommeNotes / count($notes);
    }
}

// Service pour la gestion des matières
class MatiereService {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function addMatiere($data) {
        $stmt = $this->pdo->prepare("INSERT INTO Matiere (nom, description, enseignant_id) VALUES (:nom, :description, :enseignant_id)");
        return $stmt->execute([
            'nom' => $data['nom'],
            'description' => $data['description'],
            'enseignant_id' => $data['enseignant_id']
        ]);
    }

    public function updateMatiere($data) {
        $stmt = $this->pdo->prepare("UPDATE Matiere SET nom = :nom, description = :description, enseignant_id = :enseignant_id WHERE id = :id");
        return $stmt->execute([
            'id' => $data['id'],
            'nom' => $data['nom'],
            'description' => $data['description'],
            'enseignant_id' => $data['enseignant_id']
        ]);
    }

    public function deleteMatiere($id) {
        $stmt = $this->pdo->prepare("DELETE FROM Matiere WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getMatiereById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM Matiere WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        if ($data) {
            return new Matiere($data['id'], $data['nom'], $data['description'], $data['enseignant_id']);
        }
        return null;
    }

    public function getAllMatieres() {
        $stmt = $this->pdo->query("SELECT * FROM Matiere");
        $matieres = [];
        while ($row = $stmt->fetch()) {
            $matieres[] = new Matiere($row['id'], $row['nom'], $row['description'], $row['enseignant_id']);
        }
        return $matieres;
    }

    public function getMatieresByEnseignantId($enseignantId) {
        $stmt = $this->pdo->prepare("SELECT * FROM Matiere WHERE enseignant_id = :enseignant_id");
        $stmt->execute(['enseignant_id' => $enseignantId]);
        $matieres = [];
        while ($row = $stmt->fetch()) {
            $matieres[] = new Matiere($row['id'], $row['nom'], $row['description'], $row['enseignant_id']);
        }
        return $matieres;
    }

    public function searchMatiere($nomMatiere) {
        $stmt = $this->pdo->prepare("SELECT * FROM Matiere WHERE nom LIKE :nom");
        $stmt->execute(['nom' => '%' . $nomMatiere . '%']);
        $matieres = [];
        while ($row = $stmt->fetch()) {
            $matieres[] = new Matiere($row['id'], $row['nom'], $row['description'], $row['enseignant_id']);
        }
        return $matieres;
    }
}