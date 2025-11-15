<?php
// Configurações de acesso ao banco
$host = 'localhost';
$dbname = 'ecommerce';
$username = 'root';
$password = '';

// Configurações de PDO
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Exibe erros detalhados
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Retorna resultados como arrays associativos
    PDO::ATTR_EMULATE_PREPARES => false, // Usa prepared statements reais
];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, $options);
 //   echo"bem sucedido";
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>