<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mercado Express | Cadastro</title>

  <!-- Font Awesome & AdminLTE -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">

<style>
  :root {
    --primary-color: #2C5AA0;
    --primary-dark: #1E3F73;
    --secondary-color: #4ECDC4;
    --accent-color: #FFD166;
    --text-dark: #2D3047;
    --text-light: #6C757D;
    --success-color: #06D6A0;
    --white: #ffffff;
    --gray-light: #F8F9FA;
  }

  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  /* FUNDO IDÊNTICO AO LOGIN */
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: 
      linear-gradient(135deg, 
        rgba(44, 90, 160, 0.9) 0%, 
        rgba(30, 63, 115, 0.85) 50%,
        rgba(78, 205, 196, 0.8) 100%),
      url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="%232C5AA0"/><path d="M30,30 L70,30 L70,70 L30,70 Z" fill="none" stroke="%234ECDC4" stroke-width="2"/><circle cx="50" cy="50" r="15" fill="none" stroke="%23FFD166" stroke-width="2"/></svg>');
    background-size: cover, 200px;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    position: relative;
    overflow: hidden;
  }

  /* Efeitos animados de fundo */
  .background-animation {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    overflow: hidden;
  }

  .floating-shapes {
    position: absolute;
    width: 100%;
    height: 100%;
  }

  .shape {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: float 20s infinite linear;
  }

  .shape:nth-child(1) {
    width: 80px;
    height: 80px;
    top: 10%;
    left: 10%;
    animation-delay: 0s;
    background: rgba(255, 209, 102, 0.2);
  }

  .shape:nth-child(2) {
    width: 120px;
    height: 120px;
    top: 70%;
    left: 80%;
    animation-delay: -5s;
    background: rgba(78, 205, 196, 0.2);
  }

  .shape:nth-child(3) {
    width: 60px;
    height: 60px;
    top: 50%;
    left: 20%;
    animation-delay: -10s;
    background: rgba(255, 255, 255, 0.15);
  }

  .shape:nth-child(4) {
    width: 100px;
    height: 100px;
    top: 20%;
    left: 70%;
    animation-delay: -15s;
    background: rgba(6, 214, 160, 0.2);
  }

  @keyframes float {
    0%, 100% {
      transform: translate(0, 0) rotate(0deg);
    }
    25% {
      transform: translate(20px, 20px) rotate(90deg);
    }
    50% {
      transform: translate(0, 40px) rotate(180deg);
    }
    75% {
      transform: translate(-20px, 20px) rotate(270deg);
    }
  }

  /* Container do título */
  .title-container {
    position: absolute;
    top: 30px;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
    width: 100%;
    max-width: 450px;
    z-index: 10;
    animation: slideDown 1s ease-out;
  }

  .logo {
    font-size: 3rem;
    color: var(--white);
    margin-bottom: 5px;
    text-shadow: 0 4px 15px rgba(0,0,0,0.3);
  }

  .system-title {
    font-size: 2.2rem;
    font-weight: 800;
    color: var(--white);
    text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    letter-spacing: 2px;
    margin-bottom: 5px;
    background: linear-gradient(45deg, var(--white), var(--accent-color));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .system-subtitle {
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.9);
    font-weight: 300;
    letter-spacing: 1px;
  }

  @keyframes slideDown {
    from {
      opacity: 0;
      transform: translate(-50%, -50px);
    }
    to {
      opacity: 1;
      transform: translate(-50%, 0);
    }
  }

  /* Container principal do cadastro */
  .register-container {
    width: 100%;
    max-width: 450px;
    position: relative;
    z-index: 2;
    animation: slideUp 1s ease-out 0.5s both;
    margin-top: 80px;
  }

  @keyframes slideUp {
    from {
      opacity: 0;
      transform: translateY(50px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Card de cadastro */
  .register-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    box-shadow: 
      0 20px 50px rgba(0, 0, 0, 0.3),
      0 5px 15px rgba(0, 0, 0, 0.2),
      inset 0 1px 0 rgba(255, 255, 255, 0.4);
    overflow: hidden;
    position: relative;
    border: 1px solid rgba(255, 255, 255, 0.3);
    transition: all 0.4s ease;
  }

  .register-card:hover {
    transform: translateY(-5px);
    box-shadow: 
      0 25px 60px rgba(0, 0, 0, 0.4),
      0 8px 20px rgba(0, 0, 0, 0.3),
      inset 0 1px 0 rgba(255, 255, 255, 0.4);
  }

  /* Header do card */
  .card-header {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    padding: 25px 30px;
    text-align: center;
    position: relative;
    overflow: hidden;
  }

  .card-header::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
    animation: shine 3s infinite;
  }

  @keyframes shine {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }

  .card-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, var(--secondary-color), var(--accent-color));
  }

  .card-header h1 {
    font-size: 1.5rem;
    margin-bottom: 5px;
    font-weight: 700;
    position: relative;
    z-index: 1;
  }

  .card-header p {
    opacity: 0.9;
    font-size: 0.9rem;
    font-weight: 400;
    position: relative;
    z-index: 1;
  }

  .header-icons {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-top: 12px;
    position: relative;
    z-index: 1;
  }

  .header-icon {
    width: 35px;
    height: 35px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
  }

  .header-icon:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px) scale(1.1);
  }

  /* Body do card */
  .card-body {
    padding: 30px;
    position: relative;
  }

  /* Campos do formulário - ESTILO UNIFICADO */
  .form-group {
    margin-bottom: 20px;
    position: relative;
  }

  .form-control {
    width: 100%;
    padding: 15px 20px 15px 50px;
    border: 2px solid rgba(232, 237, 242, 0.8);
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: rgba(248, 249, 250, 0.8);
    font-weight: 500;
    color: var(--text-dark);
    backdrop-filter: blur(10px);
    height: 50px;
  }

  .form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    background: rgba(255, 255, 255, 0.9);
    box-shadow: 0 0 0 4px rgba(44, 90, 160, 0.15);
    transform: translateY(-2px);
  }

  .form-control::placeholder {
    color: #A0A4A8;
    font-weight: 400;
  }

  .form-icon {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary-color);
    font-size: 1.1rem;
    transition: all 0.3s ease;
    z-index: 2;
  }

  .form-group:focus-within .form-icon {
    color: var(--primary-dark);
    transform: translateY(-50%) scale(1.1);
  }

  /* CAMPO DE IMAGEM ESTILIZADO COMO OS DEMAIS */
  .file-input-group {
    margin-bottom: 20px;
    position: relative;
  }

  .file-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
  }

  .file-input-display {
    width: 100%;
    padding: 15px 20px 15px 50px;
    border: 2px solid rgba(232, 237, 242, 0.8);
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: rgba(248, 249, 250, 0.8);
    font-weight: 500;
    color: var(--text-dark);
    backdrop-filter: blur(10px);
    height: 50px;
    display: flex;
    align-items: center;
    cursor: pointer;
  }

  .file-input-display:hover {
    border-color: var(--primary-color);
    background: rgba(255, 255, 255, 0.9);
    transform: translateY(-1px);
  }

  .file-input-group:focus-within .file-input-display {
    border-color: var(--primary-color);
    background: rgba(255, 255, 255, 0.9);
    box-shadow: 0 0 0 4px rgba(44, 90, 160, 0.15);
    transform: translateY(-2px);
  }

  .file-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 3;
  }

  .file-name {
    flex: 1;
    color: var(--text-dark);
  }

  .file-placeholder {
    flex: 1;
    color: #A0A4A8;
    font-weight: 400;
  }

  .file-icon {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary-color);
    font-size: 1.1rem;
    transition: all 0.3s ease;
    z-index: 2;
  }

  .file-input-group:focus-within .file-icon {
    color: var(--primary-dark);
    transform: translateY(-50%) scale(1.1);
  }

  /* Botão de cadastro */
  .btn-register {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    letter-spacing: 0.5px;
    margin-top: 10px;
    height: 52px;
  }

  .btn-register:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(44, 90, 160, 0.4);
  }

  .btn-register:active {
    transform: translateY(-1px);
  }

  .btn-register::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.6s;
  }

  .btn-register:hover::before {
    left: 100%;
  }

  /* Link voltar */
  .register-link {
    text-align: center;
    margin-top: 25px;
    color: var(--text-dark);
    font-weight: 500;
    font-size: 0.9rem;
  }

  .register-link a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border-bottom: 1px solid transparent;
  }

  .register-link a:hover {
    color: var(--primary-dark);
    border-bottom-color: var(--primary-dark);
  }

  /* Alertas - mesmo estilo do login */
  .alert {
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-size: 0.85rem;
    text-align: left;
    border: none;
    display: flex;
    align-items: center;
    gap: 15px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border-left: 4px solid transparent;
    animation: slideIn 0.5s ease-out;
  }

  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .alert-success {
    background: rgba(6, 214, 160, 0.15);
    color: var(--success-color);
    border-left-color: var(--success-color);
  }

  .alert-danger {
    background: rgba(220, 53, 69, 0.15);
    color: #dc3545;
    border-left-color: #dc3545;
  }

  .alert-icon {
    font-size: 1.5rem;
    flex-shrink: 0;
  }

  .alert-content {
    flex: 1;
  }

  .alert-content strong {
    display: block;
    font-size: 0.95rem;
    margin-bottom: 2px;
  }

  .alert-content small {
    font-size: 0.8rem;
    opacity: 0.9;
  }

  /* Responsividade */
  @media (max-width: 480px) {
    .register-container {
      max-width: 100%;
      padding: 0 15px;
      margin-top: 60px;
    }
    
    .title-container {
      top: 20px;
      padding: 0 15px;
    }
    
    .system-title {
      font-size: 1.8rem;
    }
    
    .logo {
      font-size: 2.5rem;
    }
    
    .card-body {
      padding: 20px;
    }
    
    .card-header {
      padding: 20px;
    }
    
    .card-header h1 {
      font-size: 1.3rem;
    }
    
    .alert {
      padding: 12px 15px;
      gap: 12px;
    }

    .alert-icon {
      font-size: 1.3rem;
    }
  }
