<?php
class EtudiantModel {
    private $connexion;
    
    public function __construct() {
        try {
            $this->connexion = new PDO(
                'mysql:host=localhost;dbname=module6;charset=utf8',
                'root',
                '',
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            die('Erreur de connexion : ' . $e->getMessage());
        }
    }
    
    public function getTousEtudiants() {
        $requete = $this->connexion->query('SELECT * FROM etudiants');
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEtudiantParId($id) {
        $requete = $this->connexion->prepare('SELECT * FROM etudiants WHERE id = :id');
        $requete->execute(['id' => $id]);
        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    public function supprimerEtudiant($id) {
        $requete = $this->connexion->prepare('DELETE FROM etudiants WHERE id = :id');
        return $requete->execute(['id' => $id]);
    }

    public function creerEtudiant($prenom, $nom, $email, $cv, $dt_naissance, $isAdmin) {
        $requete = $this->connexion->prepare(
            'INSERT INTO etudiants (prenom, nom, email, cv, dt_naissance, isAdmin) VALUES (:prenom, :nom, :email, :cv, :dt_naissance, :isAdmin)'
        );
        return $requete->execute([
            'prenom' => $prenom,
            'nom' => $nom,
            'email' => $email,
            'cv' => $cv,
            'dt_naissance' => $dt_naissance,
            'isAdmin' => $isAdmin
        ]);
    }

    public function modifierEtudiant($id, $prenom, $nom, $email, $cv, $dt_naissance, $isAdmin) {
        $requete = $this->connexion->prepare(
            'UPDATE etudiants SET prenom = :prenom, nom = :nom, email = :email, cv = :cv, dt_naissance = :dt_naissance, isAdmin = :isAdmin WHERE id = :id'
        );
        return $requete->execute([
            'prenom' => $prenom,
            'nom' => $nom,
            'email' => $email,
            'cv' => $cv,
            'dt_naissance' => $dt_naissance,
            'isAdmin' => $isAdmin,
            'id' => $id
        ]);
    }
}

class EtudiantController {
    private $model;
    
    public function __construct() {
        $this->model = new EtudiantModel();
    }
    
    public function afficherTousEtudiants() {
        $etudiants = $this->model->getTousEtudiants();
        include 'vue.php';
    }

    public function afficherEtudiant($id) {
        $etudiant = $this->model->getEtudiantParId($id);
        if ($etudiant) {
            include 'afficher_un_etudiant.php';
        } else {
            echo "L'étudiant avec l'ID $id n'existe pas.";
        }
    }

    public function supprimerEtudiant($id) {
        if ($this->model->supprimerEtudiant($id)) {
            echo "L'étudiant a été supprimé avec succès.";
        } else {
            echo "Erreur lors de la suppression de l'étudiant.";
        }
    }

    public function creerEtudiant() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $prenom = $_POST['prenom'];
            $nom = $_POST['nom'];
            $email = $_POST['email'];
            $cv = $_POST['cv'];
            $dt_naissance = $_POST['dt_naissance'];
            $isAdmin = isset($_POST['isAdmin']) ? 1 : 0;

            // Créer l'étudiant
            if ($this->model->creerEtudiant($prenom, $nom, $email, $cv, $dt_naissance, $isAdmin)) {
                echo "L'étudiant a été ajouté avec succès.";
            } else {
                echo "Erreur lors de l'ajout de l'étudiant.";
            }
        } else {
            include 'creer_un_etudiant.php';
        }
    }

    public function modifierEtudiant($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $prenom = $_POST['prenom'];
            $nom = $_POST['nom'];
            $email = $_POST['email'];
            $cv = $_POST['cv'];
            $dt_naissance = $_POST['dt_naissance'];
            $isAdmin = isset($_POST['isAdmin']) ? 1 : 0;

            // Mettre à jour l'étudiant
            if ($this->model->modifierEtudiant($id, $prenom, $nom, $email, $cv, $dt_naissance, $isAdmin)) {
                echo "L'étudiant a été mis à jour avec succès.";
            } else {
                echo "Erreur lors de la mise à jour de l'étudiant.";
            }
        } else {
            // Afficher le formulaire de modification
            $etudiant = $this->model->getEtudiantParId($id);
            if ($etudiant) {
                include 'modifier_un_etudiant.php';
            } else {
                echo "L'étudiant avec l'ID $id n'existe pas.";
            }
        }
    }
}

// Routing
$action = $_GET['action'] ?? 'afficherTousEtudiants';
$id = $_GET['id'] ?? null;

$controller = new EtudiantController();

switch ($action) {
    case 'afficherTousEtudiants':
        $controller->afficherTousEtudiants();
        break;
    case 'afficherEtudiant':
        if ($id) {
            $controller->afficherEtudiant($id);
        } else {
            echo "ID non spécifié.";
        }
        break;
    case 'supprimerEtudiant':
        if ($id) {
            $controller->supprimerEtudiant($id);
        } else {
            echo "ID non spécifié.";
        }
        break;
    case 'creerEtudiant':
        $controller->creerEtudiant();
        break;
    case 'modifierEtudiant':
        if ($id) {
            $controller->modifierEtudiant($id);
        } else {
            echo "ID non spécifié.";
        }
        break;
    default:
        echo "Action non reconnue.";
        break;
}
?>