<?php
include('../../View/header.php');
require_once __DIR__ . '/../../../assets/config/db.php';



// 1. RECUPERATION DE L'ID DE L'ARTISTE (Session ou dEfaut pour test)

$artiste_id = isset($_SESSION['uid']) ? $_SESSION['uid'] : 10; 



// 2. RECUPERATION DES INFOS DE L'ARTISTE CONNECTE

$stmt_artiste = $pdo->prepare("SELECT nom, prenom, nom_artiste FROM utilisateur WHERE uid = ?");

$stmt_artiste->execute([$artiste_id]);

$artiste_info = $stmt_artiste->fetch();



// 3. LOGIQUE DE SUPPRESSION D'UNE PRESTATION

if (isset($_POST['action']) && $_POST['action'] === 'supprimer_presta') {

    $pid = $_POST['id'];

    

    // VErification : la prestation appartient à l'artiste et n'est pas programmEe

    $check = $pdo->prepare("SELECT COUNT(*) FROM programmation WHERE prestation_id = ?");

    $check->execute([$pid]);

    

    if ($check->fetchColumn() == 0) {

        $stmt = $pdo->prepare("DELETE FROM prestation WHERE pid = ? AND artiste_id = ?");

        $stmt->execute([$pid, $artiste_id]);

        header("Location: dashboard_artiste.php?msg=deleted");

    } else {

        header("Location: dashboard_artiste.php?error=programmed");

    }

    exit();

}



// 4. RECUPERATION DES DONNEES POUR LE PLANNING FILTRE

$scenes = $pdo->query("SELECT * FROM scene ORDER BY sid ASC")->fetchAll();

$horaires = $pdo->query("SELECT DISTINCT heure_debut FROM programmation UNION SELECT '13:00:00' UNION SELECT '14:00:00' UNION SELECT '15:00:00' UNION SELECT '16:00:00' UNION SELECT '17:00:00' UNION SELECT '18:00:00' ORDER BY heure_debut ASC")->fetchAll();



// On recupere uniquement les prestations de cet artiste precis

$stmt_prog = $pdo->prepare("

    SELECT PR.*, P.intitule 

    FROM programmation PR 

    INNER JOIN prestation P ON PR.prestation_id = P.pid 

    WHERE P.artiste_id = ?

");

$stmt_prog->execute([$artiste_id]);

$progRaw = $stmt_prog->fetchAll();



$planning = [];

foreach ($progRaw as $p) { 

    $planning[$p['heure_debut']][$p['scene_id']] = $p; 

}



// 5. RECUPERATION DE TOUTES SES PRESTATIONS

$stmt_mes_prestas = $pdo->prepare("

    SELECT P.*, PR.heure_debut, S.nom_scene 

    FROM prestation P 

    LEFT JOIN programmation PR ON P.pid = PR.prestation_id 

    LEFT JOIN scene S ON PR.scene_id = S.sid

    WHERE P.artiste_id = ?

");

$stmt_mes_prestas->execute([$artiste_id]);

$mes_prestations = $stmt_mes_prestas->fetchAll();

?>



<!DOCTYPE html>

<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Artiste</title>

  <link rel="icon" type="image/x-icon" href="/~q250035/EVAL_V3/assets/images/Logo/logo.png">

    <link rel="stylesheet" href="../../../assets/css/style.css">

</head>

<body>

    <main class="centrage">

        <div class="flexWrap flexBetween">

            <h1>Espace de <?= htmlspecialchars($artiste_info['prenom'] . ' ' . $artiste_info['nom']) ?></h1>

            <div class="flexcol">

                <a href="ajouter_prestation.php" class="btn btn-primaire">+ Prestation</a>

                <a href="modifier_profil.php" class="btn btn-secondaire">Mon Profil</a>

            </div>

        </div>



        <div class="stats-container">

            <div class="stat-card">

                <h3><?= count($mes_prestations) ?></h3>

                <p>Prestations</p>

            </div>

            <div class="stat-card">

                <h3><?php $countProg = 0;foreach($mes_prestations as $mp) if(!empty($mp['heure_debut'])) $countProg++;echo $countProg; ?></h3>

                <p>ProgrammEes</p>

            </div>

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

                <h2>Mes Prestations</h2>

                <?php if (empty($mes_prestations)): ?>

                    <p>Vous n'avez pas encore ajoutE de prestation.</p>

                <?php endif; ?>

                

                <?php foreach ($mes_prestations as $p): 

                    $is_prog = !empty($p['heure_debut']);

                ?>

                <div class="mini-card">

                    <div class="artist-info">

                        <strong><?= htmlspecialchars($p['intitule']) ?></strong><br>

                        <small>

                            <?= $is_prog ? "📅 ProgrammEe à ".substr($p['heure_debut'], 0, 5) : "❌ Non programmEe" ?>

                        </small>

                    </div>

                    <div class="actions-group">

                        <a href="modifier_prestation.php?pid=<?= $p['pid'] ?>" class="btn-small btn-primaire">Editer</a>

                        <form method="POST" onsubmit="return confirm('Supprimer cette prestation ?');">

                            <input type="hidden" name="id" value="<?= $p['pid'] ?>">

                            <input type="hidden" name="action" value="supprimer_presta">

                            <button type="submit" class="btn-small btn-warning" <?= $is_prog ? 'disabled style="opacity:0.5; cursor:not-allowed;"' : '' ?>>Suppr.</button>

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