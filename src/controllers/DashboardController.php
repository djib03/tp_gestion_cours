<?php
require_once '/wamp64/www/tp_gestion_cours/src/models/Matiere.php';
require_once '/wamp64/www/tp_gestion_cours/src/models/Utilisateur.php';

class DashboardController {
    private $pdo;
    private $matiereModel;
    private $utilisateurModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->matiereModel = new Matiere($pdo);
        $this->utilisateurModel = new Utilisateur($pdo);
    }

    public function index() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit();
        }
        $user_id = $_SESSION['user_id'];
        $role = $_SESSION['role'];

        ob_start();
        if ($role == 'etudiant') {
            $stmt = $this->pdo->prepare("SELECT m.nom, n.valeur FROM Note n JOIN Matiere m ON n.matiere_id = m.id WHERE n.etudiant_id = ?");
            $stmt->execute([$user_id]);
            $notes = $stmt->fetchAll();
            require 'views/dashboard/etudiant.php';
        } elseif ($role == 'enseignant') {
            $stmt = $this->pdo->prepare("SELECT m.nom, m.description FROM Enseigne e JOIN Matiere m ON e.matiere_id = m.id WHERE e.enseignant_id = ?");
            $stmt->execute([$user_id]);
            $matieres = $stmt->fetchAll();
            require 'views/dashboard/enseignant.php';
        } elseif ($role == 'admin') {
            // Récupérer les matières
            $matieres = $this->matiereModel->getAllMatieres();
            
            // Récupérer la liste des étudiants
            $etudiants = $this->getEtudiants();
            
            // Récupérer la liste des enseignants
            $enseignants = $this->getEnseignants();

            // Gestion des formulaires POST
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (isset($_POST['ajouter_matiere'])) {
                    $nom_matiere = $_POST['nom_matiere'];
                    $description = $_POST['description'];
                    $this->matiereModel->addMatiere($nom_matiere, $description);
                    header("Location: dashboard.php");
                    exit();
                }
                elseif (isset($_POST['supprimer_matiere'])) {
                    $matiere_id = $_POST['matiere_id'];
                    $this->matiereModel->deleteMatiere($matiere_id);
                    header("Location: dashboard.php");
                    exit();
                }
                elseif (isset($_POST['ajouter_etudiant'])) {
                    $nom = $_POST['nom_etudiant'];
                    $prenom = $_POST['prenom_etudiant'];
                    $tel = $_POST['tel_etudiant'];
                    $email = $_POST['email_etudiant'];
                    $mot_de_passe = $_POST['mot_de_passe_etudiant'];
                    $anneeEntree = $_POST['annee_entree'];
                    $this->utilisateurModel->addEtudiant($nom, $prenom, $tel, $email, $mot_de_passe, $anneeEntree);
                    header("Location: dashboard.php");
                    exit();
                }
                elseif (isset($_POST['supprimer_etudiant'])) {
                    $etudiant_id = $_POST['etudiant_id'];
                    $this->supprimerEtudiant($etudiant_id);
                    header("Location: dashboard.php");
                    exit();
                }
                elseif (isset($_POST['ajouter_enseignant'])) {
                    $nom = $_POST['nom_enseignant'];
                    $prenom = $_POST['prenom_enseignant'];
                    $tel = $_POST['tel_enseignant'];
                    $email = $_POST['email_enseignant'];
                    $mot_de_passe = $_POST['mot_de_passe_enseignant'];
                    $datePriseFonction = $_POST['date_prise_fonction'];
                    $departement = $_POST['departement'];
                    $indice = $_POST['indice'];
                    $this->utilisateurModel->addEnseignant($nom, $prenom, $tel, $email, $mot_de_passe, $datePriseFonction, $departement, $indice);
                    header("Location: dashboard.php");
                    exit();
                }
                elseif (isset($_POST['supprimer_enseignant'])) {
                    $enseignant_id = $_POST['enseignant_id'];
                    $this->supprimerEnseignant($enseignant_id);
                    header("Location: dashboard.php");
                    exit();
                }
                elseif (isset($_POST['attribuer_enseignant'])) {
                    $matiere_id = $_POST['matiere_id'];
                    $enseignant_id = $_POST['enseignant_id'];
                    $this->matiereModel->attribuerEnseignant($matiere_id, $enseignant_id);
                    header("Location: dashboard.php");
                    exit();
                }
            }

            require 'views/dashboard/admin.php';
        }
        $content = ob_get_clean();
        require 'views/layouts/main.php';
    }

    // Ajouter ces nouvelles méthodes à la classe
    private function getEtudiants() {
        $stmt = $this->pdo->prepare("
            SELECT u.id, u.nom, u.prenom, u.email, u.tel as telephone, e.annee_entree 
            FROM Utilisateur u 
            JOIN Etudiant e ON u.id = e.id
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getEnseignants() {
        $stmt = $this->pdo->prepare("
            SELECT u.id, u.nom, u.prenom, u.email, u.tel as telephone, 
                   e.departement, e.date_prise_fonction, e.indice 
            FROM Utilisateur u 
            JOIN Enseignant e ON u.id = e.id
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function supprimerEtudiant($id) {
        $stmt = $this->pdo->prepare("DELETE FROM Etudiant WHERE id = ?");
        $stmt->execute([$id]);
        $stmt = $this->pdo->prepare("DELETE FROM Utilisateur WHERE id = ?");
        $stmt->execute([$id]);
    }

    private function supprimerEnseignant($id) {
        $stmt = $this->pdo->prepare("DELETE FROM Enseignant WHERE id = ?");
        $stmt->execute([$id]);
        $stmt = $this->pdo->prepare("DELETE FROM Utilisateur WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>