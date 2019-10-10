</div>
<div class="AdminLayout-aside">
    <div id="AsideMenu-wrapper" class="AsideMenu-wrapper">
        <div class="AsideMenu-container">
            <div class="AsideHeader">
                <div class="Branding">
                    <a href="/" class="Branding-link">
                        <img src="<?= URL_PATH ?>/assets/images/logo.png" alt="Logo" class="Branding-img">
                        <span class="Branding-name"><?= APP_NAME ?></span>
                    </a>
                </div>
            </div>
            <?php $asideMenu = $_SESSION[SESS_MENU] ?? []; ?>
            <ul class="AsideMenu" id="AsideMenu">
                <?php if (ArrayFindIndexByColumn($asideMenu,'module','escritorio')): ?>
                    <li>
                        <a href="<?= URL_PATH ?>/admin"> <i class="icon-tachometer"></i> <span>Escritorio </span> </a>
                    </li>
                <?php endif; ?>
                <?php if (ArrayFindIndexByColumn($asideMenu,'module','contraseña')): ?>
                    <li>
                        <a href="<?= URL_PATH ?>/admin/folder"> <i class="icon-key"></i> <span>Contraseñas </span> </a>
                    </li>
                <?php endif; ?>
                <?php if (ArrayFindIndexByColumn($asideMenu,'module','reporte')): ?>
                    <li>
                        <a href="<?= URL_PATH ?>/admin/report/general"> <i class="icon-repo"></i> <span>Reportes </span> </a>
                    </li>
                <?php endif; ?>
                <?php if (ArrayFindIndexByColumn($asideMenu,'module','usuario')): ?>
                    <li>
                        <a href="<?= URL_PATH ?>/admin/user"> <i class="icon-user"></i> <span>Usuarios</span> </a>
                    </li>
                <?php endif; ?>
                <?php if (ArrayFindIndexByColumn($asideMenu,'module','rol')): ?>
                    <li>
                        <a href="<?= URL_PATH ?>/admin/role"> <i class="icon-code-fork"></i> <span>Roles</span> </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
</div>
<script src="<?= URL_PATH ?>/assets/dist/script/admin-min.js"></script>
<script src="<?= URL_PATH ?>/assets/dist/script/theme-min.js"></script>
</body>

</html>
