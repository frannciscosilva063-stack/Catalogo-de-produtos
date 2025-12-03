<?php include('../config/conexao.php'); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mercado Express | Catálogo de Produtos</title>
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

<style>
  :root {
    --primary-color: #2C5AA0;
    --primary-dark: #1E3F73;
    --secondary-color: #4ECDC4;
    --accent-color: #FFD166;
    --text-dark: #2D3047;
    --text-light: #6C757D;
    --success-color: #06D6A0;
    --success-dark: #059973;
    --success-light: #0AFFC2;
    --warning-color: #FFD166;
    --warning-dark: #E6B950;
    --low-stock-color: #4A90E2;
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
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  /* Estilo para imagem padrão - LOGO PROFISSIONAL MERCADO EXPRESS */
  .default-image-container {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 20px;
    position: relative;
    overflow: hidden;
  }

  /* Logo SVG animada */
  .mercado-express-logo {
    width: 90%;
    height: 90%;
    max-width: 250px;
    max-height: 200px;
    filter: drop-shadow(0 8px 20px rgba(0, 0, 0, 0.15));
    animation: logoFloat 3s ease-in-out infinite;
  }

  @keyframes logoFloat {
    0%, 100% {
      transform: translateY(0px);
    }
    50% {
      transform: translateY(-8px);
    }
  }

  .product-card:hover .mercado-express-logo {
    animation: logoFloat 2s ease-in-out infinite;
  }

  .logo-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    width: 100%;
    height: 100%;
  }

  .logo-icon {
    font-size: 4rem;
    color: var(--white);
    margin-bottom: 10px;
    text-shadow: 0 4px 15px rgba(0,0,0,0.3);
  }

  .logo-text {
    font-family: 'Arial Black', 'Segoe UI', sans-serif;
    font-size: 1.5rem;
    font-weight: 900;
    color: var(--white);
    text-transform: uppercase;
    letter-spacing: 2px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.3);
  }

  .logo-subtext {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.9);
    margin-top: 5px;
    font-weight: 500;
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

  /* Estilo do nome do produto modificado */
  .product-name {
    font-weight: 700;
    font-size: 1.4rem;
    margin-bottom: 12px;
    color: var(--text-dark);
    line-height: 1.3;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    letter-spacing: -0.2px;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
  }

  .product-description {
    color: var(--text-light);
    font-size: 0.95rem;
    margin-bottom: 20px;
    flex-grow: 1;
    line-height: 1.6;
  }

  /* Estilo do preço com cores baseadas no estoque */
  .product-price {
    font-weight: 900;
    font-size: 1.8rem;
    margin-bottom: 15px;
    text-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    letter-spacing: -0.5px;
  }

  .price-high-stock {
    color: var(--success-dark); /* Verde escuro para alto estoque */
    background: linear-gradient(45deg, var(--success-dark), var(--success-color));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .price-low-stock {
    color: var(--low-stock-color); /* Azul para estoque baixo */
    background: linear-gradient(45deg, var(--low-stock-color), #2C5AA0);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .price-no-stock {
    color: #dc3545; /* Vermelho para sem estoque */
    background: linear-gradient(45deg, #dc3545, #ff6b6b);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .product-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    flex-wrap: wrap;
    gap: 10px;
  }

  /* Estilos para descrição da imagem */
  .image-description {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, rgba(44, 90, 160, 0.05), rgba(78, 205, 196, 0.05));
    border-left: 4px solid var(--primary-color);
    border-radius: 8px;
    font-size: 0.9rem;
    color: var(--text-dark);
    font-weight: 500;
    margin-bottom: 15px;
    line-height: 1.5;
    max-height: 60px;
    overflow-y: auto;
  }

  .image-description i {
    color: var(--primary-color);
    margin-right: 8px;
  }

  /* Estilos para código de barras */
  .barcode-container {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 12px;
    background: rgba(248, 249, 250, 0.9);
    border-radius: 10px;
    border: 2px dashed rgba(44, 90, 160, 0.2);
    margin-bottom: 15px;
    transition: all 0.3s ease;
  }

  .barcode-container:hover {
    border-color: var(--primary-color);
    background: rgba(248, 249, 250, 1);
    box-shadow: 0 4px 12px rgba(44, 90, 160, 0.1);
  }

  .barcode-wrapper {
    display: flex;
    justify-content: center;
    margin-bottom: 8px;
  }

  .barcode-wrapper svg {
    max-width: 100%;
    height: auto;
  }

  .barcode-label {
    font-size: 0.85rem;
    color: var(--text-light);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 5px;
  }

  .barcode-number {
    font-size: 0.8rem;
    color: var(--text-dark);
    font-family: 'Courier New', monospace;
    font-weight: 700;
    letter-spacing: 2px;
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
    background: rgba(74, 144, 226, 0.15);
    color: var(--low-stock-color);
    border: 2px solid var(--low-stock-color);
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
    
    .logo-icon {
      font-size: 3rem;
    }
    
    .logo-text {
      font-size: 1.2rem;
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
    
    .logo-icon {
      font-size: 2.5rem;
    }
    
    .logo-text {
      font-size: 1rem;
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
            // CATEGORIAS COM IMAGENS WEB
            $categorias = array(
              // Eletrônicos
              array('nome' => 'Eletrônicos', 'imagem' => 'https://img.freepik.com/fotos-gratis/arranjo-de-colecao-estacionario-moderno_23-2149309638.jpg'),
              
              // Bebidas
              array('nome' => 'Bebidas', 'imagem' => 'https://img.freepik.com/fotos-gratis/bar-concurso-adiciona-gelo-com-clipe-de-aco-em-copo-de-cocktail_141793-1998.jpg'),
              
              // Roupas
              array('nome' => 'Roupas', 'imagem' => 'https://img.freepik.com/fotos-gratis/conceito-de-maquete-de-camisa-com-roupas-simples_23-2149448749.jpg'),
              
              // Calçados
              array('nome' => 'Calçados', 'imagem' => 'https://img.freepik.com/fotos-gratis/vista-de-um-rack-de-sapatos-para-empilhar-um-par-de-calcados_23-2150991549.jpg'),
              
              // Bolsas
              array('nome' => 'Bolsas', 'imagem' => 'https://img.freepik.com/fotos-gratis/saco-pendurado-em-um-item-de-mobiliario-dentro-de-casa_23-2151073514.jpg'),
              
              // Relógios
              array('nome' => 'Relógios', 'imagem' => 'https://img.freepik.com/fotos-gratis/vista-frontal-do-relogio-de-mao_23-2148385833.jpg'),
              
              // Perfumes
              array('nome' => 'Perfumes', 'imagem' => 'https://img.freepik.com/fotos-gratis/mulher-caucasiano-perfume-aplicando-para-dela-pescoco_53876-14.jpg'),
              
              // Maquiagem
              array('nome' => 'Maquiagem', 'imagem' => 'https://img.freepik.com/fotos-gratis/arranjo-de-pinceis-de-maquiagem-de-alto-angulo_23-2149860768.jpg'),
              
              // Eletrodomésticos
              array('nome' => 'Eletrodomésticos', 'imagem' => 'https://img.freepik.com/vetores-gratis/utensilios-de-cozinha-inteligentes-para-geladeira-forno-a-gas-e-exaustor_107791-2971.jpg'),
              
              // Móveis
              array('nome' => 'Móveis', 'imagem' => 'https://img.freepik.com/fotos-gratis/planta-de-borracha-em-uma-mesa-de-madeira_53876-146856.jpg'),
              
              // Decoração
              array('nome' => 'Decoração', 'imagem' => 'https://img.freepik.com/fotos-gratis/pompons-decorativos-usados-para-vaso-decorativo_23-2149449983.jpg'),
              
              // Esportes
              array('nome' => 'Esportes', 'imagem' => 'https://img.freepik.com/fotos-gratis/ferramentas-desportivas_53876-138077.jpg'),
              
              // Suplementos
              array('nome' => 'Suplementos', 'imagem' => 'https://img.freepik.com/fotos-gratis/arranjo-com-comprimidos-no-recipiente_23-2149080623.jpg'),
              
              // Livros
              array('nome' => 'Livros', 'imagem' => 'https://img.freepik.com/fotos-gratis/de-cima-livros-abertos_23-2147779265.jpg'),
              
              // Papelaria
              array('nome' => 'Papelaria', 'imagem' => 'https://img.freepik.com/fotos-gratis/sketchbook-em-material-de-escritorio_23-2147689744.jpg'),
              
              // Brinquedos
              array('nome' => 'Brinquedos', 'imagem' => 'https://img.freepik.com/fotos-gratis/garoto-de-vista-frontal-brincando-com-brinquedos-de-madeira_23-2149357210.jpg'),
              
              // Pet Shop
              array('nome' => 'Pet Shop', 'imagem' => 'https://img.freepik.com/fotos-gratis/adoravel-cachorro-com-dona-na-loja-de-animais_23-2148872557.jpg'),
              
              // Ferramentas
              array('nome' => 'Ferramentas', 'imagem' => 'https://img.freepik.com/fotos-gratis/vista-superior-de-um-martelo-de-aco-com-outros-elementos-de-construcao-e-ferramentas_23-2150576396.jpg'),
              
              // Jardim
              array('nome' => 'Jardim', 'imagem' => 'https://img.freepik.com/fotos-gratis/trilha-sob-um-belo-arco-de-flores-e-plantas_181624-16890.jpg'),
              
              // Automotivo
              array('nome' => 'Automotivo', 'imagem' => 'https://img.freepik.com/fotos-gratis/um-carro-esporte-de-cor-metalica-tira-com-alta-velocidade-na-estrada_114579-4029.jpg'),
              
              // Alimentos
              array('nome' => 'Alimentos', 'imagem' => 'https://img.freepik.com/fotos-gratis/visao-superior-da-variedade-de-piramide-alimentar-real_23-2150238927.jpg')
            );

            // Buscar todas as categorias do banco de dados
            $cats = $conect->query("SELECT * FROM tb_categorias ORDER BY nome_categoria");
            
            // Mapear categorias do banco com as imagens pré-definidas
            while($c = $cats->fetch(PDO::FETCH_OBJ)){
              $ativo = (isset($_GET['cat']) && $_GET['cat'] == $c->id_categoria) ? 'active' : '';
              $nome_categoria = $c->nome_categoria;
              $imagem_categoria = '';
              
              // Encontrar a imagem correspondente na matriz
              foreach($categorias as $categoria_predefinida){
                if(strcasecmp($categoria_predefinida['nome'], $nome_categoria) === 0){
                  $imagem_categoria = $categoria_predefinida['imagem'];
                  break;
                }
              }
              
              // Se não encontrar imagem específica, usar imagem padrão
              if(empty($imagem_categoria)){
                $imagem_categoria = 'https://cdn.pixabay.com/photo/2022/08/28/03/42/sea-7415664_960_720.png 1x, https://cdn.pixabay.com/photo/2022/08/28/03/42/sea-7415664_1280.png 2x';
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
            // Se filtrou por categoria, mostre mensagem de categoria vazia
            if(isset($_GET['cat'])) {
              $cat_id = (int)$_GET['cat'];
              $cat_nome = $conect->prepare("SELECT nome_categoria FROM tb_categorias WHERE id_categoria = ?");
              $cat_nome->execute([$cat_id]);
              $nome_cat = $cat_nome->fetchColumn();
              
              echo '<div class="empty-state">
                      <i class="fas fa-box-open"></i>
                      <h3>Sem Produtos em ' . htmlspecialchars($nome_cat) . '</h3>
                      <p>Esta categoria ainda não possui produtos cadastrados.</p>
                      <a href="index.php" class="btn-success">Ver Todos os Produtos</a>
                    </div>';
            } else {
              echo '<div class="empty-state">
                      <i class="fas fa-shopping-basket"></i>
                      <h3>Nenhum produto encontrado</h3>
                      <p>Tente alterar os filtros ou buscar por outro termo.</p>
                      <a href="index.php" class="btn-success">Ver Todos os Produtos</a>
                    </div>';
            }
          }
          
          while($p = $produtos->fetch(PDO::FETCH_OBJ)){
            // Definir cor do badge de estoque e classe de preço
            $estoque_class = 'stock-in';
            $estoque_text = 'Em Estoque';
            $preco_class = 'price-high-stock';
            
            if($p->estoque == 0) {
              $estoque_class = 'stock-out';
              $estoque_text = 'Sem Estoque';
              $preco_class = 'price-no-stock';
            } else if($p->estoque < 10) {
              $estoque_class = 'stock-low';
              $estoque_text = 'Estoque Baixo';
              $preco_class = 'price-low-stock';
            }
            
            // Verificar se a imagem existe
            $imagem_path = "../img/produtos/" . $p->foto_produto;
            $tem_imagem = !empty($p->foto_produto) && file_exists($imagem_path);
            
            echo "<div class='product-card' data-price='{$p->preco_venda}' data-stock='{$p->estoque}' data-category='{$p->id_categoria}'>
                    <div class='product-image-container'>";
            
            if($tem_imagem) {
              echo "<img src='$imagem_path' alt='{$p->nome_produto}' class='product-image'>";
            } else {
              // LOGO SVG PROFISSIONAL DO MERCADO EXPRESS
              echo "<div class='default-image-container'>
                      <svg viewBox='0 0 400 300' xmlns='http://www.w3.org/2000/svg' class='mercado-express-logo'>
                        <!-- Fundo gradiente -->
                        <defs>
                          <linearGradient id='bgGradient' x1='0%' y1='0%' x2='100%' y2='100%'>
                            <stop offset='0%' style='stop-color:#2C5AA0;stop-opacity:1' />
                            <stop offset='100%' style='stop-color:#1E3F73;stop-opacity:1' />
                          </linearGradient>
                          <linearGradient id='basketGradient' x1='0%' y1='0%' x2='100%' y2='100%'>
                            <stop offset='0%' style='stop-color:#FFD166;stop-opacity:1' />
                            <stop offset='100%' style='stop-color:#FFC43D;stop-opacity:1' />
                          </linearGradient>
                          <filter id='shadow' x='-50%' y='-50%' width='200%' height='200%'>
                            <feDropShadow dx='2' dy='4' stdDeviation='3' flood-opacity='0.3'/>
                          </filter>
                        </defs>
                        
                        <!-- Fundo -->
                        <rect width='400' height='300' fill='url(#bgGradient)'/>
                        
                        <!-- Círculo decorativo fundo -->
                        <circle cx='200' cy='150' r='95' fill='rgba(255,255,255,0.1)'/>
                        
                        <!-- Cesto de compras estilizado -->
                        <g filter='url(#shadow)'>
                          <!-- Alça do cesto -->
                          <path d='M 140 110 Q 140 70 200 70 Q 260 70 260 110' stroke='url(#basketGradient)' stroke-width='8' fill='none' stroke-linecap='round'/>
                          
                          <!-- Corpo do cesto -->
                          <path d='M 130 115 L 145 200 Q 145 220 165 220 L 235 220 Q 255 220 255 200 L 270 115 Z' fill='url(#basketGradient)' stroke='#FFC43D' stroke-width='2'/>
                          
                          <!-- Detalhes do cesto - linhas -->
                          <line x1='160' y1='130' x2='150' y2='200' stroke='rgba(255,255,255,0.3)' stroke-width='1.5'/>
                          <line x1='200' y1='125' x2='200' y2='220' stroke='rgba(255,255,255,0.3)' stroke-width='1.5'/>
                          <line x1='240' y1='130' x2='250' y2='200' stroke='rgba(255,255,255,0.3)' stroke-width='1.5'/>
                        </g>
                        
                        <!-- Texto: MERCADO EXPRESS -->
                        <text x='200' y='265' font-family='Arial, sans-serif' font-size='28' font-weight='bold' text-anchor='middle' fill='white' letter-spacing='1'>
                          MERCADO EXPRESS
                        </text>
                        
                        <!-- Tagline -->
                        <text x='200' y='285' font-family='Arial, sans-serif' font-size='11' text-anchor='middle' fill='#FFD166' letter-spacing='0.5' font-weight='500'>
                          QUALIDADE EM CADA COMPRA
                        </text>
                      </svg>
                    </div>";
            }
            
            echo "</div>
                    <div class='product-info'>
                      <h3 class='product-name'>{$p->nome_produto}</h3>";
            
            // Exibir descrição da imagem se existir
            if(!empty($p->descricao_produto)) {
              echo "<div class='image-description'>
                      <i class='fas fa-image'></i>
                      " . htmlspecialchars(substr($p->descricao_produto, 0, 80)) . (strlen($p->descricao_produto) > 80 ? '...' : '') . "
                    </div>";
            }
            
            // Exibir código de barras se existir
            if(!empty($p->codigo_barra)) {
              echo "<div class='barcode-container'>
                      <div class='barcode-label'>Código de Barras</div>
                      <div class='barcode-wrapper'>
                        <svg id='barcode-{$p->id_produto}'></svg>
                      </div>
                      <div class='barcode-number'>{$p->codigo_barra}</div>
                    </div>
                    <script>
                      JsBarcode('#barcode-{$p->id_produto}', '{$p->codigo_barra}', {
                        format: 'CODE128',
                        width: 2,
                        height: 50,
                        displayValue: false,
                        margin: 5
                      });
                    </script>";
            }
            
            echo "<div class='product-price {$preco_class}'>R$ " . number_format($p->preco_venda, 2, ',', '.') . "</div>
                      <div class='product-footer'>
                        <span class='stock-badge {$estoque_class}'>{$estoque_text}</span>
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