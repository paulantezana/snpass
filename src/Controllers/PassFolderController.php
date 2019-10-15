<?php

require_once MODEL_PATH . '/PassFolder.php';

class PassFolderController extends Controller
{
    protected $connection;
    protected $passFolderModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->passFolderModel = new PassFolder($connection);
    }

    public function search(){
        Authorization($this->connection,'folder','listar');
        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);
        if (!$body){
            echo '';
            return;
        }

        $passFolder = $this->passFolderModel->SearchBy('name',$body['search'] ?? '');
        echo json_encode($passFolder);
    }

    public function index(){
        $this->render('admin/passPassword.php');
    }

    public function scroll(){
        Authorization($this->connection,'folder','listar');
        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);

        if (!$body){
            echo '';
            return;
        }

        $current = $body['current'] ?? 0;
        $search = $body['search'] ?? '';
        $current = $current ? $current : 1;

        $res = $this->passFolderModel->scroll($current,$search);
        echo json_encode($res);
    }

    public function delete(){
        Authorization($this->connection,'folder','eliminar');
        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);

        $res = $this->passFolderModel->DeleteById((int)($body['passCustomerId'] ?? 0));
        echo json_encode($res);
    }

    public function id(){
        Authorization($this->connection,'folder','listar');
        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);
        if (!$body){
            echo '';
            return;
        }

        $passFolder = $this->passFolderModel->GetById((int)$body['passCustomerId']);
        echo json_encode($passFolder);
    }

    public function create(){
        Authorization($this->connection,'folder','crear');
        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);

        $validate = $this->validateInput($body);
        if (!$validate->success){
            echo json_encode($validate);
            return;
        }

        $res = $this->passFolderModel->Insert($body, $_SESSION[SESS_KEY]);
        echo json_encode($res);
    }

    public function update(){
        Authorization($this->connection,'folder','modificar');
        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);

        $validate = $this->validateInput($body,true);
        if (!$validate->success){
            echo json_encode($validate);
            return;
        }

        $res = $this->passFolderModel->UpdateById((int)$body['passCustomerId'],[
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
