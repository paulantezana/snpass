<?php require_once __DIR__ . '/layout/header.php'; ?>
<div class="SnContentAside UserRole">
    <div class="SnContentAside-left">
        <div class="SnCard">
            <div class="SnCard-body">
                <div class="SnToolbar">
                    <div class="SnToolbar-left">
                        <i class="icon-braille"></i> Roles
                    </div>
                    <div class="SnToolbar-right SnBtns">
                        <div
                            data-tooltip="Recargar lista"
                            class="SnBtn jsUserRoleOption"
                            onclick="UserRoleForm.list()">
                            <i class="icon-refresh"></i>
                        </div>
                        <div
                            data-tooltip="Crear nuevo rol"
                            class="SnBtn primary jsUserRoleOption"
                            onclick="UserRoleForm.showModalCreate()">
                            Nuevo
                        </div>
                    </div>
                </div>

                <div id="userRoleListContainer"></div>
            </div>
        </div>
    </div>
    <div class="SnContentAside-right">
        <div class="SnCard">
            <div class="SnCard-body">
                <div class="SnToolbar">
                    <div class="SnToolbar-left">
                        <i class="icon-braille"></i>
                        <strong>Permisos del : </strong>
                        <span id="userRoleAuthTitle"></span>
                    </div>
                    <div class="SnToolbar-right">
                    </div>
                </div>
                <div id="userRoleAuthList">
                    <div class="SnTable-container SnMb-16">
                        <div class="SnTable-wrapper">
                            <table class="SnTable">
                                <thead>
                                    <tr>
                                        <th>Modulo</th>
                                        <th>Accion</th>
                                        <th>Descripcion</th>
                                        <th style="width: 100px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($appAuthorization ?? [] as $row): ?>
                                        <tr data-id="<?= $row['app_authorization_id'] ?>">
                                            <td><?= $row['module'] ?></td>
                                            <td><?= $row['action'] ?></td>
                                            <td><?= $row['description'] ?></td>
                                            <td>
                                                <div>
                                                    <input class="SnSwitch SnSwitch-ios" id="autState<?= $row['app_authorization_id'] ?>" type="checkbox">
                                                    <label class="SnSwitch-btn" for="autState<?= $row['app_authorization_id'] ?>"></label>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <button class="SnBtn primary hidden block jsUserRoleOption" id="userRoleAuthSave" onclick="UserRoleForm.saveAuthorization()" >Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="<?= URL_PATH ?>/assets/dist/script/userRole-min.js"></script>
<?php
    require_once  __DIR__ . '/roleModalForm.php';
    require_once __DIR__ . '/layout/footer.php';
?>