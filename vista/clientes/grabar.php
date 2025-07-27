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

$idCliente    = validar('idCliente', 10, true);
$nomCliente   = validar('nomCliente', 128, true);
$rucCliente   = validar('rucCliente', 11);
$dirCliente   = validar('dirCliente', 128);
$telCliente   = validar('telCliente', 9, true);
$emailCliente = validar('emailCliente', 64);

if (!$idCliente || !$nomCliente || !$telCliente) {
    $_SESSION['popup'] = [
        'type' => 'error',
        'title' => 'Error al guardar',
        'message' => 'Campos obligatorios vacíos o datos demasiado largos.'
    ];
    header("Location: listado.php");
    exit();
}

if (!preg_match('/^\d{1,10}$/', $idCliente)) {
    $_SESSION['popup'] = [
        'type' => 'error',
        'title' => 'Error en DNI',
        'message' => 'El DNI debe contener solo números y hasta 10 dígitos.'
    ];
    header("Location: listado.php");
    exit();
}
if (!preg_match('/^\d{1,9}$/', $telCliente)) {
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

    $stmt = $con->prepare("SELECT idCliente FROM clientes WHERE idCliente = ?");
    $stmt->execute([$idCliente]);
    if ($stmt->fetch()) {
        $_SESSION['popup'] = [
            'type' => 'warning',
            'title' => 'Cliente existente',
            'message' => 'Ya existe un cliente con ese DNI.'
        ];
        header("Location: listado.php");
        exit();
    }
    $sql = "INSERT INTO clientes (idCliente, nomCliente, rucCliente, dirCliente, telCliente, emailCliente)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->execute([
        $idCliente,
        $nomCliente,
        $rucCliente ?: null,
        $dirCliente ?: null,
        $telCliente,
        $emailCliente ?: null
    ]);

    $_SESSION['popup'] = [
        'type' => 'success',
        'title' => 'Cliente guardado',
        'message' => 'El cliente fue registrado correctamente.'
    ];
    header("Location: listado.php");
    exit();

} catch (Exception $e) {
    $_SESSION['popup'] = [
        'type' => 'error',
        'title' => 'Error inesperado',
        'message' => 'No se pudo guardar el cliente. ' . $e->getMessage()
    ];
    header("Location: listado.php");
    exit();
}