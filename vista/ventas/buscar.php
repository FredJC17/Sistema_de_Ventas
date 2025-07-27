<?php
session_start();
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar Factura</title>
    <link rel="stylesheet" href="/sisventas/vista/layout/css/formulario.css">
</head>
<body>
    <div class="modal-overlay active" id="buscarModal">
        <div class="modal-content">
            <span class="close-btn" onclick="window.location.href='listado.php'">&times;</span>
            <h2>Buscar Factura <?= $tipo === 'id' ? 'por ID' : ($tipo === 'cliente' ? 'por Cliente' : ($tipo === 'condicion' ? 'por Condición' : '')) ?></h2>
            <form method="post" action="buscar.php?tipo=<?= htmlspecialchars($tipo) ?>">
                <div class="form-group">
                    <?php if ($tipo === 'id'): ?>
                        <label for="buscarId">ID Factura</label>
                        <input type="text" id="buscarId" name="buscarId" maxlength="11" required placeholder="Ingrese el ID de la factura">
                    <?php elseif ($tipo === 'cliente'): ?>
                        <label for="buscarCliente">Cliente</label>
                        <input type="text" id="buscarCliente" name="buscarCliente" maxlength="128" required placeholder="Ingrese el nombre o DNI del cliente">
                    <?php elseif ($tipo === 'condicion'): ?>
                        <label for="buscarCondicion">Condición</label>
                        <input type="text" id="buscarCondicion" name="buscarCondicion" maxlength="10" required placeholder="Contado o Crédito">
                    <?php else: ?>
                        <div style="color:#fff;">Seleccione una opción válida.</div>
                    <?php endif; ?>
                </div>
                <div class="buttons-container">
                    <button type="button" class="btn-cancel" onclick="window.location.href='listado.php'">Cancelar</button>
                    <?php if ($tipo === 'id' || $tipo === 'cliente' || $tipo === 'condicion'): ?>
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
        $stmt = $con->prepare("SELECT f.*, c.nomCliente FROM facturas f LEFT JOIN clientes c ON f.idCliente = c.idCliente WHERE f.idFactura = ?");
        $stmt->execute([$id]);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $popup = $resultados ? [
            'type' => 'success',
            'title' => 'Búsqueda exitosa',
            'message' => 'Factura encontrada correctamente.'
        ] : [
            'type' => 'error',
            'title' => 'No encontrado',
            'message' => 'No se encontró ninguna factura con ese ID.'
        ];
    } elseif ($tipo === 'cliente' && isset($_POST['buscarCliente'])) {
        $cliente = trim($_POST['buscarCliente']);
        $stmt = $con->prepare("SELECT f.*, c.nomCliente FROM facturas f LEFT JOIN clientes c ON f.idCliente = c.idCliente WHERE c.nomCliente LIKE ? OR f.idCliente LIKE ?");
        $stmt->execute(['%' . $cliente . '%', '%' . $cliente . '%']);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $popup = $resultados ? [
            'type' => 'success',
            'title' => 'Búsqueda exitosa',
            'message' => 'Factura(s) encontrada(s) correctamente.'
        ] : [
            'type' => 'error',
            'title' => 'No encontrado',
            'message' => 'No se encontró ninguna factura para ese cliente.'
        ];
    } elseif ($tipo === 'condicion' && isset($_POST['buscarCondicion'])) {
        $cond = strtolower(trim($_POST['buscarCondicion']));
        $condicion = ($cond === 'contado' || $cond === '1') ? '1' : (($cond === 'credito' || $cond === 'crédito' || $cond === '2') ? '2' : '');
        if ($condicion) {
            $stmt = $con->prepare("SELECT f.*, c.nomCliente FROM facturas f LEFT JOIN clientes c ON f.idCliente = c.idCliente WHERE f.idCondicion = ?");
            $stmt->execute([$condicion]);
        } else {
            $stmt = $con->prepare("SELECT f.*, c.nomCliente FROM facturas f LEFT JOIN clientes c ON f.idCliente = c.idCliente WHERE f.idCondicion = ? OR f.idCondicion = ?");
            $stmt->execute(['1', '2']);
        }
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $popup = $resultados ? [
            'type' => 'success',
            'title' => 'Búsqueda exitosa',
            'message' => 'Factura(s) encontrada(s) correctamente.'
        ] : [
            'type' => 'error',
            'title' => 'No encontrado',
            'message' => 'No se encontró ninguna factura con esa condición.'
        ];
    }
    $_SESSION['resultados_busqueda'] = $resultados;
    $_SESSION['popup'] = $popup;
    header("Location: listado.php?busqueda=1");
    exit();
}
?>
</body>
</html>