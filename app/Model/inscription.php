<?php
require_once __DIR__ . '/../../assets/config/db.php';

$message = "";

// On initialise les variables
$nom = "";
$prenom = "";
$nomArtiste = "";
$description = "";
$email = "";

if (isset($_POST['inscrire'])) {
    // On recupere les donnees
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $nomArtiste = $_POST['nomArtiste'];
    $description = $_POST['description'];
    $email = $_POST['mail'];
    $mdp_brut = $_POST['mdp'];
    $mdp_hash = password_hash($mdp_brut, PASSWORD_DEFAULT);

    // Gestion de l'image
    $photoNom = "default.png";
    if (isset($_FILES['photoProfil']) && $_FILES['photoProfil']['error'] === 0) {
        $dossierDestination = "../../assets/images/Artiste/";
        $extension = pathinfo($_FILES['photoProfil']['name'], PATHINFO_EXTENSION);
        $photoNom = uniqid() . "." . $extension;
        $cheminComplet = $dossierDestination . $photoNom;
        move_uploaded_file($_FILES['photoProfil']['tmp_name'], $cheminComplet);
    }

    try {
        $sql = "INSERT INTO Utilisateur (nom, prenom, nom_artiste, email, mot_passe_hashe, photo, description, est_organisateur) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $prenom, $nomArtiste, $email, $mdp_hash, $photoNom, $description]);

        $message = "<p style='color: green;'>Inscription r&eacute;ussie ! <a href='connexion.php'>Connectez-vous ici</a></p>";
        
        // Si succ�s, on vide les champs pour ne pas que les infos restent affichees
        $nom = $prenom = $nomArtiste = $description = $email = "";
        
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $message = "<p style='color: red;'>Cette adresse e-mail est d&eacute;j&agrave; utilis&eacute;e.</p>";
        } else {
            $message = "<p style='color: red;'>Erreur : " . $e->getMessage() . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/x-icon" href="/~q250035/EVAL_V3/assets/images/Logo/logo.png">
  <link rel="stylesheet" href="../../assets/css/style.css">
  <title>Inscription | Arts & Traditions</title>
</head>

<body>
  <?php include('../View/header.php'); ?>

  <main class="centrage">
    <div class="formulaireContainer flexCol fondDesign">
      <h1>Inscription</h1>
      
      <?= $message ?>

      <form action="inscription.php" method="POST" enctype="multipart/form-data" class="formulaire flexCol" autocomplete="off">
        
        <div class="formulaire flexCol">
          <label for="photoProfil">Photo de profil</label>
          <input type="file" name="photoProfil" id="photoProfil" accept="image/*" required>
          <span class="info-text">Format accept&eacute;: JPG, PNG (max 5MB)</span>
        </div>

        <label for="nom">Nom</label>
        <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($nom) ?>" placeholder="Entrez nom" required />

        <label for="prenom">Pr&eacute;nom</label>
        <input type="text" name="prenom" id="prenom" value="<?= htmlspecialchars($prenom) ?>" placeholder="Entrez pr&eacute;nom" required />

        <label for="nomArtiste">Nom d'artiste (Optionnel)</label>
        <input type="text" name="nomArtiste" id="nomArtiste" value="<?= htmlspecialchars($nomArtiste) ?>" placeholder="Nom d'artiste" />

        <label for="description">Description</label>
        <textarea name="description" id="description" placeholder="Parlez-nous de vous..." required><?= htmlspecialchars($description) ?></textarea>

        <label for="mail">Adresse e-mail</label>
        <input type="email" name="mail" id="mail" value="<?= htmlspecialchars($email) ?>" placeholder="Entrez adresse e-mail" required />

        <label for="mdp">Mot de passe</label>
<input type="password" name="mdp" id="mdp" placeholder="Cr&eacute;ez un mot de passe" autocomplete="new-password" required />
        <button type="submit" class="btn bouton-envoyer" name="inscrire">S'inscrire</button>
      </form>

      <p id="message">Vous avez d&eacute;j&agrave; un compte ?</p>
      <a href="./connexion.php" class="bouton-commun">Se connecter</a>
    </div>
  </main>

  <?php include('../View/footer.php'); ?>
</body>
</html>