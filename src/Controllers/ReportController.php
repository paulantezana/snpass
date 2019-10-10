<?php

require_once MODEL_PATH . '/PassFolder.php';
require_once MODEL_PATH . '/User.php';
require_once MODEL_PATH . '/PassPassword.php';
require_once MODEL_PATH . '/Report.php';

class ReportController extends Controller
{
    protected $connection;
    protected $reportModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->reportModel = new Report($connection);
    }

    public function general(){
        Authorization($this->connection,'reporte','listar');

        $page = $_GET['page'] ?? 0;
        if (!$page){
            $page = 1;
        }
        $filter = $_GET['filter'] ?? [];

        $userModel = new User($this->connection);
        $passCustomerModel = new PassFolder($this->connection);

        $user = $userModel->GetAll();
        $customer = $passCustomerModel->GetAll();
        $passPassword = $this->reportModel->PaginateAudit($page,10, $filter);

        $this->render('admin/pass/report.php',[
            'user' => $user->result,
            'customer' => $customer->result,
            'passPassword' => $passPassword->result,
        ]);
    }

    public function summary(){
        Authorization($this->connection,'reporte','listar');

        $sessionByMonth = $this->reportModel->GetSessionByMonth();
        $total = $this->reportModel->GetTotal();
        $userCopy = $this->reportModel->TopUserCopy();
        $customerCopy = $this->reportModel->TopCustomerCopy();

        $res = new Result();
        $res->result = [
            'sessionByMonth' => $sessionByMonth->result,
            'total' => $total->result,
            'userCopy' => $userCopy->result,
            'customerCopy' => $customerCopy->result,
        ];
        $res->success = true;
        echo json_encode($res);
    }
}