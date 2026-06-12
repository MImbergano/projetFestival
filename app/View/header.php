<?php
// On récupère le nom du fichier actuel
$current_page = basename($_SERVER['PHP_SELF']);
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/projetFestival/');
}
?>

<header>
  <nav>
    <div class="logo">
      <img src="<?php echo BASE_URL; ?>assets/images/Logo/logo.png" alt="Logo Arts & Traditions">
    </div>

    <ul class="navigation">
       <li><a href="<?php echo BASE_URL; ?>index.php" class="<?= ($current_page == 'index.php') ? 'active' : ''; ?>">Accueil</a></li>
       <li><a href="<?php echo BASE_URL; ?>app/Model/artistes.php" class="<?= ($current_page == 'artistes.php') ? 'active' : ''; ?>">Artistes</a></li>
       <li><a href="<?php echo BASE_URL; ?>app/Model/prestations.php" class="<?= ($current_page == 'prestations.php') ? 'active' : ''; ?>">Prestations</a></li>
       <li><a href="<?php echo BASE_URL; ?>app/Model/contact.php" class="<?= ($current_page == 'contact.php') ? 'active' : ''; ?>">Contact</a></li>
       <li><a href="<?php echo BASE_URL; ?>app/Model/partiePrive/dashboard_artiste.php">Espace Artiste</a></li>
       <li><a href="<?php echo BASE_URL; ?>app/Model/partiePrive/dashboard_organisateur.php">Espace Organisateur</a></li>

   </ul>
    <ul class="nav-connexion">
       <li><a href="<?php echo BASE_URL; ?>app/Model/connexion.php">Connexion</a></li>
    </ul>
  </nav>
</header>