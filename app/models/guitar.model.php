<?php

require_once 'config.php';
class Guitar_model
{
    private $db;

    private $view;
    public function __construct()
    {
        $this->db = $this->connect();
    }

    private function connect()
    {
        return new PDO('mysql:host='.MYSQL_HOST .';dbname='.MYSQL_DB.';charset=utf8', MYSQL_USER, MYSQL_PASS);
    }


    public function getGuitarras($filtrar = null, $orden = false, $page = 1, $limit = 10)
    {
        $sql = "SELECT * FROM guitarra ";
        $queryParams = $this->getQueryParams($filtrar, $orden, $page, $limit);
        $sql .= $queryParams;


        $query = $this->db->prepare($sql);
        $query->execute();

        $guitars = $query->fetchAll(PDO::FETCH_OBJ);

        return $guitars;
    }
    private function getQueryParams($filtrar, $orden, $page = 1, $limit = 10)
    {
        $sql = $this->getFilterClause($filtrar);
        $sql .= $this->getOrderClause($orden);
        $offset = ($page - 1) * $limit;
        $sql .= " LIMIT $limit OFFSET $offset";
        return $sql;
    }

    private function getOrderClause($orden)
    {
        $sql = "";
        if ($orden) {
            $columns = ['precio', 'nombre', 'id'];
            if (in_array($orden, $columns)) {
                $sql .= " ORDER BY " . $orden;
            }
        }
        return $sql;
    }

    private function getFilterClause($filtrar)
    {
        $sql = "";

        // Verifica si ya hay una condición WHERE y añade 'AND' en lugar de 'WHERE'
        if ($filtrar == 'en-oferta') {
            $sql .= (strpos($sql, "WHERE") === false ? " WHERE " : " AND ") . "en_oferta = 1";
        }

        if ($filtrar) {
            $filtrar = ucfirst($filtrar); // Convertir la primera letra en mayúscula
            $categoria = $this->getCategoriaByNombre($filtrar);
            if ($categoria) {
                $sql .= " WHERE categoria_id = " . $categoria->id_categoria;
            }
        }

        return $sql;
    }


    public function getGuitarrasByCategoria($id_categoria, $filtrar, $orderBy)
    {

        $base = "SELECT * FROM guitarra ";
        $queryParams = $this->getQueryParams($filtrar, $orderBy);
        $base .= " WHERE categoria_id = ?";
        $sql = $base . $queryParams;

        $query = $this->db->prepare($sql);
        $query->execute([$id_categoria]);

        $filteredGuitars = $query->fetchAll(PDO::FETCH_OBJ);

        return $filteredGuitars;
    }

    public function getGuitarraByID($id)
    {
        $query = $this->db->prepare("SELECT * FROM guitarra WHERE id_guitarra = ?");
        $query->execute([$id]);

        $guitar = $query->fetch(PDO::FETCH_OBJ);

        return $guitar;
    }

    public function addGuitarra($nombre, $categoria_id, $precio, $imagen_url)
    {
        $query = $this->db->prepare("INSERT INTO guitarra(nombre, categoria_id, precio, imagen_url) VALUES(?,?,?,?)");
        $query->execute([$nombre, $categoria_id, $precio, $imagen_url]);

        return $this->db->lastInsertId();
    }

    public function deleteGuitarra($id)
    {
        $query = $this->db->prepare("DELETE FROM guitarra WHERE id_guitarra = ?");
        $query->execute([$id]);
    }

    public function updateGuitarra($id, $nombre, $precio, $categoria_id, $imagen)
    {
        $query = $this->db->prepare("UPDATE guitarra SET nombre = ?, precio = ?, categoria_id = ?, imagen_url = ? WHERE id_guitarra = ?");
        $query->execute([$nombre, $precio, $categoria_id, $imagen, $id]);
    }

    public function addCategoria($nombre)
    {
        $query = $this->db->prepare("INSERT INTO categoria(nombre) VALUES(?)");
        $query->execute([$nombre]);
    }

    public function getCategoriaById($id_categoria)
    {
        $query = $this->db->prepare("SELECT * FROM categoria WHERE id_categoria = ?");
        $query->execute([$id_categoria]);

        $categoria = $query->fetch(PDO::FETCH_OBJ);

        return $categoria;
    }

    public function getCategoriaByNombre($categoria)
    {
        $query = $this->db->prepare("SELECT * FROM categoria WHERE nombre = ?");
        $query->execute([$categoria]);

        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function updateCategoriaGuitarra($id_guitarra, $categoria_id)
    {
        $query = $this->db->prepare("UPDATE guitarra SET categoria_id = ?  WHERE id_guitarra = ? ");
        $query->execute([$categoria_id, $id_guitarra]);
    }

    public function deleteCategoria($categoria_id, $filtrar, $orden)
    {
        //Que pasa si elimino una categoria y hay guitarras que pertenecen a esa categoria???
        //habria que hacer una categoria "sin categoria" y setear esa guitarra en esa categoria
        //sino va a dar un error porque la categoria no existe

        //entonces primero agarramos todas las guitarras con la categoria que queremos borrar y la seteamos a "sin categoria"

        //en el controlador habria que checkear que el admin no pueda borrar esta categoria, ya que romperia el sistema
        $sin_categoria = $this->getCategoriaByNombre("sin_categoria");
        $id_sin_categoria = $sin_categoria->id_categoria;

        // verifico si la categoría a eliminar es "sin categoría"
        if ($categoria_id == $id_sin_categoria) {
            throw new Exception("No se puede eliminar la categoría 'sin categoría'.");
        }
        $guitars = $this->getGuitarrasByCategoria($categoria_id, $filtrar, $orden);

        foreach ($guitars as $guitar) {
            //llamamos a un metodo que hace update a la categoria y le pasamos que actualice la categoria de la guitarra a "sin categoria";
            $this->updateCategoriaGuitarra($guitar->id_guitarra, $id_sin_categoria);
        }
        //ahora borramos la categoria
        $query = $this->db->prepare("DELETE FROM categoria WHERE id_categoria = ?");
        $query->execute([$categoria_id]);
    }
    public function getNombreCategoriaById($id_categoria)
    {
        $query = $this->db->prepare("SELECT nombre FROM categoria WHERE id_categoria = ?");
        $query->execute([$id_categoria]);

        $categoria = $query->fetch(PDO::FETCH_OBJ);

        if ($categoria) {
            return $categoria->nombre;
        } else {
            return null;
        }
    }
    public function getCategorias()
    {
        $query = $this->db->prepare("SELECT * FROM categoria");
        $query->execute();

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function updateImg($id, $imagen_url)
    {
        $query = $this->db->prepare("UPDATE guitarra SET imagen_url = ? WHERE id_guitarra = ?");
        $query->execute([$imagen_url, $id]);
    }

    public function cambiarNombre($id, $nombre)
    {
        $query = $this->db->prepare("UPDATE guitarra SET nombre = ? WHERE id_guitarra = ?");
        $query->execute([$nombre, $id]);
    }
    public function updatePrecio($id, $precio)
    {
        $query = $this->db->prepare("UPDATE guitarra SET precio = ? WHERE id_guitarra = ?");
        $query->execute([$precio, $id]);
    }
}
