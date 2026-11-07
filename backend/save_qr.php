<?php
session_start();
require_once '../db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_URL);
    $fgColor = filter_input(INPUT_POST, 'fg_color', FILTER_SANITIZE_STRING) ?: '#000000';
    $bgColor = filter_input(INPUT_POST, 'bg_color', FILTER_SANITIZE_STRING) ?: '#ffffff';

    if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
        echo json_encode(['success' => false, 'message' => 'Por favor, insira uma URL válida.']);
        exit;
    }

    if (strlen($url) > 2953) {
        echo json_encode(['success' => false, 'message' => 'A URL não pode exceder 2953 caracteres.']);
        exit;
    }

    try {
        if ($pdo) {
            $stmt = $pdo->prepare("INSERT INTO qr_codes (url, fg_color, bg_color) VALUES (:url, :fg_color, :bg_color)");
            $stmt->execute([
                ':url' => $url,
                ':fg_color' => $fgColor,
                ':bg_color' => $bgColor
            ]);
        }
        echo json_encode(['success' => true, 'message' => 'QR Code gerado com sucesso!']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao salvar no banco de dados.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de requisição inválido.']);
}
?>
