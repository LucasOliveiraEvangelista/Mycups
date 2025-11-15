<?php
session_start();
require_once 'includes/db.php';

// Inicializa o carrinho
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Adiciona produto
if (isset($_GET['add'])) {
    $id = (int) $_GET['add'];
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produto) {
        if (isset($_SESSION['carrinho'][$id])) {
            $_SESSION['carrinho'][$id]['quantidade']++;
        } else {
            $_SESSION['carrinho'][$id] = [
                'id' => $produto['id'],
                'nome' => $produto['nome'],
                'preco' => $produto['preco'],
                'imagem' => $produto['imagem'],
                'quantidade' => 1
            ];
        }
    }
    header("Location: carrinho.php");
    exit;
}

// Remover produto
if (isset($_GET['remove'])) {
    $id = (int) $_GET['remove'];
    unset($_SESSION['carrinho'][$id]);
    header("Location: carrinho.php");
    exit;
}

// Atualizar quantidade
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['atualizar'])) {
    foreach ($_POST['quantidade'] as $id => $qtd) {
        if (isset($_SESSION['carrinho'][$id])) {
            $_SESSION['carrinho'][$id]['quantidade'] = max(1, (int)$qtd);
        }
    }
    header("Location: carrinho.php");
    exit;
}

// Calcular total
$total = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $total += $item['preco'] * $item['quantidade'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho - Okto Store</title>
    <style>
        * {
            margin: 0; padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            background: #f5f5f5;
            color: #333;
        }

        /* Navbar */
        nav {
            background: #47008D;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 8%;
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        nav .logo {
            font-size: 1.5em;
            font-weight: 700;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 25px;
        }

        nav ul li a {
            text-decoration: none;
            color: #fff;
            font-weight: 500;
            transition: opacity 0.3s;
        }

        nav ul li a:hover {
            opacity: 0.8;
        }

        .container {
            width: 90%;
            max-width: 1000px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            padding: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        th, td {
            padding: 15px;
            text-align: center;
        }

        th {
            background: #f0f0f0;
            color: #333;
        }

        td img {
            width: 75px;
            height: 75px;
            object-fit: cover;
            border-radius: 10px;
        }

        td div {
            font-weight: 500;
            margin-top: 5px;
        }

        .quantidade {
            width: 60px;
            padding: 6px;
            text-align: center;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .btn {
            background: #47008D;
            color: #fff;
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn:hover {
            background: #5c0ac2;
        }

        .btn-remover {
            background: #888;
        }

        .btn-remover:hover {
            background: #666;
        }

        .total {
            text-align: right;
            font-size: 1.4em;
            font-weight: 600;
            color: #47008D;
            margin-bottom: 20px;
        }

        .acoes {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        footer {
            background: #fff;
            text-align: center;
            padding: 15px;
            margin-top: 40px;
            color: #888;
            font-size: 0.9em;
            box-shadow: 0 -2px 6px rgba(0,0,0,0.05);
        }

        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            th { display: none; }

            td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px;
                border-bottom: 1px solid #eee;
            }

            td img { width: 60px; height: 60px; }

            .total {
                text-align: center;
            }

            .acoes {
                flex-direction: column;
                align-items: center;
            }

            nav ul {
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <nav>
        <div class="logo">🛍️ Okto Store</div>
        <ul>
            <li><a href="index.php">Início</a></li>
            <li><a href="meus_pedidos.php">Meus Pedidos</a></li>
            <li><a href="sobre.php">Sobre</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>

    <div class="container">
        <?php if (count($_SESSION['carrinho']) > 0): ?>
            <form method="post">
                <table>
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Preço</th>
                            <th>Quantidade</th>
                            <th>Subtotal</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['carrinho'] as $item): ?>
                            <tr>
                                <td>
                                    <img src="<?= $item['imagem'] ? 'uploads/'.$item['imagem'] : 'https://via.placeholder.com/70x70?text=Sem+Imagem' ?>" alt="<?= htmlspecialchars($item['nome']) ?>">
                                    <div><?= htmlspecialchars($item['nome']) ?></div>
                                </td>
                                <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                                <td>
                                    <input type="number" name="quantidade[<?= $item['id'] ?>]" value="<?= $item['quantidade'] ?>" class="quantidade" min="1">
                                </td>
                                <td>R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></td>
                                <td>
                                    <a href="?remove=<?= $item['id'] ?>" class="btn btn-remover">Remover</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="total">Total: R$ <?= number_format($total, 2, ',', '.') ?></div>

                <div class="acoes">
                    <a href="index.php" class="btn">🛒 Continuar Comprando</a>
                    <button type="submit" name="atualizar" class="btn">🔄 Atualizar</button>
                    <a href="checkout.php" class="btn">💳 Finalizar Pedido</a>
                </div>
            </form>
        <?php else: ?>
            <p style="text-align:center; font-size:1.1em;">Seu carrinho está vazio 😢</p>
            <div style="text-align:center; margin-top:20px;">
                <a href="index.php" class="btn">Voltar à Loja</a>
            </div>
        <?php endif; ?>
    </div>

    <footer>© <?= date('Y') ?> Okto Store - Todos os direitos reservados.</footer>
</body>
</html>
