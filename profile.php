<?php
session_start();
include "db/config.php";

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

if (isset($_POST['submit'])) {

    $nome       = $_POST['nome'];
    $cpf        = $_POST['cpf'];
    $whatsapp   = $_POST['whatsapp'];
    $email      = $_POST['email'];

    if (!empty($_POST['senha'])) {
        $senha      = md5($_POST['senha']);
        $resenha    = md5($_POST['resenha']);
    }else{
        $senha = $senha;
        $resenha = $resenha;
    }
    

    $sql = "UPDATE contas SET id = :id, nome = :nome, cpf = :cpf, whatsapp = :whatsapp, email = :email, senha = :senha, resenha = :resenha WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':whatsapp', $whatsapp);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->bindParam(':resenha', $resenha);

    $stmt->execute();

    if ($stmt->execute()) { //se ocorrer algum erro no cadastro o sistema avisa
        echo '
              <META HTTP-EQUIV=REFRESH CONTENT="0; URL=profile.php">
                  <script type="text/javascript">
                    alert("Alterações Efetuadas com Sucesso!!");
                  </script>';
    } else {
        echo '
              <META HTTP-EQUIV=REFRESH CONTENT="0; URL=profile.php">
                  <script type="text/javascript">
                    alert("Ops, aconteceu algum erro, tente novamente e se o erro persistir avise ao administrador!");
                  </script>';
    }
}

if (isset($_POST['deletarConta'])) {

    $stmt = $conn->prepare("DELETE FROM conta WHERE id = :id");
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    //se ocorrer algum erro no cadastro o sistema avisa
    if ($stmt->execute()) {
        echo '
              <META HTTP-EQUIV=REFRESH CONTENT="0; URL=login.php">
                  <script type="text/javascript">
                    alert("Conta Deletada com Suceso!!!");
                  </script>';
    } else {
        echo '
              <META HTTP-EQUIV=REFRESH CONTENT="0; URL=profile.php">
                  <script type="text/javascript">
                    alert("Ops, aconteceu algum erro, tente novamente e se o erro persistir avise ao administrador!");
                  </script>';
    }
}

