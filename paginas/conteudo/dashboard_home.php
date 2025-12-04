<?php
// Dashboard Home - Template Profissional

// A página assume que a sessão já foi iniciada no `header.php`.
// Inclui conexão e carrega métricas dinâmicas para o dashboard.
require_once('../config/conexao.php');

$id_user = isset($_SESSION['id_user']) ? intval($_SESSION['id_user']) : 0;

// Contadores
try {
    $stmt = $conect->prepare("SELECT COUNT(*) AS total FROM tb_produtos WHERE id_user = :id_user");
    $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();
    $totalProdutos = (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt = $conect->prepare("SELECT COALESCE(SUM(v.estoque_atual),0) AS total_stock FROM vw_estoque_atual v JOIN tb_produtos p ON v.id_produto = p.id_produto WHERE p.id_user = :id_user");
    $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();
    $totalEstoque = (int) $stmt->fetch(PDO::FETCH_ASSOC)['total_stock'];

    $stmt = $conect->prepare("SELECT COUNT(*) AS total_cat FROM tb_categorias WHERE id_user = :id_user");
    $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();
    $totalCategorias = (int) $stmt->fetch(PDO::FETCH_ASSOC)['total_cat'];

    $stmt = $conect->prepare("SELECT COALESCE(SUM(v.estoque_atual * v.preco_venda),0) AS valor FROM vw_estoque_atual v JOIN tb_produtos p ON v.id_produto = p.id_produto WHERE p.id_user = :id_user");
    $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();
    $valorEstoque = (float) $stmt->fetch(PDO::FETCH_ASSOC)['valor'];

    // Produtos recentes
    $stmt = $conect->prepare("SELECT p.id_produto, p.nome_produto, p.foto_produto, p.preco_venda, COALESCE(v.estoque_atual,0) AS estoque FROM tb_produtos p LEFT JOIN vw_estoque_atual v ON p.id_produto = v.id_produto WHERE p.id_user = :id_user ORDER BY p.data_cadastro DESC LIMIT 6");
    $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();
    $produtosRecentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $totalProdutos = 0;
    $totalEstoque = 0;
    $totalCategorias = 0;
    $valorEstoque = 0.00;
    $produtosRecentes = [];
}

// Foto do usuário (se houver)
$foto_user_dashboard = isset($_SESSION['foto']) && !empty($_SESSION['foto']) ? $_SESSION['foto'] : 'avatar-padrao.png';

?>

<div class="content-wrapper">
    <!-- Formas Animadas de Fundo -->
    <div class="animated-bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
    </div>

    <!-- Conteúdo Principal -->
    <div class="dashboard-content">
        <!-- Header do Dashboard -->
        <div class="dashboard-header">
            <div class="dashboard-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="dashboard-header-content">
                <h1 class="dashboard-title">
                    <i class="fas fa-shopping-basket mr-3" style="color: #FFD166;"></i>
                    Bem-vindo ao Mercado Express
                </h1>
                <p class="dashboard-subtitle">Gerencie seus produtos, categorias e vendas em um único lugar</p>
                <button id="toggle-max" class="btn-toggle-max" style="margin-top: 15px;">
                    <i class="fas fa-expand mr-2"></i>Maximizar Dashboard
                </button>
            </div>
        </div>

        <!-- Cards de Ações Rápidas -->
        <div class="dashboard-cards">
            <!-- Card 1: Cadastrar Produto -->
            <div class="dashboard-card">
                <div class="card-icon" style="background: linear-gradient(135deg, #2C5AA0, #1E3F73);">
                    <i class="fas fa-box"></i>
                </div>
                <h3 class="card-title">Cadastrar Produto</h3>
                <p class="card-description">Adicione novos produtos ao seu catálogo e defina preços, descrições e categorias.</p>
                <a href="home.php?acao=cadastrar_produto" class="card-button">
                    <i class="fas fa-plus mr-2"></i>Novo Produto
                </a>
            </div>

            <!-- Card 2: Relatórios -->
            <div class="dashboard-card">
                <div class="card-icon" style="background: linear-gradient(135deg, #4ECDC4, #3BB3AA);">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3 class="card-title">Visualizar Relatórios</h3>
                <p class="card-description">Acompanhe vendas, estoque e análise detalhada do seu negócio em tempo real.</p>
                <a href="home.php?acao=relatorio" class="card-button" style="background: linear-gradient(135deg, #4ECDC4, #3BB3AA);">
                    <i class="fas fa-chart-line mr-2"></i>Ver Relatórios
                </a>
            </div>

            <!-- Card 3: Meu Perfil -->
            <div class="dashboard-card">
                <div class="card-icon" style="background: linear-gradient(135deg, #FFD166, #E6B950);">
                    <i class="fas fa-user-cog"></i>
                </div>
                <h3 class="card-title">Configurar Perfil</h3>
                <p class="card-description">Atualize suas informações, foto de perfil e dados da sua loja online.</p>
                <a href="home.php?acao=perfil" class="card-button" style="background: linear-gradient(135deg, #FFD166, #E6B950);">
                    <i class="fas fa-edit mr-2"></i>Editar Perfil
                </a>
            </div>

            <!-- Card 4: Acessar Loja -->
            <div class="dashboard-card">
                <div class="card-icon" style="background: linear-gradient(135deg, #06D6A0, #059973);">
                    <i class="fas fa-store"></i>
                </div>
                <h3 class="card-title">Acessar Loja Pública</h3>
                <p class="card-description">Visualize sua loja como os clientes veem, com todos os produtos cadastrados.</p>
                <a href="../loja/index.php" class="card-button" style="background: linear-gradient(135deg, #06D6A0, #059973);" target="_blank">
                    <i class="fas fa-external-link-alt mr-2"></i>Abrir Loja
                </a>
            </div>
        </div>

        <!-- Seção de Estatísticas -->
        <div class="stats-section">
            <h2 class="stats-title">
                <i class="fas fa-trending-up mr-3" style="color: #2C5AA0;"></i>Estatísticas Rápidas
            </h2>
            
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number"><?= number_format($totalProdutos, 0, ',', '.') ?></div>
                    <div class="stat-label">Produtos Cadastrados</div>
                    <img src="<?= '../img/avatar_p/' . htmlspecialchars($foto_user_dashboard) ?>" alt="Avatar" class="admin-thumb"/>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?= number_format($totalEstoque, 0, ',', '.') ?></div>
                    <div class="stat-label">Total em Estoque</div>
                    <img src="<?= '../img/avatar_p/' . htmlspecialchars($foto_user_dashboard) ?>" alt="Avatar" class="admin-thumb"/>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?= number_format($totalCategorias, 0, ',', '.') ?></div>
                    <div class="stat-label">Categorias Criadas</div>
                    <img src="<?= '../img/avatar_p/' . htmlspecialchars($foto_user_dashboard) ?>" alt="Avatar" class="admin-thumb"/>
                </div>
                <div class="stat-item">
                    <div class="stat-number">R$ <?= number_format($valorEstoque, 2, ',', '.') ?></div>
                    <div class="stat-label">Valor Total do Estoque</div>
                    <img src="<?= '../img/avatar_p/' . htmlspecialchars($foto_user_dashboard) ?>" alt="Avatar" class="admin-thumb"/>
                </div>
            </div>
        </div>

        <!-- Informações Úteis -->
        <div class="stats-section">
            <h2 class="stats-title">
                <i class="fas fa-lightbulb mr-3" style="color: #4ECDC4;"></i>Dicas para Melhorar suas Vendas
            </h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div style="padding: 20px; background: rgba(44, 90, 160, 0.05); border-radius: 15px; border-left: 4px solid #2C5AA0;">
                    <h4 style="color: #2C5AA0; margin-bottom: 10px; font-weight: 700;">
                        <i class="fas fa-image mr-2"></i>Adicione Fotos de Qualidade
                    </h4>
                    <p style="color: #6C757D; font-size: 0.95rem; line-height: 1.6;">
                        Produtos com imagens atraentes tendem a vender mais. Use fotos em alta resolução e bem iluminadas.
                    </p>
                </div>

                <div style="padding: 20px; background: rgba(78, 205, 196, 0.05); border-radius: 15px; border-left: 4px solid #4ECDC4;">
                    <h4 style="color: #4ECDC4; margin-bottom: 10px; font-weight: 700;">
                        <i class="fas fa-align-left mr-2"></i>Descrições Detalhadas
                    </h4>
                    <p style="color: #6C757D; font-size: 0.95rem; line-height: 1.6;">
                        Descreva bem seus produtos. Mencione características, dimensões, materiais e instruções de uso.
                    </p>
                </div>

                <div style="padding: 20px; background: rgba(255, 209, 102, 0.05); border-radius: 15px; border-left: 4px solid #FFD166;">
                    <h4 style="color: #E6B950; margin-bottom: 10px; font-weight: 700;">
                        <i class="fas fa-tag mr-2"></i>Preços Competitivos
                    </h4>
                    <p style="color: #6C757D; font-size: 0.95rem; line-height: 1.6;">
                        Analise a concorrência e defina preços que sejam atrativos sem comprometer seus lucros.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Ajustes específicos para o dashboard */
    .content-wrapper {
        position: relative;
    }

    .dashboard-content {
        position: relative;
        z-index: 10;
        padding: 30px;
    }

    /* Botão Maximizar */
    .btn-toggle-max {
        padding: 10px 18px;
        background: linear-gradient(135deg, #FFD166, #E6B950);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(255, 209, 102, 0.3);
        display: inline-block;
        font-size: 0.95rem;
    }

    .btn-toggle-max:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 209, 102, 0.5);
    }

    .btn-toggle-max:active {
        transform: translateY(0);
    }

    /* Avatar do administrador (escondido por padrão) */
    .stat-item .admin-thumb {
        display: none;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        margin: 10px auto 0;
        object-fit: cover;
        border: 3px solid rgba(44, 90, 160, 0.3);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }

    /* Mostrar avatar quando o dashboard estiver 'maximizado' */
    .content-wrapper.dashboard-max .stat-item .admin-thumb {
        display: block;
        animation: slideIn 0.4s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .stat-item .admin-thumb:hover {
        transform: scale(1.05);
    }

    .stat-item .admin-thumb, .stat-number {
        transition: all 0.25s ease;
    }

    /* Reduzir números quando maximizado para dar espaço ao avatar */
    .content-wrapper.dashboard-max .stat-number {
        font-size: 2rem;
    }

    .content-wrapper.dashboard-max .stat-item {
        text-align: center;
    }

    @media (max-width: 600px) {
        .dashboard-header { padding: 20px; }
        .dashboard-title { font-size: 1.6rem; }
        .btn-toggle-max { padding: 8px 14px; font-size: 0.85rem; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('toggle-max');
    if (!btn) return;
    
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        var wrapper = document.querySelector('.content-wrapper');
        if (!wrapper) return;
        
        wrapper.classList.toggle('dashboard-max');
        
        if (wrapper.classList.contains('dashboard-max')) {
            btn.innerHTML = '<i class="fas fa-compress mr-2"></i>Restaurar Dashboard';
            btn.style.background = 'linear-gradient(135deg, #ff6b6b, #ee5a6f)';
        } else {
            btn.innerHTML = '<i class="fas fa-expand mr-2"></i>Maximizar Dashboard';
            btn.style.background = 'linear-gradient(135deg, #FFD166, #E6B950)';
        }
    });
});
</script>
