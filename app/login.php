<?php
session_start();

// Verificar se o usuário já está logado
if (isset($_SESSION['user_id'])) {
    header('Location: ../public/pagina_buscar.php');
    exit;
}

// Processar o login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Configurações do SQL Server
    $serverName = "DESKTOP-B3L8FDR\\SQLJONATAN"; // Nome do servidor SQL Server
    $database = "login"; // Nome do banco de dados
    $username = null; // Usuário do SQL Server (se necessário, preencha)
    $password = null; // Senha do SQL Server (se necessário, preencha)

    try {
        // Conectar ao SQL Server com PDO
        $conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password, [
            PDO::SQLSRV_ATTR_DIRECT_QUERY => true, // Configuração adicional
        ]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Receber as credenciais do formulário
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        // Consultar o usuário no SQL Server
        $sql = "SELECT * FROM usuarios WHERE email = :email AND senha = :senha";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // Salvar dados do usuário na sessão
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['sqlsrv_credentials'] = [
                'servername' => $serverName,
                'database' => $database,
                'username' => $username,
                'password' => $password,
            ];
            header('Location: ../public/pagina_buscar.php');
            exit;
        } else {
            header('Location: ../public/pagina_login.php?erro=1');
            exit;
        }
    } catch (PDOException $e) {
        echo "Erro ao conectar ao SQL Server: " . $e->getMessage();
    }
}
?>
