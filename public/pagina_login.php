<?php 

include_once '../app/login.php'; 
require_once '../app/session_config.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="icon" href="../assets/imagens/HF-ICON.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" media="screen" href="../Assets/css/login.css">
</head>

<body>


<nav class="navbar navbar-expand-lg navbar-light d-flex justify-content-between">
    <a class="navbar-brand text-white" href="#">HF Solucoes</a>
</nav>

    <section class="container">
        <form method="post" action="pagina_login.php" class="p-4 border rounded">
            <div class="mb-3">
                <h2 class="text-center">Login</h2>
            </div>
           
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <div class="mb-3">
                <label for="usuario" class="form-label">Email</label>
                <input type="email" class="form-control" id="usuario" name="email" placeholder="Email" required aria-label="Email">
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" required>
            </div>
            <div class="d-grid gap-2">
                <input type="submit" value="Entrar" class="btn btn-primary">
            </div>
        </form>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>
