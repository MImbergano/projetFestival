<?php
include('../../View/header.php');
require_once __DIR__ . '/../../../assets/config/db.php';




if (isset($_POST['action'])) {

    if ($_POST['action'] === 'deprogrammer') {

        $stmt = $pdo->prepare("DELETE FROM programmation WHERE prog_id = ?");

        $stmt->execute([$_POST['id']]);

    } elseif ($_POST['action'] === 'supprimer_artiste') {

        $pdo->beginTransaction();

        try {

            $pdo->prepare("DELETE FROM programmation WHERE prestation_id IN (SELECT pid FROM prestation WHERE artiste_id = ?)")->execute([$_POST['id']]);

            $pdo->prepare("DELETE FROM prestation WHERE artiste_id = ?")->execute([$_POST['id']]);

            $pdo->prepare("DELETE FROM utilisateur WHERE uid = ?")->execute([$_POST['id']]);

            $pdo->commit();

        } catch (Exception $e) {

            $pdo->rollBack();

        }

    }

    header("Location: dashboard_organisateur.php");

    exit();

}



$organisateur_id = isset($_SESSION['uid']) ? $_SESSION['uid'] : 11;

$stmt_orga = $pdo->prepare("SELECT nom, prenom FROM utilisateur WHERE uid = ?");

$stmt_orga->execute([$organisateur_id]);

$orga_info = $stmt_orga->fetch();



// Récupération des données

$scenes = $pdo->query("SELECT * FROM scene ORDER BY sid ASC")->fetchAll();

$horaires = $pdo->query("SELECT DISTINCT heure_debut FROM programmation UNION SELECT '13:00:00' UNION SELECT '14:00:00' UNION SELECT '15:00:00' UNION SELECT '16:00:00' UNION SELECT '17:00:00' UNION SELECT '18:00:00' ORDER BY heure_debut ASC")->fetchAll();

$progRaw = $pdo->query("SELECT PR.*, P.intitule, U.nom_artiste, U.nom, U.prenom FROM programmation PR INNER JOIN prestation P ON PR.prestation_id = P.pid INNER JOIN utilisateur U ON P.artiste_id = U.uid")->fetchAll();

$artistes = $pdo->query("SELECT * FROM utilisateur WHERE est_organisateur = 0")->fetchAll();



$planning = [];

foreach ($progRaw as $p) { $planning[$p['heure_debut']][$p['scene_id']] = $p; }

?>



<!DOCTYPE html>

<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Organisateur</title>

      <link rel="icon" type="image/x-icon" href="/../../assets/images/Logo/logo.png">

    <link rel="stylesheet" href="../../../assets/css/style.css">

</head>

<body>

    

    <main class="centrage">

        <div class="flexWrap flexBetween">

            <h1>Espace de <?= htmlspecialchars($orga_info['prenom'] . ' ' . $orga_info['nom']) ?></h1>

            <div class="flexcol">

                <a href="planifier.php" class="btn btn-primaire">+ Planifier</a>

                <a href="modifier_profil.php" class="btn btn-secondaire">Mon Profil</a>

            </div>

        </div>



        <div class="stats-container">

            <div class="stat-card"><h3><?= count($progRaw) ?></h3><p>Prestations</p></div>

            <div class="stat-card"><h3><?= count($artistes) ?></h3><p>Artistes</p></div>

        </div>



        <div class="dashboard-grid tab_mobile ">

            <section class="panel-planning">

                <h2>Planning</h2>

                <table>

                    <thead>

                        <tr>

                            <th>Heure</th>

                            <?php foreach ($scenes as $s): ?><th><?= $s['nom_scene'] ?></th><?php endforeach; ?>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($horaires as $h): ?>

                        <tr>

                            <th><?= substr($h['heure_debut'], 0, 5) ?></th>

                            <?php foreach ($scenes as $s): ?>

                            <td>

                                <?php if (isset($planning[$h['heure_debut']][$s['sid']])): 

                                    $p = $planning[$h['heure_debut']][$s['sid']]; ?>

                                    <div class="prog-item">

                                        <strong><?= htmlspecialchars($p['intitule']) ?></strong>

                                        <?= htmlspecialchars($p['nom_artiste']) ?>

                                        <form method="POST" onsubmit="return confirm('Déprogrammer cette prestation ?');">

                                            <input type="hidden" name="id" value="<?= $p['prog_id'] ?>">

                                            <input type="hidden" name="action" value="deprogrammer">

                                            <button type="submit" class="btn-small btn-danger">Supprimer</button>

                                        </form>

                                    </div>

                                <?php endif; ?>

                            </td>

                            <?php endforeach; ?>

                        </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table> 

            </section>



            <aside class="scroll-panel">

                <h2>Liste des Artistes</h2>

                <?php foreach ($artistes as $a): ?>

                <div class="mini-card">

                    <div class="artist-info">

                        <strong><?= htmlspecialchars($a['prenom'] . ' ' . $a['nom']) ?></strong><br>

                        <small><?= htmlspecialchars($a['email']) ?></small>

                    </div>

                    <div class="actions-group">

                        <a href="modifier_profil.php?id=<?= $a['uid'] ?>" class="btn-small btn-primaire">Gérer</a>

                        <form method="POST" onsubmit="return confirm('Supprimer définitivement cet artiste ?');">

                            <input type="hidden" name="id" value="<?= $a['uid'] ?>">

                            <input type="hidden" name="action" value="supprimer_artiste">

                            <button type="submit" class="btn-small btn-warning">Suppr.</button>

                        </form>

                    </div>

                </div>

                <?php endforeach; ?>

            </aside>

        </div>

    </main>



    <?php include('../../View/footer.php'); ?>

</body>

</html>