<?php
session_start();
if (!isset($_SESSION['user_id'])) {
} else {
    header('Location: index.php');
}

require 'db/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Consulta para verificar se o usuário e a senha correspondem
    $query = "SELECT * FROM contas WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user && $user['senha'] === md5($senha)) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['tokenConta'] = $user['token'];
        header('Location: index.php');
    } else {
        $error = "Login ou senha inválidas. Tente novamente.";
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Login - Sistema MKT</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/icon.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
    <style>
        .container_login {
            width: 100vw;
            height: 100vh;
            background: #f5f5f9;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
        }

        .box_login {
            width: 450px;
            height: 220px;
            background: #fff;
            border-radius: 16px;
            background-clip: padding-box;
            box-shadow: 0 2px 6px 0 rgb(67 89 113 / 12%);
        }

        body {
            margin: 0px;
        }

        .btn-warning {
            color: #fff;
            background-color: #696cff;
            border-color: #696cff;
            box-shadow: 0 0.125rem 0.25rem 0 #b39ddb;
        }

        .text-body {
            --bs-text-opacity: 1;
            color: #000000 !important;
            margin-left: 5px;
        }
    </style>
</head>

<body>

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <span class="app-brand-logo demo">
                                <img src="assets/img/icon.png">
                            </span>
                            <span class="app-brand-text demo text-body fw-bolder"> CursoDev</span>
                        </div>
                        <div class="error" style="text-align:center"><?php if (isset($error)) {
                                                                            echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                                                                        } ?></div>
                        <!-- /Logo -->
                        <h4 class="mb-2">Bem vindo! 👋</h4>
                        <p class="mb-4">Por favor, entre na sua conta e comece a Lucrar</p>

                        <form id="formAuthentication" class="mb-3" action="" method="POST">
                            <input name="login" value="" type="hidden" />
                            <div class="mb-3">
                                <label for="email" name="login" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="email@cursodev.com" autofocus />
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password" name="senha">Senha</label>
                                    <a href="#">
                                        <!--<small>Esqueceu a senha?</small>-->
                                    </a>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="senha" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit" name="logar">Entrar</button>
                            </div>
                        </form>


                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/js/menu.js"></script>
    <script src="assets/js/main.js"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>