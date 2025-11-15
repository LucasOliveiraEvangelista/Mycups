<?php
session_start();
require_once 'includes/db.php';

// Buscar produtos no banco
$stmt = $pdo->query("SELECT * FROM produtos ORDER BY id DESC");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>MyCups - Vitrine</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #faf8fc;
            color: #333;
        }

        /* NAVBAR */
        header {
            background-color: #47008D;
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

        nav a:hover {
            opacity: 0.8;
        }

        /* VITRINE */
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        h2 {
            text-align: center;
            color: #47008D;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .info {
            padding: 20px;
            text-align: center;
        }

        .info h3 {
            font-size: 1.1em;
            color: #333;
            margin-bottom: 10px;
        }

        .info p {
            color: #666;
            font-size: 0.95em;
            margin-bottom: 15px;
        }

        .price {
            font-weight: 600;
            color: #47008D;
            font-size: 1.1em;
        }

        .btn {
            display: inline-block;
            background: #47008D;
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        .btn:hover {
            background: #5d00b3;
        }

        /* RODAPÉ */
        footer {
            background: #f0eef3;
            text-align: center;
            padding: 25px;
            color: #555;
            font-size: 0.9em;
            margin-top: 40px;
        }

        @media (max-width: 600px) {
            header {
                flex-direction: column;
                gap: 10px;
                padding: 20px;
            }
            nav a {
                margin-left: 10px;
            }
        }
    </style>
</head>
<body>

<header>
    <div class="logo">🧁 MyCups</div>
    <nav>
        <a href="index.php">Início</a>
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <a href="meus_pedidos.php">Meus Pedidos</a>
            <a href="logout.php">Sair</a>
        <?php else: ?>
            <a href="user/login.php">Login</a>
        <?php endif; ?>
        <a href="sobre.php">Sobre</a>
    </nav>
</header>

<div class="container">
    <h2>Nossos Deliciosos Cupcakes</h2>

    <div class="grid">
        <?php foreach ($produtos as $produto): ?>
            <div class="card">
                <img src="uploads/<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                <div class="info">
                    <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                    <p><?= substr(htmlspecialchars($produto['descricao']), 0, 60) ?>...</p>
                    <div class="price">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></div>
                    <br>
                    <a href="produto.php?id=<?= $produto['id'] ?>" class="btn">Ver produto</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<footer id="sobre">
    <p><strong>🧁 MyCups</strong> — Inspirando momentos únicos com bolinho.</p>
    <p>Desenvolvido por Lucas • 2025</p>
</footer>

</body>
</html>
