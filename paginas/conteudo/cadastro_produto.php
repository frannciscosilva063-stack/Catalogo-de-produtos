<?php
// ============================================
// INÍCIO - VERIFICAÇÃO DE LOGIN E CONEXÃO
// ============================================
// NÃO inicie session_start() - já está no header

// Verificar se usuário está logado
if (!isset($_SESSION['id_user'])) {
    header("Location: ../../login.php");
    exit;
}

// Definir variáveis da sessão
$id_user = $_SESSION['id_user'];
$nome_user = isset($_SESSION['nome_user']) ? $_SESSION['nome_user'] : 'Usuário';

// Incluir conexão
require_once('../config/conexao.php');
// ============================================
// FIM DA VERIFICAÇÃO
// ============================================
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
                                    <input type="text" name="nome_produto" class="form-control" required placeholder="Ex: Camiseta Algodão" value="<?= isset($_POST['nome_produto']) ? htmlspecialchars($_POST['nome_produto']) : '' ?>">
                                </div>

                                <div class="form-group">
                                    <label>Código de Barras (opcional)</label>
                                    <input type="text" name="codigo_barra" class="form-control" placeholder="7891234567890" value="<?= isset($_POST['codigo_barra']) ? htmlspecialchars($_POST['codigo_barra']) : '' ?>">
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Preço de Custo</label>
                                            <input type="text" name="preco_custo" class="form-control money" placeholder="0,00" value="<?= isset($_POST['preco_custo']) ? htmlspecialchars($_POST['preco_custo']) : '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Preço de Venda *</label>
                                            <input type="text" name="preco_venda" class="form-control money" required placeholder="0,00" value="<?= isset($_POST['preco_venda']) ? htmlspecialchars($_POST['preco_venda']) : '' ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Categoria *</label>
                                    <select name="id_categoria" class="form-control" required>
                                        <option value="">Selecione uma categoria...</option>
                                        <?php
                                        // Busca TODAS as categorias disponíveis
                                        try {
                                            $sql_cats = "SELECT * FROM tb_categorias ORDER BY nome_categoria ASC";
                                            $stmt_cats = $conect->query($sql_cats);
                                            $total_categorias = $stmt_cats->rowCount();
                                            
                                            if ($total_categorias > 0) {
                                                while ($c = $stmt_cats->fetch(PDO::FETCH_OBJ)) {
                                                    $selected = (isset($_POST['id_categoria']) && $_POST['id_categoria'] == $c->id_categoria) ? 'selected' : '';
                                                    echo "<option value='{$c->id_categoria}' $selected>{$c->nome_categoria}</option>";
                                                }
                                            } else {
                                                echo '<option value="" disabled>--- Nenhuma categoria cadastrada ---</option>';
                                            }
                                        } catch (Exception $e) {
                                            echo '<option value="" disabled>--- Erro ao carregar categorias ---</option>';
                                        }
                                        ?>
                                    </select>
                                    <?php if (isset($total_categorias) && $total_categorias == 0): ?>
                                        <small class="text-danger">
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            Você precisa cadastrar categorias primeiro.
                                        </small>
                                    <?php else: ?>
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i> 
                                            <?= isset($total_categorias) ? $total_categorias : '0' ?> categorias disponíveis
                                        </small>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label>Quantidade Inicial em Estoque *</label>
                                    <input type="number" name="estoque_inicial" class="form-control" min="0" value="<?= isset($_POST['estoque_inicial']) ? htmlspecialchars($_POST['estoque_inicial']) : '0' ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Descrição (opcional)</label>
                                    <textarea name="descricao_produto" class="form-control" rows="3" placeholder="Descreva o produto..."><?= isset($_POST['descricao_produto']) ? htmlspecialchars($_POST['descricao_produto']) : '' ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Foto do Produto</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="foto" id="foto" accept="image/*">
                                        <label class="custom-file-label" for="foto">Escolher arquivo</label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Formatos: JPG, PNG, GIF, WEBP (Máx: 5MB)
                                    </small>
                                </div>

                                <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">

                            </div>
                            <div class="card-footer">
                                <button type="submit" name="cadastrar" class="btn btn-primary btn-block">
                                    <i class="fas fa-save"></i> Cadastrar Produto
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- ==== PROCESSAMENTO DO CADASTRO ==== -->
                    <?php
                    if (isset($_POST['cadastrar'])) {
                        // Sanitização dos dados
                        $nome = trim($_POST['nome_produto'] ?? '');
                        $codigo_barra = !empty($_POST['codigo_barra']) ? trim($_POST['codigo_barra']) : null;
                        $preco_custo = !empty($_POST['preco_custo']) ? str_replace(['.', ','], ['', '.'], $_POST['preco_custo']) : 0.00;
                        $preco_venda = str_replace(['.', ','], ['', '.'], $_POST['preco_venda'] ?? '0');
                        $id_categoria = (int)($_POST['id_categoria'] ?? 0);
                        $descricao = trim($_POST['descricao_produto'] ?? '');
                        $estoque_inicial = (int)($_POST['estoque_inicial'] ?? 0);
                        $id_user_post = (int)($_POST['id_user'] ?? 0);

                        // Validações básicas
                        $erros = [];
                        
                        if (empty($nome)) {
                            $erros[] = "Nome do produto é obrigatório";
                        }
                        
                        if (empty($preco_venda) || $preco_venda <= 0) {
                            $erros[] = "Preço de venda deve ser maior que zero";
                        }
                        
                        if ($id_categoria <= 0) {
                            $erros[] = "Categoria é obrigatória";
                        }
                        
                        if ($estoque_inicial < 0) {
                            $erros[] = "Estoque inicial não pode ser negativo";
                        }

                        // Verifica se a categoria existe
                        if ($id_categoria > 0) {
                            try {
                                $sql_verifica_cat = "SELECT id_categoria FROM tb_categorias WHERE id_categoria = ?";
                                $stmt_verifica_cat = $conect->prepare($sql_verifica_cat);
                                $stmt_verifica_cat->execute([$id_categoria]);
                                
                                if ($stmt_verifica_cat->rowCount() == 0) {
                                    $erros[] = "Categoria selecionada não existe";
                                }
                            } catch (Exception $e) {
                                $erros[] = "Erro ao validar categoria: " . $e->getMessage();
                            }
                        }

                        // Upload da foto
                        $foto = 'produto-sem-foto.jpg';
                        if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                            $extensoes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
                            
                            if (in_array($ext, $extensoes)) {
                                // Verifica tamanho do arquivo (máximo 5MB)
                                if ($_FILES['foto']['size'] > 5 * 1024 * 1024) {
                                    $erros[] = "Arquivo muito grande. Máximo 5MB permitido.";
                                } else {
                                    $pasta = "../img/produtos/";
                                    if (!is_dir($pasta)) {
                                        mkdir($pasta, 0755, true);
                                    }
                                    $novo_nome = uniqid() . ".$ext";
                                    if (move_uploaded_file($_FILES['foto']['tmp_name'], $pasta . $novo_nome)) {
                                        $foto = $novo_nome;
                                    } else {
                                        $erros[] = "Erro ao fazer upload da imagem";
                                    }
                                }
                            } else {
                                $erros[] = "Formato de arquivo não permitido. Use: jpg, jpeg, png, gif ou webp";
                            }
                        }

                        // Se não há erros, processa o cadastro
                        if (empty($erros)) {
                            try {
                                $conect->beginTransaction();

                                // 1. Insere o produto
                                $sql = "INSERT INTO tb_produtos 
                                        (codigo_barra, nome_produto, descricao_produto, preco_custo, preco_venda, foto_produto, id_categoria, id_user, status)
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'ativo')";
                                $stmt = $conect->prepare($sql);
                                $stmt->execute([$codigo_barra, $nome, $descricao, $preco_custo, $preco_venda, $foto, $id_categoria, $id_user_post]);
                                $id_produto = $conect->lastInsertId();

                                // 2. Registra entrada inicial no estoque (se > 0)
                                if ($estoque_inicial > 0) {
                                    $sql2 = "INSERT INTO tb_estoque (id_produto, tipo_movimento, quantidade, motivo, id_user)
                                             VALUES (?, 'entrada', ?, 'Entrada inicial - cadastro', ?)";
                                    $stmt2 = $conect->prepare($sql2);
                                    $stmt2->execute([$id_produto, $estoque_inicial, $id_user_post]);
                                }

                                $conect->commit();

                                echo '<div class="alert alert-success alert-dismissible mt-3">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <i class="fas fa-check-circle"></i> Produto cadastrado com sucesso!
                                      </div>';
                                // Redireciona para mesma página para limpar o formulário
                                echo '<script>setTimeout(() => { window.location.reload(); }, 1500);</script>';

                            } catch (Exception $e) {
                                $conect->rollBack();
                                // Se for erro de duplicação de código de barras
                                if (strpos($e->getMessage(), 'codigo_barra') !== false) {
                                    $erro_msg = "Código de barras já existe no sistema";
                                } else {
                                    $erro_msg = htmlspecialchars($e->getMessage());
                                }
                                echo '<div class="alert alert-danger alert-dismissible mt-3">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>Erro:</strong> ' . $erro_msg . '
                                      </div>';
                            }
                        } else {
                            // Exibe erros de validação
                            echo '<div class="alert alert-danger alert-dismissible mt-3">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>Erros encontrados:</strong><ul>';
                            foreach ($erros as $erro) {
                                echo '<li>' . htmlspecialchars($erro) . '</li>';
                            }
                            echo '</ul></div>';
                        }
                    }
                    ?>
                </div>
                <!-- Fim formulário -->

                <!-- ==== LISTA DE PRODUTOS RECENTES ==== -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Produtos Recentes</h3>
                            <div class="card-tools">
                                <a href="?acao=categoria" class="btn btn-sm btn-info">
                                    <i class="fas fa-folder-plus"></i> Gerenciar Categorias
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Foto</th>
                                            <th>Produto</th>
                                            <th>Categoria</th>
                                            <th>Preço Venda</th>
                                            <th>Estoque</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    try {
                                        $sql = "SELECT p.*, c.nome_categoria, COALESCE(v.estoque_atual, 0) as estoque_atual
                                                FROM tb_produtos p
                                                LEFT JOIN tb_categorias c ON p.id_categoria = c.id_categoria
                                                LEFT JOIN vw_estoque_atual v ON p.id_produto = v.id_produto
                                                WHERE p.id_user = ?
                                                ORDER BY p.id_produto DESC LIMIT 15";
                                        $stmt = $conect->prepare($sql);
                                        $stmt->execute([$id_user]);
                                        $cont = 1;

                                        if ($stmt->rowCount() > 0) {
                                            while ($p = $stmt->fetch(PDO::FETCH_OBJ)) {
                                                $status_badge = $p->status == 'ativo' ? 'bg-success' : 'bg-danger';
                                                $estoque_badge = $p->estoque_atual > 0 ? 'bg-success' : 'bg-danger';
                                    ?>
                                                <tr>
                                                    <td><?= $cont++ ?></td>
                                                    <td>
                                                        <img src="../img/produtos/<?= htmlspecialchars($p->foto_produto) ?>" 
                                                             class="img-circle elevation-2" 
                                                             style="width:45px;height:45px;object-fit:cover;"
                                                            
                                                             
                                                             onerror="this.src='../img/avatar_p/avatar-padrao.png'">
                                                    </td>
                                                    <td>
                                                        <strong><?= htmlspecialchars($p->nome_produto) ?></strong>
                                                        <?php if (!empty($p->codigo_barra)): ?>
                                                            <br><small class="text-muted"><?= htmlspecialchars($p->codigo_barra) ?></small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($p->nome_categoria) ?></td>
                                                    <td>R$ <?= number_format($p->preco_venda, 2, ',', '.') ?></td>
                                                    <td>
                                                        <span class="badge <?= $estoque_badge ?>">
                                                            <?= $p->estoque_atual ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge <?= $status_badge ?>">
                                                            <?= ucfirst($p->status) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="?acao=editar_produto&id=<?= $p->id_produto ?>" class="btn btn-sm btn-warning" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="?acao=excluir_produto&id=<?= $p->id_produto ?>" 
                                                           onclick="return confirm('Tem certeza que deseja excluir este produto permanentemente?')" 
                                                           class="btn btn-sm btn-danger" title="Excluir">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                    <?php 
                                            }
                                        } else {
                                    ?>
                                            <tr>
                                                <td colspan="8" class="text-center py-4">
                                                    <i class="fas fa-box-open fa-2x text-muted mb-2"></i><br>
                                                    Nenhum produto cadastrado ainda.<br>
                                                    <small class="text-muted">Use o formulário ao lado para cadastrar seu primeiro produto</small>
                                                </td>
                                            </tr>
                                    <?php 
                                        }
                                        
                                    } catch (Exception $e) {
                                        echo '<tr><td colspan="8" class="text-center text-danger">Erro ao carregar produtos: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
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
        
        // Atualiza label do file input
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').text(fileName || 'Escolher arquivo');
        });
    });
</script>