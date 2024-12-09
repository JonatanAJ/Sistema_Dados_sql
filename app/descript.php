<?php

// Carregar variáveis de ambiente do .env
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


$key = $_ENV['AES_KEY']; // A chave tem 3 caracteres (pode ser expandida ou ajustada para AES-128)


// Função para descriptografar
function decrypt($data, $key) {
    // Usando openssl_decrypt para descriptografar com AES-128-ECB
    return openssl_decrypt($data, 'aes-128-ecb', $key, OPENSSL_RAW_DATA);
}

// Configuração de conexão com o banco
$servername = "localhost";
$username = "root";
$password = "";
$database = "login";

// Conectar ao banco de dados
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Falha na conexão com o MySQL: " . $conn->connect_error);
}

// Recuperar os dados criptografados do banco
$sql = "SELECT id, email, servername, namedatabase, nameuser, senhabd FROM usuarios";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Iterar pelos resultados e descriptografar os campos
    while ($row = $result->fetch_assoc()) {
        echo "ID: " . $row['id'] . "<br>";
        echo "Email: " . $row['email'] . "<br>";

        // Descriptografar os campos
        echo "Servername: " . decrypt($row['servername'], $key) . "<br>";
        echo "Database: " . decrypt($row['namedatabase'], $key) . "<br>";
        echo "User: " . decrypt($row['nameuser'], $key) . "<br>";
        echo "Password: " . decrypt($row['senhabd'], $key) . "<br><hr>";
    }
} else {
    echo "Nenhum dado encontrado.";
}

// Fechar a conexão
$conn->close();
?>
