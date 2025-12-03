<?php
// Inicia buffer de saída no início do fluxo para permitir envio seguro de headers mais adiante
if (!ob_get_level()) {
    ob_start();
}

// É fundamental iniciar a sessão se você for usar $_SESSION
// session_start(); 

// --- 1. DEFINIÇÃO E INICIALIZAÇÃO DAS VARIÁVEIS DO USUÁRIO ---
// Definimos as variáveis $nome_user e $foto_user para evitar os erros "Undefined variable".
$nome_user = isset($_SESSION['nome_user']) ? $_SESSION['nome_user'] : (isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Usuário');
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

// 'Perfil' está ativo se 'acao' for 'perfil'.
$active_perfil = ($acao_get === 'perfil') ? 'active' : '';

// --- FIM DA DEFINIÇÃO DE VARIÁVEIS ---
?>
<!DOCTYPE html>
<html lang="pt_br">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Mercado Express | Dashboard</title>
  
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
    /* --- THEMA MERCADO EXPRESS (PROFISSIONAL E MODERNO) --- */

    :root {
      /* Paleta de Cores - Mercado Express */
      --primary-color: #2C5AA0;
      --primary-dark: #1E3F73;
      --secondary-color: #4ECDC4;
      --accent-color: #FFD166;
      --text-dark: #2D3047;
      --text-light: #6C757D;
      --success-color: #06D6A0;
      --white: #ffffff;
      --gray-light: #F8F9FA;
      --gray-border: #E9ECEF;
      
      --shadow-soft: 0 2px 8px rgba(0, 0, 0, 0.08);
      --shadow-medium: 0 4px 15px rgba(0, 0, 0, 0.12);
      --shadow-glow: 0 0 15px rgba(44, 90, 160, 0.15);
    }

    /* Reset Básico */
    body {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        background: linear-gradient(135deg, var(--gray-light) 0%, #ffffff 100%) !important;
    }

    /* Scrollbar Moderna */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    ::-webkit-scrollbar-track {
        background: var(--gray-light); 
    }
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, var(--primary-color), var(--secondary-color));
        border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, var(--primary-dark), var(--secondary-color)); 
    }

    /* --- SIDEBAR (Moderno e Elegante) --- */
    .main-sidebar {
        background: linear-gradient(180deg, var(--white) 0%, #f5f7fa 100%) !important;
        background-image: none !important;
        box-shadow: 2px 0 20px rgba(0, 0, 0, 0.1);
        border-right: 2px solid var(--primary-color);
    }

    /* Logo Brand */
    .brand-link {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)) !important;
        border-bottom: none !important;
        color: var(--white) !important;
        font-weight: 700 !important;
        padding: 18px 15px !important;
        transition: all 0.3s ease;
        box-shadow: 0 6px 20px rgba(44, 90, 160, 0.25);
        text-align: center;
    }
    .brand-link:hover {
        background: linear-gradient(135deg, var(--primary-dark), #0f2847) !important;
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(44, 90, 160, 0.35);
    }
    .brand-text {
        color: var(--white) !important;
        font-weight: 800 !important;
        letter-spacing: 1.5px;
        font-size: 1.2rem;
        text-transform: uppercase;
    }

    /* Painel do Usuário */
    .user-panel {
        border-bottom: 2px solid var(--gray-border) !important;
        margin: 15px;
        padding: 15px;
        background: linear-gradient(135deg, rgba(44, 90, 160, 0.05), rgba(78, 205, 196, 0.05));
        border-radius: 12px;
        transition: all 0.3s ease;
        box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.02);
    }
    .user-panel:hover {
        background: linear-gradient(135deg, rgba(44, 90, 160, 0.12), rgba(78, 205, 196, 0.12));
        box-shadow: var(--shadow-glow);
        transform: translateY(-2px);
    }
    .user-panel .info a {
        color: var(--text-dark) !important;
        font-weight: 700;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    .user-panel .info a:hover {
        color: var(--primary-color) !important;
    }
    
    /* Avatar */
    .user-panel .image img {
        border: 3px solid var(--primary-color);
        box-shadow: var(--shadow-medium);
        width: 48px;
        height: 48px;
        padding: 2px;
        background: var(--white);
        transition: all 0.3s ease;
    }
    .user-panel:hover .image img {
        border-color: var(--secondary-color);
        transform: scale(1.08);
    }

    /* Headers do Menu */
    .nav-header {
        background: linear-gradient(90deg, var(--primary-color), var(--primary-dark)) !important;
        color: white !important;
        font-size: 0.75rem !important;
        font-weight: 800;
        padding: 12px 16px !important;
        margin: 15px 8px 8px 8px !important;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        box-shadow: 0 4px 10px rgba(44, 90, 160, 0.15);
    }

    /* Links de Navegação */
    .nav-sidebar .nav-item {
        margin-bottom: 4px;
    }

    .nav-sidebar .nav-link {
        color: var(--text-dark) !important;
        border-radius: 10px;
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
        padding: 12px 15px;
        margin: 0 8px;
        display: flex;
        align-items: center;
        position: relative;
        font-weight: 500;
    }
    
    .nav-sidebar .nav-link i {
        color: var(--text-light);
        margin-right: 12px;
        font-size: 1.1rem;
        width: 20px;
        text-align: center;
        transition: all 0.3s ease;
    }

    /* Hover State */
    .nav-sidebar .nav-link:hover {
        background: linear-gradient(90deg, rgba(44, 90, 160, 0.1), rgba(78, 205, 196, 0.1));
        color: var(--primary-color) !important;
        border-left: 4px solid var(--primary-color);
        transform: translateX(4px);
    }
    .nav-sidebar .nav-link:hover i {
        color: var(--primary-color);
        transform: scale(1.15);
    }

    /* Estado Ativo */
    .nav-sidebar > .nav-item > .nav-link.active {
        background: linear-gradient(90deg, rgba(44, 90, 160, 0.15), rgba(78, 205, 196, 0.12)) !important;
        color: var(--primary-dark) !important;
        border-left: 4px solid var(--accent-color);
        font-weight: 700;
        box-shadow: inset 0 2px 8px rgba(44, 90, 160, 0.1);
    }

    .nav-sidebar > .nav-item > .nav-link.active i {
        color: var(--primary-color) !important;
    }

    /* --- NAVBAR SUPERIOR --- */
    .main-header {
        background: linear-gradient(90deg, var(--white) 0%, #f8f9fa 100%) !important;
        border-bottom: 2px solid var(--primary-color) !important;
        box-shadow: var(--shadow-soft);
    }
    
    /* Ícones da Navbar */
    .main-header .nav-link {
        color: var(--text-light) !important;
        transition: all 0.3s ease;
        position: relative;
        font-weight: 600;
    }
    .main-header .nav-link:hover {
        color: var(--primary-color) !important;
        transform: translateY(-2px);
    }
    
    /* Menu Dropdown */
    .dropdown-menu {
        background-color: var(--white);
        border: 1px solid var(--gray-border);
        box-shadow: var(--shadow-medium);
        border-radius: 12px;
        overflow: hidden;
    }
    .dropdown-item {
        color: var(--text-dark) !important;
        transition: all 0.3s ease;
        border-radius: 6px;
        margin: 4px 8px;
        padding: 10px 15px !important;
    }
    .dropdown-item:hover {
        background: linear-gradient(90deg, rgba(44, 90, 160, 0.1), rgba(78, 205, 196, 0.1));
        color: var(--primary-color) !important;
        transform: translateX(4px);
    }
    .dropdown-divider {
        border-top-color: var(--gray-border);
        margin: 6px 0;
    }
    
    .dropdown-header {
        color: var(--primary-dark) !important;
        font-weight: 700;
        font-size: 0.95rem;
        padding: 12px 15px !important;
        background: linear-gradient(90deg, rgba(44, 90, 160, 0.05), rgba(78, 205, 196, 0.05));
    }

    /* Conteúdo Principal */
    .content-wrapper {
        background: linear-gradient(135deg, var(--gray-light) 0%, #ffffff 100%) !important;
    }

    /* Content Header */
    .content-header {
        background: var(--white);
        border-bottom: 2px solid var(--gray-border);
        padding: 20px 0;
    }

    .content-header .breadcrumb {
        background: transparent !important;
        padding: 10px 0;
    }
    
    .content-header .breadcrumb-item.active {
        color: var(--text-light);
        font-weight: 600;
    }
    
    .content-header .breadcrumb-item a {
        color: var(--primary-color);
        transition: all 0.3s ease;
    }
    
    .content-header .breadcrumb-item a:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    /* Cards */
    .card {
        border: 1px solid var(--gray-border) !important;
        box-shadow: var(--shadow-soft) !important;
        border-radius: 15px !important;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .card:hover {
        box-shadow: var(--shadow-medium) !important;
        transform: translateY(-5px);
    }
    
    .card-header {
        background: linear-gradient(90deg, var(--primary-color), var(--primary-dark)) !important;
        color: var(--white) !important;
        border: none !important;
        font-weight: 700;
        border-radius: 15px 15px 0 0 !important;
        padding: 18px 20px !important;
    }

    .card-body {
        padding: 20px !important;
    }

    /* Buttons */
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)) !important;
        border: none !important;
        transition: all 0.3s ease;
        border-radius: 8px;
        font-weight: 600;
        padding: 10px 20px;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(44, 90, 160, 0.3) !important;
    }

    .btn-secondary {
        background: linear-gradient(135deg, var(--secondary-color), #3AB7AE) !important;
        border: none !important;
        color: var(--white) !important;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .brand-text {
            font-size: 1rem;
        }
        
        .user-panel {
            margin: 10px;
            padding: 12px;
        }

        .nav-header {
            font-size: 0.7rem;
        }
    }

    /* --- ESTILO DO CONTEÚDO PRINCIPAL (HOME) --- */
    .content-wrapper {
        background: linear-gradient(135deg, #f5f7fa 0%, #ffffff 50%, #f0f4f8 100%) !important;
    }

    /* Container Principal com Efeitos */
    .main-content {
        position: relative;
        min-height: 100vh;
        padding: 30px;
    }

    /* Formas animadas de fundo */
    .animated-bg-shapes {
        position: fixed;
        top: 0;
        left: 260px;
        right: 0;
        bottom: 0;
        z-index: 0;
        overflow: hidden;
        pointer-events: none;
    }

    .shape {
        position: absolute;
        opacity: 0.08;
        border-radius: 50%;
        animation: float 8s ease-in-out infinite;
    }

    .shape-1 {
        width: 300px;
        height: 300px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        top: -100px;
        right: -100px;
        animation-delay: 0s;
    }

    .shape-2 {
        width: 250px;
        height: 250px;
        background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
        bottom: -50px;
        left: 10%;
        animation-delay: 2s;
    }

    .shape-3 {
        width: 200px;
        height: 200px;
        background: linear-gradient(135deg, var(--accent-color), var(--primary-color));
        top: 50%;
        right: 5%;
        animation-delay: 4s;
    }

    .shape-4 {
        width: 280px;
        height: 280px;
        background: linear-gradient(135deg, var(--primary-dark), var(--secondary-color));
        top: 30%;
        left: 5%;
        animation-delay: 3s;
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0) rotate(0deg);
        }
        25% {
            transform: translateY(30px) rotate(90deg);
        }
        50% {
            transform: translateY(60px) rotate(180deg);
        }
        75% {
            transform: translateY(30px) rotate(270deg);
        }
    }

    /* Conteúdo Principal */
    .dashboard-content {
        position: relative;
        z-index: 1;
    }

    /* Header do Dashboard */
    .dashboard-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: var(--white);
        padding: 40px 30px;
        border-radius: 20px;
        margin-bottom: 40px;
        box-shadow: 0 15px 40px rgba(44, 90, 160, 0.2);
        position: relative;
        overflow: hidden;
    }

    .dashboard-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 20px 20px;
        animation: moveGrid 20s linear infinite;
    }

    @keyframes moveGrid {
        0% {
            transform: translate(0, 0);
        }
        100% {
            transform: translate(20px, 20px);
        }
    }

    .dashboard-header-content {
        position: relative;
        z-index: 2;
    }

    .dashboard-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
        letter-spacing: -0.5px;
    }

    .dashboard-subtitle {
        font-size: 1.1rem;
        opacity: 0.95;
        font-weight: 300;
        letter-spacing: 0.5px;
    }

    .dashboard-icon {
        font-size: 4rem;
        position: absolute;
        right: 40px;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0.2;
    }

    /* Cards do Dashboard */
    .dashboard-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
    }

    .dashboard-card {
        background: linear-gradient(135deg, var(--white), #f9fafb);
        border-radius: 18px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.5);
    }

    .dashboard-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(44, 90, 160, 0.1), transparent);
        border-radius: 50%;
        transition: all 0.4s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px rgba(44, 90, 160, 0.15);
    }

    .dashboard-card:hover::before {
        top: 10%;
        right: 10%;
    }

    .card-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: var(--white);
        margin-bottom: 20px;
        box-shadow: 0 10px 25px rgba(44, 90, 160, 0.2);
    }

    .card-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 10px;
    }

    .card-description {
        color: var(--text-light);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .card-button {
        display: inline-block;
        padding: 12px 24px;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: var(--white);
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .card-button:hover {
        transform: translateX(4px);
        box-shadow: 0 10px 25px rgba(44, 90, 160, 0.3);
    }

    /* Section com stats */
    .stats-section {
        background: var(--white);
        border-radius: 18px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        margin-bottom: 40px;
    }

    .stats-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 30px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .stat-item {
        text-align: center;
        padding: 20px;
        border-radius: 15px;
        background: linear-gradient(135deg, rgba(44, 90, 160, 0.05), rgba(78, 205, 196, 0.05));
        transition: all 0.3s ease;
    }

    .stat-item:hover {
        background: linear-gradient(135deg, rgba(44, 90, 160, 0.1), rgba(78, 205, 196, 0.1));
        transform: translateY(-5px);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary-color);
        margin-bottom: 10px;
    }

    .stat-label {
        color: var(--text-light);
        font-size: 0.95rem;
        font-weight: 600;
    }

  </style>

</head>
<!-- Mudança da classe: 'sidebar-dark-primary' para 'sidebar-light-primary' para o tema claro -->
<body class="hold-transition sidebar-mini layout-fixed sidebar-light-primary">
<div class="wrapper">
  
  <!-- Navbar Superior -->
  <nav class="main-header navbar navbar-expand navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button" title="Alternar Menu">
          <i class="fas fa-bars"></i>
        </a>
      </li>
      <li class="nav-item">
        <a href="../loja/index.php" class="nav-link" title="Acessar Loja Pública">
          <i class="fas fa-store mr-2" style="color: var(--primary-color);"></i>
          <span style="color: var(--text-dark); font-weight: 600;">Loja</span>
        </a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Perfil Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" title="Perfil e Saída">
          <div class="d-flex align-items-center" style="gap: 10px;">
            <i class="fas fa-user-circle" style="font-size: 1.8rem; color: var(--primary-color);"></i>
            <span style="color: var(--text-dark); font-weight: 600; font-size: 0.9rem;">
              <?php echo htmlspecialchars(substr($nome_user, 0, 12)); ?>
            </span>
          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header text-center font-weight-bold">
            <i class="fas fa-user-circle mr-2" style="color: var(--primary-color);"></i>Minha Conta
          </span>
          <div class="dropdown-divider"></div>
          
          <a href="home.php?acao=perfil" class="dropdown-item">
            <i class="fas fa-user-cog mr-2" style="color: var(--primary-color);"></i> Meu Perfil
          </a>

          <a href="../loja/index.php" class="dropdown-item">
            <i class="fas fa-store mr-2" style="color: var(--secondary-color);"></i> Minha Loja
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

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar elevation-4">
    <!-- Brand Logo -->
    <a href="home.php?acao=bemvindo" class="brand-link">
      <i class="fas fa-shopping-basket mr-2" style="font-size: 1.3rem;"></i>
      <span class="brand-text">MERCADO EXPRESS</span>
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
                echo '<img src="../img/user/' . htmlspecialchars($foto_user) . '" class="img-circle elevation-2" alt="User Image">';
            }
          ?>
        </div>
        <div class="info">
          <a href="#" class="d-block text-truncate" style="max-width: 150px;">
            <i class="fas fa-user-circle mr-1" style="color: var(--primary-color);"></i>
            <?php echo htmlspecialchars($nome_user); ?>
          </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          <!-- Headers de Menu -->
          <li class="nav-header">
            <i class="fas fa-home mr-2"></i>MÓDULOS
          </li>

          <li class="nav-item">
            <a href="home.php?acao=dashboard" class="nav-link <?= $active_principal ?>">
              <i class="nav-icon fas fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="home.php?acao=cadastrar_produto" class="nav-link <?= ($acao_get === 'cadastrar_produto') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-box-open"></i>
              <p>Cadastrar Produto</p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="home.php?acao=relatorio" class="nav-link <?= $active_relatorio ?>">
              <i class="nav-icon fas fa-chart-bar"></i> 
              <p>Relatórios</p>
            </a>
          </li>

          <li class="nav-header">
            <i class="fas fa-user mr-2"></i>CONTA
          </li>

          <li class="nav-item">
            <a href="home.php?acao=perfil" class="nav-link <?= $active_perfil ?>">
              <i class="nav-icon fas fa-user-cog"></i> 
              <p>Meu Perfil</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="home.php?sair=true" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i> 
              <p>Sair do Sistema</p>
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