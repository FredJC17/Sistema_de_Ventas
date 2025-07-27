<?php

include $_SERVER['DOCUMENT_ROOT']."/sisventas/modelo/producto.php";
include_once $_SERVER['DOCUMENT_ROOT']."/sisventas/includes/db.php";

class ProductoController {
    private $con;

    public function __construct() {
        $this->con = (new DBConection())->conectar();
    }

    public function obtener_listado(){

        $listado = new Producto();
       $res = $listado->listado();
        return $res;

    }

    public function inserta_producto($nom, $und,$stock, $precio, $costo){
        $oprodu = new Producto();
        $oprodu->setNomprodu($nom);
        $oprodu->setUnimed($und);
        $oprodu->setStock($stock);
        $oprodu->setPreuni($precio);
        $oprodu->setCosuni($costo);

        $res =$oprodu->create();
        if ($res){
            return true;
        }
        else    
            return false;

    }
    public function buscar_producto($busqueda) {
    $sql = "SELECT * FROM productos WHERE 
        idproducto LIKE :busqueda OR 
        nomproducto LIKE :busqueda OR 
        idcategoria LIKE :busqueda";
    $stmt = $this->con->prepare($sql);
    $like = "%$busqueda%";
    $stmt->bindParam(':busqueda', $like);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}