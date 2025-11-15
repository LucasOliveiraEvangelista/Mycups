<?php
require_once '../includes/db.php';
require_once 'protect.php';

// Excluir produto
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: produtos.php');
    exit;
}

// Listar produtos
$stmt = $pdo->query("SELECT * FROM produtos ORDER BY id DESC");
$produtos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>📦 Produtos - Painel Admin</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f5f5fa;
      margin: 0;
    }

    /* Navbar */
    .navbar {
      background: #47008D;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 30px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .navbar h1 {
      font-size: 20px;
      margin: 0;
    }
    .navbar a {
      color: white;
      text-decoration: none;
      margin-left: 20px;
      transition: 0.2s;
    }
    .navbar a:hover {
      opacity: 0.8;
    }

    /* Container */
    .container {
      max-width: 1200px;
      margin: 40px auto;
      background: #fff;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }

    h2 {
      color: #47008D;
      margin: 0;
    }

    .btn {
      display: inline-block;
      padding: 10px 18px;
      border: none;
      border-radius: 8px;
      color: white;
      font-weight: 500;
      cursor: pointer;
      text-decoration: none;
      transition: 0.3s;
    }
    .btn-primary {
      background: #47008D;
    }
    .btn-primary:hover {
      background: #5c00b5;
    }
    .btn-warning {
      background: #f39c12;
    }
    .btn-warning:hover {
      background: #e08e0b;
    }
    .btn-danger {
      background: #e74c3c;
    }
    .btn-danger:hover {
      background: #c0392b;
    }

    /* Tabela */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 25px;
    }
    th, td {
      padding: 12px 15px;
      text-align: center;
      border-bottom: 1px solid #eee;
    }
    th {
      background: #47008D;
      color: white;
      font-weight: 500;
    }
    tr:hover {
      background: #f9f9ff;
    }
    img {
      border-radius: 6px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    /* Responsividade */
    @media (max-width: 768px) {
      .container {
        width: 95%;
        padding: 20px;
      }
      table, th, td {
        font-size: 14px;
      }
      .header {
        flex-direction: column;
        gap: 15px;
      }
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <div class="navbar">
    <h1>📦 Gerenciar Produtos</h1>
    <div>
      <a href="index.php">🏠 Início</a>
      <a href="pedidos.php">🧾 Pedidos</a>
      <a href="usuarios.php">👥 Usuários</a>
      <a href="../user/logout.php">🚪 Sair</a>
    </div>
  </div>

  <!-- Conteúdo -->
  <div class="container">
    <div class="header">
      <h2>Lista de Produtos</h2>
      <a href="cadastro_produto.php" class="btn btn-primary">➕ Novo Produto</a>
    </div>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Imagem</th>
          <th>Nome</th>
          <th>Preço</th>
          <th>Estoque</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($produtos as $p): ?>
          <tr>
            <td><?= $p['id'] ?></td>
            <td>
              <?php if ($p['imagem']): ?>
                <img src="../uploads/<?= htmlspecialchars($p['imagem']) ?>" alt="" width="60">
              <?php else: ?>
                <span>Sem imagem</span>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($p['nome']) ?></td>
            <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
            <td><?= $p['estoque'] ?></td>
            <td>
              <a href="editar_produto.php?id=<?= $p['id'] ?>" class="btn btn-warning">✏️ Editar</a>
              <a href="?delete=<?= $p['id'] ?>" class="btn btn-danger" onclick="return confirm('Deseja excluir este produto?')">🗑️ Excluir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <a href="index.php" class="btn btn-primary" style="margin-top: 25px;">⬅ Voltar ao painel</a>
  </div>

</body>
</html>
