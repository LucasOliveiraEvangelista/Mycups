<?php
require_once 'protect.php';
require_once '../includes/db.php';

// Excluir usuário (cuidado: pode quebrar vínculos com pedidos)
if (isset($_GET['del'])) {
    $id = $_GET['del'];
    $pdo->prepare("DELETE FROM usuarios WHERE id = ?")->execute([$id]);
}

// Buscar todos os usuários
$stmt = $pdo->query("SELECT id, nome, email, tipo, criado_em FROM usuarios ORDER BY id DESC");
$usuarios = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>👥 Usuários Cadastrados - Painel Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f4f9;
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

        /* Container principal */
        .container {
            max-width: 1100px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        h2 {
            color: #47008D;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #777;
            font-size: 14px;
            margin-bottom: 20px;
        }

        /* Tabela */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            overflow-x: auto;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        th {
            background: #47008D;
            color: white;
            font-weight: 500;
        }
        tr:hover {
            background: #f9f9ff;
        }

        /* Botão excluir */
        .btn-del {
            background: #ff4d4d;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 14px;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-del:hover {
            background: #e60000;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .container {
                width: 95%;
                padding: 20px;
            }
            th, td {
                font-size: 14px;
                padding: 8px;
            }
            .navbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h1>👥 Usuários Cadastrados</h1>
        <div>
            <a href="index.php">🏠 Início</a>
            <a href="produtos.php">📦 Produtos</a>
            <a href="pedidos.php">🧾 Pedidos</a>
            <a href="../user/logout.php">🚪 Sair</a>
        </div>
    </div>

    <div class="container">
        <h2>Lista de Usuários</h2>
        <p class="subtitle">Veja todos os usuários cadastrados no sistema.</p>

        <div style="overflow-x:auto;">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Tipo</th>
                    <th>Criado em</th>
                    <th>Ações</th>
                </tr>

                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['nome']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= ucfirst($u['tipo']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($u['criado_em'])) ?></td>
                        <td>
                            <a href="?del=<?= $u['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                <button class="btn-del">Excluir</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

</body>
</html>
