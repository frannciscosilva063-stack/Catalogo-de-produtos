<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Catálogo de Produtos | Cadastro</title>

  <!-- Font Awesome & AdminLTE (caso use alerts) -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700;900&display=swap" rel="stylesheet">

<style>
  :root {
    --blue-dark: #072a63;
    --blue-mid:  #0b3b88;
    --accent:    #0a4fe0;
    --light-blue: #a0d8ff;
    --card-bg:   #ffffff;
    --border:    #e2e8f0;
    --shadow:    0 28px 70px rgba(10,30,60,0.18);
  }

  * { box-sizing: border-box; }
  html, body {
    height: 100%;
    margin: 0;
    font-family: 'Source Sans Pro', sans-serif;
    background: #f3f6fb;
    color: #22303a;
  }

  body {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 20px;
  }

  /* Container principal */
  .register-box {
    width: 100%;
    max-width: 980px;
    height: 580px;               /* mesma altura da sua tela de login */
    display: flex;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: var(--shadow);
    background: white;
  }

  /* Painel esquerdo azul */
  .register-left {
    flex: 0 0 56%;
    position: relative;
    background: var(--blue-dark);
    overflow: hidden;
  }

  .register-left::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, var(--blue-dark) 0%, var(--blue-mid) 70%);
    clip-path: polygon(0 0, 84% 0, 100% 100%, 0 100%);
  }

  .bubble-top {
    position: absolute;
    left: 10%;
    top: 16%;
    width: 170px;
    height: 170px;
    background: rgba(255,255,255,0.06);
    border-radius: 50%;
    filter: blur(22px);
  }

  .bubble-bottom {
    position: absolute;
    right: 6%;
    bottom: 6%;
    width: 220px;
    height: 220px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
    filter: blur(28px);
  }

  .left-content {
    position: relative;
    z-index: 2;
    padding: 100px 70px;
    color: white;
  }

  .left-content h1 {
    font-size: 52px;
    font-weight: 900;
    margin: 0 0 14px 0;
    line-height: 1.1;
  }

  .left-content h1 span {
    display: block;
    font-size: 62px;
    color: var(--light-blue);
  }

  .left-content p {
    font-size: 17.5px;
    line-height: 1.6;
    opacity: 0.96;
    max-width: 380px;
    margin-top: 8px;
  }

  /* Painel direito */
  .register-right {
    flex: 0 0 44%;
    background: var(--card-bg);
    padding: 70px 55px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .register-card-body {
    width: 100%;
    max-width: 370px;
  }

  .register-box-msg {
    font-size: 23px;
    font-weight: 700;
    text-align: center;
    color: #1f2937;
    margin-bottom: 38px;
  }

  /* Inputs e file */
  .input-group .form-control,
  .custom-file-label {
    height: 50px;
    border-radius: 12px;
    border: 1.4px solid var(--border);
    padding: 0 18px;
    font-size: 15px;
    background: #fcfdff;
    transition: all 0.3s ease;
  }

  .input-group .form-control:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 4.5px rgba(10,79,224,0.14);
  }

  .input-group-text {
    background: transparent;
    border: none;
    border-radius: 0 12px 12px 0 !important;
    color: #64748b;
  }

  .custom-file-label::after {
    height: 48px;
    line-height: 36px;
    border-radius: 0 12px 12px 0;
    background: #f1f5f9;
    color: #475569;
  }

  /* Botão idêntico ao da tela de login */
  .btn-primary {
    height: 52px;
    border-radius: 14px;
    background: linear-gradient(90deg, var(--accent), #00b4ff);
    border: none;
    font-size: 16.5px;
    font-weight: 700;
    box-shadow: 0 12px 32px rgba(10,79,224,0.28);
    transition: all 0.3s ease;
  }

  .btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 18px 42px rgba(10,79,224,0.38);
  }

  /* Link voltar */
  .text-center a {
    color: var(--accent);
    font-weight: 600;
    font-size: 15px;
    text-decoration: none;
  }

  .text-center a:hover { text-decoration: underline; }

  /* Responsivo */
  @media (max-width: 880px) {
    .register-box { flex-direction: column; height: auto; max-width: 460px; }
    .register-left { height: 240px; }
    .register-left::before { clip-path: polygon(0 0, 100% 0, 100% 82%, 0 100%); }
    .left-content { padding: 60px 40px; text-align: center; }
    .left-content h1 { font-size: 44px; }
    .left-content h1 span { font-size: 52px; }
    .register-right { padding: 50px 35px; }
  }
</style>
</head>
<body class="hold-transition register-page">

<div class="register-box">

  <!-- Painel Esquerdo -->
  <div class="register-left">
    <div class="bubble-top"></div>
    <div class="bubble-bottom"></div>
    <div class="left-content">
      <h1>Hello!<span>Have a GOOD DAY</span></h1>
      <p>Bem-vindo ao Catálogo de Produtos — gerencie seus produtos com rapidez e segurança.</p>
    </div>
  </div>

  <!-- Painel Direito - Formulário de Cadastro -->
  <div class="register-right">
    <div class="register-card-body">
      <p class="register-box-msg">Cadastre todos os dados para ter acesso à agenda</p>

      <form action="" method="post" enctype="multipart/form-data">

        <div class="form-group mb-4">
          <label>Foto do usuário <small class="text-muted">(opcional)</small></label>
          <div class="custom-file">
            <input type="file" class="custom-file-input" name="foto" id="foto" accept="image/*">
            <label class="custom-file-label" for="foto">Escolher arquivo...</label>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="text" name="nome" class="form-control" placeholder="Nome completo" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-user"></span></div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="E-mail" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
          </div>
        </div>

        <div class="input-group mb-4">
          <input type="password" name="senha" class="form-control" placeholder="Senha forte" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>
        </div>

        <button type="submit" name="botao" class="btn btn-primary btn-block">Finalizar Cadastro</button>
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
              echo '<div class="alert alert-success mt-3"><i class="fas fa-check mr-2"></i>Cadastro realizado com sucesso!</div>';
          } catch(Exception $e) {
              echo '<div class="alert alert-danger mt-3">Erro ao cadastrar. Tente novamente.</div>';
          }
      }
      ?>

      <p class="text-center mt-4">
        <a href="index.php">← Voltar para o Login</a>
      </p>
    </div>
  </div>
</div>

</body>
</html>