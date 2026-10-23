<?php
/**
 * Script de Backend Isolado: api/history.php
 * Retorna os QR Codes gerados na sessão atual utilizando PDO.
 */

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/db.php';

try {
    $pdo = getDbConnection();
    $sessionId = session_id();

    $stmt = $pdo->prepare('
        SELECT code_uuid, content_type, content_data, fg_color, bg_color,
               contrast_ratio, size, format, ecc_level, file_path, created_at
        FROM qr_codes
        WHERE session_id = :session_id
        ORDER BY created_at DESC
        LIMIT 50
    ');
    $stmt->execute([':session_id' => $sessionId]);
    $items = $stmt->fetchAll();

    // Formata itens para o frontend
    $results = array_map(function ($row) {
        $filePath = $row['file_path'];
        $exists = file_exists(__DIR__ . '/../' . $filePath);

        return [
            'uuid'           => $row['code_uuid'],
            'content_type'   => $row['content_type'],
            'content_data'   => $row['content_data'],
            'fg_color'       => $row['fg_color'],
            'bg_color'       => $row['bg_color'],
            'contrast_ratio' => (float)$row['contrast_ratio'],
            'size'           => (int)$row['size'],
            'format'         => $row['format'],
            'ecc_level'      => $row['ecc_level'],
            'created_at'     => date('d/m/Y H:i', strtotime($row['created_at'])),
            'file_exists'    => $exists,
            'download_url'   => 'api/download.php?uuid=' . $row['code_uuid'],
            'preview_url'    => $exists ? $filePath : null
        ];
    }, $items);

    echo json_encode([
        'success' => true,
        'count'   => count($results),
        'items'   => $results
    ]);

} catch (PDOException $e) {
    error_log('Erro ao buscar histórico: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Não foi possível carregar o histórico.']);
}
