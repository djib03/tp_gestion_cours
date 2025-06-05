<?php
// services/Utilisateur.php

require_once __DIR__ . '/UtilisateurService.php';

// Classe de base Utilisateur
class Utilisateur {
    protected $id;
    protected $nom;
    protected $prenom;
    protected $tel;
    protected $email;
    protected $motDePasse;

    public function __construct($id, $nom, $prenom, $tel, $email, $motDePasse) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->tel = $tel;
        $this->email = $email;
        $this->motDePasse = $motDePasse;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getPrenom() { return $this->prenom; }
    public function getTel() { return $this->tel; }
    public function getEmail() { return $this->email; }
    public function getMotDePasse() { return $this->motDePasse; }

    // Setters (si nécessaire, pour les modifications)
    public function setNom($nom) { $this->nom = $nom; }
    // ... autres setters
}

// Classe Enseignant
class Enseignant extends Utilisateur {
    private $datePriseFonction;
    private $departement;
    private $indice;

    public function __construct($id, $nom, $prenom, $tel, $email, $motDePasse, $datePriseFonction, $departement, $indice) {
        parent::__construct($id, $nom, $prenom, $tel, $email, $motDePasse);
        $this->datePriseFonction = $datePriseFonction;
        $this->departement = $departement;
        $this->indice = $indice;
    }

    public function getDatePriseFonction() { return $this->datePriseFonction; }
    public function getDepartement() { return $this->departement; }
    public function getIndice() { return $this->indice; }

    // Méthodes pour Enseignant
    public function consulterMatieres(MatiereService $matiereService) {
        // Cette méthode appellera un service pour récupérer les matières
        return $matiereService->getMatieresByEnseignantId($this->id);
    }

    public function rechercherMatiere(MatiereService $matiereService, $nomMatiere) {
        return $matiereService->searchMatiere($nomMatiere);
    }
}

// Classe Etudiant
class Etudiant extends Utilisateur {
    private $anneeEntree;
    private $notes; // Pour stocker les objets Note associés

    public function __construct($id, $nom, $prenom, $tel, $email, $motDePasse, $anneeEntree) {
        parent::__construct($id, $nom, $prenom, $tel, $email, $motDePasse);
        $this->anneeEntree = $anneeEntree;
        $this->notes = []; // Initialiser la liste des notes
    }

    public function getAnneeEntree() { return $this->anneeEntree; }

    // Méthodes pour Etudiant
    public function addNote(Note $note) {
        $this->notes[] = $note;
    }

    public function getNotes() {
        return $this->notes; // Renvoie les objets Note, pas juste les valeurs
    }

    public function consulterNotes(NoteService $noteService) {
        // Appelle un service pour récupérer les notes de cet étudiant depuis la DB
        return $noteService->getNotesByEtudiantId($this->id);
    }

    public function calculerMoyenne(NoteService $noteService) {
        // Récupérer les notes à jour
        $notes = $noteService->getNotesByEtudiantId($this->id);

        if (empty($notes)) {
            return 0.0;
        }

        $sommeNotes = 0;
        foreach ($notes as $note) {
            $sommeNotes += $note->getValeur();
        }
        return $sommeNotes / count($notes);
    }

    public function rechercherMatiere(MatiereService $matiereService, $nomMatiere) {
        return $matiereService->searchMatiere($nomMatiere);
    }

    // Nouvelle méthode pour afficher les matières non notées
    public function getMatieresNonNotees(MatiereService $matiereService, NoteService $noteService) {
        $toutesLesMatieres = $matiereService->getAllMatieres();
        $notesDeLEtudiant = $noteService->getNotesByEtudiantId($this->id);

        $matieresNoteesIds = [];
        foreach ($notesDeLEtudiant as $note) {
            $matieresNoteesIds[] = $note->getMatiereId();
        }

        $matieresNonNotees = [];
        foreach ($toutesLesMatieres as $matiere) {
            if (!in_array($matiere->getId(), $matieresNoteesIds)) {
                $matieresNonNotees[] = $matiere;
            }
        }
        return $matieresNonNotees;
    }
}

// Classe Administrateur
class Administrateur extends Utilisateur {
    public function __construct($id, $nom, $prenom, $tel, $email, $motDePasse) {
        parent::__construct($id, $nom, $prenom, $tel, $email, $motDePasse);
    }

    // Méthodes pour Administrateur
    public function gererMatiere(MatiereService $matiereService, $operation, $data = null) {
        // Exemple d'opérations : 'add', 'update', 'delete', 'get'
        // Cette méthode délègue la gestion des matières au MatiereService
        switch ($operation) {
            case 'add': return $matiereService->addMatiere($data);
            case 'update': return $matiereService->updateMatiere($data);
            case 'delete': return $matiereService->deleteMatiere($data['id']);
            case 'get': return $matiereService->getMatiereById($data['id']);
            case 'getAll': return $matiereService->getAllMatieres();
            default: return false;
        }
    }

    public function gererEtudiant(UtilisateurService $userService, $operation, $data = null) {
        // Similaire à gererMatiere, délègue à un service d'utilisateurs
        switch ($operation) {
            case 'add': return $userService->addEtudiant($data);
            case 'update': return $userService->updateEtudiant($data);
            case 'delete': return $userService->deleteUtilisateur($data['id']);
            case 'get': return $userService->getEtudiantById($data['id']);
            case 'getAll': return $userService->getAllEtudiants();
            default: return false;
        }
    }

    public function gererEnseignant(UtilisateurService $userService, $operation, $data = null) {
        // Similaire, délègue à un service d'utilisateurs
        switch ($operation) {
            case 'add': return $userService->addEnseignant($data);
            case 'update': return $userService->updateEnseignant($data);
            case 'delete': return $userService->deleteUtilisateur($data['id']);
            case 'get': return $userService->getEnseignantById($data['id']);
            case 'getAll': return $userService->getAllEnseignants();
            default: return false;
        }
    }
}