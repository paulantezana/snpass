<?php

require_once MODEL_PATH . '/PassPassword.php';
require_once MODEL_PATH . '/PassCustomer.php';

class PassPasswordController extends Controller
{
    protected $connection;
    protected $passPasswordModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->passPasswordModel = new PassPassword($connection);
    }

    public function list(){
        $passCustomerId = $_GET['passCustomerId'] ?? 0;
        if (!$passCustomerId){
            echo '';
            return;
        }

        $passCustomerModel = new PassCustomer($this->connection);
        $passCustomer = $passCustomerModel->GetById((int)$passCustomerId);

        $password = $this->passPasswordModel->GetAllByCustomerId((int)$passCustomerId);

        ob_start();
        $this->render('admin/pass/password.php',[
            'passCustomer' => $passCustomer->result,
            'password' => $password->result,
        ]);
        echo ob_get_clean();
    }

    public function id(){
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
        Authorization($this->connection,'rol','crear');

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
        Authorization($this->connection,'rol','eliminar');

        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);

        $validate = $this->validateInput($body,true);
        if (!$validate->success){
            echo json_encode($validate);
            return;
        }

        $res = $this->passPasswordModel->UpdateById((int)$body['passPasswordId'],[
            "title" => $body['title'],
            "description" => $body['description'],
            "user_name" => $body['userName'],
            "password" => $body['password'],
            "web_site" => $body['webSite'],
            "key_char" => $body['keyChar'],
            "pass_customer_id" => $body['passCustomerId'],
        ]);
        echo json_encode($res);
    }
    public function delete(){
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
