<?php
require_once '../includes/db.php';
require_once 'protect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $estoque = $_POST['estoque'];
    $imagem = $_FILES['imagem']['name'] ?? null;

    // Upload da imagem (se enviada)
    if ($imagem) {
        $pasta = '../uploads/';
        if (!is_dir($pasta)) mkdir($pasta, 0777, true);
        move_uploaded_file($_FILES['imagem']['tmp_name'], $pasta . $imagem);
    }

    $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, preco, estoque, imagem) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nome, $descricao, $preco, $estoque, $imagem]);

    header('Location: produtos.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>➕ Cadastrar Produto - Painel Admin</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f5f5fa;
      margin: 0;
    }

    /* ===== NAVBAR ===== */
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

    /* ===== CONTEÚDO ===== */
    .container {
      max-width: 700px;
      margin: 60px auto;
      background: #fff;
      border-radius: 12px;
      padding: 30px 40px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    h2 {
      color: #47008D;
      text-align: center;
      margin-bottom: 25px;
    }

    form label {
      display: block;
      margin-top: 15px;
      font-weight: 500;
      color: #333;
    }

    input[type="text"],
    input[type="number"],
    textarea,
    input[type="file"] {
      width: 100%;
      padding: 10px 12px;
      margin-top: 6px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
      transition: 0.2s;
    }

    input:focus,
    textarea:focus {
      border-color: #47008D;
      outline: none;
      box-shadow: 0 0 0 2px rgba(71,0,141,0.1);
    }

    textarea {
      resize: vertical;
      min-height: 100px;
    }

    button {
      display: block;
      width: 100%;
      margin-top: 25px;
      padding: 12px;
      background: #47008D;
      color: white;
      font-size: 16px;
      font-weight: 500;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background: #5e1cb0;
    }

    .voltar {
      display: block;
      text-align: center;
      margin-top: 20px;
      color: #47008D;
      font-weight: 500;
      text-decoration: none;
      transition: 0.2s;
    }

    .voltar:hover {
      text-decoration: underline;
    }

    /* Responsivo */
    @media (max-width: 600px) {
      .container {
        width: 90%;
        padding: 25px;
      }
    }
  </style>
</head>
<body>

  <!-- NAVBAR -->
  <div class="navbar">
    <h1>➕ Cadastrar Produto</h1>
    <div>
      <a href="index.php">🏠 Início</a>
      <a href="produtos.php">📦 Produtos</a>
      <a href="../user/logout.php">🚪 Sair</a>
    </div>
  </div>

  <!-- FORMULÁRIO -->
  <div class="container">
    <h2>Adicionar Novo Produto</h2>

    <form method="POST" enctype="multipart/form-data">
      <label>Nome do Produto:</label>
      <input type="text" name="nome" required>

      <label>Descrição:</label>
      <textarea name="descricao" required></textarea>

      <label>Preço (R$):</label>
      <input type="number" step="0.01" name="preco" required>

      <label>Estoque:</label>
      <input type="number" name="estoque" required>

      <label>Imagem do Produto:</label>
      <input type="file" name="imagem" accept="image/*">

      <button type="submit">💾 Salvar Produto</button>
    </form>

    <a href="produtos.php" class="voltar">⬅ Voltar para produtos</a>
  </div>

</body>
</html>
