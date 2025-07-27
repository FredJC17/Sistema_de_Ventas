<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/sisventas/includes/db.php";

$idProveedor = isset($_GET['id']) ? $_GET['id'] : '';
$proveedor = null;

if ($idProveedor) {
    $db = new DBConection();
    $con = $db->conectar();
    $stmt = $con->prepare("SELECT * FROM proveedores WHERE idProveedor = ?");
    $stmt->execute([$idProveedor]);
    $proveedor = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$proveedor) {
        $_SESSION['popup'] = [
            'type' => 'error',
            'title' => 'Proveedor no encontrado',
            'message' => 'No se encontró el proveedor solicitado.'
        ];
        header("Location: listado.php");
        exit();
    }
} else {
    $_SESSION['popup'] = [
        'type' => 'error',
        'title' => 'Error',
        'message' => 'No se especificó el proveedor a editar.'
    ];
    header("Location: listado.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function validar($campo, $max, $obligatorio = false) {
        if (!isset($_POST[$campo])) return !$obligatorio;
        $valor = trim($_POST[$campo]);
        if ($obligatorio && $valor === '') return false;
        if (strlen($valor) > $max) return false;
        return $valor;
    }
    $nomProveedor   = validar('nomProveedor', 128, true);
    $rucProveedor   = validar('rucProveedor', 11);
    $dirProveedor   = validar('dirProveedor', 128);
    $telProveedor   = validar('telProveedor', 9, true);
    $emailProveedor = validar('emailProveedor', 64);

    if (!$nomProveedor || !$telProveedor) {
        $_SESSION['popup'] = [
            'type' => 'error',
            'title' => 'Error al actualizar',
            'message' => 'Campos obligatorios vacíos o datos demasiado largos.'
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
        $stmt = $con->prepare("UPDATE proveedores SET nomProveedor=?, rucProveedor=?, dirProveedor=?, telProveedor=?, emailProveedor=? WHERE idProveedor=?");
        $stmt->execute([
            $nomProveedor,
            $rucProveedor ?: null,
            $dirProveedor ?: null,
            $telProveedor,
            $emailProveedor ?: null,
            $idProveedor
        ]);
        $_SESSION['popup'] = [
            'type' => 'success',
            'title' => 'Proveedor actualizado',
            'message' => 'La información del proveedor fue actualizada correctamente.'
        ];
        header("Location: listado.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['popup'] = [
            'type' => 'error',
            'title' => 'Error inesperado',
            'message' => 'No se pudo actualizar el proveedor. ' . $e->getMessage()
        ];
        header("Location: listado.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Proveedor</title>
    <link rel="stylesheet" href="/sisventas/vista/layout/css/formulario.css">
    <style>
        @media (max-width: 700px) {
            .modal-content {
                max-height: 90vh;
                overflow-y: auto;
            }
        }
    </style>
</head>
<body>
    <div class="modal-overlay active" id="proveedorModal">
        <div class="modal-content">
            <span class="close-btn" onclick="window.location.href='listado.php'">&times;</span>
            <h2>Editar Proveedor</h2>
            <form id="proveedorForm" method="POST" action="update.php?id=<?= htmlspecialchars($idProveedor) ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label for="idProveedor">ID</label>
                        <input type="text" id="idProveedor" name="idProveedor" value="<?= htmlspecialchars($proveedor['idProveedor']) ?>" required maxlength="3" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nomProveedor">Nombre</label>
                        <input type="text" id="nomProveedor" name="nomProveedor" value="<?= htmlspecialchars($proveedor['nomProveedor']) ?>" required maxlength="128">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="rucProveedor">RUC</label>
                        <input type="text" id="rucProveedor" name="rucProveedor" value="<?= htmlspecialchars($proveedor['rucProveedor']) ?>" maxlength="11">
                    </div>
                    <div class="form-group">
                        <label for="dirProveedor">Dirección</label>
                        <input type="text" id="dirProveedor" name="dirProveedor" value="<?= htmlspecialchars($proveedor['dirProveedor']) ?>" maxlength="128">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="telProveedor">Teléfono</label>
                        <input type="text" id="telProveedor" name="telProveedor" value="<?= htmlspecialchars($proveedor['telProveedor']) ?>" required maxlength="9">
                    </div>
                    <div class="form-group">
                        <label for="emailProveedor">Email</label>
                        <input type="email" id="emailProveedor" name="emailProveedor" value="<?= htmlspecialchars($proveedor['emailProveedor']) ?>" maxlength="64">
                    </div>
                </div>
                <div class="buttons-container">
                    <button type="button" class="btn-cancel" onclick="window.location.href='listado.php'">✖ Cancelar</button>
                    <button type="submit" class="btn-save">✓ Actualizar Proveedor</button>
                </div>
            </form>
        </div>
    </div>