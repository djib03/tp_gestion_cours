<?php
class Matiere {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllMatieres() {
        $stmt = $this->pdo->prepare("SELECT * FROM Matiere");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addMatiere($nom, $description) {
        $stmt = $this->pdo->prepare("INSERT INTO Matiere (id, nom, description) VALUES (NULL, ?, ?)");
        return $stmt->execute([$nom, $description]);
    }

    public function deleteMatiere($id) {
        $stmt = $this->pdo->prepare("DELETE FROM Matiere WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>