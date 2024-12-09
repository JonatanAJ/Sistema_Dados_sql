<?php 
include_once '../app/buscar.php';
include_once '../app/session_config.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Dados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light d-flex justify-content-between">
    <a class="navbar-brand text-white" href="#">HF Solucoes</a>
    <button id="logout" class="btn btn-danger" onclick="window.location.href='../assets/logout.php';">Sair</button>
</nav>

<section class="container">
    <form method="POST" action="pagina_buscar.php" class="form-container p-4 border rounded">
        <div class="mb-3">
            <h2 class="text-center">Buscar Dados do Usuário</h2>
        </div>
        
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome">
        </div>
        <div class="mb-3">
            <label for="codigo" class="form-label">Código</label>
            <input type="number" class="form-control" id="codigo" name="codigo" min="1" placeholder="Digite o código">
        </div>
        <div class="d-grid gap-2">
            <input type="submit" value="Buscar" class="btn btn-primary">
        </div>

        <!-- Mensagens de erro ou sucesso -->
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <?php if (empty($nome) || empty($codigo)): ?>
                <p class="text-danger mt-3">Por favor, preencha os campos de Nome e Código.</p>
            <?php elseif (!$resultado): ?>
                <p class="text-danger mt-3">Nenhum resultado encontrado para os critérios informados.</p>
            <?php endif; ?>
        <?php endif; ?>
    </form>
</section>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

</body>
</html>
