/**
 * Script Principal da Aplicação: assets/js/main.js
 * Atende a todas as regras de negócio CORE e regras de UI (Toast para erros, Modal para avisos).
 */

'use strict';

document.addEventListener('DOMContentLoaded', () => {

    // ==========================================
    // 1. UTILITÁRIOS: TOAST E MODAL (Regras CORE)
    // ==========================================

    const toastEl = document.getElementById('appToast');
    const toastBody = document.getElementById('toastMessage');
    const toastIcon = document.getElementById('toastIcon');
    let bsToast = null;

    if (toastEl && window.bootstrap) {
        bsToast = new bootstrap.Toast(toastEl, { delay: 4500 });
    }

    /**
     * Exibe notificação estilo Toast
     * Regra: "Retorne mensagens de erro claras na interface para o usuario no estilo Toast"
     */
    function showToast(message, type = 'danger') {
        if (!toastEl || !bsToast) return;

        const iconClasses = {
            danger:  'bi bi-exclamation-triangle-fill text-danger',
            success: 'bi bi-check-circle-fill text-success',
            warning: 'bi bi-exclamation-circle-fill text-warning',
            info:    'bi bi-info-circle-fill text-info'
        };

        toastIcon.className = (iconClasses[type] || iconClasses.info) + ' fs-5';
        toastBody.textContent = message;
        bsToast.show();
    }

    /**
     * Modal Universal substitui alert() e confirm() nativos
     * Regra: "Trate sempre as mensagens nativas 'ex, caixas de mensagens com OK' sempre em um modal."
     */
    const appModalEl = document.getElementById('appModal');
    const appModalTitle = document.getElementById('appModalTitle');
    const appModalBody = document.getElementById('appModalBody');
    const appModalIcon = document.getElementById('appModalIcon');
    const appModalCancelBtn = document.getElementById('appModalCancelBtn');
    const appModalConfirmBtn = document.getElementById('appModalConfirmBtn');
    let bsModal = null;
    let pendingConfirmCallback = null;

    if (appModalEl && window.bootstrap) {
        bsModal = new bootstrap.Modal(appModalEl);
        
        appModalConfirmBtn.addEventListener('click', () => {
            if (typeof pendingConfirmCallback === 'function') {
                const cb = pendingConfirmCallback;
                pendingConfirmCallback = null;
                bsModal.hide();
                cb();
            } else {
                bsModal.hide();
            }
        });

        appModalEl.addEventListener('hidden.bs.modal', () => {
            pendingConfirmCallback = null;
        });
    }

    function showAppModal({ title = 'Aviso', message, type = 'info', confirmText = null, cancelText = 'Fechar', onConfirm = null }) {
        if (!bsModal) {
            console.warn('Bootstrap Modal não inicializado');
            return;
        }

        appModalTitle.textContent = title;
        appModalBody.innerHTML = message;

        const iconMap = {
            info:    '<i class="bi bi-info-circle-fill text-info"></i>',
            warning: '<i class="bi bi-exclamation-triangle-fill text-warning"></i>',
            danger:  '<i class="bi bi-x-circle-fill text-danger"></i>',
            success: '<i class="bi bi-check-circle-fill text-success"></i>'
        };
        appModalIcon.innerHTML = iconMap[type] || iconMap.info;

        appModalCancelBtn.textContent = cancelText;

        if (confirmText && typeof onConfirm === 'function') {
            pendingConfirmCallback = onConfirm;
            appModalConfirmBtn.textContent = confirmText;
            appModalConfirmBtn.className = (type === 'danger') ? 'btn btn-danger px-4' : 'btn btn-primary px-4';
            appModalConfirmBtn.classList.remove('d-none');
        } else {
            pendingConfirmCallback = null;
            appModalConfirmBtn.classList.add('d-none');
        }

        bsModal.show();
    }

    // Modal de Ajuda
    const btnHelpModal = document.getElementById('btnHelpModal');
    const helpModalEl = document.getElementById('helpModal');
    if (btnHelpModal && helpModalEl && window.bootstrap) {
        const bsHelpModal = new bootstrap.Modal(helpModalEl);
        btnHelpModal.addEventListener('click', () => bsHelpModal.show());
    }

    // ==========================================
    // 2. CÁLCULO E VALIDAÇÃO DE CONTRASTE WCAG
    // ==========================================

    function hexToRgb(hex) {
        hex = hex.replace(/^#/, '').trim();
        if (hex.length === 3) {
            hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
        }
        const num = parseInt(hex, 16);
        return [num >> 16, (num >> 8) & 255, num & 255];
    }

    function calculateRelativeLuminance(hex) {
        const [r, g, b] = hexToRgb(hex);
        const channels = [r, g, b].map(val => {
            const c = val / 255.0;
            return (c <= 0.04045) ? (c / 12.92) : Math.pow((c + 0.055) / 1.055, 2.4);
        });
        return (0.2126 * channels[0]) + (0.7152 * channels[1]) + (0.0722 * channels[2]);
    }

    function calculateContrastRatio(fgHex, bgHex) {
        try {
            const l1 = calculateRelativeLuminance(fgHex);
            const l2 = calculateRelativeLuminance(bgHex);
            const lighter = Math.max(l1, l2);
            const darker = Math.min(l1, l2);
            return Number(((lighter + 0.05) / (darker + 0.05)).toFixed(2));
        } catch {
            return 1.0;
        }
    }

    // ==========================================
    // 3. ELEMENTOS DE CONTROLE DA INTERFACE
    // ==========================================

    const formGenerate = document.getElementById('formGenerate');
    const currentTabInput = document.getElementById('currentTabInput');

    // Inputs das Abas
    const inputUrl = document.getElementById('inputUrl');
    const inputUrlCounter = document.getElementById('inputUrlCounter');
    const btnClearUrl = document.getElementById('btnClearUrl');

    const inputText = document.getElementById('inputText');
    const inputTextCounter = document.getElementById('inputTextCounter');

    const inputWaPhone = document.getElementById('inputWaPhone');
    const inputWaMsg = document.getElementById('inputWaMsg');

    const inputWifiSsid = document.getElementById('inputWifiSsid');
    const inputWifiPass = document.getElementById('inputWifiPass');
    const selectWifiAuth = document.getElementById('selectWifiAuth');
    const checkWifiHidden = document.getElementById('checkWifiHidden');

    // Customizações Visuais
    const fgColorInput = document.getElementById('fgColor');
    const fgColorHex = document.getElementById('fgColorHex');
    const bgColorInput = document.getElementById('bgColor');
    const bgColorHex = document.getElementById('bgColorHex');
    const btnInvertColors = document.getElementById('btnInvertColors');
    const sizeSelect = document.getElementById('sizeSelect');
    const formatSelect = document.getElementById('formatSelect');
    const eccSelect = document.getElementById('eccSelect');

    // Medidor de Contraste
    const contrastRatioValue = document.getElementById('contrastRatioValue');
    const contrastMeterFill = document.getElementById('contrastMeterFill');
    const contrastStatusBadge = document.getElementById('contrastStatusBadge');
    const contrastAlertBox = document.getElementById('contrastAlertBox');

    // Elementos do Preview e Ações
    const previewWrapper = document.getElementById('previewWrapper');
    const qrcodeCanvasWrapper = document.getElementById('qrcodeCanvasWrapper');
    const previewPlaceholder = document.getElementById('previewPlaceholder');
    const previewActions = document.getElementById('previewActions');
    const btnSubmit = document.getElementById('btnSubmit');

    const btnDownloadPng = document.getElementById('btnDownloadPng');
    const btnDownloadSvg = document.getElementById('btnDownloadSvg');
    const btnCopyUrl = document.getElementById('btnCopyUrl');
    const btnTestUrl = document.getElementById('btnTestUrl');

    // Histórico
    const historyList = document.getElementById('historyList');
    const historyEmpty = document.getElementById('historyEmpty');
    const historyCountBadge = document.getElementById('historyCountBadge');
    const btnClearHistory = document.getElementById('btnClearHistory');

    // Estado Atual
    let activeTab = 'url';
    let currentPreviewQRCode = null;
    let lastGeneratedData = null;
    let livePreviewTimeout = null;

    // ==========================================
    // 4. GERENCIAMENTO DE ABAS
    // ==========================================

    const tabButtons = document.querySelectorAll('#qrTypeTabs button[data-bs-toggle="tab"]');
    tabButtons.forEach(btn => {
        btn.addEventListener('shown.bs.tab', (e) => {
            activeTab = e.target.getAttribute('data-tab-type') || 'url';
            currentTabInput.value = activeTab;
            triggerLivePreview();
        });
    });

    // ==========================================
    // 5. CONTADORES DE CARACTERES (Limite 2.953)
    // ==========================================

    const MAX_LENGTH = 2953;

    function updateCounter(input, counterEl) {
        if (!input || !counterEl) return;
        const len = input.value.length;
        counterEl.textContent = `${len.toLocaleString('pt-BR')} / ${MAX_LENGTH.toLocaleString('pt-BR')} caracteres`;

        counterEl.classList.remove('warning', 'danger');
        if (len > MAX_LENGTH) {
            counterEl.classList.add('danger');
        } else if (len > 2500) {
            counterEl.classList.add('warning');
        }
    }

    if (inputUrl && inputUrlCounter) {
        inputUrl.addEventListener('input', () => {
            updateCounter(inputUrl, inputUrlCounter);
            scheduleLivePreview();
        });
        if (btnClearUrl) {
            btnClearUrl.addEventListener('click', () => {
                inputUrl.value = '';
                updateCounter(inputUrl, inputUrlCounter);
                inputUrl.focus();
                scheduleLivePreview();
            });
        }
    }

    if (inputText && inputTextCounter) {
        inputText.addEventListener('input', () => {
            updateCounter(inputText, inputTextCounter);
            scheduleLivePreview();
        });
    }

    if (inputWaPhone) {
        inputWaPhone.addEventListener('input', scheduleLivePreview);
    }
    if (inputWaMsg) {
        inputWaMsg.addEventListener('input', scheduleLivePreview);
    }
    if (inputWifiSsid) {
        inputWifiSsid.addEventListener('input', scheduleLivePreview);
    }
    if (inputWifiPass) {
        inputWifiPass.addEventListener('input', scheduleLivePreview);
    }
    if (selectWifiAuth) {
        selectWifiAuth.addEventListener('change', scheduleLivePreview);
    }
    if (checkWifiHidden) {
        checkWifiHidden.addEventListener('change', scheduleLivePreview);
    }

    // ==========================================
    // 6. SINCRONIZAÇÃO DE CORES E CONTRASTE
    // ==========================================

    function normalizeHex(hex) {
        hex = hex.trim();
        if (!hex.startsWith('#')) hex = '#' + hex;
        return hex.toUpperCase();
    }

    function updateContrastUI() {
        const fg = fgColorInput.value;
        const bg = bgColorInput.value;
        const ratio = calculateContrastRatio(fg, bg);

        contrastRatioValue.textContent = `${ratio.toFixed(1)}:1`;

        // Barra de progresso (escala até 21.0)
        const pct = Math.min(100, Math.max(5, (ratio / 21.0) * 100));
        contrastMeterFill.style.width = `${pct}%`;

        contrastStatusBadge.className = 'badge';
        contrastAlertBox.classList.add('d-none');

        if (ratio >= 7.0) {
            contrastMeterFill.style.backgroundColor = '#10b981';
            contrastStatusBadge.className = 'badge badge-contrast-high';
            contrastStatusBadge.innerHTML = '<i class="bi bi-shield-check me-1"></i> Excelente Contraste (Leitura Rápida)';
        } else if (ratio >= 4.5) {
            contrastMeterFill.style.backgroundColor = '#06b6d4';
            contrastStatusBadge.className = 'badge badge-contrast-high';
            contrastStatusBadge.innerHTML = '<i class="bi bi-check-circle me-1"></i> Bom Contraste (Recomendado)';
        } else if (ratio >= 3.0) {
            contrastMeterFill.style.backgroundColor = '#f59e0b';
            contrastStatusBadge.className = 'badge badge-contrast-med';
            contrastStatusBadge.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i> Contraste Aceitável';
        } else {
            contrastMeterFill.style.backgroundColor = '#ef4444';
            contrastStatusBadge.className = 'badge badge-contrast-low';
            contrastStatusBadge.innerHTML = '<i class="bi bi-x-circle me-1"></i> Contraste Insuficiente (&lt; 3.0:1)';
            contrastAlertBox.classList.remove('d-none');
        }

        return ratio;
    }

    // Sincroniza Color Picker com Input de Texto
    function bindColorSync(picker, textInput) {
        picker.addEventListener('input', () => {
            textInput.value = picker.value.toUpperCase();
            updateContrastUI();
            scheduleLivePreview();
        });

        textInput.addEventListener('input', () => {
            let val = textInput.value.trim();
            if (/^#?([a-fA-F0-9]{3}|[a-fA-F0-9]{6})$/.test(val)) {
                if (!val.startsWith('#')) val = '#' + val;
                if (val.length === 4) {
                    val = '#' + val[1] + val[1] + val[2] + val[2] + val[3] + val[3];
                }
                picker.value = val;
                updateContrastUI();
                scheduleLivePreview();
            }
        });
    }

    bindColorSync(fgColorInput, fgColorHex);
    bindColorSync(bgColorInput, bgColorHex);

    // Inverter Cores
    if (btnInvertColors) {
        btnInvertColors.addEventListener('click', () => {
            const temp = fgColorInput.value;
            fgColorInput.value = bgColorInput.value;
            fgColorHex.value = bgColorInput.value.toUpperCase();
            bgColorInput.value = temp;
            bgColorHex.value = temp.toUpperCase();
            updateContrastUI();
            scheduleLivePreview();
        });
    }

    // Presets de Cores
    const presetButtons = document.querySelectorAll('.color-swatch-btn');
    presetButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            presetButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const fg = btn.getAttribute('data-fg');
            const bg = btn.getAttribute('data-bg');

            fgColorInput.value = fg;
            fgColorHex.value = fg.toUpperCase();
            bgColorInput.value = bg;
            bgColorHex.value = bg.toUpperCase();

            updateContrastUI();
            scheduleLivePreview();
        });
    });

    // ==========================================
    // 7. MONTAGEM E VALIDAÇÃO DOS DADOS DO FORM
    // ==========================================

    function getFormattedContent() {
        switch (activeTab) {
            case 'url': {
                let url = (inputUrl.value || '').trim();
                if (url === '') return { valid: false, error: 'Por favor, insira a URL do seu site.' };
                if (url.length > MAX_LENGTH) return { valid: false, error: 'A URL excede o limite máximo de 2.953 caracteres.' };

                if (!/^https?:\/\//i.test(url)) {
                    url = 'https://' + url;
                }
                try {
                    new URL(url);
                } catch {
                    return { valid: false, error: 'Formato de URL inválido. Ex: https://meusite.com.br' };
                }
                return { valid: true, content: url, type: 'url' };
            }

            case 'text': {
                const text = (inputText.value || '').trim();
                if (text === '') return { valid: false, error: 'Insira o texto que deseja codificar no QR Code.' };
                if (text.length > MAX_LENGTH) return { valid: false, error: 'O texto excede o limite máximo de 2.953 caracteres.' };
                return { valid: true, content: text, type: 'text' };
            }

            case 'whatsapp': {
                const phone = (inputWaPhone.value || '').replace(/\D/g, '');
                const msg = (inputWaMsg.value || '').trim();
                if (phone.length < 8) return { valid: false, error: 'Informe um número de WhatsApp válido com DDD/código do país.' };
                const waUrl = `https://wa.me/${phone}${msg ? '?text=' + encodeURIComponent(msg) : ''}`;
                if (waUrl.length > MAX_LENGTH) return { valid: false, error: 'O link do WhatsApp excede o tamanho permitido.' };
                return { valid: true, content: waUrl, type: 'whatsapp' };
            }

            case 'wifi': {
                const ssid = (inputWifiSsid.value || '').trim();
                const pass = inputWifiPass.value || '';
                const auth = selectWifiAuth.value || 'WPA';
                const hidden = checkWifiHidden.checked ? 'true' : 'false';
                if (ssid === '') return { valid: false, error: 'Informe o nome da rede Wi-Fi (SSID).' };
                const wifiCode = `WIFI:S:${ssid};T:${auth};P:${pass};H:${hidden};;`;
                return { valid: true, content: wifiCode, type: 'wifi' };
            }

            default:
                return { valid: false, error: 'Aba selecionada inválida.' };
        }
    }

    // ==========================================
    // 8. PRÉ-VISUALIZAÇÃO EM TEMPO REAL (JS)
    // ==========================================

    function scheduleLivePreview() {
        if (livePreviewTimeout) clearTimeout(livePreviewTimeout);
        livePreviewTimeout = setTimeout(triggerLivePreview, 150);
    }

    function triggerLivePreview() {
        const data = getFormattedContent();
        const fg = fgColorInput.value;
        const bg = bgColorInput.value;

        if (!data.valid) {
            previewPlaceholder.classList.remove('d-none');
            qrcodeCanvasWrapper.innerHTML = '';
            qrcodeCanvasWrapper.style.backgroundColor = 'transparent';
            previewWrapper.classList.remove('has-code');
            previewActions.classList.add('d-none');
            return;
        }

        previewPlaceholder.classList.add('d-none');
        qrcodeCanvasWrapper.innerHTML = '';
        qrcodeCanvasWrapper.style.backgroundColor = bg;
        previewWrapper.classList.add('has-code');

        try {
            // Biblioteca client-side QRCode (assets/vendor/qrcode.min.js)
            currentPreviewQRCode = new QRCode(qrcodeCanvasWrapper, {
                text: data.content,
                width: 250,
                height: 250,
                colorDark: fg,
                colorLight: bg,
                correctLevel: QRCode.CorrectLevel.M
            });

            // Se for URL ou WhatsApp, configura botão de teste de link
            if (data.type === 'url' || data.type === 'whatsapp') {
                btnTestUrl.href = data.content;
                btnTestUrl.classList.remove('d-none');
            } else {
                btnTestUrl.classList.add('d-none');
            }

        } catch (err) {
            console.error('Erro na pré-visualização instantânea:', err);
        }
    }

    // ==========================================
    // 9. SUBMISSÃO E GERAÇÃO NO SERVIDOR
    // ==========================================

    formGenerate.addEventListener('submit', async (e) => {
        e.preventDefault();

        const dataCheck = getFormattedContent();
        if (!dataCheck.valid) {
            showToast(dataCheck.error, 'danger');
            return;
        }

        const ratio = calculateContrastRatio(fgColorInput.value, bgColorInput.value);
        if (ratio < 3.0) {
            showToast(
                `Contraste muito baixo (${ratio.toFixed(2)}:1). Ajuste as cores para um contraste mínimo de 3.0:1 antes de gerar.`,
                'warning'
            );
            return;
        }

        // Prepara payload
        const payload = {
            content_type: activeTab,
            fg_color: fgColorInput.value,
            bg_color: bgColorInput.value,
            size: parseInt(sizeSelect.value, 10) || 350,
            format: formatSelect.value || 'png',
            ecc: eccSelect.value || 'M'
        };

        if (activeTab === 'url') {
            payload.url = dataCheck.content;
        } else if (activeTab === 'text') {
            payload.text = dataCheck.content;
        } else if (activeTab === 'whatsapp') {
            payload.phone = (inputWaPhone.value || '').replace(/\D/g, '');
            payload.message = inputWaMsg.value || '';
        } else if (activeTab === 'wifi') {
            payload.ssid = inputWifiSsid.value || '';
            payload.password = inputWifiPass.value || '';
            payload.auth = selectWifiAuth.value || 'WPA';
            payload.hidden = checkWifiHidden.checked;
        }

        // Estado de Loading
        btnSubmit.disabled = true;
        const originalBtnHtml = btnSubmit.innerHTML;
        btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Gerando QR Code...';

        try {
            const response = await fetch('api/generate.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                showToast(result.error || 'Erro ao gerar QR Code no servidor.', 'danger');
                return;
            }

            // Sucesso!
            lastGeneratedData = result;
            showToast('QR Code gerado com sucesso!', 'success');

            // Renderiza imagem oficial de alta resolução
            qrcodeCanvasWrapper.innerHTML = '';
            qrcodeCanvasWrapper.style.backgroundColor = payload.bg_color;

            const qrImg = document.createElement('img');
            qrImg.src = result.qr_data_uri;
            qrImg.alt = 'QR Code Gerado';
            qrImg.className = 'img-fluid rounded shadow-sm';
            qrcodeCanvasWrapper.appendChild(qrImg);

            // Atualiza botões de Download e Ações
            btnDownloadPng.href = `api/download.php?uuid=${result.uuid}`;
            btnDownloadPng.setAttribute('download', `qrcode-${result.uuid.substring(0, 8)}.png`);

            btnDownloadSvg.href = `api/download.php?uuid=${result.uuid}`;
            btnDownloadSvg.setAttribute('download', `qrcode-${result.uuid.substring(0, 8)}.svg`);

            previewActions.classList.remove('d-none');

            // Atualiza Histórico da Sessão
            loadHistory();

        } catch (err) {
            console.error('Erro na requisição à API:', err);
            showToast('Falha na comunicação com o servidor. Verifique sua conexão.', 'danger');
        } finally {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = originalBtnHtml;
        }
    });

    // ==========================================
    // 10. AÇÕES DO RESULTADO (COPIAR E TESTAR)
    // ==========================================

    if (btnCopyUrl) {
        btnCopyUrl.addEventListener('click', async () => {
            const data = getFormattedContent();
            if (!data.valid) return;

            try {
                await navigator.clipboard.writeText(data.content);
                showToast('Link copiado para a área de transferência!', 'success');
            } catch {
                showToast('Não foi possível copiar automaticamente.', 'warning');
            }
        });
    }

    // ==========================================
    // 11. HISTÓRICO DE QR CODES DA SESSÃO
    // ==========================================

    async function loadHistory() {
        if (!historyList) return;

        try {
            const response = await fetch('api/history.php');
            const data = await response.json();

            if (!response.ok || !data.success) {
                return;
            }

            const items = data.items || [];
            if (historyCountBadge) {
                historyCountBadge.textContent = items.length;
            }

            if (items.length === 0) {
                historyList.innerHTML = '';
                historyEmpty.classList.remove('d-none');
                btnClearHistory.disabled = true;
                return;
            }

            historyEmpty.classList.add('d-none');
            btnClearHistory.disabled = false;

            historyList.innerHTML = items.map(item => {
                const isUrl = (item.content_type === 'url' || item.content_type === 'whatsapp');
                const truncatedContent = item.content_data.length > 50 
                    ? item.content_data.substring(0, 50) + '...' 
                    : item.content_data;

                return `
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="history-item d-flex gap-3 align-items-center">
                            <img src="${item.preview_url || 'assets/vendor/bootstrap-icons.min.css'}" 
                                 alt="Miniatura" 
                                 class="history-thumbnail"
                                 onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'64\' height=\'64\' fill=\'%236c757d\' viewBox=\'0 0 16 16\'><path d=\'M0 .5A.5.5 0 0 1 .5 0h7a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-7z\'/></svg>'">
                            
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="badge bg-secondary bg-opacity-25 text-light text-uppercase font-monospace" style="font-size: 0.65rem;">
                                        ${item.content_type}
                                    </span>
                                    <small class="text-secondary" style="font-size: 0.7rem;">${item.created_at}</small>
                                </div>
                                <p class="text-white small mb-2 text-truncate" title="${item.content_data}">
                                    ${truncatedContent}
                                </p>
                                <div class="d-flex gap-2">
                                    <a href="${item.download_url}" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size: 0.75rem;" title="Baixar">
                                        <i class="bi bi-download me-1"></i> Baixar
                                    </a>
                                    ${isUrl ? `
                                        <a href="${item.content_data}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm py-0 px-2" style="font-size: 0.75rem;" title="Abrir Link">
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

        } catch (err) {
            console.error('Erro ao carregar histórico:', err);
        }
    }

    // Limpar Histórico com Modal
    if (btnClearHistory) {
        btnClearHistory.addEventListener('click', () => {
            showAppModal({
                title: 'Limpar Histórico',
                message: 'Tem certeza que deseja apagar todos os QR Codes gerados nesta sessão? Esta ação não pode ser desfeita.',
                type: 'warning',
                confirmText: 'Sim, Limpar',
                cancelText: 'Cancelar',
                onConfirm: async () => {
                    try {
                        const response = await fetch('api/clear_history.php', { method: 'POST' });
                        const res = await response.json();
                        if (res.success) {
                            showToast('Histórico limpo com sucesso!', 'success');
                            loadHistory();
                        } else {
                            showToast(res.error || 'Erro ao limpar histórico.', 'danger');
                        }
                    } catch (err) {
                        showToast('Erro ao comunicar com o servidor.', 'danger');
                    }
                }
            });
        });
    }

    // Inicialização
    updateContrastUI();
    updateCounter(inputUrl, inputUrlCounter);
    triggerLivePreview();
    loadHistory();
});
