<?php
$pdo = new PDO("mysql:host=localhost;dbname=student;charset=utf8", "root", "");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $coefficient = $_POST['coefficient'];
    $heure = $_POST['heure'];

    $stmt = $pdo->prepare("UPDATE matieres SET nom = ?, coefficient = ?, heure = ? WHERE id = ?");
    $stmt->execute([$nom, $coefficient, $heure, $id]);

    header("Location: liste_matieres.php");
    exit();
}
?>
