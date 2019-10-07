<?php


class PageController extends Controller
{
    protected $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    public function login()
    {
        if (isset($_SESSION[SESS_KEY])){
            $this->redirect('/admin');
        }
        $this->render('pages/login.php');
    }
    public function error404(){
        $this->render('pages/404.php');
    }
    public function error403(){
        $this->render('pages/403.php');
    }
    public function error500(){
        $this->render('pages/500.php');
    }
}