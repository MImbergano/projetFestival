<?php
session_start();
require_once __DIR__ . '/../../../assets/config/db.php';
require_once __DIR__ . '/../../View/fonctions_utiles.php';

// --- LOGIQUE DE RÉCUPÉRATION DE L'ID ---
$provenance = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
} elseif (isset($_SESSION['uid'])) {
    $user_id = $_SESSION['uid'];
} else {
    // PAR DÉFAUT POUR LA DÉMO
    if (strpos($provenance, 'dashboard_organisateur.php') !== false) {
        $user_id = 11; 
    } else {
        $user_id = 10; 
    }
}

// 1. RÉCUPÉRATION DES INFOS DE L'UTILISATEUR CIBLE
$query = $pdo->prepare("SELECT * FROM Utilisateur WHERE uid = ?");
$query->execute(array($user_id));
$user = $query->fetch();

if (!$user) {
    die("Utilisateur non trouvé.");
}

// Déterminer les rôles
$is_target_organisateur = ($user['est_organisateur'] == 1);
$is_viewer_orga = (isset($_SESSION['est_organisateur']) && $_SESSION['est_organisateur'] == 1) 
                  || (isset($_SESSION['uid']) && $_SESSION['uid'] == 6) 
                  || (strpos($provenance, 'dashboard_organisateur.php') !== false);

$success = false;

// 2. LOGIQUE DE MISE À JOUR
if (isset($_POST['save_profile'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $desc = htmlspecialchars($_POST['description']);
    $nom_a = (!$is_target_organisateur) ? htmlspecialchars($_POST['nom_artiste']) : null;

    try {
        $stmt = $pdo->prepare("UPDATE Utilisateur SET nom=?, prenom=?, email=?, description=?, nom_artiste=? WHERE uid=?");
        $stmt->execute(array($nom, $prenom, $email, $desc, $nom_a, $user_id));
        $success = true;
    } catch (PDOException $e) {
        die("Erreur lors de la mise à jour : " . $e->getMessage());
    }
}

// 3. RÉCUPÉRATION DES PRESTATIONS (Uniquement pour un artiste)
$prestations = array();
if (!$is_target_organisateur) {
    $stmt_prestas = $pdo->prepare("
        SELECT P.*, PR.heure_debut  
        FROM Prestation P 
        LEFT JOIN Programmation PR ON P.pid = PR.prestation_id 
        WHERE P.artiste_id = ?
    ");
    $stmt_prestas->execute(array($user_id));
    $prestations = $stmt_prestas->fetchAll();
}

$affichage_nom = !empty($user['nom_artiste']) ? $user['nom_artiste'] : $user['nom'];
$lien_retour = $is_viewer_orga ? "dashboard_organisateur.php" : "dashboard_artiste.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modifier Profil - <?php echo htmlspecialchars($affichage_nom); ?></title>
  <link rel="icon" type="image/x-icon" href="/~q250035/EVAL_V3/assets/images/Logo/logo.png">
    <link rel="stylesheet" href="../../../assets/css/style.css">
</head>
<body>
    <?php include('../../View/header.php'); ?>

    <main class="centrage">
        <div class="<?php echo ($is_viewer_orga && !$is_target_organisateur) ? 'dashboard-grid' : ''; ?> tab_mobile">
            
            <section class="formulaireContainer fondDesign flexCol" style="<?php echo (!$is_viewer_orga || $is_target_organisateur) ? 'max-width:600px; margin: 0 auto;' : ''; ?>">
                <h1>Modifier <?php echo $is_target_organisateur ? 'le profil Organisateur' : 'le profil Artiste'; ?></h1>
                
                <?php if ($success): ?>
                    <?php afficherSuccesEtRediriger("Vos modifications ont été enregistrées avec succès.", $lien_retour); ?>
                <?php else: ?>
                    <form method="POST" class="formulaire flexCol">
                        <label>Nom</label>
                        <input type="text" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required>
                        
                        <label>Prénom</label>
                        <input type="text" name="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>" required>

                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                        <?php if (!$is_target_organisateur): ?>
                            <label>Nom d'artiste</label>
                            <input type="text" name="nom_artiste" value="<?php echo htmlspecialchars($user['nom_artiste']); ?>" required>
                        <?php endif; ?>

                        <label>Biographie</label>
                        <textarea name="description" rows="5"><?php echo isset($user['description']) ? htmlspecialchars($user['description']) : ''; ?></textarea>

                        <div class="flexWrap">
                            <button type="submit" name="save_profile" class="btn btn-primaire">Enregistrer</button>
                            <a href="<?php echo $lien_retour; ?>" class="btn btn-secondaire">Annuler</a>
                        </div>
                    </form>
                <?php endif; ?>
            </section>

            <?php if ($is_viewer_orga && !$is_target_organisateur && !$success): ?>
            <aside class="scroll-panel">
                <h2>Prestations de l'artiste</h2>
                <?php if (empty($prestations)): ?>
                    <p>Aucune prestation.</p>
                <?php else: ?>
                    <?php foreach ($prestations as $p): ?>
                        <div class="mini-card">
                            <div class="artist-info">
                                <strong><?php echo htmlspecialchars($p['intitule']); ?></strong><br>
                                <small><?php echo !empty($p['heure_debut']) ? "📅 Programmée" : "❌ Libre"; ?></small>
                            </div>
                            <div class="actions-group">
                                <a href="modifier_prestation.php?pid=<?php echo $p['pid']; ?>&from=orga" class="btn-small btn-primaire">Gérer</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </aside>
            <?php endif; ?>

        </div>
    </main>

    <?php include('../../View/footer.php'); ?>
</body>
</html>