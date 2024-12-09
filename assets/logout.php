<?php
session_start(); // Inicia a sessão

// Destruir a sessão
session_unset();  // Remove todas as variáveis de sessão
session_destroy();  // Destroi a sessão

// Redirecionar para a página de login
header('Location: ../public/pagina_login.php');
exit;
?>
