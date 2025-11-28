<?php include('../config/conexao.php'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mercado Express - Catálogo de Produtos</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --primary: #3498db;
      --primary-dark: #2980b9;
      --secondary: #2ecc71;
      --accent: #e74c3c;
      --light: #f8f9fa;
      --dark: #2c3e50;
      --gray: #7f8c8d;
      --light-gray: #ecf0f1;
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    body {
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      color: var(--dark);
      line-height: 1.6;
      min-height: 100vh;
    }
    
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 15px;
    }
    
    /* Header */
    header {
      background: linear-gradient(to right, var(--primary), var(--primary-dark));
      color: white;
      padding: 1rem 0;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      position: sticky;
      top: 0;
      z-index: 100;
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
      text-align: center;
    }
    
    .logo i {
      font-size: 2rem;
    }
    
    .admin-btn {
      position: absolute;
      right: 0;
      background: rgba(255,255,255,0.2);
      border: 1px solid rgba(255,255,255,0.3);
      color: white;
      padding: 8px 15px;
      border-radius: 50px;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s ease;
    }
    
    .admin-btn:hover {
      background: rgba(255,255,255,0.3);
      transform: translateY(-2px);
    }
    
    /* Filtro de Categorias */
    .categories-filter {
      background: white;
      border-radius: 12px;
      padding: 20px;
      margin: 25px 0;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .filter-toggle {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 20px;
      background: var(--light-gray);
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 600;
      color: var(--dark);
      cursor: pointer;
      transition: all 0.3s ease;
      width: 100%;
      text-align: left;
    }
    
    .filter-toggle:hover {
      background: #dfe6e9;
    }
    
    .filter-toggle i {
      transition: transform 0.3s ease;
    }
    
    .filter-toggle.active i {
      transform: rotate(180deg);
    }
    
    .categories-content {
      display: none;
      margin-top: 20px;
    }
    
    .categories-content.active {
      display: block;
    }
    
    .category-search {
      position: relative;
      margin-bottom: 20px;
    }
    
    .category-search input {
      width: 100%;
      padding: 12px 20px 12px 45px;
      border: 2px solid var(--light-gray);
      border-radius: 50px;
      font-size: 1rem;
      transition: all 0.3s ease;
    }
    
    .category-search input:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
      outline: none;
    }
    
    .category-search i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--gray);
    }
    
    .categories-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
      gap: 15px;
      max-height: 300px;
      overflow-y: auto;
      padding: 10px;
    }
    
    .category-card {
      background: var(--light-gray);
      border: 2px solid transparent;
      border-radius: 10px;
      padding: 15px 10px;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .category-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      border-color: var(--primary);
    }
    
    .category-card.active {
      background: var(--primary);
      color: white;
      border-color: var(--primary);
    }
    
    .category-card.active .category-icon {
      background: rgba(255,255,255,0.2);
      color: white;
    }
    
    .category-icon {
      width: 50px;
      height: 50px;
      margin: 0 auto 10px;
      background: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.3rem;
      color: var(--primary);
      transition: all 0.3s ease;
    }
    
    .category-name {
      font-weight: 600;
      font-size: 0.9rem;
    }
    
    /* Content Area */
    .content-area {
      width: 100%;
    }
    
    .page-header {
      background: white;
      border-radius: 12px;
      padding: 25px;
      margin-bottom: 25px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .page-title {
      color: var(--dark);
      font-size: 1.8rem;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .page-subtitle {
      color: var(--gray);
      font-size: 1rem;
    }
    
    .search-filter {
      display: flex;
      gap: 15px;
      margin-bottom: 25px;
    }
    
    .search-box {
      flex: 1;
      position: relative;
    }
    
    .search-box input {
      width: 100%;
      padding: 12px 20px 12px 45px;
      border: 2px solid var(--light-gray);
      border-radius: 50px;
      font-size: 1rem;
      transition: all 0.3s ease;
    }
    
    .search-box input:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
      outline: none;
    }
    
    .search-icon {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--gray);
    }
    
    .filter-btn {
      background: white;
      border: 2px solid var(--light-gray);
      border-radius: 50px;
      padding: 0 20px;
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .filter-btn:hover {
      border-color: var(--primary);
      color: var(--primary);
    }
    
    /* Modal de Filtros */
    .filter-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 1000;
      align-items: center;
      justify-content: center;
    }
    
    .filter-modal-content {
      background: white;
      border-radius: 12px;
      padding: 30px;
      width: 90%;
      max-width: 500px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    
    .filter-modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    
    .filter-modal-title {
      font-size: 1.5rem;
      color: var(--dark);
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .close-modal {
      background: none;
      border: none;
      font-size: 1.5rem;
      color: var(--gray);
      cursor: pointer;
      padding: 5px;
    }
    
    .filter-section {
      margin-bottom: 20px;
    }
    
    .filter-section h4 {
      color: var(--dark);
      margin-bottom: 10px;
      font-size: 1.1rem;
    }
    
    .filter-options {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    
    .filter-option {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 0;
    }
    
    .filter-option input {
      width: 18px;
      height: 18px;
    }
    
    .filter-actions {
      display: flex;
      gap: 10px;
      margin-top: 25px;
    }
    
    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      flex: 1;
    }
    
    .btn-primary {
      background: var(--primary);
      color: white;
    }
    
    .btn-primary:hover {
      background: var(--primary-dark);
      transform: translateY(-2px);
    }
    
    .btn-secondary {
      background: var(--light-gray);
      color: var(--dark);
    }
    
    .btn-secondary:hover {
      background: #dde4e6;
    }
    
    /* Products Grid */
    .products-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 25px;
    }
    
    .product-card {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
      transition: all 0.3s ease;
      display: flex;
      flex-direction: column;
    }
    
    .product-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 25px rgba(0,0,0,0.1);
    }
    
    .product-image-container {
      height: 200px;
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, var(--light-gray) 0%, #dee2e6 100%);
      position: relative;
      overflow: hidden;
    }
    
    .product-avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: var(--primary);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 2rem;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .product-image {
      height: 200px;
      width: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }
    
    .product-card:hover .product-image {
      transform: scale(1.05);
    }
    
    .product-info {
      padding: 20px;
      display: flex;
      flex-direction: column;
      flex-grow: 1;
    }
    
    .product-name {
      font-weight: 700;
      font-size: 1.1rem;
      margin-bottom: 8px;
      color: var(--dark);
    }
    
    .product-description {
      color: var(--gray);
      font-size: 0.9rem;
      margin-bottom: 15px;
      flex-grow: 1;
    }
    
    .product-price {
      font-weight: 800;
      font-size: 1.4rem;
      color: var(--primary);
      margin-bottom: 10px;
    }
    
    .product-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: auto;
    }
    
    .stock-badge {
      padding: 5px 12px;
      border-radius: 50px;
      font-size: 0.8rem;
      font-weight: 600;
    }
    
    .stock-in {
      background: rgba(52, 152, 219, 0.1);
      color: var(--primary);
    }
    
    .stock-low {
      background: rgba(243, 156, 18, 0.1);
      color: #f39c12;
    }
    
    .stock-out {
      background: rgba(231, 76, 60, 0.1);
      color: var(--accent);
    }
    
    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 60px 20px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
      grid-column: 1 / -1;
    }
    
    .empty-state i {
      font-size: 4rem;
      color: var(--light-gray);
      margin-bottom: 20px;
    }
    
    .empty-state h3 {
      color: var(--gray);
      margin-bottom: 10px;
    }
    
    .empty-state p {
      color: var(--gray);
      margin-bottom: 25px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      .categories-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
      }
      
      .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      }
      
      .search-filter {
        flex-direction: column;
      }
      
      .header-content {
        flex-direction: column;
        gap: 15px;
        text-align: center;
      }
      
      .admin-btn {
        position: static;
        margin-top: 10px;
      }
    }
    
    @media (max-width: 480px) {
      .products-grid {
        grid-template-columns: 1fr;
      }
      
      .categories-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }
  </style>
