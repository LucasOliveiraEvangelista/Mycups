<?php
require_once '../includes/db.php';
require_once 'protect.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: produtos.php');
    exit;
}

// Buscar produto
$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
$stmt->execute([$id]);
$produto = $stmt->fetch();

if (!$produto) {
    die("Produto não encontrado!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $estoque = $_POST['estoque'];
    $imagem = $produto['imagem'];

    if (!empty($_FILES['imagem']['name'])) {
        $imagem = $_FILES['imagem']['name'];
        move_uploaded_file($_FILES['imagem']['tmp_name'], '../uploads/' . $imagem);
    }

    $stmt = $pdo->prepare("UPDATE produtos SET nome=?, descricao=?, preco=?, estoque=?, imagem=? WHERE id=?");
    $stmt->execute([$nome, $descricao, $preco, $estoque, $imagem, $id]);

    header('Location: produtos.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Editar Produto</title>
  <link rel="stylesheet" href="../assets/admin.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      padding: 30px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    h1 {
      color: #47008D;
      text-align: center;
      margin-bottom: 25px;
    }

    form {
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      max-width: 500px;
      width: 100%;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    label {
      font-weight: bold;
      display: block;
      margin-top: 15px;
      color: #333;
    }

    input, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }

    textarea {
      resize: vertical;
      min-height: 80px;
    }

    img {
      margin-top: 10px;
      border-radius: 6px;
      border: 1px solid #ddd;
    }

    button {
      margin-top: 20px;
      padding: 12px 20px;
      background: #47008D;
      color: #fff;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      width: 100%;
      font-weight: bold;
      font-size: 15px;
      transition: 0.2s;
    }

    button:hover {
      opacity: 0.9;
    }

    .btn-back {
      display: inline-block;
      margin-top: 25px;
      text-decoration: none;
      color: #fff;
      background: #777;
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: bold;
      transition: 0.2s;
    }

    .btn-back:hover {
      opacity: 0.9;
    }

    @media (max-width: 600px) {
      form {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

  <h1>✏️ Editar Produto</h1>

  <form method="POST" enctype="multipart/form-data">
    <label>Nome:</label>
    <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>

    <label>Descrição:</label>
    <textarea name="descricao" required><?= htmlspecialchars($produto['descricao']) ?></textarea>

    <label>Preço:</label>
    <input type="number" step="0.01" name="preco" value="<?= $produto['preco'] ?>" required>

    <label>Estoque:</label>
    <input type="number" name="estoque" value="<?= $produto['estoque'] ?>" required>

    <label>Imagem Atual:</label><br>
    <?php if ($produto['imagem']): ?>
      <img src="../uploads/<?= htmlspecialchars($produto['imagem']) ?>" width="120"><br>
    <?php else: ?>
      <p style="color:#777;">Nenhuma imagem cadastrada</p>
    <?php endif; ?>

    <label>Alterar Imagem:</label>
    <input type="file" name="imagem" accept="image/*">

    <button type="submit">💾 Salvar Alterações</button>
  </form>

  <a href="produtos.php" class="btn-back">⬅ Voltar</a>

</body>
</html>
