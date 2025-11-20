<?php include('../config/conexao.php'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Minha Loja - Catálogo de Produtos</title>
  <link rel="stylesheet" href="../adminlte/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { background:#f4f6f9; }
    .card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); transition: all 0.3s; }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Topo -->
  <nav class="main-header navbar navbar-expand navbar-dark navbar-primary">
    <div class="container-fluid">
      <a href="index.php" class="navbar-brand"><strong>MINHA LOJA</strong></a>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a href="../paginas/home.php" class="btn btn-outline-light btn-sm">
            <i class="fas fa-user-lock"></i> Área Admin
          </a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Menu lateral com categorias -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4" style="position:fixed;">
    <div class="sidebar p-3">
      <h5 class="text-white"><i class="fas fa-list"></i> Categorias</h5>
      <hr class="bg-white">
      <a href="index.php" class="btn btn-light btn-block mb-2 <?php echo !isset($_GET['cat']) ? 'active' : ''; ?>">
        <i class="fas fa-home"></i> Todos os Produtos
      </a>
      <?php
      $cats = $conect->query("SELECT * FROM tb_categorias ORDER BY nome_categoria");
      while($c = $cats->fetch(PDO::FETCH_OBJ)){
        $ativo = (isset($_GET['cat']) && $_GET['cat']==$c->id_categoria) ? 'active bg-warning' : '';
        echo "<a href='?cat={$c->id_categoria}' class='btn btn-outline-light btn-block text-left mb-1 $ativo'>
                <i class='fas fa-tag'></i> {$c->nome_categoria}
              </a>";
      }
      ?>
    </div>
  </aside>

  <!-- Conteúdo principal -->
  <div class="content-wrapper" style="margin-left:250px; padding:20px;">
    <div class="container-fluid">
      <h2 class="mb-4">
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
      </h2>

      <div class="row">
        <?php
        $sql = "SELECT p.*, COALESCE(v.estoque_atual,0) as estoque 
                FROM tb_produtos p 
                LEFT JOIN vw_estoque_atual v ON p.id_produto = v.id_produto 
                WHERE p.status = 'ativo'";
        if(isset($_GET['cat'])) $sql .= " AND p.id_categoria = ".(int)$_GET['cat'];
        $sql .= " ORDER BY p.nome_produto";

        $produtos = $conect->query($sql);
        if($produtos->rowCount() == 0){
          echo '<div class="col-12"><div class="alert alert-info">Nenhum produto encontrado.</div></div>';
        }
        while($p = $produtos->fetch(PDO::FETCH_OBJ)){
        ?>
          <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100">
              <img src="../img/produtos/<?= $p->foto_produto ?>" class="card-img-top" style="height:200px; object-fit:cover;">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?= $p->nome_produto ?></h5>
                <p class="text-muted small flex-grow-1"><?= $p->descricao_produto ?></p>
                <h3 class="text-success">R$ <?= number_format($p->preco_venda,2,',','.') ?></h3>
                <span class="badge <?= $p->estoque > 0 ? 'bg-success' : 'bg-danger' ?>">
                  Estoque: <?= $p->estoque ?>
                </span>
                <button class="btn btn-primary mt-3"><i class="fas fa-shopping-cart"></i> Comprar</button>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
</body>
</html>