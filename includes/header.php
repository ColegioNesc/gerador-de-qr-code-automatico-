<?php
/**
 * Header do Sistema
 * Responsividade Mobile-First com Bootstrap 5 e background 100% preto.
 */

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Gerador de QR Code Automático | Grátis e Personalizado</title>
    <meta name="description" content="Crie QR Codes personalizados e 100% gratuitos em segundos! Transforme links, textos, redes sociais e dados em códigos visuais únicos.">

    <!-- CSS Bootstrap 5 e Bootstrap Icons -->
    <link rel="stylesheet" href="assets/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap-icons.min.css">
    
    <!-- CSS Customizado (Tema Dark com Fundo Preto) -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-black text-light min-vh-100 d-flex flex-column">

    <!-- Barra de Navegação Superior -->
    <header class="border-bottom border-secondary border-opacity-25 py-3 sticky-top bg-black bg-opacity-75 backdrop-blur">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="index.php" class="d-flex align-items-center text-white text-decoration-none gap-2">
                <div class="bg-primary bg-gradient p-2 rounded-3 text-white d-flex align-items-center justify-content-center shadow-sm">
                    <i class="bi bi-qr-code fs-4"></i>
                </div>
                <div>
                    <span class="fs-5 fw-bold tracking-tight text-white d-block lh-1">QR Code Maker</span>
                    <small class="text-secondary font-monospace" style="font-size: 0.75rem;">100% Gratuito & Instantâneo</small>
                </div>
            </a>
            
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-dark border border-secondary border-opacity-50 text-secondary d-none d-sm-inline-flex align-items-center gap-1">
                    <i class="bi bi-shield-check text-success"></i> Sem Necessidade de Login
                </span>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnHelpModal" title="Instruções de Uso">
                    <i class="bi bi-question-circle"></i> Ajuda
                </button>
            </div>
        </div>
    </header>
