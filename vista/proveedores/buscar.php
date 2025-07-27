<?php
session_start();
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar Proveedor</title>
    <link rel="stylesheet" href="/sisventas/vista/layout/css/formulario.css">
</head>
<body>
    <div class="modal-overlay active" id="buscarModal">
        <div class="modal-content">
            <span class="close-btn" onclick="window.location.href='listado.php'">&times;</span>
            <h2>Buscar Proveedor <?= $tipo === 'id' ? 'por ID' : 'por Nombre' ?></h2>
            <form method="post" action="buscar.php?tipo=<?= htmlspecialchars($tipo) ?>">
                <div class="form-group">
                    <?php if ($tipo === 'id'): ?>
                        <label for="buscarId">ID</label>
                        <input type="text" id="buscarId" name="buscarId" maxlength="3" required placeholder="Ingrese el ID del proveedor">
                    <?php elseif ($tipo === 'nombre'): ?>
                        <label for="buscarNombre">Nombre</label>
                        <input type="text" id="buscarNombre" name="buscarNombre" maxlength="128" required placeholder="Ingrese el nombre del proveedor">
                    <?php else: ?>
                        <div style="color:#fff;">Seleccione una opción válida.</div>
                    <?php endif; ?>
                </div>
                <div class="buttons-container">
                    <button type="button" class="btn-cancel" onclick="window.location.href='listado.php'">Cancelar</button>
                    <?php if ($tipo === 'id' || $tipo === 'nombre'): ?>
                        <button type="submit" class="btn-save">Buscar</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include_once $_SERVER['DOCUMENT_ROOT']."/sisventas/includes/db.php";
    $con = (new DBConection())->conectar();
    $resultados = [];
    $popup = [];
    if ($tipo === 'id' && isset($_POST['buscarId'])) {
        $id = trim($_POST['buscarId']);
        $stmt = $con->prepare("SELECT * FROM proveedores WHERE idProveedor = ?");
        $stmt->execute([$id]);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($resultados) {
            $popup = [
                'type' => 'success',
                'title' => 'Búsqueda exitosa',
                'message' => 'Proveedor encontrado correctamente.'
            ];
        } else {
            $popup = [
                'type' => 'error',
                'title' => 'No encontrado',
                'message' => 'No se encontró ningún proveedor con ese ID.'
            ];
        }
    } elseif ($tipo === 'nombre' && isset($_POST['buscarNombre'])) {
        $nombre = trim($_POST['buscarNombre']);
        $stmt = $con->prepare("SELECT * FROM proveedores WHERE nomProveedor LIKE ?");
        $stmt->execute(['%' . $nombre . '%']);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($resultados) {
            $popup = [
                'type' => 'success',
                'title' => 'Búsqueda exitosa',
                'message' => 'Proveedor encontrado correctamente.'
            ];
        } else {
            $popup = [
                'type' => 'error',
                'title' => 'No encontrado',
                'message' => 'No se encontró ningún proveedor con ese nombre.'
            ];
        }
    }
    $_SESSION['resultados_busqueda'] = $resultados;
    $_SESSION['popup'] = $popup;
    header("Location: listado.php?busqueda=1");
    exit();
}
?>
</body>
</html>