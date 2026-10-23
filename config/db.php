<?php
/**
 * Conexão com o Banco de Dados MySQL utilizando PDO
 * Segue as regras de segurança contra SQL Injection e estruturação modular.
 */

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('DB_HOST', 'localhost');
define('DB_NAME', 'gerador_qrcode');
define('DB_USER', 'qrcode_user');
define('DB_PASS', 'qrcode_pass123');
define('DB_CHARSET', 'utf8mb4');

/**
 * Retorna uma instância de conexão PDO com tratamento de erros
 *
 * @return PDO
 * @throws PDOException
 */
function getDbConnection(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci'
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Log do erro real e mensagem amigável sem expor credenciais
            error_log('Erro na conexão PDO: ' . $e->getMessage());
            throw new PDOException('Não foi possível conectar ao banco de dados no momento.');
        }
    }

    return $pdo;
}