</style>
</head>
<body>

  <!-- Animações de fundo -->
  <div class="background-animation">
    <div class="floating-shapes">
      <div class="shape"></div>
      <div class="shape"></div>
      <div class="shape"></div>
      <div class="shape"></div>
    </div>
  </div>

  <!-- Título acima do campo de cadastro -->
  <div class="title-container">
    <div class="logo">
      <i class="fas fa-shopping-cart"></i>
    </div>
    <h1 class="system-title">MERCADO EXPRESS</h1>
    <p class="system-subtitle">Cadastro de Usuário</p>
  </div>

  <div class="register-container">
    <div class="register-card">
      <div class="card-header">
        <h1><i class="fas fa-user-plus mr-2"></i>Criar Nova Conta</h1>
        <p>Preencha seus dados para se cadastrar</p>
        <div class="header-icons">
          <div class="header-icon"><i class="fas fa-user"></i></div>
          <div class="header-icon"><i class="fas fa-shield-alt"></i></div>
          <div class="header-icon"><i class="fas fa-rocket"></i></div>
        </div>
      </div>
      
      <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">

          <!-- Campo de imagem estilizado como os demais -->
          <div class="file-input-group">
            <div class="file-input-wrapper">
              <i class="fas fa-camera file-icon"></i>
              <div class="file-input-display">
                <span class="file-placeholder" id="filePlaceholder">Selecionar foto do usuário (opcional)</span>
                <span class="file-name" id="fileName" style="display: none;"></span>
              </div>
              <input type="file" class="file-input" name="foto" id="foto" accept="image/*">
            </div>
          </div>

          <div class="form-group">
            <i class="fas fa-user form-icon"></i>
            <input type="text" name="nome" class="form-control" placeholder="Nome completo" required>
          </div>

          <div class="form-group">
            <i class="fas fa-envelope form-icon"></i>
            <input type="email" name="email" class="form-control" placeholder="E-mail" required>
          </div>

          <div class="form-group">
            <i class="fas fa-lock form-icon"></i>
            <input type="password" name="senha" class="form-control" placeholder="Senha forte" required>
          </div>

          <button type="submit" name="botao" class="btn-register">
            <i class="fas fa-user-plus"></i> Finalizar Cadastro
          </button>
        </form>

        <?php
        include_once('config/conexao.php');

        if (isset($_POST['botao'])) {
            $nome  = $_POST['nome'];
            $email = $_POST['email'];
            $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
            $foto  = 'avatar-padrao.png';

            if (!empty($_FILES['foto']['name'])) {
                $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
                $allow = ['jpg','jpeg','png','gif'];
                if (in_array($ext, $allow)) {
                    $foto = uniqid() . ".$ext";
                    move_uploaded_file($_FILES['foto']['tmp_name'], "img/user/".$foto);
                }
            }

            try {
                $sql = "INSERT INTO tb_user (foto_user, nome_user, email_user, senha_user) VALUES (?, ?, ?, ?)";
                $stmt = $conect->prepare($sql);
                $stmt->execute([$foto, $nome, $email, $senha]);
                echo '<div class="alert alert-success">
                <i class="fas fa-check-circle alert-icon"></i>
                <div class="alert-content">
                  <strong>Cadastro realizado com sucesso!</strong>
                  <small>Você já pode fazer login no sistema</small>
                </div>
                </div>';
            } catch(Exception $e) {
                echo '<div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle alert-icon"></i>
                <div class="alert-content">
                  <strong>Erro ao cadastrar!</strong>
                  <small>Tente novamente mais tarde</small>
                </div>
                </div>';
            }
        }
        ?>

        <div class="register-link">
          Já tem conta? <a href="index.php">Faça login aqui</a>
        </div>
      </div>
    </div>
  </div>

  <script src="plugins/jquery/jquery.min.js"></script>
  <script>
    // Script para o campo de imagem
    document.getElementById('foto').addEventListener('change', function(e) {
      const fileName = e.target.files[0] ? e.target.files[0].name : '';
      const filePlaceholder = document.getElementById('filePlaceholder');
      const fileNameDisplay = document.getElementById('fileName');
      
      if (fileName) {
        filePlaceholder.style.display = 'none';
        fileNameDisplay.textContent = fileName;
        fileNameDisplay.style.display = 'block';
      } else {
        filePlaceholder.style.display = 'block';
        fileNameDisplay.style.display = 'none';
      }
    });

    // Efeito de digitação no título
    document.addEventListener('DOMContentLoaded', function() {
      const title = document.querySelector('.system-title');
      const originalText = title.textContent;
      title.textContent = '';
      
      let i = 0;
      const typeWriter = () => {
        if (i < originalText.length) {
          title.textContent += originalText.charAt(i);
          i++;
          setTimeout(typeWriter, 100);
        }
      };
      
      setTimeout(typeWriter, 500);
    });

    // Efeito de foco nos campos
    const inputs = document.querySelectorAll('.form-control, .file-input');
    inputs.forEach(input => {
      input.addEventListener('focus', function() {
        this.closest('.form-group, .file-input-group').classList.add('focused');
      });
      
      input.addEventListener('blur', function() {
        this.closest('.form-group, .file-input-group').classList.remove('focused');
      });
    });
  </script>
</body>
</html>