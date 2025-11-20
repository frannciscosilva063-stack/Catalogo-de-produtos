<?php
include('../config/conexao.php');
$id_user = $_SESSION['id_user'];
$id_categoria = (int)$_GET['id'] ?? 0;

if ($id_categoria == 0) {
    echo "<h3>Categoria não encontrada</h3>";
    return;
}

// Pega o nome da categoria
$cat = $conect->prepare("SELECT nome_categoria FROM tb_categorias WHERE id_categoria = ?");
$cat->execute([$id_categoria]);
$nome_categoria = $cat->fetchColumn() ?: "Categoria";
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Produtos - <?= htmlspecialchars($nome_categoria) ?></h1>
                </div>
                <div class="col-sm-6">
                    <a href="home.php" class="btn btn-secondary float-right">
                        ← Voltar
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <?php
                $sql = "SELECT p.*, COALESCE(v.estoque_atual, 0) AS estoque_atual
                        FROM tb_produtos p
                        LEFT JOIN vw_estoque_atual v ON p.id_produto = v.id_produto
                        WHERE p.id_categoria = ? AND p.id_user = ?
                        ORDER BY p.nome_produto";

                $stmt = $conect->prepare($sql);
                $stmt->execute([$id_categoria, $id_user]);

                if ($stmt->rowCount() == 0) {
                    echo '<div class="col-12"><div class="alert alert-info text-center">Nenhum produto nesta categoria ainda.</div></div>';
                }

                while ($p = $stmt->fetch(PDO::FETCH_OBJ)) {
                ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="../img/produtos/<?= $p->foto_produto ?>" class="card-img-top" style="height:200px; object-fit:cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($p->nome_produto) ?></h5>
                                <p class="text-muted small flex-grow-1"><?= nl2br(htmlspecialchars($p->descricao_produto)) ?></p>
                                <h4 class="text-primary">R$ <?= number_format($p->preco_venda, 2, ',', '.') ?></h4>
                                <span class="badge <?= $p->estoque_atual > 0 ? 'bg-success' : 'bg-danger' ?>">
                                    Estoque: <?= $p->estoque_atual ?>
                                </span>
                                <div class="mt-3">
                                    <a href="editar-produto.php?id=<?= $p->id_produto ?>" class="btn btn-warning btn-sm">Editar</a>
                                    <a href="deletar-produto.php?id=<?= $p->id_produto ?>" onclick="return confirm('Excluir?')" class="btn btn-danger btn-sm">Excluir</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>