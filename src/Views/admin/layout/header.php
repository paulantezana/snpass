<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= APP_NAME ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700&display=swap">
    <link rel="shortcut icon" href="<?= URL_PATH ?>/assets/images/logo.png">

    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/dist/css/admin.css">
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/dist/css/nprogress.css">
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/libraries/fonts/styles.css">

    <script src="<?= URL_PATH ?>/assets/dist/script/nprogress-min.js"></script>
    <script src="<?= URL_PATH ?>/assets/dist/script/sedna-min.js"></script>
    <script src="<?= URL_PATH ?>/assets/dist/script/conmon-min.js"></script>

    <link rel="manifest" href="<?= URL_PATH ?>/assets/dist/manifest.json">
</head>

<body>
<div class="AdminLayout" id="AdminLayout">
    <div class="AdminLayout-header">
        <header class="Header">
            <div class="Header-left">
                <div id="AsideMenu-toggle"> <i class="icon-menu"></i> </div>
            </div>
            <div class="Header-right">
                <ul class="HeaderMenu SnMenu">
                    <li>
                        <div class="Header-action">
                            <input class="SnSwitch SnSwitch-ios" id="themeMode" type="checkbox" checked>
                            <label class="SnSwitch-btn" for="themeMode"></label>
                        </div>
                    </li>
                    <li>
                        <a href="<?= URL_PATH ?>/admin/folder" class="Header-action">
                            <i class="icon-search"></i>
                        </a>
                    </li>
                    <li>
                        <div class="HeaderMenu-profile Header-action">
                            <span><?= $_SESSION[SESS_DATA]['user_name'] ?? '' ?></span>
                            <div class="SnAvatar-container">
                                <img src="<?= URL_PATH ?>/assets/images/logo.png" alt="avatar" class="SnAvatar">
                            </div>
                        </div>
                        <ul>
                            <li><a href="<?= URL_PATH ?>/admin/user/profile"> <i class="icon-user"></i> Perfile</a></li>
                            <li><a href="<?= URL_PATH ?>/auth/logout"> <i class="icon-sign-out"></i> Salir</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </header>
    </div>
    <div class="AdminLayout-main">

