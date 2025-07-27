<?php
session_start();
$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = trim($_POST['dni']);
    $_SESSION['cliente_dni'] = $dni;
    header("Location: factura.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de compras</title>
    <link rel="stylesheet" href="/sisventas/vista/layout/css/formulario.css">
</head>
<body>
    <div class="modal-overlay active">
        <div class="modal-content">
            <h2>Carrito de compras</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="dni">ID (DNI) del cliente</label>
                    <input type="text" id="dni" name="dni" maxlength="10" required>
                </div>
                <div class="form-group">
                    <label>Productos seleccionados</label>
                    <ul style="list-style:none; padding:0;">
                        <?php foreach ($carrito as $item): ?>
                        <li>
                            <?= htmlspecialchars($item['nombre']) ?> - 
                            <?= $item['cantidad'] ?> x S/ <?= number_format($item['precio'], 2) ?> = 
                            <b>S/ <?= number_format($item['precio'] * $item['cantidad'], 2) ?></b>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="buttons-container">
                    <a href="store.php" class="btn-cancel">Seguir comprando</a>
                    <button type="submit" class="btn-save">Ir a Factura</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>