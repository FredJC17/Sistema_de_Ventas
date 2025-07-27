<?php

include 'config.php';

class DBConection{

public function conectar(){
    try {
        $pdo = new PDO("mysql:host=" .DBHOST. ";dbname=" .DBNAME. ";charset=utf8", DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
        echo"conectado";
    } catch (PDOException $e) {
        echo "Error en la conexión: " . $e->getMessage();
    }
}
}
?>
