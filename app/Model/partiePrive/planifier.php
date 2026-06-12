<?php
session_start();
require_once __DIR__ . '/../../../assets/config/db.php';
require_once __DIR__ . '/../../View/fonctions_utiles.php';

// Initialisation des variables
$etape = isset($_GET['etape']) ? (int)$_GET['etape'] : 1;
$heure = isset($_POST['heure']) ? $_POST['heure'] : (isset($_GET['heure']) ? $_GET['heure'] : '');
$artiste = isset($_POST['artiste']) ? $_POST['artiste'] : (isset($_GET['artiste']) ? $_GET['artiste'] : '');
$scene = isset($_POST['scene']) ? $_POST['scene'] : (isset($_GET['scene']) ? $_GET['scene'] : '');
$error = "";
$success = false;

// 1. CHARGEMENT DES DONNÉES DE RÉFÉRENCE
$scenes = $pdo->query("SELECT * FROM Scene ORDER BY sid ASC")->fetchAll();
$listeHoraires = ['13:00:00','14:00:00','15:00:00','16:00:00','17:00:00','18:00:00'];

// Récupération du planning actuel pour les vérifications de disponibilité
$progRaw = $pdo->query("SELECT PR.*, P.intitule, U.nom_artiste FROM Programmation PR INNER JOIN Prestation P ON PR.prestation_id = P.pid INNER JOIN Utilisateur U ON P.artiste_id = U.uid")->fetchAll();
$planning = [];
foreach ($progRaw as $p) { 
    $planning[$p['heure_debut']][$p['scene_id']] = $p; 
}

// 2. LOGIQUE DE NAVIGATION ET VALIDATION
if ($etape == 2 && isset($_POST['heure'])) {
    // Vérifier si au moins une scène est libre à cette heure-là
    $nbScenesOccupees = isset($planning[$heure]) ? count($planning[$heure]) : 0;
    if ($nbScenesOccupees >= count($scenes)) {
        $error = "Complet : Tous les créneaux sont déjà pris pour " . substr($heure,0,5) . ".";
        $etape = 1; 
    }
}

if ($etape == 3 && isset($_POST['scene'])) {
    // Vérification de sécurité : la scène est-elle occupée entre temps ?
    if (isset($planning[$heure][$scene])) {
        $error = "La scène sélectionnée vient d'être occupée. Veuillez en choisir une autre.";
        $etape = 2;
    }
}

