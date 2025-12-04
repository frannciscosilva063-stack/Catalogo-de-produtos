<?php
// Inicia buffer de saída o mais cedo possível (ajuda a evitar "headers already sent")
if (!ob_get_level()) {
    ob_start();
}

// Garantir que exista a conexão PDO quando o script for chamado diretamente (ex.: export via home.php?export=...)
if (!isset($conect) || !($conect instanceof PDO)) {
    $possible = [
        __DIR__ . '/../../config/conexao.php', // normalmente: /Catalogo-de-produtos/config/conexao.php
        __DIR__ . '/../config/conexao.php',
        __DIR__ . '/config/conexao.php'
    ];
    foreach ($possible as $p) {
        if (file_exists($p)) { require_once $p; break; }
    }
}

// Inicia sessão para mensagens


// -------------------------------------------------
// Carregamento de produtos
// -------------------------------------------------
try {
    // Garante que a conexão PDO exista
    if (!isset($conect) || !($conect instanceof PDO)) {
        throw new Exception('Conexão PDO não encontrada.');
    }
    // Prepara e executa a query de produtos (filtrando por id_user se houver)
    if (!empty($id_user)) {
        $sql = "SELECT p.id_produto, p.nome_produto, p.preco_venda, p.foto_produto, p.descricao_produto, p.id_categoria, c.nome_categoria
                FROM tb_produtos p
                LEFT JOIN tb_categorias c ON p.id_categoria = c.id_categoria
                WHERE p.id_user = ? AND p.status = 'ativo'
                ORDER BY p.nome_produto ASC";
        $stmt = $conect->prepare($sql);
        $stmt->execute([$id_user]);
    } else {
        $sql = "SELECT p.id_produto, p.nome_produto, p.preco_venda, p.foto_produto, p.descricao_produto, p.id_categoria, c.nome_categoria
                FROM tb_produtos p
                LEFT JOIN tb_categorias c ON p.id_categoria = c.id_categoria
                WHERE p.status = 'ativo'
                ORDER BY p.nome_produto ASC";
        $stmt = $conect->query($sql);
    }
    $produtos = $stmt->fetchAll(PDO::FETCH_OBJ);
} catch (Exception $e) {
    echo '<div class="alert alert-danger m-3">Erro ao carregar produtos: '.htmlspecialchars($e->getMessage()).'</div>';
    $produtos = [];
}

// --- NOVO: calcular base pública para imagens (ex.: /Catalogo-de-produtos/img/produtos/)
$scriptRoot = @dirname($_SERVER['SCRIPT_NAME'], 2); // geralmente "/Catalogo-de-produtos"
if ($scriptRoot === '/' || $scriptRoot === '\\' || $scriptRoot === '.') { $scriptRoot = ''; }
$imgUrlBase = rtrim($scriptRoot, '/\\') . '/img/produtos/';
$avatarFallback = rtrim($scriptRoot, '/\\') . '/img/img_padrao/img.jpeg';

