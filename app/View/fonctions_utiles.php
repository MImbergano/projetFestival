<?php
/**
 * Retourne le nom de l'image ou une image par défaut si le champ est vide
 * * @param string $image_db  Le nom du fichier stocké en base de données
 * @param string $type      Le type d'image ('activite' ou 'artiste')
 * @return string           Le nom du fichier à afficher
 */
function obtenirImage($image_db, $type = 'activite') {
    $image = trim($image_db);
    
    if (!empty($image)) {
        return $image;
    }

    // Retourne l'image par défaut selon le contexte
    if ($type === 'artiste') {
        return 'defaut.png';
    } else {
        return 'defaut.jpg';
    }
}


/**
 * Affiche un message de succès et redirige après X secondes
 * @param string $message Le texte à afficher
 * @param string $url L'adresse de destination
 * @param int $secondes Temps d'attente (défaut 5)
 */
function afficherSuccesEtRediriger($message, $url, $secondes = 2) {
    ?>
    <meta http-equiv="refresh" content="<?php echo $secondes; ?>;url=<?php echo $url; ?>">

    <div style="background: #d4edda; color: #155724; padding: 20px; border-radius: 8px; border: 1px solid #c3e6cb; text-align: center; margin: 20px 0; font-family: Arial, sans-serif;">
        <h2 style="margin-top: 0;">✅ Opération réussie</h2>
        <p><?php echo htmlspecialchars($message); ?></p>
        <p style="font-size: 0.9em; color: #666; margin-top: 15px;">
            Redirection automatique dans <?php echo $secondes; ?> secondes...<br>
            <a href="<?php echo $url; ?>" style="color: #155724; text-decoration: underline;">Cliquez ici pour y aller maintenant</a>
        </p>
    </div>
    <?php
}
?>