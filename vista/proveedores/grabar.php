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

$idProveedor    = validar('idProveedor', 3, true);
$nomProveedor   = validar('nomProveedor', 128, true);
$rucProveedor   = validar('rucProveedor', 11);
$dirProveedor   = validar('dirProveedor', 128);
$telProveedor   = validar('telProveedor', 9, true);
$emailProveedor = validar('emailProveedor', 64);

if (!$idProveedor || !$nomProveedor || !$telProveedor) {
    $_SESSION['popup'] = [
        'type' => 'error',
        'title' => 'Error al guardar',
        'message' => 'Campos obligatorios vacíos o datos demasiado largos.'
    ];
    header("Location: listado.php");
    exit();
}

if (!preg_match('/^\d{1,3}$/', $idProveedor)) {
    $_SESSION['popup'] = [
        'type' => 'error',
        'title' => 'Error en ID',
        'message' => 'El ID debe contener solo números y hasta 3 dígitos.'
    ];
    header("Location: listado.php");
    exit();
}
if (!preg_match('/^\d{1,9}$/', $telProveedor)) {
    $_SESSION['popup'] = [
        'type' => 'error',
        'title' => 'Error en Teléfono',
        'message' => 'El teléfono debe contener solo números y hasta 9 dígitos.'
    ];
    header("Location: listado.php");
    exit();
}

try {
    $db = new DBConection();
    $con = $db->conectar();

    $stmt = $con->prepare("SELECT idProveedor FROM proveedores WHERE idProveedor = ?");
    $stmt->execute([$idProveedor]);
    if ($stmt->fetch()) {
        $_SESSION['popup'] = [
            'type' => 'warning',
            'title' => 'Proveedor existente',
            'message' => 'Ya existe un proveedor con ese ID.'
        ];
        header("Location: listado.php");
        exit();
    }

    $sql = "INSERT INTO proveedores (idProveedor, nomProveedor, rucProveedor, dirProveedor, telProveedor, emailProveedor)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->execute([
        $idProveedor,
        $nomProveedor,
        $rucProveedor ?: null,
        $dirProveedor ?: null,
        $telProveedor,
        $emailProveedor ?: null
    ]);

    $_SESSION['popup'] = [
        'type' => 'success',
        'title' => 'Proveedor guardado',
        'message' => 'El proveedor fue registrado correctamente.'
    ];
    header("Location: listado.php");
    exit();

} catch (Exception $e) {
    $_SESSION['popup'] = [
        'type' => 'error',
        'title' => 'Error inesperado',
        'message' => 'No se pudo guardar el proveedor. ' . $e->getMessage()
    ];
    header("Location: listado.php");
    exit();
}