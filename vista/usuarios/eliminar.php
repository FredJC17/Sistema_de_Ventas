<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/sisventas/includes/db.php";

$idUsuario = isset($_GET['id']) ? $_GET['id'] : '';
$nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = new DBConection();
        $con = $db->conectar();
        $stmt = $con->prepare("DELETE FROM usuarios WHERE idUsuario = ?");
        $stmt->execute([$_POST['idUsuario']]);
        $_SESSION['popup'] = [
            'type' => 'success',
            'title' => 'Usuario eliminado',
            'message' => 'El usuario fue eliminado correctamente.'
        ];
        header("Location: listado.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['popup'] = [
            'type' => 'error',
            'title' => 'Error inesperado',
            'message' => 'No se pudo eliminar el usuario. ' . $e->getMessage()
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
    <title>Eliminar Usuario</title>
    <link rel="stylesheet" href="/sisventas/vista/layout/css/formulario.css">
</head>
<body>
    <div class="modal-overlay active" id="eliminarModal">
        <div class="modal-content">
            <span class="close-btn" onclick="window.location.href='listado.php'">&times;</span>
            <h2>Confirmar eliminación</h2>
            <p style="margin: 30px 0 40px 0; font-size: 18px; color: #fff;">
                ¿Está seguro que desea eliminar al usuario <b><?= htmlspecialchars($nombre) ?></b>?
            </p>
            <div class="buttons-container">
                <button type="button" class="btn-cancel" onclick="window.location.href='listado.php'">Cancelar</button>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="idUsuario" value="<?= htmlspecialchars($idUsuario) ?>">
                    <button type="submit" class="btn-save" style="background:#e53935; color:#fff;">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>