<?php
session_start();
$dbhost = 'localhost';
$dbname = 'student';
$dbuser = 'root';
$dbpass = '';

try {
    $db = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion: " . $e->getMessage());
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $required = ['matricule', 'nom', 'prenom', 'naissance', 'matieres'];
        
        foreach ($required as $field) {
            if ($field === 'matieres') {
                if (empty($_POST['matieres'])) {
                    throw new Exception("Sélectionnez au moins une matière");
                }
            } else {
                if (empty($_POST[$field])) {
                    throw new Exception("Tous les champs obligatoires doivent être remplis");
                }
            }
        }

        // Vérification photo
        if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Veuillez uploader une photo valide");
        }

        $file = $_FILES['photo'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024;

        if (!in_array($file['type'], $allowed_types)) {
            throw new Exception("Seuls les formats JPG, PNG et GIF sont autorisés");
        }

        if ($file['size'] > $max_size) {
            throw new Exception("La taille maximale autorisée est 2MB");
        }

        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $photo_name = uniqid() . '_' . basename($file['name']);
        $photo_path = $upload_dir . $photo_name;

        if (!move_uploaded_file($file['tmp_name'], $photo_path)) {
            throw new Exception("Erreur lors de l'upload de la photo");
        }

        // Insertion étudiant
        $stmt = $db->prepare("INSERT INTO etudiants 
                            (matricule, nom, prenom, date_naissance, photo)
                            VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['matricule'],
            $_POST['nom'],
            $_POST['prenom'],
            $_POST['naissance'], // Correction finale ici
            $photo_path
        ]);

        $etudiant_id = $db->lastInsertId();

        // Insertion matières
        $stmt = $db->prepare("INSERT INTO etudiant_matieres (etudiant_id, matiere_id) VALUES (?, ?)");
        foreach ($_POST['matieres'] as $matiere_id) {
            $stmt->execute([$etudiant_id, (int)$matiere_id]);
        }

        $success = "Inscription réussie !";
        unset($_POST);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$matieres = $db->query("SELECT * FROM matieres")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription Étudiant</title>
    <!-- Le CSS reste identique -->
     <style>
       * {
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: linear-gradient(to bottom right, #000000,  #755fd8);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .sparkle-container {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      overflow: hidden;
      z-index: -1;
    }
    .sparkle {
      position: absolute;
      width: 5px;
      height: 5px;
      background: white;
      border-radius: 50%;
      opacity: 0.8;
      animation: rise 2s linear forwards;
      box-shadow: 0 0 8px white;
    }
    @keyframes rise {
  0% {
    transform: translateY(0) scale(1);
    opacity: 1;
  }
  100% {
    transform: translateY(-200px) scale(0.5);
    opacity: 0;
  }
}
        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            max-width: 600px;
            width: 100%;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: #333;
        }

        input[type="text"],
        input[type="date"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding-top: 5px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            background: #f5f5f5;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .checkbox-item input {
            margin-right: 6px;
        }

        .error, .success {
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .error {
            background-color: #ffe6e6;
            color: #cc0000;
        }

        .success {
            background-color: #e6ffe6;
            color: #006600;
        }

        .button-container {
    display: flex;
    justify-content: space-between; /* pousse les boutons à gauche et à droite */
    gap: 20px;
    margin-top: 20px;
  }

  .button-container button {
    width: 48%;
    padding: 12px;
    background-color: #5a67d8;
    color: white;
    font-size: 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s ease;
  }

  .button-container button:hover {
    background-color: #434190;
  }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }
        }
        .inline-button {
    display: inline-block;
}
    
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        .checkbox-group { display: flex; flex-wrap: wrap; gap: 10px; }
        .checkbox-item { display: flex; align-items: center; }
        .checkbox-item input { margin-right: 5px; }
        .error { color: red; margin-bottom: 15px; }
        .success { color: green; margin-bottom: 15px; }
     </style>
</head>
<body>
<div class="sparkle-container" id="sparkle-container"></div>
<div class="container">
    <h2>Formulaire d'inscription étudiant</h2>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
    <script>
        if (confirm("<?= htmlspecialchars($success) ?>\n\nListe des étudiants ?")) {
            window.location.href = "liste_etudiants.php";
        } else {
            window.location.href = "index.php";
        }
        </script>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <div style="display: flex; gap: 20px;">
            <!-- Colonne Gauche -->
            <div style="flex: 1;">
                <label>Photo</label>
                <input type="file" name="photo" required><br>
                

                <label>Matières</label>
                <div style="max-height: 150px; overflow-y: auto; border: 1px solid #ccc; padding: 5px;">
                    <?php foreach ($matieres as $matiere): ?>
                        <label>
                            <input type="checkbox" name="matieres[]" 
                                   value="<?= $matiere['id'] ?>"> 
                            <?= htmlspecialchars($matiere['nom']) ?>
                        </label><br>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Colonne Droite -->
            <div style="flex: 1;">
                <label>Matricule</label>
                <input type="text" name="matricule" required>

                <label>Nom</label>
                <input type="text" name="nom" required>

                <label>Prénom</label>
                <input type="text" name="prenom" required>

                <label>Date de naissance</label>
                <input type="date" name="naissance" required>
            </div>
        </div>

        <br>
        <div class="button-container">
        <button type="button" onclick="window.location.href='index.php'">Annuler</button>
        <button type="submit" name="submit">Enregistrer</button>    
        </div>
    </form>
</div>
<script>
    function createSparkle() {
  const sparkle = document.createElement('div'); // <- le nom correct est sparkle

  sparkle.classList.add('sparkle'); // ici c'était écrit "sparcle", corrigé

  sparkle.style.left = Math.random() * window.innerWidth + 'px';
  sparkle.style.bottom = '0px';

  document.getElementById('sparkle-container').appendChild(sparkle); // id correctement écrit ici aussi

  setTimeout(() => {
    sparkle.remove();
  }, 2000);
}

// Crée une étincelle toutes les 100ms
setInterval(createSparkle, 100);

  </script>
</body>
</html>