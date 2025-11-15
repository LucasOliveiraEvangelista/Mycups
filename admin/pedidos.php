<?php
require_once 'protect.php';
require_once '../includes/db.php';

// Atualizar status do pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id'])) {
    $id = $_POST['pedido_id'];
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
}

// Buscar todos os pedidos + nome do cliente
$sql = "SELECT p.*, u.nome AS cliente 
        FROM pedidos p
        JOIN usuarios u ON u.id = p.usuario_id
        ORDER BY p.id DESC";
$pedidos = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>📦 Pedidos - Painel MyCups</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif}
body{background:#f5f4fa;min-height:100vh;}

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
nav .logo{font-weight:700;font-size:1.3em}
nav ul{display:flex;list-style:none;gap:25px}
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

/* ===== CONTAINER ===== */
.container{
    max-width:1200px;
    margin:40px auto;
    background:#fff;
    border-radius:12px;
    padding:30px;
    box-shadow:0 4px 15px rgba(0,0,0,0.1);
}
h2{
    color:#47008D;
    margin-bottom:20px;
    font-size:1.6em;
    display:flex;
    align-items:center;
    gap:8px;
}
.voltar{
    display:inline-block;
    margin-bottom:20px;
    color:#47008D;
    text-decoration:none;
    font-weight:600;
    transition:color .3s;
}
.voltar:hover{color:#6b1ec4}

/* ===== TABELA ===== */
table{
    width:100%;
    border-collapse:collapse;
    border-radius:8px;
    overflow:hidden;
}
thead{
    background:#47008D;
    color:#fff;
}
th, td{
    padding:12px 10px;
    text-align:center;
}
tbody tr:nth-child(even){background:#f8f8fc}
tbody tr:hover{background:#f1e9fc}
select,button{
    padding:6px 8px;
    border-radius:6px;
    border:1px solid #ccc;
    font-size:0.9em;
}
button{
    background:#47008D;
    color:#fff;
    border:none;
    cursor:pointer;
    transition:background .3s;
}
button:hover{background:#5e1cb0}

/* ===== STATUS CORES ===== */
.status{
    padding:6px 10px;
    border-radius:20px;
    font-weight:600;
    text-transform:capitalize;
}
.status.pendente{background:#fff3cd;color:#856404}
.status.pago{background:#d4edda;color:#155724}
.status.enviado{background:#cce5ff;color:#004085}
.status.cancelado{background:#f8d7da;color:#721c24}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav>
    <div class="logo">⚙️ Painel MyCups</div>
    <ul>
        <li><a href="index.php">🏠 Início</a></li>
        <li><a href="produtos.php">🛍️ Produtos</a></li>
        <li><a href="usuarios.php">👥 Usuários</a></li>
        <li><a href="../user/logout.php">🚪 Sair</a></li>
    </ul>
</nav>

<!-- CONTEÚDO -->
<div class="container">
    <a href="index.php" class="voltar">← Voltar ao Painel</a>
    <h2>📦 Gerenciamento de Pedidos</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Status</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($pedidos)): ?>
            <tr><td colspan="6" style="padding:20px;color:#888;">Nenhum pedido encontrado.</td></tr>
        <?php else: ?>
            <?php foreach ($pedidos as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['cliente']) ?></td>
                    <td>R$ <?= number_format($p['total'], 2, ',', '.') ?></td>
                    <td><span class="status <?= strtolower($p['status']) ?>"><?= ucfirst($p['status']) ?></span></td>
                    <td><?= date('d/m/Y H:i', strtotime($p['criado_em'])) ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="pedido_id" value="<?= $p['id'] ?>">
                            <select name="status">
                                <option value="pendente" <?= $p['status']=='pendente'?'selected':'' ?>>Pendente</option>
                                <option value="pago" <?= $p['status']=='pago'?'selected':'' ?>>Pago</option>
                                <option value="enviado" <?= $p['status']=='enviado'?'selected':'' ?>>Enviado</option>
                                <option value="cancelado" <?= $p['status']=='cancelado'?'selected':'' ?>>Cancelado</option>
                            </select>
                            <button type="submit">Atualizar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
