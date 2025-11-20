<?php
include('../config/conexao.php');
$id_user = $_SESSION['id_user']; 
?>

$id_user = $_SESSION['id_user'];  // <--- ESSA LINHA RESOLVE O ERRO
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Cadastro de Produtos</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <!-- ==== FORMULÁRIO DE CADASTRO ==== -->
                <div class="col-md-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Novo Produto</h3>
                        </div>
                        <form method="post" enctype="multipart/form-data">
                            <div class="card-body">

                                <div class="form-group">
                                    <label>Nome do Produto *</label>
                                    <input type="text" name="nome_produto" class="form-control" required placeholder="Ex: Camiseta Algodão">
                                </div>

                                <div class="form-group">
                                    <label>Código de Barras (opcional)</label>
                                    <input type="text" name="codigo_barra" class="form-control" placeholder="7891234567890">
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Preço de Custo</label>
                                            <input type="text" name="preco_custo" class="form-control money" placeholder="0,00">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Preço de Venda *</label>
                                            <input type="text" name="preco_venda" class="form-control money" required placeholder="0,00">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Categoria *</label>
                                  <select name="id_categoria" class="form-control" required>
    <option value="">Selecione uma categoria...</option>
    <?php
    // Busca TODAS as categorias (não importa de qual usuário)
    $cats = $conect->query("SELECT * FROM tb_categorias ORDER BY nome_categoria ASC");
    while ($c = $cats->fetch(PDO::FETCH_OBJ)) {
        echo "<option value='{$c->id_categoria}'>{$c->nome_categoria}</option>";
    }
    ?>
</select>
                                </div>

                                <div class="form-group">
                                    <label>Quantidade Inicial em Estoque *</label>
                                    <input type="number" name="estoque_inicial" class="form-control" min="0" value="0" required>
                                </div>

                                <div class="form-group">
                                    <label>Descrição (opcional)</label>
                                    <textarea name="descricao_produto" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Foto do Produto</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="foto" id="foto" accept="image/*">
                                        <label class="custom-file-label" for="foto">Escolher arquivo</label>
                                    </div>
                                </div>

                                <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">

                            </div>
                            <div class="card-footer">
                                <button type="submit" name="cadastrar" class="btn btn-primary btn-block">
                                    <i class="fas fa-save"></i> Cadastrar Produto
                                </button>
                            </div>
                        </form>

                        <!-- ==== PROCESSAMENTO DO CADASTRO ==== -->
                    <!-- ==== PROCESSAMENTO DO CADASTRO ==== -->
<?php
if (isset($_POST['cadastrar'])) {
    $nome           = trim($_POST['nome_produto']);
    $codigo_barra   = !empty($_POST['codigo_barra']) ? trim($_POST['codigo_barra']) : null;
    $preco_custo    = !empty($_POST['preco_custo']) ? str_replace(['.', ','], ['', '.'], $_POST['preco_custo']) : null;
    $preco_venda    = str_replace(['.', ','], ['', '.'], $_POST['preco_venda']);
    $id_categoria   = (int)$_POST['id_categoria'];
    $descricao      = trim($_POST['descricao_produto'] ?? '');
    $estoque_inicial = (int)$_POST['estoque_inicial'];  // <-- VARIÁVEL CORRETA

    // Upload da foto
    $foto = 'produto-sem-foto.jpg';
    if (!empty($_FILES['foto']['name'])) {
        $extensoes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $extensoes)) {
            $pasta = "../img/produtos/";
            if (!is_dir($pasta)) mkdir($pasta, 0755, true);
            $novo_nome = uniqid() . ".$ext";
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $pasta . $novo_nome)) {
                $foto = $novo_nome;
            }
        }
    }

    try {
        $conect->beginTransaction();

        // 1. Insere o produto
        $sql = "INSERT INTO tb_produtos 
                (codigo_barra, nome_produto, descricao_produto, preco_custo, preco_venda, foto_produto, id_categoria, id_user)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conect->prepare($sql);
        $stmt->execute([$codigo_barra, $nome, $descricao, $preco_custo, $preco_venda, $foto, $id_categoria, $id_user]);
        $id_produto = $conect->lastInsertId();

        // 2. Registra entrada inicial no estoque (se > 0)
        if ($estoque_inicial > 0) {  // <-- CORRIGIDO AQUI!
            $sql2 = "INSERT INTO tb_estoque (id_produto, tipo_movimento, quantidade, motivo, id_user)
                     VALUES (?, 'entrada', ?, 'Entrada inicial - cadastro', ?)";
            $stmt2 = $conect->prepare($sql2);
            $stmt2->execute([$id_produto, $estoque_inicial, $id_user]);
        }

        $conect->commit();

        echo '<div class="alert alert-success alert-dismissible mt-3">
                <button type="button" class="close" data-dismiss="alert">×</button>
                Produto cadastrado com sucesso!
              </div>';
        echo '<script>setTimeout(() => location.reload(), 1800);</script>';

    } catch (Exception $e) {
        $conect->rollBack();
        echo '<div class="alert alert-danger alert-dismissible mt-3">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Erro:</strong> ' . htmlspecialchars($e->getMessage()) . '
              </div>';
    }
}
?>
                    </div>
                </div>
                <!-- Fim formulário -->

                <!-- ==== LISTA DE PRODUTOS RECENTES ==== -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Produtos Recentes</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Foto</th>
                                        <th>Produto</th>
                                        <th>Categoria</th>
                                        <th>Preço Venda</th>
                                        <th>Estoque</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT p.*, c.nome_categoria, COALESCE(v.estoque_atual, 0) as estoque_atual
                                            FROM tb_produtos p
                                            LEFT JOIN tb_categorias c ON p.id_categoria = c.id_categoria
                                            LEFT JOIN vw_estoque_atual v ON p.id_produto = v.id_produto
                                            WHERE p.id_user = ?
                                            ORDER BY p.id_produto DESC LIMIT 15";
                                    $stmt = $conect->prepare($sql);
                                    $stmt->execute([$id_user]);
                                    $cont = 1;

                                    while ($p = $stmt->fetch(PDO::FETCH_OBJ)) {
                                    ?>
                                        <tr>
                                            <td><?= $cont++ ?></td>
                                            <td>
                                                <img src="../img/produtos/<?= $p->foto_produto ?>" class="img-circle elevation-2" style="width:45px;height:45px;object-fit:cover;">
                                            </td>
                                            <td><?= htmlspecialchars($p->nome_produto) ?></td>
                                            <td><?= htmlspecialchars($p->nome_categoria) ?></td>
                                            <td>R$ <?= number_format($p->preco_venda, 2, ',', '.') ?></td>
                                            <td>
                                                <span class="badge <?= $p->estoque_atual > 0 ? 'bg-success' : 'bg-danger' ?>">
                                                    <?= $p->estoque_atual ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="editar-produto.php?id=<?= $p->id_produto ?>" class="btn btn-sm btn-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="deletar-produto.php?id=<?= $p->id_produto ?>" onclick="return confirm('Excluir permanentemente?')" class="btn btn-sm btn-danger" title="Excluir">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if ($stmt->rowCount() == 0) : ?>
                                        <tr><td colspan="7" class="text-center">Nenhum produto cadastrado ainda.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<!-- Máscara de dinheiro (jQuery + plugin mask) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $(document).ready(function(){
        $('.money').mask('000.000.000,00', {reverse: true});
    });
</script>