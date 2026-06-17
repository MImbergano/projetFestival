<?php

include('../../View/header.php');
require_once __DIR__ . '/../../../assets/config/db.php';

require_once __DIR__ . '/../../View/fonctions_utiles.php'; 



$artiste_id = 10; 

$success = false; 



$query_cat = $pdo->query("SELECT * FROM categories ORDER BY intitule ASC");

$categories = $query_cat->fetchAll();



if (isset($_POST['add_presta'])) {

    try {

        $stmt = $pdo->prepare("INSERT INTO prestation (intitule, description, artiste_id, image, categories_id) VALUES (?, ?, ?, ?, ?)");

        $stmt->execute(array($_POST['intitule'], $_POST['description'], $artiste_id, "defaut.jpg", $_POST['categories_id']));

        $success = true;

    } catch (PDOException $e) {

        die("Erreur : " . $e->getMessage());

    }

}

?>



<!DOCTYPE html>

<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>Ajouter une Prestation</title>

    <link rel="icon" type="image/x-icon" href="/../../../assets/images/Logo/logo.png">

    <link rel="stylesheet" href="../../../assets/css/style.css">



</head>

<body>

    <main class="centrage">

        <div class="formulaireContainer fondDesign flexCol">

            <?php if ($success): ?>

                <?php afficherSuccesEtRediriger("La prestation a été ajoutée avec succès.", "dashboard_artiste.php", 3); ?>

            <?php else: ?>

                <h1>Nouvelle Prestation</h1>

                <form method="POST" class="formulaire flexCol">

                    <label>Titre de la prestation *</label>

                    <input type="text" name="intitule" required placeholder="Ex: Sculpture sur bois">



                    <label>Catégorie *</label>

                    <select name="categories_id" required>

                        <option value="">-- Choisir une catégorie --</option>

                        <?php foreach ($categories as $cat): ?>

                            <option value="<?php echo $cat['cid']; ?>"><?php echo htmlspecialchars($cat['intitule']); ?></option>

                        <?php endforeach; ?>

                    </select>



                    <label>Description</label>

                    <textarea name="description" rows="5" placeholder="Décrivez votre savoir-faire..."></textarea>



                    <div class="flexWrap">

                        <button type="submit" name="add_presta" class="btn btn-primaire">Ajouter au catalogue</button>

                        <a href="dashboard_artiste.php" class="btn btn-secondaire">Annuler</a>

                    </div>

                </form>

            <?php endif; ?>

        </div>

    </main>



    <?php include('../../View/footer.php'); ?>

</body>

</html>