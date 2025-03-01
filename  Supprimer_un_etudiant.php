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
    // Vérifier si l'étudiant existe avant de supprimer
    $query = "SELECT id FROM etudiants WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Supprimer l'étudiant
        $deleteQuery = "DELETE FROM etudiants WHERE id = :id";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bindParam(":id", $id);

        if ($deleteStmt->execute()) {
            $message = "Le profil de l'étudiant a bien été supprimé.";
            $success = true;
        } else {
            $message = "Erreur lors de la suppression de l'étudiant.";
        }
    } else {
        $message = "L'étudiant avec l'ID $id n'existe pas dans la base de données.";
    }
} else {
    $message = "ID non spécifié.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer un étudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1 class="mb-4">Supprimer un étudiant</h1>

    <div class="alert <?= isset($success) && $success ? 'alert-success' : 'alert-danger' ?>">
        <?= $message ?>
    </div>

    <a href="index.php?action=afficherTousEtudiants" class="btn btn-secondary">Retour à la liste des étudiants</a>
</body>
</html>