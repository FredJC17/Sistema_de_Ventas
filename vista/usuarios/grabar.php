<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/sisventas/includes/db.php";

function validar($campo, $max, $obligatorio = false) {
    if (!isset($_POST[$campo])) return !$obligatorio;
    $valor = trim($_POST[$campo]);
    if ($obligatorio && $valor === '') return false;
    if (strlen($valor) > $max) return false;
    return $valor;
}

$idUsuario  = validar('idUsuario', 3, true);
$nomUsuario = validar('nomUsuario', 15, true);
$password   = validar('password', 255, true);
$apellidos  = validar('apellidos', 64, true);
$nombres    = validar('nombres', 64, true);
$email      = validar('email', 64);
$estado     = validar('estado', 1, true);

if (!$idUsuario || !$nomUsuario || !$password || !$apellidos || !$nombres || !$estado) {
    $_SESSION['popup'] = [
        'type' => 'error',
        'title' => 'Error al guardar',
        'message' => 'Campos obligatorios vacíos o datos demasiado largos.'
    ];
    header("Location: listado.php");
    exit();
}

if (!preg_match('/^\d{1,3}$/', $idUsuario)) {
    $_SESSION['popup'] = [
        'type' => 'error',
        'title' => 'Error en ID',
        'message' => 'El ID debe contener solo números y hasta 3 dígitos.'
    ];
    header("Location: listado.php");
    exit();
}

try {
    $db = new DBConection();
    $con = $db->conectar();

    $stmt = $con->prepare("SELECT idUsuario FROM usuarios WHERE idUsuario = ?");
    $stmt->execute([$idUsuario]);
    if ($stmt->fetch()) {
        $_SESSION['popup'] = [
            'type' => 'warning',
            'title' => 'Usuario existente',
            'message' => 'Ya existe un usuario con ese ID.'
        ];
        header("Location: listado.php");
        exit();
    }

    $sql = "INSERT INTO usuarios (idUsuario, nomUsuario, password, apellidos, nombres, email, estado)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->execute([
        $idUsuario,
        $nomUsuario,
        $password,
        $apellidos,
        $nombres,
        $email ?: null,
        $estado
    ]);

    $_SESSION['popup'] = [
        'type' => 'success',
        'title' => 'Usuario guardado',
        'message' => 'El usuario fue registrado correctamente.'
    ];
    header("Location: listado.php");
    exit();

} catch (Exception $e) {
    $_SESSION['popup'] = [
        'type' => 'error',
        'title' => 'Error inesperado',
        'message' => 'No se pudo guardar el usuario. ' . $e->getMessage()
    ];
    header("Location: listado.php");
    exit();
}