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

// Traitement du formulaire
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $cv = $_POST['cv'];
    $dt_naissance = $_POST['dt_naissance'];
    $isAdmin = isset($_POST['isAdmin']) ? 1 : 0;

    // Validation des données
    if (empty($prenom) || empty($nom) || empty($email) || empty($dt_naissance)) {
        $message = "Tous les champs obligatoires doivent être remplis.";
    } else {
        // Insérer les données dans la base de données
        $query = "INSERT INTO etudiants (prenom, nom, email, cv, dt_naissance, isAdmin) VALUES (:prenom, :nom, :email, :cv, :dt_naissance, :isAdmin)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":prenom", $prenom);
        $stmt->bindParam(":nom", $nom);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":cv", $cv);
        $stmt->bindParam(":dt_naissance", $dt_naissance);
        $stmt->bindParam(":isAdmin", $isAdmin);

        if ($stmt->execute()) {
            $message = "L'étudiant a été ajouté avec succès.";
            $success = true;
        } else {
            $message = "Erreur lors de l'ajout de l'étudiant.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un étudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1 class="mb-4">Créer un étudiant</h1>

    <?php if (!empty($message)): ?>
        <div class="alert <?= strpos($message, 'succès') !== false ? 'alert-success' : 'alert-danger' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form action="index.php?action=creerEtudiant" method="post" class="mb-4">
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom :</label>
            <input type="text" id="prenom" name="prenom" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="nom" class="form-label">Nom :</label>
            <input type="text" id="nom" name="nom" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email :</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="cv" class="form-label">CV :</label>
            <textarea id="cv" name="cv" class="form-control" rows="4"></textarea>
        </div>
        <div class="mb-3">
            <label for="dt_naissance" class="form-label">Date de naissance :</label>
            <input type="date" id="dt_naissance" name="dt_naissance" class="form-control" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" id="isAdmin" name="isAdmin" class="form-check-input">
            <label for="isAdmin" class="form-check-label">Admin</label>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>

    <a href="index.php?action=afficherTousEtudiants" class="btn btn-secondary">Retour à la liste des étudiants</a>
</body>
</html>