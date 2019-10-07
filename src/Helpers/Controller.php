<?php

class Controller
{
    protected function render($path, $params = []){
        extract($params);
        require_once VIEW_PATH . '/' . $path;
    }
    protected function redirect($url = ""){
        header('Location: ' . URL_PATH . $url);
    }
    protected function getParsedBody($assoc = true){
        return json_decode(file_get_contents('php://input'), $assoc);
    }
    protected function withJson($data, $status = 200){
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
        return json_encode($data);
    }
    private function _requestStatus($code) {
        $status = array(
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
        return ($status[$code])?$status[$code]:$status[500];
    }
}