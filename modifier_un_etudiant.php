<?php
$host = "localhost";
$db_name = "module6";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}


$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    $query = "SELECT * FROM etudiants WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $etudiant = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$etudiant) {
        die("L'étudiant avec l'ID $id n'existe pas.");
    }
} else {
    die("ID non spécifié.");
}


$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $cv = $_POST['cv'];
    $dt_naissance = $_POST['dt_naissance'];
    $isAdmin = isset($_POST['isAdmin']) ? 1 : 0;

    
    if (empty($prenom) || empty($nom) || empty($email) || empty($dt_naissance)) {
        $message = "Tous les champs obligatoires doivent être remplis.";
    } else {
        
        $updateQuery = "UPDATE etudiants SET prenom = :prenom, nom = :nom, email = :email, cv = :cv, dt_naissance = :dt_naissance, isAdmin = :isAdmin WHERE id = :id";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bindParam(":prenom", $prenom);
        $updateStmt->bindParam(":nom", $nom);
        $updateStmt->bindParam(":email", $email);
        $updateStmt->bindParam(":cv", $cv);
        $updateStmt->bindParam(":dt_naissance", $dt_naissance);
        $updateStmt->bindParam(":isAdmin", $isAdmin);
        $updateStmt->bindParam(":id", $id);

        if ($updateStmt->execute()) {
            $message = "Le profil de l'étudiant a bien été mis à jour.";
            $success = true;
          
            $stmt->execute();
            $etudiant = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $message = "Erreur lors de la mise à jour du profil de l'étudiant.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un étudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1 class="mb-4">Modifier un étudiant</h1>

    <?php if (!empty($message)): ?>
        <div class="alert <?= isset($success) && $success ? 'alert-success' : 'alert-danger' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form action="index.php?action=modifierEtudiant&id=<?= htmlspecialchars($id) ?>" method="post" class="mb-4">
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom :</label>
            <input type="text" id="prenom" name="prenom" class="form-control" value="<?= htmlspecialchars($etudiant['prenom']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="nom" class="form-label">Nom :</label>
            <input type="text" id="nom" name="nom" class="form-control" value="<?= htmlspecialchars($etudiant['nom']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email :</label>
            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($etudiant['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="cv" class="form-label">CV :</label>
            <textarea id="cv" name="cv" class="form-control" rows="4"><?= htmlspecialchars($etudiant['cv']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="dt_naissance" class="form-label">Date de naissance :</label>
            <input type="date" id="dt_naissance" name="dt_naissance" class="form-control" value="<?= htmlspecialchars($etudiant['dt_naissance']) ?>" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" id="isAdmin" name="isAdmin" class="form-check-input" <?= $etudiant['isAdmin'] ? 'checked' : '' ?>>
            <label for="isAdmin" class="form-check-label">Admin</label>
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>

    <a href="index.php?action=afficherTousEtudiants" class="btn btn-secondary">Retour à la liste des étudiants</a>
</body>
</html>