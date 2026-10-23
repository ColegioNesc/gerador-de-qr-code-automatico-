<?php
/**
 * View Principal: index.php
 * Aplicação Web de Geração de QR Code Automático e Personalizado
 * Em conformidade com todos os requisitos em requisitos.md
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/toast.php';
?>

<main class="flex-grow-1 py-4 py-lg-5">
    <div class="container">

        <!-- Hero Informativo Mobile-First -->
        <div class="row justify-content-center text-center mb-4 mb-lg-5">
            <div class="col-12 col-xl-10">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-dark border border-secondary border-opacity-50 text-secondary mb-3 small">
                    <span class="badge bg-primary rounded-pill">Novo</span>
                    <span>Cores personalizadas com validação de contraste WCAG</span>
                </div>
                <h1 class="display-6 fw-extrabold text-white tracking-tight mb-2">
                    Crie seu QR Code Personalizado e Gratuito
                </h1>
                <p class="text-secondary fs-6 lead mb-0 mx-auto" style="max-width: 680px;">
                    Transforme links, textos, números de WhatsApp e redes Wi-Fi em QR Codes estilizados em segundos, sem login e sem custo.
                </p>
            </div>
        </div>

        <form id="formGenerate" novalidate>
            <input type="hidden" name="tab" id="currentTabInput" value="url">

            <div class="row g-4 align-items-start">
                
                <!-- Coluna 1: Formulário e Abas (Mobile First) -->
                <div class="col-12 col-lg-7">
                    
                    <!-- Card de Conteúdo e Abas -->
                    <div class="card-dark p-3 p-sm-4 mb-4 shadow-sm">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h2 class="fs-5 fw-bold text-white mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-sliders text-primary"></i> 1. O que seu QR Code conterá?
                            </h2>
                            <span class="badge bg-dark border border-secondary border-opacity-50 text-secondary">
                                Escolha uma aba
                            </span>
                        </div>

                        <!-- Abas de Navegação -->
                        <ul class="nav nav-tabs nav-tabs-custom mb-3" id="qrTypeTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="tab-url-btn" data-bs-toggle="tab" data-bs-target="#tab-url" type="button" role="tab" aria-controls="tab-url" aria-selected="true" data-tab-type="url">
                                    <i class="bi bi-link-45deg"></i> Link / URL
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-wa-btn" data-bs-toggle="tab" data-bs-target="#tab-wa" type="button" role="tab" aria-controls="tab-wa" aria-selected="false" data-tab-type="whatsapp">
                                    <i class="bi bi-whatsapp"></i> WhatsApp
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-text-btn" data-bs-toggle="tab" data-bs-target="#tab-text" type="button" role="tab" aria-controls="tab-text" aria-selected="false" data-tab-type="text">
                                    <i class="bi bi-file-text"></i> Texto
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-wifi-btn" data-bs-toggle="tab" data-bs-target="#tab-wifi" type="button" role="tab" aria-controls="tab-wifi" aria-selected="false" data-tab-type="wifi">
                                    <i class="bi bi-wifi"></i> Wi-Fi
                                </button>
                            </li>
                        </ul>

                        <!-- Conteúdo das Abas -->
                        <div class="tab-content" id="qrTypeTabsContent">
                            
                            <!-- Aba 1: URL (Regra CORE) -->
                            <div class="tab-pane fade show active" id="tab-url" role="tabpanel" aria-labelledby="tab-url-btn">
                                <div class="mb-2">
                                    <label for="inputUrl" class="form-label text-light fw-semibold small">
                                        Endereço da Página Web (URL) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text input-group-text-dark">
                                            <i class="bi bi-globe2"></i>
                                        </span>
                                        <input type="url" 
                                               class="form-control form-control-dark" 
                                               id="inputUrl" 
                                               name="url" 
                                               placeholder="https://seusite.com.br/pagina" 
                                               value="https://google.com"
                                               autocomplete="off" 
                                               spellcheck="false"
                                               maxlength="2953"
                                               required>
                                        <button class="btn btn-outline-secondary" type="button" id="btnClearUrl" title="Limpar URL">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-secondary small">
                                        Ao ler o QR Code, a câmera abrirá diretamente este link.
                                    </span>
                                    <span id="inputUrlCounter" class="char-counter text-secondary font-monospace">
                                        0 / 2.953 caracteres
                                    </span>
                                </div>
                            </div>

                            <!-- Aba 2: WhatsApp -->
                            <div class="tab-pane fade" id="tab-wa" role="tabpanel" aria-labelledby="tab-wa-btn">
                                <div class="mb-3">
                                    <label for="inputWaPhone" class="form-label text-light fw-semibold small">
                                        Número de WhatsApp (com DDD) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text input-group-text-dark">
                                            <i class="bi bi-telephone"></i>
                                        </span>
                                        <input type="tel" 
                                               class="form-control form-control-dark" 
                                               id="inputWaPhone" 
                                               name="wa_phone" 
                                               placeholder="5511999998888 (com DDI e DDD)">
                                    </div>
                                    <div class="form-text text-secondary small">Exemplo: 55 para Brasil + DDD + Telefone</div>
                                </div>
                                <div class="mb-2">
                                    <label for="inputWaMsg" class="form-label text-light fw-semibold small">
                                        Mensagem Automática Inicial (Opcional)
                                    </label>
                                    <textarea class="form-control form-control-dark" 
                                              id="inputWaMsg" 
                                              name="wa_message" 
                                              rows="2" 
                                              placeholder="Olá! Vi o QR Code e gostaria de mais informações."></textarea>
                                </div>
                            </div>

                            <!-- Aba 3: Texto Simples -->
                            <div class="tab-pane fade" id="tab-text" role="tabpanel" aria-labelledby="tab-text-btn">
                                <div class="mb-2">
                                    <label for="inputText" class="form-label text-light fw-semibold small">
                                        Mensagem ou Texto Livre <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control form-control-dark" 
                                              id="inputText" 
                                              name="text_content" 
                                              rows="3" 
                                              placeholder="Digite seu texto, cupom promocional, instrução..."
                                              maxlength="2953"></textarea>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <span id="inputTextCounter" class="char-counter text-secondary font-monospace">
                                        0 / 2.953 caracteres
                                    </span>
                                </div>
                            </div>

                            <!-- Aba 4: Wi-Fi -->
                            <div class="tab-pane fade" id="tab-wifi" role="tabpanel" aria-labelledby="tab-wifi-btn">
                                <div class="row g-3">
                                    <div class="col-12 col-sm-6">
                                        <label for="inputWifiSsid" class="form-label text-light fw-semibold small">
                                            Nome da Rede (SSID) <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control form-control-dark" id="inputWifiSsid" name="wifi_ssid" placeholder="MinhaRedeWiFi">
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <label for="selectWifiAuth" class="form-label text-light fw-semibold small">Segurança / Criptografia</label>
                                        <select class="form-select form-select-dark" id="selectWifiAuth" name="wifi_auth">
                                            <option value="WPA" selected>WPA / WPA2 / WPA3</option>
                                            <option value="WEP">WEP</option>
                                            <option value="nopass">Sem Senha (Aberta)</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-8">
                                        <label for="inputWifiPass" class="form-label text-light fw-semibold small">Senha da Rede</label>
                                        <input type="password" class="form-control form-control-dark" id="inputWifiPass" name="wifi_password" placeholder="Senha do Wi-Fi">
                                    </div>
                                    <div class="col-12 col-sm-4 d-flex align-items-end">
                                        <div class="form-check pb-2">
                                            <input class="form-check-input bg-dark border-secondary" type="checkbox" id="checkWifiHidden" name="wifi_hidden">
                                            <label class="form-check-label text-secondary small" for="checkWifiHidden">Rede Oculta</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Card de Personalização Visual & Contraste -->
                    <div class="card-dark p-3 p-sm-4 shadow-sm">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h2 class="fs-5 fw-bold text-white mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-palette text-primary"></i> 2. Cores &amp; Personalização
                            </h2>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnInvertColors" title="Inverter Cores de Pontos e Fundo">
                                <i class="bi bi-arrow-left-right me-1"></i> Inverter Cores
                            </button>
                        </div>

                        <!-- Presets de Combinações de Alto Contraste -->
                        <div class="mb-4">
                            <label class="form-label text-secondary small fw-semibold text-uppercase tracking-wider">
                                Combinações Rápidas Recomendadas:
                            </label>
                            <div class="d-flex gap-2 flex-wrap align-items-center">
                                <button type="button" class="color-swatch-btn active" data-fg="#000000" data-bg="#ffffff" title="Clássico: Pontos Pretos no Fundo Branco" style="background: linear-gradient(135deg, #000000 50%, #ffffff 50%);"></button>
                                <button type="button" class="color-swatch-btn" data-fg="#ffffff" data-bg="#000000" title="Dark Invertido: Pontos Brancos no Fundo Preto" style="background: linear-gradient(135deg, #ffffff 50%, #000000 50%);"></button>
                                <button type="button" class="color-swatch-btn" data-fg="#00f0ff" data-bg="#0a0e17" title="Cyber Cyan: Ciano no Preto Noturno" style="background: linear-gradient(135deg, #00f0ff 50%, #0a0e17 50%);"></button>
                                <button type="button" class="color-swatch-btn" data-fg="#10b981" data-bg="#04140c" title="Matrix Green: Verde Esmeralda no Fundo Escuro" style="background: linear-gradient(135deg, #10b981 50%, #04140c 50%);"></button>
                                <button type="button" class="color-swatch-btn" data-fg="#f59e0b" data-bg="#120c03" title="Gold Elegance: Dourado no Fundo Preto" style="background: linear-gradient(135deg, #f59e0b 50%, #120c03 50%);"></button>
                                <button type="button" class="color-swatch-btn" data-fg="#a855f7" data-bg="#10051a" title="Purple Tech: Púrpura no Fundo Profundo" style="background: linear-gradient(135deg, #a855f7 50%, #10051a 50%);"></button>
                            </div>
                        </div>

                        <!-- Seleção Manual de Cores -->
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-sm-6">
                                <label for="fgColor" class="form-label text-light fw-semibold small">
                                    Cor dos Pontos Escuros (QR) <span class="text-danger">*</span>
                                </label>
                                <div class="color-picker-wrapper">
                                    <input type="color" class="color-picker-input" id="fgColor" name="fg_color" value="#000000">
                                    <input type="text" class="form-control form-control-dark font-monospace text-uppercase" id="fgColorHex" value="#000000" maxlength="7">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="bgColor" class="form-label text-light fw-semibold small">
                                    Cor do Fundo (Background) <span class="text-danger">*</span>
                                </label>
                                <div class="color-picker-wrapper">
                                    <input type="color" class="color-picker-input" id="bgColor" name="bg_color" value="#ffffff">
                                    <input type="text" class="form-control form-control-dark font-monospace text-uppercase" id="bgColorHex" value="#FFFFFF" maxlength="7">
                                </div>
                            </div>
                        </div>

                        <!-- Medidor de Contraste WCAG (Regra de Negócio CORE) -->
                        <div class="contrast-badge-box mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="small fw-bold text-white">Contraste Calculado:</span>
                                    <span id="contrastRatioValue" class="badge bg-dark border border-secondary border-opacity-50 font-monospace fs-6">21.0:1</span>
                                </div>
                                <span id="contrastStatusBadge" class="badge badge-contrast-high">
                                    <i class="bi bi-shield-check me-1"></i> Excelente Contraste
                                </span>
                            </div>

                            <!-- Barra de Progresso Visual de Contraste -->
                            <div class="contrast-meter mb-2">
                                <div id="contrastMeterFill" class="contrast-meter-fill" style="width: 100%; background-color: #10b981;"></div>
                            </div>

                            <div id="contrastAlertBox" class="alert alert-danger py-2 px-3 small mb-0 d-none" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                <strong>Atenção:</strong> A taxa de contraste é menor que 3.0:1. Câmeras e leitores óticos terão dificuldade ou falharão ao ler o código. Por favor, aumente o contraste entre os pontos e o fundo.
                            </div>
                        </div>

                        <!-- Opções Adicionais de Exportação -->
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-sm-4">
                                <label for="sizeSelect" class="form-label text-light small fw-semibold">Dimensões (Resolução)</label>
                                <select class="form-select form-select-dark" id="sizeSelect" name="size">
                                    <option value="250">250 x 250 px (Compacto)</option>
                                    <option value="350" selected>350 x 350 px (Padrão)</option>
                                    <option value="500">500 x 500 px (Alta Nitidez)</option>
                                    <option value="800">800 x 800 px (Ultra HD)</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-4">
                                <label for="formatSelect" class="form-label text-light small fw-semibold">Formato do Arquivo</label>
                                <select class="form-select form-select-dark" id="formatSelect" name="format">
                                    <option value="png" selected>PNG (Imagem Raster)</option>
                                    <option value="svg">SVG (Vetor Gráfico)</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-4">
                                <label for="eccSelect" class="form-label text-light small fw-semibold">Correção de Erros</label>
                                <select class="form-select form-select-dark" id="eccSelect" name="ecc">
                                    <option value="L">Nível L (7% - Código mais simples)</option>
                                    <option value="M" selected>Nível M (15% - Padrão Ideal)</option>
                                    <option value="Q">Nível Q (25% - Alta Resistência)</option>
                                    <option value="H">Nível H (30% - Máxima Durabilidade)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Botão de Ação Principal -->
                        <button type="submit" class="btn btn-primary-gradient w-100 fs-5 d-flex align-items-center justify-content-center gap-2" id="btnSubmit">
                            <i class="bi bi-qr-code-scan"></i> Gerar QR Code em Alta Resolução
                        </button>
                    </div>

                </div>

                <!-- Coluna 2: Pré-Visualização e Ações de Download -->
                <div class="col-12 col-lg-5">
                    <div class="card-dark p-3 p-sm-4 shadow-sm position-sticky" style="top: 5rem;">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h2 class="fs-5 fw-bold text-white mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-eye text-primary"></i> Pré-Visualização
                            </h2>
                            <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25">
                                Tempo Real
                            </span>
                        </div>

                        <!-- Container de Visualização -->
                        <div class="preview-container mb-3" id="previewWrapper">
                            <div id="qrcodeCanvasWrapper"></div>
                            
                            <div id="previewPlaceholder" class="text-center text-secondary py-4 d-none">
                                <i class="bi bi-qr-code text-secondary opacity-50 display-4 d-block mb-2"></i>
                                <span class="small">Digite uma URL válida para visualizar o QR Code aqui.</span>
                            </div>
                        </div>

                        <!-- Ações Rápidas de Download e Teste -->
                        <div id="previewActions" class="d-flex flex-column gap-2">
                            <div class="row g-2">
                                <div class="col-6">
                                    <a href="#" id="btnDownloadPng" class="btn btn-primary w-100 py-2 d-flex align-items-center justify-content-center gap-2" download="qrcode.png">
                                        <i class="bi bi-download"></i> Baixar PNG
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="#" id="btnDownloadSvg" class="btn btn-outline-light w-100 py-2 d-flex align-items-center justify-content-center gap-2" download="qrcode.svg">
                                        <i class="bi bi-filetype-svg"></i> Baixar SVG
                                    </a>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2 mt-1">
                                <button type="button" class="btn btn-outline-secondary w-50 py-2 small d-flex align-items-center justify-content-center gap-1" id="btnCopyUrl">
                                    <i class="bi bi-clipboard"></i> Copiar Link
                                </button>
                                <a href="#" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary w-50 py-2 small d-flex align-items-center justify-content-center gap-1" id="btnTestUrl">
                                    <i class="bi bi-box-arrow-up-right"></i> Testar Destino
                                </a>
                            </div>
                        </div>

                        <div class="border-top border-secondary border-opacity-25 mt-3 pt-3">
                            <div class="d-flex align-items-start gap-2 text-secondary small">
                                <i class="bi bi-info-circle text-primary mt-1"></i>
                                <span>
                                    <strong>Garantia de Leitura:</strong> Ao escanear com a câmera do celular, o usuário será direcionado diretamente ao link codificado.
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </form>

        <!-- Seção de Histórico da Sessão -->
        <section class="mt-5 pt-3">
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div>
                    <h3 class="fs-5 fw-bold text-white mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-clock-history text-primary"></i> Seus QR Codes Recentes
                        <span class="badge bg-secondary rounded-pill" id="historyCountBadge">0</span>
                    </h3>
                    <small class="text-secondary">Itens gerados na sua sessão atual (sem necessidade de login).</small>
                </div>
                <button type="button" class="btn btn-outline-danger btn-sm" id="btnClearHistory" disabled>
                    <i class="bi bi-trash3 me-1"></i> Limpar Histórico
                </button>
            </div>

            <!-- Estado Vazio -->
            <div id="historyEmpty" class="card-dark p-4 text-center">
                <i class="bi bi-inbox text-secondary fs-1 d-block mb-2 opacity-50"></i>
                <p class="text-secondary small mb-0">Nenhum QR Code gerado nesta sessão ainda. Crie seu primeiro código acima!</p>
            </div>

            <!-- Lista de Itens do Histórico -->
            <div id="historyList" class="row g-3"></div>
        </section>

    </div>
</main>

<?php
require_once __DIR__ . '/includes/footer.php';
