<?php
// Inclui o arquivo de conexão com o banco de dados
include('../config/conexao.php');


$id_user = $_SESSION['id_user'];

// Busca os dados do usuário no banco de dados
$query = "SELECT * FROM tb_user WHERE id_user = :id";
$stmt = $conect->prepare($query);
$stmt->bindParam(':id', $id_user, PDO::PARAM_INT);
$stmt->execute();

// Verifica se encontrou o usuário
if ($stmt->rowCount() > 0) {
    $user = $stmt->fetch(PDO::FETCH_OBJ);
    
    // Atribui os valores às variáveis
    $nome_user = $user->nome_user;
    $email_user = $user->email_user;
    $foto_user = $user->foto_user;
    $nivel_user = $user->nivel;
    $status_user = $user->status;
} else {
    // Usuário não encontrado
    echo '<div class="container mt-3">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Erro!</h5>
                Usuário não encontrado.
            </div>
          </div>';
    exit();
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Editar Perfil</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                        <li class="breadcrumb-item active">Editar Perfil</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-6">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Editar Perfil</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="" method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
                                
                                <div class="form-group">
                                    <label for="nome">Nome</label>
                                    <input type="text" class="form-control" name="nome" id="nome" required 
                                           value="<?php echo htmlspecialchars($nome_user); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="email">Endereço de E-mail</label>
                                    <input type="email" class="form-control" name="email" id="email" required 
                                           value="<?php echo htmlspecialchars($email_user); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="senha">Senha</label>
                                    <input type="password" class="form-control" name="senha" id="senha" 
                                           placeholder="Digite apenas se quiser alterar a senha">
                                    <small class="text-muted">Deixe em branco para manter a senha atual</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="foto">Foto do Perfil</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="foto" id="foto">
                                            <label class="custom-file-label" for="foto">Escolher arquivo</label>
                                        </div>
                                    </div>
                                    <small class="text-muted">Formatos permitidos: JPG, PNG, JPEG, GIF</small>
                                    <input type="hidden" name="foto_atual" value="<?php echo $foto_user; ?>">
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" name="upPerfil" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Salvar Alterações
                                </button>
                                <a href="home.php" class="btn btn-default">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                            </div>
                        </form>
                        
                        <?php
                        // Processamento do formulário de atualização do perfil
                        if (isset($_POST['upPerfil'])) {
                            // Recebe os dados do formulário
                            $id_user = $_POST['id_user'];
                            $nome = $_POST['nome'];
                            $email = $_POST['email'];
                            $senha_nova = $_POST['senha'];
                            $foto_atual = $_POST['foto_atual'];
                            
                            // Busca os dados antigos do usuário
                            $query_antigo = "SELECT email_user, senha_user FROM tb_user WHERE id_user = :id";
                            $stmt_antigo = $conect->prepare($query_antigo);
                            $stmt_antigo->bindParam(':id', $id_user, PDO::PARAM_INT);
                            $stmt_antigo->execute();
                            $dados_antigos = $stmt_antigo->fetch(PDO::FETCH_OBJ);
                            
                            $email_antigo = $dados_antigos->email_user;
                            $senha_antiga = $dados_antigos->senha_user;

                            // Verificar se existe imagem para fazer o upload
                            $foto_user = $foto_atual; // Inicializa com a foto atual
                            
                            if (!empty($_FILES['foto']['name'])) {
                                $formatos_permitidos = array("png", "jpg", "jpeg", "gif");
                                $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

                                if (in_array($extensao, $formatos_permitidos)) {
                                    $pasta = "../img/user/";
                                    $temporario = $_FILES['foto']['tmp_name'];
                                    $novoNome = uniqid() . ".{$extensao}";

                                    // Cria a pasta se não existir
                                    if (!is_dir($pasta)) {
                                        mkdir($pasta, 0777, true);
                                    }

                                    // Excluir a imagem antiga se não for a padrão
                                    if ($foto_atual != 'avatar_padrao.png' && file_exists($pasta . $foto_atual)) {
                                        unlink($pasta . $foto_atual);
                                    }

                                    if (move_uploaded_file($temporario, $pasta . $novoNome)) {
                                        $foto_user = $novoNome;
                                    } else {
                                        echo '<div class="alert alert-danger alert-dismissible mt-3">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                <h5><i class="icon fas fa-ban"></i> Erro!</h5>
                                                Não foi possível fazer upload da imagem.
                                              </div>';
                                    }
                                } else {
                                    echo '<div class="alert alert-danger alert-dismissible mt-3">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h5><i class="icon fas fa-ban"></i> Erro!</h5>
                                            Formato de arquivo não permitido.
                                          </div>';
                                }
                            }

                            // Verificar se a senha foi alterada
                            if (!empty($senha_nova)) {
                                $senha = password_hash($senha_nova, PASSWORD_DEFAULT);
                            } else {
                                $senha = $senha_antiga;
                            }

                            // Atualizar o banco de dados
                            $update = "UPDATE tb_user SET foto_user = :foto, nome_user = :nome, 
                                       email_user = :email, senha_user = :senha 
                                       WHERE id_user = :id";
                            
                            try {
                                $result = $conect->prepare($update);
                                $result->bindParam(':id', $id_user, PDO::PARAM_INT);
                                $result->bindParam(':foto', $foto_user, PDO::PARAM_STR);
                                $result->bindParam(':nome', $nome, PDO::PARAM_STR);
                                $result->bindParam(':email', $email, PDO::PARAM_STR);
                                $result->bindParam(':senha', $senha, PDO::PARAM_STR);
                                $result->execute();

                                if ($result->rowCount() > 0) {
                                    echo '<div class="alert alert-success alert-dismissible mt-3">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h5><i class="icon fas fa-check"></i> Sucesso!</h5>
                                            Perfil atualizado com sucesso.
                                          </div>';
                                    
                                    // Atualizar os dados na sessão se necessário
                                    $_SESSION['nome_user'] = $nome;
                                    $_SESSION['email_user'] = $email;
                                    $_SESSION['foto_user'] = $foto_user;
                                    
                                    // Atualizar as variáveis para exibir os novos dados
                                    $nome_user = $nome;
                                    $email_user = $email;
                                    
                                    // Verificar se email ou senha foram alterados
                                    if ($email !== $email_antigo || !empty($senha_nova)) {
                                        echo '<div class="alert alert-info alert-dismissible mt-3">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                <h5><i class="icon fas fa-info"></i> Informação!</h5>
                                                Email ou senha alterados. Você será redirecionado para login.
                                              </div>';
                                        echo '<script>
                                                setTimeout(function() {
                                                    
                                                }, 3000);
                                              </script>';
                                    } else {
                                        // Recarregar a página para mostrar dados atualizados
                                        echo '<script>
                                                setTimeout(function() {
                                                    location.reload();
                                                }, 2000);
                                              </script>';
                                    }
                                } else {
                                    echo '<div class="alert alert-warning alert-dismissible mt-3">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h5><i class="icon fas fa-exclamation-triangle"></i> Atenção!</h5>
                                            Nenhuma alteração foi realizada.
                                          </div>';
                                }
                            } catch (PDOException $e) {
                                if ($e->getCode() == 23000) { // Código para violação de UNIQUE
                                    echo '<div class="alert alert-danger alert-dismissible mt-3">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h5><i class="icon fas fa-ban"></i> Erro!</h5>
                                            Este e-mail já está em uso por outro usuário.
                                          </div>';
                                } else {
                                    echo '<div class="alert alert-danger alert-dismissible mt-3">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h5><i class="icon fas fa-ban"></i> Erro!</h5>
                                            Erro ao atualizar perfil: ' . $e->getMessage() . '
                                          </div>';
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Dados do Usuário</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0" style="text-align: center; margin-bottom: 98px">
                        <?php
                        // Verifica se a foto é a padrão ou uma foto personalizada
                        if ($user->foto_user == 'avatar_padrao.png') {
                            echo '<img src="../img/avatar_p/' . htmlspecialchars($user->foto_user) . '" 
                                   alt="' . htmlspecialchars($user->nome_user) . '" 
                                   title="' . htmlspecialchars($user->nome_user) . '" 
                                   style="width: 200px; height: 200px; object-fit: cover; border-radius: 100%; margin-top: 30px; border: 3px solid #dee2e6;">';
                        } else {
                            echo '<img src="../img/user/' . htmlspecialchars($user->foto_user) . '" 
                                   alt="' . htmlspecialchars($user->nome_user) . '" 
                                   title="' . htmlspecialchars($user->nome_user) . '" 
                                   style="width: 200px; height: 200px; object-fit: cover; border-radius: 100%; margin-top: 30px; border: 3px solid #dee2e6;">';
                        }
                        ?>
                            <h3 class="mt-3 mb-2"><?php echo htmlspecialchars($user->nome_user); ?></h3>
                            <p class="mb-1">
                                <i class="fas fa-envelope text-muted mr-1"></i> 
                                <strong><?php echo htmlspecialchars($user->email_user); ?></strong>
                            </p>
                            <p class="mb-1">
                                <i class="fas fa-user-tag text-muted mr-1"></i> 
                                <span class="badge badge-<?php 
                                    echo $user->nivel == 'admin' ? 'danger' : 
                                         ($user->nivel == 'gerente' ? 'warning' : 'info'); 
                                ?>">
                                    <?php echo ucfirst($user->nivel); ?>
                                </span>
                            </p>
                            <p class="mb-1">
                                <i class="fas fa-circle text-muted mr-1"></i> 
                                <span class="badge badge-<?php echo $user->status == 'ativo' ? 'success' : 'secondary'; ?>">
                                    <?php echo ucfirst($user->status); ?>
                                </span>
                            </p>
                            <small class="text-muted">ID: <?php echo $user->id_user; ?> | 
                            Cadastrado em: <?php echo date('d/m/Y', strtotime($user->data_cadastro)); ?></small>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    
                    <!-- Informações adicionais -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Informações do Perfil</h3>
                        </div>
                        <div class="card-body">
                            <p><i class="fas fa-info-circle mr-2"></i> <strong>Instruções:</strong></p>
                            <ul class="mb-0">
                                <li><small>Se alterar o e-mail ou senha, será necessário fazer login novamente</small></li>
                                <li><small>A foto será salva na pasta <code>img/user/</code></small></li>
                                <li><small>Use senhas fortes com letras, números e caracteres especiais</small></li>
                                <li><small>Nível: <?php echo $user->nivel; ?> | Status: <?php echo $user->status; ?></small></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
// Script para mostrar o nome do arquivo selecionado no input file
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.querySelector('.custom-file-input');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                var fileName = this.files[0].name;
                var nextSibling = e.target.nextElementSibling;
                nextSibling.innerText = fileName;
            }
        });
    }
    
    // Mostrar/ocultar senha
    const senhaInput = document.getElementById('senha');
    if (senhaInput) {
        const toggleButton = document.createElement('button');
        toggleButton.type = 'button';
        toggleButton.className = 'btn btn-outline-secondary btn-sm mt-1';
        toggleButton.innerHTML = '<i class="fas fa-eye"></i> Mostrar Senha';
        toggleButton.style.fontSize = '12px';
        
        toggleButton.addEventListener('click', function() {
            if (senhaInput.type === 'password') {
                senhaInput.type = 'text';
                this.innerHTML = '<i class="fas fa-eye-slash"></i> Ocultar Senha';
            } else {
                senhaInput.type = 'password';
                this.innerHTML = '<i class="fas fa-eye"></i> Mostrar Senha';
            }
        });
        
        senhaInput.parentNode.appendChild(toggleButton);
    }
});
</script>