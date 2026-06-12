<?php include('../View/header.php'); ?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Connexion | Arts & Traditions</title>
  <link rel="icon" type="image/x-icon" href="/~q250035/EVAL_V3/assets/images/Logo/logo.png">
  <link rel="stylesheet" href="../../assets/css/new.css">
</head>

<body class="flexCol">

  <main>
    <div class="centrage">

      <!-- se connecter -->
      <div class="formulaireContainer flexCol fondDesign">

        <h1>Se connecter</h1>
        <form action="#" class="formulaire flexCol">
          <!-- Adresse mail -->
          <label for="mail">Adresse email</label>
          <input type="email" name="mail" id="mail" placeholder="votre @mail.com" required />

          <!-- Mot de passe -->
          <label for="mdp">Mot de passe</label>
          <input type="password" name="mdp" id="mdp" placeholder="Votre mot de passe" required />
          <a href="./mdp_Oublier.php" class="message">Mot de passe oublié ?</a>
          <button type="submit" name="connecterBouton" class="btn bouton-envoyer">Se connecter</button>
        </form>
        <p class="message">Vous n'avez pas de compte ?</p>
        <a href="./inscription.php" class="bouton-commun">S'inscrire</a>
      </div>
    </div>

  </main>

 <?php include('../View/footer.php'); ?>

</body>

</html>