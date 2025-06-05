<?php
// index.php (à la racine de tp_gestion_cours/)

session_start();

// Inclure la connexion à la base de données
require_once __DIR__ . '/database/dbConnect.php';

// Inclure les services (Les classes sont définies ici, les services les utilisent)
require_once __DIR__ . '/services/Utilisateur.php';
require_once __DIR__ . '/services/MatiereService.php';
require_once __DIR__ . '/services/NoteService.php';
// require_once __DIR__ . '/services/AuthService.php'; // À créer si vous avez un service d'authentification dédié

// Initialiser les services
$matiereService = new MatiereService($pdo);
$noteService = new NoteService($pdo);
// $authService = new AuthService($pdo);

$currentUser = null; // L'utilisateur actuellement connecté

// --- Logique de connexion / déconnexion ---
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'login') {
        // Logique de connexion simplifiée pour l'exemple
        // Dans un vrai projet, vous recevriez des données POST (email, mot de passe)
        // et les vérifieriez avec AuthService.
        $stmt = $pdo->prepare("SELECT u.id, u.nom, u.prenom, u.tel, u.email, u.mot_de_passe, e.annee_entree
                               FROM Utilisateur u JOIN Etudiant e ON u.id = e.id
                               WHERE u.id = :id");
        $stmt->execute(['id' => 1]); // Connexion forcée à l'étudiant ID 1 pour l'exemple
        $etudiantData = $stmt->fetch();

        if ($etudiantData) {
            $currentUser = new Etudiant(
                $etudiantData['id'],
                $etudiantData['nom'],
                $etudiantData['prenom'],
                $etudiantData['tel'],
                $etudiantData['email'],
                $etudiantData['mot_de_passe'],
                $etudiantData['annee_entree']
            );
            $_SESSION['user_id'] = $currentUser->getId();
            $_SESSION['user_role'] = 'etudiant';
            // Récupérer les notes pour l'objet Etudiant
            $notes = $noteService->getNotesByEtudiantId($currentUser->getId());
            foreach($notes as $note) {
                $currentUser->addNote($note);
            }
            header('Location: index.php?page=dashboard');
            exit();
        }
    } elseif ($_GET['action'] == 'logout') {
        session_destroy();
        header('Location: index.php?page=home');
        exit();
    }
}

// --- Récupérer l'utilisateur depuis la session s'il est déjà connecté ---
if (isset($_SESSION['user_id'])) {
    // Dans un vrai projet, vous récupéreriez le rôle de l'utilisateur de la session
    // et utiliseriez le bon service (UtilisateurService) pour créer l'objet Utilisateur
    // et ses sous-types (Etudiant, Enseignant, Administrateur).
    // Pour l'exemple, on continue avec l'étudiant ID 1
    $stmt = $pdo->prepare("SELECT u.id, u.nom, u.prenom, u.tel, u.email, u.mot_de_passe, e.annee_entree
                           FROM Utilisateur u JOIN Etudiant e ON u.id = e.id
                           WHERE u.id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $etudiantData = $stmt->fetch();
    if ($etudiantData) {
        $currentUser = new Etudiant(
            $etudiantData['id'],
            $etudiantData['nom'],
            $etudiantData['prenom'],
            $etudiantData['tel'],
            $etudiantData['email'],
            $etudiantData['mot_de_passe'],
            $etudiantData['annee_entree']
        );
        // Charger les notes
        $notes = $noteService->getNotesByEtudiantId($currentUser->getId());
        foreach($notes as $note) {
            $currentUser->addNote($note);
        }
    }
}


// --- Routage et chargement de la vue ---
$page = $_GET['page'] ?? 'home'; // Page par défaut

// Vérifier l'authentification pour les pages nécessitant une connexion
if (!$currentUser && $page != 'login' && $page != 'home') {
    header('Location: index.php?page=login');
    exit();
}

// Définir le chemin de la vue à inclure
$viewFile = '';
switch ($page) {
    case 'home':
        $viewFile = 'home.php';
        break;
    case 'login':
        $viewFile = 'login.php';
        break;
    case 'dashboard':
        $viewFile = 'dashboard.php';
        break;
    // Ajoutez d'autres cas pour les autres pages (gestion matières, gestion utilisateurs, etc.)
    default:
        $viewFile = '404.php'; // Créez une page 404.php si nécessaire
        break;
}

// Inclure le layout principal qui inclura la vue spécifique
require_once __DIR__ . '/views/layout.php';

?>