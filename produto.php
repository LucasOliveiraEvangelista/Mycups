<?php
require_once 'includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
$stmt->execute([$id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($produto['nome']) ?> - MyCups</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #faf8fc;
            margin: 0;
            color: #333;
        }

        /* NAVBAR */
        header {
            background: #47008D;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 40px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo {
            font-size: 1.6em;
            font-weight: 700;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 25px;
            font-weight: 500;
            transition: 0.3s;
        }

        nav a:hover { opacity: 0.8; }

        /* CONTEÚDO */
        .produto-container {
            max-width: 1200px;
            margin: 50px auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 6px 25px rgba(0,0,0,0.1);
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        }

        .imagem {
            background: #fafafa;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .imagem img {
            width: 100%;
            max-width: 450px;
            height: auto;
            object-fit: contain;
            border-radius: 10px;
        }

        .detalhes {
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .detalhes h1 {
            font-size: 2em;
            color: #222;
            margin-bottom: 10px;
        }

        .descricao {
            color: #555;
            line-height: 1.6;
            margin: 20px 0;
            font-size: 1em;
        }

        .preco {
            font-size: 1.8em;
            font-weight: 600;
            color: #47008D;
            margin: 20px 0;
        }

        .btns {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .btn {
            flex: 1;
            background: #47008D;
            color: white;
            text-align: center;
            padding: 14px 20px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn:hover {
            background: #5c0ac2;
            transform: translateY(-2px);
        }

        .btn-voltar {
            background: #999;
        }

        .btn-voltar:hover {
            background: #777;
        }

        footer {
            background: #f0eef3;
            text-align: center;
            padding: 25px;
            color: #555;
            font-size: 0.9em;
            margin-top: 60px;
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                gap: 10px;
                padding: 20px;
            }
            .produto-container {
                grid-template-columns: 1fr;
                margin: 20px;
            }
            .imagem {
                padding: 20px;
            }
            .detalhes {
                padding: 25px;
            }
            .detalhes h1 {
                font-size: 1.5em;
            }
        }
    </style>
</head>
<body>

<header>
    <div class="logo">🧁 MyCups</div>
    <nav>
        <a href="index.php">Início</a>
        <a href="meus_pedidos.php">Meus Pedidos</a>
        <a href="usuarios/login.php">Login</a>
        <a href="#sobre">Sobre</a>
    </nav>
</header>

<main>
    <div class="produto-container">
        <div class="imagem">
            <img src="<?= $produto['imagem'] ? 'uploads/'.$produto['imagem'] : 'https://via.placeholder.com/400x400?text=Sem+Imagem' ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
        </div>

        <div class="detalhes">
            <h1><?= htmlspecialchars($produto['nome']) ?></h1>
            <p class="descricao"><?= nl2br(htmlspecialchars($produto['descricao'])) ?></p>
            <div class="preco">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></div>

            <div class="btns">
                <a href="carrinho.php?add=<?= $produto['id'] ?>" class="btn">Adicionar ao Carrinho</a>
                <a href="index.php" class="btn btn-voltar">Voltar</a>
            </div>
        </div>
    </div>
</main>

<footer id="sobre">
    <p><strong>🧁 MyCups</strong> — Inspirando momentos únicos com bolinho.</p>
    <p>Desenvolvido por Lucas • 2025</p>
</footer>

</body>
</html>
