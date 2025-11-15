<?php
session_start();
require_once 'includes/db.php';

// Verifica login (ajuste conforme seu fluxo)
$usuario_id = $_SESSION['usuario_id'] ?? null;

// Verifica carrinho
if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    echo "<p style='text-align:center;'>Seu carrinho está vazio.</p>";
    exit;
}

// Calcula total
$total = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $total += $item['preco'] * $item['quantidade'];
}

// PROCESSAMENTO DO POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');

    if ($nome === '' || $email === '') {
        $erro = "Por favor preencha nome e e-mail.";
    } else {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO pedidos (usuario_id, total, criado_em, status) VALUES (?, ?, NOW(), 'Pendente')");
            $stmt->execute([$usuario_id ?? 1, $total]);
            $pedido_id = $pdo->lastInsertId();

            $stmtItem = $pdo->prepare("INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, subtotal) VALUES (?, ?, ?, ?)");
            foreach ($_SESSION['carrinho'] as $item) {
                $subtotal = $item['preco'] * $item['quantidade'];
                $stmtItem->execute([$pedido_id, $item['id'], $item['quantidade'], $subtotal]);
            }

            $pdo->commit();

            $mensagem = "🛍️ *Novo pedido MyCups!*\n\n";
            $mensagem .= "📦 Pedido: #$pedido_id\n";
            $mensagem .= "👤 Cliente: $nome\n📧 E-mail: $email\n📱 Telefone: " . ($telefone ?: 'Não informado') . "\n\n";
            $mensagem .= "🧾 Itens:\n";
            foreach ($_SESSION['carrinho'] as $item) {
                $mensagem .= "- {$item['nome']} x{$item['quantidade']} = R$ " . number_format($item['preco'] * $item['quantidade'], 2, ',', '.') . "\n";
            }
            $mensagem .= "\n💰 Total: R$ " . number_format($total, 2, ',', '.') . "\n";
            $mensagem .= "\n📍 Status: Pendente\n";

            unset($_SESSION['carrinho']);

            $whatsappPhone = '5511968813442';
            $waUrl = "https://wa.me/{$whatsappPhone}?text=" . rawurlencode($mensagem);

            header("Location: $waUrl");
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            $erro = "Erro ao finalizar pedido: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Checkout - MyCups</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif}
body{background:#faf8fc}

/* ===== NAVBAR ===== */
nav{
    background:#47008D;
    color:#fff;
    padding:15px 8%;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
}
nav .logo{
    font-weight:700;
    font-size:1.3em;
}
nav ul{
    display:flex;
    list-style:none;
    gap:25px;
}
nav ul li a{
    color:#fff;
    text-decoration:none;
    font-weight:500;
    transition:opacity .3s;
}
nav ul li a:hover{opacity:.8}
@media(max-width:700px){
    nav ul{flex-direction:column;gap:10px;margin-top:10px}
}

/* ===== CONTEÚDO ===== */
.container{
    max-width:900px;
    margin:40px auto;
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 6px 20px rgba(0,0,0,0.08);
}
h1{text-align:center;color:#47008D;margin-bottom:20px}
table{width:100%;border-collapse:collapse;margin:12px 0}
th,td{padding:10px;text-align:left}
th{background:#47008D;color:#fff}
tr:nth-child(even){background:#f9f9f9}
.total{text-align:right;color:#47008D;font-weight:700;margin-top:10px}
input{width:100%;padding:12px;border:1px solid #ddd;border-radius:6px;margin-top:8px}
.btn{
    background:#47008D;
    color:#fff;
    padding:14px;
    border:none;
    border-radius:8px;
    width:100%;
    margin-top:12px;
    font-size:1.1em;
    cursor:pointer;
    transition:.3s;
}
.btn:hover{background:#5e1cb0}
.alert{background:#ffecec;color:#c0392b;padding:10px;border-radius:6px;margin-bottom:12px}
</style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav>
    <div class="logo">☕ MyCups</div>
    <ul>
        <li><a href="index.php">🏠 Início</a></li>
        <li><a href="meus_pedidos.php">📦 Meus Pedidos</a></li>
        <li><a href="sobre.php">ℹ️ Sobre</a></li>
        <?php if(isset($_SESSION['usuario_id'])): ?>
            <li><a href="logout.php">🚪 Sair</a></li>
        <?php else: ?>
            <li><a href="login.php">🔑 Login</a></li>
        <?php endif; ?>
    </ul>
</nav>

<!-- ===== CONTEÚDO CHECKOUT ===== -->
<div class="container">
    <h1>Finalizar Pedido</h1>

    <?php if (!empty($erro)): ?>
        <div class="alert"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <table>
        <tr><th>Produto</th><th>Qtd</th><th>Preço</th><th>Subtotal</th></tr>
        <?php foreach ($_SESSION['carrinho'] ?? [] as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['nome']) ?></td>
                <td><?= (int)$item['quantidade'] ?></td>
                <td>R$ <?= number_format($item['preco'],2,',','.') ?></td>
                <td>R$ <?= number_format($item['preco'] * $item['quantidade'],2,',','.') ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div class="total">Total: R$ <?= number_format($total,2,',','.') ?></div>

    <form method="POST">
        <input type="text" name="nome" placeholder="Seu nome completo" required>
        <input type="email" name="email" placeholder="Seu e-mail" required>
        <input type="text" name="telefone" placeholder="Telefone (opcional)">
        <button class="btn" type="submit">💳 Finalizar Compra</button>
    </form>
</div>
</body>
</html>
