<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '/wamp64/www/tp_gestion_cours/src/models/Matiere.php';
require_once '/wamp64/www/tp_gestion_cours/src/models/Utilisateur.php';
require_once '/wamp64/www/tp_gestion_cours/src/models/Note.php';

class DashboardController {
    private $pdo;
    private $matiereModel;
    private $utilisateurModel;
    private $noteModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->matiereModel = new Matiere($pdo);
        $this->utilisateurModel = new Utilisateur($pdo);
        $this->noteModel = new Note($pdo);
    }

    public function index() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /tp_gestion_cours/login.php');
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $role = $_SESSION['role'];
        $data = []; // Initialisation par défaut

        if ($role == 'etudiant') {
            $search_matiere = isset($_POST['search_matiere']) ? $_POST['search_matiere'] : '';
            
            error_log("Recherche des notes pour l'étudiant ID: $user_id");
            
            // Récupération des notes
            if ($search_matiere) {
                $notes = $this->noteModel->getNotesByMatiere($user_id, $search_matiere);
            } else {
                $notes = $this->noteModel->getAllNotesByEtudiant($user_id);
            }
            
            $moyenne_generale = $this->noteModel->getMoyenneGenerale($user_id);
            $moyennes_par_matiere = $this->noteModel->getMoyennesParMatiere($user_id);

            $data = [
                'notes' => $notes,
                'moyenne_generale' => $moyenne_generale,
                'moyennes_par_matiere' => $moyennes_par_matiere,
                'search_matiere' => $search_matiere
            ];

            error_log("Nombre de notes: " . count($notes));
            error_log("Data: " . print_r($data, true));

            // Capture du contenu de la vue étudiant
            ob_start();
            require 'views/dashboard/etudiant.php';
            $content = ob_get_clean();
            
        } elseif ($role == 'enseignant') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saisir_note'])) {
                $etudiant_id = $_POST['etudiant_id'] ?? null;
                $matiere_id = $_POST['matiere_id'] ?? null;
                $valeur = $_POST['valeur'] ?? null;

                if ($etudiant_id && $matiere_id && $valeur !== null) {
                    if ($this->noteModel->saisirNote($etudiant_id, $matiere_id, $valeur)) {
                        $_SESSION['message'] = "Note enregistrée avec succès";
                    } else {
                        $_SESSION['error'] = "Erreur lors de l'enregistrement de la note";
                    }
                } else {
                    $_SESSION['error'] = "Tous les champs sont obligatoires";
                }
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }

            $data['matieres'] = $this->getMatieresByEnseignant($user_id);
            $data['etudiants'] = $this->getEtudiants();
            
            // Capture du contenu de la vue enseignant
            ob_start();
            require 'views/dashboard/enseignant.php';
            $content = ob_get_clean();
            
        } elseif ($role == 'admin') {
            $matieres = $this->matiereModel->getAllMatieres();
            $etudiants = $this->getEtudiants();
            $enseignants = $this->getEnseignants();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (isset($_POST['ajouter_matiere'])) {
                    $nom_matiere = $_POST['nom_matiere'];
                    $description = $_POST['description'];
                    $this->matiereModel->addMatiere($nom_matiere, $description);
                    header("Location: dashboard.php");
                    exit();
                } elseif (isset($_POST['supprimer_matiere'])) {
                    $matiere_id = $_POST['matiere_id'];
                    $this->matiereModel->deleteMatiere($matiere_id);
                    header("Location: dashboard.php");
                    exit();
                } elseif (isset($_POST['ajouter_etudiant'])) {
                    $nom = $_POST['nom_etudiant'];
                    $prenom = $_POST['prenom_etudiant'];
                    $tel = $_POST['tel_etudiant'];
                    $email = $_POST['email_etudiant'];
                    $mot_de_passe = $_POST['mot_de_passe_etudiant'];
                    $anneeEntree = $_POST['annee_entree'];
                    $this->utilisateurModel->addEtudiant($nom, $prenom, $tel, $email, $mot_de_passe, $anneeEntree);
                    header("Location: dashboard.php");
                    exit();
                } elseif (isset($_POST['supprimer_etudiant'])) {
                    $etudiant_id = $_POST['etudiant_id'];
                    $this->supprimerEtudiant($etudiant_id);
                    header("Location: dashboard.php");
                    exit();
                } elseif (isset($_POST['ajouter_enseignant'])) {
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
                } elseif (isset($_POST['supprimer_enseignant'])) {
                    $enseignant_id = $_POST['enseignant_id'];
                    $this->supprimerEnseignant($enseignant_id);
                    header("Location: dashboard.php");
                    exit();
                } elseif (isset($_POST['attribuer_enseignant'])) {
                    $matiere_id = $_POST['matiere_id'];
                    $enseignant_id = $_POST['enseignant_id'];
                    $this->matiereModel->attribuerEnseignant($matiere_id, $enseignant_id);
                    header("Location: dashboard.php");
                    exit();
                }
            }

            $data = [
                'matieres' => $matieres,
                'etudiants' => $etudiants,
                'enseignants' => $enseignants
            ];
            
            // Capture du contenu de la vue admin
            ob_start();
            require 'views/dashboard/admin.php';
            $content = ob_get_clean();
        }

        // Affichage du layout principal avec le contenu
        require 'views/layouts/main.php';
    }

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

    private function getMatieresByEnseignant($enseignant_id) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM Matiere 
            WHERE enseignant_id = :enseignant_id
        ");
        $stmt->execute([':enseignant_id' => $enseignant_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>