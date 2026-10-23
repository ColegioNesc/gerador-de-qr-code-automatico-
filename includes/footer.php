<?php
/**
 * Rodapé do Sistema, Modais Globais e Carregamento de Scripts
 * Responsividade Mobile-First com Bootstrap 5 e background 100% preto.
 */

declare(strict_types=1);
?>
    <!-- Rodapé -->
    <footer class="mt-auto border-top border-secondary border-opacity-25 py-4 bg-black">
        <div class="container">
            <div class="row align-items-center g-3">
                <div class="col-12 col-md-6 text-center text-md-start">
                    <p class="mb-1 text-light small fw-semibold">
                        <i class="bi bi-qr-code text-primary me-1"></i> Gerador de QR Code Automático &amp; Personalizado
                    </p>
                    <p class="text-secondary small mb-0">
                        Totalmente gratuito, seguro e sem cadastro. Contraste certificado conforme WCAG.
                    </p>
                </div>
                <div class="col-12 col-md-6 text-center text-md-end">
                    <div class="d-flex justify-content-center justify-content-md-end gap-2 flex-wrap">
                        <span class="badge bg-dark border border-secondary border-opacity-50 text-secondary py-2 px-3">
                            <i class="bi bi-phone me-1"></i> Leitura Garantida
                        </span>
                        <span class="badge bg-dark border border-secondary border-opacity-50 text-secondary py-2 px-3">
                            <i class="bi bi-file-earmark-image me-1"></i> PNG &amp; SVG Vetorial
                        </span>
                        <span class="badge bg-dark border border-secondary border-opacity-50 text-secondary py-2 px-3">
                            <i class="bi bi-palette me-1"></i> Cores Customizadas
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Modal Universal do Sistema (Substitui alerts e confirms nativos conforme regra) -->
    <div class="modal fade" id="appModal" tabindex="-1" aria-labelledby="appModalTitle" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-light border border-secondary border-opacity-50 shadow-lg">
                <div class="modal-header border-secondary border-opacity-25 pb-2">
                    <div class="d-flex align-items-center gap-2">
                        <span id="appModalIcon" class="fs-4 text-info">
                            <i class="bi bi-info-circle-fill"></i>
                        </span>
                        <h5 class="modal-title fs-5 fw-bold" id="appModalTitle">Mensagem do Sistema</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body py-3" id="appModalBody">
                    Conteúdo da mensagem.
                </div>
                <div class="modal-footer border-secondary border-opacity-25 pt-2">
                    <button type="button" class="btn btn-outline-secondary px-4" id="appModalCancelBtn" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary px-4 d-none" id="appModalConfirmBtn">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Ajuda & Boas Práticas de Leitura -->
    <div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark text-light border border-secondary border-opacity-50 shadow-lg">
                <div class="modal-header border-secondary border-opacity-25">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-primary bg-opacity-25 p-2 rounded text-primary">
                            <i class="bi bi-lightbulb fs-4"></i>
                        </div>
                        <h5 class="modal-title fw-bold" id="helpModalTitle">Como Gerar QR Codes Perfeitos</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="row g-4">
                        <div class="col-12 col-md-6">
                            <h6 class="fw-bold text-primary mb-2">
                                <i class="bi bi-check-circle-fill me-1"></i> Regra de Contraste (Crucial)
                            </h6>
                            <p class="text-secondary small mb-2">
                                Os pontos do QR Code e a cor de fundo precisam de um contraste mínimo (índice 3.0:1 ou superior). Cores muito parecidas fazem com que as câmeras dos smartphones não consigam decodificar os módulos.
                            </p>
                            <p class="text-secondary small mb-0">
                                <strong>Dica:</strong> Pontos escuros em fundo claro são a combinação mais universal e rápida para qualquer câmera.
                            </p>
                        </div>
                        <div class="col-12 col-md-6">
                            <h6 class="fw-bold text-primary mb-2">
                                <i class="bi bi-link-45deg me-1"></i> Tamanho da URL
                            </h6>
                            <p class="text-secondary small mb-2">
                                O padrão QR Code suporta URLs de até <strong>2.953 caracteres alfanuméricos</strong>. Para links muito longos com parâmetros UTM, recomendamos URLs encurtadas para gerar um QR Code mais simples e rápido de escanear.
                            </p>
                            <p class="text-secondary small mb-0">
                                Ao ler o QR Code com a câmera, o usuário será direcionado diretamente para a URL informada.
                            </p>
                        </div>
                        <div class="col-12 col-md-6">
                            <h6 class="fw-bold text-primary mb-2">
                                <i class="bi bi-file-earmark-arrow-down me-1"></i> Formatos PNG vs SVG
                            </h6>
                            <p class="text-secondary small mb-0">
                                <strong>PNG:</strong> Ideal para sites, redes sociais, apresentações e telas digitais.<br>
                                <strong>SVG:</strong> Vetor escalável de alta definição, perfeito para materiais impressos como panfletos, banners, cartões e adesivos, sem perda de nitidez.
                            </p>
                        </div>
                        <div class="col-12 col-md-6">
                            <h6 class="fw-bold text-primary mb-2">
                                <i class="bi bi-shield-check me-1"></i> Totalmente Gratuito e Seguro
                            </h6>
                            <p class="text-secondary small mb-0">
                                Não é necessário criar conta, fazer login ou pagar nada. Seus códigos são gerados instantaneamente e permanecem acessíveis no histórico da sua sessão.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary border-opacity-25">
                    <button type="button" class="btn btn-primary px-4" data-bs-dismiss="modal">Entendi, vamos lá!</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts Bootstrap 5, QRCode JS e Aplicação -->
    <script src="assets/vendor/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/qrcode.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