// -------------------------------------------------
// Export handlers: csv, xls (CSV com extensão .xls), json, print, pdf (redireciona)
// -------------------------------------------------
if (isset($_GET['export'])) {
    $export = strtolower($_GET['export']);
    $userParam = isset($id_user) ? $id_user : '';
    // Normalize data rows
    $rows = [];
    foreach ($produtos as $p) {
        $rows[] = [
            'id' => (int)$p->id_produto,
            'nome' => $p->nome_produto ?? '',
            'categoria' => $p->nome_categoria ?? '',
            'preco' => isset($p->preco_venda) ? number_format($p->preco_venda, 2, '.', '') : '0.00',
            'descricao' => $p->descricao_produto ?? '',
            'foto' => $p->foto_produto ?? '',
        ];
    }

    if ($export === 'csv' || $export === 'xls') {
        // CSV output (XLS apenas extensão diferente)
        $isXls = ($export === 'xls');
        $ext = $isXls ? 'xls' : 'csv';
        $filename = 'produtos_'.date('Ymd').'.'.$ext; // nome final do arquivo

        // Gera o CSV em memória (string) para permitir fallback cliente-side se headers já foram enviados
        $fp = fopen('php://temp', 'r+');
        // BOM para suportar acentuação no Excel
        fwrite($fp, "\xEF\xBB\xBF");
        fputcsv($fp, ['ID','Nome do Produto','Categoria','Descrição','Preço','Foto']);
        foreach ($rows as $r) {
            fputcsv($fp, [$r['id'],$r['nome'],$r['categoria'],$r['descricao'],str_replace('.',',',$r['preco']),$r['foto']]);
        }
        rewind($fp);
        $csvContent = stream_get_contents($fp);
        fclose($fp);

        // Se ainda for possível enviar headers, faz download diretamente
        if (!headers_sent()) {
            // limpa buffer atual mantendo ob ativo
            if (ob_get_level()) { @ob_clean(); }
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            echo $csvContent;
            exit;
        }

        // Fallback: se headers já foram enviados (ex.: includes já imprimiram HTML), força download via JS criando um Blob
        $escapedCsv = json_encode($csvContent); // json_encode cuida do escaping do conteúdo
        ?>
        <!doctype html>
        <html lang="pt-br">
        <head><meta charset="utf-8"><title>Exportando CSV</title></head>
        <body>
        <p>Se o download não iniciar automaticamente, clique no link abaixo.</p>
        <a id="dl" href="#">Baixar CSV</a>
        <script>
        (function(){
            try {
                var csv = <?php echo $escapedCsv; ?>;
                var filename = <?php echo json_encode($filename); ?>;
                var blob = new Blob([csv], {type: 'text/csv;charset=utf-8;'});
                if (window.navigator && window.navigator.msSaveOrOpenBlob) {
                    // IE fallback
                    window.navigator.msSaveOrOpenBlob(blob, filename);
                } else {
                    var url = URL.createObjectURL(blob);
                    var a = document.getElementById('dl');
                    a.href = url;
                    a.download = filename;
                    // inicia download automático
                    var ev = document.createEvent('MouseEvents');
                    ev.initEvent('click', true, true);
                    a.dispatchEvent(ev);
                    // revoke após curto delay
                    setTimeout(function(){ URL.revokeObjectURL(url); }, 2000);
                }
            } catch (e) {
                document.getElementById('dl').textContent = 'Clique aqui para baixar o CSV';
            }
        })();
        </script>
        </body>
        </html>
        <?php
        exit;
    }

    if ($export === 'json') {
        // limpa o buffer atual mantendo buffering ativo
        if (ob_get_level()) { ob_clean(); }
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=UTF-8');
        }
        echo json_encode($rows, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        exit;
    }

    if ($export === 'print') {
        // Simples página imprimível com tabela
        ?>
        <!doctype html>
        <html lang="pt-br">
        <head>
          <meta charset="utf-8">
          <title>Relatório de Produtos - Impressão</title>
          <style>
            body{font-family:Arial,Helvetica,sans-serif;font-size:12px;margin:20px}
            table{width:100%;border-collapse:collapse}
            th,td{border:1px solid #999;padding:6px;text-align:left}
            img{max-width:60px;height:auto}
            @media print { .no-print{display:none} }
          </style>
        </head>
        <body>
          <h2>Relatório de Produtos</h2>
          <p class="no-print">Clique em imprimir do navegador (Ctrl+P).</p>
          <table>
            <thead>
              <tr><th>#</th><th>Foto</th><th>Nome</th><th>Categoria</th><th>Descrição</th><th>Preço</th></tr>
            </thead>
            <tbody>
              <?php foreach ($rows as $r): ?>
                <tr>
                  <td><?php echo $r['id']; ?></td>
                  <td>
                    <?php
                      $fotoFile = $r['foto'] ?: 'produto-sem-foto.jpg';
                      $fotoUrl = htmlspecialchars($imgUrlBase . rawurlencode($fotoFile));
                      $avatar = htmlspecialchars($avatarFallback);
                    ?>
                    <img src="<?php echo $fotoUrl; ?>" alt="foto" onerror="this.onerror=null;this.src='<?php echo $avatar; ?>'">
                  </td>
                  <td><?php echo htmlspecialchars($r['nome']); ?></td>
                  <td><?php echo htmlspecialchars($r['categoria']); ?></td>
                  <td><?php echo htmlspecialchars(mb_strimwidth($r['descricao'], 0, 200, '...')); ?></td>
                  <td>R$ <?php echo number_format((float)str_replace(',','.',$r['preco']),2,',','.'); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <script>window.print();</script>
        </body>
        </html>
        <?php
        exit;
    }

    if ($export === 'pdf') {
        // Gera PDF server-side via Dompdf (se instalado)
        $html = '<!doctype html><html lang="pt-br"><head><meta charset="utf-8"><style>
            body{font-family:Arial,Helvetica,sans-serif;font-size:12px}
            table{width:100%;border-collapse:collapse}
            th,td{border:1px solid #999;padding:6px;text-align:left}
            img{max-width:60px;height:auto}
            </style></head><body>';
        $html .= '<h2>Relatório de Produtos</h2>';
        $html .= '<table><thead><tr><th>#</th><th>Foto</th><th>Nome</th><th>Categoria</th><th>Descrição</th><th>Preço</th></tr></thead><tbody>';
        foreach ($rows as $r) {
            $html .= '<tr>';
            $html .= '<td>'.htmlspecialchars($r['id']).'</td>';
            $html .= '<td>'.($r['foto']?'<img src="'.htmlspecialchars('../img/produtos/'.$r['foto']).'" alt="foto">':'').'</td>';
            $html .= '<td>'.htmlspecialchars($r['nome']).'</td>';
            $html .= '<td>'.htmlspecialchars($r['categoria']).'</td>';
            $html .= '<td>'.htmlspecialchars(mb_strimwidth($r['descricao'],0,300,'...')).'</td>';
            $html .= '<td>R$ '.number_format((float)str_replace(',','.',$r['preco']),2,',','.').'</td>';
            $html .= '</tr>';
         }
         $html .= '</tbody></table></body></html>';

        // tenta carregar autoload do composer em locais comuns
        $autoloadPaths = [
            __DIR__ . '/vendor/autoload.php'
        ];
        $loaded = false;
        foreach ($autoloadPaths as $a) {
            if (file_exists($a)) { require_once $a; $loaded = true; break; }
        }

        if (class_exists('\Dompdf\Dompdf')) {
            try {
                $dompdf = new \Dompdf\Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                // 'Attachment' => 0 abre no navegador; 1 força download
                $dompdf->stream('relatorio_produtos_'.date('Ymd').'.pdf', ['Attachment' => 0]);
                exit;
            } catch (Exception $ex) {
                echo '<div class="alert alert-danger m-3">Erro ao gerar PDF: '.htmlspecialchars($ex->getMessage()).'</div>';
            }
        } else {
            // instrução curta caso Dompdf não esteja instalado
            echo '<div class="alert alert-warning m-3">Dompdf não encontrado. Instale com: <code>composer require dompdf/dompdf</code> e coloque o autoload na pasta vendor.</div>';
            echo '<div class="m-3">Alternativamente, use o botão "Imprimir / PDF" para gerar PDF via navegador.</div>';
        }
    }
}

// ----
// -------------------------------------------------
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Exibir mensagens de sucesso/erro -->
    <?php if (isset($_SESSION['msg_sucesso'])): ?>
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            <?php echo htmlspecialchars($_SESSION['msg_sucesso']); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['msg_sucesso']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['msg_erro'])): ?>
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            <?php echo htmlspecialchars($_SESSION['msg_erro']); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['msg_erro']); ?>
    <?php endif; ?>

    <!-- ...existing markup... -->
                </table>

                <!-- INÍCIO: Controles de busca e filtros -->
                <?php
                  // monta lista única de categorias para o select
                  $cats = [];
                  foreach ($produtos as $p) {
                      $catName = trim((string)($p->nome_categoria ?? ''));
                      if ($catName !== '') { $cats[] = $catName; }
                  }
                  $cats = array_values(array_unique($cats));
                  sort($cats);
                ?>
                <div class="row mb-3">
                  <div class="col-md-6">
                    <input id="prod-search" class="form-control" type="search" placeholder="Pesquisar produto por nome...">
                  </div>
                  <div class="col-md-4">
                    <select id="prod-cat" class="form-control">
                      <option value="">Todas as categorias</option>
                      <?php foreach ($cats as $c): ?>
                        <option value="<?php echo htmlspecialchars($c); ?>"><?php echo htmlspecialchars($c); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <button id="prod-clear" class="btn btn-secondary btn-block" type="button">Limpar</button>
                  </div>
                </div>
                <!-- FIM: Controles -->

                <!-- INÍCIO: Lista de produtos (cards) dinâmica -->
                <div class="container-fluid my-4">
                  <div id="products-row" class="row">
                    <?php if (!empty($produtos)): ?>
                      <?php foreach ($produtos as $p): ?>
                        <?php
                          $fotoName = !empty($p->foto_produto) ? $p->foto_produto : 'produto-sem-foto.jpg';
                          $foto = htmlspecialchars($imgUrlBase . rawurlencode($fotoName));
                          $nome = htmlspecialchars($p->nome_produto ?? '—');
                          $cat  = htmlspecialchars($p->nome_categoria ?? '—');
                          $preco = number_format((float)($p->preco_venda ?? 0), 2, ',', '.');
                          $idProduto = (int)($p->id_produto ?? 0);
                          // atributos para filtragem (em lowercase para facilitar)
                          $dataName = strtolower($p->nome_produto ?? '');
                          $dataCat  = strtolower($p->nome_categoria ?? '');
                        ?>
                        <div class="col-sm-6 col-md-4 col-lg-3 mb-3 product-card" 
                             data-name="<?php echo htmlspecialchars($dataName); ?>" 
                             data-category="<?php echo htmlspecialchars($dataCat); ?>">
                          <div class="card h-100 position-relative">
                            <!-- Menu de ações (ícone três pontos) -->
                            <div class="position-absolute" style="top:10px; right:10px; z-index:10;">
                              <div class="dropdown">
                                <button class="btn btn-sm btn-light rounded-circle shadow-sm" type="button" 
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                        style="width:32px; height:32px; padding:0; display:flex; align-items:center; justify-content:center;">
                                  <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                 <a href="?acao=editar_produto&id=<?= (int)$p->id_produto ?>" class="btn btn-sm btn-warning" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                  <form method="post" action="<?= htmlspecialchars($_SERVER['SCRIPT_NAME']) . '?acao=excluir_produto&id=' . (int)$p->id_produto ?>" 
                                                              onsubmit="return confirm('Tem certeza que deseja excluir este produto permanentemente?');" 
                                                              style="display:inline">
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Excluir">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                </div>
                              </div>
                            </div>
                            
                            <img src="<?php echo $foto; ?>" class="card-img-top" alt="<?php echo $nome; ?>" 
                                 style="height:220px;object-fit:cover;" 
                                 onerror="this.onerror=null;this.src='<?php echo htmlspecialchars($avatarFallback); ?>'">
                            
                            <div class="card-body d-flex flex-column">
                              <h6 class="card-title mb-1"><?php echo $nome; ?></h6>
                              <p class="text-muted mb-2 small"><?php echo $cat; ?></p>
                              <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="font-weight-bold">R$ <?php echo $preco; ?></span>
                                <span class="badge badge-success">Ativo</span>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- Modal de Edição -->
                        <div class="modal fade" id="editModal<?php echo $idProduto; ?>" tabindex="-1" role="dialog" 
                             aria-labelledby="editModalLabel<?php echo $idProduto; ?>" aria-hidden="true">
                          <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                              <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="acao" value="atualizar-produto">
                                <input type="hidden" name="id_produto" value="<?php echo $idProduto; ?>">
                                <div class="modal-header bg-primary text-white">
                                  <h5 class="modal-title" id="editModalLabel<?php echo $idProduto; ?>">
                                    <i class="fas fa-edit mr-2"></i> Editar Produto
                                  </h5>
                                  <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <div class="form-group">
                                        <label for="nome<?php echo $idProduto; ?>">Nome do Produto *</label>
                                        <input type="text" class="form-control" id="nome<?php echo $idProduto; ?>" 
                                               name="nome_produto" value="<?php echo $nome; ?>" required>
                                      </div>
                                      
                                      <div class="row">
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label for="preco<?php echo $idProduto; ?>">Preço de Venda *</label>
                                            <div class="input-group">
                                              <div class="input-group-prepend">
                                                <span class="input-group-text">R$</span>
                                              </div>
                                              <input type="number" class="form-control" id="preco<?php echo $idProduto; ?>" 
                                                     name="preco_venda" step="0.01" min="0" 
                                                     value="<?php echo $p->preco_venda ?? 0; ?>" required>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label for="categoria<?php echo $idProduto; ?>">Categoria</label>
                                            <select class="form-control" id="categoria<?php echo $idProduto; ?>" name="id_categoria">
                                              <option value="">Selecione uma categoria</option>
                                              <?php
                                                // Buscar categorias disponíveis
                                                try {
                                                  $sqlCategorias = "SELECT id_categoria, nome_categoria FROM tb_categorias WHERE status = 'ativo' ORDER BY nome_categoria";
                                                  $stmtCategorias = $conect->query($sqlCategorias);
                                                  $categorias = $stmtCategorias->fetchAll(PDO::FETCH_OBJ);
                                                  foreach ($categorias as $categoria) {
                                                    $selected = ($categoria->id_categoria == $p->id_categoria) ? 'selected' : '';
                                                    echo '<option value="' . $categoria->id_categoria . '" ' . $selected . '>' 
                                                         . htmlspecialchars($categoria->nome_categoria) . '</option>';
                                                  }
                                                } catch (Exception $e) {
                                                  echo '<option value="">Erro ao carregar categorias</option>';
                                                }
                                              ?>
                                            </select>
                                          </div>
                                        </div>
                                      </div>
                                      
                                      <div class="form-group">
                                        <label for="descricao<?php echo $idProduto; ?>">Descrição</label>
                                        <textarea class="form-control" id="descricao<?php echo $idProduto; ?>" 
                                                  name="descricao_produto" rows="3"><?php echo htmlspecialchars($p->descricao_produto ?? ''); ?></textarea>
                                      </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                      <div class="form-group text-center">
                                        <label>Foto Atual</label>
                                        <div class="mb-3">
                                          <img src="<?php echo $foto; ?>" class="img-thumbnail" alt="Foto atual" 
                                               style="max-height:150px; object-fit:cover;"
                                               onerror="this.onerror=null;this.src='<?php echo htmlspecialchars($avatarFallback); ?>'">
                                        </div>
                                        <label for="novaFoto<?php echo $idProduto; ?>">Alterar Foto</label>
                                        <div class="custom-file">
                                          <input type="file" class="custom-file-input" id="novaFoto<?php echo $idProduto; ?>" 
                                                 name="foto_produto" accept="image/*">
                                          <label class="custom-file-label" for="novaFoto<?php echo $idProduto; ?>">Escolher arquivo</label>
                                        </div>
                                        <small class="form-text text-muted">Deixe em branco para manter a foto atual</small>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                  <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i> Salvar Alterações
                                  </button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>

                        <!-- Modal de Exclusão -->
                        <div class="modal fade" id="deleteModal<?php echo $idProduto; ?>" tabindex="-1" role="dialog" 
                             aria-labelledby="deleteModalLabel<?php echo $idProduto; ?>" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <form method="POST">
                                <input type="hidden" name="acao" value="excluir-produto">
                                <input type="hidden" name="id_produto" value="<?php echo $idProduto; ?>">
                                <div class="modal-header bg-danger text-white">
                                  <h5 class="modal-title" id="deleteModalLabel<?php echo $idProduto; ?>">
                                    <i class="fas fa-trash mr-2"></i> Confirmar Exclusão
                                  </h5>
                                  <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <div class="text-center mb-3">
                                    <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                                  </div>
                                  <h5 class="text-center">Tem certeza que deseja excluir este produto?</h5>
                                  <p class="text-center">
                                    <strong><?php echo $nome; ?></strong><br>
                                    <small class="text-muted">Esta ação não pode ser desfeita</small>
                                  </p>
                                  <div class="alert alert-warning">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    O produto será marcado como inativo e não aparecerá mais no catálogo.
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                  <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash mr-2"></i> Sim, Excluir Produto
                                  </button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <div class="col-12">
                        <div class="alert alert-info">Nenhum produto encontrado.</div>
                      </div>
                    <?php endif; ?>
                  </div>

                  <div id="no-results" class="row d-none">
                    <div class="col-12">
                      <div class="alert alert-warning">Nenhum produto corresponde aos filtros.</div>
                    </div>
                  </div>
                </div>
                <!-- FIM: Lista de produtos -->

                <!-- Estilo para cards de produtos -->
                <style>
                  .product-card .card {
                    border: none;
                    border-radius: 12px;
                    overflow: hidden;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
                    transition: all 0.3s ease;
                  }

                  .product-card .card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 10px 30px rgba(44, 90, 160, 0.15);
                  }

                  .product-card .card-img-top {
                    background: linear-gradient(135deg, #f5f7fa, #e9ecef);
                    height: 180px;
                    object-fit: cover;
                    object-position: center;
                  }

                  .product-card .card-body {
                    padding: 16px;
                  }

                  .product-card .card-title {
                    font-weight: 700;
                    color: #2D3047;
                    margin-bottom: 8px;
                  }

                  .product-card .text-muted {
                    color: #6C757D !important;
                  }

                  .product-card .card-body .small {
                    font-size: 0.85rem;
                  }

                  .product-card .font-weight-bold {
                    color: #2C5AA0;
                    font-size: 1.1rem;
                  }

                  .card img {
                    max-width: 100%;
                    height: auto;
                  }
                </style>

                <!-- Script de filtragem client-side -->
                <script>
                (function(){
                  var input = document.getElementById('prod-search');
                  var select = document.getElementById('prod-cat');
                  var clearBtn = document.getElementById('prod-clear');
                  var cards = Array.prototype.slice.call(document.querySelectorAll('.product-card'));
                  var noResults = document.getElementById('no-results');

                  function normalize(s){ return (s||'').toString().trim().toLowerCase(); }

                  function filter(){
                    var q = normalize(input.value);
                    var cat = normalize(select.value);
                    var visible = 0;
                    cards.forEach(function(card){
                      var name = normalize(card.getAttribute('data-name'));
                      var c = normalize(card.getAttribute('data-category'));
                      var match = true;
                      if (q !== '' && name.indexOf(q) === -1) match = false;
                      if (cat !== '' && c !== cat) match = false;
                      card.style.display = match ? '' : 'none';
                      if (match) visible++;
                    });
                    if (visible === 0) {
                      noResults.classList.remove('d-none');
                    } else {
                      noResults.classList.add('d-none');
                    }
                  }

                  // debounce simples
                  var timer = null;
                  function debounceFilter(){
                    if (timer) clearTimeout(timer);
                    timer = setTimeout(filter, 150);
                  }

                  input.addEventListener('input', debounceFilter);
                  select.addEventListener('change', filter);
                  clearBtn.addEventListener('click', function(){
                    input.value = '';
                    select.value = '';
                    filter();
                  });

                  // init
                  filter();
                })();
                </script>

                <!-- Script para custom file input e validação -->
                <script>
                // Atualizar label do file input
                document.querySelectorAll('.custom-file-input').forEach(function(input) {
                  input.addEventListener('change', function(e) {
                    var fileName = e.target.files[0] ? e.target.files[0].name : 'Escolher arquivo';
                    var label = e.target.nextElementSibling;
                    label.textContent = fileName;
                  });
                });

                // Prevenir envio do formulário se houver erro
                document.querySelectorAll('form').forEach(function(form) {
                  form.addEventListener('submit', function(e) {
                    var required = form.querySelectorAll('[required]');
                    var valid = true;
                    
                    required.forEach(function(field) {
                      if (!field.value.trim()) {
                        valid = false;
                        field.classList.add('is-invalid');
                      } else {
                        field.classList.remove('is-invalid');
                      }
                    });
                    
                    if (!valid) {
                      e.preventDefault();
                      alert('Por favor, preencha todos os campos obrigatórios.');
                    }
                  });
                });
                </script>

                <div class="col-lg-12 d-flex justify