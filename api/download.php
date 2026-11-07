<?php
/**
 * Script de Backend Isolado: api/download.php
 * Fornece download forçado seguro da imagem gerada (com regeneração sob demanda caso o arquivo não exista em disco).
 */

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$uuid = trim((string)($_GET['uuid'] ?? ''));
$requestedFormat = strtolower(trim((string)($_GET['format'] ?? '')));
$inline = !empty($_GET['inline']);

if (!preg_match('/^[a-fA-F0-9]{32}$/', $uuid)) {
    http_response_code(400);
    die('Identificador de QR Code inválido.');
}

try {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare('
        SELECT code_uuid, content_data, fg_color, bg_color, size, format, ecc_level, file_path
        FROM qr_codes
        WHERE code_uuid = :uuid
        LIMIT 1
    ');
    $stmt->execute([':uuid' => $uuid]);
    $item = $stmt->fetch();

    if (!$item) {
        http_response_code(404);
        die('QR Code não encontrado.');
    }

    $storedFormat = $item['format'] === 'svg' ? 'svg' : 'png';
    $format = in_array($requestedFormat, ['png', 'svg'], true) ? $requestedFormat : $storedFormat;

    $generatedDir = __DIR__ . '/../generated';
    if (!is_dir($generatedDir)) {
        @mkdir($generatedDir, 0755, true);
    }

    // Renderiza sempre com as cores e configurações exatas salvas no banco
    $outputData = renderQrCodeOutput(
        (string)$item['content_data'],
        (string)($item['fg_color'] ?? '#000000'),
        (string)($item['bg_color'] ?? '#FFFFFF'),
        (int)($item['size'] ?? 350),
        $format,
        (string)($item['ecc_level'] ?? 'M'),
        false
    );

    if (is_dir($generatedDir) && is_writable($generatedDir)) {
        $targetFile = $generatedDir . '/' . sprintf('%s.%s', $uuid, $format);
        @file_put_contents($targetFile, $outputData);
    }

    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    $mime = $format === 'svg' ? 'image/svg+xml' : 'image/png';
    $filename = sprintf('qrcode-%s.%s', substr($uuid, 0, 8), $format);
    $disposition = $inline ? 'inline' : 'attachment';

    header('Content-Description: File Transfer');
    header('Content-Type: ' . $mime);
    header('Content-Disposition: ' . $disposition . '; filename="' . $filename . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . strlen($outputData));

    echo $outputData;
    exit;

} catch (Throwable $e) {
    error_log('Erro no download do QR Code: ' . $e->getMessage());
    http_response_code(500);
    die('Erro ao processar download.');
}
