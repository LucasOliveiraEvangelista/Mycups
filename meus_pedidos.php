<?php
session_start();
require_once 'includes/db.php';

// Simulação temporária de login (ajuste quando o sistema estiver ativo)
$usuario_id = $_SESSION['usuario_id'] ?? 1;

// Redireciona se não estiver logado
if (!$usuario_id) {
    echo "<script>alert('Você precisa estar logado para ver seus pedidos.'); window.location='login.php';</script>";
    exit;
}

// Verifica se é visualização de detalhes
$pedido_id = $_GET['pedido_id'] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Pedidos - Okto Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f7f7fb;
            margin: 0;
            padding: 0;
        }

        /* Navbar */
        nav {
            background: #47008D;
            color: #fff;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        nav .logo {
            font-weight: 600;
            font-size: 1.2em;
            letter-spacing: 1px;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            margin-left: 20px;
            font-weight: 500;
            transition: 0.3s;
        }

        nav a:hover {
            opacity: 0.8;
        }

        .container {
            max-width: 900px;
            background: #fff;
            margin: 50px auto;
            padding: 40px;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        }

        h1 {
            text-align: center;
            color: #47008D;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            text-align: left;
            padding: 14px;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #47008D;
            color: #fff;
            font-weight: 500;
            text-align: center;
        }

        td {
            text-align: center;
            color: #333;
        }

        tr:hover {
            background: #f9f9f9;
        }

        .btn {
            background: #47008D;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.9em;
            transition: 0.3s;
            font-weight: 500;
            display: inline-block;
        }

        .btn:hover {
            background: #5f00c2;
        }

        .voltar {
            display: inline-block;
            margin-bottom: 20px;
            color: #47008D;
            text-decoration: none;
            font-weight: bold;
            transition: 0.2s;
        }

        .voltar:hover {
            opacity: 0.8;
        }

        p {
            color: #444;
        }

        .pedido-total {
            text-align: right;
            font-size: 1.2em;
            font-weight: bold;
            color: #47008D;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .container {
                margin: 20px;
                padding: 25px;
            }

            th, td {
                padding: 10px;
                font-size: 0.9em;
            }

            nav {
                flex-direction: column;
                align-items: flex-start;
            }

            nav a {
                margin: 8px 0 0 0;
            }
        }
    </style>
</head>
<body>

<nav>
    <div class="logo">🛒 Okto Store</div>
    <div>
        <a href="index.php">Início</a>
        <a href="sobre.php">Sobre</a>
        <a href="meus_pedidos.php" style="font-weight:600;">Meus Pedidos</a>
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <a href="logout.php">Sair</a>
        <?php else: ?>
            <a href="login.php">Entrar</a>
        <?php endif; ?>
    </div>
</nav>

<div class="container">
    <?php if (!$pedido_id): ?>
        <h1>📦 Meus Pedidos</h1>

        <?php
        $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY criado_em DESC");
        $stmt->execute([$usuario_id]);
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <?php if (count($pedidos) === 0): ?>
            <p style="text-align:center; font-size:1.1em; color:#666;">Você ainda não fez nenhum pedido.</p>
        <?php else: ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Data</th>
                <th>Status</th>
                <th>Total</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($pedidos as $p): ?>
            <tr>
                <td>#<?= $p['id'] ?></td>
                <td><?= date('d/m/Y H:i', strtotime($p['criado_em'])) ?></td>
                <td><?= htmlspecialchars($p['status']) ?></td>
                <td>R$ <?= number_format($p['total'], 2, ',', '.') ?></td>
                <td><a href="?pedido_id=<?= $p['id'] ?>" class="btn">Ver Detalhes</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>

    <?php else: ?>
        <?php
        $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$pedido_id, $usuario_id]);
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pedido) {
            echo "<p>Pedido não encontrado.</p>";
        } else {
            $stmtItens = $pdo->prepare("SELECT ip.*, p.nome, p.preco FROM itens_pedido ip 
                                        JOIN produtos p ON ip.produto_id = p.id
                                        WHERE ip.pedido_id = ?");
            $stmtItens->execute([$pedido_id]);
            $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <a href="meus_pedidos.php" class="voltar">← Voltar para meus pedidos</a>
        <h1>Detalhes do Pedido #<?= $pedido['id'] ?></h1>
        <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($pedido['criado_em'])) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($pedido['status']) ?></p>

        <table>
            <tr>
                <th>Produto</th>
                <th>Qtd</th>
                <th>Preço</th>
                <th>Subtotal</th>
            </tr>
            <?php foreach ($itens as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['nome']) ?></td>
                <td><?= $item['quantidade'] ?></td>
                <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                <td>R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <p class="pedido-total">Total: R$ <?= number_format($pedido['total'], 2, ',', '.') ?></p>
        <?php } ?>
    <?php endif; ?>
</div>

</body>
</html>
