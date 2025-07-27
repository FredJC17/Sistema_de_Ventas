<?php
session_start();
$idCondicion = isset($_POST['idCondicion']) ? $_POST['idCondicion'] : (isset($_SESSION['idCondicion']) ? $_SESSION['idCondicion'] : '');
$_SESSION['idCondicion'] = $idCondicion;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar'])) {
    include_once $_SERVER['DOCUMENT_ROOT']."/sisventas/includes/db.php";
    $db = new DBConection();
    $con = $db->conectar();

    $idCliente = $_SESSION['cliente_dni'];
    $idUsuario = $_SESSION['idUsuario']; 
    $fecha = date('Y-m-d');
    $fechaReg = date('Y-m-d H:i:s');
    $valorVenta = $_SESSION['total_pago'];
    $igv = round($valorVenta * 0.18 / 1.18, 2);
    $stmt = $con->prepare("INSERT INTO facturas (fecha, idCliente, idUsuario, fechaReg, idCondicion, valorVenta, igv) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$fecha, $idCliente, $idUsuario, $fechaReg, $idCondicion, $valorVenta, $igv]);
    $idFactura = $con->lastInsertId();
    $carrito = $_SESSION['carrito'];
    foreach ($carrito as $item) {
        $stmt = $con->prepare("INSERT INTO detallefactura (idFactura, idProducto, cant, cosuni, preuni) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $idFactura,
            $item['id'],
            $item['cantidad'],
            0,
            $item['precio']
        ]);
        $stmt = $con->prepare("UPDATE productos SET stock = stock - ? WHERE idProducto = ?");
        $stmt->execute([$item['cantidad'], $item['id']]);
    }

    unset($_SESSION['carrito']);
    $_SESSION['popup'] = [
        'type' => 'success',
        'title' => '¡Compra realizada con éxito!',
        'message' => 'La factura ha sido registrada correctamente en el sistema.'
    ];
    header("Location: store.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Pago</title>
    <link rel="stylesheet" href="/sisventas/vista/layout/css/formulario.css">
</head>
<body>
    <div class="modal-overlay active">
        <div class="modal-content">
            <h2>Confirmar Pago</h2>
            <form method="POST">
                <div class="form-group">
                    <label>¿Seguro que desea cancelar el pago <b><?= $idCondicion == '1' ? 'Contado' : 'Crédito' ?></b>?</label>
                </div>
                <div class="buttons-container">
                    <a href="factura.php" class="btn-cancel">Cancelar</a>
                    <button type="submit" name="confirmar" class="btn-save">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>