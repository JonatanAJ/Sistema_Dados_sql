<?php
session_start();
session_destroy();
header('Location: ../public/pagina_login.php');
exit;
?>
