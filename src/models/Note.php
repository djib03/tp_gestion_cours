<?php
class Note {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function saisirNote($etudiant_id, $matiere_id, $valeur) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO Note (etudiant_id, matiere_id, valeur) 
                VALUES (:etudiant_id, :matiere_id, :valeur)
                ON DUPLICATE KEY UPDATE valeur = :valeur
            ");
            return $stmt->execute([
                ':etudiant_id' => $etudiant_id,
                ':matiere_id' => $matiere_id,
                ':valeur' => $valeur
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getAllNotesByEtudiant($etudiant_id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT n.*, m.nom as nom_matiere 
                FROM Note n 
                INNER JOIN Matiere m ON n.matiere_id = m.id 
                WHERE n.etudiant_id = :etudiant_id
            ");
            $stmt->execute([':etudiant_id' => $etudiant_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getNotesByMatiere($etudiant_id, $search_matiere) {
        try {
            $query = "
                SELECT n.*, m.nom as nom_matiere 
                FROM Note n 
                INNER JOIN Matiere m ON n.matiere_id = m.id 
                WHERE n.etudiant_id = :etudiant_id 
                AND m.nom LIKE :search
            ";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':etudiant_id' => $etudiant_id,
                ':search' => '%' . $search_matiere . '%'
            ]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getMoyenneGenerale($etudiant_id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT AVG(valeur) as moyenne 
                FROM Note 
                WHERE etudiant_id = :etudiant_id
            ");
            $stmt->execute([':etudiant_id' => $etudiant_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['moyenne'] ?? 0;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return 0;
        }
    }

    public function getMoyennesParMatiere($etudiant_id) {
        $stmt = $this->pdo->prepare("
            SELECT m.nom, AVG(n.valeur) as moyenne
            FROM Note n
            JOIN Matiere m ON n.matiere_id = m.id
            WHERE n.etudiant_id = :etudiant_id
            GROUP BY m.id, m.nom
        ");
        $stmt->execute([':etudiant_id' => $etudiant_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function suggestMatieres($search_term) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT nom FROM Matiere 
                WHERE nom LIKE :search 
                LIMIT 5
            ");
            $stmt->execute([':search' => '%' . $search_term . '%']);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
}
?>