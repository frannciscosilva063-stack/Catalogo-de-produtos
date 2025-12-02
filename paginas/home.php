<?php
session_start();  // Iniciar a sessão

// Verificar se o parâmetro 'sair' está presente na URL
if (isset($_GET['sair'])) {
    // Destruir todos os dados da sessão
    session_unset();
    session_destroy();  // Destroi a sessão completamente

    // Redirecionar para a página 'index.php' após o logout
    header("Location: ../index.php");
    exit;  // Garante que o script pare após o redirecionamento
}

// O resto do código continua aqui...
include_once('../includes/header.php');

// Sanitização de entrada
$acao = filter_var(isset($_GET['acao']) ? $_GET['acao'] : 'bemvindo', FILTER_SANITIZE_STRING);

// Definir caminhos em variáveis - ADICIONEI 'lista_contatos'
$paginas = [
    'bemvindo' => 'conteudo/cadastro_produto.php',
    'editar' => 'conteudo/update_contato.php',
    'perfil' => 'conteudo/perfil.php',
    'relatorio' => 'conteudo/relatorio.php',
    'categoria' => 'conteudo/categoria.php',
    'lista_contatos' => 'conteudo/update_contato.php'
];

// Verificar se a ação existe no array, caso contrário, usar a página padrão
$pagina_incluir = isset($paginas[$acao]) ? $paginas[$acao] : $paginas['bemvindo']; 

// Incluir a página correspondente
include_once($pagina_incluir);

include_once('../includes/footer.php');
?>