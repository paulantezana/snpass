<?php

define('PUBLIC_ATH',[
    '/' => ['controller' => 'PageController', 'method' => 'login'],
    '/404' => ['controller' => 'PageController', 'method' => 'error404'],
    '/500' => ['controller' => 'PageController', 'method' => 'error500'],
    '/403' => ['controller' => 'PageController', 'method' => 'error403'],

    '/auth/login' => ['controller' => 'AuthController', 'method' => 'login'],
    '/auth/logout' => ['controller' => 'AuthController', 'method' => 'logout'],
    '/auth/forgot' => ['controller' => 'AuthController', 'method' => 'forgot'],
    '/auth/forgotValidate' => ['controller' => 'AuthController', 'method' => 'forgotValidate'],
]);

define('ADMIN_ATH',[
    // Dashboard
    '/' => ['controller' => 'AdminController', 'method' => 'index'],
    '/user/profile' => ['controller' => 'UserController', 'method' => 'profile'],

    // User routes
    '/customer' => ['controller' => 'PassCustomerController', 'method' => 'index'],
    '/user' => ['controller' => 'UserController', 'method' => 'index'],
    '/role' => ['controller' => 'UserRoleController', 'method' => 'index'],

    '/report/general' => ['controller' => 'ReportController', 'method' => 'general'],
    '/report/summary' => ['controller' => 'ReportController', 'method' => 'summary'],

    '/api/customer/id' => ['controller' => 'PassCustomerController', 'method' => 'id'],
    '/api/customer/scroll' => ['controller' => 'PassCustomerController', 'method' => 'scroll'],
    '/api/customer/create' => ['controller' => 'PassCustomerController', 'method' => 'create'],
    '/api/customer/update' => ['controller' => 'PassCustomerController', 'method' => 'update'],
    '/api/customer/delete' => ['controller' => 'PassCustomerController', 'method' => 'delete'],

    '/api/user/id' => ['controller' => 'UserController', 'method' => 'id'],
    '/api/user/create' => ['controller' => 'UserController', 'method' => 'create'],
    '/api/user/update' => ['controller' => 'UserController', 'method' => 'update'],
    '/api/user/updatePassword' => ['controller' => 'UserController', 'method' => 'updatePassword'],
    '/api/user/delete' => ['controller' => 'UserController', 'method' => 'delete'],

    '/api/user/role/id' => ['controller' => 'UserRoleController', 'method' => 'id'],
    '/api/user/role/list' => ['controller' => 'UserRoleController', 'method' => 'list'],
    '/api/user/role/create' => ['controller' => 'UserRoleController', 'method' => 'create'],
    '/api/user/role/update' => ['controller' => 'UserRoleController', 'method' => 'update'],
    '/api/user/role/delete' => ['controller' => 'UserRoleController', 'method' => 'delete'],

    '/api/authorization/save' => ['controller' => 'AppAuthorizationController', 'method' => 'save'],
    '/api/authorization/byUserRoleId' => ['controller' => 'AppAuthorizationController', 'method' => 'byUserRoleId'],

    '/api/customer/password/list' => ['controller' => 'PassPasswordController', 'method' => 'list'],
    '/api/customer/password/id' => ['controller' => 'PassPasswordController', 'method' => 'id'],
    '/api/customer/password/create' => ['controller' => 'PassPasswordController', 'method' => 'create'],
    '/api/customer/password/update' => ['controller' => 'PassPasswordController', 'method' => 'update'],
    '/api/customer/password/delete' => ['controller' => 'PassPasswordController', 'method' => 'delete'],

    '/api/customer/password/actionAudit' => ['controller' => 'PassPasswordController', 'method' => 'actionAudit'],
]);

define('API_ADMIN_ATH',[
    '/customer/route/assigns' => ['controller' => 'RouteController', 'method' => 'assigns'],
    '/customer/route/un/assigns' => ['controller' => 'RouteController', 'method' => 'unAssigns'],
    '/customer/route/remove/assign' => ['controller' => 'RouteController', 'method' => 'removeAssign'],
    '/customer/route/add/assigns' => ['controller' => 'RouteController', 'method' => 'addAssign'],
]);

class Router{
    public $url;
    public $controller;
    public $method;
    public $param;

    public function __construct()
    {
        $this->setUri();
        $this->matchRoute();
    }

    public function setUri()
    {
        $this->url = URL;
    }

    protected function server(string $key, string $default = ""){
        return array_key_exists($key,$_SERVER) ?  $_SERVER[$key] : $default;
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
        $database = new Database();

        $controller = new $this->controller($database->getConnection());
        $method = $this->method;
        $controller->$method();
    }
}
