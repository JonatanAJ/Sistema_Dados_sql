<?php
session_start();

// Verificar se o usuário já está logado
if (isset($_SESSION['user_id'])) {
    header('Location: ../public/pagina_buscar.php');
    exit;
}

// Processar o login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Configurações do MySQL
    $servername_mysql = "localhost";
    $username_mysql = "root";
    $password_mysql = "";
    $dbname_mysql = "login";

    $conn_mysql = new mysqli($servername_mysql, $username_mysql, $password_mysql, $dbname_mysql);

    if ($conn_mysql->connect_error) {
        die("Falha na conexão: " . $conn_mysql->connect_error);
    }

    // Receber as credenciais do formulário
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Consultar o usuário no MySQL
    $sql = "SELECT * FROM usuarios WHERE email = ? AND senha = ?";
    $stmt = $conn_mysql->prepare($sql);
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        // Conectar ao SQL Server com as credenciais do usuário
        $servername_sqlsrv = $usuario['servername'];
        $database_sqlsrv = $usuario['namedatabase'];
        $username_sqlsrv = $usuario['nameuser'];
        $password_sqlsrv = $usuario['senhabd'];

        try {
            $conn_sqlsrv = new PDO("sqlsrv:Server=$servername_sqlsrv;Database=$database_sqlsrv", $username_sqlsrv, $password_sqlsrv);
            if ($conn_sqlsrv) {
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['sqlsrv_credentials'] = [
                    'servername' => $servername_sqlsrv,
                    'database' => $database_sqlsrv,
                    'username' => $username_sqlsrv,
                    'password' => $password_sqlsrv
                ];
                header('Location: ../public/pagina_buscar.php');
                exit;
            }
        } catch (PDOException $e) {
            echo "Erro de conexão com o SQL Server: " . $e->getMessage();
        }
    } else {
        header('Location: ../public/pagina_login.php?erro=1');
        exit;
    }

    $stmt->close();
    $conn_mysql->close();
}
?>
