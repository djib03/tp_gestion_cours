<?php
class Utilisateur {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addEtudiant($nom, $prenom, $tel, $email, $mot_de_passe, $annee_entree) {
        $stmt = $this->pdo->prepare("INSERT INTO Utilisateur (id, nom, prenom, tel, email, mot_de_passe) VALUES (NULL, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $tel, $email, $mot_de_passe]);
        $user_id = $this->pdo->lastInsertId();
        $stmt = $this->pdo->prepare("INSERT INTO Etudiant (id, annee_entree) VALUES (?, ?)");
        return $stmt->execute([$user_id, $annee_entree]);
    }

    public function addEnseignant($nom, $prenom, $tel, $email, $mot_de_passe, $date_prise_fonction, $departement, $indice) {
        $stmt = $this->pdo->prepare("INSERT INTO Utilisateur (id, nom, prenom, tel, email, mot_de_passe) VALUES (NULL, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $tel, $email, $mot_de_passe]);
        $user_id = $this->pdo->lastInsertId();
        $stmt = $this->pdo->prepare("INSERT INTO Enseignant (id, date_prise_fonction, departement, indice) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user_id, $date_prise_fonction, $departement, $indice]);
    }
}
?>