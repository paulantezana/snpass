<div class="SnModal-wrapper" data-modal="userRoleModalForm">
    <div class="SnModal">
        <div class="SnModal-close" data-modalclose="userRoleModalForm">
            <svg viewBox="64 64 896 896" class="" data-icon="close" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">
                <path d="M563.8 512l262.5-312.9c4.4-5.2.7-13.1-6.1-13.1h-79.8c-4.7 0-9.2 2.1-12.3 5.7L511.6 449.8 295.1 191.7c-3-3.6-7.5-5.7-12.3-5.7H203c-6.8 0-10.5 7.9-6.1 13.1L459.4 512 196.9 824.9A7.95 7.95 0 0 0 203 838h79.8c4.7 0 9.2-2.1 12.3-5.7l216.5-258.1 216.5 258.1c3 3.6 7.5 5.7 12.3 5.7h79.8c6.8 0 10.5-7.9 6.1-13.1L563.8 512z"></path>
            </svg>
        </div>
        <div class="SnModal-header">Rol</div>
        <div class="SnModal-body">
            <form action="" class="SnForm" id="userRoleForm" onsubmit="UserRoleForm.submit(event)">
                <input type="hidden" class="SnForm-input" id="userRoleFormId">
                <div class="SnForm-item">
                    <label for="userRoleFormName" class="SnForm-label">Nombre</label>
                    <input type="text" class="SnForm-input" id="userRoleFormName">
                </div>
                <div class="SnForm-item">
                    <button type="submit" class="SnBtn primary block" id="userRoleFormSubmit">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>