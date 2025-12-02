<?php include('../config/conexao.php'); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mercado Express | Catálogo de Produtos</title>
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">

<style>
  :root {
    --primary-color: #2C5AA0;
    --primary-dark: #1E3F73;
    --secondary-color: #4ECDC4;
    --accent-color: #FFD166;
    --text-dark: #2D3047;
    --text-light: #6C757D;
    --success-color: #06D6A0;
    --white: #ffffff;
    --gray-light: #F8F9FA;
  }

  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  /* FUNDO IDÊNTICO AO CADASTRO */
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: 
      linear-gradient(135deg, 
        rgba(44, 90, 160, 0.9) 0%, 
        rgba(30, 63, 115, 0.85) 50%,
        rgba(78, 205, 196, 0.8) 100%),
      url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="%232C5AA0"/><path d="M30,30 L70,30 L70,70 L30,70 Z" fill="none" stroke="%234ECDC4" stroke-width="2"/><circle cx="50" cy="50" r="15" fill="none" stroke="%23FFD166" stroke-width="2"/></svg>');
    background-size: cover, 200px;
    min-height: 100vh;
    position: relative;
    overflow-x: hidden;
  }

  /* Efeitos animados de fundo */
  .background-animation {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    overflow: hidden;
  }

  .floating-shapes {
    position: absolute;
    width: 100%;
    height: 100%;
  }

  .shape {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: float 20s infinite linear;
  }

  .shape:nth-child(1) {
    width: 80px;
    height: 80px;
    top: 10%;
    left: 10%;
    animation-delay: 0s;
    background: rgba(255, 209, 102, 0.2);
  }

  .shape:nth-child(2) {
    width: 120px;
    height: 120px;
    top: 70%;
    left: 80%;
    animation-delay: -5s;
    background: rgba(78, 205, 196, 0.2);
  }

  .shape:nth-child(3) {
    width: 60px;
    height: 60px;
    top: 50%;
    left: 20%;
    animation-delay: -10s;
    background: rgba(255, 255, 255, 0.15);
  }

  .shape:nth-child(4) {
    width: 100px;
    height: 100px;
    top: 20%;
    left: 70%;
    animation-delay: -15s;
    background: rgba(6, 214, 160, 0.2);
  }

  @keyframes float {
    0%, 100% {
      transform: translate(0, 0) rotate(0deg);
    }
    25% {
      transform: translate(20px, 20px) rotate(90deg);
    }
    50% {
      transform: translate(0, 40px) rotate(180deg);
    }
    75% {
      transform: translate(-20px, 20px) rotate(270deg);
    }
  }

  /* Header - Estilo similar ao do cadastro */
  header {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    padding: 15px 0;
    box-shadow: 
      0 10px 30px rgba(0, 0, 0, 0.3),
      0 5px 15px rgba(0, 0, 0, 0.2);
    position: sticky;
    top: 0;
    z-index: 1000;
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.3);
  }

  .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
  }

  .header-content {
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
  }

  .logo {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--white);
    text-shadow: 0 4px 15px rgba(0,0,0,0.3);
  }

  .logo i {
    font-size: 2.5rem;
    background: linear-gradient(45deg, var(--white), var(--accent-color));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  /* Main Content Container */
  .main-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
    position: relative;
    z-index: 2;
    animation: slideUp 1s ease-out 0.5s both;
  }

  @keyframes slideUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Card de filtro de categorias */
  .categories-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    box-shadow: 
      0 20px 50px rgba(0, 0, 0, 0.25),
      0 5px 15px rgba(0, 0, 0, 0.2),
      inset 0 1px 0 rgba(255, 255, 255, 0.4);
    overflow: hidden;
    margin-bottom: 30px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    transition: all 0.4s ease;
  }

  .categories-card:hover {
    transform: translateY(-5px);
    box-shadow: 
      0 25px 60px rgba(0, 0, 0, 0.35),
      0 8px 20px rgba(0, 0, 0, 0.25),
      inset 0 1px 0 rgba(255, 255, 255, 0.4);
  }

  .card-header-filter {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    padding: 20px 30px;
    position: relative;
    overflow: hidden;
    cursor: pointer;
  }

  .card-header-filter::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
    animation: shine 3s infinite;
  }

  .card-header-filter::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, var(--secondary-color), var(--accent-color));
  }

  .filter-toggle {
    display: flex;
    align-items: center;
    gap: 15px;
    font-size: 1.2rem;
    font-weight: 700;
    position: relative;
    z-index: 1;
  }

  .filter-toggle i {
    transition: transform 0.3s ease;
  }

  .filter-toggle.active i {
    transform: rotate(180deg);
  }

  /* Conteúdo das categorias */
  .categories-content {
    padding: 25px;
    display: none;
  }

  .categories-content.active {
    display: block;
    animation: fadeIn 0.5s ease;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Campo de busca */
  .search-box {
    position: relative;
    margin-bottom: 20px;
  }

  .search-box input {
    width: 100%;
    padding: 15px 20px 15px 50px;
    border: 2px solid rgba(232, 237, 242, 0.8);
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: rgba(248, 249, 250, 0.8);
    font-weight: 500;
    color: var(--text-dark);
    backdrop-filter: blur(10px);
  }

  .search-box input:focus {
    outline: none;
    border-color: var(--primary-color);
    background: rgba(255, 255, 255, 0.9);
    box-shadow: 0 0 0 4px rgba(44, 90, 160, 0.15);
    transform: translateY(-2px);
  }

  .search-icon {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary-color);
    font-size: 1.1rem;
    transition: all 0.3s ease;
  }

  .search-box:focus-within .search-icon {
    color: var(--primary-dark);
    transform: translateY(-50%) scale(1.1);
  }

  /* Grid de categorias com imagens */
  .categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
    gap: 25px;
    max-height: 400px;
    overflow-y: auto;
    padding: 15px;
  }

  .category-card {
    background: rgba(248, 249, 250, 0.8);
    border: 2px solid transparent;
    border-radius: 15px;
    padding: 0;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 200px;
  }

  .category-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 15px 35px rgba(44, 90, 160, 0.25);
    border-color: var(--primary-color);
    background: rgba(255, 255, 255, 0.95);
  }

  .category-card.active {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    border-color: var(--primary-color);
    box-shadow: 0 15px 35px rgba(44, 90, 160, 0.3);
  }

  .category-card.active .category-name {
    background: rgba(0, 0, 0, 0.7);
    color: white;
  }

  .category-image {
    width: 100%;
    height: 140px;
    object-fit: cover;
    transition: transform 0.5s ease;
  }

  .category-card:hover .category-image {
    transform: scale(1.1);
  }

  .category-name {
    padding: 15px 10px;
    font-weight: 700;
    font-size: 0.95rem;
    background: rgba(255, 255, 255, 0.95);
    flex-grow: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    transition: all 0.3s ease;
  }

  /* Card principal de conteúdo */
  .content-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    box-shadow: 
      0 20px 50px rgba(0, 0, 0, 0.25),
      0 5px 15px rgba(0, 0, 0, 0.2),
      inset 0 1px 0 rgba(255, 255, 255, 0.4);
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.3);
    margin-bottom: 30px;
  }

  .card-header-content {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    padding: 25px 30px;
    position: relative;
    overflow: hidden;
  }

  .card-header-content::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
    animation: shine 3s infinite;
  }

  .card-header-content::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, var(--secondary-color), var(--accent-color));
  }

  .page-title {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 8px;
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 15px;
  }

  .page-subtitle {
    opacity: 0.9;
    font-size: 1rem;
    font-weight: 400;
    position: relative;
    z-index: 1;
  }

  /* Filtros de busca */
  .search-filter {
    display: flex;
    gap: 15px;
    margin: 25px 0;
    padding: 0 30px;
  }

  .filter-btn {
    background: linear-gradient(135deg, var(--secondary-color), #3AB7AE);
    color: white;
    border: none;
    padding: 15px 25px;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 10px;
    white-space: nowrap;
  }

  .filter-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(78, 205, 196, 0.4);
  }

  /* Grid de produtos */
  .products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
    padding: 30px;
  }

  .product-card {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(20px);
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 
      0 15px 35px rgba(0, 0, 0, 0.1),
      0 5px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.4s ease;
    border: 1px solid rgba(255, 255, 255, 0.3);
    display: flex;
    flex-direction: column;
  }

  .product-card:hover {
    transform: translateY(-10px);
    box-shadow: 
      0 25px 50px rgba(0, 0, 0, 0.2),
      0 10px 20px rgba(0, 0, 0, 0.15);
  }

  .product-image-container {
    height: 200px;
    width: 100%;
    position: relative;
    overflow: hidden;
  }

  /* Estilo para imagem padrão - PAREDE DE CIMENTO BRANCO */
  .default-image-container {
    width: 100%;
    height: 100%;
    background-image: url('https://img.freepik.com/fotos-gratis/parede-de-cimento-branco_53876-88673.jpg?w=900&t=st=1708012345~exp=1708012945~hmac=9a3b6493e697444ea044b4f66654c0d7a2b5d5c5c8e8c8c8c8c8c8c8c8c8c8c8');
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .default-image-overlay {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 20px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 10px;
    margin: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  }

  .default-image-overlay i {
    font-size: 3rem;
    color: var(--primary-color);
    margin-bottom: 15px;
  }

  .default-image-text {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-dark);
    line-height: 1.4;
    max-width: 200px;
  }

  .product-image {
    height: 100%;
    width: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
  }

  .product-card:hover .product-image {
    transform: scale(1.05);
  }

  .product-info {
    padding: 25px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
  }

  .product-name {
    font-weight: 700;
    font-size: 1.2rem;
    margin-bottom: 10px;
    color: var(--text-dark);
    line-height: 1.4;
  }

  .product-description {
    color: var(--text-light);
    font-size: 0.95rem;
    margin-bottom: 20px;
    flex-grow: 1;
    line-height: 1.6;
  }

  .product-price {
    font-weight: 800;
    font-size: 1.5rem;
    color: var(--primary-color);
    margin-bottom: 15px;
    text-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }

  .product-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
  }

  .stock-badge {
    padding: 8px 16px;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .stock-in {
    background: rgba(6, 214, 160, 0.15);
    color: var(--success-color);
    border: 2px solid var(--success-color);
  }

  .stock-low {
    background: rgba(255, 209, 102, 0.15);
    color: var(--accent-color);
    border: 2px solid var(--accent-color);
  }

  .stock-out {
    background: rgba(220, 53, 69, 0.15);
    color: #dc3545;
    border: 2px solid #dc3545;
  }

  /* Modal de filtros */
  .filter-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    z-index: 2000;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(5px);
    animation: fadeIn 0.3s ease;
  }

  .filter-modal-content {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 30px;
    width: 90%;
    max-width: 500px;
    box-shadow: 
      0 30px 60px rgba(0, 0, 0, 0.4),
      0 10px 20px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.3);
    animation: slideUp 0.4s ease;
  }

  .filter-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
  }

  .filter-modal-title {
    font-size: 1.5rem;
    color: var(--text-dark);
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 700;
  }

  .close-modal {
    background: rgba(0, 0, 0, 0.1);
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    font-size: 1.5rem;
    color: var(--text-dark);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
  }

  .close-modal:hover {
    background: var(--primary-color);
    color: white;
    transform: rotate(90deg);
  }

  .filter-section {
    margin-bottom: 25px;
  }

  .filter-section h4 {
    color: var(--text-dark);
    margin-bottom: 15px;
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .filter-options {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  .filter-option {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 12px 15px;
    background: rgba(248, 249, 250, 0.8);
    border-radius: 10px;
    transition: all 0.3s ease;
    cursor: pointer;
  }

  .filter-option:hover {
    background: rgba(44, 90, 160, 0.1);
    transform: translateX(5px);
  }

  .filter-option input {
    width: 20px;
    height: 20px;
    cursor: pointer;
  }

  .filter-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
  }

  .btn {
    padding: 15px 25px;
    border: none;
    border-radius: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    flex: 1;
    text-align: center;
    font-size: 1rem;
  }

  .btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
  }

  .btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(44, 90, 160, 0.4);
  }

  .btn-secondary {
    background: rgba(248, 249, 250, 0.9);
    color: var(--text-dark);
    border: 2px solid rgba(44, 90, 160, 0.3);
  }

  .btn-secondary:hover {
    background: rgba(44, 90, 160, 0.1);
    transform: translateY(-3px);
  }

  /* Estado vazio */
  .empty-state {
    text-align: center;
    padding: 60px 30px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    grid-column: 1 / -1;
    backdrop-filter: blur(10px);
  }

  .empty-state i {
    font-size: 4rem;
    color: var(--primary-color);
    margin-bottom: 20px;
    opacity: 0.7;
  }

  .empty-state h3 {
    color: var(--text-dark);
    margin-bottom: 10px;
    font-size: 1.5rem;
  }

  .empty-state p {
    color: var(--text-light);
    margin-bottom: 25px;
    font-size: 1rem;
  }

  .btn-success {
    background: linear-gradient(135deg, var(--success-color), #05C593);
    color: white;
    padding: 12px 30px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 700;
    display: inline-block;
    transition: all 0.3s ease;
  }

  .btn-success:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(6, 214, 160, 0.4);
  }

  /* Responsividade */
  @media (max-width: 768px) {
    .search-filter {
      flex-direction: column;
    }
    
    .header-content {
      flex-direction: column;
      gap: 15px;
      text-align: center;
    }
    
    .products-grid {
      grid-template-columns: 1fr;
      padding: 20px;
    }
    
    .categories-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 15px;
    }
    
    .category-card {
      height: 180px;
    }
    
    .category-image {
      height: 120px;
    }
    
    .card-header-content,
    .categories-content,
    .search-filter {
      padding: 20px;
    }
    
    .product-info {
      padding: 20px;
    }
    
    .default-image-overlay {
      padding: 15px;
      margin: 15px;
    }
    
    .default-image-overlay i {
      font-size: 2.5rem;
      margin-bottom: 10px;
    }
    
    .default-image-text {
      font-size: 0.9rem;
    }
  }

  @media (max-width: 480px) {
    .categories-grid {
      grid-template-columns: 1fr;
    }
    
    .products-grid {
      grid-template-columns: 1fr;
    }
    
    .filter-modal-content {
      padding: 20px;
      width: 95%;
    }
    
    .filter-actions {
      flex-direction: column;
    }
    
    .category-card {
      height: 160px;
    }
    
    .category-image {
      height: 100px;
    }
    
    .default-image-overlay {
      padding: 10px;
      margin: 10px;
    }
    
    .default-image-overlay i {
      font-size: 2rem;
    }
    
    .default-image-text {
      font-size: 0.85rem;
    }
  }

  /* Scrollbar personalizada */
  ::-webkit-scrollbar {
    width: 8px;
  }

  ::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
  }

  ::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 10px;
  }

  ::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--secondary-color));
  }
