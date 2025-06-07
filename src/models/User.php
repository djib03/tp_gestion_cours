<?php
       class User {
           private $pdo;
           public function __construct($pdo) {
               $this->pdo = $pdo;
           }
           public function authenticate($email, $password) {
               $stmt = $this->pdo->prepare("SELECT * FROM Utilisateur WHERE email = ? AND mot_de_passe = ?");
               $stmt->execute([$email, $password]);
               return $stmt->fetch();
           }
           public function getRole($user_id) {
               if ($this->pdo->query("SELECT id FROM Administrateur WHERE id = $user_id")->fetch()) return 'admin';
               if ($this->pdo->query("SELECT id FROM Enseignant WHERE id = $user_id")->fetch()) return 'enseignant';
               return 'etudiant';
           }
       }
       ?>