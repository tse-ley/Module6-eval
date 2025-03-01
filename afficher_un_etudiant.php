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

// Récupérer l'ID de l'étudiant 
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    // Récupérer les données de l'étudiant
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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de l'étudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1 class="mb-4">Détails de l'étudiant</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($etudiant['prenom']) ?> <?= htmlspecialchars($etudiant['nom']) ?></h5>
            <p class="card-text"><strong>ID :</strong> <?= htmlspecialchars($etudiant['id']) ?></p>
            <p class="card-text"><strong>Email :</strong> <?= htmlspecialchars($etudiant['email']) ?></p>
            <p class="card-text"><strong>CV :</strong> <?= htmlspecialchars($etudiant['cv']) ?></p>
            <p class="card-text"><strong>Date de naissance :</strong> <?= htmlspecialchars($etudiant['dt_naissance']) ?></p>
            <p class="card-text"><strong>Admin :</strong> <?= $etudiant['isAdmin'] ? 'Oui' : 'Non' ?></p>
            <p class="card-text"><strong>Dernière mise à jour :</strong> <?= htmlspecialchars($etudiant['dt_mis_a_jour']) ?></p>
        </div>
    </div>

    <a href="index.php?action=afficherTousEtudiants" class="btn btn-secondary mt-4">Retour à la liste des étudiants</a>
</body>
</html>