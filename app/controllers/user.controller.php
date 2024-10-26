<?php

require_once "app/models/user.model.php";
require_once "app/views/JSONView.php";
require_once "libs/jwt.php";

class User_controller {
    private $model;
    private $view;

    public function __construct(){
        $this->model = new User_model();
        $this->view = new JSONView();
    }

    public function getToken(){
        if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
            return $this->view->response("No se encontró el encabezado de autorización", 400);
        }
        
        $auth_header = $_SERVER['HTTP_AUTHORIZATION'];
        $auth_header = explode(" ", $auth_header);

        if(count($auth_header) != 2 || $auth_header[0] != 'Basic'){
            return $this->view->response("Error en los datos ingresados", 401);
        }

        $user_pass = base64_decode($auth_header[1]);
        $user_pass = explode(":", $user_pass);

        $name = $user_pass[0];
        $user = $this->model->getUserByNickname($user_pass[0]);
        

        if(!$user){
            return $this->view->response("El usuario con el nickname=$name no existe", 404);
        }

        if(!password_verify($user_pass[1], $user->password)){
            return $this->view->response("Contraseña incorrecta", 400);
        }

        $token = createJWT(array(
            "sub" => $user->id,
            "nickname"=> $user->nickname, 
            "role" => "admin",
            "iat" => time(),
            "exp" => time() + 300,
        ));

        if(!$token){
            return $this->view->response("Error no se pudo crear el token", 500);
        }
        return $this->view->response($token);
    }
}