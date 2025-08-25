<?php
// Connexion à la base de données
$db = new PDO("mysql:host=localhost;dbname=student;charset=utf8", "root", "");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Traitement de la modification
if (isset($_POST['modifier'])) {
    $id = $_POST['id'];
    $matricule = $_POST['matricule'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date_naissance = $_POST['date_naissance'];
    $photo_path = '';

    // Si une nouvelle photo est uploadée
    if (!empty($_FILES['photo']['name'])) {
        $photo_name = time() . '_' . $_FILES['photo']['name'];
        $photo_path = 'uploads/' . $photo_name;
        move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path);
    } else {
        // Récupérer l'ancienne photo
        $stmt = $db->prepare("SELECT photo FROM etudiants WHERE id = ?");
        $stmt->execute([$id]);
        $photo_path = $stmt->fetchColumn();
    }

    // Mise à jour de l'étudiant
    $stmt = $db->prepare("UPDATE etudiants SET matricule = ?, nom = ?, prenom = ?, date_naissance = ?, photo = ? WHERE id = ?");
    $stmt->execute([$matricule, $nom, $prenom, $date_naissance, $photo_path, $id]);

    // Mise à jour des matières
    $stmt = $db->prepare("DELETE FROM etudiant_matieres WHERE etudiant_id = ?");
    $stmt->execute([$id]);

    if (!empty($_POST['matieres'])) {
        foreach ($_POST['matieres'] as $matiere_id) {
            $stmt = $db->prepare("INSERT INTO etudiant_matieres (etudiant_id, matiere_id) VALUES (?, ?)");
            $stmt->execute([$id, $matiere_id]);
        }
    }

    // Recharger la page après modification
    header("Location: liste_etudiants.php");
    exit;
}

// Suppression si demandé
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    // Supprimer les matières associées
    $stmt = $db->prepare("DELETE FROM etudiant_matieres WHERE etudiant_id = ?");
    $stmt->execute([$id]);

    // Supprimer l'étudiant
    $stmt = $db->prepare("DELETE FROM etudiants WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: liste_etudiants.php");
    exit;
}

// Récupération des étudiants
$etudiants = $db->query("SELECT * FROM etudiants")->fetchAll(PDO::FETCH_ASSOC);

// Récupération des matières pour chaque étudiant
$etudiant_matieres = [];
$stmt = $db->prepare("SELECT em.etudiant_id, m.nom 
                      FROM etudiant_matieres em 
                      JOIN matieres m ON em.matiere_id = m.id");
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $etudiant_matieres[$row['etudiant_id']][] = $row['nom'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    
    <title>Liste des Étudiants</title>
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
    width: 80%;
    border-collapse: collapse;
    margin: auto;
    background: rgba(255, 255, 255, 0.1); /* Fond semi-transparent */
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.37); /* Ombre plus marquée */
    border-radius: 10px;
    backdrop-filter: blur(10px); /* Effet de flou */
    -webkit-backdrop-filter: blur(10px); /* Compatibilité Safari */
    border: 1px solid rgba(255, 255, 255, 0.2); /* Bordure légère */
    overflow: hidden;
    margin-bottom: 150px;
}

th {
    background: rgba(95, 66, 224, 0.5); /* Bleu translucide */
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

img {
    max-height: 80px;
    border-radius: 8px;
    border: 1px solid #ccc;
}

.btn {
    padding: 6px 14px;
    margin: 4px;
    text-decoration: none;
    color: white;
    background-color: #3498db;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 0.9em;
    transition: background-color 0.3s;
}

.btn:hover {
    opacity: 0.9;
}

.btn-delete {
    background-color: #e74c3c;
}

.btn-edit {
    background-color: #2ecc71;
}

/* Style pour la modale */
#modal {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.6);
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

#modal > div {
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
}

#modal h3 {
    margin-top: 0;
    color: #2c3e50;
}

#modal label {
    display: block;
    margin-top: 10px;
    font-weight: bold;
}

#modal input[type="text"],
#modal input[type="date"],
#modal input[type="file"] {
    width: 100%;
    padding: 8px;
    margin-top: 4px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

#modal input[type="checkbox"] {
    margin-right: 5px;
}

