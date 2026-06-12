<?php
// 2. CONNEXION ET FONCTIONS
require_once __DIR__ . '/../../assets/config/db.php';
require_once __DIR__ . '/../View/fonctions_utiles.php';

// 3. RÉCUPÉRATION DE L'ID
$presta_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($presta_id === 0) {
    die("Erreur : Aucun ID de prestation n'a été transmis dans l'URL.");
}

try {
    $sql = "SELECT P.*, U.uid as artiste_uid, U.nom, U.prenom, U.photo as photo_artiste, 
                   C.intitule as nom_categorie, PR.heure_debut, S.nom_scene
            FROM Prestation P
            INNER JOIN Utilisateur U ON P.artiste_id = U.uid
            LEFT JOIN categories C ON P.categories_id = C.cid 
            LEFT JOIN Programmation PR ON P.pid = PR.prestation_id
            LEFT JOIN Scene S ON PR.scene_id = S.sid
            WHERE P.pid = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$presta_id]);
    $p = $stmt->fetch();

    if (!$p) {
        die("Erreur : La prestation avec l'ID " . htmlspecialchars($presta_id) . " n'existe pas dans la base.");
    }
} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Détails | <?= htmlspecialchars($p['intitule']) ?></title>
  <link rel="icon" type="image/x-icon" href="/~q250035/EVAL_V3/assets/images/Logo/logo.png">
  <link rel="stylesheet" href="../../assets/css/style.css" />
</head>

<body>

  <?php include('../View/header.php'); ?> 

  <main class="centrage">
    <h1>Détails de la prestation</h1>

    <a href="./profil_Artiste.php?id=<?= $p['artiste_uid'] ?>">
      <div class="presenterPar">
        <img src="../../assets/images/Artiste/<?= obtenirImage($p['photo_artiste'], 'artiste') ?>" alt="photo artiste">
        <p>Présenté par <?= htmlspecialchars($p['prenom'] . ' ' . $p['nom']) ?></p>
      </div>
    </a>

    <div class="profil flexWrap">
      <section class="profil flexitems flexWrap card-brun">
        <img src="../../assets/images/activite/<?= obtenirImage($p['image']) ?>" alt="photo prestation" />
        
        <div class="flexitems">
          <h2 class="underline"><?= htmlspecialchars($p['intitule']) ?></h2>
          <span><?= nl2br(htmlspecialchars($p['description'])) ?></span>

          <p class="nomCategorie underline">
            <span>Catégorie</span> : <?= isset($p['nom_categorie']) ? htmlspecialchars($p['nom_categorie']) : 'Non classée' ?>
          </p>
        </div>
      </section>

      <section class="profil horaireDetail flexStart">
        <h3 class="container-fluid">Horaire</h3>
        <ul class="card_info">
          <li><strong>Heure :</strong> <?= $p['heure_debut'] ? substr($p['heure_debut'], 0, 5) . 'H' : 'à définir' ?></li>
          <li><strong>Lieu :</strong> <?= isset($p['nom_scene']) ? htmlspecialchars($p['nom_scene']) : 'Non programmé' ?></li>
        </ul>
      </section>
    </div>
  </main>

  <?php include('../View/footer.php'); ?>

</body>
</html>