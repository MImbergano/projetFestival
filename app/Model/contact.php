<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact | Arts & Traditions</title>
  <link rel="icon" type="image/x-icon" href="/~q250035/EVAL_V3/assets/images/Logo/logo.png">
    <link rel="stylesheet" href="../../assets/css/new.css">
</head>
<body>
    <?php include('../View/header.php'); ?>
    <main class="centrage">
        <div class="formulaireContainer flexCol fondDesign">
            <form action="contact.php" method="POST" class="formulaire flexCol">
            <h1>Contactez-nous</h1>
                    <label for="nom">Nom :</label>
                    <input type="text" name="nom" id="nom" placeholder="Votre nom" required />

                    <label for="mail">Votre adresse mail :</label>
                    <input type="email" name="mail" id="mail" placeholder="votre@mail.com" required />

                    <label for="sujet">Sujet :</label>
                    <input type="text" name="sujet" id="sujet" placeholder="Sujet du message" required />

                    <label for="message">Message :</label>
                    <textarea name="message" id="message" placeholder="Votre message ici..." required></textarea>
                <button type="submit" name="contactbouton" class="btn bouton-envoyer">Envoyer</button>
            </form>
        </div>
    </main>
    <?php include('../View/footer.php'); ?>
</body>
</html>