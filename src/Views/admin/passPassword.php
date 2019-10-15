<?php require_once __DIR__ . '/layout/header.php'; ?>
<div class="SnContent">
    <div class="SnCard">
        <div class="SnCard-body">
            <div>
                <div class="PassCustomer">
                    <div class="PassCustomer-row">
                        <input
                            type="text"
                            onkeyup="PassPasswordForm.search(event)"
                            id="searchPassCustomer"
                            style="height: 2.5rem"
                            class="SnForm-input icon-search"
                            placeholder="Buscar en mi bóveda..."
                        >
                    </div>
                    <div class="PassCustomer-row PassCustomer-options">
                        <strong>
                            Contraseñas
                        </strong>
                        <div class="SnBtns">
                            <div class="SnBtn jsPassPasswordOption" id="passCustomerModalReload" onclick="InfinityLoading.reload()" >Actualizar</div>
                            <div class="SnBtn primary jsPassPasswordOption" onclick="PassPasswordForm.showModalCreate()" >Nuevo</div>
                        </div>
                    </div>
                    <div class="PassCustomer-list">
                        <ul class="CustomerList InfiniteScroll" id="passPasswordList">
                        </ul>
                        <div class="SnLoading"></div>
                    </div>
                    <div class="PassCustomer-footer">

                    </div>
                </div>
            </div>
            <div>

                <div class="SnModal-wrapper" data-modal="passPasswordModal">
                    <div class="SnModal" style="max-width: 1000px">
                        <div class="SnModal-close" data-modalclose="passPasswordModal">
                            <svg viewBox="64 64 896 896" class="" data-icon="close" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">
                                <path d="M563.8 512l262.5-312.9c4.4-5.2.7-13.1-6.1-13.1h-79.8c-4.7 0-9.2 2.1-12.3 5.7L511.6 449.8 295.1 191.7c-3-3.6-7.5-5.7-12.3-5.7H203c-6.8 0-10.5 7.9-6.1 13.1L459.4 512 196.9 824.9A7.95 7.95 0 0 0 203 838h79.8c4.7 0 9.2-2.1 12.3-5.7l216.5-258.1 216.5 258.1c3 3.6 7.5 5.7 12.3 5.7h79.8c6.8 0 10.5-7.9 6.1-13.1L563.8 512z"></path>
                            </svg>
                        </div>
                        <div class="SnModal-header">Contraseña</div>
                        <div class="SnModal-body">
                            <div id="passPasswordContainer"></div>
                        </div>
                    </div>
                </div>

                <div class="SnModal-wrapper" data-modal="passPasswordModalForm">
                    <div class="SnModal">
                        <div class="SnModal-close" data-modalclose="passPasswordModalForm">
                            <svg viewBox="64 64 896 896" class="" data-icon="close" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">
                                <path d="M563.8 512l262.5-312.9c4.4-5.2.7-13.1-6.1-13.1h-79.8c-4.7 0-9.2 2.1-12.3 5.7L511.6 449.8 295.1 191.7c-3-3.6-7.5-5.7-12.3-5.7H203c-6.8 0-10.5 7.9-6.1 13.1L459.4 512 196.9 824.9A7.95 7.95 0 0 0 203 838h79.8c4.7 0 9.2-2.1 12.3-5.7l216.5-258.1 216.5 258.1c3 3.6 7.5 5.7 12.3 5.7h79.8c6.8 0 10.5-7.9 6.1-13.1L563.8 512z"></path>
                            </svg>
                        </div>
                        <div class="SnModal-header">Contraseña</div>
                        <div class="SnModal-body">
                            <form action="" class="SnForm" id="passPasswordForm" onsubmit="PassPasswordForm.submit(event)">
                                <input type="hidden" class="SnForm-input" id="passPasswordId">
                                <div class="SnForm-item required">
                                    <label for="passPasswordWebSite" class="SnForm-label">Url</label>
                                    <input type="text" class="SnForm-input" id="passPasswordWebSite">
                                </div>
                                <div class="SnGrid m-2">
                                    <div class="SnForm-item required">
                                        <label for="passPasswordTitle" class="SnForm-label">Titulo o nombre</label>
                                        <input type="text" class="SnForm-input" id="passPasswordTitle">
                                    </div>
                                    <div class="SnForm-item required">
                                        <label for="passFolderName" class="SnForm-label">Carpeta</label>
                                        <input type="text" class="SnForm-input" id="passFolderName">
                                    </div>
                                </div>
                                <div class="SnGrid m-2">
                                    <div class="SnForm-item required">
                                        <label for="passPasswordUserName" class="SnForm-label">Email o usuario</label>
                                        <input type="text" class="SnForm-input" id="passPasswordUserName">
                                    </div>
                                    <div class="SnForm-item required">
                                        <label for="passPasswordPassword" class="SnForm-label">Contrasena</label>
                                        <div class="SnInput-wrapper">
                                            <input type="password" class="SnForm-input" id="passPasswordPassword">
                                            <span class="SnInput-suffix icon-eye togglePassword"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="SnForm-item hidden">
                                    <label for="passPasswordKeyChar" class="SnForm-label">Clave</label>
                                    <input type="text" class="SnForm-input" id="passPasswordKeyChar">
                                </div>
                                <div class="SnForm-item">
                                    <label for="passPasswordDescription" class="SnForm-label">Notas</label>
                                    <textarea name="passPasswordDescription" id="passPasswordDescription" cols="10" rows="5" class="SnForm-textarea"></textarea>
                                </div>
                                <div class="SnForm-item">
                                    <button type="submit" class="SnBtn primary block" id="passPasswordFormSubmit">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="<?= URL_PATH ?>/assets/dist/script/passPassword-min.js"></script>

<?php require_once __DIR__ . '/layout/footer.php' ?>
