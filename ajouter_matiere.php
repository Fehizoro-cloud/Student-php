<?php
$pdo = new PDO("mysql:host=localhost;dbname=student;charset=utf8", "root", "");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'];
    $coefficient = $_POST['coefficient'];
    $heure = $_POST['heure'];

    $stmt = $pdo->prepare("INSERT INTO matieres (nom, coefficient, heure) VALUES (?, ?, ?)");
    $stmt->execute([$nom, $coefficient, $heure]);

    header("Location: liste_matieres.php");
    exit();
}
?>