</head>
<body>
  <header>
    <div class="container">
      <div class="header-content">
        <div class="logo">
          <i class="fas fa-shopping-basket"></i>
          <span>MERCADO EXPRESS</span>
        </div>
        <a href="../paginas/home.php" class="admin-btn">
          <i class="fas fa-user-lock"></i>
          <span>Área Admin</span>
        </a>
      </div>
    </div>
  </header>

  <div class="container">
    <!-- Filtro de Categorias Recolhível -->
    <div class="categories-filter">
      <button class="filter-toggle" id="categoryToggle">
        <i class="fas fa-chevron-down"></i>
        <span>Filtrar por Categoria</span>
      </button>
      
      <div class="categories-content" id="categoriesContent">
        <div class="category-search">
          <i class="fas fa-search"></i>
          <input type="text" placeholder="Buscar categorias..." id="categorySearch">
        </div>
        
        <div class="categories-grid" id="categoriesGrid">
          <!-- Todas as Categorias -->
          <div class="category-card <?php echo !isset($_GET['cat']) ? 'active' : ''; ?>" data-category="all">
            <div class="category-icon">
              <i class="fas fa-th-large"></i>
            </div>
            <div class="category-name">Todos</div>
          </div>
          
          <?php
          // Buscar categorias do banco de dados
          try {
            $cats = $conect->query("SELECT * FROM tb_categorias ORDER BY nome_categoria");
            
            while($c = $cats->fetch(PDO::FETCH_OBJ)){
              $ativo = (isset($_GET['cat']) && $_GET['cat'] == $c->id_categoria) ? 'active' : '';
              
              // Definir ícone baseado no nome da categoria
              $icone = 'fas fa-tag'; // ícone padrão
              $nome_categoria = strtolower($c->nome_categoria);
              
              // Mapeamento de ícones por categoria
              if (strpos($nome_categoria, 'fruta') !== false || strpos($nome_categoria, 'verdura') !== false) {
                $icone = 'fas fa-apple-alt';
              } elseif (strpos($nome_categoria, 'bebida') !== false) {
                $icone = 'fas fa-wine-bottle';
              } elseif (strpos($nome_categoria, 'padaria') !== false || strpos($nome_categoria, 'pão') !== false) {
                $icone = 'fas fa-bread-slice';
              } elseif (strpos($nome_categoria, 'laticínio') !== false || strpos($nome_categoria, 'leite') !== false || strpos($nome_categoria, 'queijo') !== false) {
                $icone = 'fas fa-cheese';
              } elseif (strpos($nome_categoria, 'carne') !== false || strpos($nome_categoria, 'frango') !== false || strpos($nome_categoria, 'bovino') !== false) {
                $icone = 'fas fa-drumstick-bite';
              } elseif (strpos($nome_categoria, 'limpeza') !== false) {
                $icone = 'fas fa-soap';
              } elseif (strpos($nome_categoria, 'pet') !== false) {
                $icone = 'fas fa-paw';
              } elseif (strpos($nome_categoria, 'hortifruti') !== false) {
                $icone = 'fas fa-seedling';
              } elseif (strpos($nome_categoria, 'eletrodoméstico') !== false || strpos($nome_categoria, 'eletro') !== false) {
                $icone = 'fas fa-plug';
              } elseif (strpos($nome_categoria, 'higiene') !== false || strpos($nome_categoria, 'beleza') !== false) {
                $icone = 'fas fa-soap';
              } elseif (strpos($nome_categoria, 'congelado') !== false || strpos($nome_categoria, 'frio') !== false) {
                $icone = 'fas fa-snowflake';
              } elseif (strpos($nome_categoria, 'enlatado') !== false || strpos($nome_categoria, 'conserva') !== false) {
                $icone = 'fas fa-can-food';
              } elseif (strpos($nome_categoria, 'grão') !== false || strpos($nome_categoria, 'cereal') !== false) {
                $icone = 'fas fa-wheat';
              } elseif (strpos($nome_categoria, 'biscoito') !== false || strpos($nome_categoria, 'snack') !== false) {
                $icone = 'fas fa-cookie';
              } elseif (strpos($nome_categoria, 'roupa') !== false || strpos($nome_categoria, 'vestuário') !== false) {
                $icone = 'fas fa-tshirt';
              } elseif (strpos($nome_categoria, 'material') !== false || strpos($nome_categoria, 'escritório') !== false) {
                $icone = 'fas fa-pencil-alt';
              } elseif (strpos($nome_categoria, 'brinquedo') !== false) {
                $icone = 'fas fa-gamepad';
              } elseif (strpos($nome_categoria, 'automotivo') !== false) {
                $icone = 'fas fa-car';
              } elseif (strpos($nome_categoria, 'construção') !== false) {
                $icone = 'fas fa-hammer';
              } elseif (strpos($nome_categoria, 'farmácia') !== false || strpos($nome_categoria, 'medicamento') !== false) {
                $icone = 'fas fa-pills';
              } elseif (strpos($nome_categoria, 'pescado') !== false || strpos($nome_categoria, 'peixe') !== false) {
                $icone = 'fas fa-fish';
              }
              
              echo "<div class='category-card $ativo' data-category='{$c->id_categoria}'>
                      <div class='category-icon'>
                        <i class='$icone'></i>
                      </div>
                      <div class='category-name'>{$c->nome_categoria}</div>
                    </div>";
            }
          } catch (PDOException $e) {
            echo "<div class='category-card'>
                    <div class='category-icon'>
                      <i class='fas fa-exclamation-triangle'></i>
                    </div>
                    <div class='category-name'>Erro</div>
                  </div>";
          }
          ?>
        </div>
      </div>
    </div>

    <!-- Área de Conteúdo -->
    <main class="content-area">
      <div class="page-header">
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
            <h4>Preço</h4>
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
            <h4>Disponibilidade</h4>
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
                    <h3 class="text-muted">Nenhum produto encontrado</h3>
                    <p class="text-muted">Tente alterar os filtros ou buscar por outro termo.</p>
                    <a href="index.php" class="btn btn-success mt-2">Ver Todos os Produtos</a>
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
            
            // Obter a primeira letra do nome do produto para o avatar
            $primeira_letra = strtoupper(substr($p->nome_produto, 0, 1));
            
            echo "<div class='product-card' data-price='{$p->preco_venda}' data-stock='{$p->estoque}' data-category='{$p->id_categoria}'>
                    <div class='product-image-container'>";
            
            if($tem_imagem) {
              echo "<img src='$imagem_path' alt='{$p->nome_produto}' class='product-image'>";
            } else {
              echo "<div class='product-avatar'>
                      <span>$primeira_letra</span>
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
                  <h3 class="text-muted">Erro ao carregar produtos</h3>
                  <p class="text-muted">Não foi possível conectar ao banco de dados.</p>
                </div>';
        }
        ?>
      </div>
    </main>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Toggle do Filtro de Categorias
      const categoryToggle = document.getElementById('categoryToggle');
      const categoriesContent = document.getElementById('categoriesContent');
      
      categoryToggle.addEventListener('click', function() {
        categoriesContent.classList.toggle('active');
        categoryToggle.classList.toggle('active');
      });
      
      // Busca em Categorias
      const categorySearch = document.getElementById('categorySearch');
      const categoryCards = document.querySelectorAll('.category-card');
      
      categorySearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        categoryCards.forEach(card => {
          const categoryName = card.querySelector('.category-name').textContent.toLowerCase();
          
          if (categoryName.includes(searchTerm)) {
            card.style.display = 'block';
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
    });
  </script>
</body>
</html>