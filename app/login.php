<?php

session_start();

// Carregar variáveis de ambiente do .env
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Verificar se o usuário já está logado
if (isset($_SESSION['user_id'])) {
    header('Location: ../public/pagina_buscar.php');
    exit;
}

// Gerar um token CSRF único se não existir
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Processar o login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar o token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Token CSRF inválido ou ausente.');
    }

    // Configurações do MySQL
    $conn_mysql = new mysqli(
        $_ENV['MYSQL_SERVERNAME'],
        $_ENV['MYSQL_USERNAME'],
        $_ENV['MYSQL_PASSWORD'],
        $_ENV['MYSQL_DATABASE']
    );

    if ($conn_mysql->connect_error) {
        die("Falha na conexão com o MySQL.");
    }

    // Receber e sanitizar as credenciais do formulário
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if ($email === false) {
        die("Email inválido.");
    }

    $senha = trim($_POST['senha']);

    $key_bin  = $_ENV['AES_KEY']; 

    
    $sql = "
        SELECT id, email, senha, 
               CAST(AES_DECRYPT(servername, ?) AS CHAR) AS servername,
               CAST(AES_DECRYPT(namedatabase, ?) AS CHAR) AS namedatabase,
               CAST(AES_DECRYPT(nameuser, ?) AS CHAR) AS nameuser,
               CAST(AES_DECRYPT(senhabd, ?) AS CHAR) AS senhabd
        FROM usuarios WHERE email = ?";
    
    $stmt = $conn_mysql->prepare($sql);
    $stmt->bind_param("sssss", $key_bin, $key_bin, $key_bin, $key_bin, $email);

    // Executar a consulta
    $stmt->execute();
    $result = $stmt->get_result();

    

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        // Verificar a senha com password_verify (comparando a senha com o hash armazenado)
        if (password_verify($senha, $usuario['senha'])) {  // Verifica se a senha fornecida corresponde ao hash armazenado
            // Conectar ao SQL Server com as credenciais do usuário
            $servername_sqlsrv = $usuario['servername'];
            $database_sqlsrv = $usuario['namedatabase'];
            $username_sqlsrv = $usuario['nameuser'];
            $password_sqlsrv = $usuario['senhabd'];

            try {
                $conn_sqlsrv = new PDO("sqlsrv:Server=$servername_sqlsrv;Database=$database_sqlsrv", $username_sqlsrv, $password_sqlsrv);
                if ($conn_sqlsrv) {
                    // Armazenar informações na sessão
                    $_SESSION['user_id'] = $usuario['id'];
                    $_SESSION['user_email'] = $usuario['email'];
                    $_SESSION['sqlsrv_credentials'] = [
                        'servername' => $servername_sqlsrv,
                        'database' => $database_sqlsrv,
                        'username' => $username_sqlsrv,
                        'password' => $password_sqlsrv,
                    ];

                    // Regenerar o token CSRF após login
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    header('Location: ../public/pagina_buscar.php');
                    exit;
                }
            } catch (PDOException $e) {
                error_log("Erro de conexão com o SQL Server: " . $e->getMessage());
                die("Erro interno. Tente novamente mais tarde.");
            }
        } else {
            header('Location: ../public/pagina_login.php?erro=invalid_credentials');
            exit;
        }
    } else {
        header('Location: ../public/pagina_login.php?erro=invalid_credentials');
        exit;
    }

    $stmt->close();
    $conn_mysql->close();
}

?>
