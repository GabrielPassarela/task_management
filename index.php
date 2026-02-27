<?php
session_start(); // Inicia a sessão

if (isset($_SESSION['login_error'])) {
    echo '<p style="color: red;">' . $_SESSION['login_error'] . '</p>';
    unset($_SESSION['login_error']); // Limpa a mensagem de erro após exibi-la
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Task Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex align-items-center justify-content-center vh-100">
        <div class="card p-4 shadow" style="width: 100%; max-width: 400px;">
            <div class="text-center mb-1">
                <img src="logo-sistema.png" alt="Ícone do Task Management" width="100">
            </div>
            <h3 class="text-center mb-4">Task Management 1.0</h3>
            <form action="login_usuario.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Usuário</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Digite seu usuário" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
        </div>
    </div>
</body>
</html>
