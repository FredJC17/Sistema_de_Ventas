<?php
include_once $_SERVER['DOCUMENT_ROOT']."/sisventas/includes/db.php";

class ProveedorController {
    private $con;

    public function __construct() {
        $db = new DBConection();
        $this->con = $db->conectar();
    }

    public function obtener_listado() {
        $sql = "SELECT * FROM proveedores";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}