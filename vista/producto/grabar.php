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

$idProducto    = validar('idProducto', 10, true);
$idProveedor   = validar('idProveedor', 3, true);
$idCategoria   = validar('idCategoria', 2, true);
$nomProducto   = validar('nomProducto', 128, true);
$unimed        = validar('unimed', 15, true);
$stock         = validar('stock', 11, true);
$cosuni        = validar('cosuni', 20, true);
$preuni        = validar('preuni', 20, true);
$estado        = "1";

$imagenProducto = null;
if (isset($_FILES['imagenProducto']) && $_FILES['imagenProducto']['error'] === UPLOAD_ERR_OK) {
    $imagenProducto = file_get_contents($_FILES['imagenProducto']['tmp_name']);
}

if (
    !$idProducto || !$idProveedor || !$idCategoria || !$nomProducto ||
    !$unimed || $stock === false || $cosuni === false || $preuni === false
) {
    $_SESSION['popup'] = [
        'type' => 'error',
        'title' => 'Error al guardar',
        'message' => 'Campos obligatorios vacíos o datos demasiado largos.'
    ];
    header("Location: listado.php");
    exit();
}

try {
    $db = new DBConection();
    $con = $db->conectar();

    $stmt = $con->prepare("SELECT idProducto FROM productos WHERE idProducto = ?");
    $stmt->execute([$idProducto]);
    if ($stmt->fetch()) {
        $_SESSION['popup'] = [
            'type' => 'warning',
            'title' => 'Producto existente',
            'message' => 'Ya existe un producto con ese ID.'
        ];
        header("Location: listado.php");
        exit();
    }

    $sql = "INSERT INTO productos 
        (idProducto, idProveedor, idCategoria, nomProducto, unimed, stock, cosuni, preuni, estado, imagenProducto)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->execute([
        $idProducto,
        $idProveedor,
        $idCategoria,
        $nomProducto,
        $unimed,
        $stock,
        $cosuni,
        $preuni,
        $estado,
        $imagenProducto
    ]);

    $_SESSION['popup'] = [
        'type' => 'success',
        'title' => 'Producto guardado',
        'message' => 'El producto fue registrado correctamente.'
    ];
    header("Location: listado.php");
    exit();

} catch (Exception $e) {
    $_SESSION['popup'] = [
        'type' => 'error',
        'title' => 'Error inesperado',
        'message' => 'No se pudo guardar el producto. ' . $e->getMessage()
    ];
    header("Location: listado.php");
    exit();
}



