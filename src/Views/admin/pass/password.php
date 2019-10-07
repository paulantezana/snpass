<div class="SnToolbar">
<div class="SnToolbar-left">
    <i class="icon-braille"></i> <strong>Contrasenas de :</strong> <?= $passCustomer['name'] ?? '' ?>
</div>
<div class="SnToolbar-right">
    <div class="SnBtn jsPassPasswordOption" onclick="PassPasswordForm.list(<?= $passCustomer['pass_customer_id'] ?? 0 ?>)">
        <i class="icon-refresh"></i>
        Actualizar
    </div>
    <div class="SnBtn primary jsPassPasswordOption" onclick="PassPasswordForm.showModalCreate()">
        <i class="icon-plus"></i>
        Nuevo
    </div>
</div>
</div>
<?php foreach ($password ?? [] as $row): ?>
<div class="SnCard SnMb-32">
    <div class="SnCard-body">
        <div class="PassPassword">
            <div class="PassPassword-header">
                <strong class="PassPassword-title"><?= $row['title'] ?? '' ?></strong>
<!--                <div class="SnAvatar rect">--><?//= $row['key_char'] ?><!--</div>-->

            </div>
            <div class="PassPassword-content">
                <div class="Credential">
                    <div class="Credential-item">
                        <div id="copyClipUser<?= $row['pass_password_id'] ?? 0 ?>" class="Credential-secret"><?= $row['user_name'] ?></div>
                        <div class="SnBtn lg success" onclick="PassPasswordForm.copyClipboard('copyClipUser<?= $row['pass_password_id'] ?? 0 ?>',<?= $row['pass_password_id'] ?? 0 ?>)">Copiar usuario</div>
                    </div>
                    <div class="Credential-item">
                        <div id="copyClipPass<?= $row['pass_password_id'] ?? 0 ?>" class="Credential-secret"><?= $row['password'] ?></div>
                        <div class="SnBtn lg success" onclick="PassPasswordForm.copyClipboard('copyClipPass<?= $row['pass_password_id'] ?? 0 ?>',<?= $row['pass_password_id'] ?? 0 ?>)">Copiar contraseña</div>
                    </div>
                    <a href="<?= $row['web_site'] ?>" target="_blanck" class="SnBtn primary lg">iniciar sesión</a>
                </div>

            </div>
            <div class="PassPassword-footer">
                <div class="SnBtn jsPassCustomerOption" onclick="PassPasswordForm.showModalUpdate(<?= $row['pass_password_id'] ?? 0 ?>,'<?= $row['title'] ?? '' ?>')">
                    <i class="icon-edit"></i>
                </div>
                <div class="SnBtn jsPassCustomerOption" onclick="PassPasswordForm.delete(<?= $row['pass_password_id'] ?? 0 ?>,'<?= $row['title'] ?? '' ?>')">
                    <i class="icon-trash"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>