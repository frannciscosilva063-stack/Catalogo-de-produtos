<?php
// arquivo standalone: não inclui header.php para evitar saída prévia
// ... configurações mínimas ...
require_once __DIR__ . '/../../config/conexao.php'; // ajusta caminho para o arquivo de conexão

// obter parâmetros
$export = strtolower($_GET['export'] ?? '');
$id_user = $_GET['id_user'] ?? ''; // se estiver sendo usado
// consulta produtos (mesma lógica do relatorio)
try {
    if (!isset($conect) || !($conect instanceof PDO)) {
        throw new Exception('Conexão PDO não encontrada.');
    }
    if (!empty($id_user)) {
        $sql = "SELECT p.id_produto, p.nome_produto, p.preco_venda, p.foto_produto, c.nome_categoria, p.descricao_produto
                FROM tb_produtos p
                LEFT JOIN tb_categorias c ON p.id_categoria = c.id_categoria
                WHERE p.id_user = ? AND p.status = 'ativo'
                ORDER BY p.nome_produto ASC";
        $stmt = $conect->prepare($sql);
        $stmt->execute([$id_user]);
    } else {
        $sql = "SELECT p.id_produto, p.nome_produto, p.preco_venda, p.foto_produto, c.nome_categoria, p.descricao_produto
                FROM tb_produtos p
                LEFT JOIN tb_categorias c ON p.id_categoria = c.id_categoria
                WHERE p.status = 'ativo'
                ORDER BY p.nome_produto ASC";
        $stmt = $conect->query($sql);
    }
    $produtos = $stmt->fetchAll(PDO::FETCH_OBJ);
} catch (Exception $e) {
    http_response_code(500);
    echo 'Erro ao carregar produtos: ' . htmlspecialchars($e->getMessage());
    exit;
}

// normaliza linhas
$rows = [];
foreach ($produtos as $p) {
    $rows[] = [
        'id' => (int)($p->id_produto ?? 0),
        'nome' => $p->nome_produto ?? '',
        'categoria' => $p->nome_categoria ?? '',
        'preco' => isset($p->preco_venda) ? number_format($p->preco_venda, 2, '.', '') : '0.00',
        'descricao' => $p->descricao_produto ?? '',
        'foto' => $p->foto_produto ?? '',
    ];
}

// função utilitária para limpar buffer atual (se houver) sem quebrar fluxo
if (ob_get_level()) { @ob_clean(); }

if ($export === 'csv' || $export === 'xls') {
    $isXls = ($export === 'xls');
    $ext = $isXls ? 'xls' : 'csv';
    $filename = 'produtos_' . date('Ymd') . '.' . $ext;

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // BOM UTF-8
    echo "\xEF\xBB\xBF";
    $out = fopen('php://output', 'w');
    fputcsv($out, ['ID','Nome do Produto','Categoria','Descrição','Preço','Foto']);
    foreach ($rows as $r) {
        fputcsv($out, [
            $r['id'],
            $r['nome'],
            $r['categoria'],
            $r['descricao'],
            str_replace('.', ',', $r['preco']),
            $r['foto']
        ]);
    }
    fclose($out);
    exit;
}

if ($export === 'json') {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

if ($export === 'print') {
    // página imprimível simples
    ?>
    <!doctype html>
    <html lang="pt-br">
    <head><meta charset="utf-8"><title>Relatório - Impressão</title>
    <style>body{font-family:Arial,Helvetica,sans-serif;font-size:12px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #999;padding:6px;text-align:left}img{max-width:60px}</style>
    </head><body>
    <h2>Relatório de Produtos</h2>
    <table><thead><tr><th>#</th><th>Foto</th><th>Nome</th><th>Categoria</th><th>Descrição</th><th>Preço</th></tr></thead><tbody>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['id']) ?></td>
        <td><?php if ($r['foto']): ?><img src="<?= '../img/produtos/' . htmlspecialchars($r['foto']) ?>" alt="foto"><?php endif; ?></td>
        <td><?= htmlspecialchars($r['nome']) ?></td>
        <td><?= htmlspecialchars($r['categoria']) ?></td>
        <td><?= htmlspecialchars(mb_strimwidth($r['descricao'], 0, 200, '...')) ?></td>
        <td>R$ <?= number_format((float)str_replace(',','.',$r['preco']),2,',','.') ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody></table>
    <script>window.print();</script>
    </body></html>
    <?php
    exit;
}

// pdf: tenta gerar via dompdf se disponível
if ($export === 'pdf') {
    $html = '<!doctype html><html lang="pt-br"><head><meta charset="utf-8"><style>body{font-family:Arial,Helvetica,sans-serif;font-size:12px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #999;padding:6px;text-align:left}img{max-width:60px}</style></head><body>';
    $html .= '<h2>Relatório de Produtos</h2><table><thead><tr><th>#</th><th>Foto</th><th>Nome</th><th>Categoria</th><th>Descrição</th><th>Preço</th></tr></thead><tbody>';
    foreach ($rows as $r) {
        $html .= '<tr>';
        $html .= '<td>'.htmlspecialchars($r['id']).'</td>';
        $html .= '<td>'.($r['foto']?'<img src="'.htmlspecialchars('../img/produtos/'.$r['foto']).'">':'').'</td>';
        $html .= '<td>'.htmlspecialchars($r['nome']).'</td>';
        $html .= '<td>'.htmlspecialchars($r['categoria']).'</td>';
        $html .= '<td>'.htmlspecialchars(mb_strimwidth($r['descricao'],0,300,'...')).'</td>';
        $html .= '<td>R$ '.number_format((float)str_replace(',','.',$r['preco']),2,',','.').'</td>';
        $html .= '</tr>';
    }
    $html .= '</tbody></table></body></html>';

    // tenta carregar autoload
    $autoload = __DIR__ . '/vendor/autoload.php';
    if (file_exists($autoload)) { require_once $autoload; }

    if (class_exists('\Dompdf\Dompdf')) {
        try {
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4','portrait');
            $dompdf->render();
            $dompdf->stream('relatorio_produtos_'.date('Ymd').'.pdf',['Attachment'=>0]);
            exit;
        } catch (Exception $ex) {
            echo 'Erro ao gerar PDF: ' . htmlspecialchars($ex->getMessage());
        }
    } else {
        echo 'Dompdf não encontrado. Instale com: composer require dompdf/dompdf';
    }
    exit;
}

// se nenhum export válido
http_response_code(400);
echo 'Parâmetro export inválido.';
