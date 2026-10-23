<?php
/**
 * Script de Backend Isolado: api/generate.php
 * Validação de dados, verificação de contraste, geração do QR Code e persistência via PDO.
 */

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Data\QRMatrix;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método não permitido. Utilize POST.']);
    exit;
}

try {
    // Leitura e sanitização dos parâmetros
    $rawInput = file_get_contents('php://input');
    $payload = json_decode($rawInput, true);

    if (!is_array($payload)) {
        $payload = $_POST;
    }

    $contentType = trim((string)($payload['content_type'] ?? 'url'));
    $fgColor     = sanitizeHexColor((string)($payload['fg_color'] ?? '#000000'), '#000000');
    $bgColor     = sanitizeHexColor((string)($payload['bg_color'] ?? '#ffffff'), '#ffffff');
    $size        = max(150, min(1000, (int)($payload['size'] ?? 350)));
    $format      = strtolower((string)($payload['format'] ?? 'png'));
    $ecc         = strtoupper((string)($payload['ecc'] ?? 'M'));

    if (!in_array($format, ['png', 'svg'], true)) {
        $format = 'png';
    }

    $eccMap = [
        'L' => QRCode::ECC_L,
        'M' => QRCode::ECC_M,
        'Q' => QRCode::ECC_Q,
        'H' => QRCode::ECC_H,
    ];
    $eccLevel = $eccMap[$ecc] ?? QRCode::ECC_M;

    // Regra de Negócio CORE: Cálculo do contraste entre pontos escuros e o fundo
    $contrastRatio = calculateContrastRatio($fgColor, $bgColor);
    if ($contrastRatio < 3.00) {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'error'   => sprintf(
                'Contraste insuficiente (taxa calculada: %.2f:1). Para garantir que o QR Code seja lido corretamente pelas câmeras, escolha cores com maior contraste (mínimo de 3.0:1).',
                $contrastRatio
            ),
            'contrast_ratio' => $contrastRatio
        ]);
        exit;
    }

    // Processamento e validação conforme a aba/tipo
    $finalContent = '';

    switch ($contentType) {
        case 'url':
            $rawUrl = (string)($payload['url'] ?? '');
            $urlCheck = validateUrlInput($rawUrl);
            if (!$urlCheck['valid']) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => $urlCheck['error']]);
                exit;
            }
            $finalContent = $urlCheck['url'];
            break;

        case 'text':
            $text = trim((string)($payload['text'] ?? ''));
            if ($text === '') {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'O texto a ser codificado não pode estar vazio.']);
                exit;
            }
            if (mb_strlen($text, 'UTF-8') > 2953) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'O texto excede o limite máximo de 2.953 caracteres.']);
                exit;
            }
            $finalContent = $text;
            break;

        case 'whatsapp':
            $phone = preg_replace('/\D/', '', (string)($payload['phone'] ?? ''));
            $msg   = trim((string)($payload['message'] ?? ''));

            if (strlen($phone) < 8) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Informe um número de WhatsApp válido com código de área/país.']);
                exit;
            }

            // Gera URL oficial de redirecionamento WhatsApp (atende à regra de redirecionar para a URL)
            $finalContent = 'https://wa.me/' . $phone . ($msg !== '' ? '?text=' . rawurlencode($msg) : '');
            if (mb_strlen($finalContent, 'UTF-8') > 2953) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'A mensagem excede o tamanho permitido.']);
                exit;
            }
            break;

        case 'wifi':
            $ssid   = trim((string)($payload['ssid'] ?? ''));
            $pass   = (string)($payload['password'] ?? '');
            $auth   = (string)($payload['auth'] ?? 'WPA');
            $hidden = !empty($payload['hidden']) ? 'true' : 'false';

            if ($ssid === '') {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Informe o nome da rede Wi-Fi (SSID).']);
                exit;
            }

            // Formato padrão MeCard para redes Wi-Fi
            $finalContent = sprintf('WIFI:S:%s;T:%s;P:%s;H:%s;;', $ssid, $auth, $pass, $hidden);
            break;

        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Tipo de conteúdo inválido.']);
            exit;
    }

    // Configuração dos módulos para colorização precisa
    $moduleColors = [
        QRMatrix::M_DARKMODULE     => $fgColor,
        QRMatrix::M_DATA_DARK      => $fgColor,
        QRMatrix::M_FINDER_DARK    => $fgColor,
        QRMatrix::M_ALIGNMENT_DARK => $fgColor,
        QRMatrix::M_TIMING_DARK    => $fgColor,
        QRMatrix::M_FORMAT_DARK    => $fgColor,
        QRMatrix::M_VERSION_DARK   => $fgColor,
        QRMatrix::M_DATA           => $bgColor,
        QRMatrix::M_FINDER         => $bgColor,
        QRMatrix::M_ALIGNMENT      => $bgColor,
        QRMatrix::M_TIMING         => $bgColor,
        QRMatrix::M_FORMAT         => $bgColor,
        QRMatrix::M_VERSION        => $bgColor,
        QRMatrix::M_QUIETZONE      => $bgColor,
        QRMatrix::M_SEPARATOR      => $bgColor,
    ];

    $scale = max(3, (int)round($size / 35));

    $optionsData = [
        'version'         => QRCode::VERSION_AUTO,
        'eccLevel'        => $eccLevel,
        'bgColor'         => $bgColor,
        'moduleValues'    => $moduleColors,
        'scale'           => $scale,
        'imageBase64'     => true,
    ];

    if ($format === 'svg') {
        $optionsData['outputType'] = QRCode::OUTPUT_MARKUP_SVG;
        $optionsData['svgUseFillColor'] = true;
    } else {
        $optionsData['outputType'] = QRCode::OUTPUT_IMAGE_PNG;
    }

    $options = new QROptions($optionsData);
    $qrcodeInstance = new QRCode($options);
    $renderedDataUri = $qrcodeInstance->render($finalContent);

    // Identificador único e persistência do arquivo em disco
    $uuid = bin2hex(random_bytes(16));
    $generatedDir = __DIR__ . '/../generated';
    $fileName = sprintf('%s.%s', $uuid, $format);
    $filePath = $generatedDir . '/' . $fileName;

    if ($format === 'svg') {
        // Gera SVG puro para download
        $svgRawOptions = new QROptions(array_merge($optionsData, ['imageBase64' => false]));
        $svgRaw = (new QRCode($svgRawOptions))->render($finalContent);
        file_put_contents($filePath, $svgRaw);
    } else {
        // Decodifica Base64 PNG e salva arquivo binário
        $pngBinary = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $renderedDataUri));
        file_put_contents($filePath, $pngBinary);
    }

    // Persistência segura no MySQL via PDO
    $pdo = getDbConnection();
    $sessionId = session_id();

    $sql = 'INSERT INTO qr_codes (
                code_uuid, session_id, content_type, content_data, fg_color, bg_color,
                contrast_ratio, size, format, ecc_level, file_path
            ) VALUES (
                :code_uuid, :session_id, :content_type, :content_data, :fg_color, :bg_color,
                :contrast_ratio, :size, :format, :ecc_level, :file_path
            )';

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':code_uuid'      => $uuid,
        ':session_id'     => $sessionId,
        ':content_type'   => $contentType,
        ':content_data'   => $finalContent,
        ':fg_color'       => $fgColor,
        ':bg_color'       => $bgColor,
        ':contrast_ratio' => $contrastRatio,
        ':size'           => $size,
        ':format'         => $format,
        ':ecc_level'      => $ecc,
        ':file_path'      => 'generated/' . $fileName,
    ]);

    // Salva no histórico da sessão nativa do PHP
    if (!isset($_SESSION['history'])) {
        $_SESSION['history'] = [];
    }
    array_unshift($_SESSION['history'], [
        'uuid'           => $uuid,
        'type'           => $contentType,
        'content'        => $finalContent,
        'format'         => $format,
        'fg_color'       => $fgColor,
        'bg_color'       => $bgColor,
        'contrast_ratio' => $contrastRatio,
        'created_at'     => date('Y-m-d H:i:s')
    ]);

    echo json_encode([
        'success'        => true,
        'uuid'           => $uuid,
        'qr_data_uri'    => $renderedDataUri,
        'download_url'   => 'api/download.php?uuid=' . $uuid,
        'contrast_ratio' => $contrastRatio,
        'content'        => $finalContent,
        'format'         => $format,
        'message'        => 'QR Code gerado com sucesso!'
    ]);

} catch (Throwable $e) {
    error_log('Erro na geração do QR Code: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Ocorreu um erro interno ao gerar o QR Code. Tente novamente mais tarde.'
    ]);
}
