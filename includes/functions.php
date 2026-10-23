<?php
/**
 * Funções auxiliares do sistema
 * Cálculos complexos de contraste, validações de segurança e formatação.
 */

declare(strict_types=1);

/**
 * Normaliza e valida código hexadecimal (#RGB ou #RRGGBB)
 */
function sanitizeHexColor(string $hex, string $default = '#000000'): string
{
    $hex = trim($hex);
    if (preg_match('/^#([a-fA-F0-9]{3}|[a-fA-F0-9]{6})$/', $hex)) {
        if (strlen($hex) === 4) {
            return sprintf('#%s%s%s%s%s%s', $hex[1], $hex[1], $hex[2], $hex[2], $hex[3], $hex[3]);
        }
        return strtoupper($hex);
    }
    return $default;
}

/**
 * Converte cor HEX para componentes RGB
 */
function hexToRgb(string $hex): array
{
    $hex = ltrim($hex, '#');
    if (strlen($hex) === 3) {
        $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
        $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
        $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    return [$r, $g, $b];
}

/**
 * Calcula a luminância relativa de uma cor conforme norma WCAG 2.1
 * Lógica matemática: converte sRGB gama para escala linear e pondera pela sensibilidade humana (R=0.2126, G=0.7152, B=0.0722).
 */
function calculateRelativeLuminance(string $hex): float
{
    [$r, $g, $b] = hexToRgb($hex);

    $channels = array_map(function ($val) {
        $c = $val / 255.0;
        return ($c <= 0.04045) ? ($c / 12.92) : pow(($c + 0.055) / 1.055, 2.4);
    }, [$r, $g, $b]);

    return (0.2126 * $channels[0]) + (0.7152 * $channels[1]) + (0.0722 * $channels[2]);
}

/**
 * Calcula a taxa de contraste entre duas cores (WCAG)
 * Retorna razão entre (L1 + 0.05) e (L2 + 0.05) variando de 1.0 a 21.0
 */
function calculateContrastRatio(string $color1, string $color2): float
{
    $l1 = calculateRelativeLuminance($color1);
    $l2 = calculateRelativeLuminance($color2);

    $lighter = max($l1, $l2);
    $darker  = min($l1, $l2);

    return round(($lighter + 0.05) / ($darker + 0.05), 2);
}

/**
 * Validação rigorosa de URL e comprimento
 * Regra CORE: Não pode exceder 2.953 caracteres alfanuméricos e deve ser válida
 */
function validateUrlInput(string $url): array
{
    $url = trim($url);

    if ($url === '') {
        return ['valid' => false, 'error' => 'A URL informada não pode estar vazia.'];
    }

    if (mb_strlen($url, 'UTF-8') > 2953) {
        return ['valid' => false, 'error' => 'A URL excede o limite máximo de 2.953 caracteres.'];
    }

    // Garante que o protocolo http ou https está presente
    if (!preg_match('#^https?://#i', $url)) {
        $url = 'https://' . $url;
    }

    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return ['valid' => false, 'error' => 'Por favor, insira um formato de URL válido (ex: https://meusite.com).'];
    }

    return ['valid' => true, 'url' => $url];
}
