<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../../assets/config/db.php';
require_once __DIR__ . '/../View/fonctions_utiles.php';
include('../View/header.php');

// 1. Récupération des données pour les filtres
$artistesFiltre = $pdo->query("SELECT uid, nom_artiste FROM utilisateur WHERE est_organisateur = 0 ORDER BY nom_artiste ASC")->fetchAll();
$categoriesFiltre = $pdo->query("SELECT cid, intitule FROM categories ORDER BY intitule ASC")->fetchAll();

// 2. Logique des filtres
$where = [];
$params = [];

if (!empty($_GET['Recherche'])) {
    $where[] = "P.intitule LIKE ?";
    $params[] = "%" . $_GET['Recherche'] . "%";
}

if (!empty($_GET['ListesArtistes'])) {
    $where[] = "P.artiste_id = ?";
    $params[] = $_GET['ListesArtistes'];
}

if (!empty($_GET['ListesCategories'])) {
    $where[] = "P.categories_id = ?";
    $params[] = $_GET['ListesCategories'];
}

if (isset($_GET['PrestationsProgrammerFiltre'])) {
    $where[] = "PR.prog_id IS NOT NULL";
}

$sql = "SELECT P.*, U.nom_artiste, PR.heure_debut, S.nom_scene
        FROM prestation P
        INNER JOIN utilisateur U ON P.artiste_id = U.uid
        LEFT JOIN programmation PR ON P.pid = PR.prestation_id
        LEFT JOIN scene S ON PR.scene_id = S.sid";

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY PR.heure_debut ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$prestations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Les prestations | Arts & Traditions</title>
    <link rel="icon" type="image/x-icon" href="/../../assets/images/Logo/logo.png">
    <link rel="stylesheet" href="../../assets/css/style.css" />
</head>

<body>
    

    <main>
        <section class="centrage">
            <h1>Catalogue des prestations</h1>

            <div class="conteneur-prestations grid container-fluid">
                <form method="GET" action="prestations.php" id="filtrePrestation">
                    <label for="Recherche">Recherche :</label>
                    <input type="search" name="Recherche" id="Recherche" placeholder="Rechercher" value="<?= isset($_GET['Recherche']) ? htmlspecialchars($_GET['Recherche']) : '' ?>" />

                    <div class="checkbox-conteneur">
                        <input type="checkbox" name="PrestationsProgrammerFiltre" id="PrestationsProgrammerFiltre" <?= isset($_GET['PrestationsProgrammerFiltre']) ? 'checked' : '' ?> />
                        <label for="PrestationsProgrammerFiltre">Prestations programmées</label>
                    </div>

                    <select name="ListesArtistes" id="ListesArtistes">
                        <option value="">Tous les artistes</option>
                        <?php foreach ($artistesFiltre as $art): ?>
                            <option value="<?= $art['uid'] ?>" <?= (isset($_GET['ListesArtistes']) && $_GET['ListesArtistes'] == $art['uid']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($art['nom_artiste']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="ListesCategories" id="ListesCategories">
                        <option value="">Toutes les catégories</option>
                        <?php foreach ($categoriesFiltre as $cat): ?>
                            <option value="<?= $cat['cid'] ?>" <?= (isset($_GET['ListesCategories']) && $_GET['ListesCategories'] == $cat['cid']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['intitule']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <div class="filtres-actions">
                        <button type="submit" class="btn-primaire btn">Appliquer</button>
                        <a href="prestations.php" class="btn-secondaire btn" style="display: block; text-decoration: none;">Réinitialiser</a>
                    </div>
                </form>

                <div class="grillePrestation flexCol flexitems fondDesign">
                    <?php if (empty($prestations)): ?>
                        <p style="padding: 20px; color: white;">Aucune prestation ne correspond.</p>
                    <?php else: ?>
                        <?php foreach ($prestations as $presta): ?>
                            <a href="./detail_prestation.php?id=<?= $presta['pid']; ?>">
                                <article class="grillePrestation flexWrap card-brun translateY-card">
                                    <img src="../../assets/images/activite/<?= obtenirImage($presta['image']) ?>" alt="photo <?= htmlspecialchars($presta['intitule']); ?>" />
                                    
                                    <div class="presentation-text-catalogue flexitems">
                                        <h2><?= htmlspecialchars($presta['intitule']); ?></h2>
                                        <p class="catalogue-artiste">Par <?= htmlspecialchars($presta['nom_artiste']); ?></p>
                                        <p>
                                            <?php if ($presta['heure_debut']): ?>
                                                <?= substr($presta['heure_debut'], 0, 5); ?>H - <?= htmlspecialchars($presta['nom_scene']); ?>
                                            <?php else: ?>
                                                Non programmé
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </article>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <?php include('../View/footer.php'); ?>
</body>
</html>