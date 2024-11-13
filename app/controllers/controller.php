<?php


require_once "app/models/guitar.model.php";
require_once "app/views/JSONview.php";

class Controller {
    private $model;

    private $view;

    public function __construct(){
        $this->model = new Guitar_model();
        $this->view = new JSONView();
    }

    public function getGuitarras($req, $res){
        $filtrar= $this->getFiltro($req);
        $orderBy = $this->getOrder($req);
        $page = $this->getPage($req);
        $limit = $this->getLimit($req);
    
        $guitarras = $this->model->getGuitarras($filtrar, $orderBy, $page, $limit);

        if(count($guitarras) == 0){
            return $this->view->response("No hay guitarras", 200);
        }

        return $this->view->response($guitarras, 200);
    }

    public function getPage($req){
        $page = 1;
        if(isset($req->query->page)){
            $page = $req->query->page;
        }
        return $page;
    }

    public function getLimit($req){
        $limit = 10;
        if(isset($req->query->limit)){
            $limit = $req->query->limit;
        }

        return $limit;
    }

    public function getFiltro($req){
        $filtrar = null;
        if(isset($req->query->filtrar)){
            $filtrar = $req->query->filtrar;
        }
        return $filtrar;
    }

    public function getOrder($req){
        $orderBy = false;
        if(isset($req->query->orderBy)){
            $orderBy = $req->query->orderBy;
        }
        return $orderBy;
    }

    public function getGuitarrasByCategoria($req, $res){
        $nombre_categoria = $req->params->categoria;
        $filtrar= $this->getFiltro($req);
        $orderBy = $this->getOrder($req);

        $categoria = $this->model->getCategoriaByNombre($nombre_categoria);

        if($categoria == null){
            return $this->view->response("Error al buscar por categoria", 500);
        }

        $guitarras = $this->model->getGuitarrasByCategoria($categoria->id_categoria, $filtrar, $orderBy);

        if(count($guitarras) == 0){
            return $this->view->response("no hay guitarras con la categoria= $nombre_categoria", 200);
        }

        return $this->view->response($guitarras, 200);
    }

    public function getGuitarraByID($req, $res){
        $id = $req->params->id;

        $guitarra = $this->model->getGuitarraByID($id);

        if(!$guitarra){
            return $this->view->response("la guitarra con el id ". $id. " no existe", 404);
        }

        return $this->view->response($guitarra, 200);
    }

    public function addGuitarra($req, $res){

        if(!$res->user){
            return $this->view->response("El usuario no esta autorizado", 401);
        }
        
        $nombre = $req->body->nombre;
        $categoria_id = $req->body->categoria_id;
        $precio = $req->body->precio;
        $imagen = $req->body->imagen_url;

        if(empty(($nombre) || ($categoria_id) || ($precio) || ($imagen))){
            return $this->view->response("Faltan completar campos obligatorios", 400);
        }

        $id = $this->model->addGuitarra($nombre, $categoria_id, $precio, $imagen);

        if(!$id){
            return $this->view->response("No se ha podido crear la guitarra", 500);
        }

        $guitarra = $this->model->getGuitarraByID($id);

        return $this->view->response($guitarra, 200);
    }

    public function deleteGuitarra($req, $res){

        if(!$res->user){
            return $this->view->response("El usuario no esta autorizado", 401);
        }

        $id = $req->params->id;

        $guitarra = $this->model->getGuitarraByID($id);

        if(!$guitarra){
            return $this->view->response("La guitarra con el id=$id no existe", 200);
        }

        try {
            $this->model->deleteGuitarra($id);
        } catch(Exception $e){
            return $this->view->response("No se pudo eliminar la guitarra con el id=$id", 500);
        }

        return $this->view->response("la guitarra con el id=$id fue eliminada con exito", 200);
    }

    public function getCategorias($req, $res){
        $categorias = $this->model->getCategorias();

        if(!$categorias){
            return $this->view->response("error al mostrar las categorias", 404);
        }

        return $this->view->response($categorias, 200);
    }

    public function updateGuitarra($req, $res){
        if(!$res->user){
            return $this->view->response("El usuario no esta autorizado", 401);
        }

        $id = $req->params->id;

        $guitarra = $this->model->getGuitarraByID($id);

        if(!$guitarra){
            return $this->view->response("la guitarra con el id=$id no existe", 404);
        }

        $body = $req->body;

        if(!isset($body->nombre, $body->precio, $body->imagen_url, $body->categoria_id)){
            return $this->view->response("faltan datos obligatorios", 400);
        }

        $nombre =$body->nombre;
        $precio = $body->precio;
        $imagen = $body->imagen_url;
        $categoria_id =  $body->categoria_id;

        try {
            $this->model->updateGuitarra($id, $nombre, $precio, $categoria_id, $imagen);
            return $this->view->response("guitarra actualizada con exito", 200);
        } catch (Exception  $e){
            return $this->view->response("No se pudo crear la guitarra $e", 404);
        }
    }
    
}