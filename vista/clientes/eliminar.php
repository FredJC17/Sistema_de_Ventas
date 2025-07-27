<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/sisventas/includes/db.php";

$idCliente = isset($_GET['id']) ? $_GET['id'] : '';
$nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = new DBConection();
        $con = $db->conectar();
        $stmt = $con->prepare("DELETE FROM clientes WHERE idCliente = ?");
        $stmt->execute([$_POST['idCliente']]);
        $_SESSION['popup'] = [
            'type' => 'success',
            'title' => 'Cliente eliminado',
            'message' => 'El cliente fue eliminado correctamente.'
        ];
        header("Location: listado.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['popup'] = [
            'type' => 'error',
            'title' => 'Error inesperado',
            'message' => 'No se pudo eliminar el cliente. ' . $e->getMessage()
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
    <title>Eliminar Cliente</title>
    <link rel="stylesheet" href="/sisventas/vista/layout/css/formulario.css">
</head>
<body>
    <div class="modal-overlay active" id="eliminarModal">
        <div class="modal-content">
            <span class="close-btn" onclick="window.location.href='listado.php'">&times;</span>
            <h2>Confirmar eliminación</h2>
            <p style="margin: 30px 0 40px 0; font-size: 18px; color: #fff;">
                ¿Está seguro que desea eliminar al cliente <b><?= htmlspecialchars($nombre) ?></b>?
            </p>
            <div class="buttons-container">
                <button type="button" class="btn-cancel" onclick="window.location.href='listado.php'">Cancelar</button>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="idCliente" value="<?= htmlspecialchars($idCliente) ?>">
                    <button type="submit" class="btn-save" style="background:#e53935; color:#fff;">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>