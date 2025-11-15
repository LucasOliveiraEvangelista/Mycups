<?php
require_once '../includes/db.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    // Verifica se já existe e-mail
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $msg = "⚠️ E-mail já cadastrado.";
    } else {
        $hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $email, $hash]);

        $msg = "✅ Cadastro realizado com sucesso!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - MyCups</title>
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
        .msg.success {
            border-left: 4px solid #28a745;
            color: #28a745;
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
    <div class="logo"> 🧁 MyCups</div>
    <h2>Crie sua conta</h2>

    <?php if ($msg): ?>
        <div class="msg <?= strpos($msg, 'sucesso') ? 'success' : 'error' ?>">
            <?= $msg ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label for="nome">Nome completo</label>
        <input type="text" name="nome" id="nome" placeholder="Digite seu nome" required>

        <label for="email">E-mail</label>
        <input type="email" name="email" id="email" placeholder="Digite seu e-mail" required>

        <label for="senha">Senha</label>
        <input type="password" name="senha" id="senha" placeholder="Crie uma senha" required>

        <button type="submit">Cadastrar</button>
    </form>

    <p>Já tem conta? <a href="login.php">Entrar</a></p>
</div>

</body>
</html>
