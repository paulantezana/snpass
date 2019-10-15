<?php

require_once MODEL_PATH . '/User.php';
require_once MODEL_PATH . '/AppAuthorization.php';

require_once ROOT_DIR . '/src/Helpers/TimeAuthenticator.php';
require_once ROOT_DIR . '/src/Helpers/phpqrcode/qrlib.php';

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
                'messageType'=>'error',
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
                'messageType'=>'error',
                'message' => $loginRes->message,
            ]);
            return;
        }
        $loginUser = $loginRes->result;

        if (!($loginUser['fa2_secret_enabled']) && $loginUser['login_count'] >= 10){
            $this->redirect('/403?message='. urlencode('Su cuenta a caducado comuníquese con el administrador para activar la autenticación de dos factores'));
            return;
        }

        if ($loginUser['fa2_secret_enabled']){
            $_SESSION[SESS_FA2] = $loginUser['user_id'];
            $this->redirect('/auth/posLogin');
            return;
        }

        if(!$this->initApp($loginUser)){
            $this->logout();
            $this->redirect('/403?message=' .urlencode('Comuniquese con el administrador'));
            return;
        }

        if (!$loginUser['fa2_secret_enabled']){
            $this->redirect('/admin/auth/fa2');
            return;
        }

        $this->redirect('/admin/folder');
    }

    private function initApp($user) {
        unset($user['password']);
        unset($user['temp_key']);
        unset($user['last_update_temp_key']);
        unset($user['fa2_secret']);

        $_SESSION[SESS_KEY] = $user['user_id'];
        $_SESSION[SESS_DATA] = $user;

        $appAuthorizationModel = new AppAuthorization($this->connection);
        $appAuthorization = $appAuthorizationModel->GetMenu($user['user_role_id']);
        if (count($appAuthorization->result)<1){
            return false;
        }
        $_SESSION[SESS_MENU] = $appAuthorization->result;
        return true;
    }

    public function posLogin(){
        if (!isset($_SESSION[SESS_FA2])){
            $this->redirect('');
        }

        if (isset($_POST['commit'])){
            $timeAuthenticator = new TimeAuthenticator();
            $faKey = $_POST['user2faKey'] ?? '';

            $userModelRes = $this->userModel->GetByIdFa2((int)$_SESSION[SESS_FA2]);
            if ($userModelRes->success){
                $checkResult = $timeAuthenticator->verifyCode($userModelRes->result['fa2_secret'] ?? '',$faKey);
                if ($checkResult) {
                    if(!$this->initApp($userModelRes->result)){
                        $this->logout();
                        $this->redirect('/403?message=' .urlencode('Comuniquese con el administrador'));
                        return;
                    }
                    $this->redirect('/admin/folder');
                } else {
                    $this->render('pages/posLogin.php',[
                        'messageType'=>'error',
                        'message' => 'La clave es incorrecta',
                    ]);
                }
            }
        }

        $this->render('pages/posLogin.php');
    }

    public function logout(){
        session_destroy();
        $this->redirect('/');
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

    public function renderQrCode(){
        $secret = $_GET['data'] ?? '';
        $host = urlencode($_SERVER['SERVER_NAME'] ?? '');
        $data = "otpauth://totp/$host?secret=$secret&issuer=" . APP_NAME;
        QRcode::svg($data,false,QR_ECLEVEL_L,10,1.5);
    }

    public function profile(){
        $message = '';
        $messageType = 'info';
        $currentDate = date('Y-m-d H:i:s');

        if (isset($_POST['commitUser'])){
            $res = $this->userModel->UpdateById((int)$_SESSION[SESS_KEY],[
                "updated_at" => $currentDate,
                "updated_user_id" => $_SESSION[SESS_KEY],

                'email' => $_POST['userEmail'] ?? '',
                'user_name' => $_POST['userUserName'] ?? '',
            ]);
            $message = $res->message;
            $messageType =  $res->success ? 'success' : 'error';

        } else if (isset($_POST['commitChangePassword'])){
            $res = $this->userModel->UpdateById((int)$_SESSION[SESS_KEY],[
                "updated_at" => $currentDate,
                "updated_user_id" => $_SESSION[SESS_KEY],

                'password' => sha1($_POST['userPassword'] ?? ''),
            ]);
            $message = $res->message;
            $messageType =  $res->success ? 'success' : 'error';
        }


        $user = $this->userModel->GetById((int)$_SESSION[SESS_KEY]);
        $this->render('admin/profile.php',[
            'user' => $user->result,
            'message' => $message,
            'messageType' => $messageType,
        ]);
    }

    public function fa2(){
        $message = $_GET['message'] ?? '';
        $messageType = 'warning';
        $currentDate = date('Y-m-d H:i:s');
        $timeAuthenticator = new TimeAuthenticator();

        if (isset($_POST['commit2faKey'])){
            $faSecret = $_POST['user2faSecret'] ?? '';
            $faKey = $_POST['user2faKey'] ?? '';
            $checkResult = $timeAuthenticator->verifyCode($faSecret,$faKey);
            if ($checkResult) {
                $res = $this->userModel->UpdateById((int)$_SESSION[SESS_KEY],[
                    "updated_at" => $currentDate,
                    "updated_user_id" => $_SESSION[SESS_KEY],
                    'fa2_secret' => $faSecret,
                ]);
                $message = $res->message;
                $messageType =  $res->success ? 'success' : 'error';
                $_SESSION[SESS_DATA] = $this->userModel->GetById((int)$_SESSION[SESS_KEY])->result;
            } else {
                $message = 'Clave de verificacion incorrecta scanee nuevamente el codigo';
                $messageType =  'error';
            }
        }

        $secret = '';
        try{
            $secret = $timeAuthenticator->createSecret();
        } catch (Exception $exception){
            $message = $exception->getMessage();
            $messageType = 'error';
        }

        $user = $this->userModel->GetById((int)$_SESSION[SESS_KEY]);
        $this->render('admin/fa2.php',[
            'user' => $user->result,
            'message' => $message,
            'messageType' => $messageType,
            'secret' => $secret,
        ]);
    }
}