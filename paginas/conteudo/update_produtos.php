<?php
// início: sessão e conexão
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$id_user = $_SESSION['id_user'] ?? null;

// tenta garantir $conect (opcional — ajusta conforme seu projeto)
if (!isset($conect) || !($conect instanceof PDO)) {
    $candidatos = [
        __DIR__ . '/../config/conexao.php',
    ];
    foreach ($candidatos as $f) {
        if (file_exists($f)) { include_once $f; if (isset($conect) && $conect instanceof PDO) break; }
    }
}

// valida id do produto
$id_produto = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_produto <= 0) {
    echo '<div class="alert alert-danger m-3">ID de produto inválido.</div>';
    return;
}

// busca produto (garante que pertence ao usuário, se aplicável)
try {
    $stmt = $conect->prepare("SELECT * FROM tb_produtos WHERE id_produto = ? AND id_user = ?");
    $stmt->execute([$id_produto, $id_user]);
    $produto = $stmt->fetch(PDO::FETCH_OBJ);
    if (!$produto) {
        echo '<div class="alert alert-warning m-3">Produto não encontrado ou acesso negado.</div>';
        return;
    }
} catch (Exception $e) {
    echo '<div class="alert alert-danger m-3">Erro ao buscar produto: '.htmlspecialchars($e->getMessage()).'</div>';
    return;
}

// carrega categorias para select
$categorias = [];
try {
    $c = $conect->query("SELECT * FROM tb_categorias ORDER BY nome_categoria ASC");
    $categorias = $c->fetchAll(PDO::FETCH_OBJ);
} catch (Exception $e) {
    // mantém categorias vazias
}

