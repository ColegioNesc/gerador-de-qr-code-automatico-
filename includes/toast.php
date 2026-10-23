<?php
/**
 * Container de Notificações Toast do Bootstrap 5
 * Cumpre a regra: "Retorne mensagens de erro claras na interface para o usuario no estilo Toast"
 */
?>
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1090;">
    <div id="appToast" class="toast align-items-center text-bg-dark border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2">
                <i id="toastIcon" class="bi bi-info-circle-fill fs-5 text-info"></i>
                <span id="toastMessage">Mensagem do sistema</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
        </div>
    </div>
</div>
