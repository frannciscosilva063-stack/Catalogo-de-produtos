<?php
include('../config/conexao.php');
$id_user = $_SESSION['id_user'];
$id_categoria = (int)($_GET['id'] ?? 0);

if ($id_categoria == 0) {
    echo "<h3>Categoria não encontrada</h3>";
    return;
}

// Pega o nome da categoria
try {
    $cat = $conect->prepare("SELECT nome_categoria FROM tb_categorias WHERE id_categoria = ? AND id_user = ?");
    $cat->execute([$id_categoria, $id_user]);
    $nome_categoria = $cat->fetchColumn() ?: "Categoria";
} catch (Exception $e) {
    echo "<h3>Erro ao carregar categoria</h3>";
    return;
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-box"></i> 
                        Produtos - <?= htmlspecialchars($nome_categoria) ?>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <a href="home.php" class="btn btn-secondary float-right">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <?php
                try {
                    $sql = "SELECT p.*, COALESCE(v.estoque_atual, 0) AS estoque_atual
                            FROM tb_produtos p
                            LEFT JOIN vw_estoque_atual v ON p.id_produto = v.id_produto
                            WHERE p.id_categoria = ? AND p.id_user = ? AND p.status = 'ativo'
                            ORDER BY p.nome_produto ASC";

                    $stmt = $conect->prepare($sql);
                    $stmt->execute([$id_categoria, $id_user]);

                    if ($stmt->rowCount() == 0) {
                        echo '<div class="col-12">
                                <div class="alert alert-info text-center">
                                    <i class="fas fa-info-circle fa-2x mb-2"></i><br>
                                    <strong>Nenhum produto nesta categoria ainda.</strong><br>
                                    <small>Cadastre produtos para começar</small>
                                </div>
                              </div>';
                    }

                    while ($p = $stmt->fetch(PDO::FETCH_OBJ)) {
                        $estoque_badge = $p->estoque_atual > 0 ? 'bg-success' : 'bg-danger';
                        $estoque_icon = $p->estoque_atual > 0 ? 'fa-check-circle' : 'fa-exclamation-triangle';
                ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="../img/produtos/<?= htmlspecialchars($p->foto_produto) ?>" 
                                 class="card-img-top" 
                                 style="height:200px; object-fit:cover;"
                                 alt="<?= htmlspecialchars($p->nome_produto) ?>"
                                 onerror="this.src='../img/produtos/produto-sem-foto.jpg'">
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-truncate" title="<?= htmlspecialchars($p->nome_produto) ?>">
                                    <?= htmlspecialchars($p->nome_produto) ?>
                                </h5>
                                
                                <?php if (!empty($p->codigo_barra)): ?>
                                    <small class="text-muted mb-2">
                                        <i class="fas fa-barcode"></i> 
                                        <?= htmlspecialchars($p->codigo_barra) ?>
                                    </small>
                                <?php endif; ?>

                                <?php if (!empty($p->descricao_produto)): ?>
                                    <p class="text-muted small flex-grow-1" style="max-height: 60px; overflow: hidden;">
                                        <?= nl2br(htmlspecialchars(substr($p->descricao_produto, 0, 100))) ?>
                                        <?= strlen($p->descricao_produto) > 100 ? '...' : '' ?>
                                    </p>
                                <?php else: ?>
                                    <p class="text-muted small flex-grow-1">Sem descrição</p>
                                <?php endif; ?>

                                <div class="mb-2">
                                    <?php if ($p->preco_custo > 0): ?>
                                        <small class="text-muted d-block">
                                            <del>Custo: R$ <?= number_format($p->preco_custo, 2, ',', '.') ?></del>
                                        </small>
                                    <?php endif; ?>
                                    <h4 class="text-primary mb-0">
                                        R$ <?= number_format($p->preco_venda, 2, ',', '.') ?>
                                    </h4>
                                </div>

                                <span class="badge <?= $estoque_badge ?> mb-3">
                                    <i class="fas <?= $estoque_icon ?>"></i> 
                                    Estoque: <?= $p->estoque_atual ?>
                                </span>

                                <div class="btn-group btn-block">
                                    <a href="editar-produto.php?id=<?= $p->id_produto ?>" 
                                       class="btn btn-warning btn-sm" 
                                       title="Editar produto">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <a href="deletar-produto.php?id=<?= $p->id_produto ?>" 
                                       onclick="return confirm('Tem certeza que deseja excluir este produto?')" 
                                       class="btn btn-danger btn-sm"
                                       title="Excluir produto">
                                        <i class="fas fa-trash"></i> Excluir
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
                    }
                } catch (Exception $e) {
                    echo '<div class="col-12">
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> 
                                <strong>Erro ao carregar produtos:</strong> ' . 
                                htmlspecialchars($e->getMessage()) . 
                            '</div>
                          </div>';
                    error_log("Erro ao carregar produtos da categoria: " . $e->getMessage());
                }
                ?>
            </div>
        </div>
    </section>
</div>