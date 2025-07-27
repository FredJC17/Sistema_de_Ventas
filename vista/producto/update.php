<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/sisventas/includes/db.php";

$idProducto = isset($_GET['id']) ? $_GET['id'] : '';
$producto = null;

if ($idProducto) {
    $db = new DBConection();
    $con = $db->conectar();
    $stmt = $con->prepare("SELECT * FROM productos WHERE idProducto = ?");
    $stmt->execute([$idProducto]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$producto) {
        $_SESSION['popup'] = [
            'type' => 'error',
            'title' => 'Producto no encontrado',
            'message' => 'No se encontró el producto solicitado.'
        ];
        header("Location: listado.php");
        exit();
    }
} else {
    $_SESSION['popup'] = [
        'type' => 'error',
        'title' => 'Error',
        'message' => 'No se especificó el producto a editar.'
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
    $idProveedor   = validar('idProveedor', 3, true);
    $idCategoria   = validar('idCategoria', 2, true);
    $nomProducto   = validar('nomProducto', 128, true);
    $unimed        = validar('unimed', 15, true);
    $stock         = validar('stock', 11, true);
    $cosuni        = validar('cosuni', 20, true);
    $preuni        = validar('preuni', 20, true);
    $estado        = "1";

    $imagenProducto = $producto['imagenProducto'];
    if (isset($_FILES['imagenProducto']) && $_FILES['imagenProducto']['error'] === UPLOAD_ERR_OK) {
        $imagenProducto = file_get_contents($_FILES['imagenProducto']['tmp_name']);
    }

    if (
        !$idProveedor || !$idCategoria || !$nomProducto ||
        !$unimed || $stock === false || $cosuni === false || $preuni === false
    ) {
        $_SESSION['popup'] = [
            'type' => 'error',
            'title' => 'Error al actualizar',
            'message' => 'Campos obligatorios vacíos o datos demasiado largos.'
        ];
        header("Location: listado.php");
        exit();
    }

    try {
        $stmt = $con->prepare("UPDATE productos SET 
            idProveedor=?, idCategoria=?, nomProducto=?, unimed=?, stock=?, cosuni=?, preuni=?, estado=?, imagenProducto=?
            WHERE idProducto=?");
        $stmt->execute([
            $idProveedor,
            $idCategoria,
            $nomProducto,
            $unimed,
            $stock,
            $cosuni,
            $preuni,
            $estado,
            $imagenProducto,
            $idProducto
        ]);
        $_SESSION['popup'] = [
            'type' => 'success',
            'title' => 'Producto actualizado',
            'message' => 'La información del producto fue actualizada correctamente.'
        ];
        header("Location: listado.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['popup'] = [
            'type' => 'error',
            'title' => 'Error inesperado',
            'message' => 'No se pudo actualizar el producto. ' . $e->getMessage()
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
    <title>Editar Producto</title>
    <link rel="stylesheet" href="/sisventas/vista/layout/css/formulario.css">
    <!-- diseños especiales para la adaptacion de la pantalla-->
    <style>
        .modal-content {
            max-width: 540px;
            margin: 30px auto;
            padding: 18px 10px 10px 10px;
            border-radius: 10px;
            background: #181818;
            box-shadow: 0 0 12px #0008;
        }
        .modal-content .form-row {
            display: flex;
            gap: 8px;
            margin-bottom: 10px;
        }
        .modal-content .form-group {
            flex: 1 1 0;
            min-width: 0;
        }
        .modal-content label {
            font-size: 0.98em;
            margin-bottom: 2px;
            display: block;
        }
        .modal-content input,
        .modal-content select {
            width: 100%;
            padding: 5px 7px;
            font-size: 0.98em;
            border-radius: 5px;
            border: 1px solid #00e5ff;
            background: #222;
            color: #fff;
            box-sizing: border-box;
        }
        .modal-content input[type="file"] {
            padding: 1px 2px;
            background: #222;
            color: #fff;
            border: none;
        }
        .modal-content h2 {
            font-size: 1.25em;
            margin-bottom: 12px;
            text-align: center;
        }
        .buttons-container {
            display: flex;
            gap: 12px;
            margin-top: 12px;
            justify-content: center;
        }
        .btn-cancel, .btn-save {
            font-size: 1em;
            padding: 8px 18px;
            border-radius: 7px;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-cancel {
            background: #666;
            color: #fff;
        }
        .btn-save {
            background: #00e5ff;
            color: #222;
        }
        @media (max-width: 700px) {
            .modal-content {
                max-width: 98vw;
                padding: 8px 1vw;
            }
            .modal-content .form-row {
                flex-wrap: wrap;
            }
            .modal-content .form-group {
                flex: 1 1 100%;
            }
        }
    </style>
</head>
<body>
    <div class="modal-overlay active" id="productoModal">
        <div class="modal-content">
            <span class="close-btn" onclick="window.location.href='listado.php'">&times;</span>
            <h2>Editar Producto</h2>
            <form method="POST" enctype="multipart/form-data" action="update.php?id=<?= htmlspecialchars($idProducto) ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label for="idProducto">ID Producto</label>
                        <input type="text" id="idProducto" name="idProducto" maxlength="10" value="<?= htmlspecialchars($producto['idProducto']) ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="idProveedor">ID Proveedor</label>
                        <input type="text" id="idProveedor" name="idProveedor" maxlength="3" required value="<?= htmlspecialchars($producto['idProveedor']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="idCategoria">ID Categoría</label>
                        <input type="text" id="idCategoria" name="idCategoria" maxlength="2" required value="<?= htmlspecialchars($producto['idCategoria']) ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="nomProducto">Nombre</label>
                        <input type="text" id="nomProducto" name="nomProducto" maxlength="128" required value="<?= htmlspecialchars($producto['nomProducto']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="unimed">Unidad Medida</label>
                        <input type="text" id="unimed" name="unimed" maxlength="15" required value="<?= htmlspecialchars($producto['unimed']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <input type="number" id="stock" name="stock" min="0" required value="<?= htmlspecialchars($producto['stock']) ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="cosuni">Costo Unitario</label>
                        <input type="number" id="cosuni" name="cosuni" step="0.01" min="0" required value="<?= htmlspecialchars($producto['cosuni']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="preuni">Precio Unitario</label>
                        <input type="number" id="preuni" name="preuni" step="0.01" min="0" required value="<?= htmlspecialchars($producto['preuni']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <select id="estado" name="estado" disabled>
                            <option value="1" selected>Activo</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="imagenProducto">Imagen (opcional)</label>
                        <input type="file" id="imagenProducto" name="imagenProducto" accept="image/*">
                        <?php if (!empty($producto['imagenProducto'])): ?>
                            <div style="margin-top:8px;">
                                <img src="data:image/jpeg;base64,<?= base64_encode($producto['imagenProducto']) ?>" alt="Imagen actual" style="max-width:80px; max-height:80px; border-radius:8px;">
                                <span style="color:#00e5ff; font-size:12px;">Imagen actual</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="buttons-container">
                    <button type="button" class="btn-cancel" onclick="window.location.href='listado.php'">✖ Cancelar</button>
                    <button type="submit" class="btn-save">✓ Actualizar Producto</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>