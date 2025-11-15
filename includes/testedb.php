<?php
require_once 'includes/db.php';

try {
    $stmt = $pdo->query("SELECT NOW() AS data_atual");
    $row = $stmt->fetch();
    echo "✅ Conexão bem-sucedida! Data do servidor: " . $row['data_atual'];
} catch (Exception $e) {
    echo "❌ Erro ao testar conexão: " . $e->getMessage();
}
?>