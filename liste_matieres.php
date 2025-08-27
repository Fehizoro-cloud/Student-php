<?php
$pdo = new PDO("mysql:host=localhost;dbname=student;charset=utf8", "root", "");

// SUPPRESSION : À placer avant tout affichage HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM matieres WHERE id = ?");
    $stmt->execute([$delete_id]);
    header("Location: " . $_SERVER['PHP_SELF']); // Redirection pour recharger la page sans resoumettre
    exit;
}

// Récupération de toutes les matières
$matieres = $pdo->query("SELECT * FROM matieres")->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
    <title>Liste des Matières</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
                background: linear-gradient(to bottom right, #000000,  #755fd8);
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                margin: 0;
                padding: 20px;
            }

            h2 {
                text-align: center;
                color: white;
                margin-bottom: 30px;
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
            table {
                width: 100%;
                border-collapse: collapse;
                margin: auto;
                background: rgba(255, 255, 255, 0.27); /* Fond semi-transparent */
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.37); /* Ombre plus marquée */
                border-radius: 10px;
                backdrop-filter: blur(10px); /* Effet de flou */
                -webkit-backdrop-filter: blur(10px); /* Compatibilité Safari */
                border: 1px solid rgba(255, 255, 255, 0.2); /* Bordure légère */
                overflow: hidden;
                margin-bottom: 150px;
            }

            th {
                background: rgb(10, 0, 56); /* Bleu translucide */
                color: white;
                padding: 12px;
                text-align: center;
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
            }

            td {
                color: black;
                padding: 12px;
                text-align: center;
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
                color: #fff;
            }

            tr:hover {
                color: black;
                background-color: rgba(255, 255, 255, 0.2); /* Survol translucide */
            }
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            width: 400px;
            border-radius: 10px;
        }
        /* Sidebar styles */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 60px; /* Initial width */
    background-color: #000;
    overflow-x: hidden;
    transition: width 0.5s;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* Espace entre les éléments */
    padding: 10px 0;
}
.sidebar a img {
    width: 30px;
    height: 29px;
    margin-right: 10px;
    flex-shrink: 0;
    border: none; 
}
.sidebar:hover {
    width: 175px; /* Expanded width */
}

.sidebar-content {
    display: flex;
    flex-direction: column;
    flex-grow: 1; /* Garde les liens en haut */
}

.sidebar a {
    display: flex;
    align-items: center;
    padding: 16px;
    text-decoration: none;
    color: white;
    text-align: left; /* Align text to the left */
    font-size: 18px; /* Font size for the text */
    transition: background-color 0.3s;
}

.sidebar a i {
    margin-right: 10px; /* Space between icon and text */
    font-size: 20px; /* Size of the icons */
}

.sidebar a span {
    display: none; /* Hide text by default */
}

.sidebar:hover a span {
    display: inline; /* Show text on hover */
}

.sidebar a:hover {
    background-color: #755fd8;
    border-radius: 5px; /* Rounded corners */
    padding-left: 20px; /* Extra space on hover */
}

/* Style for the "Se déconnecter" button */
.sidebar a.logout {
    margin-bottom: 10px; /* Space from the bottom */
}

.sidebar a.logout:hover {
    background-color: #ff4d4d; /* Red background on hover for the logout button */
}
    </style>
</head>
<body class="container py-4">
<div class="sparkle-container" id="sparkle-container"></div>
<div class="sidebar">
    <div class="sidebar-content">
        <a href="../student/index.php"><img src="../student/icon/icon (5).png" alt="Accueil" class="icon-png"><span>Accueil</span></a>
        <a href="../student/liste_matieres.php"><img src="../student/icon/icon (2).png" alt="Matiere" class="icon-png"><span>Matiere</span></a>
        <a href="../student/formulaire.php"><img src="../student/icon/icon (3).png" alt="Formulaire" class="icon-png"><span>Formulaire</span></a>
    </div>

    <!-- Bouton de déconnexion placé au pied de la sidebar -->
    <a href="../student/test.php" class="logout"><i class="fas fa-sign-out-alt"></i><span>Déconnexion</span></a>
</div>
    <h2 class="mb-4">Liste des Matières</h2>
    <!-- Bouton pour ajouter une matière -->
<button class="btn btn-success mb-3" onclick="ouvrirAjoutModal()">Ajouter une matière</button>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Libellé</th>
                <th>Coefficient</th>
                <th>Heure</th>
                <th style="width: 20%;">Action</th>

            </tr>
        </thead> 
        <tbody>
    <?php foreach ($matieres as $matiere): ?>
        <tr>
            <td><?= $matiere['id'] ?></td>
            <td><?= htmlspecialchars($matiere['nom']) ?></td>
            <td><?= $matiere['coefficient'] ?></td>
            <td><?= $matiere['heure'] ?></td>
            <td>
                <button class="btn btn-sm btn-primary" onclick='ouvrirModal(<?= json_encode($matiere) ?>)'>Modifier</button>
                <form method="POST" action="" style="display:inline;" onsubmit="return confirm('Voulez-vous vraiment supprimer cette matière ?');">
                    <input type="hidden" name="delete_id" value="<?= $matiere['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>

    </table>

    <!-- Modale d'ajout -->
<div class="modal" id="ajoutModal">
    <div class="modal-content">
        <h5>Ajouter une matière</h5>
        <form method="post" action="ajouter_matiere.php">
            <div class="mb-2">
                <label for="nom_ajout" class="form-label">Libellé</label>
                <input type="text" name="nom" id="nom_ajout" class="form-control" required>
            </div>
            <div class="mb-2">
                <label for="coefficient_ajout" class="form-label">Coefficient</label>
                <input type="number" name="coefficient" id="coefficient_ajout" class="form-control" required>
            </div>
            <div class="mb-2">
            <label for="heure_ajout" class="form-label">Heure</label>
            <input type="time" name="heure" id="heure_ajout" class="form-control" required>
        </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <button type="button" class="btn btn-secondary" onclick="fermerAjoutModal()">Annuler</button>
        </form>
    </div>
</div>

    <!-- Fenêtre modale -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <h5>Modifier la matière</h5>
            <form method="post" action="modifier_matieres.php">
                <input type="hidden" name="id" id="matieres_id">
                <div class="mb-2">
                    <label for="nom" class="form-label">Libellé</label>
                    <input type="text" name="nom" id="nom" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label for="coefficient" class="form-label">Coefficient</label>
                    <input type="number" name="coefficient" id="coefficient" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label for="heure" class="form-label">Heure</label>
                    <input type="time" name="heure" id="heure" class="form-control" required>

                </div>
                <button type="submit" class="btn btn-success">Enregistrer</button>
                <button type="button" class="btn btn-secondary" onclick="fermerModal()">Annuler</button>
            </form>
        </div>
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
    <script>
        function ouvrirModal(matieres) {
            document.getElementById("matieres_id").value = matieres.id;
            document.getElementById("nom").value = matieres.nom;
            document.getElementById("coefficient").value = matieres.coefficient;
            document.getElementById("heure").value = matieres.heure;
            document.getElementById("editModal").style.display = "block";
        }

        function fermerModal() {
            document.getElementById("editModal").style.display = "none";
        }

        // Fermer modal si on clique dehors
        window.onclick = function(event) {
            if (event.target == document.getElementById("editModal")) {
                fermerModal();
            }
        }
        function ouvrirAjoutModal() {
            document.getElementById("ajoutModal").style.display = "block";
        }

        function fermerAjoutModal() {
            document.getElementById("ajoutModal").style.display = "none";
        }
    </script>
</body>
</html>
