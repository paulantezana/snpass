<?php require_once __DIR__ . '/layout/header.php'; ?>
<div class="SnContent">
    <div class="SnCard SnMb-32">
        <div class="SnCard-body">
            <?php if ($message ?? ''): ?>
                <div class="SnAlert <?= $messageType ?? '' ?> SnMb-64"><?php echo $message ?? '' ?></div>
            <?php endif; ?>

            <div class="SnGrid m-2 SnMb-64">
                <div>
                    <strong>Perfil</strong>
                    <p>Su dirección de correo electrónico es su identidad en <?= APP_NAME ?> y se utiliza para iniciar sesión.</p>
                </div>
                <form action="" class="SnForm" method="post">
                    <input type="hidden" class="SnForm-input" id="userId">
                    <div class="SnForm-item">
                        <label for="userEmail" class="SnForm-label">Email</label>
                        <input type="email" class="SnForm-input" id="userEmail" name="userEmail" value="<?= $user['email'] ?? ''?>">
                    </div>
                    <div class="SnForm-item">
                        <label for="userUserName" class="SnForm-label">Nombre de usuario</label>
                        <input type="text" class="SnForm-input" id="userUserName" name="userUserName" value="<?= $user['user_name'] ?? ''?>">
                    </div>
                    <div class="SnForm-item">
                        <button type="submit" class="SnBtn primary block" name="commitUser">Guardar</button>
                    </div>
                </form>
            </div>
            <div class="SnGrid m-2">
                <div>
                    <strong>Password</strong>
                    <p>Cambiar su contraseña también restablecerá su clave</p>
                </div>
                <form action="" class="SnForm" method="post">
                    <input type="hidden" class="SnForm-input" name="userId">
                    <div class="SnForm-item">
                        <label for="userPassword" class="SnForm-label">Contrasena</label>
                        <input type="password" class="SnForm-input" name="userPassword" id="userPassword">
                    </div>
                    <div class="SnForm-item">
                        <label for="userConfirmPassword" class="SnForm-label">Confirmar contraseña</label>
                        <input type="password" class="SnForm-input" name="userConfirmPassword" id="userConfirmPassword">
                    </div>
                    <div class="SnForm-item">
                        <button type="submit" class="SnBtn primary block" name="commitChangePassword">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>
