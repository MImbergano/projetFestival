<?php
require_once __DIR__ . '/../../assets/config/db.php';
require_once __DIR__ . '/../View/fonctions_utiles.php';
include('../View/header.php'); 

// Par défaut, on prend tous les artistes
$sql = "SELECT uid, nom, prenom, nom_artiste, photo FROM utilisateur WHERE est_organisateur = 0";

// Si la case est cochée, on ajoute une condition
if (isset($_GET['filtreActif']) && $_GET['filtreActif'] == '1') {
    $sql = "SELECT DISTINCT U.uid, U.nom, U.prenom, U.nom_artiste, U.photo 
            FROM utilisateur U
            INNER JOIN prestation P ON U.uid = P.artiste_id
            INNER JOIN programmation PR ON P.pid = PR.prestation_id
            WHERE U.est_organisateur = 0";
}

$sql .= " ORDER BY nom_artiste ASC"; 
$stmt = $pdo->query($sql);
$artistes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Artistes | Arts & Traditions</title>
  <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>/assets/images/Logo/logo.png">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css" />
</head>
<body>
  

  <main>
    <section>
      <h1>D&eacute;couvrez nos artistes</h1>

      <div class="blockInfo flexStart container-fluid">
        <form method="GET" action="artistes.php" class="filtreArtistes flexWrap">
            <input type="checkbox" name="filtreActif" id="filtreActif" value="1" onchange="this.form.submit()" <?= isset($_GET['filtreActif']) ? 'checked' : '' ?> />
            <label for="filtreActif">Artistes programm&eacute;s</label>
        </form>
      </div>

      <div class="decouverte fondDesign container-fluid displayGrid">
        
        <?php foreach ($artistes as $a): ?>
          <a href="./profil_Artiste.php?id=<?= $a['uid'] ?>">
            <article class="img-circle card-brun translateY-card">

              <img src="../../assets/images/Artiste/<?= obtenirImage($a['photo'], 'artiste') ?>" 
                   alt="Photo de <?= htmlspecialchars($a['nom_artiste']) ?>" />
              
              <h2><?= htmlspecialchars($a['nom'] . ' ' . $a['prenom']) ?></h2>
              
              <ul class="card_info">
                <li>Voir le profil</li>
              </ul>
            </article>
          </a>
        <?php endforeach; ?>

      </div>
    </section>
  </main>

  <?php include('../View/footer.php'); ?>
</body>
</html>