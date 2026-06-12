<?php
// 1. CONNEXION ET LOGIQUE 
require_once __DIR__ . '/../../assets/config/db.php';
require_once __DIR__ . '/../View/fonctions_utiles.php'; 

$artiste_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($artiste_id === 0) {
    header('Location: artistes.php');
    exit();
}

try {
    $stmtArtiste = $pdo->prepare("SELECT nom_artiste, nom, prenom, description, photo FROM Utilisateur WHERE uid = ?");
    $stmtArtiste->execute([$artiste_id]);
    $artiste = $stmtArtiste->fetch();

    if (!$artiste) { die("Artiste introuvable."); }

    $sqlPresta = "SELECT P.pid, P.intitule, P.image, PR.heure_debut, S.nom_scene
                  FROM Prestation P
                  LEFT JOIN Programmation PR ON P.pid = PR.prestation_id
                  LEFT JOIN Scene S ON PR.scene_id = S.sid
                  WHERE P.artiste_id = ?";
    $stmtPresta = $pdo->prepare($sqlPresta);
    $stmtPresta->execute([$artiste_id]);
    $prestations = $stmtPresta->fetchAll();
} catch (PDOException $e) {
    die("Erreur base de donnees.");
}          
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profil de <?= htmlspecialchars($artiste['nom_artiste']) ?></title>
  <link rel="icon" type="image/x-icon" href="/~q250035/EVAL_V3/assets/images/Logo/logo.png">
  <link rel="stylesheet" href="../../assets/css/style.css" />
</head>

<body>
  <?php include('../View/header.php'); ?>

  <main class="centrage">
    <h1>Profil de : <?= htmlspecialchars($artiste['nom_artiste']) ?></h1>

    <section class="profil card-brun flexWrap">
      <img src="../../assets/images/Artiste/<?= obtenirImage($artiste['photo'], 'artiste') ?>" alt="photo artiste" />
      
      <div class="flexitems">
        <h2 class="underline"><?= htmlspecialchars($artiste['nom_artiste']) ?></h2>
        <p class="texte">
          <?= nl2br(htmlspecialchars($artiste['description'])) ?>
        </p>
      </div>
    </section>

    <section>
      <h3>Ses prestations</h3>
      <div class="decouverte fondDesign container-fluid displayGrid grid">
        
        <?php foreach ($prestations as $p): ?>
          <a href="./detail_prestation.php?id=<?= $p['pid'] ?>">
            <article class="SesPrestation card-brun translateY-card">
              <img src="../../assets/images/activite/<?= obtenirImage($p['image']) ?>" alt="photo <?= htmlspecialchars($p['intitule']); ?>" />
              <div>
                  <h4 class="underline"><?= htmlspecialchars($p['intitule']) ?></h4>
                  <ul class="card_info">
                    <?php if ($p['heure_debut']): ?>
                      <li><strong>Heure :</strong> <?= substr($p['heure_debut'], 0, 5) ?>H</li>
                      <li><strong>Lieu :</strong> <?= htmlspecialchars($p['nom_scene']) ?></li>
                    <?php else: ?>
                      <li><em>Non programmé</em></li>
                    <?php endif; ?>
                  </ul>
              </div>
            </article>
          </a>
        <?php endforeach; ?>

      </div>
    </section>
  </main> 
  
  <?php include('../View/footer.php'); ?>
</body>
</html>