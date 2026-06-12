<?php
require_once __DIR__ . '/assets/config/db.php';
include('./app/View/header.php');

try {
    $stmtScenes = $pdo->query("SELECT * FROM Scene ORDER BY sid ASC");
    $scenes = $stmtScenes->fetchAll();

    $stmtHoraires = $pdo->query("SELECT DISTINCT heure_debut FROM Programmation ORDER BY heure_debut ASC");
    $horaires = $stmtHoraires->fetchAll();

    $sql = "SELECT PR.heure_debut, PR.scene_id, P.pid, P.intitule, U.nom_artiste 
            FROM Programmation PR
            INNER JOIN Prestation P ON PR.prestation_id = P.pid
            INNER JOIN Utilisateur U ON P.artiste_id = U.uid";
    $stmtProg = $pdo->query($sql);
    $programmation = $stmtProg->fetchAll();

    $tableauPlanning = [];
    foreach ($programmation as $item) {
        $tableauPlanning[$item['heure_debut']][$item['scene_id']] = $item;
    }

} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Accueil | Arts & Traditions</title>
  <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>/assets/images/Logo/logo.png">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/new.css"/>
</head>

<body>


  <main class="centrage">
    <section class="presentation flexWrap container-fluid section-space">
      <div class="flexitems">
        <h1>Arts & Traditions</h1>
        <p>
          Arts & Traditions est une journ&eacute;e d&eacute;di&eacute;e &agrave; la c&eacute;l&eacute;bration des
          savoir-faire, des traditions et des artisans qui fa&ccedil;onnent notre
          patrimoine culturel. Un moment de partage, d&rsquo;&eacute;motion et de d&eacute;couverte,
          o&ugrave; le pass&eacute; et le pr&eacute;sent se rencontrent au c&oelig;ur d&rsquo;un m&ecirc;me geste.
        </p>
      </div>
      <img src="<?php echo BASE_URL; ?>/assets/images/menuFamille.jpg" alt="Image de presentation" />
    </section>

    <section class="tableau grid tab_mobile ">
      <h2>Programme de la journ&eacute;e</h2>
      <table class="container-fluid">
        <thead>
          <tr>
            <th scope="col">Horaire</th>
            <?php foreach ($scenes as $s): ?>
                <th scope="col"><?= htmlspecialchars($s['nom_scene']) ?></th>
            <?php endforeach; ?>
          </tr>
        </thead>

        <tbody>
          <?php foreach ($horaires as $h): ?>
            <tr>
              <th scope="row">
                <time datetime="<?= $h['heure_debut'] ?>">
                    <?= substr($h['heure_debut'], 0, 5) ?>H
                </time>
              </th>
              
              <?php foreach ($scenes as $s): ?>
                <td>
                  <?php if (isset($tableauPlanning[$h['heure_debut']][$s['sid']])): 
                      $p = $tableauPlanning[$h['heure_debut']][$s['sid']]; ?>
                      
                      <a href="<?php echo BASE_URL; ?>/app/Model/detail_prestation.php?id=<?= $p['pid'] ?>" class="tabPrestation">
                        <div class="prog-item">
                        <strong><?= htmlspecialchars($p['intitule']) ?></strong>
                        <?= htmlspecialchars($p['nom_artiste']) ?>
                        </div>
                      </a>
                  
                  <?php endif; ?>
                </td>
              <?php endforeach; ?>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>
  </main>

  <?php include('./app/View/footer.php'); ?>

</body>
</html>