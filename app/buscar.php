

<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../public/pagina_login.php');
    exit;
}





// Conectar ao banco SQL Server com base nas credenciais da sessão
if (isset($_SESSION['sqlsrv_credentials'])) {
    $servername_sqlsrv = $_SESSION['sqlsrv_credentials']['servername'];
    $database_sqlsrv = $_SESSION['sqlsrv_credentials']['database'];
    $username_sqlsrv = $_SESSION['sqlsrv_credentials']['username'];
    $password_sqlsrv = $_SESSION['sqlsrv_credentials']['password'];



    
    

    try {

        // Conectar ao SQL Server
        $conn_sqlsrv = new PDO("sqlsrv:Server=$servername_sqlsrv;Database=$database_sqlsrv", $username_sqlsrv, $password_sqlsrv);
        $conn_sqlsrv->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Erro de conexão com o SQL Server: " . $e->getMessage();
        exit;
    }
}

// Processar o formulário de busca
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $codigo = trim($_POST['codigo']);
    
    // Constrói a consulta de acordo com os campos preenchidos
    $sql = "SELECT * FROM Pessoas WHERE 1=1"; 
    $params = [];

    if (!empty($nome)) {
        $sql .= " AND nmPessoa = ?";
        $params[] = $nome;
    }
    if (!empty($codigo)) {
        $sql .= " AND cdPessoa = ?";
        $params[] = $codigo;
    }

    $stmt = $conn_sqlsrv->prepare($sql);
    $stmt->execute($params);

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se encontrado, redireciona para pagina_dados.php passando o cdPessoa
    if ($resultado) {
        $_SESSION['cdPessoa'] = $resultado['cdPessoa'];  
        header('Location: ../public/pagina_dados.php');  
        exit;
    }
}




