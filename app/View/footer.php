<?php
  if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/projetFestival/');
  }
?>
  <footer>
    <p>Besoin de nous contacter ?</p>
    <a href="<?php echo BASE_URL; ?>app/Model/contact.php" class="fondDesign translateY-card">Contactez-nous</a>
    <p id="copyright">Copyright © 2025 All Rights Reserved</p>
  </footer>