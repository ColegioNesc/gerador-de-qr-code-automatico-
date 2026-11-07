<?php
/**
 * Script de Backend Isolado: api/history.php
 * Retorna os QR Codes gerados na sessão atual utilizando PDO.
 */

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

try {
    $pdo = getDbConnection();
    $sessionId = session_id();

    // Limpeza automática (Garbage Collection) de registros com mais de 7 dias
    cleanupExpiredQrCodes($pdo, defined('QR_HISTORY_RETENTION_DAYS') ? QR_HISTORY_RETENTION_DAYS : 7);

    $stmt = $pdo->prepare('
        SELECT code_uuid, content_type, content_data, fg_color, bg_color,
               contrast_ratio, size, format, ecc_level, file_path, created_at
        FROM qr_codes
        WHERE session_id = :session_id
          AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        ORDER BY created_at DESC
        LIMIT 50
    ');
    $stmt->execute([':session_id' => $sessionId]);
    $items = $stmt->fetchAll();

    // Formata itens para o frontend
    $results = array_map(function ($row) {
        $format = $row['format'] === 'svg' ? 'svg' : 'png';
        $dataUri = renderQrCodeOutput(
            (string)$row['content_data'],
            (string)($row['fg_color'] ?? '#000000'),
            (string)($row['bg_color'] ?? '#FFFFFF'),
            (int)($row['size'] ?? 350),
            $format,
            (string)($row['ecc_level'] ?? 'M'),
            true
        );

        return [
            'uuid'           => $row['code_uuid'],
            'content_type'   => $row['content_type'],
            'content_data'   => $row['content_data'],
            'fg_color'       => $row['fg_color'],
            'bg_color'       => $row['bg_color'],
            'contrast_ratio' => (float)$row['contrast_ratio'],
            'size'           => (int)$row['size'],
            'format'         => $format,
            'ecc_level'      => $row['ecc_level'],
            'created_at'     => date('d/m/Y H:i', strtotime($row['created_at'])),
            'file_exists'    => true,
            'download_url'   => 'api/download.php?uuid=' . $row['code_uuid'] . '&format=' . $format,
            'preview_url'    => $dataUri,
            'qr_data_uri'    => $dataUri
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
