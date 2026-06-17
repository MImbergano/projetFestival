<?php

include('../../View/header.php');

require_once __DIR__ . '/../../../assets/config/db.php';

require_once __DIR__ . '/../../View/fonctions_utiles.php';



// 1. RECUPERATION DES PARAMETRES

$pid = isset($_GET['pid']) ? $_GET['pid'] : null;

$from = isset($_GET['from']) ? $_GET['from'] : 'artiste'; // 'orga' ou 'artiste'



if (!$pid) {

    header("Location: ../../../index.php");

    exit();

}



// 2. RECUPERATION DES INFOS DE LA PRESTATION ET DE L'ARTISTE

$stmt = $pdo->prepare("

    SELECT P.*, U.nom_artiste 

    FROM prestation P 

    INNER JOIN utilisateur U ON P.artiste_id = U.uid 

    WHERE P.pid = ?

");

$stmt->execute(array($pid));

$presta = $stmt->fetch();



if (!$presta) {

    die("Prestation introuvable.");

}



// 3. RECUPERATION DE TOUTES LES CATEGORIES POUR LE MENU DEROULANT

$query_cat = $pdo->query("SELECT cid, intitule FROM categories ORDER BY intitule ASC");

$categories = $query_cat->fetchAll();



// 4. LOGIQUE DE MISE À JOUR

$success = false;

if (isset($_POST['update_presta'])) {

    $intitule = $_POST['intitule'];

    $description = $_POST['description'];

    $cat_id = $_POST['categories_id'];



    try {

        $update = $pdo->prepare("UPDATE prestation SET intitule = ?, description = ?, categories_id = ? WHERE pid = ?");

        $update->execute(array($intitule, $description, $cat_id, $pid));

        

        $success = true;

    } catch (PDOException $e) {

        die("Erreur lors de la modification : " . $e->getMessage());

    }

}



// Gestion du bouton retour

$lien_retour = ($from === 'orga') ? 'modifier_profil.php?id=' . $presta['artiste_id'] : 'dashboard_artiste.php';

$texte_retour = ($from === 'orga') ? "Retour au profil" : "Retour à mon espace";

?>



<!DOCTYPE html>

<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Modifier la Prestation | Arts & Traditions</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="icon" type="image/x-icon" href="/../../../assets/images/Logo/logo.png">

    <link rel="stylesheet" href="../../../assets/css/style.css">

</head>

<body>

    <main class="centrage">

        <div class="formulaireContainer fondDesign flexCol" style="max-width: 600px; margin: 0 auto;">

            <h1>Modifier la Prestation</h1>

            <p>Artiste : <strong><?php echo htmlspecialchars($presta['nom_artiste']); ?></strong></p>



            <?php if ($success): ?>

                <?php afficherSuccesEtRediriger("Les modifications ont été enregistrées avec succès.", $lien_retour); ?>

            <?php else: ?>

                <form method="POST" class="formulaire flexCol">

                    <label for="intitule">Titre de la prestation</label>

                    <input type="text" name="intitule" id="intitule" value="<?php echo htmlspecialchars($presta['intitule']); ?>" required>



                    <label for="categories_id">Catégorie</label>

                    <select name="categories_id" id="categories_id" required>

                        <?php foreach ($categories as $cat): ?>

                            <option value="<?php echo $cat['cid']; ?>" <?php echo ($cat['cid'] == $presta['categories_id']) ? 'selected' : ''; ?>>

                                <?php echo htmlspecialchars($cat['intitule']); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>



                    <label for="description">Description</label>

                    <textarea name="description" id="description" rows="6"><?php echo htmlspecialchars($presta['description']); ?></textarea>



                    <div class="flexWrap gap-10 mt-20">

                        <button type="submit" name="update_presta" class="btn btn-primaire">Enregistrer les modifications</button>

                        <a href="<?php echo $lien_retour; ?>" class="btn btn-secondaire">⬅ <?php echo $texte_retour; ?></a>

                    </div>

                </form>

            <?php endif; ?>

        </div>

    </main>



    <?php include('../../View/footer.php'); ?>

</body>

</html>