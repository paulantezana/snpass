<?php

require_once MODEL_PATH . '/User.php';
require_once MODEL_PATH . '/UserRole.php';

class UserController extends Controller
{
    protected $connection;
    protected $userModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->userModel = new User($connection);
    }

    public function index(){
        Authorization($this->connection,'usuario','listar');

        $page = $_GET['page'] ?? 0;
        if (!$page){
            $page = 1;
        }
        $user = $this->userModel->Paginate($page);

        $userRoleModel = new UserRole($this->connection);
        $userRole = $userRoleModel->GetAll();

        $this->render('admin/user.php',[
            'user' => $user->result,
            'userRole' => $userRole->result,
        ]);
    }

    public function id(){
        Authorization($this->connection,'usuario','modificar');

        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);
        if (!$body){
            echo '';
            return;
        }

        $userRole = $this->userModel->GetById((int)$body['userId']);
        echo json_encode($userRole);
    }

    public function create(){
        Authorization($this->connection,'usuario','crear');

        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);

        $validate = $this->validateInput($body);
        if (!$validate->success){
            echo json_encode($validate);
            return;
        }

        $res = $this->userModel->Insert($body, $_SESSION[SESS_KEY]);
        echo json_encode($res);
    }
    public function update(){
        Authorization($this->connection,'usuario','modificar');

        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);

        $validate = $this->validateInput($body,false, true);
        if (!$validate->success){
            echo json_encode($validate);
            return;
        }

        $currentDate = date('Y-m-d H:i:s');
        $res = $this->userModel->UpdateById($body['userId'],[
            "updated_at" => $currentDate,
            "updated_user_id" => $_SESSION[SESS_KEY],

            "email" => $body['email'] ?? '',
            "avatar" => '',
            "user_name" => $body['userName'] ?? '',
            "state" => $body['state'] ?? false,

            "user_role_id" => $body['userRoleId'],
        ]);
        echo json_encode($res);
    }
    public function updatePassword(){
        Authorization($this->connection,'usuario','modificar');

        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);

//        $validate = $this->validateInput($body,true);
//        if (!$validate->success){
//            echo json_encode($validate);
//            return;
//        }

        $currentDate = date('Y-m-d H:i:s');

        $res = $this->userModel->UpdateById($body['userId'],[
            "updated_at" => $currentDate,
            "updated_user_id" => $_SESSION[SESS_KEY],

            "password" => sha1($body['password'] ?? ''),
        ]);
        echo json_encode($res);
    }
    public function delete(){
        Authorization($this->connection,'usuario','eliminar');

        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);

        $res = $this->userModel->DeleteById((int)($body['userId'] ?? 0));
        echo json_encode($res);
    }

    public function validateInput($body, $password = true, $update = false){
        $res = new Result();
        $res->success = true;

        if ($update){
            if (($body['userId'] ?? '') == ''){
                $res->message .= 'Falta ingresar el userId | ';
                $res->success = false;
            }
        }

        if (($body['email'] ?? '') == ''){
            $res->message .= 'Falta ingresar el correo electrónico | ';
            $res->success = false;
        }

        if (($body['userRoleId'] ?? '') == ''){
            $res->message .= 'Falta elegir un rol | ';
            $res->success = false;
        }

        if ($password){
            if (($body['password'] ?? '') == ''){
                $res->message .= 'Falta ingresar la contraseña | ';
                $res->success = false;
            }
        }

        if (($body['userName'] ?? '') == ''){
            $res->message .= 'Falta ingresar el nombre de usuario | ';
            $res->success = false;
        }

        return $res;
    }
}
