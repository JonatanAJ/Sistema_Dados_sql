<?php 

// Verifica se a sessão está ativa antes de configurar
if (session_status() !== PHP_SESSION_ACTIVE) {
    // Configurações de segurança para sessões
    ini_set('session.use_strict_mode', 1);         // Impede IDs de sessão inválidos
    ini_set('session.cookie_secure', 1);           // Cookies apenas via HTTPS
    ini_set('session.cookie_httponly', 1);         // Bloqueia acesso via JavaScript
    ini_set('session.use_only_cookies', 1);        // Apenas cookies, sem URL-based sessions
    ini_set('session.cookie_samesite', 'Strict');  // Cookies enviados apenas no mesmo site

    // Configurações de cookie
    session_set_cookie_params([
        'lifetime' => 1600,    // Tempo de expiração (1600 segundos = 26 minutos e 40 segundos)
        'path'     => '/',     // Caminho para o qual o cookie é válido
        'domain'   => '',      // Domínio (deixe vazio para padrão)
        'secure'   => true,    // Apenas via HTTPS
        'httponly' => true,    // Acesso apenas pelo servidor
        'samesite' => 'Strict' // Restringe envio entre sites
    ]);

    // Inicia a sessão
    session_start();
}

// Renova o ID da sessão para evitar sequestro
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// Função para verificar e atualizar o tempo de sessão
function verificarTempoSessao($tempoMaximo = 600) { // Tempo padrão: 10 minutos
    // Verificar se há atividade e calcular o tempo de inatividade
    if (isset($_SESSION['ultimo_acesso']) && (time() - $_SESSION['ultimo_acesso'] > $tempoMaximo)) {
        session_unset();     // Remove todas as variáveis da sessão
        session_destroy();   // Destroi a sessão
        header("Location: ../public/pagina_login.php"); // Redireciona para a página de login
        exit();
    }
    
    // Atualizar o registro de tempo da última atividade
    $_SESSION['ultimo_acesso'] = time();
}

// Protege contra sequestro de sessão
function protegerSessao() {
    $token = hash('sha256', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
    if (!isset($_SESSION['user_token'])) {
        $_SESSION['user_token'] = $token;
    } elseif ($_SESSION['user_token'] !== $token) {
        session_unset();
        session_destroy();
        die('Sessão inválida.');
    }
}

// Chamar as funções de verificação
verificarTempoSessao(); // Verifica o tempo de sessão
protegerSessao();       // Verifica a integridade da sessão

