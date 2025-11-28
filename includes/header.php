<?php
// É fundamental iniciar a sessão se você for usar $_SESSION
// session_start(); 

// --- 1. DEFINIÇÃO E INICIALIZAÇÃO DAS VARIÁVEIS DO USUÁRIO ---
// Definimos as variáveis $nome_user e $foto_user para evitar os erros "Undefined variable".
$nome_user = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Usuário Não Logado';
$foto_user = isset($_SESSION['foto']) ? $_SESSION['foto'] : 'avatar-padrao.png';

// --- 2. LÓGICA DE NAVEGAÇÃO ATIVA (ROBUSTA) ---
// Obtém o parâmetro 'acao' e 'id' da URL (com operador de coalescência nula)
$acao_get = $_GET['acao'] ?? '';
$pagina_get = $_GET['pagina'] ?? '';
$id_get = $_GET['id'] ?? '';

// Define o estado ativo para os itens fixos do menu
// 'Principal' está ativo se 'acao' for 'bemvindo' ou se for a página inicial sem ação específica.
$active_principal = ($acao_get === 'bemvindo' || ($acao_get === '' && $pagina_get === '')) ? 'active' : '';

// 'Relatório' está ativo se 'acao' for 'relatorio'.
$active_relatorio = ($acao_get === 'relatorio') ? 'active' : '';

