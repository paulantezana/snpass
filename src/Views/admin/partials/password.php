<div class="PassPassword">
    <div class="PassPassword-header">
        <strong class="PassPassword-title"><?= $passFolder['name'] ?? '' ?> - <?= $passPassword['title'] ?? '' ?></strong>
    </div>
    <div class="PassPassword-content">
        <div class="Credential">
            <div class="Credential-item">
                <div id="copyClipUser<?= $passPassword['pass_password_id'] ?? 0 ?>" class="Credential-secret"><?= $passPassword['user_name'] ?></div>
                <div class="SnBtn lg success" onclick="PassPasswordForm.copyClipboard('copyClipUser<?= $passPassword['pass_password_id'] ?? 0 ?>',<?= $passPassword['pass_password_id'] ?? 0 ?>)">Copiar usuario</div>
            </div>
            <div class="Credential-item">
                <div id="copyClipPass<?= $passPassword['pass_password_id'] ?? 0 ?>" class="Credential-secret"><?= $passPassword['password'] ?></div>
                <div class="SnBtn lg success" onclick="PassPasswordForm.copyClipboard('copyClipPass<?= $passPassword['pass_password_id'] ?? 0 ?>',<?= $passPassword['pass_password_id'] ?? 0 ?>)">Copiar contraseña</div>
            </div>
            <a href="<?= $passPassword['web_site'] ?>" target="_blanck" class="SnBtn primary lg">iniciar sesión</a>
        </div>
    </div>
</div>