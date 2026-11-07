<?php
$host = 'localhost';
$dbname = 'qr_code_db';
$user = 'root'; // default XAMPP user
$pass = '';     // default XAMPP password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Configura o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Log do erro e continuação do script para que o sistema funcione mesmo sem DB se desejado
    error_log("Erro de conexão com o banco de dados: " . $e->getMessage());
    $pdo = null;
}
?>
