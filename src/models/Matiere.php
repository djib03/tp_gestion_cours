<?php
class Matiere {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllMatieres() {
        $stmt = $this->pdo->query("
            SELECT m.*,
                   u.nom as nom_enseignant, 
                   u.prenom as prenom_enseignant 
            FROM Matiere m 
            LEFT JOIN Enseignant e ON m.enseignant_id = e.id
            LEFT JOIN Utilisateur u ON e.id = u.id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addMatiere($nom, $description, $enseignant_id = null) {
        $stmt = $this->pdo->prepare("INSERT INTO Matiere (nom, description, enseignant_id) VALUES (?, ?, ?)");
        return $stmt->execute([$nom, $description, $enseignant_id]);
    }

    public function deleteMatiere($id) {
        $stmt = $this->pdo->prepare("DELETE FROM Matiere WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function attribuerEnseignant($matiere_id, $enseignant_id) {
        $stmt = $this->pdo->prepare("UPDATE Matiere SET id_enseignant = ? WHERE id = ?");
        return $stmt->execute([$enseignant_id, $matiere_id]);
    }
}
?>