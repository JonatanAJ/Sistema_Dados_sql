<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id']) || !isset($_SESSION['cdPessoa'])) {
    header('Location: ../public/pagina_login.php');
    exit;
}

// Obter cdPessoa da sessão
$cdPessoa = $_SESSION['cdPessoa'];

// Verificar se as credenciais SQL Server já estão carregadas na sessão
if (!isset($_SESSION['sqlsrv_credentials'])) {
    // Conectar ao banco MySQL para obter as credenciais do SQL Server
    $mysqli = new mysqli("localhost", "root", "", "login");

    if ($mysqli->connect_error) {
        die("Erro de conexão com o MySQL: " . $mysqli->connect_error);
    }

    // Consultar as credenciais SQL Server para o usuário
    $user_id = $_SESSION['user_id'];
    $query = "SELECT servername, namedatabase, nameuser, senhabd FROM usuarios WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Armazenar as credenciais do SQL Server na sessão
        $_SESSION['sqlsrv_credentials'] = [
            'servername' => $row['servername'],
            'database' => $row['namedatabase'],
            'username' => $row['nameuser'],
            'password' => $row['senhabd']
        ];
    } else {
        die("Credenciais do SQL Server não encontradas para o usuário.");
    }

    $stmt->close();
    $mysqli->close();
}

// Função para criar a conexão com o banco de dados SQL Server
function createConnection() {
    $credentials = $_SESSION['sqlsrv_credentials'] ?? null;
    if (!$credentials) {
        die("Credenciais do SQL Server não disponíveis.");
    }

    $dsn = "sqlsrv:Server={$credentials['servername']};Database={$credentials['database']};TrustServerCertificate=true";
    try {
        $pdo = new PDO($dsn, $credentials['username'], $credentials['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erro na conexão com o SQL Server: " . $e->getMessage());
    }
}

// Função para obter o código do plano de um usuário
function getCdPlano($pdo, $cdPessoa) {
    $sql = "SELECT cdPlano FROM Planos WHERE cdPessoa = :cdPessoa";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':cdPessoa', $cdPessoa, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Função para obter os detalhes do plano
function getPlanoDetails($pdo, $cdPlano) {
    $sql = "SELECT * FROM Planos WHERE cdPlano = :cdPlano";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':cdPlano', $cdPlano, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Iniciar a conexão e obter os dados necessários
if (!isset($pdo)) {
    $pdo = createConnection();
}

if (!isset($_SESSION['cdPlano'])) {
    $cdPlano = getCdPlano($pdo, $cdPessoa);
    $_SESSION['cdPlano'] = $cdPlano;
} else {
    $cdPlano = $_SESSION['cdPlano'];
}

if ($cdPlano) {
    $planoDetails = getPlanoDetails($pdo, $cdPlano);
}

function formatarDocumento($numero) {
    $numero = preg_replace('/\D/', '', $numero); // Remove qualquer caractere não numérico

    if (strlen($numero) === 11) {
        // CPF
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $numero);
    } elseif (strlen($numero) === 14) {
        // CNPJ
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $numero);
    } elseif (strlen($numero) === 9) {
        // RG
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{1})/', '$1.$2.$3-$4', $numero);
    } else {
        return $numero;
    }
}

// Função para formatar números de telefone
function formatarTelefone($numero) {
    $numero = preg_replace('/\D/', '', $numero); // Remove qualquer caractere não numérico

    if (strlen($numero) === 10) {
        return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $numero);
    } elseif (strlen($numero) === 11) {
        return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $numero);
    } else {
        return $numero; 
    }
}

// Função para formatar o CEP
function formatarCEP($cep) {
    $cep = preg_replace("/\D/", "", $cep); // Remove qualquer caractere que não seja número

    if (strlen($cep) == 8) {
        // Formata o CEP para o formato XXXXX-XXX
        return substr($cep, 0, 5) . '-' . substr($cep, 5);
    } else {
        // Retorna o CEP sem formatação caso não tenha 8 dígitos
        return $cep;
    }
}
?>
