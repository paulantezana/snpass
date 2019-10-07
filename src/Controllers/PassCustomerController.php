<?php

require_once MODEL_PATH . '/PassCustomer.php';

class PassCustomerController extends Controller
{
    protected $connection;
    protected $passCustomerModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->passCustomerModel = new PassCustomer($connection);
    }

    public function index(){
        $this->render('admin/pass/index.php');
    }

    public function scroll(){
        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);

        if (!$body){
            echo '';
            return;
        }

        $current = $body['current'] ?? 0;
        $search = $body['search'] ?? '';
        $current = $current ? $current : 1;

        $res = $this->passCustomerModel->scroll($current,$search);
        echo json_encode($res);
    }

    public function delete(){
        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);

        $res = $this->passCustomerModel->DeleteById((int)($body['passCustomerId'] ?? 0));
        echo json_encode($res);
    }

    public function id(){
        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);
        if (!$body){
            echo '';
            return;
        }

        $passCustomer = $this->passCustomerModel->GetById((int)$body['passCustomerId']);
        echo json_encode($passCustomer);
    }

    public function create(){
        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);

        $validate = $this->validateInput($body);
        if (!$validate->success){
            echo json_encode($validate);
            return;
        }

        $res = $this->passCustomerModel->Insert($body, $_SESSION[SESS_KEY]);
        echo json_encode($res);
    }

    public function update(){
        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);

        $validate = $this->validateInput($body,true);
        if (!$validate->success){
            echo json_encode($validate);
            return;
        }

        $res = $this->passCustomerModel->UpdateById((int)$body['passCustomerId'],[
            'name' => $body['name'] ?? '',
            'description' => $body['description'] ?? '',
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_user_id' => $_SESSION[SESS_KEY],
        ]);
        echo json_encode($res);
    }

    public function validateInput($body, $update = false){
        $res = new Result();
        $res->success = true;

        if ($update){
            if (($body['passCustomerId'] ?? '') == ''){
                $res->message .= 'Falta ingresar el passCustomerId | ';
                $res->success = false;
            }
        }

        if (($body['name'] ?? '') == ''){
            $res->message .= 'Falta ingresar el nombre';
            $res->success = false;
        }

        return $res;
    }
}
