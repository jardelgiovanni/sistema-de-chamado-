<?php

namespace controllers;

class chamadoController
{
    public function existeToken()
    {
        $token = $_GET['token'];
        $verifica = \MySql::conectar()->prepare("SELECT token FROM chamados WHERE token = ?");
        $verifica->execute([$token]);

        if($verifica->rowCount() == 1){
            return true;
        }else{
            return false;
        }
    }

    public function getPergunta($token)
    {
        $sql = \MySql::conectar()->prepare("SELECT * FROM chamados WHERE token = ?");
        $sql->execute([$token]);
        return $sql->fetch();
    }

    public function index($info)
    {
        \views\mainView::render('chamado', $info);
    }
}