<?php
// Connexion à la base de données
$host = "localhost";
$db_name = "module6";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer tous les étudiants
    $query = "SELECT * FROM etudiants";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    $etudiants = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des étudiants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1 class="mb-4">Liste des étudiants</h1>

    <a href="index.php?action=creerEtudiant" class="btn btn-primary mb-4">Créer un étudiant</a>
    
    <?php if (empty($etudiants)): ?>
        <div class="alert alert-warning">Aucun étudiant trouvé dans la base de données.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Date de naissance</th>
                    <th>Admin</th>
                    <th>Mise à jour</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($etudiants as $etudiant): ?>
                    <tr>
                        <td><?= htmlspecialchars($etudiant['id']) ?></td>
                        <td><?= htmlspecialchars($etudiant['prenom']) ?></td>
                        <td><?= htmlspecialchars($etudiant['nom']) ?></td>
                        <td><?= htmlspecialchars($etudiant['email']) ?></td>
                        <td><?= htmlspecialchars($etudiant['dt_naissance']) ?></td>
                        <td><?= $etudiant['isAdmin'] ? 'Oui' : 'Non' ?></td>
                        <td><?= htmlspecialchars($etudiant['dt_mis_a_jour']) ?></td>
                        <td>
                            <a href="index.php?action=afficherEtudiant&id=<?= htmlspecialchars($etudiant['id']) ?>" class="btn btn-info btn-sm">Voir</a>
                            <a href="index.php?action=modifierEtudiant&id=<?= htmlspecialchars($etudiant['id']) ?>" class="btn btn-warning btn-sm">Modifier</a>
                            <a href="index.php?action=supprimerEtudiant&id=<?= htmlspecialchars($etudiant['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>