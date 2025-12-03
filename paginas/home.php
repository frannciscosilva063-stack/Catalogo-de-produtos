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

// Handler de export (executa antes de qualquer saída)
if (isset($_GET['export'])) {
    $handler = __DIR__ . '/conteudo/relatorio.php';
    if (file_exists($handler)) {
        include_once $handler;
        exit;
    } else {
        http_response_code(500);
        echo 'Handler de export não encontrado.';
        exit;
    }
}

// Handler de exclusão (executa antes de qualquer saída)
if (isset($_GET['acao']) && $_GET['acao'] === 'excluir_produto' && isset($_GET['id'])) {
    $handler = __DIR__ . '/conteudo/cadastro_produto.php';
    if (file_exists($handler)) {
        include_once $handler;
        exit;
    } else {
        http_response_code(500);
        echo 'Handler de exclusão não encontrado.';
        exit;
    }
}

// O resto do código continua aqui...
include_once('../includes/header.php');

// Sanitização de entrada — DEFAULT volta a ser 'bemvindo'
$acao = filter_var(isset($_GET['acao']) ? $_GET['acao'] : 'bemvindo', FILTER_SANITIZE_STRING);

// Definir caminhos em variáveis (mantém dashboard e cadastro)
$paginas = [
    'bemvindo' => 'conteudo/cadastro_produto.php',
    'dashboard' => 'conteudo/dashboard_home.php',
    'editar_produto' => 'conteudo/update_produtos.php',
    'perfil' => 'conteudo/perfil.php',
    'relatorio' => 'conteudo/relatorio.php',
    'categoria' => 'conteudo/categoria.php',
    'lista_contatos' => 'conteudo/update_contato.php',
    'excluir_produto' => 'conteudo/cadastro_produto.php'
];

// Incluir a página correspondente (fallback para bemvindo)
$pagina_incluir = isset($paginas[$acao]) ? $paginas[$acao] : $paginas['bemvindo']; 
include_once($pagina_incluir);

include_once('../includes/footer.php');
?>