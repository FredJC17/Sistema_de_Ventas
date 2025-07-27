<?php

include $_SERVER['DOCUMENT_ROOT']."/sisventas/includes/db.php";

class Producto {

    private $idproducto;
    private $nomprodu;
    private $unimed;
    private $stock;
    private $preuni;
    private $cosuni;
    private $con;

    public function __construct(){
        $cnx = new DBConection();
        $this->con = $cnx->conectar();
    }

    public function listado(){
        $sql = "select * from productos";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $resultados;
    }

    public function buscar($id){
 
            $sql = "select * from productos where idProducto = :codigo";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':codigo', $id);
            $stmt->execute();
            $resultados = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultados;
       

    }

    public function create(){
        $sql = "insert into productos (nomproducto,unimed,stock, preuni,cosuni)
                values (:nompro, :unimed, :stock, :preuni, :cosuni)";
        
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':nompro', $this->nomprodu);
        $stmt->bindParam(':unimed', $this->unimed);
        $stmt->bindParam(':stock', $this->stock);
        $stmt->bindParam(':preuni', $this->preuni);
        $stmt->bindParam(':cosuni', $this->cosuni);
        if ($stmt->execute()){
            return true;
        } else
            return false;
        
    }
    
    public function update(){
         $sql = "update productos 
                    set nomproducto = :nompro,
                    unimed = :unimed,
                    stock = :stock, 
                    preuni = :preuni,
                    cosuni= :cosuni 
                 where idProducto = :id
                ";       
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':id', $this->idproducto);
        $stmt->bindParam(':nompro', $this->nomprodu);
        $stmt->bindParam(':unimed', $this->unimed);
        $stmt->bindParam(':stock', $this->stock);
        $stmt->bindParam(':preuni', $this->preuni);
        $stmt->bindParam(':cosuni', $this->cosuni);

        if ($stmt->execute()){
            return true;
        } else
            return false;
        
    }

    public function delete($id){
        $sql = "delete from productos where idProducto = :codigo";
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':codigo', $id);
        $stmt->execute();
        
        if ($stmt->rowCount()==1) {
            return true;
        } else {
            return false;
        }

    }

    public function setIdproducto($id){
        $this->idproducto = $id;
    }
    public function setNomprodu($nom){
        $this->nomprodu = $nom;
    }
    public function setUnimed($und){
        $this->unimed = $und;
    }
    public function setStock($stk){
        $this->stock = $stk;
    }
    public function setPreuni($pre){
        $this->preuni = $pre;
    }
    public function setCosuni($cos){
        $this->cosuni = $cos;
    }
    public function getIdproducto(){
        return $this->idproducto;
    }
    public function getNomprodu(){
        return $this->nomprodu;
    }
    public function getUnimed(){
        return $this->unimed;
    }
    public function getStock(){
        return $this->stock;
    }
    public function getPreuni(){
        return $this->preuni;
    }
    public function getCosuni(){
        return $this->cosuni;
    }

}