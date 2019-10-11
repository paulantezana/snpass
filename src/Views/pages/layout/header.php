<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= APP_NAME ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700&display=swap">

    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/dist/css/basic.css">
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/libraries/fonts/styles.css">

    <link rel="shortcut icon" href="<?= URL_PATH ?>/assets/images/logo.png">

    <link rel="manifest" href="<?= URL_PATH ?>/assets/dist/manifest.json">
    <script src="<?= URL_PATH ?>/assets/dist/script/sedna-min.js"></script>
</head>
<body>
<div class="BasicLayout" id="BasicLayout">
    <div class="BasicLayout-header">
        <div class="Branding">
            <a href="<?= URL_PATH ?>" class="Branding-header">
                <img src="<?= URL_PATH ?>/assets/images/logo.png" alt="" class="Branding-img">
                <h1> <?= APP_NAME ?> </h1>
            </a>
            <div class="Branding-description">gestion de contraseñas</div>
        </div>
    </div>
    <div class="BasicLayout-main">
