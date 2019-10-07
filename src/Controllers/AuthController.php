<?php

require_once MODEL_PATH . '/User.php';
require_once MODEL_PATH . '/AppAuthorization.php';

class AuthController extends Controller
{
    protected $connection;
    protected $userModel;
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->userModel = new User($connection);
    }

    public function login(){
        $user = $_POST['user'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($user == "" || $password == ""){
            $this->render('pages/login.php', [
                'user' => $user,
                'password' => $password,
                'type'=>'error',
                'message' => 'Los campos usuario y contraseña son requeridos',
            ]);
            return;
        }

        // Get model
        $loginRes = $this->userModel->login($user,$password);

        if(!$loginRes->success){
            $this->render('pages/login.php', [
                'user' => $user,
                'password' => $password,
                'type'=>'error',
                'message' => $loginRes->message,
            ]);
            return;
        }

        $_SESSION[SESS_KEY] = $loginRes->result['user_id'];
        $_SESSION[SESS_DATA] = $loginRes->result;

        $appAuthorizationModel = new AppAuthorization($this->connection);
        $appAuthorization = $appAuthorizationModel->GetMenu($loginRes->result['user_role_id']);
        if (count($appAuthorization->result)<1){
            $this->redirect('/403');
            $this->logout();
        }
        $_SESSION[SESS_MENU] = $appAuthorization->result;

        $this->redirect('/admin/customer');
    }

    public function logout(){
        session_destroy();
        $this->redirect();
    }

    public function  forgot(){
        $this->render('pages/forgot.php');
    }

    public  function forgotValidate(){
        $key = $_GET['key'] ?? '';
        if ($key == ''){
            $this->redirect('/403');
        }

        $user = $this->userModel->GetBy('temp_key', $key);
        if (!$user->success){
            $this->redirect('/403?message=' . $user->message);
        }

        $this->render('pages/forgotValidate.php');
    }

    public function changePassword(){

    }
}