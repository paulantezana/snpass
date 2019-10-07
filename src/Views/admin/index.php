<?php require_once __DIR__ . '/layout/header.php'; ?>
    <div class="SnContent">
        <div class="SnGrid m-2 l-4 SnMb-16">
            <div class="SnCard">
                <div class="SnCard-body">
                    <strong>usuarios</strong>
                    <div id="userCount"></div>
                </div>
            </div>
            <div class="SnCard">
                <div class="SnCard-body">
                    <strong>clientes</strong>
                    <div id="customerCount"></div>
                </div>
            </div>
            <div class="SnCard">
                <div class="SnCard-body">
                    <strong>sessiones</strong>
                    <div id="sessionCount"></div>
                </div>
            </div>
            <div class="SnCard">
                <div class="SnCard-body"> </div>
            </div>
        </div>
        <div class="SnCard SnMb-16">
            <div class="SnCard-body">
                <div id="userCopy" class="avatarList"></div>
            </div>
        </div>
        <div class="SnCard SnMb-16">
            <div class="SnCard-body">
                <div id="customerCopy" class="avatarList"></div>
            </div>
        </div>
    </div>

<script src="<?= URL_PATH ?>/assets/dist/script/report-min.js"></script>

<?php require_once __DIR__ . '/layout/footer.php' ?>