// processa submissão do formulário
if (isset($_POST['upProduto'])) {
    $nome           = trim($_POST['nome_produto'] ?? '');
    $codigo_barra   = trim($_POST['codigo_barra'] ?? '') ?: null;
    $preco_custo    = !empty($_POST['preco_custo']) ? (float)str_replace(['.', ','], ['', '.'], $_POST['preco_custo']) : null;
    $preco_venda    = (float)str_replace(['.', ','], ['', '.'], $_POST['preco_venda'] ?? '0');
    $id_categoria   = (int)($_POST['id_categoria'] ?? 0);
    $descricao      = trim($_POST['descricao_produto'] ?? '');
    $foto_atual     = $produto->foto_produto ?? 'produto-sem-foto.jpg';

    // upload da nova foto (opcional)
    $foto = $foto_atual;
    if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $extensoes = ['jpg','jpeg','png','gif','webp'];
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $tmp = $_FILES['foto']['tmp_name'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $tmp);
        finfo_close($finfo);
        $mimes_permitidos = ['image/jpeg','image/png','image/gif','image/webp'];

        if (in_array($ext, $extensoes) && in_array($mime, $mimes_permitidos)) {
            $pasta = __DIR__ . '/../../img/produtos/';
            if (!is_dir($pasta)) mkdir($pasta, 0755, true);
            $novo_nome = uniqid('prod_') . ".$ext";
            if (move_uploaded_file($tmp, $pasta . $novo_nome)) {
                // opcional: remover arquivo antigo (cuidado com shared files)
                if ($foto_atual && $foto_atual !== 'produto-sem-foto.jpg' && file_exists($pasta . $foto_atual)) {
                    @unlink($pasta . $foto_atual);
                }
                $foto = $novo_nome;
            } else {
                echo '<div class="alert alert-warning">Falha ao enviar a imagem. Mantendo imagem atual.</div>';
            }
        } else {
            echo '<div class="alert alert-warning">Formato de imagem não permitido. Mantendo imagem atual.</div>';
        }
    }

    // atualiza registro
    try {
        $sql = "UPDATE tb_produtos SET codigo_barra = ?, nome_produto = ?, descricao_produto = ?, preco_custo = ?, preco_venda = ?, foto_produto = ?, id_categoria = ? WHERE id_produto = ? AND id_user = ?";
        $upd = $conect->prepare($sql);
        $upd->execute([$codigo_barra, $nome, $descricao, $preco_custo, $preco_venda, $foto, $id_categoria, $id_produto, $id_user]);

        echo '<div class="alert alert-success m-3">Produto atualizado com sucesso.</div>';
        // antigo redirecionamento para arquivo possivelmente inexistente:
        // echo '<script>setTimeout(()=>location.href="editar-produto.php?id='.$id_produto.'",1200);</script>';
        // substitui por reload seguro da mesma URL (mantém parâmetros)
        echo '<script>
                setTimeout(function(){
                    // recarrega a mesma página atual (mantém path e query string)
                    window.location.href = window.location.pathname + window.location.search;
                }, 1200);
              </script>';

        // recarrega dados do produto
        $stmt = $conect->prepare("SELECT * FROM tb_produtos WHERE id_produto = ? AND id_user = ?");
        $stmt->execute([$id_produto, $id_user]);
        $produto = $stmt->fetch(PDO::FETCH_OBJ);
    } catch (Exception $e) {
        echo '<div class="alert alert-danger m-3">Erro ao atualizar: '.htmlspecialchars($e->getMessage()).'</div>';
    }
}
?>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Editar Produto</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      
        <div class="row">
          <!-- left column: formulário -->
          <div class="col-md-6">
            <div class="card card-primary">
              <div class="card-header"><h3 class="card-title">Editar produto</h3></div>
              <form role="form" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="form-group">
                    <label>Nome do Produto</label>
                    <input type="text" class="form-control" name="nome_produto" required value="<?php echo htmlspecialchars($produto->nome_produto); ?>">
                  </div>

                  <div class="form-group">
                    <label>Código de Barras</label>
                    <input type="text" class="form-control" name="codigo_barra" value="<?php echo htmlspecialchars($produto->codigo_barra); ?>">
                  </div>

                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <label>Preço de Custo</label>
                        <input type="text" class="form-control money" name="preco_custo" value="<?php echo $produto->preco_custo !== null ? number_format($produto->preco_custo,2,',','.') : ''; ?>">
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label>Preço de Venda</label>
                        <input type="text" class="form-control money" name="preco_venda" required value="<?php echo number_format($produto->preco_venda,2,',','.'); ?>">
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Categoria</label>
                    <select name="id_categoria" class="form-control" required>
                      <option value="">Selecione uma categoria...</option>
                      <?php foreach ($categorias as $cat): ?>
                        <option value="<?php echo (int)$cat->id_categoria; ?>" <?php echo ($cat->id_categoria == $produto->id_categoria) ? 'selected' : ''; ?>>
                          <?php echo htmlspecialchars($cat->nome_categoria); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="form-group">
                    <label>Descrição</label>
                    <textarea name="descricao_produto" class="form-control" rows="4"><?php echo htmlspecialchars($produto->descricao_produto); ?></textarea>
                  </div>

                  <div class="form-group">
                    <label>Foto do Produto (opcional)</label>
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" name="foto" id="foto" accept="image/*">
                      <label class="custom-file-label" for="foto">Escolher arquivo</label>
                    </div>
                  </div>

                </div>
                <div class="card-footer">
                  <button type="submit" name="upProduto" class="btn btn-primary">Salvar alterações</button>
                </div>
              </form>
            </div>
          </div>
          
          <!-- right column: preview -->
          <div class="col-md-6">
            <div class="card">
              <div class="card-header"><h3 class="card-title">Visualização do Produto</h3></div>
              <div class="card-body text-center">
                <img src="<?php echo '../img/produtos/'.htmlspecialchars($produto->foto_produto ?: 'produto-sem-foto.jpg'); ?>" alt="Foto" style="width:150px;height:150px;object-fit:cover;border-radius:6px;">
                <h3 class="mt-3"><?php echo htmlspecialchars($produto->nome_produto); ?></h3>
                <p><strong>Categoria:</strong> <?php
                    $catNome = '';
                    foreach ($categorias as $cat) if ($cat->id_categoria == $produto->id_categoria) { $catNome = $cat->nome_categoria; break; }
                    echo htmlspecialchars($catNome);
                ?></p>
                <p><strong>Preço Venda:</strong> R$ <?php echo number_format($produto->preco_venda,2,',','.'); ?></p>
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
document.addEventListener('DOMContentLoaded', function(){
    $('.money').mask('000.000.000,00', {reverse: true});
    // atualiza label do custom-file quando selecionar arquivo
    document.querySelectorAll('.custom-file-input').forEach(function(input){
        input.addEventListener('change', function(e){
            var fileName = e.target.files.length ? e.target.files[0].name : 'Escolher arquivo';
            var label = e.target.nextElementSibling;
            if(label) label.textContent = fileName;
        });
    });
});
</script>
