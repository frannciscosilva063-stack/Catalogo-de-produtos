<?php
session_start();  // Iniciar a sessão

// Destruir todos os dados da sessão
session_unset();
session_destroy();  // Destroi a sessão completamente

// Redirecionar para a página 'index.php' após o logout
header("Location: ../index.php?acao=bemvindo");
exit;  // Garante que o script pare após o redirecionamento
?>
