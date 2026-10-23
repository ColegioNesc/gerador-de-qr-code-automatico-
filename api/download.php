<?php
/**
 * Script de Backend Isolado: api/download.php
 * Fornece download forçado seguro da imagem gerada.
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

$uuid = trim((string)($_GET['uuid'] ?? ''));

if (!preg_match('/^[a-fA-F0-9]{32}$/', $uuid)) {
    http_response_code(400);
    die('Identificador de QR Code inválido.');
}

try {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare('SELECT code_uuid, format, file_path FROM qr_codes WHERE code_uuid = :uuid LIMIT 1');
    $stmt->execute([':uuid' => $uuid]);
    $item = $stmt->fetch();

    if (!$item) {
        http_response_code(404);
        die('QR Code não encontrado.');
    }

    $realPath = realpath(__DIR__ . '/../' . $item['file_path']);
    $allowedDir = realpath(__DIR__ . '/../generated');

    // Prevenção de Path Traversal
    if (!$realPath || !str_starts_with($realPath, $allowedDir) || !file_exists($realPath)) {
        http_response_code(404);
        die('Arquivo do QR Code não encontrado no servidor.');
    }

    $format = $item['format'] === 'svg' ? 'svg' : 'png';
    $mime = $format === 'svg' ? 'image/svg+xml' : 'image/png';
    $filename = sprintf('qrcode-%s.%s', substr($uuid, 0, 8), $format);

    header('Content-Description: File Transfer');
    header('Content-Type: ' . $mime);
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($realPath));

    readfile($realPath);
    exit;

} catch (PDOException $e) {
    error_log('Erro no download do QR Code: ' . $e->getMessage());
    http_response_code(500);
    die('Erro ao processar download.');
}
