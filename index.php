<?php
  session_start();

  // redireciona se já logado
  if (isset($_SESSION['loginUser']) && isset($_SESSION['senhaUser'])) {
      header("Location: paginas/home.php");
      exit;
  }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Catálogo de Produtos | Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <style>
    /* Layout split */
    :root{
      --blue-700:#0b2b66;
      --blue-500:#152b8f;
      --accent:#0a4fe0;
    }
    html,body{height:100%;}
    body{
      margin:0;
      font-family: "Source Sans Pro", "Helvetica Neue", Arial, sans-serif;
      background:#f3f6fb;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:20px;
    }
    .split-wrap{
      width: 100%;
      max-width: 980px;
      height: 560px;
      display:flex;
      border-radius:12px;
      overflow:hidden;
      box-shadow:0 18px 50px rgba(10,30,60,0.25);
      background:#fff;
    }
    .left-panel{
      flex:1;
      background: linear-gradient(180deg, var(--blue-700), var(--blue-500));
      color:#fff;
      padding:48px 36px;
      display:flex;
      flex-direction:column;
      justify-content:center;
      position:relative;
    }
    .left-panel h1{
      font-size:34px;
      margin:0 0 10px 0;
      letter-spacing:0.6px;
    }
    .left-panel p.lead{
      font-size:20px;
      margin:0 0 18px 0;
      opacity:0.95;
    }
    /* Decorative circles */
    .left-panel::before,
    .left-panel::after{
      content:'';
      position:absolute;
      border-radius:50%;
      opacity:0.06;
      pointer-events:none;
    }
    .left-panel::before{
      width:200px;height:200px;
      right: -60px; top: -40px;
      background: #ffffff;
    }
    .left-panel::after{
      width:140px;height:140px;
      left: -40px; bottom: -40px;
      background:#ffffff;
    }

    .right-panel{
      width:420px;
      background:#ffffff;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:36px;
    }
    .card-login{
      width:100%;
      max-width:360px;
    }
    .card-login .card-body{
      padding:28px;
    }
    .card-login .card-title{
      font-weight:600;
      margin-bottom:8px;
      color:#192034;
      text-align:center;
      font-size:20px;
    }
    .form-control{
      border-radius:8px;
      height:44px;
      border:1px solid #e6edf5;
      box-shadow:none;
    }
    .btn-login{
      background:linear-gradient(90deg,var(--accent),#00b4ff);
      color:#fff;
      border-radius:10px;
      padding:10px 14px;
      font-weight:600;
      border:none;
      width:100%;
    }
    .small-link{font-size:13px; color:#5b6b85;}
    .text-muted-center{text-align:center; margin-top:14px;}
    @media(max-width:880px){
      .split-wrap{height:auto; flex-direction:column;}
      .right-panel{width:100%;}
      .left-panel{padding:28px; order:2;}
      .right-panel{order:1; padding:24px;}
    }
  </style>
</head>
<body>
  <div class="split-wrap">
    <div class="left-panel">
      <h1>Hello!</h1>
      <p class="lead">Have a<br><strong style="font-size:28px;letter-spacing:0.8px;">GOOD DAY</strong></p>
      <p style="max-width:320px; opacity:0.9;">Bem-vindo ao Catálogo de Produtos — gerencie seus produtos com rapidez e segurança.</p>
    </div>

    <div class="right-panel">
      <div class="card card-login">
        <div class="card-body">
          <h3 class="card-title">Login</h3>

          <form action="" method="post" novalidate>
            <div class="form-group">
              <input type="email" name="email" class="form-control" placeholder="E-mail" required autofocus>
            </div>
            <div class="form-group">
              <input id="senha" type="password" name="senha" class="form-control" placeholder="Senha" required>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
              <div>
                <input type="checkbox" id="lembrar" name="lembrar">
                <label for="lembrar" class="small-link"> Lembrar-me</label>
              </div>
              <div>
                <a href="#" id="toggleSenha" class="small-link"><i class="fas fa-eye"></i> Mostrar</a>
              </div>
            </div>

            <button type="submit" name="login" class="btn-login">Login</button>
          </form>

      <?php
    include_once('config/conexao.php');
                   
// Exibir mensagens com base na ação
if (isset($_GET['acao'])) {
    $acao = $_GET['acao'];
    if ($acao == 'negado') {
        echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Erro ao Acessar o sistema!</strong> Efetue o login ;(</div>';
       
    } elseif ($acao == 'sair') {
        echo '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Você acabou de sair da Agenda Eletrônica!</strong> :(</div>';
       
    }
}

// Processar o formulário de login
if (isset($_POST['login'])) {
    $login = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_DEFAULT);

    if ($login && $senha) {
        $select = "SELECT * FROM tb_user WHERE email_user = :emailLogin";

        try {
            $resultLogin = $conect->prepare($select);
            $resultLogin->bindParam(':emailLogin', $login, PDO::PARAM_STR);
            $resultLogin->execute();

            $verificar = $resultLogin->rowCount();
            if ($verificar > 0) {
                $user = $resultLogin->fetch(PDO::FETCH_ASSOC);

                // Verifica a senha
                if (password_verify($senha, $user['senha_user'])) {
                    // Criar sessão
                    $_SESSION['loginUser'] = $login;
                    $_SESSION['senhaUser'] = $user['id_user'];
                  

                    echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Logado com sucesso!</strong> Você será redirecionado para a agenda :)</div>';

                    header("Refresh: 5; url=paginas/home.php?acao=bemvindo");
                } else {
                    echo '<div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Erro!</strong> Senha incorreta, tente novamente.</div>';
                    header("Refresh: 7; url=index.php");
                }
            } else {
                echo '<div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Erro!</strong> E-mail não encontrado, verifique seu login ou faça o cadastro.</div>';
                header("Refresh: 7; url=index.php");
            }
        } catch (PDOException $e) {
            // Log the error instead of displaying it to the user
            error_log("ERRO DE LOGIN DO PDO: " . $e->getMessage());
            echo '<div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Erro!</strong> Ocorreu um erro ao tentar fazer login. Por favor, tente novamente mais tarde.</div>';
        }
    } else {
        echo '<div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Erro!</strong> Todos os campos são obrigatórios.</div>';
    }
}
      ?>


          <p class="text-muted-center small-link">Não possui conta? <a href="cad_user.php">Cadastre-se</a></p>
        </div>
      </div>
    </div>
  </div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
  // Toggle mostrar/ocultar senha
  document.getElementById('toggleSenha').addEventListener('click', function(e){
    e.preventDefault();
    var senha = document.getElementById('senha');
    var icon = this.querySelector('i');
    if (senha.type === 'password') {
      senha.type = 'text';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
      this.childNodes[1].nodeValue = ' Ocultar';
    } else {
      senha.type = 'password';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
      this.childNodes[1].nodeValue = ' Mostrar';
    }
  });
</script>
</body>
</html>
