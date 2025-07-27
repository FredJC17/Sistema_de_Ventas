<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'].'/sisventas/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = $_POST['idUsuario'];
    $password = $_POST['password'];

    $cn = new DBConection();
    $con = $cn->conectar();

    $sql = "SELECT * FROM usuarios WHERE idUsuario = :idUsuario AND password = :password AND estado = '1'";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':idUsuario', $idUsuario);
    $stmt->bindParam(':password', $password);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION['usuario_logueado'] = true;
        $_SESSION['idUsuario'] = $idUsuario;
        header("Location: /sisventas/index.php");
        exit();
    } else {
        header("Location: /sisventas/login.php?error=1");
        exit();
    }
}
?> 