</style>
</head>
<body>

  <!-- Animações de fundo -->
  <div class="background-animation">
    <div class="floating-shapes">
      <div class="shape"></div>
      <div class="shape"></div>
      <div class="shape"></div>
      <div class="shape"></div>
    </div>
  </div>

  <header>
    <div class="container">
      <div class="header-content">
        <div class="logo">
          <i class="fas fa-shopping-basket"></i>
          <span>MERCADO EXPRESS</span>
        </div>
      </div>
    </div>
  </header>

  <div class="main-container">
    <!-- Filtro de Categorias Recolhível -->
    <div class="categories-card">
      <div class="card-header-filter" id="categoryToggle">
        <div class="filter-toggle">
          <i class="fas fa-chevron-down"></i>
          <span>Filtrar por Categoria</span>
        </div>
      </div>
      
      <div class="categories-content" id="categoriesContent">
        <div class="search-box">
          <i class="fas fa-search search-icon"></i>
          <input type="text" placeholder="Buscar categorias..." id="categorySearch">
        </div>
        
        <div class="categories-grid" id="categoriesGrid">
          <!-- Todas as Categorias -->
          <div class="category-card <?php echo !isset($_GET['cat']) ? 'active' : ''; ?>" data-category="all">
            <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Todos os produtos" class="category-image">
            <div class="category-name">Todos os Produtos</div>
          </div>
          
          <?php
          try {
            $cats = $conect->query("SELECT * FROM tb_categorias ORDER BY nome_categoria");
            
            while($c = $cats->fetch(PDO::FETCH_OBJ)){
              $ativo = (isset($_GET['cat']) && $_GET['cat'] == $c->id_categoria) ? 'active' : '';
              $nome_categoria = strtolower($c->nome_categoria);
              $imagem_categoria = '';
              
              // Definir imagem baseada no nome da categoria
              if (strpos($nome_categoria, 'fruta') !== false || strpos($nome_categoria, 'verdura') !== false) {
                $imagem_categoria = 'https://images.unsplash.com/photo-1610832958506-aa56368176cf?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';
              } elseif (strpos($nome_categoria, 'bebida') !== false) {
                $imagem_categoria = 'https://images.unsplash.com/photo-1551024709-8f23befc6f87?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';
              } elseif (strpos($nome_categoria, 'padaria') !== false || strpos($nome_categoria, 'pão') !== false) {
                $imagem_categoria = 'https://images.unsplash.com/photo-1509440159596-0249088772ff?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';
              } elseif (strpos($nome_categoria, 'laticínio') !== false || strpos($nome_categoria, 'leite') !== false || strpos($nome_categoria, 'queijo') !== false) {
                $imagem_categoria = 'https://images.unsplash.com/photo-1488462237308-ecaa28b729d7?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';
              } elseif (strpos($nome_categoria, 'carne') !== false || strpos($nome_categoria, 'frango') !== false || strpos($nome_categoria, 'bovino') !== false) {
                $imagem_categoria = 'https://images.unsplash.com/photo-1602476526386-6bbf6a3e3c7e?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';
              } elseif (strpos($nome_categoria, 'limpeza') !== false) {
                $imagem_categoria = 'https://images.unsplash.com/photo-1583947581924-860bda6a26df?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';
              } elseif (strpos($nome_categoria, 'pet') !== false) {
                $imagem_categoria = 'https://images.unsplash.com/photo-1518717758536-85ae29035b6d?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';
              } elseif (strpos($nome_categoria, 'hortifruti') !== false) {
                $imagem_categoria = 'https://images.unsplash.com/photo-1540420773420-3366772f4999?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';
              } elseif (strpos($nome_categoria, 'eletrodoméstico') !== false || strpos($nome_categoria, 'eletro') !== false) {
                $imagem_categoria = 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';
              } elseif (strpos($nome_categoria, 'higiene') !== false || strpos($nome_categoria, 'beleza') !== false) {
                $imagem_categoria = 'https://images.unsplash.com/photo-1522338242990-e8c0f8f9d43a?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';
              } elseif (strpos($nome_categoria, 'congelado') !== false || strpos($nome_categoria, 'frio') !== false) {
                $imagem_categoria = 'https://images.unsplash.com/photo-1571175443880-49e1d1b7b3c3?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';
              } elseif (strpos($nome_categoria, 'enlatado') !== false || strpos($nome_categoria, 'conserva') !== false) {
                $imagem_categoria = 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';
              } elseif (strpos($nome_categoria, 'grão') !== false || strpos($nome_categoria, 'cereal') !== false) {
                $imagem_categoria = 'https://images.unsplash.com/photo-1592921870789-04563d55041c?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';
              } elseif (strpos($nome_categoria, 'biscoito') !== false || strpos($nome_categoria, 'snack') !== false) {
                $imagem_categoria = 'https://images.unsplash.com/photo-1598983871855-7d6c17c24d0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';
              } elseif (strpos($nome_categoria, 'roupa') !== false || strpos($nome_categoria, 'vestuário') !== false) {
                $imagem_categoria = 'https://images.unsplash.com/photo-1445205170230-053b83016050?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';
              } elseif (strpos($nome_categoria, 'material') !== false || strpos($nome_categoria, 'escritório') !== false) {
                $imagem_categoria = 'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';
              } elseif (strpos($nome_categoria, 'brinquedo') !== false) {
                $imagem_categoria = 'https://images.unsplash.com/photo-1596461404969-9ae70f2830c1?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';
              } elseif (strpos($nome_categoria, 'automotivo') !== false) {
                $imagem_categoria = 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';
              } elseif (strpos($nome_categoria, 'construção') !== false) {
                $imagem_categoria = 'https://images.unsplash.com/photo-1503387769-00a112127ca0?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';
              } elseif (strpos($nome_categoria, 'farmácia') !== false || strpos($nome_categoria, 'medicamento') !== false) {
                $imagem_categoria = 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';
              } elseif (strpos($nome_categoria, 'pescado') !== false || strpos($nome_categoria, 'peixe') !== false) {
                $imagem_categoria = 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';
              } else {
                // Imagem padrão para categorias não mapeadas - usando a parede de cimento
                $imagem_categoria = 'https://img.freepik.com/fotos-gratis/parede-de-cimento-branco_53876-88673.jpg?w=900&t=st=1708012345~exp=1708012945~hmac=9a3b6493e697444ea044b4f66654c0d7a2b5d5c5c8e8c8c8c8c8c8c8c8c8c8c8';
              }
              
              echo "<div class='category-card $ativo' data-category='{$c->id_categoria}'>
                      <img src='$imagem_categoria' alt='{$c->nome_categoria}' class='category-image'>
                      <div class='category-name'>{$c->nome_categoria}</div>
                    </div>";
            }
          } catch (PDOException $e) {
            echo "<div class='category-card'>
                    <img src='https://img.freepik.com/fotos-gratis/parede-de-cimento-branco_53876-88673.jpg?w=900&t=st=1708012345~exp=1708012945~hmac=9a3b6493e697444ea044b4f66654c0d7a2b5d5c5c8e8c8c8c8c8c8c8c8c8c8c8' alt='Erro' class='category-image'>
                    <div class='category-name'>Erro ao carregar</div>
                  </div>";
          }
          ?>
        </div>
      </div>
    </div>

    <!-- Área de Conteúdo -->
    <div class="content-card">
      <div class="card-header-content">
        <h1 class="page-title">
          <i class="fas fa-boxes"></i>
          <span>
            <?php 
            if(isset($_GET['cat'])){
              $id = (int)$_GET['cat'];
              $nome = $conect->prepare("SELECT nome_categoria FROM tb_categorias WHERE id_categoria = ?");
              $nome->execute([$id]);
              echo $nome->fetchColumn() ?: "Produtos";
            } else {
              echo "Todos os Produtos";
            }
            ?>
          </span>
        </h1>
        <p class="page-subtitle">Encontre os melhores produtos com os melhores preços</p>
      </div>

      <div class="search-filter">
        <div class="search-box">
          <i class="fas fa-search search-icon"></i>
          <input type="text" placeholder="Buscar produtos..." id="productSearch">
        </div>
        <div class="filter-btn" id="openFilterModal">
          <i class="fas fa-filter"></i>
          <span>Mais Filtros</span>
        </div>
      </div>

      <!-- Modal de Filtros Avançados -->
      <div class="filter-modal" id="filterModal">
        <div class="filter-modal-content">
          <div class="filter-modal-header">
            <h3 class="filter-modal-title">
              <i class="fas fa-filter"></i>
              Filtros Avançados
            </h3>
            <button class="close-modal" id="closeFilterModal">&times;</button>
          </div>
          
          <div class="filter-section">
            <h4><i class="fas fa-tags"></i> Preço</h4>
            <div class="filter-options">
              <label class="filter-option">
                <input type="checkbox" name="price" value="0-50">
                <span>Até R$ 50,00</span>
              </label>
              <label class="filter-option">
                <input type="checkbox" name="price" value="50-100">
                <span>R$ 50,00 - R$ 100,00</span>
              </label>
              <label class="filter-option">
                <input type="checkbox" name="price" value="100-200">
                <span>R$ 100,00 - R$ 200,00</span>
              </label>
              <label class="filter-option">
                <input type="checkbox" name="price" value="200+">
                <span>Acima de R$ 200,00</span>
              </label>
            </div>
          </div>
          
          <div class="filter-section">
            <h4><i class="fas fa-box-open"></i> Disponibilidade</h4>
            <div class="filter-options">
              <label class="filter-option">
                <input type="checkbox" name="stock" value="in-stock">
                <span>Em Estoque</span>
              </label>
              <label class="filter-option">
                <input type="checkbox" name="stock" value="low-stock">
                <span>Estoque Baixo</span>
              </label>
              <label class="filter-option">
                <input type="checkbox" name="stock" value="out-of-stock">
                <span>Sem Estoque</span>
              </label>
            </div>
          </div>
          
          <div class="filter-actions">
            <button class="btn btn-secondary" id="clearFilters">Limpar</button>
            <button class="btn btn-primary" id="applyFilters">Aplicar Filtros</button>
          </div>
        </div>
      </div>

      <div class="products-grid">
        <?php
        try {
          // Query para buscar produtos
          $sql = "SELECT p.*, c.nome_categoria, COALESCE(v.estoque_atual, 0) as estoque 
                  FROM tb_produtos p 
                  LEFT JOIN tb_categorias c ON p.id_categoria = c.id_categoria 
                  LEFT JOIN vw_estoque_atual v ON p.id_produto = v.id_produto 
                  WHERE p.status = 'ativo'";
          
          if(isset($_GET['cat'])) {
            $sql .= " AND p.id_categoria = " . (int)$_GET['cat'];
          }
          
          $sql .= " ORDER BY p.nome_produto";
          
          $produtos = $conect->query($sql);
          
          if($produtos->rowCount() == 0){
            echo '<div class="empty-state">
                    <i class="fas fa-shopping-basket"></i>
                    <h3>Nenhum produto encontrado</h3>
                    <p>Tente alterar os filtros ou buscar por outro termo.</p>
                    <a href="index.php" class="btn-success">Ver Todos os Produtos</a>
                  </div>';
          }
          
          while($p = $produtos->fetch(PDO::FETCH_OBJ)){
            // Definir cor do badge de estoque
            $estoque_class = 'stock-in';
            $estoque_text = 'Em Estoque';
            
            if($p->estoque == 0) {
              $estoque_class = 'stock-out';
              $estoque_text = 'Sem Estoque';
            } else if($p->estoque < 10) {
              $estoque_class = 'stock-low';
              $estoque_text = 'Estoque Baixo';
            }
            
            // Verificar se a imagem existe
            $imagem_path = "../img/produtos/" . $p->foto_produto;
            $tem_imagem = !empty($p->foto_produto) && file_exists($imagem_path);
            
            echo "<div class='product-card' data-price='{$p->preco_venda}' data-stock='{$p->estoque}' data-category='{$p->id_categoria}'>
                    <div class='product-image-container'>";
            
            if($tem_imagem) {
              echo "<img src='$imagem_path' alt='{$p->nome_produto}' class='product-image'>";
            } else {
              // IMAGEM PADRÃO - PAREDE DE CIMENTO BRANCO
              echo "<div class='default-image-container'>
                      <div class='default-image-overlay'>
                        <i class='fas fa-cube'></i>
                        <div class='default-image-text'>
                          Imagem não disponível<br>
                          <small>Produto: {$p->nome_produto}</small>
                        </div>
                      </div>
                    </div>";
            }
            
            echo "</div>
                    <div class='product-info'>
                      <h3 class='product-name'>{$p->nome_produto}</h3>
                      <p class='product-description'>{$p->descricao_produto}</p>
                      <div class='product-price'>R$ " . number_format($p->preco_venda, 2, ',', '.') . "</div>
                      <div class='product-footer'>
                        <span class='stock-badge $estoque_class'>$estoque_text</span>
                      </div>
                    </div>
                  </div>";
          }
        } catch (PDOException $e) {
          echo '<div class="empty-state">
                  <i class="fas fa-exclamation-triangle"></i>
                  <h3>Erro ao carregar produtos</h3>
                  <p>Não foi possível conectar ao banco de dados.</p>
                </div>';
        }
        ?>
      </div>
    </div>
  </div>

  <script src="plugins/jquery/jquery.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Toggle do Filtro de Categorias
      const categoryToggle = document.getElementById('categoryToggle');
      const categoriesContent = document.getElementById('categoriesContent');
      
      categoryToggle.addEventListener('click', function() {
        categoriesContent.classList.toggle('active');
        const toggleIcon = categoryToggle.querySelector('.filter-toggle i');
        toggleIcon.classList.toggle('active');
      });
      
      // Busca em Categorias
      const categorySearch = document.getElementById('categorySearch');
      const categoryCards = document.querySelectorAll('.category-card');
      
      categorySearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        categoryCards.forEach(card => {
          const categoryName = card.querySelector('.category-name').textContent.toLowerCase();
          
          if (categoryName.includes(searchTerm)) {
            card.style.display = 'flex';
          } else {
            card.style.display = 'none';
          }
        });
      });
      
      // Seleção de Categoria
      categoryCards.forEach(card => {
        card.addEventListener('click', function() {
          const categoryId = this.dataset.category;
          
          if (categoryId === 'all') {
            window.location.href = 'index.php';
          } else {
            window.location.href = `?cat=${categoryId}`;
          }
        });
      });
      
      // Busca de Produtos
      const productSearch = document.getElementById('productSearch');
      const productCards = document.querySelectorAll('.product-card');
      
      productSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        productCards.forEach(card => {
          const productName = card.querySelector('.product-name').textContent.toLowerCase();
          const productDesc = card.querySelector('.product-description').textContent.toLowerCase();
          
          if (productName.includes(searchTerm) || productDesc.includes(searchTerm)) {
            card.style.display = 'flex';
          } else {
            card.style.display = 'none';
          }
        });
      });
      
      // Modal de Filtros
      const filterModal = document.getElementById('filterModal');
      const openFilterModal = document.getElementById('openFilterModal');
      const closeFilterModal = document.getElementById('closeFilterModal');
      const applyFilters = document.getElementById('applyFilters');
      const clearFilters = document.getElementById('clearFilters');
      
      openFilterModal.addEventListener('click', function() {
        filterModal.style.display = 'flex';
      });
      
      closeFilterModal.addEventListener('click', function() {
        filterModal.style.display = 'none';
      });
      
      applyFilters.addEventListener('click', function() {
        aplicarFiltros();
        filterModal.style.display = 'none';
      });
      
      clearFilters.addEventListener('click', function() {
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
          checkbox.checked = false;
        });
        aplicarFiltros();
      });
      
      // Função para aplicar filtros
      function aplicarFiltros() {
        const priceFilters = Array.from(document.querySelectorAll('input[name="price"]:checked')).map(cb => cb.value);
        const stockFilters = Array.from(document.querySelectorAll('input[name="stock"]:checked')).map(cb => cb.value);
        
        productCards.forEach(card => {
          const price = parseFloat(card.dataset.price);
          const stock = parseInt(card.dataset.stock);
          let show = true;
          
          // Filtro de preço
          if (priceFilters.length > 0) {
            let priceMatch = false;
            priceFilters.forEach(filter => {
              if (filter === '0-50' && price <= 50) priceMatch = true;
              if (filter === '50-100' && price > 50 && price <= 100) priceMatch = true;
              if (filter === '100-200' && price > 100 && price <= 200) priceMatch = true;
              if (filter === '200+' && price > 200) priceMatch = true;
            });
            if (!priceMatch) show = false;
          }
          
          // Filtro de estoque
          if (stockFilters.length > 0) {
            let stockMatch = false;
            stockFilters.forEach(filter => {
              if (filter === 'in-stock' && stock > 10) stockMatch = true;
              if (filter === 'low-stock' && stock > 0 && stock <= 10) stockMatch = true;
              if (filter === 'out-of-stock' && stock === 0) stockMatch = true;
            });
            if (!stockMatch) show = false;
          }
          
          card.style.display = show ? 'flex' : 'none';
        });
      }
      
      // Fechar modal ao clicar fora
      window.addEventListener('click', function(event) {
        if (event.target === filterModal) {
          filterModal.style.display = 'none';
        }
      });
    });
  </script>
</body>
</html>