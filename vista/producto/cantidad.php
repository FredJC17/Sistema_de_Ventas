<?php
session_start();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$precio = isset($_GET['precio']) ? $_GET['precio'] : '';
$stock = isset($_GET['stock']) ? intval($_GET['stock']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cantidad = intval($_POST['cantidad']);
    if ($id && $nombre && $precio && $cantidad > 0 && $cantidad <= $stock) {
        $producto = [
            'id' => $id,
            'nombre' => $nombre,
            'precio' => floatval($precio),
            'cantidad' => $cantidad
        ];
        if (!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];
        $_SESSION['carrito'][] = $producto;
        header("Location: carrito.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar al carrito</title>
    <link rel="stylesheet" href="/sisventas/vista/layout/css/formulario.css">
</head>
<body>
    <div class="modal-overlay active">
        <div class="modal-content">
            <h2>Agregar al carrito</h2>
            <?php if ($stock <= 0): ?>
                <div class="form-group">
                    <p style="color:red;">Este producto no tiene stock disponible.</p>
                </div>
                <div class="buttons-container">
                    <a href="store.php" class="btn-cancel">Volver</a>
                </div>
            <?php else: ?>
            <form method="POST">
                <div class="form-group">
                    <label>Producto</label>
                    <input type="text" value="<?= htmlspecialchars($nombre) ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Precio Unitario</label>
                    <input type="text" value="S/ <?= number_format($precio, 2) ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="cantidad">Cantidad (Stock disponible: <?= $stock ?>)</label>
                    <input type="number" id="cantidad" name="cantidad" min="1" max="<?= $stock ?>" required>
                </div>
                <div class="buttons-container">
                    <a href="store.php" class="btn-cancel">Cancelar</a>
                    <button type="submit" class="btn-save">Agregar</button>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>