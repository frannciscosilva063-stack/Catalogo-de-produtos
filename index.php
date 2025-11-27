<?php
session_start();

// redireciona se já logado
if (isset($_SESSION['loginUser']) && isset($_SESSION['id_user'])) {
    header("Location: paginas/home.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Sistema de Produtos | Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <style>
    :root {
      --primary-color: #007bff;
      --primary-dark: #0056b3;
      --text-dark: #333;
      --text-light: #666;
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f8f9fa;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    
    .login-container {
      width: 100%;
      max-width: 400px;
    }
    
    .login-card {
      background: white;
      border-radius: 10px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    
    .card-header {
      background: var(--primary-color);
      color: white;
      padding: 30px 20px;
      text-align: center;
    }
    
    .card-header h1 {
      font-size: 1.8rem;
      margin-bottom: 5px;
    }
    
    .card-header p {
      opacity: 0.9;
      font-size: 0.9rem;
    }
    
    .card-body {
      padding: 30px;
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-control {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 1rem;
      transition: border-color 0.3s;
    }
    
    .form-control:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
    }
    
    .form-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    
    .checkbox-wrapper {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .toggle-password {
      background: none;
      border: none;
      color: var(--primary-color);
      cursor: pointer;
      font-size: 0.9rem;
    }
    
    .btn-login {
      width: 100%;
      padding: 12px;
      background: var(--primary-color);
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.3s;
    }
    
    .btn-login:hover {
      background: var(--primary-dark);
    }
    
    .register-link {
      text-align: center;
      margin-top: 20px;
      color: var(--text-light);
    }
    
    .register-link a {
      color: var(--primary-color);
      text-decoration: none;
    }
    
    .alert {
      padding: 12px 15px;
      border-radius: 5px;
      margin-bottom: 20px;
      font-size: 0.9rem;
    }
    
    .alert-success {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    
    .alert-danger {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
    
    .alert-warning {
      background: #fff3cd;
      color: #856404;
      border: 1px solid #ffeaa7;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <div class="card-header">
        <h1><i class="fas fa-boxes mr-2"></i>Sistema de Produtos</h1>
        <p>Faça login para acessar o sistema</p>
      </div>
      
      <div class="card-body">
        <?php
        include_once('config/conexao.php');
        
        // Exibir mensagens com base na ação
        if (isset($_GET['acao'])) {
            $acao = $_GET['acao'];
            if ($acao == 'negado') {
                echo '<div class="alert alert-danger">
                <strong><i class="fas fa-exclamation-triangle"></i> Acesso negado!</strong> Faça login para continuar.
                </div>';
            } elseif ($acao == 'sair') {
                echo '<div class="alert alert-warning">
                <strong><i class="fas fa-info-circle"></i> Logout realizado!</strong> Você saiu do sistema.
                </div>';
            }
        }
        ?>
        
        <form method="post" id="loginForm">
          <div class="form-group">
            <input type="email" name="email" class="form-control" placeholder="E-mail" required autofocus>
          </div>
          
          <div class="form-group">
            <input type="password" name="senha" id="senha" class="form-control" placeholder="Senha" required>
          </div>
          
          <div class="form-options">
            <div class="checkbox-wrapper">
              <input type="checkbox" id="lembrar" name="lembrar">
              <label for="lembrar">Lembrar-me</label>
            </div>
            <button type="button" id="toggleSenha" class="toggle-password">
              <i class="fas fa-eye"></i> Mostrar
            </button>
          </div>
          
          <button type="submit" name="login" class="btn-login">
            <i class="fas fa-sign-in-alt"></i> Entrar
          </button>
        </form>
        
        <div class="register-link">
          Não tem conta? <a href="cad_user.php">Cadastre-se</a>
        </div>
      </div>
    </div>
  </div>

  <?php
  // Processar o formulário de login
  if (isset($_POST['login'])) {
      $login = trim($_POST['email']);
      $senha = trim($_POST['senha']);

      if (!empty($login) && !empty($senha)) {
          try {
              // Verificar se o usuário existe
              $sql = "SELECT * FROM tb_user WHERE email_user = ? AND status = 'ativo'";
              $stmt = $conect->prepare($sql);
              $stmt->execute([$login]);
              
              if ($stmt->rowCount() > 0) {
                  $user = $stmt->fetch(PDO::FETCH_ASSOC);
                  
                  // Verificar a senha
                  if (password_verify($senha, $user['senha_user'])) {
                      // Criar sessão
                      $_SESSION['loginUser'] = $user['email_user'];
                      $_SESSION['id_user'] = $user['id_user'];
                      $_SESSION['nome_user'] = $user['nome_user'];
                      $_SESSION['nivel_user'] = $user['nivel'];
                      
                      echo '<div class="alert alert-success">
                      <strong><i class="fas fa-check-circle"></i> Login realizado!</strong> Redirecionando...
                      </div>';
                      
                      echo '<script>
                      setTimeout(function() {
                          window.location.href = "paginas/home.php";
                      }, 1000);
                      </script>';
                      
                  } else {
                      echo '<div class="alert alert-danger">
                      <strong><i class="fas fa-times-circle"></i> Erro!</strong> Senha incorreta.
                      </div>';
                  }
              } else {
                  echo '<div class="alert alert-danger">
                  <strong><i class="fas fa-user-times"></i> Erro!</strong> E-mail não encontrado ou conta inativa.
                  </div>';
              }
              
          } catch (PDOException $e) {
              echo '<div class="alert alert-danger">
              <strong><i class="fas fa-exclamation-triangle"></i> Erro!</strong> Problema no sistema. Tente novamente.
              </div>';
              error_log("Erro login: " . $e->getMessage());
          }
      } else {
          echo '<div class="alert alert-danger">
          <strong><i class="fas fa-exclamation-circle"></i> Erro!</strong> Preencha todos os campos.
          </div>';
      }
  }
  ?>

  <script src="plugins/jquery/jquery.min.js"></script>
  <script>
    // Mostrar/ocultar senha
    document.getElementById('toggleSenha').addEventListener('click', function() {
      var senha = document.getElementById('senha');
      var icon = this.querySelector('i');
      
      if (senha.type === 'password') {
        senha.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
        this.innerHTML = '<i class="fas fa-eye-slash"></i> Ocultar';
      } else {
        senha.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
        this.innerHTML = '<i class="fas fa-eye"></i> Mostrar';
      }
    });

    // Foco no campo email
    document.querySelector('input[name="email"]').focus();
  </script>
</body>
</html>