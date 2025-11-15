<?php
require_once 'protect.php';
require_once '../includes/db.php';

// ======= CONSULTAS DE DADOS =======
$pedidosPendentes = $pdo->query("SELECT COUNT(*) FROM pedidos WHERE status = 'Pendente'")->fetchColumn();
$totalUsuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$faturamentoTotal = $pdo->query("SELECT COALESCE(SUM(total),0) FROM pedidos WHERE status != 'Cancelado'")->fetchColumn();

// Filtro de datas
$dataInicio = $_GET['inicio'] ?? date('Y-m-01');
$dataFim = $_GET['fim'] ?? date('Y-m-t');

// Faturamento filtrado
$stmt = $pdo->prepare("SELECT COALESCE(SUM(total),0) FROM pedidos WHERE DATE(criado_em) BETWEEN ? AND ? AND status != 'Cancelado'");
$stmt->execute([$dataInicio, $dataFim]);
$faturamentoFiltrado = $stmt->fetchColumn();

// Gráfico (últimos 30 dias)
$grafico = $pdo->query("
    SELECT DATE(criado_em) as data, SUM(total) as total
    FROM pedidos
    WHERE criado_em >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    GROUP BY DATE(criado_em)
    ORDER BY data ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Painel Administrativo - MyCups</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif}
body{background:#f5f4fa;min-height:100vh}

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

/* ===== CONTEÚDO ===== */
.container{
    max-width:1200px;
    margin:40px auto;
    padding:0 20px;
}
h1{
    color:#47008D;
    font-size:1.8em;
    margin-bottom:25px;
}
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:20px;
}
.card{
    background:#fff;
    border-radius:12px;
    box-shadow:0 4px 15px rgba(0,0,0,0.1);
    padding:20px;
    text-align:center;
    transition:.3s;
    cursor:pointer;
    text-decoration:none;
    color:inherit;
}
.card:hover{
    transform:translateY(-5px);
    box-shadow:0 6px 18px rgba(0,0,0,0.15);
}
.card h2{color:#47008D;font-size:2em;margin:10px 0}
.card span{color:#888}

/* ===== FILTRO ===== */
.filtro{
    background:#fff;
    padding:20px;
    margin:30px 0;
    border-radius:12px;
    box-shadow:0 4px 15px rgba(0,0,0,0.1);
}
.filtro form{
    display:flex;
    flex-wrap:wrap;
    align-items:center;
    gap:10px;
}
input[type="date"]{
    padding:10px;
    border:1px solid #ddd;
    border-radius:6px;
}
button{
    background:#47008D;
    color:#fff;
    border:none;
    padding:10px 20px;
    border-radius:8px;
    cursor:pointer;
    transition:.3s;
}
button:hover{background:#5e1cb0}

/* ===== GRÁFICO ===== */
.grafico{
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 15px rgba(0,0,0,0.1);
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav>
    <div class="logo">⚙️ Painel MyCups</div>
    <ul>
        <li><a href="produtos.php">🛍️ Produtos</a></li>
        <li><a href="usuarios.php">👥 Usuários</a></li>
        <li><a href="pedidos.php">📦 Pedidos</a></li>
        <li><a href="../user/logout.php">🚪 Sair</a></li>
    </ul>
</nav>

<!-- CONTEÚDO -->
<div class="container">
    <h1>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?> 👋</h1>

    <div class="cards">
        <a href="pedidos.php" class="card">
            <span>Pedidos Pendentes</span>
            <h2><?= $pedidosPendentes ?></h2>
        </a>
        <a href="usuarios.php" class="card">
            <span>Usuários Cadastrados</span>
            <h2><?= $totalUsuarios ?></h2>
        </a>
        <div class="card">
            <span>Faturamento Total</span>
            <h2>R$ <?= number_format($faturamentoTotal,2,',','.') ?></h2>
        </div>
    </div>

    <div class="filtro">
        <form method="GET">
            <label>Filtrar por data:</label>
            <input type="date" name="inicio" value="<?= htmlspecialchars($dataInicio) ?>">
            <input type="date" name="fim" value="<?= htmlspecialchars($dataFim) ?>">
            <button type="submit">Filtrar</button>
        </form>
        <p style="margin-top:10px;color:#47008D;font-weight:600">
            💰 Faturamento no período: R$ <?= number_format($faturamentoFiltrado,2,',','.') ?>
        </p>
    </div>

    <div class="grafico">
        <h3 style="color:#47008D;margin-bottom:15px;">📊 Faturamento (últimos 30 dias)</h3>
        <canvas id="graficoVendas"></canvas>
    </div>
</div>

<script>
// ===== GRÁFICO CHART.JS =====
const ctx = document.getElementById('graficoVendas').getContext('2d');
const grafico = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_column($grafico, 'data')) ?>,
        datasets: [{
            label: 'Faturamento Diário (R$)',
            data: <?= json_encode(array_column($grafico, 'total')) ?>,
            borderColor: '#47008D',
            backgroundColor: 'rgba(71, 0, 141, 0.1)',
            borderWidth: 2,
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

</body>
</html>
