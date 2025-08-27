<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bagaimana Mungkin</title>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to bottom right, #000000, #755fd8);
      color: white;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 60px;
    }

    nav {
      display: flex;
      gap: 30px;
      font-weight: 600;
    }

    nav a {
      color: #fff;
      text-decoration: none;
    }

    h1 {
      display: flex;
      align-items: center;
      gap: 10px; /* espace entre le logo et le texte */
      color: white;
      font-size: 2.5rem;
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


    .logo {
      width: 50px;
      height: auto;
    }
    .content {
      flex: 1;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 50px 60px;
    }

    .text {
      max-width: 50%;
    }

    .text h1 {
      font-size: 48px;
      margin-bottom: 10px;
      font-weight: 700;
    }

    .text p {
      font-size: 16px;
      margin-bottom: 20px;
      color: #e5e5e5;
    }

    .quote {
      font-style: italic;
      font-size: 14px;
    }

    .books {
      max-width: 40%;
    }

    .books img {
      width: 100%;
      border-radius: 30px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.404); 
    }

    footer {
      background: rgba(0, 0, 0, 0.829);
      border-radius: 10px; /* coins arrondis */
      padding: 20px 60px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: #fff;
      font-size: 14px;
    }

    /* Conteneur des icônes en colonne verticale */
    .icon-container {
      width: 60%;
  display: flex;
  flex-direction: row; /* affichage horizontal */
  gap: 30px;
  align-items: center;
  justify-content: center;
  margin-top: 10px;
  
  /* Effet Glassmorphisme */
  background: rgba(255, 255, 255, 0.205); /* fond semi-transparent */
  border-radius: 20px;
  backdrop-filter: blur(15px); /* flou en arrière-plan */
  -webkit-backdrop-filter: blur(15px); /* support Safari */
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2); /* ombre douce */
  padding: 20px 30px; /* espace intérieur */
}

/* Icône individuelle */
.icon { 
    text-decoration: none;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

/* Icône style */
.icon img {
    width: 60px;
    height: 60px;
    padding: 10px;
    background: linear-gradient(145deg, #ffffff, #e6e6e6);
    border-radius: 35%;
    box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2), -5px -5px 15px rgba(255, 255, 255, 0.2);
    transition: transform 0.3s ease;
}

/* Effet au survol */
.icon:hover img {
    transform: translateY(-10px) scale(1.1);
}

/* Texte sous l’icône */
.icon p {
    margin-top: 2px;
    color: #B4B5F3;
    font-size: 1rem;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.363);
}
.btn-deconnexion {
    display: inline-flex;
    background-color:rgba(139, 73, 165, 0.72); /* Violet/Mauve */
    box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.43), -5px -5px 15px rgba(114, 111, 111, 0.71);
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    gap: 10px;
    margin-left: 215%; /* si vraiment nécessaire, mais on peut améliorer ça */
    transition: background-color 0.3s, transform 0.2s;
}

.btn-deconnexion:hover {
    background-color: #8e44ad; /* Violet plus foncé au survol */
    transform: scale(1.05);    /* Petit effet d'agrandissement */
}

.icon-logout {
    width: 30px;
    height: 29px;
    object-fit: contain;
}

  </style>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="sparkle-container" id="sparkle-container"></div>
  <header>
    <div>#PlateformeÉducative</div>
    <nav>
      
    <a href="../student/test.php" class="btn-deconnexion">
    DÉCONNEXION
    <img src="../student/icon/icon.png" alt="Déconnexion" class="icon-logout">
    </a>

    </nav>
    <div class="icons">
      <i class="fas fa-comment-dots"></i>
      <i class="fas fa-bell"></i>
      <i class="fas fa-bars"></i>
    </div>
  </header>

  <div class="content">
    <div class="text">

      <h1>
        <img src="../student/icon/icon (1).png" alt="Logo" class="logo">
        UniverCity
      </h1>
      
      <p>Bienvenue sur UniverCity, une plateforme conçue pour simplifier la gestion et l’inscription des étudiants. S’inscrire dans un parcours d’études, c’est faire le premier pas vers ta réussite.</p>
      <div class="quote">"Le succès n’est pas une question de chance, mais de persévérance."</div><br>
      
       <!-- Afficher les icônes entourées de cercles -->
        <center>
       <div class="icon-container">
        <a href="../student/formulaire.php" class="icon">
          <img src="../student/icon/icon (7).png" alt="State Icon">
          <p>INSCRIPTION</p>
        </a>
        <a href="../student/liste_etudiants.php" class="icon">
          <img src="../student/icon/icon (6).png" alt="List Icon">
          <p>ETUDIANTS</p>
        </a>
        <a href="../student/liste_matieres.php" class="icon">
          <img src="../student/icon/icon (4).png" alt="Tools Icon">
          <p>MATIERE</p>
        </a>
      </div>         
    </center>
    
    </div>
    <div class="books">
      <img src="../student/img/7.jpeg" alt="Books">
    </div>
  </div>

  <footer>
    
    <div>Conçu avec passion pour faciliter la gestion et l'inscription des étudiants.Plateforme développée avec soin à l’aide des outils modernes.</div>
    <div>Created by<br> Feih</div>

  </footer>
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