if (isset($_POST['uploadFoto'])) {

    $errors = array();
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_type = $_FILES['image']['type'];
    $file_exploded = explode('.', $_FILES['image']['name']);
    $file_ext = strtolower(end($file_exploded));

    $extensions = array("jpeg", "jpg", "png");

    if (in_array($file_ext, $extensions) === false) {
        $errors[] = "Você deve enviar imagem .jpg, .png ou .gif";
    }

    if ($file_size > 2097152) {
        $errors[] = 'Arquivo Maior que 2 MB, Diminua a Imagem';
    }

    if (empty($errors)) {
        $file_path = "foto_profile/" . $file_name;

        if (move_uploaded_file($file_tmp, $file_path)) {
            $sql = "UPDATE contas SET file_name = :file_name WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':file_name', $file_name);

            if ($stmt->execute()) {
                echo '
                    <META HTTP-EQUIV=REFRESH CONTENT="0; URL=profile.php">
                    <script type="text/javascript">
                        alert("Foto Atualizada com Sucesso!!!");
                    </script>';
            } else {
                echo '
                    <META HTTP-EQUIV=REFRESH CONTENT="0; URL=profile.php">
                    <script type="text/javascript">
                        alert("Ops, aconteceu algum erro, tente novamente e se o erro persistir avise ao administrador!");
                    </script>';
            }
        } else {
            $errors[] = "Falha ao mover o arquivo para o diretório de destino.";
        }
    } else {
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Sistema - MKT</title>

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

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
    <script type="text/javascript">
        function fMasc(objeto, mascara) {
            obj = objeto
            masc = mascara
            setTimeout("fMascEx()", 1)
        }

        function fMascEx() {
            obj.value = masc(obj.value)
        }

        function mTel(tel) {
            tel = tel.replace(/\D/g, "")
            tel = tel.replace(/^(\d)/, "($1")
            tel = tel.replace(/(.{3})(\d)/, "$1)$2")
            if (tel.length == 9) {
                tel = tel.replace(/(.{1})$/, "-$1")
            } else if (tel.length == 10) {
                tel = tel.replace(/(.{2})$/, "-$1")
            } else if (tel.length == 11) {
                tel = tel.replace(/(.{3})$/, "-$1")
            } else if (tel.length == 12) {
                tel = tel.replace(/(.{4})$/, "-$1")
            } else if (tel.length > 12) {
                tel = tel.replace(/(.{4})$/, "-$1")
            }
            return tel;
        }
    </script>
    <style>
        div.img {
            margin: 5px;
            float: left;
            width: 180px;

        }


        div.img img {
            width: 100%;
            height: 180px;
            border-radius: 12px;
        }

        div.desc {
            padding: 15px;
            text-align: center;
        }
    </style>
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
                        <div class="row">
                            <div class="col-md-12">

                                <div class="card mb-4">
                                    <h5 class="card-header">Dados Pessoais</h5>
                                    <!-- Account -->
                                    <div class="card-body">
                                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                                            <img src="<?php if ($foto == null) {
                                                            echo "assets/img/avatars/1.png";
                                                        } else {
                                                            echo "foto_profile/" . "$foto";
                                                        } ?>" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                                            <form action="profile.php" method="post" enctype="multipart/form-data" name="uploadFoto">
                                                <div class="button-wrapper">
                                                    <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                                        <span class="d-none d-sm-block">Carregar Foto</span>
                                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                                        <input type="file" id="upload" name="image" class="account-file-input" hidden accept="image/png, image/jpeg" />
                                                    </label>
                                                    <button type="submit" name="uploadFoto" class="btn btn-outline-secondary account-image-reset mb-4">
                                                        <i class="bx bx-reset d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Salvar Foto</span>
                                                    </button>
                                                    <p class="text-muted mb-0">JPG, GIF ou PNG permitidos. Tamanho máximo de 2MB</p>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <hr class="my-0" />
                                    <div class="card-body">
                                        <form action="profile.php" method="post" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="mb-3 col-md-6">
                                                    <label for="nome" class="form-label">Nome</label>
                                                    <input class="form-control" type="text" id="nome" name="nome" value="<?php echo "$nome"; ?>" placeholder="" autofocus />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label for="lastName" class="form-label">CPF</label>
                                                    <input class="form-control" type="text" name="cpf" id="cpf" value="<?php echo "$cpf"; ?>" placeholder="" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label for="email" class="form-label">E-mail</label>
                                                    <input class="form-control" type="text" id="email" name="email" value="<?php echo "$email"; ?>" placeholder="" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="phoneNumber">WhatsApp</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text">BR (+55)</span>
                                                        <input type="text" id="whatsapp" name="whatsapp" value="<?php echo "$whatsapp"; ?>" class="form-control" onkeydown="javascript: fMasc( this, mTel );" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label for="state" class="form-label">Senha</label>
                                                    <input class="form-control" type="password" id="senha" name="senha" value="" placeholder="" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label for="zipCode" class="form-label">Confirmar senha</label>
                                                    <input type="password" class="form-control" id="resenha" name="resenha" value="" placeholder="" />
                                                </div>
                                                <div class="mt-2">
                                                    <button type="submit" name="submit" class="btn btn-primary me-2">Alterar dados</button>
                                                </div>
                                        </form>
                                    </div>
                                    <!-- /Account -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Content -->



                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->

                <!-- Core JS -->
                <!-- build:js assets/vendor/js/core.js -->
                <script src="assets/vendor/libs/jquery/jquery.js"></script>
                <script src="assets/vendor/libs/popper/popper.js"></script>
                <script src="assets/vendor/js/bootstrap.js"></script>
                <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

                <script src="js/custom1.js"></script>

                <script src="assets/vendor/js/menu.js"></script>
                <!-- endbuild -->

                <!-- Vendors JS -->

                <!-- Main JS -->
                <script src="assets/js/main.js"></script>

                <!-- Page JS -->

                <!-- Place this tag in your head or just before your close body tag. -->
                <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>