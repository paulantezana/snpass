<?php

define('PUBLIC_ATH',[
    '/' => ['controller' => 'PageController', 'method' => 'login'],
    '/404' => ['controller' => 'PageController', 'method' => 'error404'],
    '/500' => ['controller' => 'PageController', 'method' => 'error500'],
    '/403' => ['controller' => 'PageController', 'method' => 'error403'],

    '/auth/login' => ['controller' => 'AuthController', 'method' => 'login'],
    '/auth/posLogin' => ['controller' => 'AuthController', 'method' => 'posLogin'],
    '/auth/logout' => ['controller' => 'AuthController', 'method' => 'logout'],
    '/auth/forgot' => ['controller' => 'AuthController', 'method' => 'forgot'],
    '/auth/forgotValidate' => ['controller' => 'AuthController', 'method' => 'forgotValidate'],
]);

define('ADMIN_ATH',[
    // Dashboard
    '/' => ['controller' => 'AdminController', 'method' => 'index'],
    '/auth/profile' => ['controller' => 'AuthController', 'method' => 'profile'],
    '/auth/fa2' => ['controller' => 'AuthController', 'method' => 'fa2'],
    '/auth/renderQrCode' => ['controller' => 'AuthController', 'method' => 'renderQrCode'],

    // User routes
    '/folder' => ['controller' => 'PassFolderController', 'method' => 'index'],
    '/user' => ['controller' => 'UserController', 'method' => 'index'],
    '/role' => ['controller' => 'UserRoleController', 'method' => 'index'],

    '/report/general' => ['controller' => 'ReportController', 'method' => 'general'],
    '/report/summary' => ['controller' => 'ReportController', 'method' => 'summary'],

    '/api/passFolder/id' => ['controller' => 'PassFolderController', 'method' => 'id'],
    '/api/passFolder/scroll' => ['controller' => 'PassFolderController', 'method' => 'scroll'],
    '/api/passFolder/create' => ['controller' => 'PassFolderController', 'method' => 'create'],
    '/api/passFolder/update' => ['controller' => 'PassFolderController', 'method' => 'update'],
    '/api/passFolder/delete' => ['controller' => 'PassFolderController', 'method' => 'delete'],

    '/api/user/id' => ['controller' => 'UserController', 'method' => 'id'],
    '/api/user/create' => ['controller' => 'UserController', 'method' => 'create'],
    '/api/user/update' => ['controller' => 'UserController', 'method' => 'update'],
    '/api/user/updatePassword' => ['controller' => 'UserController', 'method' => 'updatePassword'],
    '/api/user/delete' => ['controller' => 'UserController', 'method' => 'delete'],

    '/api/userRole/id' => ['controller' => 'UserRoleController', 'method' => 'id'],
    '/api/userRole/list' => ['controller' => 'UserRoleController', 'method' => 'list'],
    '/api/userRole/create' => ['controller' => 'UserRoleController', 'method' => 'create'],
    '/api/userRole/update' => ['controller' => 'UserRoleController', 'method' => 'update'],
    '/api/userRole/delete' => ['controller' => 'UserRoleController', 'method' => 'delete'],

    '/api/authorization/save' => ['controller' => 'AppAuthorizationController', 'method' => 'save'],
    '/api/authorization/byUserRoleId' => ['controller' => 'AppAuthorizationController', 'method' => 'byUserRoleId'],

    '/api/passPassword/detail' => ['controller' => 'PassPasswordController', 'method' => 'detail'],
    '/api/passPassword/scroll' => ['controller' => 'PassPasswordController', 'method' => 'scroll'],
    '/api/passPassword/id' => ['controller' => 'PassPasswordController', 'method' => 'id'],
    '/api/passPassword/create' => ['controller' => 'PassPasswordController', 'method' => 'create'],
    '/api/passPassword/update' => ['controller' => 'PassPasswordController', 'method' => 'update'],
    '/api/passPassword/delete' => ['controller' => 'PassPasswordController', 'method' => 'delete'],

    '/api/passPassword/actionAudit' => ['controller' => 'PassPasswordController', 'method' => 'actionAudit'],
]);

define('API_ADMIN_ATH',[
]);

class Router{
    public $url;
    public $controller;
    public $method;
    public $param;

    public function __construct()
    {
        $this->url = URL;
        $this->matchRoute();
    }

    private function matchRoute(){
        $path = null;

        if (isset(PUBLIC_ATH[$this->url])){
            $path = PUBLIC_ATH[$this->url] ?? PUBLIC_ATH['/404'];
        } else if (preg_match('/^\/admin/', $this->url)){
            if (!isset($_SESSION[SESS_KEY])){
                $path = PUBLIC_ATH['/'];
            }else{
                $url = '/' . trim(preg_replace('/^\/admin/','',$this->url),'/');
                $path = ADMIN_ATH[$url] ?? PUBLIC_ATH['/404'];
            }
        } else if (preg_match('/^\/api\/v1\/admin/',$this->url)){
            if (!isset($_SESSION[SESS_KEY])){
                $path = PUBLIC_ATH['/'];
            }else{
                $url = '/' . trim(preg_replace('/^\/api\/v1\/admin/','',$this->url),'/');
                $path = API_ADMIN_ATH[$url] ?? PUBLIC_ATH['/404'];
            }
        } else {
            $path = PUBLIC_ATH['/404'];
        }

        $this->controller = $path['controller'];
        $this->method = $path['method'];

        require_once CONTROLLER_PATH . "/{$this->controller}.php";
    }

    public function run(){
        try{
            $database = new Database();

            $controller = new $this->controller($database->getConnection());
            $method = $this->method;
            $controller->$method();
        } catch (PDOException $e){
            echo $e->getMessage();
            $this->log($e);
        } catch (Exception $e){
            $this->log($e);
            header('Location: ' . URL_PATH . '/500?message=' . urlencode($e->getMessage()));
        }
    }

    private function log($e){
        $ipClient='';
        $ipProxy='';
        $ipServer='';

        if (isset($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ipClient=$_SERVER['HTTP_CLIENT_IP'];
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ipProxy=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        if (isset($_SERVER['REMOTE_ADDR']))
        {
            $ipServer=$_SERVER['REMOTE_ADDR'];
        }
        $error = 'PHP Fatal error | URL : '.$_SERVER['REQUEST_URI']."\n".'IP : '.$ipClient.' | '.$ipProxy.' | '.$ipServer."\n".' ERROR index : '. $e->getMessage()."\n".$e->getTraceAsString()."\n\n";
        error_log($error);
    }
}
