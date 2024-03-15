<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
}

include 'db/config.php';

$idConta = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM contas WHERE id = '$idConta'");
while ($exibir = $result->fetch(PDO::FETCH_OBJ)) {
    if ($result) {
        $nome = $exibir->nome;
        $id = $exibir->id;
        $tokenConta = $exibir->token;
        $cpf = $exibir->cpf;
        $whatsapp = $exibir->whatsapp;
        $email = $exibir->email;
        $senha = $exibir->senha;
        $resenha = $exibir->resenha;
        $foto = $exibir->file_name;
    }
}

try {
    $sql = 'SELECT * FROM config_api';
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) > 0) {
        foreach ($results as $result) {
            $instancia_ID = $result['id'];
            $url_api = $result['url_api'];
            $api_key = $result['api_key'];
        }
    }
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}

if (isset($_POST['add_instancia'])) {

    $instancia = $_POST['instancia'];

    try {
        // Criando a Instancia na API
        $parametros = "/instance/create";
        $url = $url_api . $parametros;

        // Dados do cabeçalho (headers)
        $headers = array(
            "Content-Type: application/json",
            "apikey: " . $api_key
        );

        $token_instancia = bin2hex(random_bytes(16));
        // Dados do corpo da requisição (body)
        $data = array(
            "instanceName" => $instancia,
            "token" => $token_instancia,
            "qrcode" => true
        );

        // Converte os dados em JSON
        $data_json = json_encode($data);

        // Inicializa a sessão cURL
        $ch = curl_init($url);

        // Define as opções da requisição
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Executa a requisição e obtém a resposta
        $response = curl_exec($ch);

        // Verifica por erros
        if (curl_errno($ch)) {
            echo 'Erro na requisição cURL: ' . curl_error($ch);
        }

        // Fecha a sessão cURL
        curl_close($ch);

        // Se chegou até aqui, a requisição foi bem-sucedida
        echo "<script>javascript:alert('Instância criada com sucesso!');javascript:window.location='listar_instancias.php';</script>";
    } catch (Exception $e) {

        // Trata o erro de forma genérica
        echo "<script>javascript:alert('Aconteceu algum erro, tente novamente!');</script>";
        // Você pode logar o erro para debugar
        error_log('Error: ' . $e->getMessage());
    }
}


?>

<!DOCTYPE html>
<html lang="pt-br" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Manager - CursoDev</title>

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

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/29.0.0/classic/ckeditor.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <?php include "menu_side.php"; ?>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <?php include "navbar.php"; ?>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Criar /</span> Instância</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <?php

                                    $parametros = "/instance/fetchInstances";
                                    $url = $url_api . $parametros;

                                    // Dados do cabeçalho (headers)
                                    $headers = array(
                                        "Content-Type: application/json",
                                        "apikey: " . $api_key
                                    );

                                    $ch = curl_init($url);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                                    $response = curl_exec($ch);
                                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                    curl_close($ch);

                                    if ($httpCode != 200) {
                                        echo '<div style="padding:10px"><span class="badge rounded-pill bg-danger">SUA API ESTÁ OFF-LINE, INICIE PARA CRIAR SUA INSTÂNCIA.</span></div>';
                                    } else {
                                        echo '<form id="formAccountSettings" method="POST" enctype="multipart/form-data" name="add_instancia">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="mb-3 col-md-6">
                                                    <label for="titulo" class="form-label">Nome da Instância</label>
                                                    <input class="form-control" type="text" id="instancia" name="instancia" value="" autofocus />
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <button type="submit" class="btn btn-primary me-2" name="add_instancia">Criar Instância</button>
                                            </div>
                                    </form>';
                                    }

                                    ?>
                                    
                                    
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <!-- / Content -->
                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="assets/js/pages-account-settings-account.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>