#modif_preview {
    margin-top: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
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
<body>
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
    <h2>Liste des Étudiants Inscrits</h2>

    <table>
        <thead>
            <tr>
                <th>Photo</th>
                <th>Matricule</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Date de Naissance</th>
                <th>Matières</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($etudiants as $etudiant): ?>
            <tr>
                <td><img src="<?= htmlspecialchars($etudiant['photo']) ?>" alt="Photo"></td>
                <td><?= htmlspecialchars($etudiant['matricule']) ?></td>
                <td><?= htmlspecialchars($etudiant['nom']) ?></td>
                <td><?= htmlspecialchars($etudiant['prenom']) ?></td>
                <td><?= htmlspecialchars($etudiant['date_naissance']) ?></td>
                <td>
                    <?php
                        $id = $etudiant['id'];
                        echo isset($etudiant_matieres[$id]) ? implode(", ", $etudiant_matieres[$id]) : "Aucune";
                    ?>
                </td>
               <td>
                    <div style="display: flex; gap: 5px;">
                        <button class="btn btn-edit"
                            onclick='ouvrirModal(
                                <?= json_encode($etudiant) ?>, 
                                <?= json_encode($etudiant_matieres[$etudiant['id']] ?? []) ?>
                            )'>
                            Modifier
                        </button>

                        <a class="btn btn-delete"
                        href="?delete=<?= $etudiant['id'] ?>"
                        onclick="return confirm('Supprimer cet étudiant ?')">
                            Supprimer
                        </a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <!-- Fenêtre modale -->
        <div id="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:#000000aa; justify-content:center; align-items:center;">
    <div style="background:white; padding:20px; border-radius:10px; width:550px; max-width:95%; position:relative;">
        <center><h3 style="margin-bottom: 20px;">Modifier Étudiant</h3></center>
        <form id="formModif" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" id="modif_id">
            <div style="display: flex; gap: 60px;">
                
                <!-- Colonne Gauche -->
                <div style="flex: 1;">
                    <label style="font-weight: bold;">Photo</label><br>
                    <center><img id="modif_preview" src="" alt="Photo actuelle" style="max-height:100px; margin-top:10px;"></center><br>
                    <input type="file" name="photo" style="width: 100%;"><br>

                    <label style="font-weight: bold;">Matières</label><br>
                    <div style="max-height: 150px; overflow-y: auto; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
                        <?php
                        $matiereList = $db->query("SELECT * FROM matieres")->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($matiereList as $matiere): ?>
                            <label style="display: block; margin-left: 5px; font-weight: normal;">
                                <input type="checkbox" name="matieres[]" value="<?= $matiere['id'] ?>" class="modif_matiere"> <?= htmlspecialchars($matiere['nom']) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Colonne Droite -->
                <div style="flex: 1;">
                    <label style="font-weight: bold;">Matricule</label>
                    <input type="text" name="matricule" id="modif_matricule" required style="width: 70%; margin-bottom: 8px;"><br>

                    <label style="font-weight: bold;">Nom</label>
                    <input type="text" name="nom" id="modif_nom" required style="width: 70%; margin-bottom: 8px;"><br>

                    <label style="font-weight: bold;">Prénom</label>
                    <input type="text" name="prenom" id="modif_prenom" required style="width: 70%; margin-bottom: 8px;"><br>

                    <label style="font-weight: bold;">Date de naissance</label>
                    <input type="date" name="date_naissance" id="modif_date" required style="width: 70%;">
                </div>
            </div>
Modifier
            <br>
            <div style="text-align: center;">
                <button type="submit" name="modifier" class="btn btn-edit" style="margin-right: 10px;">Enregistrer</button>
                <button type="button" class="btn btn-delete" onclick="fermerModal()">Annuler</button>
            </div>
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

function ouvrirModal(etudiant, matieres) {
    document.getElementById('modal').style.display = 'flex';
    document.getElementById('modif_id').value = etudiant.id;
    document.getElementById('modif_matricule').value = etudiant.matricule;
    document.getElementById('modif_nom').value = etudiant.nom;
    document.getElementById('modif_prenom').value = etudiant.prenom;
    document.getElementById('modif_date').value = etudiant.date_naissance;
    document.getElementById('modif_preview').src = etudiant.photo;

    // Désélectionner toutes les cases
    document.querySelectorAll('.modif_matiere').forEach(cb => {
        cb.checked = false;
    });

    // Sélectionner les matières de l'étudiant
    matieres.forEach(m => {
        document.querySelectorAll('.modif_matiere').forEach(cb => {
            if (cb.nextSibling.textContent.trim() === m.trim()) {
                cb.checked = true;
            }
        });
    });
}

function fermerModal() {
    document.getElementById('modal').style.display = 'none';
}
</script>

        </tbody>
    </table>
</body>
</html>