// --- FIM DA DEFINIÇÃO DE VARIÁVEIS ---
?>
<!DOCTYPE html>
<html lang="pt_br">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Agenda Eletrônica</title>
  
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- ADMINLTE ASSETS -->
  <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- CSS Customizado (Pode remover o estilo.css externo se todo o estilo estiver aqui) -->
  <!-- <link rel="stylesheet" href="../dist/css/estilo.css"> -->

  <style>
    /* --- THEMA MINIMALIST BLUE (CLEAN UI) --- */

    :root {
      /* Paleta de Cores Refinada */
      --blue-primary: #007bff;  /* Azul Primário (Padrão Bootstrap) */
      --blue-dark: #0056b3;     /* Azul Escuro para Hover */
      --bg-light: #ffffff;      /* Fundo Branco */
      --bg-body: #f4f6f9;       /* Fundo da Área de Conteúdo (Clean) */
      --text-dark: #343a40;     /* Texto Escuro */
      --text-muted-light: #6c757d; /* Texto Secundário */
      
      --shadow-soft: 0 2px 4px rgba(0, 0, 0, 0.05);
      --shadow-blue-glow: 0 0 10px rgba(0, 123, 255, 0.2);
    }

    /* Reset Básico para Fontes mais Bonitas */
    body {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        font-family: 'Source Sans Pro', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Scrollbar Moderna (Estilo Limpo) */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    ::-webkit-scrollbar-track {
        background: var(--bg-body); 
    }
    ::-webkit-scrollbar-thumb {
        background: #ced4da; /* Cinza claro */
        border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: var(--blue-primary); 
    }

    /* --- SIDEBAR (Limpa e Profissional) --- */
    .main-sidebar {
        background-color: var(--bg-light) !important;
        background-image: none !important;
        box-shadow: 2px 0 8px rgba(0,0,0,0.1); /* Sombra lateral suave */
        border-right: 1px solid #dee2e6;
    }

    /* Logo Brand */
    .brand-link {
        background-color: var(--bg-light) !important;
        border-bottom: 1px solid #dee2e6 !important;
        color: var(--text-dark) !important;
        font-weight: 600 !important;
    }
    .brand-link:hover {
        background-color: var(--bg-body) !important;
    }
    .brand-text {
        color: var(--blue-primary) !important;
        font-weight: 700 !important;
        letter-spacing: 1px;
        font-size: 1.1rem;
    }

    /* --- ESTILOS ESPECÍFICOS BASEADOS NA IMAGEM --- */

    /* Painel do Usuário (Estilo da Imagem) */
    .user-panel {
        border-bottom: 1px solid #dee2e6 !important;
        margin-top: 0;
        padding: 10px 15px;
        background-color: #f8f9fa;
    }
    .user-panel .info a {
        color: var(--text-dark) !important;
        font-weight: 500;
        font-size: 0.9rem;
    }
    /* Avatar estilo da imagem */
    .user-panel .image img {
        border: 2px solid var(--blue-primary);
        box-shadow: var(--shadow-soft);
        padding: 2px;
        background: var(--bg-light);
        width: 40px;
        height: 40px;
    }

    /* Headers do Menu (Módulos - Estilo da Imagem) */
    .nav-header {
        background-color: var(--blue-primary) !important;
        color: white !important;
        font-size: 0.8rem !important;
        font-weight: 600;
        padding: 8px 16px !important;
        margin: 0 !important;
        border-radius: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* --- LINKS DE NAVEGAÇÃO (Estilo da Imagem) --- */
    .nav-sidebar .nav-item {
        margin-bottom: 0;
    }

    .nav-sidebar .nav-link {
        color: var(--text-dark) !important;
        border-radius: 0;
        transition: all 0.2s ease-in-out;
        border-left: 4px solid transparent;
        padding: 12px 16px;
        display: flex;
        align-items: center;
    }
    
    .nav-sidebar .nav-link i {
        color: var(--text-muted-light);
        margin-right: 12px;
        font-size: 1.1rem;
        width: 20px;
        text-align: center;
    }

    /* Hover State */
    .nav-sidebar .nav-link:hover {
        background-color: rgba(0, 123, 255, 0.05);
        color: var(--blue-dark) !important;
    }
    .nav-sidebar .nav-link:hover i {
        color: var(--blue-primary);
    }

    /* --- ESTADO ATIVO (Estilo da Imagem) --- */
    .nav-sidebar > .nav-item > .nav-link.active {
        background-color: rgba(0, 123, 255, 0.1) !important;
        color: var(--blue-dark) !important;
        border-left: 4px solid var(--blue-primary);
        font-weight: 600;
    }

    .nav-sidebar > .nav-item > .nav-link.active i {
        color: var(--blue-primary) !important;
    }

    /* --- NAVBAR SUPERIOR --- */
    .main-header {
        background-color: var(--bg-light) !important;
        border-bottom: 1px solid #dee2e6 !important;
        box-shadow: var(--shadow-soft);
    }
    
    /* Ícones da Navbar */
    .main-header .nav-link {
        color: var(--text-muted-light) !important;
    }
    .main-header .nav-link:hover {
        color: var(--blue-primary) !important;
    }
    
    /* Menu Dropdown */
    .dropdown-menu {
        background-color: var(--bg-light);
        border: 1px solid #dee2e6;
        box-shadow: 0 6px 12px rgba(0,0,0,.175);
    }
    .dropdown-item {
        color: var(--text-dark) !important;
    }
    .dropdown-item:hover {
        background-color: rgba(0, 123, 255, 0.1);
        color: var(--blue-primary) !important;
    }
    .dropdown-divider {
        border-top-color: #dee2e6;
    }

    /* Conteúdo Principal */
    .content-wrapper {
        background-color: var(--bg-body) !important;
    }

  </style>

</head>
<!-- Mudança da classe: 'sidebar-dark-primary' para 'sidebar-light-primary' para o tema claro -->
<body class="hold-transition sidebar-mini layout-fixed sidebar-light-primary">
<div class="wrapper">
  
  <!-- Navbar Superior (Agora Light) -->
  <nav class="main-header navbar navbar-expand navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      
      <!-- Perfil Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" title="Perfil e Saída">
          <div class="d-flex align-items-center">
             <!-- Ícone mais discreto na navbar -->
             <i class="fas fa-user-circle" style="font-size: 1.5rem; color: var(--text-muted-light);"></i>
          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header text-center font-weight-bold" style="color: var(--blue-dark);">Minha Conta</span>
          <div class="dropdown-divider"></div>
          
          <a href="home.php?acao=perfil" class="dropdown-item">
            <i class="fas fa-user-cog mr-2"></i> Alterar Perfil
          </a>
          
          <div class="dropdown-divider"></div>
          
          <a href="home.php?sair=true" class="dropdown-item text-danger">
            <i class="fas fa-sign-out-alt mr-2"></i> Sair do Sistema
          </a>
        </div>
      </li>
      
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container (Mantendo apenas a classe base para permitir o CSS customizado) -->
  <aside class="main-sidebar elevation-4">
    <!-- Brand Logo -->
    <a href="home.php?acao=bemvindo" class="brand-link">
      <!-- Ícone ou Logo Pequeno se tiver -->
      <i class="fas fa-calendar-check mr-2" style="color: var(--blue-primary);"></i>
      <span class="brand-text">Agenda Pro</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
        <div class="image">
          <?php
            // Lógica de imagem segura
            if ($foto_user == 'avatar-padrao.png' || empty($foto_user)) {
                echo '<img src="../img/avatar_p/avatar-padrao.png" class="img-circle elevation-2" alt="User Image">';
            } else {
                echo '<img src="../img/user/' . $foto_user . '" class="img-circle elevation-2" alt="User Image">';
            }
          ?>
        </div>
        <div class="info">
          <a href="#" class="d-block text-truncate" style="max-width: 150px;"><?php echo $nome_user; ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          <!-- Headers de Menu customizados para o tema claro -->
          <li class="nav-header text-uppercase font-weight-bold">Módulos</li>

          <li class="nav-item">
            <a href="home.php?acao=bemvindo" class="nav-link <?= $active_principal ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="home.php?acao=relatorio" class="nav-link <?= $active_relatorio ?>">
              <i class="nav-icon fas fa-chart-line"></i> 
              <p>Relatórios</p>
            </a>
          </li>
           <?php
          include_once('../config/conexao.php');
         
          ?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
</div>
</body>
</html>