// 3. ENREGISTREMENT FINAL
if (isset($_POST['confirmer'])) {
    $presta_id = $_POST['prestation'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO Programmation (heure_debut, prestation_id, scene_id) VALUES (?, ?, ?)");
        $stmt->execute([$heure, $presta_id, $scene]);
        $success = true;
    } catch (PDOException $e) {
        $error = "Erreur lors de l'enregistrement : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planifier une prestation | Arts & Traditions</title>
      <link rel="icon" type="image/x-icon" href="/~q250035/EVAL_V3/assets/images/Logo/logo.png">
    <link rel="stylesheet" href="../../../assets/css/new.css">
</head>
<body>
    <?php include('../../View/header.php'); ?>
    
    <main class="centrage">
        <div class="split-container grid">
            
            <section class="side-programme">
                <h3>Aperçu du Programme en temps réel</h3>
                <table class="mini-table">
                    <thead>
                        <tr>
                            <th class="col-heure">Heure</th>
                            <?php foreach ($scenes as $s): ?> 
                                <th><?= htmlspecialchars($s['nom_scene']) ?></th> 
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listeHoraires as $h): ?>
                            <tr class="<?= ($heure == $h) ? 'row-selected' : '' ?>">
                                <th class="col-heure"><?= substr($h, 0, 5) ?></th>
                                <?php foreach ($scenes as $s): ?>
                                    <td class="<?= isset($planning[$h][$s['sid']]) ? 'slot-occupied' : '' ?>">
                                        <?= isset($planning[$h][$s['sid']]) ? htmlspecialchars($planning[$h][$s['sid']]['nom_artiste']) : '<span>Libre</span>' ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <section class="side-form">
                <div class="formulaireContainer fondDesign flexWrap">
                    
                    <?php if ($success): ?>
                        <?php afficherSuccesEtRediriger("La prestation a été ajoutée à la programmation avec succès.", "dashboard_organisateur.php"); ?>
                    <?php else: ?>
                        <h2>Étape <?= $etape ?> / 3</h2>

                        <?php if ($error): ?>
                            <div class="error-msg" style="color: red; margin-bottom: 15px; font-weight: bold;">⚠️ <?= $error ?></div>
                        <?php endif; ?>

                        <form method="POST" action="planifier.php?etape=<?= $etape + 1 ?>" class="formulaire">
                            
                            <?php if ($etape == 1): ?>
                                <label>Heure de début :</label>
                                <select name="heure" required>
                                    <?php foreach($listeHoraires as $h): ?>
                                        <option value="<?= $h ?>" <?= ($heure == $h) ? 'selected' : '' ?>><?= substr($h,0,5) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn-primaire">Vérifier l'heure</button>

                            <?php elseif ($etape == 2): ?>
                                <input type="hidden" name="heure" value="<?= $heure ?>">
                                <p>Horaire choisi : <strong><?= substr($heure,0,5) ?></strong></p>
                                
                                <label>Artiste :</label>
                                <select name="artiste" required>
                                    <?php
                                    $sql = "SELECT DISTINCT U.uid, U.nom_artiste 
                                            FROM Utilisateur U 
                                            INNER JOIN Prestation P ON U.uid = P.artiste_id 
                                            WHERE U.est_organisateur = 0 
                                            ORDER BY U.nom_artiste ASC";
                                    $arts = $pdo->query($sql)->fetchAll();
                                    
                                    if (count($arts) > 0) {
                                        foreach($arts as $a) {
                                            echo "<option value='{$a['uid']}'>".htmlspecialchars($a['nom_artiste'])."</option>";
                                        }
                                    } else {
                                        echo "<option disabled>Aucun artiste disponible</option>";
                                    }
                                    ?>
                                </select>

                                <label>Scène disponible :</label>
                                <select name="scene" required>
                                    <?php foreach($scenes as $s): ?>
                                        <option value="<?= $s['sid'] ?>" <?= (isset($planning[$heure][$s['sid']])) ? 'disabled style="color:#ccc;"' : '' ?>>
                                            <?= htmlspecialchars($s['nom_scene']) ?> <?= (isset($planning[$heure][$s['sid']])) ? '(OCCUPÉE)' : '' ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn-primaire">Choisir la prestation</button>
                                <a href="planifier.php?etape=1" class="btn-nav">⬅ Retour</a>

                            <?php elseif ($etape == 3): ?>
                                <input type="hidden" name="heure" value="<?= $heure ?>">
                                <input type="hidden" name="scene" value="<?= $scene ?>">
                                <input type="hidden" name="artiste" value="<?= $artiste ?>">
                                
                                <?php 
                                    $nomS = "";
                                    foreach($scenes as $s) { if($s['sid'] == $scene) $nomS = $s['nom_scene']; }
                                ?>
                                
                                <div class="infoPlanning" style="background: #f9f9f9; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                                    <p>🕒 Heure : <strong><?= substr($heure,0,5) ?></strong></p>
                                    <p>📍 Scène : <strong><?= htmlspecialchars($nomS) ?></strong></p>
                                </div>
                                
                                <label>Sélectionner la prestation :</label>
                                <select name="prestation" required>
                                    <?php 
                                    $stmt = $pdo->prepare("SELECT pid, intitule FROM Prestation WHERE artiste_id = ?");
                                    $stmt->execute([$artiste]);
                                    $prestas = $stmt->fetchAll();
                                    foreach($prestas as $p) {
                                        echo "<option value='{$p['pid']}'>".htmlspecialchars($p['intitule'])."</option>";
                                    }
                                    ?>
                                </select>
                                
                                <button type="submit" name="confirmer" class="btn btn-primaire">Confirmer la programmation</button>
                                <a href="planifier.php?etape=2&heure=<?= $heure ?>" class="btn-nav">⬅ Retour</a>
                            <?php endif; ?>
                            
                            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
                            <a href="dashboard_organisateur.php" class="annulation displayBlock lien-prestation" style="text-align: center; color: #666;">✖ Annuler et quitter</a>
                        </form>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </main>
    
    <?php include('../../View/footer.php'); ?>
</body>
</html>