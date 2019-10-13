<?php
    date_default_timezone_set('America/Lima');

    $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $requestUri = parse_url('http://example.com' . $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    // explode('/', explode('?' ,$_SERVER['REQUEST_URI'])[0])
    $virtualPath = '/' . ltrim(substr($requestUri, strlen($scriptName)), '/');

    define('URI', $requestUri);
    define('URL_PATH', $scriptName === '/' ? '' : ('/'. trim($scriptName,'/')));
    define('URL',$virtualPath);

    define('ROOT_DIR', $_SERVER["DOCUMENT_ROOT"] . $scriptName);
    define('CONTROLLER_PATH', ROOT_DIR. '/src/Controllers');
    define('MODEL_PATH', ROOT_DIR. '/src/Models');
    define('VIEW_PATH', ROOT_DIR. '/src/Views');

    define('SESS_KEY','SkyId');
    define('SESS_FA2','SkyIdFa2');
    define('SESS_DATA','SkyData');
    define('SESS_MENU','SkyMenu');

    define('APP_NAME','SnPass');
    define('APP_AUTHOR','SnPass');
    define('APP_DESCRIPTION','SnPass');