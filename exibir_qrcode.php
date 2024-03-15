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

$instancia = $_GET['instancia'];

// URL da API para buscar as instâncias
$parametros = "/instance/fetchInstances";
$url = $url_api . $parametros;

// Dados do cabeçalho (headers)
$headers_fetch = array(
    "Content-Type: application/json",
    "apikey: " . $api_key
);

// Inicializa a sessão cURL
$ch_fetch = curl_init($url);

// Define as opções da requisição para buscar as instâncias
curl_setopt($ch_fetch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch_fetch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch_fetch, CURLOPT_HTTPHEADER, $headers_fetch);

// Executa a requisição para buscar as instâncias e obtém a resposta
$response_fetch = curl_exec($ch_fetch);

// Verifica por erros na requisição
if (curl_errno($ch_fetch)) {
    echo 'Erro na requisição cURL: ' . curl_error($ch_fetch);
}

// Decodifica a resposta JSON
$instances = json_decode($response_fetch, true);

// Fecha a sessão cURL
curl_close($ch_fetch);

// Variável para armazenar a instância encontrada
$instancia_encontrada = null;

// Itera sobre as instâncias buscando pela instância desejada
foreach ($instances as $instance) {
    if ($instance['instance']['instanceName'] === $instancia) {
        $instancia_encontrada = $instance;
        break; // Interrompe o loop quando a instância é encontrada
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
                        <div class="card">
                            <h5 class="card-header">Instancia: <span><strong><?php echo $instancia ?></strong></span></h5>
                            <hr>
                            <?php

                            if ($instancia_encontrada['instance']['status'] === 'open') {
                                echo '<img src="assets/img/whats-conectado.jpg" style="width:300px; margin-left:30px; margin-bottom:30px; border-radius:12px;">';
                            } else {

                                echo '<div style="padding-left: 20px; padding-right:20px">
                                        <div class="alert alert-danger" role="alert" style="padding: 20px;">QRCODE é válido por apenas 40 segundos, Caso não apareça ou erro, atualize a página.</div>
                                        </div>';

                                // URL da API para conectar a instância e obter o QR Code
                                $parametros = "/instance/connect/";
                                $url_connect = $url_api . $parametros . $instancia;

                                // Dados do cabeçalho (headers)
                                $headers_connect = array(
                                    "Content-Type: application/json",
                                    "apikey: " . $api_key
                                );

                                // Inicializa a sessão cURL
                                $ch_connect = curl_init($url_connect);

                                // Define as opções da requisição para conectar a instância
                                curl_setopt($ch_connect, CURLOPT_CUSTOMREQUEST, "GET");
                                curl_setopt($ch_connect, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch_connect, CURLOPT_HTTPHEADER, $headers_connect);

                                // Executa a requisição para conectar a instância e obtém a resposta
                                $response_connect = curl_exec($ch_connect);

                                // Verifica por erros na requisição
                                if (curl_errno($ch_connect)) {
                                    echo 'Erro na requisição cURL: ' . curl_error($ch_connect);
                                }

                                // Decodifica a resposta JSON
                                $connect_data = json_decode($response_connect, true);


                                // Fecha a sessão cURL
                                curl_close($ch_connect);

                                // Verifica se a conexão foi bem-sucedida e exibe o QR Code
                                if (isset($connect_data['code']) && isset($connect_data['base64'])) {
                                    echo "<img src='" . $connect_data['base64'] . "' alt='QR Code' style='width:300px; margin-left:20px; margin-bottom:20px'>";
                                } else {
                                    echo 'Não foi possível conectar a instância ou gerar o QR Code.';
                                }
                            }

                            ?>
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