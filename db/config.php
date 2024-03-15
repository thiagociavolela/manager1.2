<?php

// Detalhes da conexão ao banco de dados
$servername = "localhost";
$username = "root";
$password = "A10y12a28@";
$dbname = "manager_evo";

// Opções de PDO (opcional)
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    // Criar conexão PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password, $options);
} catch (PDOException $e) {
    // Exibir mensagem de erro
    echo "Falha na conexão: " . $e->getMessage();
    die();
}

?>