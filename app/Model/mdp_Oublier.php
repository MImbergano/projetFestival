<?php include('../View/header.php'); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mot de passe oublié | Arts & Traditions</title>
  <link rel="icon" type="image/x-icon" href="/~q250035/EVAL_V3/assets/images/Logo/logo.png">
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body>


  <main class="centrage">
    <div class="formulaireContainer flexCol fondDesign">
      <h1>Réinitialiser votre mot de passe</h1>
      <p id="message">
        Un nouveau mot de passe vous sera attribué.
      </p>


      <form action="#" class="formulaire flexCol">

        <!-- Adresse mail -->
        <label for="mail">Adresse email</label>
        <input type="email" name="mail" id="mail" placeholder="Entrez votre e-mail" required />
        <button type="submit" class="btn bouton-envoyer" name="inscrire">Envoyer</button>
        
      </form>
      <a href="./connexion.php" class="bouton-commun">Retour à la connexion</a>
    </div>

  </main>

  <?php include('../View/footer.php'); ?>

</body>

</html>