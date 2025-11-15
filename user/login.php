<?php
session_start();
require_once '../includes/db.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario_nome'] = $user['nome'];
        $_SESSION['usuario_tipo'] = $user['tipo'];

        if ($user['tipo'] === 'admin') {
            header('Location: ../admin/index.php');
        } else {
            header('Location: ../index.php');
        }
        exit;
    } else {
        $msg = "❌ E-mail ou senha incorretos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - MyCups</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #47008D, #6a11cb);
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 40px 50px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 420px;
            text-align: center;
        }
        .logo {
            font-size: 2em;
            font-weight: 700;
            color: #47008D;
            margin-bottom: 10px;
        }
        h2 {
            font-weight: 600;
            color: #333;
            margin-bottom: 25px;
        }
        form {
            text-align: left;
        }
        label {
            font-weight: 500;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 1em;
            transition: 0.3s;
        }
        input:focus {
            border-color: #47008D;
            outline: none;
            box-shadow: 0 0 5px rgba(71, 0, 141, 0.4);
        }
        button {
            width: 100%;
            background: #47008D;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #5d00b3;
        }
        p {
            font-size: 0.95em;
            margin-top: 15px;
        }
        a {
            color: #47008D;
            text-decoration: none;
            font-weight: 500;
        }
        a:hover {
            text-decoration: underline;
        }
        .msg {
            background: #f4f4f9;
            padding: 10px;
            border-radius: 6px;
            font-size: 0.9em;
            margin-bottom: 15px;
        }
        .msg.error {
            border-left: 4px solid #dc3545;
            color: #dc3545;
        }
        @media (max-width: 480px) {
            .container {
                padding: 30px 25px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="logo">🧁 MyCups</div>
    <h2>Entrar na sua conta</h2>

    <?php if ($msg): ?>
        <div class="msg error"><?= $msg ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="email">E-mail:</label>
        <input type="email" name="email" id="email" required placeholder="Digite seu e-mail">

        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required placeholder="Digite sua senha">

        <button type="submit">Entrar</button>
    </form>

    <p>Não tem conta? <a href="cadastro.php">Cadastrar agora</a></p>
</div>

</body>
</html>
