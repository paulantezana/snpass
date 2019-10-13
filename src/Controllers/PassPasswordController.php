<?php

require_once MODEL_PATH . '/PassPassword.php';
require_once MODEL_PATH . '/PassFolder.php';

class PassPasswordController extends Controller
{
    protected $connection;
    protected $passPasswordModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->passPasswordModel = new PassPassword($connection);
    }

    public function detail(){
        Authorization($this->connection,'contraseña','listar');

        $passPasswordId = $_GET['passPasswordId'] ?? 0;
        if (!$passPasswordId){
            echo '';
            return;
        }

        $passCustomerModel = new PassFolder($this->connection);

        $passPassword = $this->passPasswordModel->GetById((int)$passPasswordId);
        $passCustomer = $passCustomerModel->GetById((int)$passPassword->result['pass_folder_id'] ?? 0);

        ob_start();
        $this->render('admin/pass/password.php',[
            'passFolder' => $passCustomer->result,
            'passPassword' => $passPassword->result,
        ]);
        echo ob_get_clean();
    }

    public function scroll(){
        Authorization($this->connection,'contraseña','listar');

        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);

        if (!$body){
            echo '';
            return;
        }

        $current = $body['current'] ?? 0;
        $search = $body['search'] ?? '';
        $current = $current ? $current : 1;

        $res = $this->passPasswordModel->Scroll($current,$search);
        echo json_encode($res);
    }

    public function id(){
        Authorization($this->connection,'contraseña','modificar');

        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);
        if (!$body){
            echo '';
            return;
        }

        $userRole = $this->passPasswordModel->GetById((int)$body['passPasswordId']);
        echo json_encode($userRole);
    }

    public function create(){
        Authorization($this->connection,'contraseña','crear');

        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);

        $validate = $this->validateInput($body);
        if (!$validate->success){
            echo json_encode($validate);
            return;
        }

        $res = $this->passPasswordModel->Insert($body, $_SESSION[SESS_KEY]);
        echo json_encode($res);
    }
    public function update(){
        Authorization($this->connection,'contraseña','modificar');

        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);

        $validate = $this->validateInput($body,true);
        if (!$validate->success){
            echo json_encode($validate);
            return;
        }

        $res = $this->passPasswordModel->Update($body, $_SESSION[SESS_KEY]);
        echo json_encode($res);
    }
    public function delete(){
        Authorization($this->connection,'contraseña','eliminar');

        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);

        $res = $this->passPasswordModel->DeleteById((int)($body['passPasswordId'] ?? 0));
        echo json_encode($res);
    }

    public function validateInput($body, $update = false){
        $res = new Result();
        $res->success = true;

//        if ($update){
//            if (($body['userRoleId'] ?? '') == ''){
//                $res->message .= 'Falta ingresar el userRoleId | ';
//                $res->success = false;
//            }
//        }
//
//        if (($body['name'] ?? '') == ''){
//            $res->message .= 'Falta ingresar el nombre';
//            $res->success = false;
//        }

        return $res;
    }

    public function actionAudit(){
        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);

        $res = $this->passPasswordModel->actionAudit($body,$_SESSION[SESS_KEY]);
        echo json_encode($res);
    }
}
