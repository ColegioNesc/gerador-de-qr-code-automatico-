<?php
/**
 * Script de Backend Isolado: api/clear_history.php
 * Remove o histórico da sessão atual do usuário no banco de dados e na sessão.
 */

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método inválido.']);
    exit;
}

try {
    $pdo = getDbConnection();
    $sessionId = session_id();

    // Seleciona arquivos para deletar do disco
    $stmt = $pdo->prepare('SELECT file_path FROM qr_codes WHERE session_id = :session_id');
    $stmt->execute([':session_id' => $sessionId]);
    $files = $stmt->fetchAll();

    foreach ($files as $file) {
        $realPath = realpath(__DIR__ . '/../' . $file['file_path']);
        if ($realPath && file_exists($realPath)) {
            @unlink($realPath);
        }
    }

    // Deleta registros usando prepared statements
    $deleteStmt = $pdo->prepare('DELETE FROM qr_codes WHERE session_id = :session_id');
    $deleteStmt->execute([':session_id' => $sessionId]);

    $_SESSION['history'] = [];

    echo json_encode([
        'success' => true,
        'message' => 'Histórico limpo com sucesso!'
    ]);

} catch (PDOException $e) {
    error_log('Erro ao limpar histórico: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Erro ao limpar histórico.']);
}
